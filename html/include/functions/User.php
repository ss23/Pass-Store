<?php

/**
 * Functions and object for the "User"
 */

/**
 * Generate a "hash" for the username and a password
 *
 * @param string $Username The username to generate the hash with
 * @param string $Password The password to generate the hash with
 *
 * @return string The resulting hash
 */
function user_hash($Username, $Password) {
	return crypt(user_key($Username, $Password), '$6$rounds=50000$' . substr(hash('sha512', uniqid()), 0, 16));
}

/**
 * Generate a user "key"
 *
 * @param string $Username The username to generate the key with
 * @param string $Password The password to generate the key with
 *
 * @return string The resulting key
 */
function user_key($Username, $Password) {
	return hash('sha512', $Username . $Password);
}

/**
 * Compared a username and password with a hashed password to see if they match
 *
 * @param string $Username       The username to generate the key with
 * @param string $Password       The password to generate the key with
 * @param string $HashedPassword The hashed password to compare against
 *
 * @return bool Whether they matched or not
 */
function user_compare($Username, $Password, $HashedPassword) {
	return (crypt(user_key($Username, $Password), $HashedPassword) == $HashedPassword);
}

/**
 * Check whether a user with this username exists
 *
 * @param string $User Username to check
 *
 * @return bool Whether the user exists or not
 */
function user_exists($User) {
	$stmt = $GLOBALS['pdo']->prepare('
		SELECT count(*)
		FROM `users`
		WHERE `username` = :username');
	$stmt->bindParam(':username', s($User));
	$stmt->execute();

	return ($stmt->fetchColumn() > 0);
}

/**
 * Create a user with the specified username and password
 *
 * @param string $Username The password to generate the key with
 * @param string $Password The username to generate the key with
 *
 * @return string The ID of the new user
 */
function user_create($Username, $Password) {
	if (user_exists($Username)) {
		return false;
	}

	$stmt = $GLOBALS['pdo']->prepare('
		INSERT INTO `users`
		(
			`username`
			, `password`
		) VALUES (
			:username
			, :password
		)');
	$stmt->bindValue(':username', s($Username));
	$stmt->bindValue(':password', user_hash($Username, $Password));
	$stmt->execute();
	$uid = $GLOBALS['pdo']->lastInsertId();
	$stmt->closeCursor();

	// Create a group for the new user
	lib('Group');
	$gid = group_create(s($Username), 'user');
	group_add($gid, $uid);

	return $uid;
}

/**
 * Authenticate a user with the given Username and Password
 *
 * @param string $Username The username
 * @param string $Password The password
 *
 * @return bool Whether the authentication suceeded or not
 */
function user_authenticate($Username, $Password) {
	$stmt = $GLOBALS['pdo']->prepare('
		SELECT `password`
		FROM `users`
		WHERE `username` = :username
	');
	$stmt->bindValue(':username', $Username);
	$stmt->execute();

	if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if (user_compare($Username, $Password, $row['password'])) {
			$user = new User($Username, user_key($Username, $Password));

	                $_SESSION['user'] = &$user;
			session_regenerate_id();
			return true;
		}
	}
	return false;
}

/**
 * Log out the user that's currently logged in
 *
 * @return void
 */
function user_logout() {
	unset($_SESSION['user']);
	session_regenerate_id();
}

/**
 * The User class
 */
class User {
	public $id;
	public $username;
	public $group;

	private $decryptionKey;

	/**
	 * Construct the user class
	 *
	 * @param string $Username The username
	 * @param string $key      The decrpytion key
	 */
	function __construct($Username, $key) {
		if (!user_exists($Username)) {
			return false;
		}
		$this->username = $Username;
		$this->rehash();

		$_SESSION['user'] = &$this;

		$this->decryptionKey = $key;
	}

	/**
	 * Update the users object with new information from the database
	 *
	 * @return void
	 */
	function rehash() {
		$stmt = $GLOBALS['pdo']->prepare('
			SELECT `id`, `username`
			FROM `users`
			WHERE
				`username` = :username
		');
		$stmt->bindParam(':username', $this->username);
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		$stmt->execute();
		$stmt->fetch();
	}

	/**
	 * Decrypt the given encryped data
	 *
	 * @param binary $Encrypted The encrypted data
	 *
	 * @return string Decrypted string
	 */
	function decrypt($Encrypted) {
		// Check that the private key we're using for decryption exists
		if ((is_readable(PATH . '/keys/' . $this->username . '.pem')) && (!empty($this->decryptionKey))) {
			$PrivKey = openssl_get_privatekey(file_get_contents(PATH . '/keys/' . $this->username . '.pem'), $this->decryptionKey);
			openssl_private_decrypt($Encrypted, $Decrypted, $PrivKey);
			return $Decrypted;
		}
		return false;
	}

	/**
	 * Encrypt the given text with this users keys
	 *
	 * @param string $PlainText The text to encrypt
	 *
	 * @return binary The encrypted data
	 */
	function encrypt($PlainText) {
		if (is_readable(PATH . 'keys/' . $this->username . '.pub')) {
			openssl_public_encrypt($PlainText, $Encrypted, file_get_contents(PATH . '/keys/' . $this->username . '.pub'));
			return $Encrypted;
		}
		return false;
	}
}

?>

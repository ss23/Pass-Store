<?php

function user_hash($Password, $Username) {
	return crypt(user_key($Password, $Username), '$6$rounds=50000$' . substr(hash('sha512', uniqid(true)), 0, 16));
}

function user_key($Password, $Username) {
	return hash('sha512', $Password . $Username);
}

function user_compare($Password, $Username, $HashedPassword) {
	return (crypt(user_key($Password, $Username), $HashedPassword) == $HashedPassword);
}

function user_exists($User) {
	global $pdo;

	$stmt = $pdo->prepare('
		SELECT count(*)
		FROM `users`
		WHERE `username` = :username');
	$stmt->bindParam(':username', s($User));
	$stmt->execute();

	return ($stmt->fetchColumn() > 0);
}

function user_create($Username, $Password) {
	global $pdo;

	if (user_exists($Username)) {
		return false;
	}

	$stmt = $pdo->prepare('
		INSERT INTO `users`
		(
			`username`
			, `password`
		) VALUES (
			:username
			, :password
		)');
	$stmt->bindValue(':username', s($Username));
	$stmt->bindValue(':password', user_hash($Password, $Username));
	$stmt->execute();
	$uid = $pdo->lastInsertId();
	$stmt->closeCursor();

	// Create a group for the new user
	lib('Group');
	$gid = group_create(s($Username), 'user');
	group_add($gid, $uid);

	return $uid;
}

function user_authenticate($Username, $Password) {
	global $pdo;

	$stmt = $pdo->prepare('
		SELECT `password`
		FROM `users`
		WHERE `username` = :username
	');
	$stmt->bindValue(':username', $Username);
	$stmt->execute();

	if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if (user_compare($Password, $Username, $row['password'])) {
			$user = new User($Username, user_key($Password, $Username));
	                $_SESSION['user'] = &$user;
			session_regenerate_id();
			return true;
		}
	}
	return false;
}


function user_logout() {
	unset($_SESSION['user']);
}

class User {
	public $id;
	public $username;
	public $group;

	private $decryptionKey;

	function __construct($Username, $key) {
		if (!user_exists($Username)) {
			return false;
		}
		$this->username = $Username;
		$this->rehash();

		$_SESSION['user'] = &$this;

		$this->decryptionKey = $key;
	}

	function rehash() {
		global $pdo;

		$stmt = $pdo->prepare('
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

	function decrypt($Encrypted) {
		// Check that the private key we're using for decryption exists
		if ((is_readable(PATH . '/keys/' . $this->username . '.pem')) && (!empty($this->decryptionKey))) {
			$PrivKey = openssl_get_privatekey(file_get_contents(PATH . '/keys/' . $this->username . '.pem'), $this->decryptionKey);
			openssl_private_decrypt($Encrypted, $Decrypted, $PrivKey);
			return $Decrypted;
		}
		return false;
	}

	function encrypt($PlainText) {
		if (is_readable(PATH . 'keys/' . $this->username . '.pub')) {
			openssl_public_encrypt($PlainText, $Encrypted, file_get_contents(PATH . '/keys/' . $this->username . '.pub'));
			return $Encrypted;
		}
		return false;
	}
}

?>

<?php

/**
 * Password functions
 */

/**
 * Add a password into the database
 *
 * @param string $Name		The name of the password to be entered under
 * @param string $Description Description of the password
 * @param string $Link		Link to the login page for the password this refeers to
 * @param string $Username	Username to log in
 * @param string $Password	Password to log in
 *
 * @return mixed ID of the password entered or false on failure.
 */
function password_add($Name, $Description, $Link, $Username, $Password) {
	$stmt = $GLOBALS['pdo']->prepare('insert into `passwords` set
		`name` = :name,
		`description` = :description,
		`link` = :link,
		`username` = :username
	');
	$stmt->bindValue(':name', $Name);
	$stmt->bindValue(':description', $Description);
	$stmt->bindValue(':link', $Link);
	$stmt->bindValue(':username', $Username);

	$stmt->execute();

	$PasswordID = $GLOBALS['pdo']->lastInsertId();

	// Go through every user, and insert a row for them, using their public key
	$stmt = $GLOBALS['pdo']->prepare('select * from `users`');

	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if (is_readable(PATH . 'keys/' . $row['username'] . '.pub')) {
			openssl_public_encrypt($Password, $Encrypted, file_get_contents(PATH . 'keys/' . $row['username'] . '.pub'));

			$stmt = $GLOBALS['pdo']->prepare('insert into `password_encrypted` set
				`password_id` = :password_id,
				`user_id` = :user_id,
				`blob` = :blob
			');
			$stmt->bindValue(':password_id', $PasswordID, PDO::PARAM_INT);
			$stmt->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
			$stmt->bindValue(':blob', $Encrypted, PDO::PARAM_LOB);

			$stmt->execute();
		}
	}

	return $PasswordID;
}

/**
 * A class for passwords
 * Note, this class does not do any encryption / decryption,
 *  as that would require a user class. Instead, you may
 *  pass this object to and from a User class
 */
class Password {
	public $id;
	public $name;
	public $description;
	public $link;
	public $username;
	public $active;

	/**
	 * Construct the Password class
	 *
	 * @param int $id ID of the password we're constructing.
	 */
	public function __construct($id) {
		if (!ctype_digit($id) and !is_int($id)) {
			return false;
		}
		$this->id = (int)$id;
		$this->rehash();
	}

	/**
	 * Update / set the properties of the class
	 *
	 * @return void
	 */
	public function rehash() {
		    $stmt = $GLOBALS['pdo']->prepare('
		            SELECT `id`, `name`, `description`, `link`, `username`, `active`
		            FROM `passwords`
		            WHERE
		                    `id` = :id
		    ');
		    $stmt->bindParam(':id', $this->id);
		    $stmt->setFetchMode(PDO::FETCH_INTO, $this);
		    $stmt->execute();
		    $stmt->fetch();
	}

	/**
	 * Delete a password (actually marks as inactive, but has the same effect)
	 *
	 * @return bool Did it delete?
	 */
	public function delete() {
		$stmt = $GLOBALS['pdo']->prepare('update `passwords`
			set `active` = false
			where `id` = :id');
		$stmt->bindValue(':id', $this->id);
		return $stmt->execute();
	}

	/**
	 * Create a new Password
	 *
	 * @return mixed An instance of this class, or false
	 */
	static function create($Name, $Description, $Link, $Username, $Password) {
		$stmt = $GLOBALS['pdo']->prepare('insert into `passwords` set
			`name` = :name,
			`description` = :description,
			`link` = :link,
			`username` = :username
		');
		$stmt->bindValue(':name', $Name);
		$stmt->bindValue(':description', $Description);
		$stmt->bindValue(':link', $Link);
		$stmt->bindValue(':username', $Username);

		$stmt->execute();

		$PasswordID = $GLOBALS['pdo']->lastInsertId();

		// Go through every user, and insert a row for them, using their public key
		$stmt = $GLOBALS['pdo']->prepare('select * from `users`');

		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if (is_readable(PATH . 'keys/' . $row['username'] . '.pub')) {
			openssl_public_encrypt($Password, $Encrypted, file_get_contents(PATH . 'keys/' . $row['username'] . '.pub'));

				$stmt = $GLOBALS['pdo']->prepare('insert into `password_encrypted` set
					`password_id` = :password_id,
					`user_id` = :user_id,
					`blob` = :blob
				');
				$stmt->bindValue(':password_id', $PasswordID, PDO::PARAM_INT);
				$stmt->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
				$stmt->bindValue(':blob', $Encrypted, PDO::PARAM_LOB);

				$stmt->execute();
			}
		}
		return new Password($PasswordID);
	}
}

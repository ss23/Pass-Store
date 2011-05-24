<?php

/**
 * Password functions
 */

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
	public $password;

	// Consider these two funky prepared statment haxy things
	protected $rehashPreparedQuery;
	protected $savePreparedQuery;

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
		if (!$this->rehashPreparedQuery) {
			$this->rehashPreparedQuery = $GLOBALS['pdo']->prepare('
				SELECT `id`, `name`, `description`, `link`, `username`, `active`, `password`
				FROM `passwords`
				WHERE `id` = :id
			');
			$this->rehashPreparedQuery->bindParam(':id', $this->id);
			$this->rehashPreparedQuery->setFetchMode(PDO::FETCH_INTO, $this);
		}
		$this->rehashPreparedQuery->execute();
		$this->rehashPreparedQuery->fetch();
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
	 * Uses the ID to update a row within the passwords table
	 *
	 * @return void
	 */
	public function save() {
		if (!$this->savePreparedQuery) {
			$this->savePreparedQuery = $GLOBALS['pdo']->prepare('update `passwords`
				set `name` = :name,
				`description` = :desc,
				`link` = :link,
				`username` = :username');
			$this->savePreparedQuery->bindParam(':name', $this->name);
			$this->savePreparedQuery->bindParam(':desc', $this->description);
			$this->savePreparedQuery->bindParam(':link', $this->link);
			$this->savePreparedQuery->bindParam(':username', $this->username);
		}
		return $this->savePreparedQuery->execute();
	}

	/**
	 * Short Description
	 */
	public function shortDescription() {
		if (strlen($this->description) > 60) {
			return substr($this->description, 0, 60) . '...';
		} else {
			return $this->description;
		}
	}


	/**
	 * Decrypt the password
	 *
	 * @return string Password
	 */
	public function decrypt() {
		return openssl_decrypt($this->password, ENC, KEY, true);
	}

	/**
	 * Create a new Password
	 *
	 * @return mixed An instance of this class, or false
	 */
	static function create($Name, $Description, $Link, $Username, $Password) {
		var_dump($Password);
		$Password = self::encrypt($Password);
		var_dump($Password);
		$stmt = $GLOBALS['pdo']->prepare('insert into `passwords` set
			`name` = :name,
			`description` = :description,
			`link` = :link,
			`username` = :username,
			`password` = :password
		');
		$stmt->bindValue(':name', $Name);
		$stmt->bindValue(':description', $Description);
		$stmt->bindValue(':link', $Link);
		$stmt->bindValue(':username', $Username);
		$stmt->bindValue(':password', $Password);

		$stmt->execute();

		$PasswordID = $GLOBALS['pdo']->lastInsertId();
		return new Password($PasswordID);
	}

	/**
	 * Encypt a given password
	 *
	 * @param string $Password The password
	 *
	 * @return string Encrypted password
	 */
	static function encrypt($Password) {
		return openssl_encrypt($Password, ENC, KEY, true);
	}

}

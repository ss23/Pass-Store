<?php

/**
 * Password functions
 */

/**
 * Add a password into the database
 *
 * @param string $Name        The name of the password to be entered under
 * @param string $Description Description of the password
 * @param string $Link        Link to the login page for the password this refeers to
 * @param string $Username    Username to log in
 * @param string $Password    Password to log in
 *
 * @return bool Whether it suceeded or not
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

	return true;
}

/**
 * Delete a password (actually marks as inactive, but has the same effect)
 *
 * @param int $id ID of the password to delete
 *
 * @return bool Did it delete?
 */
function password_delete($id) {
	$stmt = $GLOBALS['pdo']->prepare('update `passwords`
		set `active` = false
		where `id` = :id');
	$stmt->bindValue(':id', $id);
	return $stmt->execute();
}

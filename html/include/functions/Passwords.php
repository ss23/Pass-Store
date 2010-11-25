<?php

function password_add($Name, $Description, $Link, $Username, $Password) {
	global $pdo;

	$stmt = $pdo->prepare('insert into `passwords` set
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

	$PasswordID = $pdo->lastInsertId();

	// Go through every user, and insert a row for them, using their public key
	$stmt = $pdo->prepare('select * from `users`');

	$stmt->execute();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if (is_readable(PATH . 'keys/' . $row['username'] . '.pub')) {
			openssl_public_encrypt($Password, $Encrypted, file_get_contents(PATH . 'keys/' . $row['username'] . '.pub'));

			$stmt = $pdo->prepare('insert into `password_encrypted` set
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

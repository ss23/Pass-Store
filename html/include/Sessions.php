<?php

lib('User');

session_start();

if (isset($_SESSION['id'])) {
	$stmt = $pdo->prepare('
		SELECT `id`, UNIX_TIMESTAMP(`last_active`) as last_active, INET_NTOA(`ip`) as ip
		FROM `sessions`
		WHERE `id` = :id
	');
	$stmt->bindParam(':id', $_SESSION['id']);
	$stmt->execute();

	$row = $stmt->fetch();
	$stmt->closeCursor();

	if (($row['ip'] != $_SERVER['REMOTE_ADDR']) || ($row['last_active'] < (time() - (60 * 5))))  {
		// Looks like this is now inactive, or the IP changed
		// Log out the user and regenerate the session ID
		session_obliterate();
		session_start();
	} else {
		if (isset($_SESSION['user'])) {
			$GLOBALS['user'] = &$_SESSION['user'];
		}
	}
	$stmt = $pdo->prepare('
		REPLACE INTO `sessions`
		(
			`id`, `ip`, `last_active`
		) VALUES (
			:id, INET_ATON(:ip), now()
		)
	');
	$stmt->bindParam(':id', $_SESSION['id']);
	$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
	$stmt->execute();

	// And that should finish up for when a session is already made

} else {
	$stmt = $pdo->prepare('
		INSERT INTO `sessions`
		(
			`ip`, `last_active`
		) VALUES (
			INET_ATON(:ip), now()
		)
	');
	$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
	$stmt->execute();
	$_SESSION['id'] = $pdo->lastInsertId();
}

/**
 * Obliterate a session out of existence
 *
 * @return void
 */
function session_obliterate() {
	foreach ($_SESSION as $var) {
		unset($var);
	}
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	);
	session_destroy();
}

?>

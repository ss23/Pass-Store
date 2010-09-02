<?php

require_once 'include/global.php';

if (!isset($_SESSION['user'])) {
	header('Location: /login.php');
}

lib('User');

// Get a list of possible passwords
$stmt = $pdo->prepare('select `passwords`.`name`, `passwords`.`description`
	from `passwords`
	where `passwords`.`id` = `password_encrypted`.`password_id` AND
	`password_encrypted` = :user_id
');
$stmt->bindParam(':user_id', $user->id);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo $row['name'] . "\n";
}
?>

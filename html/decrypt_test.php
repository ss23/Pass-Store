<?php
include 'include/global.php';

$stmt = $pdo->prepare('select * from `password_encrypted`
where user_id = :user_id');
$stmt->bindValue(':user_id', 2);
var_dump($stmt->execute());

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo "ID: " . $row['password_id'] . "\n";
	echo "Password: " . $user->decrypt($row['blob']) . "\n";
}

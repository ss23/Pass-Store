<?php

group_create($name, $type = 'group') {
	global $pdo;
	
	$stmt = $pdo->prepare('
		INSERT INTO `groups`
		(
			`name`
			, `type`
		) VALUES (
			:name
			, :type
		)
	');
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':type', $type);
	$stmt->execute();
	return $pdo->lastInsertId();
}

group_add($gid, $user_id) {
	global $pdo;

	$stmt = $pdo->prepare('
		INSERT INTO `group_users`
		(
			`group_id`
			, `user_id`
		) VALUES (
			:group_id
			, :user_id
		)
	');
	$stmt->bindParam(':group_id', $gid);
	$stmt->bindParam(':user_id', $user_id);
	return $stmt->execute();
}

?>

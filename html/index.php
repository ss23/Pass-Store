<?php

require_once 'include/global.php';

lib('User');

// Get a list of possible passwords
$stmt = $pdo->prepare('select `passwords`.*, `password_encrypted`.*
	from  `passwords`, `password_encrypted`
	where `passwords`.`id` = `password_encrypted`.`password_id`
	  and `passwords`.`active` = 1
	  and `password_encrypted`.`user_id` = :user_id
');
$stmt->bindParam(':user_id', $user->id);
$stmt->execute();

require 'include/header.php';
?>

<table id="password-list">
	<thead>
		<tr><th class="input" colspan="5"><input type="search" placeholder="Search for a password" autofocus></th></tr>
		<tr class="menu"><th></th><th class="inner-first">Name</th><th>Username</th><th class="inner-last">Password</th><th></th></tr>
	</thead>
	<tfoot>
		<tr><td></td><td colspan="3"></td><td></td></tr>
		<tr class="spacer"><td></td></tr>
	</tfoot>
	<tbody>
<?php

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$Password = $user->decrypt($row['blob']);

	echo "<tr>";
	echo "<td></td>";
        echo "<td><a target=\"_blank\" href=\"" . htmlspecialchars($row['link'], ENT_QUOTES) . "\">" . htmlspecialchars($row['name'], ENT_QUOTES) . "</td>";
        echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES) . "</td>";
        echo "<td>" . htmlspecialchars($Password, ENT_QUOTES) . "</td>";
	echo "<td></td>";
	echo "</tr>";
}

?>
	</tbody>
</table>
<?php

require 'include/footer.php';

?>

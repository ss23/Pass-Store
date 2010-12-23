<?php

$JS = '';
require_once 'include/global.php';

lib('User');

// Delete a password
if (!empty($_POST['delete'])) {
	if (!empty($_POST['checkbox']) && is_array($_POST['checkbox'])) {
		// Delete the password from the database.
		// TODO: Add some sort of "delete" permissions
		lib('Passwords');
		foreach ($_POST['checkbox'] as $id => $value) {
			password_delete($id);
		}
	}
}

// Get a list of possible passwords
$stmt = $pdo->prepare('select `passwords`.*, `password_encrypted`.*
	from  `passwords`, `password_encrypted`
	where `passwords`.`id` = `password_encrypted`.`password_id`
	  and `passwords`.`active` = 1
	  and `password_encrypted`.`user_id` = :user_id
');
$stmt->bindParam(':user_id', $user->id);
$stmt->execute();


$JSFiles[] = 'jquery.quicksearch.js';

$JS .= <<<JS
$(function () {
	$('#password-list input').quicksearch('#password-list tbody tr');
});
JS;

require 'include/header.php';
?>

<form action="index.php" method="post">
<table id="password-list">
	<thead>
		<tr><th class="input" colspan="8"><input type="search" placeholder="Search for a password" autofocus></th></tr>
		<tr class="menu"><th></th><th class="inner-first"></th><th>Name</th><th>Username</th><th>Password</th><th>Description</th><th class="inner-last"></th><th></th></tr>
	</thead>
	<tfoot>
		<tr><td></td><td colspan="6"></td><td></td></tr>
		<tr><td class="options" colspan="6">
			<input type="submit" name="delete" value="Delete">
		</td></tr>
		<tr class="spacer"><td></td></tr>
	</tfoot>
	<tbody>
<?php

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$Password = $user->decrypt($row['blob']);

	if (strlen($row['description']) > 60) {
		$Description = substr($row['description'], 0, 60) . '...';
	} else {
		$Description = $row['description'];
	}
	echo "<tr>\n\t\t";
	echo "<td></td>\n\t\t";
	echo '<td><input type="checkbox" name="checkbox[' . htmlspecialchars($row['id'], ENT_QUOTES) . ']"></td>' . "\n\t\t";
        echo '<td><a target="_blank" href="' . htmlspecialchars($row['link'], ENT_QUOTES) . '">' . htmlspecialchars($row['name']) . "</td>\n\t\t";
        echo '<td>' . htmlspecialchars($row['username']) . "</td>\n\t\t";
        echo '<td class="password"><span class="mask">********</span><span class="real">' . htmlspecialchars($Password) . "</span></td>\n\t\t";
	echo '<td class="desc">' . htmlspecialchars($Description) . '<span class="full-desc">' . htmlspecialchars($row['description']) . "</span></td>\n\t\t";
	echo '<td><a href="/edit_password.php?id=' . htmlspecialchars($row['id'], ENT_QUOTES) . '">Edit</td>' . "\n\t\t";
	echo "<td></td>\n\t";
	echo "</tr>\n\t";
}

?>
	</tbody>
</table>
</form>
<?php

require 'include/footer.php';

?>

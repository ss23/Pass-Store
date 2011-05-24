<?php

$JS = '';
require_once 'include/global.php';

lib('User');
lib('Passwords');

// Delete a password
if (!empty($_POST['delete'])) {
	if (!empty($_POST['checkbox']) && is_array($_POST['checkbox'])) {
		// Delete the password from the database.
		// TODO: Add some sort of "delete" permissions
		lib('Passwords');
		foreach ($_POST['checkbox'] as $id => $value) {
			$Password = new Password($id);
			if ($Password) {
				$Password->delete();
				unset($Password);
			}
		}
	}
}

// Get a list of possible passwords
$stmt = $pdo->prepare('select id
	from  `passwords`
	where `passwords`.`active` = 1');
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
	$Password = new Password($row['id']);

	echo "<tr>\n\t\t";
	echo "<td></td>\n\t\t";
	echo '<td><input type="checkbox" name="checkbox[' . htmlspecialchars($Password->id, ENT_QUOTES) . ']"></td>' . "\n\t\t";
	echo '<td><a target="_blank" href="' . htmlspecialchars($Password->link, ENT_QUOTES) . '">' . htmlspecialchars($Password->name) . "</td>\n\t\t";
	echo '<td>' . htmlspecialchars($Password->username) . "</td>\n\t\t";
	echo '<td class="password"><span class="mask">********</span><span class="real">' . htmlspecialchars($Password->decrypt()) . "</span></td>\n\t\t";
	echo '<td class="desc">' . htmlspecialchars($Password->shortDescription()) . '<span class="full-desc">' . htmlspecialchars($Password->description) . "</span></td>\n\t\t";
	echo '<td><a href="/edit_password.php?id=' . $Password->id . '">Edit</td>' . "\n\t\t";
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

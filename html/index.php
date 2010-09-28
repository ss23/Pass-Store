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


$JSFiles[] = 'jquery.quicksearch.js';
$JS .= <<<JS
$(function () {
        $('#password-list input').quicksearch('#password-list tbody tr');
});
JS;

require 'include/header.php';
?>

<table id="password-list">
	<thead>
		<tr><th class="input" colspan="7"><input type="search" placeholder="Search for a password" autofocus></th></tr>
		<tr class="menu"><th></th><th class="inner-first"></th><th>Name</th><th>Username</th><th>Password</th><th class="inner-last">Description</th><th></th></tr>
	</thead>
	<tfoot>
		<tr><td></td><td colspan="5"></td><td></td></tr>
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
        echo '<td><a target="_blank" href="' . htmlspecialchars($row['link'], ENT_QUOTES) . '">' . htmlspecialchars($row['name'], ENT_QUOTES) . "</td>\n\t\t";
        echo '<td>' . htmlspecialchars($row['username'], ENT_QUOTES) . "</td>\n\t\t";
        echo '<td class="password"><span class="mask">********</span><span class="real">' . htmlspecialchars($Password, ENT_QUOTES) . "</span></td>\n\t\t";
	echo '<td class="desc">' . htmlspecialchars($Description, ENT_QUOTES) . '<span class="full-desc">' . htmlspecialchars($row['description'], ENT_QUOTES) . "</span></td>\n\t\t";
	echo "<td></td>\n\t";
	echo "</tr>\n\t";
}

?>
	</tbody>
</table>
<?php

require 'include/footer.php';

?>

<?php

require 'include/global.php';

include 'include/header.php';

if (empty($_GET['id']) or !ctype_digit($_GET['id'])) {
        die('Invalid ID');
}

lib('Passwords');

if (isset($_POST['update'])) {
        // I LOVE manual form validation
        // TODO: Write it

        if (password_edit($_GET['id'], $_POST['name'], $_POST['description'], $_POST['link'], $_POST['username'], $_POST['password'])) {
                echo "<h4>Password Saved</h4>";
		die();
        }
}

// Instantiate the password object and start fetching values
$Password = new Password($_GET['id']);

?>

<form action="edit_password.php" method="post">

<div class="form_container" id="edit_password_form">
	<div class="inner">

	<label for="name">Name:</label>
	<input type="name" name="name" autocomplete="off" value="<?php echo htmlspecialchars($Password->name, ENT_QUOTES); ?>">
	<br>

	<label for="description">Description:</label>
	<textarea name="description" autocomplete="off" cols="23"><?php echo htmlspecialchars($Password->description, ENT_QUOTES); ?></textarea>
	<br>

	<label for="link">Link:</label>
	<input type="url" name="link" value="<?php echo htmlspecialchars($Password->link, ENT_QUOTES); ?>">
	<br>

	<br><br>
	<label for="username">Username:</label>
	<input type="text" name="username" autocomplete="off" value="<?php echo htmlspecialchars($Password->username, ENT_QUOTES); ?>">
	<br>

	<label for="password">Password:</label>
	<input type="password" name="password" autocomplete="off">
	<small>Leave blank to remain unchanged</small>
	<br>

	<input type="submit" name="update" value="Update">

	</div><!-- inner -->
</div>

<!--
<table id="groups">
	<thead>
		<tr class="menu">
			<th></th>
			<th class="inner-first"></th>
			<th>Name</th>
			<th class="inner-last"></th>
			<th></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td></td>
			<td colspan="5"></td>
		</tr>
		<tr class="spacer">
			<td></td>
		</tr>
	</tfoot>
	<tbody>

	</tbody>
</table>
-->
</form>
<?php

include 'include/footer.php';

?>

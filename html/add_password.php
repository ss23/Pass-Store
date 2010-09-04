<?php

require 'include/global.php';

if (isset($_POST['create'])) {
	// I LOVE manual form validation
	// TODO: Write it

	lib('Passwords');
	if (password_add($_POST['name'], $_POST['description'], $_POST['link'], $_POST['username'], $_POST['password'])) {
		$pass_added = true;
	}	
}

include 'include/header.php';

if ($pass_added) {
	echo "<h4>Password Added</h4>";
}
?>

<div class="form_container" id="new_password_form">
<form action="add_password.php" method="post">
	<label for="name">Name:</label>
	<input type="name" name="name" autocomplete="off">
	<br>
	
	<label for="description">Description:</label>
	<textarea name="description" autocomplete="off" cols="23"></textarea>
	<br>
	
	<label for="url">URL:</label>
	<input type="url" name="link">
	<br>
	
	<br><br>
	<label for="username">Username:</label>
	<input type="text" name="username" autocomplete="off">
	<br>
	
	<label for="password">Password:</label>
	<input type="password" name="password" autocomplete="off">
	<br>
	
	<input type="submit" name='create' value="Create">
</form>
</div>

<?php

include 'include/footer.php';

?>

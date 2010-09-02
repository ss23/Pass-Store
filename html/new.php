<?php

require 'include/global.php';

if (!isset($_SESSION['user'])) {
	header('Location: /login.php');
}

if (isset($_POST['create'])) {
	// I LOVE manual form validation
	// TODO: Write it

	lib('Passwords');
	password_add($_POST['name'], $_POST['description'], $_POST['link'], $_POST['username'], $_POST['password']);
	echo "Password Added"; 		
}

?>

<form action="new.php" method="post">
	Name: <input type="name" name="name" ><br>
	Description: <textarea name="description"></textarea><br>
	URL: <input type="url" name="link"><br>
	<br><br>
	Username: <input type="text" name="username"><br>
	Password: <input type="password" name="password"><br>
	<input type="submit" name='create' value="Create">
</form>

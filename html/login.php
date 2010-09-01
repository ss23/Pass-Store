<?php

require "include/global.php";

if (isset($_SESSION['user'])) {
	header('Location: index.php');
}

if (isset($_POST['submit'])) {
	// Oh the joy of manual form validation
	
	// Set our redirect if needed
	if (!empty($_POST['redirect'])) {
		$redirect = $_POST['redirect'];
	}
	
	// First, check all compulsary fields aren't blank
	if (empty($_POST['username'])) {
		$errors['username'] = true;
	}

	if (empty($_POST['password'])) {
		$errors['password'] = true;
	}

	if (!$errors) {
		// More validation, but no point if anything is empty
		lib('User');

		if (user_authenticate($_POST['username'], $_POST['password'])) {
			if (!empty($redirect)) {
				header('Location: ' . $redirect);
			} else {
				header('Location: /index.php');
			}
			die(); // Just in case?
		} else {
			$errors['auth'] = true;
		}
	}
} else {
	// Seems to be the first visit to this page
	if (!empty($_GET['redirect'])) {
		$redirect = $_GET['redirect'];
	}
}

// Display the page

require 'include/header.php';

?>
<form action="/login.php" method="post">
	<input type="text" name="username" >
	<?php
	if ($errors['username']) {
		echo "<p class='error username'>Please enter a username</p>";
	} ?>
	<input type="password" name="password" >
	<?php
	if ($errors['password']) {
		echo "<p class='error password'>Please enter a password</p>";
	}
	if (!empty($redirect)) {
		echo '<input type="hidden" name="redirect" value="' . $redirect . '" >';
	} ?>
	<input type="submit" value="Log in" name="submit">
	<?php
	if ($errors['auth']) {
		echo "<p class='error auth'>Invalid username or password.</p>";
	} ?>
</form>

<?php

require 'include/footer.php';

?>



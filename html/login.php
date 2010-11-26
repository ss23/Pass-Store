<?php

define('NO_LOGIN', true);

require "include/global.php";

if (isset($_REQUEST['redirect'])) {
	$redirect = $_REQUEST['redirect'];
} else {
	$redirect = '/index.php';
}

if (isset($_SESSION['user'])) {
	header('Location: ' . $redirect);
}

if (isset($_POST['submit'])) {
	// Oh the joy of manual form validation

	// First, check all compulsary fields aren't blank
	if (empty($_POST['username'])) {
		$errors['username'] = true;
	}

	if (empty($_POST['password'])) {
		$errors['password'] = true;
	}

	if (empty($errors)) {
		// More validation, but no point if anything is empty
		lib('User');

		if (user_authenticate($_POST['username'], $_POST['password'])) {
			header('Location: ' . $redirect);
			die();
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
<div class="form_container" id="login_form_container">
<form action="/login.php" method="post" id="login_form">
	<h4>Sign In</h4>
	<label for="username">Username:</label>
	<input type="text" name="username" required>
	<?php
if (!empty($errors['username'])) {
	echo "<p class='error username'>Please enter a username</p>";
} else {
	echo "<br>";
}
	?>
	<label for="password">Password:</label>
	<input type="password" name="password" required >
	<?php
if (!empty($errors['password'])) {
	echo "<p class='error password'>Please enter a password</p>";
}
if (!empty($redirect)) {
	echo '<input type="hidden" name="redirect" value="' . $redirect . '" >';
} else {
	echo "<br>";
}
	?>
	<input type="submit" value="Sign In" name="submit">
	<?php
if (!empty($errors['auth'])) {
	echo "<p class='error auth'>Invalid username or password.</p>";
}
	?>
</form>
</div>

<?php

require 'include/footer.php';

?>

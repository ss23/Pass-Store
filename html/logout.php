<?

define('NO_LOGIN', true);
require "include/global.php";

if ($_SESSION['user']) {
	lib('User');
	user_logout();

	// Good practice to regenerate ID's etc on logout
	session_obliterate();
	session_start();
?>
You have been successfully logged out
<?php
} else {
?>
You were never logged in, silly billy
<?php
}
?>

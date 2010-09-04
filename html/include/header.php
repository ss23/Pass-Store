<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php
	if (!empty($Page['title'])) {
		echo $Page['title'] . ' - ';
	}
	?>PassStore</title>
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css">
	<link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>

<div class="title-bar">
	<?php
	if (isset($_SESSION['user'])) { ?>
	<a href="/">Home</a>
	<a href="/add_password.php">Add a Password</a>
	<a href="#">Secure Notes</a>
	
	<a href="/logout.php">Logout</a>
	<? } else { ?>
	<a href="/login.php">Login</a>
	<a href="/help.php">Help</a>
	<?php
	} ?>
</div>

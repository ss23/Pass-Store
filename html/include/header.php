<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php
if (!empty($Page['title'])) {
	echo $Page['title'] . ' - ';
}
	?>PassStore</title>
	<link rel="shortcut icon" type="image/png" href="<?php echo WEBPATH; ?>/images/favicon.png">
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset/reset-min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WEBPATH; ?>/css/styles.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<?php
if (!empty($JSFiles)) {
	foreach ($JSFiles as $File) {
		if (is_readable(PATH . 'html/js/' . $File)) {
			echo '	<script type="text/javascript" src="/js/' . $File . "\"></script>\n";
		}
	}
}
?>

<head>
<body>

<div class="title-bar">
<?php if (isset($_SESSION['user'])) { ?>
	<a href="<?php echo WEBPATH; ?>/">Home</a>
	<a href="<?php echo WEBPATH; ?>/add_password.php">Add a Password</a>
	<a href="<?php echo WEBPATH; ?>/groups.php">Groups</a>
	<a href="<?php echo WEBPATH; ?>/notes.php">Secure Notes</a>
	<a href="<?php echo WEBPATH; ?>/logout.php">Logout</a>
	<?php
} else {
	?>
	<a href="<?php echo WEBPATH; ?>/login.php">Login</a>
	<a href="<?php echo WEBPATH; ?>/help.php">Help</a>
	<?php
	} ?>
</div>

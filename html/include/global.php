<?php

// Load our configuration
require_once '../config/config.php';

// Database
require_once PATH.'html/include/MyPDO.php';
$GLOBALS['pdo'] = new MyPDO();

// The library (lib) function
function lib($Library) {
	if (file_exists(PATH."html/include/$Library.php")) {
		require_once PATH."html/include/$Library.php";
		return;
	}
	if (file_exists(PATH."html/include/functions/$Library.php")) {
		require_once PATH."html/include/functions/$Library.php";
		return;
	}
	throw new Exception('Could not load library '.$Library);
}

// Authentication and Sessions
require_once PATH.'html/include/Sessions.php';
// Custom Classes go here
lib('Sanitize');

if (!defined('NO_LOGIN')) {
	if (!isset($_SESSION['user'])) {
		header('Location: /login.php?redirect=' . $_SERVER['PHP_SELF']);
	}
}

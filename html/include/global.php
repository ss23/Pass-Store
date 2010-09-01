<?php

// Load our configuration
require_once '../config/config.php';

// Database
require_once PATH.'html/include/MyPDO.php';
$GLOBALS['pdo'] = new MyPDO();

// User Authentication and sessions
require_once PATH.'html/include/Sessions.php';

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

// Custom Classes go here
lib('Sanitize');

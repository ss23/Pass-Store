<?php

/* Configuration for this thing 
 * Not much here yet
 *
 */

// Use something like https://www.grc.com/passwords.htm to generate a string for this
// Really, if this isn't long enough, you're wasting your time encrypting at all.
// If you want to migrate servers, keeping this key the same will make sure all passwords still work
define('KEY', '');

define('DB_HOST', 'localhost');	// Host of the database server
define('DB_PORT', 3306);	// Port of the database server
define('DB_USER', 'pass');	// User for this application on the database server
define('DB_PASS', '');		// Password for the above user
define('DB_NAME', 'pass');	// Name of the database

define('PATH', realpath(dirname(__FILE__) . '/../') . '/');// Full path to the application. The path above the folder containing this one

require_once(PATH . 'html/include/functions/Common.php');

define('WEBPATH', common_get_relative_path(PATH . 'html'));

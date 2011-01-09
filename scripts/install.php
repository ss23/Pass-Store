#!/usr/bin/php
<?php

echo "Installing Pass-Store.\n";

if (!is_readable(realpath(dirname(__FILE__)) . '/../config/config.php')) {
	echo "Cannot read /config/config.php. Please check that the user running this script has permission to read it.\n";
	echo "Exiting.\n";
	die();
}

echo "Reading configuration file.\n";
require (realpath(dirname(__FILE__)) . '/../config/config.php');

echo "Verifying database connection.\n";
require (PATH . 'html/include/MyPDO.php');

try {
	$pdo = new MyPDO();
} catch (Exception $e) {
	switch ($e->getCode()) {
		case 1045:
			echo "A permissions error occurred. Please recheck your configuration options, specifically, your username and password.\n";
			break;
		case 1044:
			echo "The database selected in the configuration file either doesn't exist, or our user doesn't have permission to read. Please recheck your confiugration options.\n";
			break;
		case 2002:
			echo "Could not to connect to the host and port specified. Please recheck your configuartion options.\n";
			break;
		default:
			echo "An unknown database error occurred. Please submit a bug report to http://www.github.com/ss23/Pass-Store or recheck your configuration options.\n";
			break;
	}
	die("Exiting.\n");
}

echo "Importing database.\n";
require ('importDatabase.php');

echo "Checking for /keys.\n";
if (!file_exists(PATH . 'keys')) {
	echo "/keys not found, creating it.\n";
	if (!mkdir(PATH . 'keys')) {
		echo "/keys could not be created. Please check that the user running this script has permission to create it, or create it manually.\n";
		echo "Exiting.\n";
		die();
	}
}

if (!is_readable(PATH . 'keys')) {
	echo "/keys is not readable. Please check that the user running this script has permission to read it.\n";
	echo "Exiting\n";
	die();
}

echo "/keys exists and is writable.\n";

require (PATH . 'html/include/functions/Sanitize.php');

echo "\nPlease enter a username:\n";
$Username = s(read_line());
echo "Please enter a password for this user:\n";
$Password = s(read_line());

require (PATH . 'html/include/functions/User.php');

$Password = user_hash($Username, $Password);

echo "Hashed Password of '" . $Password . "' generated.\n";

echo "Generating OpenSSL Keys";

// Create the keypair
$res = openssl_pkey_new(array('encrypt_key' => user_key($Username, $Password)));
if (empty($res)) {
	echo "OpenSSL is not configured properly. See http://www.php.net/manual/en/openssl.installation.php\n";
	echo "Exiting\n";
	die();
}

echo "."; // Progress

// Get private key
openssl_pkey_export($res, $PrivateKey);
echo "."; // Progress

// Get public key
$pubkey = openssl_pkey_get_details($res);
$PublicKey = $pubkey["key"];
echo "."; // Progress

file_put_contents(PATH . 'keys/' . $Username . '.pem', $PrivateKey);
file_put_contents(PATH . 'keys/' . $Username . '.pub', $PublicKey);
echo "\n"; // Finish progress

echo "Certificates generated.\n";

echo "Inserting user into database.\n";

$stmt = $pdo->prepare('insert into `users` (
                `username`, `password`
                ) values (
                :username, :password
                )');
$stmt->bindParam(':username', $Username);
$stmt->bindParam(':password', $Password);
if (!$stmt->execute()) {
        echo "Automatic entry to database failed. Please do this manually.\n";
	echo "Exiting.\n";
	die();
}

echo "User inserted into database.\n";

echo "Installation Complete. Please note you will need to configure Apache still, along with permissions, etc.\n";



function read_line() {
	$fp = fopen('php://stdin', 'r');
	$input = fread($fp, 999);
	return $input;
}

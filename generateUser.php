#!/usr/bin/php
<?php

if (count($argv) != 3) {
	echo "Example Usage - ./generateUser username password\n";
	echo "Or, if you want spaces in your password - ./generateUser username 'password with spaces'\n";
	die();
}

require ('html/include/functions/Sanitize.php');
require ('html/include/functions/User.php');

$Username = s($argv[1]);
$Password = user_hash($argv[2], $argv[1]);
echo "Username: " . $Username . "\n";
echo "Hashed Password: " . $Password . "\n";

echo "Generating OpenSSL Keys...\n";

// Create the keypair
$res = openssl_pkey_new(array('encrypt_key' => user_key($argv[2], $argv[1])));

// Get private key
openssl_pkey_export($res, $PrivateKey);

// Get public key
$pubkey = openssl_pkey_get_details($res);
$PublicKey = $pubkey["key"];

file_put_contents('./keys/' . $argv[1] . '.pem', $PrivateKey);
file_put_contents('./keys/' . $argv[1] . '.pub', $PublicKey);

echo "Certificates generated\n";

// Start the connection to the database
require ('config/config.php');
require ('html/include/MyPDO.php');
$pdo = new MyPDO();

$stmt = $pdo->prepare('insert into `users` (
		`username`, `password`
		) values (
		:username, :password
		)');
$stmt->bindParam(':username', $Username);
$stmt->bindParam(':password', $Password);
if (!$stmt->execute()) {
	echo "Automatic entry to database failed. Please do this manually.\n";
} else {
	echo "User automatically inserted into database.\n";
}
?>

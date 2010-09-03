#!/usr/bin/php
<?php

if (count($argv) != 3) {
	echo "Example Usage - ./generateUser username password\n";
	echo "Or, if you want spaces in your password - ./generateUser username 'password with spaces'";
}

require ('html/include/functions/Sanitize.php');
require ('html/include/functions/User.php');

echo "Username: " . s($argv[1]) . "\n";
echo "Hashed Password: " . user_hash($argv[2], $argv[1]) . "\n";

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
echo "Remeber to manually add this user to your database\n";
?>

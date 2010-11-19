#!/usr/bin/php
<?php

if (count($argv) > 2) {
        echo "Example Usage - ./doTests [-debug]\n";
        die();
}

require ('../html/include/functions/Sanitize.php');
require ('../html/include/functions/User.php');
require ('../html/include/functions/Common.php');
require ('../html/include/functions/Passwords.php');

$Debug = (isset($argv[1]));

$Tests = array();

// Required functions/things
if (CRYPT_BLOWFISH == 1) {
	echo "ya";
} else {
	echo "na";
}

// Hashing test
for ($i = 0; $i <= 10; $i++) {
	$Username = common_rand_str(mt_rand(4, 24));
	$Password = common_rand_str(mt_rand(4, 24), 'ABCDEFGHIJKLMNOPQRSTUVWQYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*()-=_+[]\\{}|;\':",./<>?`~');

	//$hash = crypt(user_key($Password, $Username), '$2a$05$' . 'rawr$');
	$salt = substr(hash('sha512', uniqid(true), true), 0, 12);
	//var_dump($salt);
	$hash = crypt(user_key($Password, $Username), '$6$rounds=5000$' . $salt . '$');
	$hash = crypt($Password, '$6$rounds=5000$' . $salt . '$');
	var_dump($hash);

	if (($hash_res = crypt(user_key($Password, $Username), $hash)) == $hash) {
		// If we're on the last stage of the test
		if ($i == 10) {
			$Tests['Hashing']['Result'] = 'Passed';
		}
	} else {
		$Tests['Hashing']['Result'] = 'Failed';
		$Tests['Hashing']['Debug'] = "Failed on test $i. \n";
		$Tests['Hashing']['Debug'] .= "Username: $Username \n";
		$Tests['Hashing']['Debug'] .= "Password: $Password \n";
		$Tests['Hashing']['Debug'] .= "Hash: $hash \n";
		$Tests['Hashing']['Debug'] .= "Hash Result: $hash_res \n";
	}
}

foreach($Tests as $Name => $Test) {
	echo "Test '{$Name}': {$Test['Result']}.\n";
	if ($Debug && $Test['Debug']) {
		echo "--- Debug for '{$Name}' ---\n";
		echo $Test['Debug'];
		echo "--- Debug end ---\n";
	}
}

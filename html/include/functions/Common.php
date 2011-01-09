<?php

/**
 * Function that are "common" or have no other place go here
 */

/**
 * Generate a random string.
 *
 * @param int    $length The length of the generated string
 * @param string $chars  The possible charactesr to choose from
 *
 * @return The "random" generated string
 */
function common_rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
	// http://www.php.net/manual/en/function.rand.php#90773

	// Length of character list
	$chars_length = (strlen($chars) - 1);

	// Start our string
	$string = $chars{rand(0, $chars_length)};

	// Generate random string
	for ($i = 1; $i < $length; $i = strlen($string)) {
		// Grab a random character from our list
		$r = $chars{rand(0, $chars_length)};

		// Make sure the same two characters don't appear next to each other
		if ($r != $string{$i - 1}) {
			$string .=  $r;
		}
	}
	// Return the string
	return $string;
}

function common_get_relative_path($path) {
	$dr = $_SERVER['DOCUMENT_ROOT']; //Probably Apache situated

	if (empty($dr)) { // Probably IIS situated
		if (isset($_SERVER['PATH_TRANSLATED'])) {
			$pt = $_SERVER['PATH_TRANSLATED'];
		} else {
			$pt = $_SERVER['ORIG_PATH_TRANSLATED'];
		}
		//Get the document root from the translated path.
		$pt = str_replace('\\\\', '/', $pt);
		$dr = substr($pt, 0, -strlen($_SERVER['SCRIPT_NAME']));
	}

	$dr = str_replace('\\\\', '/', $dr);

	return substr(str_replace('\\', '/', str_replace('\\\\', '/', $path)), strlen($dr));
}

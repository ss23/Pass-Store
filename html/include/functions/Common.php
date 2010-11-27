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

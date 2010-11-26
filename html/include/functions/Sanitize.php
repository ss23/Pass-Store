<?php

/**
 * Santize functions
 */

/**
 * Sanatize input for use in things like usernames
 *
 * @param string $i The string to sanatize
 *
 * @return string The sanatized string
 */
function s($i) {
	return (strtolower(trim(filter_var($i, FILTER_SANITIZE_STRING))));
}

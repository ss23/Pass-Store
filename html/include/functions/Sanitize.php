<?php

function s($i) {
	return (strtolower(trim(filter_var($i, FILTER_SANITIZE_STRING))));
}

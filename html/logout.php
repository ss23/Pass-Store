<?php

define('NO_LOGIN', true);
require "include/global.php";

if (isset($_SESSION['user'])) {
    lib('User');
    user_logout();

    // Good practice to regenerate ID's etc on logout
    session_regenerate_id();

    echo "You have been successfully logged out";
} else {
    echo "You were never logged in, silly billy";
}

?>

<?php

require_once 'include/global.php';

lib('User');

$Store = $_SESSION['user']->encrypt('lol');
echo strlen($Store);
echo $_SESSION['user']->decrypt($Store);

?>

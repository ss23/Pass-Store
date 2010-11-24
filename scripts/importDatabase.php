<?php

if (!isset($pdo)) {
	require_once ('../config/config.php');
	require_once (PATH . 'html/include/MyPDO.php');
	$pdo = new MyPDO();
}

$SQL = file_get_contents(PATH . 'mysql/pass.sql');
$pdo->exec($SQL);


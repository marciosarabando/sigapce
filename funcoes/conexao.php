<?php
//Local Ten Sarabando
$db['server']	= '192.168.10.10';
$db['user']	= 'homestead';
$db['password']	= 'secret';
$db['dbname']	= 'sigapce';

$conn = mysqli_connect($db['server'], $db['user'], $db['password']) or die(mysql_error());

mysqli_select_db($conn, $db['dbname']);
mysqli_query($conn,"SET NAMES 'utf8';");
?>

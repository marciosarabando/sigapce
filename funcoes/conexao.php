<?php
//Local Ten Sarabando
$db['server']	= 'localhost';
$db['user']	= 'root';
$db['password']	= '';
$db['dbname']	= 'sigapce_prd';

//WebSFPC CTA
//$db['server']	= '10.13.130.42';
//$db['user']	= 'sistemas_sfpc';
//$db['password']	= '9En8SOIT';
//$db['dbname']	= 'sistemas_sfpc';

$conn = mysqli_connect($db['server'], $db['user'], $db['password']) or die(mysql_error());

mysqli_select_db($conn, $db['dbname']);
mysqli_query($conn,"SET NAMES 'utf8';");
?>

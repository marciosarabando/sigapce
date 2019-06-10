<?php
//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$id_processo = $_GET['id_processo'];
$msg_nota_informativa = mb_strtoupper($_GET['msg_nota_informativa'],'UTF-8');
date_default_timezone_set('America/Sao_Paulo');
$dt_nota_informativa = date('Y-m-d H:i');

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "INSERT INTO nota_informativa VALUES (null,$id_processo,$id_login,'$dt_nota_informativa','$msg_nota_informativa',0,null,null)";
mysqli_query($conn,$query) or die(mysqli_error($conn));


?>
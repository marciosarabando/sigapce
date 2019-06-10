<?php
if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['login_sfpc']))
{

 	$id_login = $_SESSION['id_login_sfpc'];
}


$senha = urldecode($_GET['senha']);
//echo ($senha);
$senha = md5($senha);
//$ConfirmaSenha = $_GET['confirmacao'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "UPDATE login SET nm_senha = '$senha' WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());
//echo($query);




?>
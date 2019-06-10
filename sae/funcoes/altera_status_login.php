<?php
//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
	session_start();
}
//$id_login = $_SESSION['id_login_sfpc'];
$nm_guerra = $_SESSION['login_sfpc'];

$id_agendamento_login = $_GET['id_agendamento_login'];
$id_status_login_novo = $_GET['id_status_login_novo'];
$txt_observacao_login = mb_strtoupper($_GET['txt_observacao_login'],'UTF-8');

if($txt_observacao_login <> "")
{
	$txt_observacao_login .= "ˆMODIFICADO POR $nm_guerra";	
}
else
{
	$txt_observacao_login = "MODIFICADO POR $nm_guerra";		
}



date_default_timezone_set('America/Sao_Paulo');
$dt_historico_login = date('Y-m-d H:i');


include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "INSERT INTO agendamento_login_historico VALUES (null,$id_agendamento_login,$id_status_login_novo,'$dt_historico_login','$txt_observacao_login')";
mysqli_query($conn,$query) or die(mysql_error());

if($id_status_login_novo == 2)
{
	$query = "UPDATE agendamento_login SET st_login = 1 WHERE id_agendamento_login = $id_agendamento_login";
}
else
{
	$query = "UPDATE agendamento_login SET st_login = 0 WHERE id_agendamento_login = $id_agendamento_login";
}
mysqli_query($conn,$query) or die(mysql_error());

?>
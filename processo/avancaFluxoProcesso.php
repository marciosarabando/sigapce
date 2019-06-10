<?php
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];

$id_processo = $_GET['id_processo'];
$id_processo_status = $_GET['id_processo_status'];
$obs_processo_andamento = mb_strtoupper($_GET['obs_processo_andamento'],'UTF-8');
date_default_timezone_set('America/Sao_Paulo');
$dt_processo_andamento = date('Y-m-d H:i');

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "INSERT INTO processo_andamento VALUES (null,$id_processo,$id_login,$id_processo_status,'$dt_processo_andamento','$obs_processo_andamento')";
mysqli_query($conn,$query) or die(mysqli_error($conn));

?>
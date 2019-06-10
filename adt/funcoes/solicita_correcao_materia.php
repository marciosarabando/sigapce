<?php
//SOLICITA CORREÇÃO DA MATÉRIA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) 
{
	session_start();
}
$data_hora_atual = date('YmdHis');
$id_adt_materia = $_GET['id_adt_materia'];
$txt_correcao_materia = $_GET['txt_correcao_materia'];
$id_login = $_SESSION['id_login_sfpc'];

$query = "INSERT INTO adt_materia_andamento VALUES (null, $id_adt_materia, 3, $id_login, $data_hora_atual, '$txt_correcao_materia')";
//echo($query);
mysqli_query($conn,$query) or die(mysqli_error($conn));


?>
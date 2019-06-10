<?php
//MARCA PRESENCA OU AUSENCIA DO USUÁRIO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

if(isset($_SESSION['login_sfpc']))
{
	$nm_guerra = $_SESSION['login_sfpc'];
}

$id_agendamento_requerente = $_GET['id_agendamento_requerente'];
$situacao = $_GET['situacao'];
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i');

if($situacao == "presente")
{
	$query = "INSERT INTO agendamento_requerente_andamento VALUES (null,$id_agendamento_requerente,2,'$hoje','Confirmado por: $nm_guerra')";
	if (mysqli_query($conn,$query) or die(mysql_error()))
	{
		echo("<font color='green'><center><h5><b><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> PRESENTE</b></h5></center></font>");
	}
}
else
{
	$query = "INSERT INTO agendamento_requerente_andamento VALUES (null,$id_agendamento_requerente,3,'$hoje','Confirmado por: $nm_guerra')";
	if (mysqli_query($conn,$query) or die(mysql_error()))
	{
		echo("<font color='red'><center><h5><b><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> AUSENTE</b></h5></center></font>");
	}
}
?>
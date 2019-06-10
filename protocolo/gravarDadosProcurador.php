<?php

$nm_procurador = mb_strtoupper($_GET['nm_procurador'],'UTF-8');
$nr_tel_procurador = $_GET['telefone'];
$email_procurador = mb_strtolower($_GET['email'],'UTF-8');
$acao = $_GET['acao'];

$id_procurador = null;

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = null;

if($acao == "atualizar")
{
	$query = "UPDATE procurador SET nm_procurador = '$nm_procurador', nr_tel_procurador = '$nr_tel_procurador', email_procurador = '$email_procurador' WHERE nm_procurador = '$nm_procurador'";
}
else
{
	$query = "INSERT INTO procurador VALUES (null,'$nm_procurador','$nr_tel_procurador','$email_procurador')";
}
mysqli_query($conn,$query) or die(mysql_error());

?>
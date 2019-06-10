<?php
function limpaCPF_CNPJ($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor;
}

$acao = $_GET['acao'];
$cpf_cnpj = limpaCPF_CNPJ($_GET['cpf_cnpj']);
$sg_tipo_interessado = $_GET['sg_tipo_interessado'];
$nm_interessado = mb_strtoupper($_GET['nome'],'UTF-8');
$cidade_interessado = $_GET['cidade'];
$nr_tel_interessado = $_GET['contato'];
$email_interessado = mb_strtolower($_GET['email'],'UTF-8');
$cpf = null;
$cnpj = null;
$cr_interessado = null;
$tr_interessado = null;
$query = null;

if($sg_tipo_interessado == "PJ")
{
	$cnpj = $cpf_cnpj;
	$tr_interessado = $_GET['tr'];
	$cr_interessado = $_GET['cr'];
}
else
{	
	$cpf = $cpf_cnpj;
	$cr_interessado = $_GET['cr'];	
}

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
if($acao == "incluir")
{
	$query = "INSERT INTO interessado VALUES (null,$cidade_interessado,'$sg_tipo_interessado','$nm_interessado','$cpf','$cnpj','$cr_interessado','$tr_interessado','$nr_tel_interessado','$email_interessado')";
}
else
{
	if($sg_tipo_interessado == "PJ")
	{
		$query = "UPDATE interessado SET id_cidade = $cidade_interessado, sg_tipo_interessado = '$sg_tipo_interessado', nm_interessado = '$nm_interessado', cr_interessado = '$cr_interessado', tr_interessado = '$tr_interessado', nr_tel_interessado = '$nr_tel_interessado', email_interessado = '$email_interessado' WHERE cnpj_interessado = '$cnpj'";

	}
	else
	{
		$query = "UPDATE interessado SET id_cidade = $cidade_interessado, sg_tipo_interessado = '$sg_tipo_interessado', nm_interessado = '$nm_interessado', cr_interessado = '$cr_interessado', tr_interessado = '$tr_interessado', nr_tel_interessado = '$nr_tel_interessado', email_interessado = '$email_interessado' WHERE cpf_interessado = '$cpf'";	
	}	
}

mysqli_query($conn,$query) or die(mysql_error());

?>
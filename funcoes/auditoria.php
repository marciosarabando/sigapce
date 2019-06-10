<?php

function logar_falha_autenticação($usuario, $senha)
{
	$ip = getenv("REMOTE_ADDR");
	date_default_timezone_set('America/Sao_Paulo');
	$dt_evento = date('Y-m-d H:i');
	$obs_evento = 'Tentativa de Acesso Negado! Usuário: '. $usuario . ' - Senha: '. $senha;

	include ("conexao.php");
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = ("INSERT INTO evento VALUES (null, 1, '$dt_evento', '$ip', '$obs_evento')");
	mysqli_query($conn,$query) or die(mysql_error());

}

function logar_login_sucesso($usuario)
{
	$ip = getenv("REMOTE_ADDR");
	date_default_timezone_set('America/Sao_Paulo');
	$dt_evento = date('Y-m-d H:i');
	$obs_evento = 'Login realizado!: '. $usuario;

	include ("conexao.php");
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = ("INSERT INTO evento VALUES (null, 2, '$dt_evento', '$ip', '$obs_evento')");
	mysqli_query($conn,$query) or die(mysql_error());
}

function logar_consulta_interessado($cpf_cnpj, $protocolo)
{
	$ip = getenv("REMOTE_ADDR");
	date_default_timezone_set('America/Sao_Paulo');
	$dt_evento = date('Y-m-d H:i');
	$obs_evento = 'Processo Consultado pelo Requerente: '. $protocolo.' por CPF/CNPJ: '. $cpf_cnpj;

	include ("conexao.php");
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = ("INSERT INTO evento VALUES (null, 3, '$dt_evento', '$ip', '$obs_evento')");
	mysqli_query($conn,$query) or die(mysql_error());

}

?>
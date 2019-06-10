<?php
//VERIFICA SE A GRU INFORMADA JÁ ENCONTRA-SE NO SISTEMA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['login_sfpc']))
{
	$id_login = $_SESSION['id_login_sfpc'];
}
date_default_timezone_set('America/Sao_Paulo');
$dt_gru_tentativa_fraude = date('Y-m-d H:i');

$nr_autenticacao = mb_strtoupper($_GET['nr_autenticacao'],'UTF-8');
$nr_autenticacao = str_replace(".", "", $nr_autenticacao);
$id_interessado = $_GET['id_interessado'];
$id_procurador = $_GET['id_procurador'];
if($id_procurador == 0)
{
	$id_procurador = "null";
}
$id_gru = "";

$query = "SELECT 
			gru.id_gru,
			processo.id_processo,
			processo.cd_protocolo_processo
			FROM gru 
			INNER JOIN gru_processo on gru_processo.id_gru = gru.id_gru
			INNER JOIN processo on processo.id_processo = gru_processo.id_processo
			WHERE nr_autenticacao = '$nr_autenticacao'";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	  {
	  	$id_gru = $linha['id_gru'];
	  	$id_processo = $linha['id_processo'];
	  	$cd_protocolo_processo = $linha['cd_protocolo_processo'];
	  }
	 while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

if($id_gru != "")
{
	echo("<font color='red'>ATENÇÃO! GRU JÁ UTILIZADA NO PROCESSO <b>$cd_protocolo_processo</b></font></h6>");	
	$query = "INSERT INTO gru_tentativa_fraude VALUES ('$dt_gru_tentativa_fraude', $id_gru, $id_login, $id_interessado, $id_procurador)";
	mysqli_query($conn,$query) or die(mysql_error());
}
else
{
	echo("
			ok			
		");	
}

//echo("GRU VALIDA!");
//echo($nr_autenticacao);
//echo($query);

?>
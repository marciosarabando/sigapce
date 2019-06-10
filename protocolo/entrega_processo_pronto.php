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

//Verifica se o Status do Processo ainda é igual ao última da tela
//Se nenhum outro usuário já deu entregou o processo
$processo_entregue = 0;
$query = "SELECT processo_andamento.id_processo_status FROM processo_andamento WHERE processo_andamento.id_processo = $id_processo and processo_andamento.id_processo_status in (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = $id_processo)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['id_processo_status'] <> 8)
	  		$processo_entregue = 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}
//echo($processo_entregue);
if($processo_entregue == 0)
{
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = "INSERT INTO processo_andamento VALUES (null,$id_processo,$id_login,$id_processo_status,'$dt_processo_andamento','$obs_processo_andamento')";
	mysqli_query($conn,$query) or die(mysql_error());
}
?>
<?php
//VERIFICA SE A GRU INFORMADA JÁ ENCONTRA-SE NO SISTEMA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_servicos_selecionados = $_GET['id_servicos_selecionados'];
$st_gru = 0;

$query = "SELECT 
			st_gru
			FROM servico 
			WHERE id_servico in ($id_servicos_selecionados)";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	  {
	  	$st_gru = $linha['st_gru'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

if($st_gru != "0")
{
	echo("1");
}
else
{
	echo("0");	
}

?>
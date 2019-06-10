<?php
//VERIFICA SE A QUANTIDADE DEVE SER INFORMADA DE ACORDO COM A ATIVIDADE SELECIONADA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$id_adt_atividade_pj = $_GET['id_adt_atividade_pj'];

$informa_quantidade = 0;

$query = "	SELECT 
				st_solicita_quantidade
			FROM adt_atividade_pj 
			WHERE id_adt_atividade_pj = $id_adt_atividade_pj";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	do
	  {
	  	$st_solicita_quantidade = $linha['st_solicita_quantidade'];
	  	
	  	//VERIFICA SE A UNIDADE DE MEDIDA ESTÁ PREENCHIDA
	  	if($st_solicita_quantidade == 1)
	  	{
	  		$informa_quantidade = 1;
	  	}


	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

echo("<input hidden id='txt_st_informa_quantidade' value='$informa_quantidade'></input>");


?>
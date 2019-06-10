<?php
//EXIBE A UNIDADE DO PCE SELECIONADO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$id_pce = $_GET['id_pce'];

$query = "SELECT nm_adt_unidade_medida 
			FROM adt_pce 
			INNER JOIN adt_unidade_medida on adt_unidade_medida.id_adt_unidade_medida = adt_pce.id_adt_unidade_medida
			WHERE adt_pce.id_adt_pce = $id_pce";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	do
	  {
	  	$nm_adt_unidade_medida = $linha['nm_adt_unidade_medida'];  	
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

echo($nm_adt_unidade_medida);


?>
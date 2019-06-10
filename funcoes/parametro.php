<?php

function retornaValorParametro($id_unidade, $id_modulo, $nm_parametro)
{
	include ("conexao.php");	
	$valor = null;
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = "SELECT vl_parametro FROM parametro WHERE id_unidade = $id_unidade AND id_modulo = $id_modulo AND nm_parametro = '$nm_parametro'";

	// executa a query
	$dados = mysqli_query($conn,$query) or die(mysql_error());
	// transforma os dados em um array
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);

	if($totalLinhas > 0)
	{	
		do{
			$valor = $linha['vl_parametro'];
		}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	}

	return $valor;
}

function atualizaValorParametro($id_unidade, $id_modulo, $nm_parametro, $valor)
{
	include ("conexao.php");	
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = "UPDATE parametro SET vl_parametro = '$valor' WHERE id_unidade = $id_unidade AND id_modulo = $id_modulo AND nm_parametro = '$nm_parametro'";
	mysqli_query($conn,$query) or die(mysql_error());
}

?>
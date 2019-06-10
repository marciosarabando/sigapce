<?php
//CARREGA COMBO FORNECEDOR
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");


$query = "SELECT id_adt_arma_fornecedor, nm_adt_arma_fornecedor, cnpj FROM adt_arma_fornecedor order by nm_adt_arma_fornecedor";

echo("<select id='cmb_fornecedor' name='cmb_fornecedor' class='form-control input-sm' onchange='exibeCampoNotaFiscalArma()'>");

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	echo("<option value='0'>SELECIONE...</option>");
	do{
		echo("<option value='". $linha['id_adt_arma_fornecedor'] . "'>" . $linha['nm_adt_arma_fornecedor'] . "</option>");
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
}

echo("</select>");

?>
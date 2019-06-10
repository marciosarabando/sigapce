<?php


//CARREGA COMBO MARCA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");

$query = "SELECT id_adt_arma_marca, nm_adt_arma_marca FROM adt_arma_marca ORDER BY nm_adt_arma_marca";

echo("<select id='cmb_marca' name='cmb_marca' class='form-control input-sm' onchange='carrega_combo_modelo_arma_form_transf()'>");

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	echo("<option value='0'>SELECIONE...</option>");
	do{
		echo("<option value='". $linha['id_adt_arma_marca'] . "'>" . $linha['nm_adt_arma_marca'] . "</option>");
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
}

echo("</select>");

?>
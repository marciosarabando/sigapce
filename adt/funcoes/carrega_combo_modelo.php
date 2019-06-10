<?php
//CARREGA COMBO MODELO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$id_marca = $_GET['id_marca'];

$query = "	SELECT 
				id_adt_arma_modelo,
				nm_arma_modelo
			FROM adt_arma_modelo 
			WHERE id_adt_arma_marca = $id_marca";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	

	 echo("	
 		<div class='row'>
 			<div class='col-md-3'>
				<label>MODELO</label>
			</div>

			<div class='col-md-7'>
				<select id='cmb_modelo' name='cmb_modelo' class='form-control input-sm' onchange='carrega_combo_acabamento_arma()'>
				<option value='0' selected>SELECIONE...</option>
	"
	);	
	do
	  {
	  	

	  	echo("<option value='". $linha['id_adt_arma_modelo'] . "'>" . $linha['nm_arma_modelo'] . "</option>");

	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  		echo("
 				</select>
 			</div>
 		</div>
 		<p>
 	");
}


?>
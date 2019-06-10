<?php
//CARREGA COMBO ACABAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');



$query = "SELECT 
				id_adt_arma_acabamento,
				nm_adt_arma_acabamento
			FROM adt_arma_acabamento
			";

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
				<label>ACABAMENTO</label>
			</div>

			<div class='col-md-7'>
				<select id='cmb_acabamento' name='cmb_acabamento' class='form-control input-sm' onchange='valida_campos_form_adt_aqs_libera_botao_confirmar()'>
				<option value='0' selected>SELECIONE...</option>
	"
	);	
	do
	  {
	  	

	  	echo("<option value='". $linha['id_adt_arma_acabamento'] . "'>" . $linha['nm_adt_arma_acabamento'] . "</option>");

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
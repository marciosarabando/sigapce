<?php
//CARREGA COMBO PCE
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');


$id_adt_pce_tipo = $_GET['id_adt_pce_tipo'];

$query = "	SELECT 
				id_adt_pce,
				nm_pce,
				nr_ordem_pce
			FROM adt_pce
			WHERE id_adt_pce_tipo = $id_adt_pce_tipo 
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
 			<div class='col-md-4'>
				<label>PCE</label>
			</div>

			<div class='col-md-8'>
				<select id='cmb_pce' name='cmb_pce' class='form-control input-sm' onchange='habilita_botao_adicionar_atv_pce_pj()'>
				<option value='0' selected>SELECIONE...</option>
	"
	);	
	do
	  {
	  	

	  	echo("<option value='". $linha['id_adt_pce'] . "'>" . $linha['nr_ordem_pce'] . " - " . $linha['nm_pce'] . "</option>");

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
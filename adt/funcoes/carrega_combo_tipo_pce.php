<?php
//CARREGA COMBO TIPO PCE
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$nm_atividade_selecionada = $_GET['nm_atividade_selecionada'];

//PEGA A ÚLTIMA PALAVRA DA ATIVIDADE SELECIONADA 
//PARA FILTRAR A COMBO TIPO DE PCE
$array_atividade_selecionada = explode(" ", $nm_atividade_selecionada);
$ultima_palavra_atividade_selecionada = $array_atividade_selecionada[count($array_atividade_selecionada) - 1];

//echo($ultima_palavra_atividade_selecionada);

$query = "	SELECT 
				id_adt_pce_tipo,
				nm_adt_pce_tipo
			FROM adt_pce_tipo 
			
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
				<label>TIPO PCE</label>
			</div>

			<div class='col-md-8'>
				<select id='cmb_tipo_pce' name='cmb_tipo_pce' class='form-control input-sm' onchange='carrega_combo_pce()'>
				<option value='0' selected>SELECIONE...</option>
	"
	);	
	do
	  {
	  	

	  	echo("<option value='". $linha['id_adt_pce_tipo'] . "'>" . $linha['nm_adt_pce_tipo'] . "</option>");

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
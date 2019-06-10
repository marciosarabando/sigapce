<?php
//CARREGA COMBO ATIVIDADE
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$id_atividade_pj_tipo = $_GET['id_atividade_pj_tipo'];

$query = "	SELECT 
				id_adt_atividade_pj,
				nm_completo_adt_atividade_pj
			FROM adt_atividade_pj 
			WHERE id_adt_atividade_pj_tipo = $id_atividade_pj_tipo";

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
				<label>ATIVIDADE</label>
			</div>

			<div class='col-md-8'>
				<select id='cmb_atividade' name='cmb_atividade' class='form-control input-sm' onchange='carrega_combo_tipo_pce()'>
				<option value='0' selected>SELECIONE...</option>
	"
	);	
	do
	  {
	  	

	  	echo("<option value='". $linha['id_adt_atividade_pj'] . "'>" . $linha['nm_completo_adt_atividade_pj'] . "</option>");

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
<?php
//EXIBE CADASTRO DE NOVA MARCA DE ARMA NO MODAL
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

//BOTAO DE INCLUSÃO DE NOVA MARCA
echo("	
		<div id='div_btn_nova_marca'>
			<button id='btn_incluir_nova_marca_arma' class='btn btn-primary btn-sm' onclick='mostra_form_nova_marca_arma()'><i class='glyphicon glyphicon-plus-sign'></i> INCLUIR NOVA MARCA</button>
		</div>
	");

//FORM INPUT NOVA MARCA
echo("
		<div class='row' id='div_form_cad_marca' hidden>
			<div class='col-md-2'>	
				<label>MARCA:</label>
			</div>
			<div class='col-md-8'>	
				<input id='txt_cad_marca' type='text' class='upper form-control input-sm'></input>
			</div>
			<div class='col-md-2'>
				<button id='btn_insere_marca' class='btn btn-success btn-sm' onclick='incluir_nova_marca_arma()'><i class='glyphicon glyphicon-floppy-disk'></i></button>
			</div>
		</div>
	");


echo("<hr>");

//EXIBE CADASTRO DE MARCA DE ARMA
$query = "SELECT id_adt_arma_marca, nm_adt_arma_marca FROM adt_arma_marca";
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
$count = 0;
if($totalLinhas > 0)
{
	do
	  {
	  		$id_adt_arma_marca[$count] = $linha['id_adt_arma_marca'];
	  		$nm_adt_arma_marca[$count] = $linha['nm_adt_arma_marca'];
	  		$count = $count + 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  echo("
	  		<div class='panel panel-default'>
				<div class='panel-body'>

		  			<div class='table-responsive'>
		  				<table id='tb_marca_armamento' class='table table-responsive table-condensed'>
		  					<thead>
		  						<th>
		  							MARCA DE ARMAMENTO
		  						</th>
		  					</thead>
		  					<tbody>
	  	");
	  for($x = 0; $x < $count; $x++)
	  {
	  	echo("
			  					<tr>
			  						<td>
			  							<font color='green'><b>$nm_adt_arma_marca[$x]</b></font>
			  						</td>
			  					</tr>
			");		  				
	  }

		  echo("			</tbody>
		  				</table>
		  			</div>
		  		</div>
		  	</div>
	  		");

}
else
{
	echo("<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não foram encontrados registros de Marca de Armamento.</p>");
}

?>
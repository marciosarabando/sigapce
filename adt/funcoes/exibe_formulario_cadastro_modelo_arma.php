<?php
//EXIBE CADASTRO DE NOVO MODELO DE ARMA NO MODAL
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$id_marca = $_GET['id_marca'];

//BOTAO DE INCLUSÃO DE NOVO MODELO
echo("	
		<div id='div_btn_novo_modelo'>
			<button id='btn_incluir_novo_modelo_arma' class='btn btn-primary btn-sm' onclick='mostra_form_novo_modelo_arma()'><i class='glyphicon glyphicon-plus-sign'></i> INCLUIR NOVO MODELO</button>
		</div>
	");

//FORM INPUT NOVO MODELO
echo("
		<div id='div_form_cad_modelo' hidden>
			<div class='row'>
				<div class='col-md-4'>	
					<label>MODELO:</label>
				</div>
				<div class='col-md-7'>	
					<input id='txt_cad_nm_modelo' type='text' class='upper form-control input-sm'></input>
				</div>
				<div id='div_valida_modelo'>

				</div>
			</div>

			<p>

			<div class='row'>
				<div class='col-md-4'>	
					<label>ESPÉCIE:</label>
				</div>
				<div class='col-md-7'>
	");

					//CARREGA COMBO ESPÉCIE
					echo("<select id='cmb_especie' name='cmb_especie' class='form-control input-sm' onchange=''>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_especie, nm_adt_arma_especie FROM adt_arma_especie";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_especie'] . "'>" . $linha['nm_adt_arma_especie'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");



echo("	
	
				</div>

				<div id='div_valida_especie'>

				</div>


			</div>

			<p>

			<div class='row'>
				<div class='col-md-4'>	
					<label>CALIBRE:</label>
				</div>
				<div class='col-md-7'>	
	");	

					//CARREGA COMBO CALIBRE
					echo("<select id='cmb_calibre' name='cmb_calibre' class='form-control input-sm' onchange=''>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_calibre, nm_adt_arma_calibre FROM adt_arma_calibre";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_calibre'] . "'>" . $linha['nm_adt_arma_calibre'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");


echo("	
				</div>

				<div id='div_valida_calibre'>

				</div>


			</div>

			<p>

			<div class='row'>
				<div class='col-md-4'>	
					<label>FUNCIONAMENTO:</label>
				</div>
				<div class='col-md-7'>	
	");						
					//CARREGA COMBO FUNCIONAMENTO
					echo("<select id='cmb_funcionamento' name='cmb_funcionamento' class='form-control input-sm' onchange=''>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_funcionamento, nm_adt_arma_funcionamento FROM adt_arma_funcionamento";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_funcionamento'] . "'>" . $linha['nm_adt_arma_funcionamento'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");
				

echo("
				</div>

				<div id='div_valida_funcionamento'>

				</div>

			</div>

			<p>

			<div class='row'>
				<div class='col-md-4'>	
					<label>ALMA:</label>
				</div>
				<div class='col-md-7'>	
	");			
					//CARREGA COMBO ALMA
					echo("<select id='cmb_alma' name='cmb_alma' class='form-control input-sm' onchange='exibe_oculta_input_raia_arma()'>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_alma, nm_adt_arma_alma FROM adt_arma_alma";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_alma'] . "'>" . $linha['nm_adt_arma_alma'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");
				

echo("
				</div>

				<div id='div_valida_alma'>

				</div>

			</div>

			<p>

			<div id='div_campos_raia_arma' hidden>

				<div class='row'>
					<div class='col-md-4'>	
						<label>SENTIDO RAIA:</label>
					</div>
					<div class='col-md-2'>	
						<select id='cmb_sentido_raia' name='cmb_sentido_raia' class='form-control input-sm' onchange=''>
							<option value='0'>SELECIONE...</option>
							<option value='1'>DIREITA</option>
							<option value='2'>ESQUERDA</option>
						</select>
					</div>
				
					<div id='div_valida_sentido_raia'>

					</div>
				
					<div class='col-md-3'>	
						<label>QTD RAIA:</label>
					</div>
					<div class='col-md-2'>	
						<input id='txt_qtd_raia' type='text' class='upper form-control input-sm' onkeydown='return SomenteNumero(event)'></input>
					</div>

					<div id='div_valida_qtd_raia'>

					</div>

				</div>			

				<p>

			</div>

			<div class='row'>
				
				<div class='col-md-4'>	
					<label>QTD CANO:</label>
				</div>
				
				<div class='col-md-2'>	
					<select id='cmb_qtd_cano' name='cmb_qtd_cano' class='form-control input-sm' onchange=''>
						<option value='1'>1</option>
						<option value='2'>2</option>
					</select>
				</div>

				<div id='div_valida_qtd_cano'>

				</div>

				<div class='col-md-3'>	
					<label>COMPRIMENTO:</label>
				</div>

				<div class='col-md-2'>	
					<input id='txt_comprimento_cano' type='text' class='upper form-control input-sm'></input>
				</div>

				<div id='div_valida_comprimento_cano'>

				</div>

			</div>

			<p>

			<div class='row'>
				<div class='col-md-4'>	
					<label>CAPACIDADE CARREGADOR:</label>
				</div>
				<div class='col-md-7'>	
					<input id='txt_nr_capacidade_carregador' type='text' class='upper form-control input-sm' onkeydown='return SomenteNumero(event)'></input>
				</div>

				<div id='div_valida_capacidade_carregador'>

				</div>

			</div>

		</div>

	");


//BOTAO SALVAR MODELO
echo("<div id='div_btn_salvar_modelo' hidden>
			<br>
			<button id='btn_incluir_novo_modelo_arma' class='btn btn-success btn-sm btn-block' onclick='incluir_novo_modelo_arma()'><i class='glyphicon glyphicon-floppy-disk'></i> SALVAR MODELO</button>
	</div>");

echo("<hr>");

$query = "SELECT 
				adt_arma_modelo.nm_arma_modelo,
		        adt_arma_especie.nm_adt_arma_especie,
		        adt_arma_calibre.nm_adt_arma_calibre,
		        adt_arma_funcionamento.nm_adt_arma_funcionamento
		      
		FROM adt_arma_modelo
		INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
		INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma_modelo.id_adt_arma_calibre
		INNER JOIN adt_arma_funcionamento on adt_arma_funcionamento.id_adt_arma_funcionamento = adt_arma_modelo.id_adt_arma_funcionamento
		INNER JOIN adt_arma_alma on adt_arma_alma.id_adt_arma_alma = adt_arma_modelo.id_adt_arma_alma
		INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie

		WHERE adt_arma_modelo.id_adt_arma_marca = $id_marca
		";


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
	  		$nm_arma_modelo[$count] = $linha['nm_arma_modelo'];
	  		$nm_adt_arma_especie[$count] = $linha['nm_adt_arma_especie'];
		    $nm_adt_arma_calibre[$count] = $linha['nm_adt_arma_calibre'];
		    $nm_adt_arma_funcionamento[$count] = $linha['nm_adt_arma_funcionamento'];
		   
	  		
	  		$count = $count + 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);


	   echo("
	  		<div class='panel panel-default'>
				<div class='panel-body'>

		  			<div class='table-responsive'>
		  				<table id='tb_modelo_armamento' class='table table-responsive table-condensed'>
		  					<thead>
		  						<th>
		  							MODELO
		  						</th>
		  						<th>
		  							ESPÉCIE
		  						</th>
		  						<th>
		  							CALIBRE
		  						</th>
		  						<th>
		  							FUNCIONAMENTO
		  						</th>
		  						
		  					</thead>
		  					<tbody>
	  	");
	  for($x = 0; $x < $count; $x++)
	  {
	  	echo("
			  					<tr>
			  						<td>
			  							<font color='green'><b>$nm_arma_modelo[$x]</b></font>
			  						</td>
			  						<td>
			  							$nm_adt_arma_especie[$x]
			  						</td>
			  						<td>
			  							$nm_adt_arma_calibre[$x]
			  						</td>
			  						<td>
			  							$nm_adt_arma_funcionamento[$x]
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
	echo("<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não foram encontrados registros para este Modelo de Armamento.</p>");
}

echo("<input id='txt_id_marca' value='$id_marca' hidden></input>");

?>
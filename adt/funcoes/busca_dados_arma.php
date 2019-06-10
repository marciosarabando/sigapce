<?php
//BUSCAR DADOS DA ARMA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$nr_arma = $_GET['nr_arma'];
$id_adt_materia_tipo = $_GET['id_adt_materia_tipo']; 
$mostra_sigma= $_GET['mostra_sigma']; 

/*
$query = "SELECT
					adt_arma_especie.nm_adt_arma_especie, 
					adt_arma_funcionamento.nm_adt_arma_funcionamento, 
					adt_arma_marca.nm_adt_arma_marca, 
					adt_arma_calibre.nm_adt_arma_calibre,
					adt_arma_modelo.nm_arma_modelo,
					adt_arma.nr_arma, 
					adt_arma_pais_origem.nm_adt_arma_pais_origem,
					adt_arma_acabamento.nm_adt_arma_acabamento 
				
					 
					FROM adt_arma 

					INNER JOIN adt_arma_modelo ON adt_arma.id_adt_arma_modelo = adt_arma_modelo.id_adt_arma_modelo
					INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie
					INNER JOIN adt_arma_funcionamento on adt_arma_funcionamento.id_adt_arma_funcionamento = adt_arma_modelo.id_adt_arma_funcionamento
					INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
					INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma_modelo.id_adt_arma_calibre
					INNER JOIN adt_arma_pais_origem on adt_arma_pais_origem.id_adt_arma_pais_origem = adt_arma.id_adt_arma_pais_origem
					INNER JOIN adt_arma_acabamento ON adt_arma_acabamento.id_adt_arma_acabamento = adt_arma.id_adt_arma_acabamento
										
					WHERE adt_arma.nr_arma = '$nr-arma'
					";

*/

$query = "SELECT 
				adt_arma_especie.nm_adt_arma_especie,
				adt_arma_marca.id_adt_arma_marca,
			    adt_arma_marca.nm_adt_arma_marca,
			    adt_arma_modelo.id_adt_arma_modelo,
			    adt_arma_modelo.nm_arma_modelo,
			    adt_arma.nr_arma,
			    adt_arma.nr_sigma,
			    adt_arma.id_adt_arma_calibre,
			    adt_arma_calibre.nm_adt_arma_calibre, 
			    adt_arma.id_adt_arma_pais_origem, 
			    adt_arma_pais_origem.nm_adt_arma_pais_origem,
			    adt_arma.id_adt_arma_acabamento,  
			    adt_arma_acabamento.nm_adt_arma_acabamento

			FROM adt_arma
			
			INNER JOIN adt_arma_modelo ON adt_arma.id_adt_arma_modelo = adt_arma_modelo.id_adt_arma_modelo
			INNER JOIN adt_arma_especie on adt_arma_especie.id_adt_arma_especie = adt_arma_modelo.id_adt_arma_especie
			
			INNER JOIN adt_arma_marca on adt_arma_marca.id_adt_arma_marca = adt_arma_modelo.id_adt_arma_marca
			INNER JOIN adt_arma_calibre on adt_arma_calibre.id_adt_arma_calibre = adt_arma.id_adt_arma_calibre
			INNER JOIN adt_arma_pais_origem on adt_arma_pais_origem.id_adt_arma_pais_origem = adt_arma.id_adt_arma_pais_origem
			INNER JOIN adt_arma_acabamento ON adt_arma_acabamento.id_adt_arma_acabamento = adt_arma.id_adt_arma_acabamento

			WHERE adt_arma.nr_arma = '$nr_arma'";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//SE ACHOU A ARMA...
if($totalLinhas > 0)
{
	do
	  {
	  		$nm_adt_arma_especie = $linha['nm_adt_arma_especie'];
	  		$id_adt_arma_marca = $linha['id_adt_arma_marca'];
			$nm_adt_arma_marca = $linha['nm_adt_arma_marca'];
			$id_adt_arma_modelo = $linha['id_adt_arma_modelo'];
			$nm_arma_modelo = $linha['nm_arma_modelo'];
			$nr_arma = $linha['nr_arma'];
			$nr_sigma = $linha['nr_sigma'];
			$id_adt_arma_calibre = $linha['id_adt_arma_calibre'];
			$nm_adt_arma_calibre = $linha['nm_adt_arma_calibre'];
			$id_adt_arma_pais_origem = $linha['id_adt_arma_pais_origem'];
			$nm_adt_arma_pais_origem = $linha['nm_adt_arma_pais_origem'];
			$id_adt_arma_acabamento = $linha['id_adt_arma_acabamento'];
			$nm_adt_arma_acabamento = $linha['nm_adt_arma_acabamento'];
	  
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  echo" 
    		<p>

    		<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>NR SIGMA:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<input type='text' id='txt_nr_sigma' class='form-control input-sm' value='$nr_sigma'></input>
	 	 		</div>
	 	 	</div>
			"; 

		echo "	
			<p>

  			<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>ESPÉCIE:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<input id='txt_especie' type='text' class='form-control input-sm' value='$nm_adt_arma_especie' disabled></input>
	 	 		</div>
	 	 	</div>

	 	 	<p>

	 	 	<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>MARCA:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<select id='cmb_marca' name='cmb_marca' class='form-control input-sm' disabled>
						<option value='$id_adt_arma_marca' selected='selected'>$nm_adt_arma_marca</option>
	 	 			</select>
	 	 			
	 	 		</div>
	 	 	</div>

	 	 	<p>

	 	 	<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>MODELO:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<select id='cmb_modelo' name='cmb_modelo' class='form-control input-sm' disabled>
						<option value='$id_adt_arma_modelo' selected='selected'>$nm_arma_modelo</option>
	 	 			</select>
	 	 		</div>
	 	 	</div>

	 	 	<p>
	 	 	

	 	 	<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>CALIBRE:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<select id='cmb_calibre' name='cmb_calibre' class='form-control input-sm' disabled>
						<option value='$id_adt_arma_calibre' selected='selected'>$nm_adt_arma_calibre</option>
	 	 			</select>
	 	 			
	 	 		</div>
	 	 	</div>

	<p>
	 	 	<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>ORIGEM:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<select id='cmb_arma_pais_origem' name='cmb_arma_pais_origem' class='form-control input-sm' onchange='exibe_combo_acabamento_form_transf()' disabled>
	 	 			<option value='$id_adt_arma_pais_origem' selected='selected'>$nm_adt_arma_pais_origem</option>
	 	 			</select>
	 	 		</div>
	 	 	</div>
	<p>

	 	 	<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			<label>ACABAMENTO:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<select id='cmb_arma_acabamento' name='cmb_arma_acabamento' class='form-control input-sm' onfocus='valida_campos_form_adt_transf_arma_libera_botao_confirmar()' disabled>
	 	 			<option value='$id_adt_arma_acabamento' selected='selected'>$nm_adt_arma_acabamento</option>
	 	 			</select>
	 	 		</div>
	 	 	</div>
	
<!-- Gambiarra para ativar o botão de confirmar sem precisar do focus ou onchange nos inputs acima --> 	
<iframe style='width:0;height:0;border:0; border:none;' onload='valida_campos_form_adt_transf_arma_libera_botao_confirmar()'></iframe>

  	";

//ARMA ENCONTRADA
echo("<input id='st_arma' value='1' hidden></input>");

}

//ARMA NÃO CADASTRADA, EXIBE FORMULARIO PARA PREENCHIMENTO
else
{
	
	if($mostra_sigma == 1)
	{
	 echo"
    		<p>

    
    		<div class='row'>
	 	 		<div class='col-md-3'>
	 	 			";

	 	 			if($id_adt_materia_tipo == 39)
	 	 				echo "<label>Nr SINARM:</label>"; 
	 	 			else
	 	 				echo "<label>SIGMA / SINARM:</label>";

	 	 			echo "
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<input id='txt_nr_sigma' type='text' class='form-control input-sm' value='' placeholder='Informe o Nr SIGMA ou SINARM' onkeyup='exibe_oculta_marca_arma_form_transf_arma($mostra_sigma)' onkeypress='return SomenteNumero(event)'></input>
	 	 		</div>
	 	 	</div>
	 	 	"; 
	}
	else
	{
		echo"
    		   
    		<div class='row' hidden>
	 	 		<div class='col-md-3'>
	 	 			";

	 	 			if($id_adt_materia_tipo == 39)
	 	 				echo "<label>Nr SINARM:</label>"; 
	 	 			else
	 	 				echo "<label>SIGMA / SINARM:</label>";

	 	 			echo "
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<input id='txt_nr_sigma' type='text' class='form-control input-sm' value=''></input>
	 	 		</div>
	 	 	</div>
	 	 	"; 
	    			 
	}

		echo "
	 	 	<p>

    		<div class='row' id='div_marca_arma' hidden>
	 	 		<div class='col-md-3'>
	 	 			<label>MARCA:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<div id='div_combo_marca'></div>
	 	 		</div>

	 	 		<!-- Button trigger modal CADASTRO GERAL-->
	 	 		<div class='col-md-2' id='div_btn_incluir_marca'>
	 	 			<button type='button' class='btn btn-primary btn-sm' onclick='exibe_cadastro_marca_arma()' data-toggle='modal' data-target='#myModalCadastro'><span class='glyphicon glyphicon-plus-sign'></span></button>
	 	 		</div>
	 	 	</div>
	 	 ";

	 
	 echo("
	 		<p>
	 		<div class='row'  id='div_modelo_arma' hidden>
	 	 		<div class='col-md-3'>
	 	 			<label>MODELO:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<div id='div_combo_modelo_form_transf'></div>
	 	 		</div>

	 	 		<!-- Button trigger modal CADASTRO GERAL-->
	 	 		<div class='col-md-2' id='div_btn_incluir_modelo'>
	 	 			<button type='button' class='btn btn-primary btn-sm' onclick='exibe_cadastro_modelo_arma()' data-toggle='modal' data-target='#myModalCadastro'><span class='glyphicon glyphicon-plus-sign'></span></button>
	 	 		</div>
	 	 	</div>
	 	 ");

	  echo("
	 		<p>
	 		<div class='row'  id='div_calibre_arma' hidden>
	 	 		<div class='col-md-3'>
	 	 			<label>CALIBRE:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 			<div id='div_combo_calibre_form_transf'></div>
	 	 		</div>

	 	 		<!-- Button trigger modal CADASTRO GERAL-->
	 	 		<div class='col-md-2' id='div_btn_incluir_calibre'>
	 	 			<button type='button' class='btn btn-primary btn-sm' onclick='exibe_cadastro_calibre_arma()' data-toggle='modal' data-target='#myModalCadastro'><span class='glyphicon glyphicon-plus-sign'></span></button>
	 	 		</div>
	 	 	</div>
	 	 ");

	 echo("
	 		
	 		<div class='row' id='div_origem_arma' hidden>
	 	 		<div class='col-md-3'>
	 	 			<label>ORIGEM:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 ");

 					//CARREGA COMBO ORIGEM ARMAMENTO
					echo("<select id='cmb_arma_pais_origem' name='cmb_arma_pais_origem' class='form-control input-sm' onchange='exibe_combo_acabamento_form_transf()'>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_pais_origem, nm_adt_arma_pais_origem FROM adt_arma_pais_origem";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_pais_origem'] . "'>" . $linha['nm_adt_arma_pais_origem'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");
	 echo("
	 	 		</div>
	 	    </div>
	 	 ");

	 echo("
	 		<p>
	 		<div class='row'  id='div_acabamento_arma' hidden>
	 	 		<div class='col-md-3'>
	 	 			<label>ACABAMENTO:</label>
	 	 		</div>

	 	 		<div class='col-md-7'>
	 	 ");

 					//CARREGA COMBO ACABAMENTO ARMAMENTO
					echo("<select id='cmb_arma_acabamento' name='cmb_arma_acabamento' class='form-control input-sm' onchange='valida_campos_form_adt_transf_arma_libera_botao_confirmar()'>");
					// //Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT id_adt_arma_acabamento, nm_adt_arma_acabamento FROM adt_arma_acabamento";
					$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$linha = mysqli_fetch_assoc($dados);
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_adt_arma_acabamento'] . "'>" . $linha['nm_adt_arma_acabamento'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}
					echo("</select>");
	 echo("
	 	 		</div>
	 	    </div>
	 	    <p>
	 	 ");


//ARMA NÃO ENCONTRADA
echo("<input id='st_arma' value='0' hidden></input>");
}

?>
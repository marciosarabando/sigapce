<?php
//FORMULÁRIO DE INSERÇÃO DE DADOS PARA MATÉRIA
//TIPO DA MATÉRIA: AQUISIÇÃO DE ARMA

include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

echo("

	");

$id_adt_materia_tipo = $_GET['id_adt_materia_tipo']; 

//se o tipo da matéria não precisa da nota fiscal (autz / exclusão) 
if(
	$id_adt_materia_tipo == 84
 or $id_adt_materia_tipo == 78 
 or $id_adt_materia_tipo == 25
 or $id_adt_materia_tipo == 27
 or $id_adt_materia_tipo == 30
 or $id_adt_materia_tipo == 31
 or $id_adt_materia_tipo == 32
 or $id_adt_materia_tipo == 33
 or $id_adt_materia_tipo == 34
 or $id_adt_materia_tipo == 24
 or $id_adt_materia_tipo == 37
 or $id_adt_materia_tipo == 38
 or $id_adt_materia_tipo == 36
 or $id_adt_materia_tipo == 88
 or $id_adt_materia_tipo == 39
 or $id_adt_materia_tipo == 42
 or $id_adt_materia_tipo == 74
)
{	
	$mostra_nf = 0;
	$mostra_fornecedor = 0; 

	if($id_adt_materia_tipo == 84)
		$mostra_fornecedor = 1; 		
}
else
{
	$mostra_nf = 1; 
	$mostra_fornecedor = 1;
}

//se não precisa do nr do sigma...
if(
	$id_adt_materia_tipo == 84
 or $id_adt_materia_tipo == 33 
 or $id_adt_materia_tipo == 86
 or $id_adt_materia_tipo == 35
 or $id_adt_materia_tipo == 34
 or $id_adt_materia_tipo == 85
)
{	
	$mostra_sigma = 0;
}
else
{
	$mostra_sigma = 1; 
}



?>

<div class='row'>
	<div class='col-md-12'>
		<fieldset>
		 	 	<legend>MATÉRIA PARA O ADITAMENTO AO BAR</legend>
		 	 	

		 	 	 <div class="row">
		 	 		<div class="col-md-3">
	               		<label>ACERVO</label>
	              	</div>
	              	<div class="col-md-7">
	              			
	              		<select autofocus id='cmb_acervo' name='cmb_acervo' class='form-control input-sm' onchange='exibeComboFornecedorArma(<?php echo($mostra_fornecedor);?>)'
	              		 onfocus='exibeComboFornecedorArma(<?php echo($mostra_fornecedor);?>)'>
		 	 						<option value='0'>SELECIONE...</option>
		 	 						 <?php
								      //Preenche o combo do FORNECEDOR
								      //Conecta no Banco de Dados
								      include ("../../funcoes/conexao.php");
								      mysqli_query($conn,"SET NAMES 'utf8';");

								      $query = "SELECT 	id_adt_acervo, nm_adt_acervo	      					
								      	 		FROM adt_acervo";

								      // executa a query
								      $dados = mysqli_query($conn,$query) or die(mysql_error($conn));
								      // transforma os dados em um array
								      $linha = mysqli_fetch_assoc($dados);
								      // calcula quantos dados retornaram
								      $totalLinhas = mysqli_num_rows($dados);

								      if($totalLinhas > 0)
								      {
								        do{

									        if($id_adt_materia_tipo == 27 and $linha['id_adt_acervo'] == 3)
									        	echo("<option selected='selected' value='". $linha['id_adt_acervo'] . "'>" . $linha['nm_adt_acervo'] . "</option>");
									        										        	
									        else	
									          echo("<option value='". $linha['id_adt_acervo'] . "'>" . $linha['nm_adt_acervo'] . "</option>");

								        }while($linha = mysqli_fetch_assoc($dados));
								        mysqli_free_result($dados);
								        
								      }


								    ?>              
		 	 			</select>	
	              	</div>
		 	 	</div>

		 	 	<p>

		 	 	
		 	 	<div id='div_cmb_fornecedor' hidden>
			 	 	<div class="row">
			 	 		<div class="col-md-3">
		               		<label>FORNECEDOR</label>
		              	</div>
		              	<div class="col-md-7" id='div_combo_fornecedor'>
		              		<select id='cmb_fornecedor' name='cmb_fornecedor' class='form-control input-sm' onchange='exibeCampoNotaFiscalArma(<?php echo $mostra_nf; ?>)'>

			 	 						<option value='0'>SELECIONE...</option>
			 	 						 <?php
									      //Preenche o combo do FORNECEDOR
									      //Conecta no Banco de Dados
									      include ("../../funcoes/conexao.php");
									      mysqli_query($conn,"SET NAMES 'utf8';");
									      $query = "SELECT 
									      					id_adt_arma_fornecedor,
									      					nm_adt_arma_fornecedor,
									      					cnpj
									      	 			FROM adt_arma_fornecedor";
									      // executa a query
									      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
									      // transforma os dados em um array
									      $linha = mysqli_fetch_assoc($dados);
									      // calcula quantos dados retornaram
									      $totalLinhas = mysqli_num_rows($dados);

									      if($totalLinhas > 0)
									      {
									        do{
									          echo("<option value='". $linha['id_adt_arma_fornecedor'] . "'>" . $linha['nm_adt_arma_fornecedor'] . "</option>");

									        }while($linha = mysqli_fetch_assoc($dados));
									        mysqli_free_result($dados);
									        
									      }
									    ?>              
			 	 			</select>	
		              	</div>
                        
                        <!-- Button trigger modal CADASTRO GERAL-->
                        <div class='col-md-2' id='div_btn_incluir_marca'>
                            <button type='button' class='btn btn-primary btn-sm' onclick='exibe_cadastro_fornecedor()' data-toggle='modal' data-target='#myModalCadastro'><span class='glyphicon glyphicon-plus-sign'></span></button>
                        </div>

			 	 	</div>
			 	</div>

		 	 	<p>

<?php
	//a supressão da NF e fornecedor é feita no JS	--> exibeCampoNotaFiscalArma(mostra_nf)
		echo "
		 	 	<div id='div_txt_nr_nota_fiscal' hidden>
			 	 	<div class='row'>
			 	 		<div class='col-md-3'>
		               		<label>N° DA NF ou SISCOMEX</label>
		              	</div>
		              	<div class='col-md-7'>
		              		<input type='text' onkeyup='exibeCampoNumeroArma($mostra_nf)' onblur='exibeCampoNumeroArma($mostra_nf)' class='form-control input-sm' id='txt_nr_nota_fiscal' name='txt_nr_nota_fiscal' placeholder='Digite o Numero da Nota Fiscal ou SISCOMEX'/>
		              	</div>
		            </div>
		        </div>

	            <p>
	        "; 


	

?>

<div class="row" id='div_txt_nr_arma' hidden>
		 	 		<div class="col-md-3">
		 	 			<label>NR SÉRIE:</label>
		 	 		</div>
		 	 		<div class="col-md-7">
		 	 			
		 	 			
			 	 			<div class="input-group">
						      <input type="text" class="upper form-control input-sm" id="txt_nr_arma" name="txt_nr_arma" placeholder="Digite o Nº da Arma" onkeypress="busca_dados_arma_enter(<?php echo $mostra_sigma; ?>)" onkeyup='exibe_oculta_form_arma_trans()' onkeydown='return nao_aceita_espaco(event)'>
						      <span class="input-group-btn">
						        <button id='btn_busca_dados_arma' class="btn btn-primary btn-sm" type="button" onclick='busca_dados_arma(<?php echo $mostra_sigma; ?>)'><span class='glyphicon glyphicon-search'></span></button>
						      </span>
						    </div><!-- /input-group -->

		 	 		</div>
		 	 		
				</div>


				<div id='div_dados_arma'>

					<!-- DIV ALIMENTADA PELA PAGINA adt/funcoes/busca_dados_arma.php  -->
				</div>

<!--
	            <div id='div_txt_nr_arma' hidden>
		            <div class="row">
			 	 		<div class="col-md-3">
		               		<label>N° DE SÉRIE</label>
		              	</div>
		              	<div class="col-md-7">
		              		<input type="text" onkeyup="exibeComboOrigemArma()" onblur="exibeComboOrigemArma()" class="form-control input-sm" id="txt_nr_arma" name="txt_nr_arma" placeholder="Digite o Numero da Arma"/>
		              	</div>
		            </div>
		        </div>

	            <p>

	            <div id='div_cmb_origem' hidden>
		            <div class="row">
			 	 		<div class="col-md-3">
		               		<label>ORIGEM</label>
		              	</div>
		              	<div class="col-md-7">
		              		<select id='cmb_origem' name='cmb_origem' class='form-control input-sm' onchange='exibeComboMarcaArma()'>
			 	 						<option value='0'>SELECIONE...</option>
			 	 						 <?php
									      //Preenche o combo da ORIGEM
									      //Conecta no Banco de Dados
									      include ("../../funcoes/conexao.php");
									      mysqli_query($conn,"SET NAMES 'utf8';");
									      $query = "SELECT 
									      					id_adt_arma_pais_origem,
									      					nm_adt_arma_pais_origem
									      	 			FROM adt_arma_pais_origem";
									      // executa a query
									      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
									      // transforma os dados em um array
									      $linha = mysqli_fetch_assoc($dados);
									      // calcula quantos dados retornaram
									      $totalLinhas = mysqli_num_rows($dados);

									      if($totalLinhas > 0)
									      {
									        do{
									          echo("<option value='". $linha['id_adt_arma_pais_origem'] . "'>" . $linha['nm_adt_arma_pais_origem'] . "</option>");

									        }while($linha = mysqli_fetch_assoc($dados));
									        mysqli_free_result($dados);
									        
									      }
									    ?>              
			 	 			</select>		
		              	</div>
		            </div>
		        </div>

	            <p>

	            <div id='div_cmb_marca' hidden>
		            <div class="row">
			 	 		<div class="col-md-3">
		               		<label>MARCA</label>
		              	</div>
		              	<div class="col-md-7">
		              		<select id='cmb_marca' name='cmb_marca' class='form-control input-sm' onchange='carrega_combo_modelo()'>
			 	 						<option value='0'>SELECIONE...</option>
			 	 						 <?php
									      //Preenche o combo da MARCA
									      //Conecta no Banco de Dados
									      include ("../../funcoes/conexao.php");
									      mysqli_query($conn,"SET NAMES 'utf8';");
									      $query = "SELECT 
									      					id_adt_arma_marca,
									      					nm_adt_arma_marca
									      	 			FROM adt_arma_marca";
									      // executa a query
									      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
									      // transforma os dados em um array
									      $linha = mysqli_fetch_assoc($dados);
									      // calcula quantos dados retornaram
									      $totalLinhas = mysqli_num_rows($dados);

									      if($totalLinhas > 0)
									      {
									        do{
									          echo("<option value='". $linha['id_adt_arma_marca'] . "'>" . $linha['nm_adt_arma_marca'] . "</option>");

									        }while($linha = mysqli_fetch_assoc($dados));
									        mysqli_free_result($dados);
									        
									      }
									    ?>              
			 	 			</select>		
		              	</div>
		            </div>
		        </div>

	            <p>

	            <div id='div_cmb_modelo'></div>

				<p>

	            <div id='div_cmb_acabamento'></div>

		 	 		-->
		 	 			

		 	 	<div id='div_info_dados_arma_aqs' class="alert alert-info">
		 	 		<p class='text-info'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> Informe os Dados da Arma do Requerente</p>
		 	 	</div>


		 	 	<div id='div_btn_confirma_dados' hidden>
		 	 		<button class="btn btn-success btn-block" id='btn_confirma_atividades' type="button" onclick='confirmar_dados_form_adt_aqs()'>CONFIRMAR DADOS</button>
		 	 	</div>

		 	 	<div id='div_info_prosseguir_status' class="alert alert-success" hidden>
		 	 		<p class='text-success'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> <b>DADOS CONFIRMADOS!</b> Prossiga com a alteração do Estado do Processo.</p>
		 	 	</div>

		</fieldset>
	</div>
</div>	

<!-- JANELA Modal ACIONADA PELO BOTÃO + PARA CADASTROS EM GERAL-->
<div class='modal fade' id='myModalCadastro' tabindex='-1' role='dialog' aria-labelledby='myModalCadastro'>
	<div class='modal-dialog' role='document'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' id='btn_fechar_modal' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				<h4 class='modal-title' id='myModalLabel'><div id='div_title_modal_cadastro_geral'></div> </h4>
			</div>
	
			<div class='modal-body' id='div_modal_body_cadastro_geral'>

			</div>
	
			<div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
			</div>

		</div>
	</div>
</div>
<!--FECHA MODAL -->

<input id='txt_mostra_nf' type="hidden" value='<?php echo($mostra_nf); ?>'></input>
<input id='txt_mostra_fornecedor' type="hidden" value='<?php echo($mostra_f); ?>'></input>

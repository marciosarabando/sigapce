<?php
//FORMULÁRIO DE INSERÇÃO DE DADOS PARA MATÉRIA
//TIPO DA MATÉRIA: TRANSFERÊNCIA DE ARMA

include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$mostra_nf = 0; 
$mostra_fornecedor = 0; 
$mostra_sigma = 1; 

?>

<div class='row'>
	<div class='col-md-12'>
		<fieldset>
		 	 	<legend>MATÉRIA PARA O ADITAMENTO AO BAR</legend>

		 	 	<label>CEDENTE DO ARMAMENTO</label>

		 	 	<div class="row">
		 	 		<div class="col-md-6">
				 	 	<div class="form-group">
							<label class="radio-inline">
								<input type="radio" id="rdb_cpf" name="cpf_cnpj" value="PF" onclick="mostra_campo_tipo_cedente('PF')"> <b>CPF</b></input>
							</label>
							<label class="radio-inline">
								<input type="radio" id="rdb_cnpj" name="cpf_cnpj" value="PJ" onclick="mostra_campo_tipo_cedente('PJ')"> <b>CNPJ</b></input>
							</label>
						</div>
					</div>

					<div class="col-md-4">
						<div id='div_cpf' hidden>
							<input type="text" class="form-control input-sm" id="cpf_cedente" name="cpf_cedente" placeholder="Digite o CPF do Cedente da Arma" onkeyup="buscaDadosCedenteArmamento('PF')"></input>
						</div>

						<div id='div_cnpj' hidden>
							<input type="text" class="form-control input-sm" id="cnpj_cedente" name="cnpj_cedente" placeholder="Digite o CNPJ do Cedente da Arma" onkeyup="buscaDadosCedenteArmamento('PJ')"></input>
						</div>

						<div id='div_msg_cpf_cnpj'>							
						</div>

					</div>

				</div>

				<div id='div_dados_cedente_arma'>
					<!-- DIV ALIMENTADA PELA PAGINA adt/funcoes/busca_dados_cedente_arma.php  -->
				</div>

				<br>

				<div class="row" id='div_cmb_acervo' hidden>
		 	 		<div class="col-md-3">
		 	 			<label>ACERVO:</label>
		 	 		</div>
		 	 		<div class="col-md-7">

		 	 			<select id='cmb_acervo' name='cmb_acervo' class='form-control input-sm' onchange='exibe_campo_nr_arma()'>
		 	 						<option value='0'>SELECIONE...</option>
		 	 						 <?php
								      //Preenche o combo do FORNECEDOR
								      //Conecta no Banco de Dados
								      include ("../../funcoes/conexao.php");
								      mysqli_query($conn,"SET NAMES 'utf8';");
								      $query = "SELECT 
								      					id_adt_acervo,
								      					nm_adt_acervo								      					
								      	 			FROM adt_acervo";
								      // executa a query
								      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
								      // transforma os dados em um array
								      $linha = mysqli_fetch_assoc($dados);
								      // calcula quantos dados retornaram
								      $totalLinhas = mysqli_num_rows($dados);

								      if($totalLinhas > 0)
								      {
								        do{
								          echo("<option value='". $linha['id_adt_acervo'] . "'>" . $linha['nm_adt_acervo'] . "</option>");

								        }while($linha = mysqli_fetch_assoc($dados));
								        mysqli_free_result($dados);
								        
								      }
								    ?>              
		 	 			</select>	

		 	 		</div>
		 	 		
				</div>

				<p>
				
				<div class="row" id='div_nr_arma' hidden>
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

				<div id='div_btn_confirma_dados' hidden>
		 	 		<button class="btn btn-success btn-block" id='btn_confirma_dados_form_transf' type="button" onclick='confirmar_dados_form_adt_transf()'>CONFIRMAR DADOS</button>
		 	 	</div>

		 	 	<p>

		 	 	<div id='div_info_prosseguir_status' class="alert alert-success" hidden>
		 	 		<p class='text-success'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> <b>DADOS CONFIRMADOS!</b> Prossiga com a alteração do Estado do Processo.</p>
		 	 	</div>


		</fildset>
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


<input id='st_cedente' value='0' hidden></input>
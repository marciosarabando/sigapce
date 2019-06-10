<?php
include ("funcoes/verificaAtenticacao.php");
define("Version", "12499");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA ABERTURA DE PROTOCOLO">
  <meta name="author" content="2 TEN SARABANDO">


  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>
  <script src="js/jquery.maskmoney.js" type="text/javascript"></script>
 
  <script>
		jQuery(function($){
		   $("#cpf").mask("999.999.999-99");
		   $("#cnpj").mask("99.999.999/9999-99");
		   $("#cpf_interessado").mask("999.999.999-99");
		});
  </script>

  <script src="protocolo/frm_protocolo_novo.js?<?php echo Version; ?>" type="text/javascript"></script>

  
  <script>
		function Mudarestado(el,estado) {
		    var display = document.getElementById(el).style.display;

			document.getElementById('div_cpf').style.display = 'none';
		    document.getElementById('div_cnpj').style.display = 'none';
		    document.getElementById('inclusaoSolicitacao').style.display = 'none';
		    document.getElementById('dadosInteressado').innerHTML = "";
		    document.getElementById('div_btn_incluirservico').style.display = 'none';
		    document.getElementById('id_servico_selecionado').value = '';
		    document.getElementById('div_servicos_incluidos').innerHTML = '';
		    document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
		    document.getElementById('div_obs_protocolo').style.display = 'none';
		    document.getElementById('div_pergunta_procurador').style.display = 'none';
		    document.getElementById('div_procurador').style.display = 'none';
		    document.getElementById('ic_procuradorNao').checked = false;
		    document.getElementById('ic_procuradorSim').checked = false;
			
		    
		    document.getElementById(el).style.display = 'block';
		    if(estado == "none")
		        document.getElementById(el).style.display = 'block';
		    else
		        document.getElementById(el).style.display = 'none';

		    //alert(el);
		    if(el == 'div_cpf')
		    {
		    	document.getElementById('cpf').focus();
		    }
		    else
		    {
		    	document.getElementById('cnpj').focus();
		    }
		}
  </script>


  <script>
  		function buscarDadosInteressadoCampoPreenchido(el)
  		{
  			if(el == 'div_cpf')
		    {
		    	var cpf = document.getElementById('cpf').value;
		    	cpf = cpf.replace(/[^\d]+/g,'');
		    	if(cpf.length == 11)
		    	{
		    		buscaInteressadoCPF();
		    	}
		    }
		    else if(el == 'div_cnpj')
		    {
		    	var cnpj = document.getElementById('cnpj').value;
		    	cnpj = cnpj.replace(/[^\d]+/g,'');
		    	if(cnpj.length == 14)
		    	{
		    		buscaInteressadoCNPJ();
		    	}
		    }
  		}
  </script>

 </head>

<body>

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="sisprot.php">SISPROT</a></li>
  <li class="active">SOLICITAÇÃO</li>
  <li class="active"><a href="sisprot.php?url=protocolo_novo">PROTOCOLAR</a></li>
</ol>


	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">PROTOCOLO DE SOLICITAÇÃO DE SERVIÇO DO SFPC/2</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
		<div id="div_corpo_painel" class="panel-body">
		    <!-- ... Corpo do Painel ... -->
		    
			<!-- ... Div Linha ... -->
		    <div class="row">
		    	<!-- ... Div Coluna da Esquerda ... -->
		    	<div class="col-md-6">

					<fieldset>
						<legend>Busca Interessado</legend>

						<label>TIPO DE DOCUMENTO DO INTERESSADO</label>

						<div class="form-group">
							<label class="radio-inline">
								<input type="radio" id="rdb_cpf" name="cpf_cnpj" value="0" onclick="Mudarestado('div_cpf','none')"> CPF</input>
							</label>
							<label class="radio-inline">
								<input type="radio" id="rdb_cnpj" name="cpf_cnpj" value="1" onclick="Mudarestado('div_cnpj','none')"> CNPJ</input>
							</label>
						</div>

						<div class="row" id='div_cpf' hidden>
							<div class="col-md-5" >
							    <div class="form-group" >		    
								    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite o CPF" onkeyup="buscarDadosInteressadoCampoPreenchido('div_cpf')"></input>
								    <div id='cpf_invalido' hidden>
								    	<br>
						    			<span class="label label-danger"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> CPF INVÁLIDO! Corrija o CPF e tente novamente!</span>
						    		</div>
								</div>
						    </div>
						    <div class="col-md-1">
						    		<button onClick="buscaInteressadoCPF()" class="btn btn-default" id="busca_cpf"><i class="glyphicon glyphicon-search"></i></button>
						    		
						    </div>
						</div>
						
						<div class="row" id='div_cnpj' hidden>
							<div class="col-md-5" >
								<div class="form-group">
								    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="Digite o CNPJ" onkeyup="buscarDadosInteressadoCampoPreenchido('div_cnpj')"></input>
								    <div id='cnpj_invalido' hidden>
								    	<br>
						    			<span class="label label-danger"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> CNPJ INVÁLIDO! Corrija o CNPJ e tente novamente!</span>
						    		</div>
								</div>
							</div>
						 	<div class="col-md-1">
						    		<button onClick="buscaInteressadoCNPJ()" class="btn btn-default" id="busca_cnpj"><i class="glyphicon glyphicon-search"></i></button>		    
						    </div>
						</div>
					</fieldset>

					<div id="inclusaoSolicitacao" hidden>
						<fieldset>
								
								<legend>Inclusão do Serviço</legend>
								
								<div id="div_comboCarteira">
									<!-- ... Carrega a Combo com as Carteiras ... -->
					 				<!-- ... Div Alimentada pela página protocolo/carregaComboCarteira.php ... -->
								</div>
								
								<div id='div_comboServicos'>
									<!-- ... Carrega a Combo Serviço de Acordo com a Carteira Selecionada e o Tipo de Interessado ... -->
					 				<!-- ... Div Alimentada pela página protocolo/carregaComboServico.php ... -->

								</div>

								<div id='div_btn_incluirservico' hidden>
									<br>
									<button onClick='incluirServico()' type='button' class='btn btn-primary btn-block'><i class='glyphicon glyphicon-download-alt'></i> Incluir Serviço</button>

								</div>

								<div id='div_servicos_incluidos'>
									<!-- ... aqui vai os serviços Incluidos pelo Botão Incluir Serviço ... -->
							 		<!-- ... Div Alimentada pela página protocolo/incluirServicoSelecionado.php ... -->
								</div>


						</fieldset>
					</div>

				</div>
				<!-- ... FIM da Div Coluna da Esquerda ... -->

				<!-- ... Div Coluna da Direita ... -->
				<div class="col-md-6">
					<div id="dadosInteressado">
				 		<!-- ... aqui vai o resultado da busca dos Dados do Interessado ... -->
				 		<!-- ... Div Alimentada pela página protocolo/buscaDadosInteressado.php ... -->
					</div>


					<div class="panel panel-default" id="div_pergunta_procurador" hidden>
					<div class="panel-heading">
                        <b>SOLICITAÇÃO PROTOCOLADA POR PROCURADOR OU REPRESENTANTE LEGAL?</b>
					</div>
					<div class="panel-body">
						<div>
							<label class="radio-inline">
								<input type="radio" id="ic_procuradorNao" name="ic_procurador" value="0" onclick="MudarestadoComboProcurador('div_combo_procurador','none')"> NÃO</input>
							</label>
							<label class="radio-inline">
								<input type="radio" id="ic_procuradorSim" name="ic_procurador" value="1" onclick="MudarestadoComboProcurador('div_combo_procurador','block')"> SIM</input>
							</label>
						</div>

						<div id="div_procurador" hidden>
							<br>
							
									<div id="div_combo_procurador" hidden>
										<!-- ... Dados do Procurador ... -->
								 		<!-- ... Div Alimentada pela página protocolo/carregaComboProcurador.php ... -->
									</div>

									<div id="div_dados_procurador">

									</div>
							
						</div>
					</div>
					</div>
			

					

				</div>
				<!-- ... FIM da Div Coluna da Direita ... -->

		 	</div>
		 	<!-- ... FIM da Div Linha ... -->


		 	<!-- CONTROLE DE GRUS DO PROCESSO -->
			<div id='div_gru' hidden>
				<div class="panel panel-default">
					<div class="panel-heading">
						<button type='button' id='btn_nova_gru' class='btn btn-primary btn-sm'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> INCLUIR GRU</button>
					</div>
					<div class="panel-body" id='div_campos_gru'>
						<!-- ... DIV PREENCHIDA PELA FUNCAO INSERE GRU COM O CLIQUE DO BOTAO btn_nova_gru... -->
					</div>
					<div class="panel-footer">
						<p class='text-info'><i class='glyphicon glyphicon-info-sign'></i> Atencão, todas as GRUs constantes no processo devem ser registrada no sistema.</p>
					</div>
					
				</div>
			</div>

			

			

		 	<div id='div_obs_protocolo' hidden>
		 		
		 		<label>OBSERVAÇÃO SOBRE O PROCESSO (PREENCHIMENTO NÃO OBRIGATÓRIO)</label>
		 		<textarea id='txt_observacao' class="form-control upper" rows="3" maxlength=400 placeholder='INFORME AQUI, CASO EXISTA ALGUMA INFORMAÇÃO EXTRA SOBRE ESTE PROTOCOLO'></textarea>
		 	</div>


			<br>
			<div id='div_btn_gerarProtocolo' hidden>	
				
				<!-- Button trigger modal -->
				<center>
				<button type="button" id="btn_gerarProtocolo" onclick="carregaInformacoesProtocoloModal()" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#myModalProtocolo">
				  <i class='glyphicon glyphicon-barcode'></i> GERAR PROTOCOLO DO PROCESSO
				</button>
				</center>

				<!-- Modal -->
				<div class="modal fade" id="myModalProtocolo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">INCLUSÃO DE PROTOCOLO</h4>
				      </div>
				      <div class="modal-body">
				      		<div id="div_janelaConfirmacaoProtocolo">
				      			<!-- ... aqui vai as informaçoes do Protocolo na janela Modal ... -->
			 					<!-- ... Div Alimentada pela página protocolo/carregaInformacoesConfirmacaoProtocoloModal.php ... -->
				      		</div>
					  </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				        <button type="button" name="gerarProtocolo" onClick="setTimeout(function(){ gerarProtocolo(); }, 500);" class="btn btn-success" data-dismiss="modal">GERAR NÚMERO DE PROTOCOLO</button>
				      </div>
				     </div>
				  </div>
				</div>

			</div>




		</div>
		<!-- ... FIM da Div Corpo do Painel ... -->

	</div>
	<!-- ... FIM da Div Painel ... -->

<input id="id_servico_selecionado" hidden></input>
<div id='div_st_gru' hidden></div>
<input id='nm_div_gru_incluida' hidden></input>
<input id='valores_grus_inseridas' hidden></input>


</body>
</html>
<?php
//FORMULÁRIO DE EMISSAO DE RELATORIO DO SISPROT
include ("funcoes/verificaAtenticacao.php");
define("Version", "145");
if (!isset($_SESSION)) 
{
  session_start();
}
if(isset($_SESSION['id_login_sfpc']))
{

  $id_login_logado = $_SESSION['id_login_sfpc'];
  $id_login_perfil = $_SESSION['id_login_perfil'];
  $id_unidade_sfpc = $_SESSION['id_unidade_sfpc'];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Fiscalização Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA RELATORIO DO SISPROT">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>
  
  <script src="sisprot/sisprot.js?<?php echo Version; ?>" type="text/javascript"></script>
  

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

  <script>
  $(function () 
	 {
        $('#periodo_inicial').datetimepicker({format: 'DD/MM/YYYY'});
        $('#txt_dt_inicio_periodo').mask('99/99/9999');
        $('#periodo_final').datetimepicker({format: 'DD/MM/YYYY'});
        $('#txt_dt_fim_periodo').mask('99/99/9999');
     });
  </script>

</head>

<body onload='carregaComboServico()'>



<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">RELATÓRIO ANALÍTICO DE PROCESSOS</h2>
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_rel_sisprot" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     	
     	<!-- ABRE LINHA 1-->
		<div class='row'>
			<!-- ABRE DIV COLUNA FILTROS-->
			<div class='col-md-6'>
				<form class="form-horizontal" enctype="multipart/form-data">
				  
				  <div class="form-group">
				    <label for="cmb_unidade" class="col-sm-2 control-label">UNIDADE</label>
				    <div class="col-sm-8">
				      <select id='cmb_unidade' name='cmb_unidade' class='form-control input-sm' onchange=''>
				      	<?php

						//Preenche o combo do tipo de solicitação
						//Conecta no Banco de Dados
						include ("funcoes/conexao.php");
						mysqli_query($conn,"SET NAMES 'utf8';");
						$query = "SELECT id_unidade, nm_unidade FROM unidade";

						// executa a query
						$dados = mysqli_query($conn,$query) or die(mysql_error());
						// transforma os dados em um array
						$linha = mysqli_fetch_assoc($dados);
						// calcula quantos dados retornaram
						$totalLinhas = mysqli_num_rows($dados);

						echo("<option value='0' selected>*** TODAS ***</option>");

						if($totalLinhas > 0)
						{
						do{


						echo("<option value='". $linha['id_unidade'] . "'>" . $linha['nm_unidade'] . "</option>");


						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);

						}

						?>              
						</select>
				    </div>
				  </div>


				  <div class="form-group">
				    <label for="cmb_carteira" class="col-sm-2 control-label">CARTEIRA</label>
				    <div class="col-sm-8">
				      <select id='cmb_carteira' name='cmb_carteira' class='form-control input-sm' onchange='carregaComboServico()'>
				      	<?php

						//Preenche o combo do tipo de solicitação
						//Conecta no Banco de Dados
						include ("funcoes/conexao.php");
						mysqli_query($conn,"SET NAMES 'utf8';");
						$query = "SELECT id_carteira, ds_carteira FROM carteira";

						// executa a query
						$dados = mysqli_query($conn,$query) or die(mysql_error());
						// transforma os dados em um array
						$linha = mysqli_fetch_assoc($dados);
						// calcula quantos dados retornaram
						$totalLinhas = mysqli_num_rows($dados);

						echo("<option value='0' selected>*** TODAS ***</option>");

						if($totalLinhas > 0)
						{
						do{


						echo("<option value='". $linha['id_carteira'] . "'>" . $linha['ds_carteira'] . "</option>");


						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);

						}

						?>              
						</select>
				    </div>
				  </div>

				  <div class="form-group">
				    <label for="cmb_servico" class="col-sm-2 control-label">SERVIÇO</label>
				    <div class="col-sm-8">
				      <div id="div_combo_servico"></div>
				    </div>
				  </div>

				  <div class="form-group">
					    <label for="cmb_status" class="col-sm-2 control-label">STATUS</label>
					    <div class="col-sm-8">
					      <select id='cmb_status' name='cmb_status' class='form-control input-sm' onchange=''>
					      	<?php

							//Preenche o combo do tipo de solicitação
							//Conecta no Banco de Dados
							include ("funcoes/conexao.php");
							mysqli_query($conn,"SET NAMES 'utf8';");
							$query = "SELECT id_processo_status, nm_processo_status FROM processo_status";

							// executa a query
							$dados = mysqli_query($conn,$query) or die(mysql_error());
							// transforma os dados em um array
							$linha = mysqli_fetch_assoc($dados);
							// calcula quantos dados retornaram
							$totalLinhas = mysqli_num_rows($dados);

							echo("<option value='0' selected>*** TODOS ***</option>");

							if($totalLinhas > 0)
							{
							do{


							echo("<option value='". $linha['id_processo_status'] . "'>" . $linha['nm_processo_status'] . "</option>");


							}while($linha = mysqli_fetch_assoc($dados));
							mysqli_free_result($dados);

							}

							?>              
							</select>
					    </div>
				  	</div>

				  
				</form>
				
			</div>

			<!-- ABRE COLUNA 2-->
			<div class='col-md-6'>
				<form class="form-horizontal" enctype="multipart/form-data">
					
					<div class="form-group">
					    <label for="cmb_procurador" class="col-sm-3 control-label">PROCURADOR</label>
					    <div class="col-sm-8">
					      	<select id='cmb_procurador' name='cmb_procurador' class='form-control input-sm' onchange=''>
					      	
						      	<?php

								//Preenche o combo do tipo de solicitação
								//Conecta no Banco de Dados
								include ("funcoes/conexao.php");
								mysqli_query($conn,"SET NAMES 'utf8';");
								$query = "SELECT id_procurador, nm_procurador FROM procurador ORDER BY nm_procurador";

								// executa a query
								$dados = mysqli_query($conn,$query) or die(mysql_error());
								// transforma os dados em um array
								$linha = mysqli_fetch_assoc($dados);
								// calcula quantos dados retornaram
								$totalLinhas = mysqli_num_rows($dados);

								echo("<option value='0' selected>*** TODOS ***</option>");

								if($totalLinhas > 0)
								{
								do{


								echo("<option value='". $linha['id_procurador'] . "'>" . $linha['nm_procurador'] . "</option>");


								}while($linha = mysqli_fetch_assoc($dados));
								mysqli_free_result($dados);

								}

								?>       

					      	     
							</select>
					    </div>
				  	</div>

				  	<div class="form-group">
					    <label for="cmb_interessado" class="col-sm-3 control-label">INTERESSADO</label>
					    <div class="col-sm-4">
					      	<select id='cmb_interessado' name='cmb_interessado' class='form-control input-sm' onchange=''>
					      	
						      	<option value='TODOS' selected>*** TODOS ***</option>
						      	<option value='PF'>PF</option>
						      	<option value='PJ'>PJ</option>
						      	<option value='MIL'>MIL</option>

					      	     
							</select>
					    </div>
				  	</div>

				  	<div class="form-group">
					    <label for="cmb_interessado" class="col-sm-3 control-label">PESQUISAR POR</label>

					    <div class="col-sm-4">

					      	<input type="radio" name="rdb_tipo_pesquisa" id="rdb_tipo_pesquisa" value="1" checked><font size=2> <b>DATA DO PROTOCOLO</b></font>
					    </div>

					    <div class="col-sm-4">
					    	<input type="radio" name="rdb_tipo_pesquisa" id="rdb_tipo_pesquisa" value="2"><font size=2> <b>DATA DO STATUS</b></font>
				   		</div>

				  	</div>

				  <div class="form-group">
				    <label for="periodo_inicial" class="col-sm-3 control-label">PERÍODO</label>
				    <div class="col-sm-4">
				    	<div class='input-group date' id='periodo_inicial'>
						<input type='text' class='form-control' id='txt_dt_inicio_periodo' name='txt_dt_inicio_periodo' value=''/>
							<span class='input-group-addon'>
	                        	<span class='glyphicon glyphicon-calendar'></span>
		                    </span>
		                </div>
				    </div>

				    <div class="col-sm-4">
				    	<div class='input-group date' id='periodo_final'>
						<input type='text' class='form-control' id='txt_dt_fim_periodo' name='txt_dt_fim_periodo' value=''/>
							<span class='input-group-addon'>
	                        	<span class='glyphicon glyphicon-calendar'></span>
		                    </span>
		                </div>
				    </div>
				  </div>	
				
				</form>
				<button type='button' id='btn_exibe_relatorio' onclick='exibeRelatorioSisprot()' class='btn btn-primary btn-lg btn-block btn-sm'><i class='glyphicon glyphicon-search'></i> GERAR RELATÓRIO</button>
			</div>


		<!-- FECHA LINHA 1-->			
		</div>

		<div class='row'>
			<div class='col-md-12'>
				<hr>

				<div class="tabbable" id="tabs_relatorio">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#panel-resultado-pesquisa" data-toggle="tab"><div id='div_tab_resultado'></div>DADOS</a>
						</li>
						<li>
							<a href="#panel-detalhes-processo" data-toggle="tab"><div id='div_tab_nr_processo'></div></a>
						</li>
					</ul>

					
					<div class="tab-content">
						<!-- Inicio da TAB Resultado Pesquisa-->
						<div class="tab-pane active" id="panel-resultado-pesquisa">
							
							<div id='div_result_rel'></div>
						
						</div>
						<!-- FIM da TAB Resultado Pesquisa-->

						<!-- INICIO da TAB Detalhes do Processo-->
						<div class="tab-pane" id="panel-detalhes-processo">
							
							<div id='div_detalhes_processo'></div>

						</div>
						<!-- FIM da TAB Detalhes do Processo-->
					</div>
				</div>

				<div id="tabid"></div>
				
			</div>

		</div>

		
      

	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

</body>
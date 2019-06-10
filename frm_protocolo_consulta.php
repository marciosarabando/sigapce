<?php
include ("funcoes/verificaAtenticacao.php");
date_default_timezone_set('America/Sao_Paulo');
$data_atual = date('d-m-Y');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta name="description" content="PAGINA DE CONSULTA DE PROTOCOLO">
  	<meta name="author" content="2 TEN SARABANDO">

  	<script type="text/javascript" src="js/jquery.min.js"></script>
  	<script type="text/javascript" src="js/moment.min.js"></script>
  	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  	<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
 	
  	<script type="text/javascript">
            $(function () {
                $('#dt_pesquisa_protocolo').datetimepicker({format: 'DD/MM/YYYY'});
            });
   	</script>

  	<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  	<script src="js/jquery.printElement.js" type="text/javascript"></script>
  
  	<script>
		jQuery(function($){
		   $("#txt_pesquisa_cpf").mask("999.999.999-99");
		   $("#txt_pesquisa_cnpj").mask("99.999.999/9999-99");
		});
  	</script>

  	<script>
		 $(document).keypress(function(e) {
		  if(e.which == 13) {
		    // enter pressed
		    buscaProcessosPesquisa();
		  }
		});
	</script>

	
	<script src="js/jquery.dataTables.js"></script>


	<style type="text/css">
		@import "css/jquery.dataTables.css";
	</style>

	<script src="protocolo/frm_protocolo_consulta.js" type="text/javascript"></script>



</head>

<body onload="alteraCampoPesquisa()">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="sisprot.php">SISPROT</a></li>
  <li class="active">PROTOCOLO</li>
  <li class="active"><a href="sisprot.php?url=protocolo_consulta">CONSULTAR</a></li>
</ol>


	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">CONSULTA DE SOLICITAÇÃO DE SERVIÇO DO SFPC/2</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
		<div class="panel-body">
		    <!-- ... Corpo do Painel ... -->
		    
			<!-- ... Div Linha ... -->
		    <div class="row clearfix">
		    	<!-- ... Div Coluna da Esquerda ... -->
		    	<div class="col-md-12" id="div_corpo_painel_dados_consulta">

		    		<fieldset>
						<legend>BUSCAR PROTOCOLOS</legend>
						<label>FILTRO</label>	 
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group" >		    	   
										<select id="cmb_tipo_pesquisa" class="form-control" onchange="alteraCampoPesquisa()">
												
												<option value="protocolo">Nº PROTOCOLO</option>
												<option value="data">DATA DO PROTOCOLO</option>
												<option value="cpf">CPF</option>
												<option value="cnpj">CNPJ</option>
												<option value="cr">Nº DO CR</option>
												<option value="tr">Nº DO TR</option>
												<option value="requerente">REQUERENTE</option>
												<option value="processos_prontos">PROCESSOS PRONTOS</option>

										</select>
								</div>
							</div>

							<div class="col-md-4" id="div_campo_pesquisa">
								
								<div class="row clearfix" id="div_pesquisa_data" hidden>	
									<div class="col-xs-8">
										 <div class="input-group date" id="dt_pesquisa_protocolo">
					                   		<input type="text" class="form-control" id="txt_pesquisa_data" name="txt_pesquisa_data" value="<?php echo($data_atual); ?>"/>
					                    	<span class="input-group-addon">
					                        	<span class="glyphicon glyphicon-calendar"></span>
						                    </span>
						                    <button onClick="buscaProcessosPesquisa()" class="form-control" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>	
					                	</div>
									</div>
								</div>
							
								<div class="row clearfix" id="div_pesquisa_protocolo">	
									<div class="row clearfix">	
										<div class="col-xs-8">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="form-control" id="txt_pesquisa_protocolo" name="txt_pesquisa_protocolo" onkeypress='return SomenteNumero(event)' placeholder="Digite o Nº do Protocolo"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_pesquisa_cpf" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-8">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="form-control" id="txt_pesquisa_cpf" name="txt_pesquisa_cpf" placeholder="Digite o CPF"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
							                    
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_pesquisa_cnpj" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-8">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="form-control" id="txt_pesquisa_cnpj" name="txt_pesquisa_cnpj" placeholder="Digite o CNPJ"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
							                    
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_pesquisa_cr" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-8">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="form-control" id="txt_pesquisa_cr" name="txt_pesquisa_cr" placeholder="Digite o Nº do CR"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
							                    
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_pesquisa_tr" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-8">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="form-control" id="txt_pesquisa_tr" name="txt_pesquisa_tr" placeholder="Digite o Nº do TR"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
							                    
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_nm_requerente" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-12">
											 <div class="input-group">
						                   		<input type="text" onblur="" class="upper form-control" id="txt_nm_requerente" name="txt_nm_requerente" placeholder="Digite o Nome do Requerente"/>
						                    	<span class="input-group-btn">
						                        	<button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
							                    </span>
							                    
						                	</div>
										</div>
									</div>
								</div>

								<div class="row clearfix" id="div_pesquisa_prontos" hidden>	
									<div class="row clearfix">	
										<div class="col-xs-8">
						                    <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>		
										</div>
									</div>
								</div>

							</div>							
						</div>

						</fieldset>

					<hr>

						<div class="row">
							<div class="col-md-12">
								<div id='div_resultado_consulta'>

								</div>
							</div>
						</div>


					</div>

					

					


					
		    	</div>
		    </div>
		</div>
	



</body>

</html>
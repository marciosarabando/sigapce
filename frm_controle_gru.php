<?php
include ("funcoes/verificaAtenticacao.php");
date_default_timezone_set('America/Sao_Paulo');
$data_atual = date('d-m-Y');
define("Version", "1234");
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
  

	<script src="js/jquery.dataTables.js"></script>

	<style type="text/css">
		@import "css/jquery.dataTables.css";
	</style>

	<script src="protocolo/gru/gru.js?<?php echo Version; ?>" type="text/javascript"></script>

</head>

<body onload="">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="sisprot.php">SISPROT</a></li>
  <li class="active">GRU</li>
  
</ol>

	<div class="panel panel-default">
		
		<div class="panel-heading">
			<h2 class="panel-title">CONTROLE DE PAGAMENTO DE GUIA DE RECOLHIMENTO DA UNIÃO</h2>
			UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
			<h6>Inclusão de GRU iniciada em 07/08/2017</h6>
		</div>

		<div class="panel-body">
		    <!-- ... Corpo do Painel ... -->

		    <div class='row'>
		    	<div class='col-md-4'>

		    		<div class="panel panel-default">
					  <div class="panel-body">
					  	<div id='div_totais_gru'>
					  		<!-- ... PREENCHIDA PELA PÁGINA calcula_total_gru.php ... -->
					  	</div>
					  </div>
					</div>

		    	</div>

		    

		    
		    	<div class='col-md-5'>

		    		<div class="panel panel-default">
					  <div class="panel-body">

					  		<button type="button" id='btn_gru_cadastrada' class="btn btn-primary btn-block">GRU'S CADASTRADAS</button>
		   					<button type="button" id='btn_tentativa_fraude' class="btn btn-primary btn-block">TENTATIVAS DE REUSO DE GRU</button>

					  	</div>
					  </div>
				</div>

		    	

		    </div>

		    
		   	
		   	<hr>

		   	<div id='div_result'></div>
			
		    
		 </div>
	</div>

</body>
</html>
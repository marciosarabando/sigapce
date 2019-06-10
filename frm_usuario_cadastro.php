<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA DE CADASTRO DE USUARIOS DO SISTEMA">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  

  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/moment.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  <script src="usuario/frm_usuario_cadastro.js" type="text/javascript"></script>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>

  <script src="js/jquery.js"></script>
  <script src="js/jquery.dataTables.js"></script>


  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>



 </head>

<body onload="listaUsuariosCadastrados()">

<ol class="breadcrumb">
  <li><a href="home.php">SGPC</a></li>
  <li class="active">CADASTRO</li>
  <li class="active"><a href="home.php?url=cadastro_usuario">USUÁRIO</a></li>
</ol>


	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">CONTROLE DE ACESSO AO SISTEMA SISPROT DO SFPC/2</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
		<div class="panel-body">
		    <!-- ... Corpo do Painel ... -->
		    
			<!-- ... Div Linha ... -->
		    <div class="row">
		    	<!-- ... Div Coluna da Esquerda ... -->
		    	<div class="col-md-12" id="div_corpo_painel_usuarios_sisprot">
           
            <div id="div_lista_usuarios">
            </div>

            <div class='row'>
              <div class='col-md-6' id='div_dados_usuario'>

              </div>
              <div class='col-md-6' id='div_dados_acesso_carteira'>

              </div>
            </div>
            <div class='row'>
              <div class='col-md-6' id='div_dados_acesso_unidades'>
                
              </div>
              </div>
            </div>
            


          </fieldset>





		    	</div>
		    </div>
		</div>
	</div>
  <input id='id_unidades_selecionadas' hidden></input>
  <input id='id_carteiras_selecionadas' hidden></input>

</body>

</html>
<?php
include ("funcoes/verificaAtenticacao.php");
if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['login_sfpc']))
{
 	$nm_guerra = $_SESSION['login_sfpc'];
 	$nm_unidade = $_SESSION['nm_unidade_sfpc'];
 	$nm_login_perfil = $_SESSION['nm_login_perfil'];
 	$id_login_perfil = $_SESSION['id_login_perfil'];
 	$id_login = $_SESSION['id_login_sfpc'];
 	$id_modulo_login_permissao = $_SESSION['id_modulo_login_permissao'];
 	$id_modulo_login_permissao = split(",",$id_modulo_login_permissao);
}

//Perfil de Acesso
$AdminsSFPC = array("1","2","3","4");
$Analista = array("5");
$Atendimento = array("6");
$modulo_ged = 3;

if (!in_array($modulo_ged, $id_modulo_login_permissao))
{
	//echo("SEM PERMISSAO");
	$redirect = "home.php";
	header("location:$redirect");
	echo "<meta http-equiv='refresh' content='0;URL=home.php'>";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>SIGAPCE - SAE - 2ª RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Sistema de Controle de Processos SFPC 2RM" content="">
  <meta name="2 Ten Sarabando" content="Sistema de Controle de Processos SFPC 2RM">

	
	<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
	<link href="css/style.css" rel="stylesheet">


  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon-2rm.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	
	<script type="text/javascript" src="js/scripts.js"></script>

	<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  	<script src="js/jquery.printElement.js" type="text/javascript"></script>

  	<script src="js/jquery.min.js" type="text/javascript"></script>
  	<script src="js/moment.min.js" type="text/javascript"></script>
  	 	
  	<script src="js/bootstrap.js" type="text/javascript"></script>
  	
  	<script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  	<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />


	
 <style type="text/css">
html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }

      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      .container {
        width: auto;
        max-width: 90%;
      }
      .container .credit {
        margin: 20px 0;
      }

 </style>

 <style>
		.upper
		{
			text-transform: uppercase;
		}
		.lower
		{
			text-transform: lowercase;
		}
</style>

<script language='JavaScript'>
	function SomenteNumero(e){
	    var tecla=(window.event)?event.keyCode:e.which;   
	    if((tecla>47 && tecla<58)) return true;
	    else{
	    	if (tecla==8 || tecla==0) return true;
		else  return false;
	    }
	}
</script>

<script language="JavaScript">
window.onbeforeunload = ConfirmExit;
function ConfirmExit()
{
    //Pode se utilizar um window.confirm aqui também...
    //return "Mensagem de fechamento de janela....";
    //return location.href="funcoes/logout.php";
    //window.location="funcoes/logout.php";
}
</script>

</head>

<body>


<div id="wrap">
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="home.php"><img width="25" height="22" src="img/simbolo_2rm.png" class="img-polaroid"> SIGAPCE / INDICADORES</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						

	
					
							


					</ul>
					
					<ul class="nav navbar-nav navbar-right">
						
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> <?php if(isset($nm_guerra)){echo($nm_guerra);}?><strong class="caret"></strong></a>
							<ul class="dropdown-menu">

								<li>
									<a href="?url=alterasenha"><i class="glyphicon glyphicon-pencil"></i> Alterar Senha</a>
								</li>
							
								<li class="divider">
								</li>
								<li>
									<a href="funcoes/logout.php"><i class="glyphicon glyphicon-off"></i> Logout</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				
			</nav>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<?php		
				
				//Controle de Chamadas de Menu
				// if (isset($_GET['url']) != null)
				// {
				// 	$url = $_GET['url'];	
				// 	if ($url == 'pesquisa_documento')
				// 	{
				// 		include ("ged/frm_pesquisa_documento.php");	
				// 	}
				// 	if ($url == 'upload_documento')
				// 	{
				// 		include ("ged/frm_upload_documento.php");	
				// 	}
				// }	
				// else
				// {
				// 	include("noticia_ged.php");					
				// }

				include("indicadores/indicadores.php");					
			?>
		</div>
	</div>
</div>

 <div id="push"></div>
</div>

<div id="footer">
      <div class="container">
        <center><p class="muted credit">Seção de Fiscalização de Produtos Controlados da 2ª Região Militar - © Copyright 2017</p></center>
      </div>
 </div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
-->


</body>
</html>

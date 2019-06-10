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
 	$id_modulo_login_permissao = explode(",",$id_modulo_login_permissao);
}

//Perfil de Acesso
$AdminsSFPC = array("1","2","3","4");
$Analista = array("5");
$Atendimento = array("6");
$modulo_sisprot = 1;

if (!in_array($modulo_sisprot, $id_modulo_login_permissao))
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
  <title>SIGAPCE - SISPROT - 2ª RM</title>
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
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>

	<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  	<script src="js/jquery.printElement.js" type="text/javascript"></script>

  	<script src="js/jquery.min.js" type="text/javascript"></script>
  	<script src="js/moment.min.js" type="text/javascript"></script>
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
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="sisprot.php"><img width="25" height="22" src="img/simbolo_2rm.png" class="img-polaroid"> SIGAPCE / SISPROT</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">PROTOCOLO<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="?url=protocolo_novo"><i class="glyphicon glyphicon-download-alt"></i> PROTOCOLAR</a>
								</li>
								<li>
									<a href="?url=protocolo_consulta"><i class="glyphicon glyphicon-search"></i> CONSULTAR</a>
								</li>
							</ul>
						</li>

						<?php
							
							if (in_array($id_login_perfil, $AdminsSFPC) || in_array($id_login_perfil, $Analista)) 
							{ 
								echo("
									<li class='dropdown'>
										<a href='#' class='dropdown-toggle' data-toggle='dropdown'>PROCESSO<strong class='caret'></strong></a>
										<ul class='dropdown-menu'>
											<li>
												<a href='?url=processo_analise'><i class='glyphicon glyphicon-eye-open'></i> ANALISAR</a>
											</li>
											
										</ul>
									</li>
								");
							}
						?>


						<?php
							
							if (in_array($id_login_perfil, $AdminsSFPC) || in_array($id_login_perfil, $Analista)) 
							{ 
								echo("
									<li class='dropdown'>
										<a href='#' class='dropdown-toggle' data-toggle='dropdown'>GRU<strong class='caret'></strong></a>
										<ul class='dropdown-menu'>
											<li>
												<a href='?url=gru'><i class='glyphicon glyphicon-barcode'></i> CONTROLE DE GRU</a>
											</li>
											
										</ul>
									</li>
								");
							}
						?>


						<?php
							
							if (in_array($id_login_perfil, $AdminsSFPC) || in_array($id_login_perfil, $Analista)) 
							{ 
								echo("
									<li class='dropdown'>
										<a href='#' class='dropdown-toggle' data-toggle='dropdown'>RELATÓRIO<strong class='caret'></strong></a>
										<ul class='dropdown-menu'>
											<li>
												<a href='?url=relatorio_sisprot'><i class='glyphicon glyphicon-list-alt'></i> STATUS</a>
											</li>

											<li>
												<a href='?url=indicadores'><i class='glyphicon glyphicon-list-alt'></i> PRODUÇÃO DIÁRIA</a>
											</li>
											
											<li>
												<a href='?url=produtividade'><i class='glyphicon glyphicon-list-alt'></i> TEMPO DE PROCESSAMENTO</a>
											</li>
											<li>
												<a href='?url=producao'><i class='glyphicon glyphicon-list-alt'></i> PRODUTIVIDADE</a>
											</li>
										</ul>
									</li>
								");
							}
						?>

						
								


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
				if (isset($_GET['url']) != null)
				{
					$url = $_GET['url'];	
					if ($url == 'protocolo_novo')
					{
						include ("frm_protocolo_novo.php");	
					}
					if ($url == 'protocolo_consulta')
					{
						include ("frm_protocolo_consulta.php");	
					}
					if ($url == 'processo_analise' && in_array($id_login_perfil, $AdminsSFPC) || $url == 'processo_analise' && in_array($id_login_perfil, $Analista))
					{
						include ("frm_processo_analise.php");	
					}
					if ($url == 'relatorio_sisprot')
					{
						include ("frm_relatorio_sisprot.php");	
					}
					if ($url == 'gru')
					{
						include ("frm_controle_gru.php");	
					}
					if ($url == 'indicadores')
					{
						include("indicadores/indicadores.php");	
					}
					if ($url == 'produtividade')
					{
						include("frm_produtividade.php");	
					}
					if ($url == 'producao')
					{
						include("frm_producao.php");	
					}
					if($url == 'alterasenha')
					{
						include ("frm_altera_senha.php");	
					}
					if($url == 'novo_usuario')
					{
						include ("frm_usuario_novo.php");	
					}

				}	
				else
				{
					include("noticia_sisprot.php");					
				}
			?>
		</div>
	</div>
</div>

 <div id="push"></div>
</div>

<div id="footer">
      <div class="container">
        <center><p class="muted credit">Seção de Fiscalização de Produtos Controlados da 2ª Região Militar - © Copyright 2015-2019</p></center>
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

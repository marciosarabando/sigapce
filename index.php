<?php
include("funcoes/obtemIP.php");
define("Version", "1234");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SIGAPCE - 2ª Região Militar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

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
        margin: 10px 0;
      }

 </style>


	<script type='text/javascript' >
	window.onload = function() 
  	{
         document.formLogin.login.focus();
    } 
	</script>
	
    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 10px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        

      }

      .imagemFundo{
        background-image: url("img/ebciber.jpg");
        background-repeat:no-repeat;
        background-attachment: fixed;
        background-size: cover;
        background-position:50% 50%;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="img/favicon-2rm.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">

     <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script src="js/jquery.maskedinput.js" type="text/javascript"></script>

  

    <script src="funcoes/funcoes_sigapce.js?<?php echo Version; ?>" type="text/javascript"></script>

    <script>
     $(document).keypress(function(e) {
      if(e.which == 13) {
        // enter pressed
        entrar();
      }
    });
    </script>
    <script type='text/javascript' >
      window.onload = function() 
      {
           document.formLogin_siagpc.login_sfpc.focus();
      } 
    </script>
     

  </head>

<body>
	
<div id="wrap">

    <div class="container">
    <h2 align="center">Sistema de Gerenciamento de Atividades com PCE</h2>
    <h3 align="center">SFPC - 2ª Região Militar</h3>
    <p align="center"><img width="100" height="130" src="img/simbolo_2rm.png" class="img-polaroid"></p>


    </div>
    



  <div class="container">

      <form id='formLogin_siagpc' name="formLogin_siagpc" class="form-signin" method='post' action='funcoes/autentica.php'>
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" id='login_sfpc' name="login_sfpc" class="input-block-level" placeholder="Login">
        <input type="password" id='txt_senha_sfpc' name="txt_senha_sfpc" maxlength="20" class="input-block-level" placeholder="Senha">
		
       
        <button class="btn btn-large btn-primary" type="button" onclick='entrar()'><span class='glyphicon glyphicon-lock' aria-hidden='true'></span> Entrar</button>
		
        <input type='text' id='senha_sfpc' name='senha_sfpc' hidden></input>
        

      </form>

      <br><br>

      <center><font color="red">Endereço IP de acesso: <?php echo(getenv("REMOTE_ADDR")) ?></font></center>
      <br>

      <center><font color="blue">Versão 4.0 - Março de 2019</font></center>
      
       
     
    </div> <!-- /container -->
</div>
 <div id="push"></div>
</div>

<div id="footer">
      <div class="container">
        <center><p class="muted credit">Desenvolvido por 1º Ten Sarabando - SFPC do Comando da 2ª Região Militar - © Copyright 2015-2019</p><br></center>
      </div>
 </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    

  </body>
</html>

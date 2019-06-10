<?php
include ("funcoes/verificaAtenticacao.php");
if(isset($_SESSION['login_sfpc']))
 {
 	$nm_guerra = $_SESSION['login_sfpc'];
 	$id_modulo_login_permissao = $_SESSION['id_modulo_login_permissao'];
 	$id_modulo_login_permissao = explode(",",$id_modulo_login_permissao);
 }
 //Perfil de Acesso
$AdminsSFPC = array("1","2","3","4");
$Analista = array("5");
$Atendimento = array("6");
$modulo_sisprot = 1;
$modulo_sae = 2;
$modulo_ged = 3;
$modulo_adt = 4;

 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Notícia</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">


</head>

<body>
	<div class="well">
        <p><h3>SIGAPCE <small> SISTEMA DE GERENCIAMENTO DE ATIVIDADES COM PCE DA SFPC/2</small></h3></p>
        <hr>
        <p><h5><b>Olá, <?php if(isset($nm_guerra)){echo($nm_guerra);}?></h5></b></p>
        <p><h6>UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?></h6></p>
        <p><h6>PERFIL DE ACESSO: <?php if(isset($nm_login_perfil)){echo($nm_login_perfil);}?></h6></p>
        <p><h6>ÚLTIMO ACESSO EM: <font color="red"><?php if(isset($dt_ultimo_acesso_login)){echo($dt_ultimo_acesso_login.' h');}?></font></h6></p>
        <br>

        <div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">MÓDULOS DO SIGAPCE</h3>
		</div>
		<div class="panel-body">
			
			
			<div class="row">
		        <?php
					if (in_array($modulo_sisprot, $id_modulo_login_permissao))
					{
						echo("
						        <div class='col-sm-2'>
						          
						            	
							              	<center>
												<a href='sisprot.php'><img src='img/sisprot.jpg' class='img-thumbnail' alt='SISPROT/2'></a>
											</center>
										
						           
						        </div>
						     ");
					}
					if (in_array($modulo_sae, $id_modulo_login_permissao))
					{
						echo("

						        <div class='col-sm-2'>
						        
						            	
							              	<center>    
												<a href='sae.php' class='img'><img src='img/sae.jpg' class='img-thumbnail' alt='SAE/2'></a>
											</center>
										
						          
						        </div>
						    ");
					}
					if (in_array($modulo_ged, $id_modulo_login_permissao))
					{
						echo("

						        <div class='col-sm-2'>
						        
						            	
							              	<center>    
												<a href='ged.php' class='img'><img src='img/ged.jpg' class='img-thumbnail' alt='GED/2'></a>
											</center>
										
						          
						        </div>
						    ");
					}

					if (in_array($modulo_adt, $id_modulo_login_permissao))
					{
						echo("

						        <div class='col-sm-2'>
						        
						            	
							              	<center>    
												<a href='adt.php' class='img'><img src='img/adt_new.jpg' class='img-thumbnail' alt='ADT/2'></a>
											</center>
										
						          
						        </div>
						    ");
					}
				?>
		    </div>
		</div>
		</div>
        
     </div>
      	

	
		

</body>
</html>

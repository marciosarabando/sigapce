<?php
//include ("funcoes/verificaAtenticacao.php");
if(isset($_SESSION['login_sfpc']))
 {
 	$nm_guerra = $_SESSION['login_sfpc']; 
 }
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Notícia</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <script src="sisprot/sisprot.js" type="text/javascript"></script>

</head>

<body onload='exibeDashBoard()'>
	
      <div class="well">
      	<table>
			<tr>
				<td>
					<a href="sisprot.php" class="img"><img src="img/sisprot.jpg" width='100' alt="SISPROT/2"></a>
				</td>
				<td>
					<p><h3>MÓDULO SISPROT <small> SISTEMA DE PROTOCOLO ELETRÔNICO DA SFPC/2</small></h3></p>
        			<p><h5>UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?></h5></p>
				</td>
			</tr>
		</table>

     </div>

     <div class='row'>
	     <div id='div_dashboard_sisprot'>
	     </div>
 	 
	     <div class='col-md-6'>
        	<div class='panel panel-default'>
            	<div class='panel-heading'>
					     
					     
					     	<p><h5>PERFIL DE ACESSO: <?php if(isset($nm_login_perfil)){echo($nm_login_perfil);}?></h5></p>
					     	<br>

									<?php
										include ("funcoes/conexao.php");
										mysqli_query($conn,"SET NAMES 'utf8';");
										$query = "SELECT
															carteira.ds_carteira
													FROM login_carteira
													INNER JOIN carteira on carteira.id_carteira = login_carteira.id_carteira
													WHERE login_carteira.id_login = $id_login ORDER BY 1
												";

												// executa a query
												//mysqli_query($conn,"SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
												
												$dados = mysqli_query($conn,$query) or die(mysql_error());
												// transforma os dados em um array
												$linha = mysqli_fetch_assoc($dados);
												// calcula quantos dados retornaram
												$totalLinhas = mysqli_num_rows($dados);

												if($totalLinhas > 0)
												{	
													do{
														$ds_carteira = $linha['ds_carteira'];
														echo("<p><i class='glyphicon glyphicon-book'></i> ".$ds_carteira."<p>");
																		
													}while($linha = mysqli_fetch_assoc($dados));
													mysqli_free_result($dados);			

												}
									?>
					   

				</div>
	        </div>
	    </div>


	</div>

</body>
</html>

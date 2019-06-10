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
  
  <script src="adt/adt.js" type="text/javascript"></script>


</head>

<body onload='exibeDashBoard()'>

<div class="well">
	<table>
		<tr>
			<td>
				<a href="adt.php" class="img"><img src="img/adt_new.jpg" width='100' alt="ADT/2"></a>
			</td>
			<td>
				<p><h3>MÓDULO ADT <small>ADITAMENTO DO SFPC/2 AO BOLETIM DE ACESSO RESTRITO</small></h3></p>
				<p><h5>UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?></h5></p>
			</td>
		</tr>
	</table>
</div>

<div class='row'>

	<div id="div_dashboard_adt">
		<!-- Exibe Dashboard -->
	</div>

</div>

	   

</body>
</html>

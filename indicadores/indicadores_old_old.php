<?php
//Indicadores
define("Version", "123");
include ("funcoes/verificaAtenticacao.php");
include ("funcoes/formata_dados.php");
$hoje = date('Y-m-d');
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

?>
<html>

<head>
	<script src="indicadores/indicadores.js?<?php echo Version; ?>" type="text/javascript"></script>

</head>


<body>

	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">
	    	<p><h3>PRODUÇÃO DIÁRIA<small>  PROCESSOS DO SIGAPCE SFPC/2</small></h3></p>
	    	
	    	<select id='cmb_unidade' name='cmb_unidade' class='form-control input-sm' onchange='exibeIndicadoresUnidade()'>
	    	 <?php
                         
              include ("../funcoes/conexao.php");
              mysqli_query($conn,"SET NAMES 'utf8';");
              $query = "SELECT id_unidade, nm_unidade FROM unidade";

              // executa a query
              $dados = mysqli_query($conn,$query) or die(mysql_error());
              // transforma os dados em um array
              $linha = mysqli_fetch_assoc($dados);
              // calcula quantos dados retornaram
              $totalLinhas = mysqli_num_rows($dados);

              //echo("<option value='0' selected>*** EXIBIR DE TODAS UNIDADES ***</option>");

              if($totalLinhas > 0)
              {
                do{

                  if($linha['nm_unidade'] == $nm_unidade)
                  {
                    echo("<option value='". $linha['id_unidade'] . "' selected>" . $linha['nm_unidade'] . "</option>");
                  }
                  else
                  {
                    echo("<option value='". $linha['id_unidade'] . "'>" . $linha['nm_unidade'] . "</option>");
                  }

                }while($linha = mysqli_fetch_assoc($dados));
                mysqli_free_result($dados);
                
              }
            ?>      
        </select>

        

	    </h3>
	  </div>
	  <div class="panel-body">

	  	
	  	<div class="well well-lg">

	  		
	  		INFORMAÇÕES EM TEMPO REAL DA UNIDADE
	  		<div id='div_unidade'></div>
	  		<h5><b><?php echo(retornaDataExtenso($hoje));?></b></h5>

	  	</div>

	  	<div class='row'>
	  		
	  		<div class='col-md-12'>
	    
			  	<div class='row'>
			  		
			  		<div class='col-md-6'>
			  			<h5>ENTRADA DE PROCESSOS NO DIA</h5>
			  			<div class="panel panel-default">
						  <div class="panel-body">
						    
						    
						    <div id='div_protocolados_dia'></div>


						  </div>
						</div>

			  			

			  		</div>

			  		<div class='col-md-6'>

			  			<h5>PROCESSOS GERENCIADOS POR ANALISTA</h5>
			  			<div class="panel panel-default">
						  <div class="panel-body">
						    
						  	<div id='div_gerenciados_analista_dia'></div>

						  </div>
						</div>

			  		</div>

			  	</div>

			</div>

		</div>


		<div class='row'>
	  		
	  		<div class='col-md-12'>

			  	<div class='row'>
			  		
			  		<div class='col-md-6'>
			  			<h5>GERENCIAMENTOS DO DIA POR STATUS</h5>
			  			<div class="panel panel-default">
						  <div class="panel-body">
						    
						  	<div id='div_gerenciados_dia'></div>

						  </div>
						</div>

			  		</div>

			  	</div>


			  </div>
			</div>

		</div>

	</div>

</body>
</html>
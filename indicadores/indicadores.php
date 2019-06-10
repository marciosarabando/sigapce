<?php
//Indicadores
define("Version", "1455");
include ("funcoes/verificaAtenticacao.php");
include ("funcoes/formata_dados.php");
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$data_atual_format_d_m_ano = date('d-m-Y');
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



?>
<html>

<head>
	<script src="indicadores/indicadores.js?<?php echo Version; ?>" type="text/javascript"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
	  <script>
	  $(function () 
		 {
	        $('#periodo_inicial').datetimepicker({format: 'DD/MM/YYYY'});
	        $('#txt_dt_inicio_periodo').mask('99/99/9999');
	        $('#periodo_final').datetimepicker({format: 'DD/MM/YYYY'});
	        $('#txt_dt_fim_periodo').mask('99/99/9999');
	     });
	  </script>
</head>


<body onload="exibeIndicadoresUnidade()">

	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">
	    	<p><h3>PRODUÇÃO DIÁRIA<small>  PROCESSOS DO SIGAPCE SFPC/2</small></h3></p>
	    	INFORMAÇÕES DA PRODUÇÃO DIÁRIA DA UNIDADE
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

	    <br>

	    <!-- FORM PARA SELEÇÃO DE PERÍODO -->

	    <div class='row'>
	    	<div class="col-md-12">
	    		<div class='row'>
	    			<div class="form-group">
					    <div class="col-sm-2">
					      	<input type="radio" name="rdb_tipo_pesquisa" id="rdb_tipo_pesquisa" value="1" onclick='muda_tipo_pesquisa()' checked><font size=2> <b>TEMPO REAL</b></font>
					    </div>

					    <div class="col-sm-2">
					    	<input type="radio" name="rdb_tipo_pesquisa" id="rdb_tipo_pesquisa" value="2" onclick='muda_tipo_pesquisa()'><font size=2> <b>POR PERÍODO</b></font>
				   		</div>

				   		<div class="col-sm-2">
		    				<label>DATA INICIAL</label>
		    				<div class='input-group date' id='periodo_inicial'>
			    				<input type='text' class='form-control' id='txt_dt_inicio_periodo' name='txt_dt_inicio_periodo' value='<?php echo($data_atual_format_d_m_ano);?>' disabled/>
								<span class='input-group-addon'>
			                    	<span class='glyphicon glyphicon-calendar'></span>
			                    </span>
			                </div>
	    				</div>

	    				<div class="col-md-2">
		    				<label>DATA FINAL</label>
		    				<div class='input-group date' id='periodo_final'>
								<input type='text' class='form-control' id='txt_dt_fim_periodo' name='txt_dt_fim_periodo' value='<?php echo($data_atual_format_d_m_ano);?>' disabled/>
								<span class='input-group-addon'>
			                    	<span class='glyphicon glyphicon-calendar'></span>
			                    	
			                   
			                    </span>
			                    <span>

			                    <button type="button" id='btn_pesquisa_periodo' class="btn btn-default btn glyphicon glyphicon-search" onclick="exibeIndicadoresUnidadePeriodo()" disabled>  </button>
			                	</span>
			                </div>
		    			</div>


				  	</div>
				</div>

	    	</div>	
	    </div>
	  </div>
	</div>

	<div class="panel-body">
	  	
	  	<div class='row'>
	  		
	  		<div class='col-md-12'>
	    
			  	<div class='row'>
			  		
			  		<div class='col-md-6'>
			  			<h5>ENTRADA DE PROCESSOS NO PERÍODO</h5>
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
			  			<h5>GERENCIAMENTOS POR STATUS</h5>
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
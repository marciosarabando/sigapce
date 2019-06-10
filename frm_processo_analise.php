<?php
include ("funcoes/verificaAtenticacao.php");
date_default_timezone_set('America/Sao_Paulo');
$data_atual = date('d-m-Y');
define("Version", "2");
//Verifica se o usuário tem o perfil para acessar a página, se não dá acesso negado
  if (!isset($_SESSION)) 
  {
    session_start();
  }
  if(isset($_SESSION['id_login_perfil']))
  {
    $id_login_perfil = $_SESSION['id_login_perfil'];   
  }
  $perfisPermitido = array("1","2","3","4","5");

  if (in_array($id_login_perfil, $perfisPermitido)) {   
    }
  else
  {
    echo "<div align='center'><br><br><h2>USUÁRIO SEM PERMISSÃO DE ACESSO NESTA FUNÇÃO!</h2></div>"; 
    echo "<meta http-equiv='refresh' content='0;URL=home.php'>";  
  } 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA DE ANÁLISE DE PROCESSO">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  

  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/moment.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  <script src="processo/frm_processo_analise.js?<?php echo Version; ?>" type="text/javascript"></script>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>

   <script>
    jQuery(function($){
       $("#txt_pesquisa_cpf").mask("999.999.999-99");
       $("#txt_pesquisa_cnpj").mask("99.999.999/9999-99");
       $( "#txt_pesquisa_protocolo" ).focus();
    });
  </script>


  <script src="js/jquery.dataTables.js"></script>


  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

  <script type="text/javascript">
            $(function () {
                $('#dt_filtro_pesquisa').datetimepicker({format: 'DD/MM/YYYY'});
            });
            
  </script>

  <script>
     $(document).keypress(function(e) {
      if(e.which == 13) {
        // enter pressed
        buscaProcessosPesquisa();
      }
    });
  </script>

 </head>

<body onload="">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="sisprot.php">SISPROT</a></li>
  <li class="active">PROCESSO</li>
  <li class="active"><a href="sisprot.php?url=processo_analise">ANALISAR</a></li>
</ol>


	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">ANÁLISE DE PROCESSOS DO SFPC/2</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
		<div class="panel-body">
		    <!-- ... Corpo do Painel ... -->
		    
			<!-- ... Div Linha ... -->
		    <div class="row">
		    	<!-- ... Div Coluna da Esquerda ... -->
		    	<div class="col-md-12" id="div_corpo_painel_analise_processo">
           
            <fieldset>
            <legend>BUSCAR PROCESSO PARA ANÁLISE</legend>
            <label>BUSCAR PROCESSO POR:</label>  
            
            <div class="row">
   
           
              <div class="col-md-3" id='div_tipo_pesquisa'>
                <div class="form-group" >
                    <select id="cmb_tipo_pesquisa" class="form-control" onchange="alteraCampoPesquisa()">
                        <option value="protocolo">Nº PROTOCOLO</option>
                        <option value="status">STATUS</option>
                        <option value="cpf">CPF</option>
                        <option value="cnpj">CNPJ</option>
                        <option value="cr">Nº DO CR</option>
                        <option value="tr">Nº DO TR</option>
                        <option value="requerente">REQUERENTE</option>
                    </select>
                </div>
              </div>

              <div class="col-md-9" id="div_campo_pesquisa">
              
                <div class="row clearfix" id="div_pesquisa_protocolo"> 
                  <div class="row clearfix">  
                    <div class="col-md-4">
                       <div class="input-group">
                                  <input type="text" onblur="" class="form-control" id="txt_pesquisa_protocolo" name="txt_pesquisa_protocolo" onkeypress='return SomenteNumero(event)' placeholder="Digite o Nº do Protocolo"/>
                                  <span class="input-group-btn">
                                      <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                                  </span>
                              </div>
                    </div>
                  </div>
                </div>

                 <div class="row clearfix" id="div_pesquisa_status" hidden> 
                  <div class="row clearfix">  
                    <div class="col-md-4">
                       
                                  <select id="cmb_status" name="cmb_status" class="form-control" onchange="alteraCampoPesquisa()">
                                    <?php
                                      //Preenche o combo do tipo de solicitação
                                      //Conecta no Banco de Dados
                                      include ("funcoes/conexao.php");
                                      mysqli_query($conn,"SET NAMES 'utf8';");
                                      $query = "SELECT 
                                                processo_status.id_processo_status,
                                                processo_status.nm_processo_status
                                           FROM processo_status ORDER BY processo_status.nm_processo_status
                                           ";
                                      // executa a query
                                      $dados = mysqli_query($conn,$query) or die(mysql_error());
                                      // transforma os dados em um array
                                      $linha = mysqli_fetch_assoc($dados);
                                      // calcula quantos dados retornaram
                                      $totalLinhas = mysqli_num_rows($dados);

                                      if($totalLinhas > 0)
                                      {
                                        do{
                                          echo("<option value='". $linha['id_processo_status'] . "'>" . $linha['nm_processo_status'] . "</option>");      
                                        }while($linha = mysqli_fetch_assoc($dados));
                                        mysqli_free_result($dados);
                                      }
                                    ?>              
                                  </select>
                                 
                      
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                                  <select id="cmb_filtro_data" name="cmb_filtro_data" class="form-control" onchange="mostra_oculta_FiltroData()">
                                      <option value="todos">MOSTRAR TODOS</option>
                                      <option value="data">FILTRAR POR DATA</option>
                                  </select>
                        </div>
                    </div>

                    <div class="col-md-3" id='div_txt_data_filtro' hidden>
                        <div class="input-group date" id="dt_filtro_pesquisa">
                            <input type="text" class="form-control" id="txt_data_filtro" name="txt_data_filtro" value="<?php echo($data_atual); ?>"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button> 
                    </div>


                  </div>
                </div>

                <div class="row clearfix" id="div_pesquisa_cpf" hidden> 
                  <div class="row clearfix">  
                    <div class="col-md-4">
                       <div class="input-group">
                                  <input type="text" onblur="" class="form-control" id="txt_pesquisa_cpf" name="txt_pesquisa_cpf" placeholder="Digite o CPF"/>
                                  <span class="input-group-btn">
                                      <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                                  </span>
                                  
                              </div>
                    </div>
                  </div>
                </div>

              <div class="row clearfix" id="div_pesquisa_cnpj" hidden>  
                <div class="row clearfix">  
                  <div class="col-md-4">
                    <div class="input-group">
                      <input type="text" onblur="" class="form-control" id="txt_pesquisa_cnpj" name="txt_pesquisa_cnpj" placeholder="Digite o CNPJ"/>
                      <span class="input-group-btn">
                          <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row clearfix" id="div_pesquisa_cr" hidden>  
                  <div class="row clearfix">  
                    <div class="col-xs-4">
                       <div class="input-group">
                                  <input type="text" onblur="" class="form-control" id="txt_pesquisa_cr" name="txt_pesquisa_cr" placeholder="Digite o Nº do CR"/>
                                  <span class="input-group-btn">
                                      <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                                  </span>
                                  
                              </div>
                    </div>
                  </div>
                </div>

                <div class="row clearfix" id="div_pesquisa_tr" hidden>  
                  <div class="row clearfix">  
                    <div class="col-xs-4">
                       <div class="input-group">
                                  <input type="text" onblur="" class="form-control" id="txt_pesquisa_tr" name="txt_pesquisa_tr" placeholder="Digite o Nº do TR"/>
                                  <span class="input-group-btn">
                                      <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                                  </span>
                                  
                              </div>
                    </div>
                  </div>
                </div>

                <div class="row clearfix" id="div_nm_requerente" hidden>  
                  <div class="row clearfix">  
                    <div class="col-xs-8">
                       <div class="input-group">
                                  <input type="text" onblur="" class="upper form-control" id="txt_nm_requerente" name="txt_nm_requerente" placeholder="Digite o Nome do Requerente"/>
                                  <span class="input-group-btn">
                                      <button onClick="buscaProcessosPesquisa()" class="btn btn-default" id="btn_pesquisa_processo"><i class="glyphicon glyphicon-search"></i></button>   
                                  </span>
                                  
                              </div>
                    </div>
                  </div>
                </div>

  


  </div>              
</div>

<hr>
            <div class="row">
              <div class="col-md-12" id="div_resultado_consulta">
                

              </div>
            </div>


          </fieldset>





		    	</div>
		    </div>
		</div>
	</div>

</body>

</html>
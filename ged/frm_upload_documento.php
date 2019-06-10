<?php
//PESQUISA DOCUMENTOS DIGITALIZADOS GED
include ("funcoes/verificaAtenticacao.php");
define("Version", "123");
if (!isset($_SESSION)) 
{
  session_start();
}
if(isset($_SESSION['id_login_sfpc']))
{

  $id_login_logado = $_SESSION['id_login_sfpc'];
  $id_login_perfil = $_SESSION['id_login_perfil'];
  $id_unidade_sfpc = $_SESSION['id_unidade_sfpc'];
}

include ("funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Fiscalização Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA UPLOAD DE DOCUMENTO">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  
  <script src="ged/ged.js?<?php echo Version; ?>" type="text/javascript"></script>

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>
  <script src="js/jquery_form.js" type="text/javascript"></script>

</head>

<body onload="InicializaPagina()">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="ged.php">GED</a></li>
  <li class="active"><a href="ged.php?url=upload_documento">IMPORTAÇÃO DE DOCUMENTO DIGITAL</a></li>
</ol>


<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">IMPORTAÇÃO DE DOCUMENTO DIGITALIZADO</h2>
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_corpo_upload_documento" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-6">
             
              <fieldset>
              <legend><h5>DADOS DO DOCUMENTO</h5></legend>

                <div class="row" id="div_carteira"> 
                  <div class="col-md-4">
                    <label>CARTEIRA</label>
                  </div>
                  
                  <div class="col-md-8">
                    <select id='cmb_carteira' name='cmb_carteira' class='form-control input-sm' onchange='carregaComboServico()'>
                          <?php
                            $query = "SELECT id_carteira, ds_carteira FROM carteira";
                            // // executa a query
                            $dados = mysqli_query($conn,$query) or die(mysql_error());
                            // transforma os dados em um array
                            $linha = mysqli_fetch_assoc($dados);
                            // calcula quantos dados retornaram
                            $totalLinhas = mysqli_num_rows($dados);

                            echo("<option value='0' selected>*** SELECIONE ***</option>");

                            if($totalLinhas > 0)
                            {
                             do{
                                  echo("<option value='". $linha['id_carteira'] . "'>" . $linha['ds_carteira'] . "</option>");  
                             }while($linha = mysqli_fetch_assoc($dados));
                             mysqli_free_result($dados); 
                            }
                          ?>
                    </select>   
                  </div>
                </div>

                <p>
                
                <div id="div_combo_servico"> 
                </div>

                <p>

                <div class="row" id='div_tipo_pessoa' hidden> 
                  <div class="col-md-4">
                    <label>TIPO DE PESSOA</label>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label class="radio-inline">
                        <input type="radio" id="rdb_cpf" name="cpf_cnpj" value="0" onclick="alteraTipoPF_PJ('div_cpf','none')"> FÍSICA (CPF)</input>
                      </label>
                      <label class="radio-inline">
                        <input type="radio" id="rdb_cnpj" name="cpf_cnpj" value="1" onclick="alteraTipoPF_PJ('div_cnpj','none')"> JURÍDICA (CNPJ)</input>
                      </label>
                    </div>
                  </div>
                </div>

                <div id="div_cpf" hidden>
                  <div class="row"> 
                    <div class="col-md-4">
                      <label>CPF</label>
                    </div>
                    <div id='div_txt_cpf' class="col-md-8">
                      <input type="text" onblur='' class="form-control input-sm" id="txt_cpf" name="txt_cpf" onkeypress='return SomenteNumero(event)' onkeyup="mostraComboTipoDocumento('pf')"/>
                    </div>
                  </div>
                </div>

                <div id="div_cnpj" hidden>
                  <div class="row"> 
                    <div class="col-md-4">
                      <label>CNPJ</label>
                    </div>
                    <div id='div_txt_cnpj' class="col-md-8">
                      <input type="text" onblur='' class="form-control input-sm" id="txt_cnpj" name="txt_cnpj" onkeypress='return SomenteNumero(event)' onkeyup="mostraComboTipoDocumento('pj')"/>
                    </div>
                  </div>
                </div>

                <p>

                <div id="div_combo_documento_tipo" hidden> 
                    <div class="row"> 
                      <div class="col-md-4">
                        <label>DOCUMENTO</label>
                      </div>
                      <div class="col-md-8">
                        <select id='cmb_tipo_documento' name='cmb_tipo_documento' class='form-control input-sm' onchange='carregaIndexadoresDocumento()'>
                        <?php
                            $query = "SELECT id_documento_tipo, sg_documento_tipo, nm_documento_tipo FROM documento_tipo";
                            // // executa a query
                            $dados = mysqli_query($conn,$query) or die(mysql_error());
                            // transforma os dados em um array
                            $linha = mysqli_fetch_assoc($dados);
                            // calcula quantos dados retornaram
                            $totalLinhas = mysqli_num_rows($dados);

                            echo("<option value='0' selected>*** SELECIONE O TIPO DE DOCUMENTO ***</option>");

                            if($totalLinhas > 0)
                            {
                             do{
                                  echo("<option value='". $linha['id_documento_tipo'] . ";". $linha['sg_documento_tipo'] ."'>" . $linha['nm_documento_tipo'] . "</option>");  
                             }while($linha = mysqli_fetch_assoc($dados));
                             mysqli_free_result($dados); 
                            }
                        ?>
                        </select>
                      </div>
                    </div>
                </div>

                <p>

                <div id="div_indexadores_documento" hidden> 
                </div>

                <div id='div_upload_documento' hidden>
                  <div class='row'>
                    <div class='col-md-4'>
                      <label>ARQUIVO DIGITAL (PDF)</label>
                    </div>

                    <div id='div_form_upload' class='col-md-8'>
                        <!-- Form criado dinamicamente via Jquery no carregamento do ged.js-->
                    </div>

                  </div>
                </div>
                <br>
                <div id="div_botao_upload" hidden>
                  <div class='row'>
                    <div id='div_btn_importar' class="col-md-12" hidden>
                      <button type="button" id="btn_importar_documento" class="form-control btn btn-primary" onclick=""><i class="glyphicon glyphicon-cloud-upload"></i> INICIAR IMPORTAÇÃO DO ARQUIVO</button>
                    </div>
                  </div>
                </div>

            </fieldset>
            </div>
            <!-- ... FIM da Div col-md-6 ... -->

            <!-- ...Coluna do Lado Direito -->
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                  <fieldset>
                    <legend>
                        <h5>PROCESSAMENTO DA IMPORTAÇÃO</h5>
                    </legend>
                  
                    <div class="panel panel-default">
                      
                      <!-- CONTEÚDO DO PAINEL DE CARREGAMENTO -->
                      <div id='div_painel_upload' class="panel-body" style="height:280px;overflow-y:scroll;width:100%;">

                        <div class="row">
                          
                          <!-- Div que recebe o CPF/CNPJ do arquivo que está sendo importado -->
                          <div id='div_cpf_cnpj_upload' class="col-md-4">
                          </div>
                          
                          <!-- Div que recebe a barra de progresso do arquivo que está sendo importado -->
                          <div id='div_barra_upload' class="col-md-6">
                          </div>

                          <!-- Div que recebe a a resposta do status do arquivo que foi importado -->
                          <div class="col-md-2" id='div_resul_upload'>
                          </div>

                        </div>

                        <!-- Div que recebe mensagem de erro em caso de falha -->
                        <div class="row">
                          <div id='div_msg_err' class="col-md-12">

                          </div>
                        </div>


                      </div>

                    </div>

                    <div style='text-align: right;'>
                      <!-- BOTAO PARA LIMPAR JANELA DE PROCESSAMENTO -->
                      <button type="button" id='btn_limpa_janela_processamento' class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> LIMPAR PROCESSAMENTOS CONCLUÍDOS</button>
                    </div>

                  </fieldset>
                 
                </div>

              </div>

            </div>



          </div>
          <!-- ... FIM da Div row ... -->
            
          

          <p>

          

          
          

          


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

<!-- ... Inputs Hidden de Controle ... -->
<input id='txt_sg_documento_tipo_selecionado' hidden></input>

</body>
</html>
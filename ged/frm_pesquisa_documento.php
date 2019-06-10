<?php
//PESQUISA DOCUMENTOS DIGITALIZADOS GED
include ("funcoes/verificaAtenticacao.php");
define("Version", "12345");
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

//echo("CADASTRO LOGIN SAE");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Fiscalização Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA DE PESQUISA DO DOCUMENTO">
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

</head>

<body onload="InicializaPagina()">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="ged.php">GED</a></li>
  <li class="active"><a href="ged.php?url=pesquisa_documento">PESQUISA DOCUMENTO DIGITAL</a></li>
</ol>


<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">PESQUISA DE DOCUMENTOS DIGITALIZADOS</h2>
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_corpo_pesquisa_documento" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm">
                <div class="row">
                  
                  <div class="col-md-3" id="div_combo_documento">
                    <div class='form-group'>
                      <label for="cmb_om_atendimento">DOCUMENTO</label>
                     
                        <?php
                          echo("<select id='cmb_tipo_documento' name='cmb_tipo_documento' class='form-control input-sm' onchange='carregaComboIndexadores()'>"); 
                          //Preenche o combo do tipo de documento
                          // //Conecta no Banco de Dados
                          include ("funcoes/conexao.php");
                          mysqli_query($conn,"SET NAMES 'utf8';");
                          $query = "SELECT id_documento_tipo, nm_documento_tipo FROM documento_tipo";

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
                                echo("<option value='". $linha['id_documento_tipo'] . "'>" . $linha['nm_documento_tipo'] . "</option>");  
                           }while($linha = mysqli_fetch_assoc($dados));
                           mysqli_free_result($dados); 
                          }
                        ?>              
                        </select>
                    </div>
                  </div>
                  
                  <div class="col-md-3" id="div_combo_indexadores" hidden>
                    
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_texto" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_texto" name="txt_valor_texto"/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_numero" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_numero" name="txt_valor_numero" onkeypress='return SomenteNumero(event)'/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_data" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_data" name="txt_valor_data" onkeypress='return SomenteNumero(event)'/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_rg" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_rg" name="txt_valor_rg" onkeypress='return SomenteNumero(event)'/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_cpf" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_cpf" name="txt_valor_cpf" onkeypress='return SomenteNumero(event)'/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-3" id="div_campo_parametro_cnpj" hidden>
                      <div class='form-group'>
                      <label>FILTRO</label><br>        
                        <div class="input-group">
                          <input type="text" onblur='' class="form-control input-sm" id="txt_valor_cnpj" name="txt_valor_cnpj" onkeypress='return SomenteNumero(event)'/>
                          <span class='input-group-btn'>
                              <button onClick="buscaDocumento()" class='btn btn-default btn-sm' id='btn_pesquisa_processo'><i class='glyphicon glyphicon-search'></i></button>   
                          </span>
                        </div>
                      </div>
                  </div>

                </div>

                </div>
              </div>
            </div>
         
        
          <div class="row">
      			<div class="col-md-12">
              <div id="div_documentos_digitalizados" hidden>

      				</div>

      			</div>
    		  </div>

          <hr>

          <div class='row'>
            <div id='div_total_documentos_digitalizados' class='col-md-12'>
              
            </div>
          </div>


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->
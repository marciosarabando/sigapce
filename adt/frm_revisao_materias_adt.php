<?php
//FORM GESTÃO DE MATÉRIAS
include ("funcoes/verificaAtenticacao.php");
define("Version", "2");
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


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Serviço de Fiscalização de Produtos Controlados SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="TELA DE GESTÃO DE MATERIAS">
  <meta name="author" content="1 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  
  <script src="adt/adt.js?<?php echo Version; ?>" type="text/javascript"></script>

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>

</head>

<body onload="exibe_materias_pendentes()">

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="adt.php">ADT</a></li>
  <li class="active"><a href="adt.php?url=materia">GESTÃO DE MATÉRIAS</a></li>
</ol>


<div class="panel panel-default">
	<div class="panel-heading">
    <table>
      <tr>
        <td>
        	<h2 class="panel-title">MATÉRIAS PENDENTES DE REVISÃO</h2>
        </td>
        
        <td width="50">
          &nbsp;
        </td>  

        <td align="center"> 
           <a href = "adt.php?url=tipos_materia" style="text-decoration: none;" title="Cadastro de tipos de matéria"><img src="img/edit2.png" width="30"><br>
            <font size="2">Tipos de matéria</a> 
        </td>
     
        <td width="50">
          &nbsp;
        </td>  

        <td align="center"> 
           <a href = "adt.php" style="text-decoration: none;" title="Voltar à tela anterior"><img src="img/back.png" width="30"><br>
            <font size="2">Voltar</a> 
        </td>
      </tr>
    </table>  

		<!-- UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?> --> 
	</div>
	<div id="div_corpo_aprovacao_materias" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

        <div class="well well-sm">
          <div class='row'>
              <div class="col-md-6" id="div_filtro">
                      <div class='form-group'>
                          <label>EXIBIR: </label><br>
                          <div class="radio-inline">
                            <label class="label label-danger">
                              <input type="radio" name="rdb_filtro" id="rdb_filtro_pendentes" value="1" onclick="buscaSolicitacoesAcesso()" checked>
                              <b>PENDENTES</b>
                            </label>
                          </div>
                          
                          <!--
                          <div class="radio-inline">
                            <label class="label label-success">
                              <input type="radio" name="rdb_filtro" id="rdb_filtro_todos" value="2" onclick="buscaSolicitacoesAcesso()">
                              <b>POR DATA</b>
                            </label>
                          </div>
                          -->
                      </div>
                </div>
            </div>
        </div>

        <div class='row'>
          <div class='col-md-12'>

            <div class='row'>
              
              <div class='col-md-12'>
                <div id='div_materias_pendentes'>
                  <!-- ... Div Alimentada pela página adt/funcoes/exibe_materias_pendentes.php ... -->      
                </div>
                
                <!-- JANELA Modal ACIONADA PELO BOTÃO GERENCIAR-->
                  <div class='modal fade' id='myModalGerenciarMateria' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                    <div class='modal-dialog' role='document'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <button type='button' id='btn_fechar_modal' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                          <h4 class='modal-title' id='myModalLabel'>REVISÃO DE MATÉRIA </h4>
                        </div>
                        <div class='modal-body'>

                            <div id='div_detalhes_materia'>
                              <!-- ... Div Alimentada pela página adt/funcoes/exibe_detalhes_materia.php ... -->      
                            </div>

                            <div id='div_painel_controle_revisao_materia'>
                              
                                <div class="panel panel-default">
                                  <div class="panel-body">
                                      
                                      <div class='row' id='div_botoes_controles'>
                                        <div class='col-md-6' hidden>
                                          <center><button type='button' id='btn_solicita_correcao' onclick='solicitar_correcao_materia()' class='btn btn-warning'><i class='glyphicon glyphicon-pencil'></i> SOLICITAR CORREÇÃO</button> </center>
                                        </div>
                                        <div class='col-md-12'>
                                          <center><button type='button' id='btn_aprovar_materia' onclick='aprovar_materia()' class='btn btn-success' data-dismiss='modal'><i class='glyphicon glyphicon-thumbs-up'></i> APROVAR MATÉRIA</button></center>
                                        </div>
                                      </div>

                                      <div class='row' id='div_solicita_correcao' hidden>
                                        <div class='col-md-12'>

                                          <label>INFORMAÇÃO AO ANALISTA (PREENCHIMENTO OBRIGATÓRIO)</label>
                                          <textarea id='txt_correcao_materia' class='form-control upper' rows='3' maxlength=2000 placeholder='INFORME AQUI O MOTIVO DA CORREÇÃO SOLICITADA'></textarea>
                                          <br>
                                          <button type='button' id='btn_solicita_correcao' onclick='enviar_solicitacao_correcao_materia()' class='btn btn-primary btn-block'><i class='glyphicon glyphicon-ok'></i> ENVIAR SOLICITAÇÃO</button>

                                        </div>
                                      </div>

                                  </div>
                                </div>

                            </div>
      
                            
                            
                      </div>
                        <div class='modal-footer'>
                          <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
                          
                        </div>
                       </div>
                    </div>
                  </div>
                  <!--FECHA MODAL -->


              </div>
            </div>
            <!--FECHA LINHA/COLUNA -->

            <div class='row'>
            
              <div class='col-md-6'>
                <div id='div_notas_criadas'></div>
              </div>


            </div>

          </div>
        </div>


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

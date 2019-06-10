<?php
//GERENCIAMENTO DE SOLICITAÇÕES DE ACESSO
include ("funcoes/verificaAtenticacao.php");
include ("funcoes/parametro.php");
define("Version", "17888");
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
  <meta name="description" content="PAGINA CONTROLE DE ACESSO">
  <meta name="author" content="2 TEN SARABANDO">


  
  <script src="sae/ssae.js?<?php echo Version; ?>" type="text/javascript"></script>
  <script src="js/jquery.dataTables.js"></script>

  <script src="componentes/fullcalendar/fullcalendar.js" type="text/javascript"></script>
  <script src="componentes/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
  <script src="componentes/fullcalendar/lang-all.js" type="text/javascript"></script>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  

  
  <style type="text/css">
    @import "css/jquery.dataTables.css";
    @import "componentes/fullcalendar/fullcalendar.css";
  </style>


</head>

<?php 
  echo("<body onload='carregaCalendario($id_unidade_sfpc)'>");
?>



<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">GERENCIAMENTO DA AGENDA ELETRÔNICA</h2>
	
	</div>
	<div id="div_corpo_gerenciar_agenda" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm">
                
                <div class="row"> 
                  <div class="col-md-6" id="div_combo_om">

                    <div class='form-group'>
                      <label for="cmb_om_atendimento">UNIDADE:</label>
                     
                        <?php
                          if($id_unidade_sfpc == 1)
                          {
                            echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='carregaCalendario(\"$id_unidade_sfpc\")'>");
                          }
                          else
                          {
                            echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='carregaCalendario(\"$id_unidade_sfpc\")' disabled>");
                          }
                          //Preenche o combo do tipo de solicitação
                          //Conecta no Banco de Dados
                          include ("../../funcoes/conexao.php");
                          mysqli_query($conn,"SET NAMES 'utf8';");
                          $query = "SELECT id_unidade, nm_unidade FROM unidade";

                          // executa a query
                          $dados = mysqli_query($conn,$query) or die(mysql_error());
                          // transforma os dados em um array
                          $linha = mysqli_fetch_assoc($dados);
                          // calcula quantos dados retornaram
                          $totalLinhas = mysqli_num_rows($dados);

                         

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
                          echo("</select>");
                        ?>              
                      
                    </div>
                  
                  </div>

                </div>

                <div class="row"> 
                  <div class="col-md-12">

                    <div id='div_painel_parametros_atendimento_unidade'>
                      <!--Div Alimentada pela chamada da funcao javascript exibePainelParametrosUnidade() -->
                    </div>
                  
                  </div>


                </div>
              </div>
            </div>
          </div>
        

          <div class="row">
      			<div class="col-md-4">
             
                <div id="calendar"></div>

                <div id="div_horarios_criados">
                 
                 </div>
                 <div class="panel panel-default">
                  <div class="panel-heading">Legenda</div>
                  <div class="panel-body">
                    <span class="label label-primary">JAN - BLOQ</span> - <label><h6>JANELA BLOQUEADA. <small>AGUARDANDO LIBERAÇÃO PARA AGENDAMENTO.</small></h6></label><br>
                    <span class="label label-success">JAN - LIB</span> - <label><h6>JANELA LIBERADA. <small>DISPONÍVEL PARA AGENDAMENTOS.</small></h6></label><br>
                    <span class="label label-warning">JAN - AGEN</span> - <label><h6>JANELA COM USUÁRIO AGENDADO. <small>JANELA COM USUÁRIO AGENDADO.</small></h6></label>
                    <span class="label label-danger">JAN - LOT</span> - <label><h6>JANELA LOTADA. <small>JANELA COM TODOS OS HORÁRIOS AGENDADOS.</small></h6></label>
                  </div>
                </div>
                
      			</div>

            <div class="col-md-8">

                <div id="div_horarios"></div>

            </div>

    		  </div>
          <div id='div_data_criadas'></div>
          <input type=hidden id='dt_selecionada'></input>

	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->


</div>
<!-- ... FIM da Div Painel ... -->



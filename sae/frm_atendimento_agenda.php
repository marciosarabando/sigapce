<?php
//ATENDIMENTO DE USUÁRIOS AGENDADOS
include ("funcoes/verificaAtenticacao.php");
include ("funcoes/parametro.php");
include ("funcoes/formata_dados.php");
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

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Fiscalização Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA CONTROLE DE ACESSO">
  <meta name="author" content="2 TEN SARABANDO">

  <script src="sae/ssae.js" type="text/javascript"></script>
  <script src="js/jquery.dataTables.js"></script>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>

  <script type="text/javascript">
      $(function () {
          $('#dt_atendimento_usuarios_agendados').datetimepicker({format: 'DD/MM/YYYY'});
          $('#dt_atendimento_usuarios_agendados').datetimepicker({locale: 'pt-BR'});
          
          $('#txt_data_atendimento_usuarios_agendados').mask('99/99/9999');

          $("#dt_atendimento_usuarios_agendados").on("dp.change", function (e) {
              exibeUsuariosAgendados();
          });

      });
  </script>
  
  

  <script>
  
  </script>




</head>

<?php 
  echo("<body onload='exibeUsuariosAgendados()'>");
?>



<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">ATENDIMENTO DE USUÁRIOS AGENDADOS</h2>
	
	</div>
	<div id="div_corpo_gerenciar_agenda" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm">
                <div class="row">
                  
                  <div class="col-md-3" id="div_combo_om">
                    <div class='form-group'>
                      <label for="cmb_om_atendimento">UNIDADE:</label>
                     
                        <?php
                          if($id_unidade_sfpc == 1)
                          {
                            echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='exibeUsuariosAgendados()'>");
                          }
                          else
                          {
                            echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='exibeUsuariosAgendados()' disabled>");
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
                        ?>              
                      </select>
                    </div>
                  
                  </div>

                  <div id='div_painel_data_atendimento_unidade'>
                    <div class="col-md-3">
                      <label>DATA DO ATENDIMENTO:</label>
                      <div class="input-group date" id="dt_atendimento_usuarios_agendados">
                                <input type="text" class="form-control input-sm" id="txt_data_atendimento_usuarios_agendados" name="txt_data_atendimento_usuarios_agendados" value='<?php echo(formataDataDDMMYYYY($hoje)); ?>'/>
                                <span class="input-group-addon input-sm">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <button onClick="exibeUsuariosAgendados()" class="form-control input-sm" id="btn_mostra_usuarios_agendados"><i class="glyphicon glyphicon-search"></i></button>  
                      </div>
                    </div>

                     <div class="col-md-6">
                      <br>
                        <table>
                          <tr>
                            <td  width="120">
                            </td>
                            <td align="right">
                              <h5><b>Hoje é <?php echo(retornaDataExtenso($hoje));?></b></h5>
                            </td>
                          </tr>
                        </table>
                     </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        

          <div class="row">
      			<div class="col-md-12">
              <div id="div_usuarios_agendados"></div>
    
      			</div>
    		  </div>
         

	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->


</div>
<!-- ... FIM da Div Painel ... -->



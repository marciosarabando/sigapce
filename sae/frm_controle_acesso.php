<?php
//GERENCIAMENTO DE SOLICITAÇÕES DE ACESSO
include ("funcoes/verificaAtenticacao.php");
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

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>
  
  <script src="sae/ssae.js" type="text/javascript"></script>

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

</head>

<body onload='buscaSolicitacoesAcesso()'>

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="sae.php">SAE</a></li>
  <li class="active"><a href="sae.php?url=controle_acesso">CONTROLE DE ACESSO</a></li>
</ol>


<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">CADASTRO DE USUÁRIOS REQUERENTES</h2>
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_corpo_controle_acesso" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm">
                <div class="row">
                  
                  <div class="col-md-3" id="div_combo_om">
                    <div class='form-group'>
                      <label for="cmb_om_atendimento">UNIDADE:</label>
                     
                        <?php
                          //if($id_unidade_sfpc == 1)
                          //{
                            echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='buscaSolicitacoesAcesso()'>");
                          //}
                          // else
                          // {
                          //   echo("<select id='cmb_om_atendimento' name='cmb_om_atendimento' class='form-control input-sm' onchange='buscaSolicitacoesAcesso()' disabled>");
                          // }
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

                          echo("<option value='0' selected>*** EXIBIR DE TODAS UNIDADES ***</option>");

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
                  
                  <div class="col-md-6" id="div_filtro">
                    <div class='form-group'>
                        <label>FILTRO: </label><br>
                        <div class="radio-inline">
                          <label class="label label-danger">
                            <input type="radio" name="rdb_filtro" id="rdb_filtro_pendentes" value="1" onclick="buscaSolicitacoesAcesso()" checked>
                            <b>PENDENTES</b>
                          </label>
                        </div>
                        <div class="radio-inline">
                          <label class="label label-success">
                            <input type="radio" name="rdb_filtro" id="rdb_filtro_todos" value="2" onclick="buscaSolicitacoesAcesso()">
                            <b>MOSTRAR TODOS</b>
                          </label>
                        </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        

          <div class="row">
      			<div class="col-md-12">
              <div id="div_solicitacoes_acesso">

      				</div>

      			</div>
    		  </div>


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->
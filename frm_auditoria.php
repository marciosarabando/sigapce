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
  
  <script src="funcoes/funcoes.js" type="text/javascript"></script>

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

</head>

<body onload=''>



<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">AUDITORIA DE ACESSOS NO SIGAPCE</h2>
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_auditoria" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     

          <div class="row">
            <div class="col-md-12">
              <div class="well well-sm">
                <div class="row">

                    <div class="col-md-6" id="div_combo_om">
                      <div class='form-group'>
                        <label for="cmb_tipo_evento">TIPO DE EVENTO:</label>
                       
                          <?php
                            
                            echo("<select id='cmb_tipo_evento' name='cmb_tipo_evento' class='form-control input-sm' onchange='exibeEventosAuditoria()'>");
                            
                            //Preenche o combo do tipo de solicitação
                            //Conecta no Banco de Dados
                            include ("funcoes/conexao.php");
                            mysqli_query($conn,"SET NAMES 'utf8';");
                            $query = "SELECT id_tipo_evento, ds_tipo_evento FROM tipo_evento";

                            // executa a query
                            $dados = mysqli_query($conn,$query) or die(mysql_error());
                            // transforma os dados em um array
                            $linha = mysqli_fetch_assoc($dados);
                            // calcula quantos dados retornaram
                            $totalLinhas = mysqli_num_rows($dados);

                            echo("<option value='0' selected>SELECIONE...</option>");

                            if($totalLinhas > 0)
                            {
                              do{

                               
                                  echo("<option value='". $linha['id_tipo_evento'] . "'>" . $linha['ds_tipo_evento'] . "</option>");
                               

                              }while($linha = mysqli_fetch_assoc($dados));
                              mysqli_free_result($dados);
                              
                            }
                          ?>              
                        </select>
                    </div>
                  
                  </div>

                  <div class="col-md-6">
                    <br>
                    <button type="button" onclick="exibeUsuariosOnline()" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-user"></i> USUÁRIOS ONLINE</button>
                  </div>
                  
                  

                </div>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <div id="div_result_auditoria">

              </div>

            </div>
          </div>
        

          


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->
</body>
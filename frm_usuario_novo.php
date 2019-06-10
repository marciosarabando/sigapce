<?php
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


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA DE CADASTRO DE NOVO USUARIO DO SISTEMA">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  

  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/moment.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  <script src="usuario/frm_usuario_cadastro.js" type="text/javascript"></script>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>

  <script src="js/jquery.js"></script>
  <script src="js/jquery.dataTables.js"></script>


  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>



 </head>

<body>

<ol class="breadcrumb">
  <li><a href="home.php">SGPC</a></li>
  <li class="active">CADASTRO</li>
  <li class="active"><a href="home.php?url=cadastro_usuario">USUÁRIO</a></li>
  <li class="active">NOVO USUÁRIO</li>
</ol>

  <!-- ... Abre Painel ... -->
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">CONTROLE DE ACESSO AO SISTEMA SISFPC DO SFPC/2</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
    		<div class="panel-body">
    		<!-- ... Corpo do Painel ... -->
    		    
          <div class="row">
            <div class="col-md-12">
              
              <!-- ... COLUNA DA ESQUERDA ... -->
              <div class="col-md-6">
        
                <label>INFORMAÇÕES DO USUÁRIO</label>
                <div class='table-responsive'>
                <table class='table table-condensed table-bordered'>
                <tr>
                  <td>
                    <label>POSTO/GRADUAÇÃO:</label>
                  </td>
                  <td>
                        <select id='cmb_posto_graduacao' name='cmb_posto_graduacao' class='form-control'>
                        <?php
                        //Preenche o combo Posto/Graduacao
                        //Conecta no Banco de Dados
                        include ("funcoes/conexao.php");
                        mysqli_query($conn,"SET NAMES 'utf8';");
                        $query = "SELECT 
                                  id_posto_graduacao,
                                  nm_posto_graduacao
                             FROM posto_graduacao";
                        // executa a query
                        $dados = mysqli_query($conn,$query) or die(mysql_error());
                        // transforma os dados em um array
                        $linha = mysqli_fetch_assoc($dados);
                        // calcula quantos dados retornaram
                        $totalLinhas = mysqli_num_rows($dados);

                        if($totalLinhas > 0)
                        {
                          echo("<option value='0'>SELECIONE...</option>");
                          do{
                              echo("<option value='". $linha['id_posto_graduacao'] . "'>" . $linha['nm_posto_graduacao'] . "</option>");
                          }while($linha = mysqli_fetch_assoc($dados));
                          mysqli_free_result($dados);
                        }
                        ?>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label>LOGIN:</label>
                  </td>
                  <td>
                    <input type='text' class='upper form-control' id='nm_login' name='nm_login' placeholder='Ex.: Nome de Guerra'>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label>NOME DE GUERRA:</label>
                  </td>
                  <td>
                    <input type='text' class='upper form-control' id='nm_guerra' name='nm_guerra' placeholder='Digite o Nome de Guerra'>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label>NOME COMPLETO:</label>
                  </td>
                  <td>
                    <input type='text' class='upper form-control' id='nm_completo' name='nm_completo' placeholder='Digite o Nome Completo do Usuário'>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>E-MAIL:</label>
                  </td>
                  <td>
                    <input type='text' class='lower form-control' id='email' name='email' placeholder='Digite o e-mail'>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>OM SFPC PERTENCENTE:</label>
                  </td>
                  <td>
                    <select id='cmb_unidade_pertencente' name='cmb_unidade_pertencente' class='form-control'>
                    <?php
                    //Preenche o combo UNIDADES OM SFPC
                    //Conecta no Banco de Dados
                    include ("funcoes/conexao.php");
                    mysqli_query($conn,"SET NAMES 'utf8';");
                    $query = "SELECT 
                              id_unidade,
                              nm_unidade
                         FROM unidade
                         WHERE id_unidade in (SELECT id_unidade FROM login_unidade WHERE id_login = $id_login_logado)";
                    // executa a query
                    $dados = mysqli_query($conn,$query) or die(mysql_error());
                    // transforma os dados em um array
                    $linha = mysqli_fetch_assoc($dados);
                    // calcula quantos dados retornaram
                    $totalLinhas = mysqli_num_rows($dados);

                    if($totalLinhas > 0)
                    {
                      echo("<option value='0'>SELECIONE...</option>");
                      do{
                          echo("<option value='". $linha['id_unidade'] . "'>" . $linha['nm_unidade'] . "</option>");
                      }while($linha = mysqli_fetch_assoc($dados));
                      mysqli_free_result($dados);
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>PERFIL DE ACESSO:</label>
                  </td>
                  <td>
                    <select id='cmb_perfil_acesso' name='cmb_perfil_acesso' class='form-control'>
                    <?php
                    //Preenche o combo Perfil de Acesso
                    //Conecta no Banco de Dados
                    include ("funcoes/conexao.php");
                    mysqli_query($conn,"SET NAMES 'utf8';");
                    $query = null;
                    //SE FOR ADMINISTRADOR DO SISTEMA
                    if($id_login_perfil == 1)
                    {
                        $query = "SELECT 
                              id_login_perfil,
                              nm_login_perfil
                         FROM login_perfil";
                    }
                    //SE FOR CHEFE DO SFPC
                    else if($id_login_perfil == 2)
                    {
                        $query = "SELECT 
                              id_login_perfil,
                              nm_login_perfil
                         FROM login_perfil
                         WHERE id_login_perfil > 1";
                    }
                    //CHEFE DE UNIDADE SUBORDINADA
                    else if($id_login_perfil == 3)
                    {
                        $query = "SELECT 
                                  id_login_perfil,
                                  nm_login_perfil
                             FROM login_perfil
                             WHERE id_login_perfil > 3";
                    }
                    //CHEFE DE CARTEIRA
                    else if($id_login_perfil == 4)
                    {
                        $query = "SELECT 
                                  id_login_perfil,
                                  nm_login_perfil
                             FROM login_perfil
                             WHERE id_login_perfil > 4";
                    }

                    // executa a query
                    $dados = mysqli_query($conn,$query) or die(mysql_error());
                    // transforma os dados em um array
                    $linha = mysqli_fetch_assoc($dados);
                    // calcula quantos dados retornaram
                    $totalLinhas = mysqli_num_rows($dados);

                    if($totalLinhas > 0)
                    {
                      echo("<option value='0'>SELECIONE...</option>");
                      do{
                          echo("<option value='". $linha['id_login_perfil'] . "'>" . $linha['nm_login_perfil'] . "</option>");
                      
                      }while($linha = mysqli_fetch_assoc($dados));
                      mysqli_free_result($dados);
                    }
                    ?>
                  </td>
                </tr>
              </table>
              </div>

              </div>

              <!-- ... COLUNA DA DIREITA ... -->
              <div class="col-md-6">
              
                    <div class='col-md-4'>
                    <label>CARTEIRAS:</label>
                    <select multiple id='sel_mult_carteiras' class='form-control' size=10 style='height: 100%;'>
                      <?php
                        include ("funcoes/conexao.php");
                        mysqli_query($conn,"SET NAMES 'utf8';");
                        $query = "SELECT 
                                    id_carteira,
                                    sg_carteira
                               FROM carteira
                               WHERE id_carteira in (SELECT id_carteira FROM login_carteira WHERE id_login = $id_login_logado)";
                        $dados = mysqli_query($conn,$query) or die(mysql_error());
                        $linha = mysqli_fetch_assoc($dados);
                        // calcula quantos dados retornaram
                        $totalLinhas = mysqli_num_rows($dados);
                        if($totalLinhas > 0)
                        {
                          do
                            {
                              
                              echo("<option value='". $linha['id_carteira'] . "'>" . $linha['sg_carteira'] . "</option>");

                              }while($linha = mysqli_fetch_assoc($dados));
                            mysqli_free_result($dados);
                          
                        }
                      ?>
                    </select>
                    </div>

                    <div class='col-md-2'>
                      <br><br><br>
                      <center><button type='button' class='btn btn-default' id='btnAddAcessoCarteira' onclick='addAcessoCarteiraUsuario()'><i class='glyphicon glyphicon-arrow-right'></i></button></center>
                      <br>
                      <center><button type='button' class='btn btn-default' id='btnAddAcessoCarteira' onclick='removeAcessoCarteiraUsuario()'><i class='glyphicon glyphicon-arrow-left'></i></button></center>
                    </div>

                    <div class='col-md-4'>
                      <label>ACESSO EM:</label>
                      <select multiple id='sel_mult_carteiras_acesso' class='form-control' size=10 style='height: 100%;'>
                      </select>
                      <br>
                    </div>


                    <!-- JANELAS DE SELECAO DE MODULOS-->
                    <div class='col-md-4'>
                    <label>MODULOS:</label>
                    <select multiple id='sel_mult_modulo' class='form-control' size=5 style='height: 100%;'>
                      <?php
                        include ("funcoes/conexao.php");
                        mysqli_query($conn,"SET NAMES 'utf8';");
                        $query = "SELECT 
                                    id_modulo,
                                    nm_modulo
                               FROM modulo
                               ";
                        $dados = mysqli_query($conn,$query) or die(mysql_error());
                        $linha = mysqli_fetch_assoc($dados);
                        // calcula quantos dados retornaram
                        $totalLinhas = mysqli_num_rows($dados);
                        if($totalLinhas > 0)
                        {
                          do
                            {
                              
                              echo("<option value='". $linha['id_modulo'] . "'>" . $linha['nm_modulo'] . "</option>");

                              }while($linha = mysqli_fetch_assoc($dados));
                            mysqli_free_result($dados);
                          
                        }
                      ?>
                    </select>
                    </div>

                    <div class='col-md-2'>
                      <br><br>
                      <center><button type='button' class='btn btn-default' id='btnAddAcessoModulo' onclick='addAcessoModuloUsuario()'><i class='glyphicon glyphicon-arrow-right'></i></button></center>
                      <br>
                      <center><button type='button' class='btn btn-default' id='btnAddAcessoModulo' onclick='removeAcessoModuloUsuario()'><i class='glyphicon glyphicon-arrow-left'></i></button></center>
                    </div>

                    <div class='col-md-4'>
                      <label>ACESSO EM:</label>
                      <select multiple id='sel_mult_modulos_acesso' class='form-control' size=5 style='height: 100%;'>
                      </select>
                    </div>




                    <div class='col-md-10'>
                      <br>
                      <button type='button' class='btn btn-success btn-block' id='btn_incluir_usuario' onclick='incluir_usuario()'><i class='glyphicon glyphicon-floppy-disk'></i> CRIAR USUÁRIO</button>
                      <br>
                      
                      <div id='div_resultado_novo_usuario'>
                          
                      </div>
                      <div class="alert alert-danger alert-dismissible" role="alert" id='div_alert_erro' hidden>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <div id='div_msg_erro'>
                              
                            </div>
                          </div>

                    </div>
             
               
              <!-- ... FECHA COLUNA DA DIREITA ... -->
              </div>
              
            <!-- ... FECHA COLUNA 12 LINHA 1... -->
            </div>
         
          <!-- ... FECHA LINHA 1... -->
    			</div>

          <!-- ... ABRE LINHA 2... -->
          <div class="row">
            <!-- ... ABRE COLUNA 12 LINHA 2... -->
            <div class="col-md-12">
              <div class="col-md-12">
              
              <!-- ...CORPO LINHA 2 PARA SELEÇÃO DE ACESSOS EM UNIDADES... -->

              <label>MARQUE AS UNIDADES DE SFPC QUE O USUÁRIO POSSUIRÁ ACESSO</label>
                

                <div class='table-responsive'>
                  <table class='table table-condensed table-bordered' id='tb_unidades_acesso'>
                    <thead>
                      <tr>
                       
                        <th>
                        
                          <label class='btn btn-primary btn-xs btn-block'>
                              <input type='checkbox' name='unidades[]'  autocomplete='off' onClick='MarcarDesmarcarTodasUnidades(this.checked)'> Marcar / Desmarcar Todas
                          </label>
                        </th>
                       
                        
                        
                      </tr>
                    </thead>
                  <tbody>

                    <?php
                      //Preenche o combo UNIDADES OM SFPC
                      //Conecta no Banco de Dados
                      include ("funcoes/conexao.php");
                      mysqli_query($conn,"SET NAMES 'utf8';");
                      $query1 = "SELECT 
                                      unidade.id_unidade,
                                      nm_unidade,
                                      cidade.nm_cidade,
                                      cidade.uf_cidade

                                  FROM unidade
                                  INNER JOIN cidade on cidade.id_cidade = unidade.id_cidade
                                  INNER JOIN login_unidade on unidade.id_unidade = login_unidade.id_unidade
                                  WHERE login_unidade.id_login = $id_login_logado
                           ";
                      // executa a query
                      $dados1 = mysqli_query($conn,$query1) or die(mysql_error());
                      // transforma os dados em um array
                      $linha1 = mysqli_fetch_assoc($dados1);
                      // calcula quantos dados retornaram
                      $totalLinhas1 = mysqli_num_rows($dados1);

                      if($totalLinhas1 > 0)
                      {
                        do{
                            $id_unidade = $linha1['id_unidade'];
                            $nm_unidade = $linha1['nm_unidade'];
                            $nm_cidade = $linha1['nm_cidade'];
                            $nm_cidade .= " - ";
                            $nm_cidade .= $linha1['uf_cidade'];

                            echo("

                                  <tr class='active'>
                                    
                                    <td>
                                      
                                      <label class='btn btn-default btn-sm btn-block'>
                                                <input type='checkbox' name='unidades[]' autocomplete='off' onClick='inclui_exclui_unidade_selecionada(". $id_unidade .")' value='". $id_unidade ."'> ". $nm_unidade ." - ". $nm_cidade ."
                                      </label>
                                    </td>
                                    
                                    
                                  </tr>


                                ");


                          }while($linha1 = mysqli_fetch_assoc($dados1));
                          mysqli_free_result($dados1);
                      }



                    ?>


                  </tbody>
                </table>


                </div>

                
                <button type='button' class='btn btn-success btn-block' id='btn_incluir_usuario' onclick='incluir_usuario()'><i class='glyphicon glyphicon-floppy-disk'></i> CRIAR USUÁRIO</button>

           
              </div>
            <!-- ... FECHA COLUNA 12 LINHA 2... -->
            </div>
         
          <!-- ... FECHA LINHA 2... -->
          </div>

        
            
        <!-- ... Fecha Corpo do Painel ... -->
		    </div>

  <!-- ... Fecha Painel ... -->
	</div>
   
  <!--<input id='id_unidades_selecionadas' hidden></input>-->
  <input id='id_unidades_selecionadas' hidden></input>
  <input id='id_carteiras_selecionadas' hidden></input>
  <input id='id_modulos_selecionados' hidden></input>

</body>

</html>
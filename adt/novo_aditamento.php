<?php
//FORM GESTÃO DE ADITAMENTOS
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

include ("funcoes/conexao.php");

###### MATÉRIAS APROVADAS PARA PUBLICAÇÃO #######

$query = "SELECT 
        adt_materia.id_adt_materia,
        processo.cd_protocolo_processo,
            processo_status.nm_processo_status,
            adt_materia_tipo.nm_adt_materia_tipo,
            adt_materia_status.nm_adt_materia_status,
            adt_materia_andamento.dt_adt_materia_andamento,
            posto_graduacao.nm_posto_graduacao,
            login.nm_login,
            processo_andamento.dt_processo_andamento

    FROM adt_materia
    INNER JOIN adt_materia_tipo on adt_materia_tipo.id_adt_materia_tipo = adt_materia.id_adt_materia_tipo
    INNER JOIN adt_materia_andamento on adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia
    INNER JOIN adt_materia_status on adt_materia_status.id_adt_materia_status = adt_materia_andamento.id_adt_materia_status
    INNER JOIN login on login.id_login = adt_materia_andamento.id_login
    INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
    INNER JOIN processo on processo.id_processo = adt_materia.id_processo
    INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
    INNER JOIN processo_status on processo_status.id_processo_status = adt_materia.id_processo_status

    WHERE   adt_materia.st_publicada = 0 
        AND adt_materia_status.id_adt_materia_status = 2
        AND adt_materia_andamento.id_adt_materia_andamento IN 
        (
          SELECT max(adt_materia_andamento.id_adt_materia_andamento) FROM adt_materia_andamento WHERE adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia
        )
        AND processo_andamento.id_processo_andamento IN
        (
          SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE
          processo.id_processo = processo_andamento.id_processo AND processo_andamento.id_processo_status in (6,7,13)
        )
    ";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Serviço de Fiscalização de Produtos Controlados SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="TELA DE GESTÃO DE ADITAMENTO">
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

<body> <!--  onload="exibe_materias_aprovadas()"> -->

<FORM action="adt/grava_adt.php" method="post" enctype="multipart/form-data" id="my_form"> 

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="adt.php">ADT</a></li>
  <li class="active"><a href="adt.php?url=aditamento">GESTÃO DE ADITAMENTOS</a></li>
  <li class="active"><a href="adt.php?url=novo">NOVO ADITAMENTO</a></li>
</ol>


<div class="panel panel-default" >
	<div class="panel-heading">
		<h2 class="panel-title">NOVO ADITAMENTO AO BAR</h2> 
		UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	</div>
	<div id="div_corpo_gestao_adt" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     
      <div class='row'>
        <div class='col-md-12'>
            
            <div class='row'>
              <div class='col-md-2'>
                <labeL>NR ADT</label>
              </div>  
              <div class='col-md-10'>
                <input type="text" class="form-control input-sm" id="txt_nr_adt" name="txt_nr_adt" placeholder="Digite o Número do Novo Aditamento" onkeyup="" onchange="verificaDados();"></input>

                <!-- campo oculto para transportar (post) posteriormente as matérias para o programa grava_adt.php -->  
                <input type="hidden" name="input_materias" id="input_materias" readonly></input>

              </div>
            </div>

            <p>

            <div class='row'>
              <div class='col-md-2'>
                <labeL>NR BAR</label>
              </div>  
              <div class='col-md-10'>
                <input type="text" class="form-control input-sm" id="txt_nr_bar" name="txt_nr_bar" placeholder="Digite o Número do BAR" onkeyup="" onchange="verificaDados();"></input>
              </div>
            </div>

            <p>

            <div class='row'>
              <div class='col-md-2'>
                <labeL>DATA</label>
              </div>  
              <div class='col-md-10'>
                <input type="text" class="form-control input-sm" id="txt_data_adt" name="txt_data_adt" placeholder="DATA" onkeyup="" onchange="verificaDados();"></input>
              </div>
            </div>            
            
            <br>
            
        </div>
      </div>

      <div class="row">

        <table width="100%" border="0" cellpadding="10">
        <tr>
        <td width="45%">  
        
                 <!-- Exibir matérias aprovadas na div abaixo: 
                 Lógica: no início desta página é carregado o script adj.js, que contém a função "exibe_materias_aprovadas()", que é carregada no onload do body desta página. 

                  Lá na função, acontece o seguinte: 
                  document.getElementById('div_materias_aprovadas').innerHTML = ajax.responseText;
                  url = "adt/funcoes/exibe_materias_aprovadas.php";

                  Ou seja, pelo ID da div, é chamado o programa: exibe_materias_aprovadas.php. 

                  -->     

                  <?php 

$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{            
  echo("<div>
      <label>MATÉRIAS APROVADAS</label>&nbsp;&nbsp;&nbsp;

      <a href='#' onclick='seleciona_todas_materias()' style='text-decoration: none' title='Selecionar Todas'>
      <i class='glyphicon glyphicon-arrow-right'></i><i class='glyphicon glyphicon-arrow-right'></i></a>

      <!-- <a href='#' onclick='seleciona_todas_materias()' style='text-decoration: none' title='Selecionar Todas'>
      <img src='/sigapce/img/ff.png' width='20'></a> --> 

      <select multiple id='sel_mult_materias' class='form-control' size=5 style='height: 100%;'>
    ");
  do
    {

      $id_adt_materia = $linha['id_adt_materia'];
      $cd_protocolo_processo = $linha['cd_protocolo_processo'];
      $nm_processo_status = $linha['nm_processo_status'];
      $nm_adt_materia_tipo = $linha['nm_adt_materia_tipo'];
      $nm_adt_materia_status = $linha['nm_adt_materia_status'];
      $dt_adt_materia_andamento = $linha['dt_adt_materia_andamento'];
      $nm_posto_graduacao = $linha['nm_posto_graduacao'];
      $nm_login = $linha['nm_login'];
      $gerenciado_por = $nm_posto_graduacao . " " . $nm_login;
      $dt_processo_andamento = $linha['dt_processo_andamento'];
        
      
      echo("<option value='". $id_adt_materia . "' >" . "$cd_protocolo_processo - $nm_adt_materia_tipo - $nm_processo_status
                EM ".date('d/m/Y H:i', strtotime($dt_processo_andamento))."&nbsp;" . "</option>");


    }while($linha = mysqli_fetch_assoc($dados));
    mysqli_free_result($dados);
    //echo("</table>");

    //echo("<button class='btn btn-large btn-block btn-primary' type='button' onclick='criar_nota_materias_sisprot()'>GERAR NOTA PARA BOLETIM</button>");

  //  echo("</select></div>");

    echo("</select></div>");

    echo '</div>'; 
    echo '</td>'; 


    echo '<td width="10%" align="center" valign="middle">'; 

    //DIV DOS CONTROLES adiciona ou remove matéria
    echo("<div'>
        <br>
        <center><button type='button' class='btn btn-default' id='btnAddMateria' onclick='addMateria()'><i class='glyphicon glyphicon-arrow-right'></i></button></center>

        <br>

        <center><button type='button' class='btn btn-default' id='btnRemoveMateria' onclick='removeMateria()'><i class='glyphicon glyphicon-arrow-left'></i></button></center>
        

      </div>");

    echo '</td><td width="45%">'; 

    echo("<div'>
          <label>MATÉRIAS SELECIONADAS PARA PUBLICAÇÃO</label>
          <select multiple id='sel_mult_materias_sel' class='form-control' size=5 style='height: 100%;'>
        ");
       
      
    echo("</select></div>");

    
    
}
else
{
  echo("
      <p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não Há Matérias Aprovadas Pendentes de Publicação.</p>
    ");
}

echo '</td></tr>'; 
    echo '<table>'; 

//DIV BOTAO VOLTAR
echo("

    
      <div class='col-md-6' id='div_btn_voltar'>
        <br>
        <button type='button' id='btn_voltar' onclick='voltar_adt()' class='btn btn-warning btn-sm btn-block'>
            <i class='glyphicon glyphicon-arrow-left'></i>&nbsp;&nbsp;VOLTAR À TELA ANTERIOR
        </button>
        <br>
      </div>

    
  ");


//DIV BOTAO SALVAR ADT
echo("

    
      <div class='col-md-6' id='div_btn_salva_adt' hidden>
        <br>
        <button type='button' id='btn_salva_adt' onclick='grava_adt()' class='btn btn-success btn-sm btn-block'>
            <i class='glyphicon glyphicon-floppy-disk'></i>&nbsp;&nbsp;SALVAR E PUBLICAR ADITAMENTO
        </button>
        <br>
      </div>

         
  ");


//FECHA DIV LINHA 1
  echo("
    </div>
  ");

   ?>

<br><br><br>


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->
</form>
</body>
</html>

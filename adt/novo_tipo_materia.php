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

include ("../funcoes/conexao.php");

//se estou editando um registro existente
if ((int)$id_tipo_materia <> 0) {
  $edita_tipo_materia = true; 
  $query = "SELECT * FROM adt_materia_tipo WHERE id_adt_materia_tipo = $id_tipo_materia";

  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  $row_array = mysqli_fetch_row($result); 

  $tipo = $row_array[1]; 
  $pre_texto = $row_array[2];
  $pos_texto = $row_array[3];

  $_SESSION['id_tipo_materia'] = $id_tipo_materia; 

}

//se for um registro novo
else {
  $edita_tipo_materia = false; 
  $tipo = null; 
  $pre_texto = null;
  $pos_texto = null;
}

$_SESSION['edita_tipo_materia'] = $edita_tipo_materia; 

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

<body onload="verificaDadosTipoMateria()">

<FORM action="adt/grava_tipo_materia.php" method="post" enctype="multipart/form-data" id="my_form"> 

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="adt.php">ADT</a></li>
  <li class="active"><a href="adt.php?url=aditamento">GESTÃO DE ADITAMENTOS</a></li>
  <li class="active"><a href="adt.php?url=tipos_materia">TIPOS DE MATÉRIA</a></li>
</ol>


<div class="panel panel-default" >
	<div class="panel-heading">
		<h2 class="panel-title">
      <?php 
        if($edita_tipo_materia)
          echo "Editar Tipo de Matéria"; 

        else
          echo "Novo Tipo de Matéria";     
      ?>
      </h2> 
		<!-- UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?> --> 
	</div>
	<div id="div_corpo_gestao_adt" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
     
      <div class='row'>
        <div class='col-md-12'>
            
            <div class='row'>
              <div class='col-md-2' align="right">
                <labeL>TIPO / TÍTULO</label>
              </div>  
              <div class='col-md-10'>
                <input type="text" class="form-control input-sm" id="tipo_materia" name="tipo_materia" placeholder="Digite a descrição do Tipo de Matéria" onkeyup="" onchange="verificaDadosTipoMateria()" value="<?php echo $tipo; ?>"></input>

                <!-- campo oculto para transportar (post) posteriormente as matérias para o programa grava_adt.php -->  
                <!-- <input type="hidden" name="input_materias" id="input_materias" readonly></input> -->

              </div>
            </div>

            <p>

            <div class='row'>
              <div class='col-md-2' align="right">
                <labeL>TEXTO DE ABERTURA</label>
              </div>  
              <div class='col-md-10'>
                <textarea name='pre_texto' rows='9' cols = '72'>
                  <?php echo $pre_texto; ?>
                </textarea>
              </div>
            </div>

            <p>

            <div class='row'>
              <div class='col-md-2' align="right">
                <labeL>TEXTO DE FECHAMENTO</label>
              </div>  
              <div class='col-md-10'>
                <textarea name='pos_texto' rows='9' cols = '72'>
                  <?php echo $pos_texto; ?>
                </textarea>
              </div>
            </div>            
            
            <br>
            
        </div>
      </div>

      <div class="row">

      <?php

      //ajustando o destino do botão 'voltar'
      if($edita_tipo_materia)
        $link = 'location.href="adt.php?url=exibe_tipo_materia&tipo_materia=' . $id_tipo_materia . '"';
      
      else
        $link = 'location.href="adt.php?url=tipos_materia"';     

//DIV BOTAO VOLTAR
echo("

    
      <div class='col-md-6' id='div_btn_voltar'>
        <br>
        <button type='button' id='btn_voltar' onclick='$link' class='btn btn-warning btn-sm btn-block'>
            <i class='glyphicon glyphicon-arrow-left'></i>&nbsp;&nbsp;VOLTAR À TELA ANTERIOR
        </button>
        <br>
        
      </div>

    
  ");


//DIV BOTAO SALVAR ADT
?>
   
      <div class='col-md-6' id='div_btn_salva_tipo' hidden>
        <br>
        <button type='button' id='btn_salva_tipo' onclick="document.getElementById('my_form').submit();" class='btn btn-success btn-sm btn-block'>
            <i class='glyphicon glyphicon-floppy-disk'></i>&nbsp;&nbsp;SALVAR
        </button>
        <br>
      </div>

         
  


    </div>




	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->
</form>
</body>
</html>

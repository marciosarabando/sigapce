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

<body> <!-- onload="exibe_materias_aprovadas()" -->

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="adt.php">ADT</a></li>
  <li class="active"><a href="adt.php?url=aditamento">GESTÃO DE ADITAMENTOS</a></li>
</ol>


<div class="panel panel-default">
	<div class="panel-heading">
    <table>
      <tr>
        <td> 
		      <h2 class="panel-title">GESTÃO DE ADITAMENTOS AO BAR</h2> 
		      <!-- UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?> --> 
        </td>
    
        <td width="50">
          &nbsp;
        </td>  
    
        <td align="center"> 
           <a href = "adt.php?url=novo" style="text-decoration: none;" title="Novo Aditamento"><img src="img/new_doc.png" width="30"><br>
            <font size="2">Novo Aditamento</a> 
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
      
  </div>
  
	<div id="div_corpo_gestao_adt" class="panel-body">
	    <!-- ... Corpo do Painel ... -->
  
<?php

$sql = "SELECT * FROM adt ORDER BY dt_adt DESC, nr_adt"; 
$result = mysqli_query($conn, $sql); 

#### EXIBIR LISTA DE ADITAMENTOS ####

if($result){
  if(mysqli_num_rows($result) > 0) {
    
    //cabeçalho
    echo "
    <fieldset>
              
      <table class='table' align='left' id='tb_adt'>
          <thead>
            <tr>
              <th>
                Aditamento
              </th>
              <th>
                Data
              </th>

              <th>
                Nr BAR
             </th>
            </tr>         
          </thead>
          <tbody>
        
    "; 

    /*<table width="100%">
      <tr>
        <td width="33%"><font size="2"><b>Data</b></td>
        <td width="33%"><font size="2"><b>Nr Adt</b></td>
        <td width="33%"><font size="2"><b>Nr BAR</b></td>
      </tr>
*/

    //Linhas
    for($linhas = 1; $linhas <= mysqli_num_rows($result); $linhas ++) {
      $row_array = mysqli_fetch_row($result);
  
      $id_adt = $row_array[0]; 
      $data_adt = date("d/m/Y", strtotime($row_array[2])); 
      $nr_adt = $row_array[3]; 
      $nr_bar = $row_array[4];

      echo "
      <tr class='active'>
            <td>
            <a href='adt.php?url=exibe_adt&id_adt=$id_adt' style='text-decoration: none'>
             $nr_adt 
            </a> 
            </td>

            <td>
              <a href='adt.php?url=exibe_adt&id_adt=$id_adt' style='text-decoration: none'>
               $data_adt
              </a>
            </td>

            <td>
              $nr_bar
            </td>
      </tr>             
    ";


/*
      <tr>
        <td width='33%'><font size='2'>$data_adt</td>
        <td width='33%'><font size='2'>$nr_adt</td>
        <td width='33%'><font size='2'>$nr_bar</td>
      </tr>
      */

    } //for linhas

    echo '</tbody></table>'; 

  } // if num rows > 0

else
  echo("
      <p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não há nenhum Aditamento na base de dados.</p>
    ");

} //if result


?>


	</div>
	<!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

</body>
</html>

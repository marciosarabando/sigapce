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
  <li class="active"><a href="adt.php?url=materia">GESTÃO DE MATÉRIAS</a></li>
  <li class="active"><a href="adt.php?url=tipos_materia">TIPOS DE MATÉRIA</a></li>
</ol>


<div class="panel panel-default">
  <div class="panel-heading">
    <table>
      <tr>
        <td> 
          <h2 class="panel-title">TIPOS DE MATÉRIA</h2> 
         <!-- UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?> --> 
        </td>
    
        <td width="50">
          &nbsp;
        </td>  

        <!-- 
    
        <td align="center"> 
           <a href = "adt.php?url=editar_tipo_materia" style="text-decoration: none;" title="Cadastrar novo tipo de matéria"><img src="img/new_doc.png" width="30"><br>
            <font size="2">Novo Tipo</a> 
        </td>
        
        --> 

        <td width="50">
          &nbsp;
        </td>  

        <td align="center"> 
           <a href = "adt.php?url=materia" style="text-decoration: none;" title="Voltar à tela anterior"><img src="img/back.png" width="30"><br>
            <font size="2">Voltar</a> 
        </td>
      </tr>
    </table>  
      
  </div>
  
  <div id="div_corpo_gestao_adt" class="panel-body">
      <!-- ... Corpo do Painel ... -->
  
<?php

//$query = "SELECT * FROM adt_materia_tipo ORDER BY nm_adt_materia_tipo";

$query = "
SELECT id_servico, ds_servico, ds_carteira
FROM servico as t1, carteira as t2
WHERE t1.id_carteira = t2.id_carteira 
AND t1.id_adt_materia_tipo <> 0
ORDER BY ds_carteira, ds_servico
";

$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$totalLinhas = mysqli_num_rows($result);

#### EXIBIR LISTA ####

if($result){
  if(mysqli_num_rows($result) > 0) {
    
    //cabeçalho
    echo "
    <fieldset>
              
      <table class='table' align='left' id='tb_tipos'>
          
        
    "; 

    //Linhas

    $carteira = null; 
    $carteira_ant = null; 

    for($linhas = 1; $linhas <= mysqli_num_rows($result); $linhas ++) {
      $row_array = mysqli_fetch_row($result);
 
      $id_tipo = $row_array[0];  
      $tipo = $row_array[1]; 
      //$pre_texto = $row_array[2];
      //$pos_texto = $row_array[3];

      $carteira = $row_array[2];
      
      if($carteira <> $carteira_ant){
        if($carteira_ant <> null)
          echo "<tr><td>&nbsp;</td></tr>"; 

        $carteira_ant = $carteira; 
        
        echo "
        <tr>
          <td>
            Carteira: $carteira
          </td>  
        </tr>
         "; 
      }


      echo "
      <tr class='active'>
            <td>
              <a href='adt.php?url=exibe_tipo_materia&tipo_materia=$id_tipo' style='text-decoration: none'>
                ". $tipo ."
              </a>    
            </td>
      </tr>             
      
    ";

  } //for linhas

  echo '</tbody></table>'; 

} // if num rows > 0

else
  echo("
      <p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não há nenhum Tipo de Matéria na base de dados.</p>
    ");

} //if result


?>


  </div>
  <!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

</body>
</html>

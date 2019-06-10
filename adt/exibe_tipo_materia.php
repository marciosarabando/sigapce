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
  <li class="active"><a href="adt.php?url=aditamento">GESTÃO DE ADITAMENTOS</a></li>
  <li class="active"><a href="adt.php?url=tipos_materia">TIPOS DE MATÉRIA</a></li>
</ol>


<div class="panel panel-default">
  <div class="panel-heading">
    <table>
      <tr>
        <td> 
          <h2 class="panel-title">TIPO DE MATÉRIA</h2> 
         <!--  UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?> -->
        </td>
    
        <td width="50">
          &nbsp;
        </td>  
    
        <!-- ÍCONES DE CONTROLE --> 

        <td align="center"> 
           <?php 
           echo "<a href='adt.php?url=editar_tipo_materia&tipo_materia=$id_tipo_materia' style='text-decoration: none;' title='Editar este tipo de matéria'><img src='img/edit2.png' width='30'><br>
            <font size='2'>Editar</a>"; 
            ?> 
        </td>

        <td width="50">
          &nbsp;
        </td>  

		<td align="center"> 
           <?php 
           echo "<a href='adt.php?url=excluir_tipo_materia&tipo_materia=$id_tipo_materia' style='text-decoration: none;' title='Excluir este tipo de matéria'><img src='img/trash2.png' width='30'><br>
            <font size='2'>Excluir</a>"; 
            ?> 
        </td>

        <td width="50">
          &nbsp;
        </td>  


        <td align="center"> 
           <a href = "adt.php?url=tipos_materia" style="text-decoration: none;" title="Voltar à tela anterior"><img src="img/back.png" width="30"><br>
            <font size="2">Voltar</a> 
        </td>
      </tr>
    </table>  
      
  </div>
  
  <div id="div_corpo_gestao_adt" class="panel-body">
      <!-- ... Corpo do Painel ... -->
  
<?php 

//$id_tipo_materia é definida via GET em adt.php linha 244  

$query = "SELECT * FROM adt_materia_tipo WHERE id_adt_materia_tipo = $id_tipo_materia";

$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$row_array = mysqli_fetch_row($result); 

$tipo = $row_array[1]; 
$pre_texto = $row_array[2];
$pos_texto = $row_array[3];

echo "
	<div>
		<b>TIPO / TÍTULO: </b>$tipo <br><br>
	</div>
		
	<div>
		<b>TEXTO DE ABERTURA</b><br>
		<textarea name='pos_texto' rows='9' cols = '72' readonly>
		$pre_texto
		</textarea>
		<br><br>
	</div>	

	<div>
		<b>TEXTO DE FECHAMENTO</b><br>
		<textarea name='pos_texto' rows='9' cols = '72' readonly>
		$pos_texto
		</textarea>
		<br><br>
	</div>	

"; 
?>

   </div>
  <!-- ... FIM da Div Corpo do Painel ... -->

</div>
<!-- ... FIM da Div Painel ... -->

</body>
</html>
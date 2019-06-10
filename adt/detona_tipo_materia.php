<?php
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

$id_tipo_materia = $_SESSION['id_tipo_materia']; 

$sql = "DELETE FROM adt_materia_tipo WHERE id_adt_materia_tipo = $id_tipo_materia"; 
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 


echo '<script>';
echo "//alert('Tipo excluído'); 
location.href='../adt.php?url=tipos_materia';"; 
echo '</script>';

?>




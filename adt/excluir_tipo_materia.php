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


//verificando se há matérias usando este tipo
$sql = "SELECT id_adt_materia from adt_materia where id_adt_materia_tipo = $id_tipo_materia";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
	echo '<script>
	alert("Ainda há Matérias nos Aditamentos utilizando este Tipo de matéria.");
	location.replace(document.referrer);
	</script>';
}

else {
	echo '<script>';
	echo "if(confirm('Deseja realmente EXCLUIR PERMANENTEMENTE este Tipo de Matéria?')) {";
	echo 'window.location.href = "adt/detona_tipo_materia.php";';
	echo '}';
	echo 'else {';
	echo '	location.replace(document.referrer);';
	echo '}';
	echo '</script>';
}

?>

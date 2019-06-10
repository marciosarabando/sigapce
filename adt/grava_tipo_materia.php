
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

//Pegando os valores passados pelo POST
$tipo = $_POST['tipo_materia'];
$pre_texto = ltrim($_POST['pre_texto']);
$pos_texto = ltrim($_POST['pos_texto']);

$edita_tipo_materia = $_SESSION['edita_tipo_materia']; 


if($edita_tipo_materia) { //se estou editando
	$id_tipo_materia = $_SESSION['id_tipo_materia']; 

	//Verificando se o tipo de matéria já existe em outro registro
	$sql = "SELECT id_adt_materia_tipo FROM adt_materia_tipo WHERE nm_adt_materia_tipo = '$tipo' AND id_adt_materia_tipo <> $id_tipo_materia";
	$result = mysqli_query($conn, $sql); 

	 if(mysqli_num_rows($result) > 0) { //se já existe um ADT a este BAR...
	 	echo "<script>
	 			alert('O Tipo < $tipo > já está cadastrado em outro registro.');
	 			location.replace(document.referrer); 
	 		</script>"; 
	 } 

	 else { //OK, posso gravar

	 	$sql = "UPDATE adt_materia_tipo SET nm_adt_materia_tipo='$tipo', pre_texto='$pre_texto', pos_texto='$pos_texto'
	 	 WHERE id_adt_materia_tipo = $id_tipo_materia";

		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
	
	}

	$link = 'location.href="../adt.php?url=exibe_tipo_materia&tipo_materia=' . $id_tipo_materia . '"';
      
} //if edita

else { //novo registro
//Verificando se o tipo de matéria já existe em outro registro
	$sql = "SELECT id_adt_materia_tipo FROM adt_materia_tipo WHERE nm_adt_materia_tipo = '$tipo'";
	$result = mysqli_query($conn, $sql); 

	 if(mysqli_num_rows($result) > 0) { //se já existe um ADT a este BAR...
	 	echo "<script>
	 			alert('O Tipo < $tipo > já está cadastrado.');
	 			location.replace(document.referrer); 
	 		</script>"; 
	 } 

	 else { //OK, posso gravar

	 	$sql = "INSERT INTO adt_materia_tipo SET nm_adt_materia_tipo='$tipo', pre_texto='$pre_texto', pos_texto='$pos_texto'";
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
	
	}

	$_SESSION['id_tipo_materia'] = null; 
	$link = 'location.href="../adt.php?url=tipos_materia"';     
   
} //else (novo registro)

//voltando para casa...
echo "
<script>
$link
</script>
 ";        
?>



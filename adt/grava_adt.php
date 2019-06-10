
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
$materias = $_POST['input_materias'];
$nr_adt = $_POST['txt_nr_adt'];
$nr_bar = $_POST['txt_nr_bar'];
$data_adt = $_POST['txt_data_adt'];

$a_data = explode('/',$data_adt);
$data_adt = $a_data[2] . '-' . $a_data[1] . '-' . $a_data[0];  


//Verificando se o Adt já existe (nr adt + nr BAR)
$sql = "SELECT id_adt FROM adt WHERE nr_adt='$nr_adt' AND nr_bar='$nr_bar'";
$result = mysqli_query($conn, $sql); 

 if(mysqli_num_rows($result) > 0) { //se já existe um ADT a este BAR...
 	echo "<script>
 			alert('O aditamento $nr_adt ao BAR $nr_bar já existe.');
 			location.replace(document.referrer); 
 		</script>"; 
 }

 else { 
	//Incluindo os dados do novo Adt na tabela 'adt'
	//por hora vou deixar o adt_status como 1 ('teste' na tabela em adt_statua) e o st_publicado como 0. 
	$sql = "INSERT INTO adt SET id_adt_status=1, dt_adt='$data_adt', nr_adt='$nr_adt', nr_bar='$nr_bar', st_publicado=0"; 

	$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	//pegando o ID do Adt que acabamos de incluir
	$sql="SELECT max(id_adt) FROM adt"; 
	$result = mysqli_query($conn, $sql) or die(mysqli_error($conn)); 
	$row_array = mysqli_fetch_row($result); 

	$id_adt = $row_array[0]; 

	//echo "<script>alert('id Adt: $id_adt');</script>"; 

	//Vinculando as matérias selecionadas ao Adt recém salvo
	//a variável $materias guarda a lista dos IDs das matérias selecionadas, seperados por vírgula 
	$a_materias = explode(',', $materias); 

	foreach ($a_materias as $id_materia) {
		$id_materia = (int)$id_materia; 

		//echo "<script>alert('id Matéria: $id_materia');</script>"; 

		$sql = "UPDATE adt_materia SET id_adt=$id_adt, st_publicada=1  WHERE id_adt_materia=$id_materia"; 
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	}

} //else if nr adt / bar já existe
	//echo "<script>alert('$materias');</script>";   

echo "
<script>
	location.href='adt.php?url=aditamento';
</script>
"; 
?>
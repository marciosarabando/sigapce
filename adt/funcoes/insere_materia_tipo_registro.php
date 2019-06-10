<?php
//INSERE MATERIA TIPO REGISTRO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");

if (!isset($_SESSION)) 
{
	session_start();
}

mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
$data_hora_atual = date('YmdHis');

$id_processo = $_GET['id_processo'];
$id_atividades = explode (',',$_GET['id_atividades']);
$id_processo_status = $_GET['id_processo_status'];
$id_login = $_SESSION['id_login_sfpc'];

$id_adt_materia = 0;

//MATERIA TIPO REGISTRO PF
$id_adt_materia_tipo = $_GET['id_adt_materia_tipo'];
$nr_cr = $_GET['nr_cr'];
$val_cr = $_GET['val_cr'];

//vamos descobrir o id do interessado pelo id do processo
$query = "SELECT id_interessado FROM processo WHERE id_processo = $id_processo"; 
$result = mysqli_query($conn,$query) or die(mysqli_error($conn)); 
if ($result)
{	
	$id_interessado = mysqli_fetch_row($result)[0];  
}

//INSERE A MATERIA
$query = "INSERT INTO adt_materia VALUES (null, $id_adt_materia_tipo, $id_processo, $id_processo_status, null, '$data_hora_atual', 0, null)";

echo $query; 
echo "<script>alert('$query');</script>"; 

if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
{
	//ID DA MATERIA
	$id_adt_materia = mysqli_insert_id($conn);
}
//echo($query);

//INSERE O ANDAMENTO DA MATÉRIA
//CRIADA
$query = "INSERT INTO adt_materia_andamento VALUES (null, $id_adt_materia, 1, $id_login, '$data_hora_atual', null)";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)));


//se não for cancelamento, INSERE AS ATIVIDADES DA MATÉRIA
if($id_adt_materia_tipo <> 12) {
	
	for($h = 0; $h < count($id_atividades); $h++)
	{
		$id_atividade = $id_atividades[$h];
		$query = "INSERT INTO adt_materia_cr_atividade VALUES ($id_adt_materia, $id_atividade)";
		(mysqli_query($conn,$query) or die(mysqli_error($conn)));
	//	echo($query);
	}
}

//insere ods dados do CR
if($id_adt_materia_tipo == 1 or $id_adt_materia_tipo == 12 or $id_adt_materia_tipo == 22) {//2a via / cancelamento / revalidação de CR
	$query = "INSERT INTO adt_cr VALUES (null, $id_interessado, '$nr_cr', '$val_cr', $id_adt_materia)";
	if (mysqli_query($conn,$query) or die(mysqli_error($conn)));
}

//echo('MATERIA INSERIDA!');


?>
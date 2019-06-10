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
$id_login = $_SESSION['id_login_sfpc'];


$id_processo = (int)$_GET['id_processo'];
$id_processo_status = $_GET['id_processo_status'];
$id_adt_materia_tipo = (int)$_GET['id_adt_materia_tipo'];
$rm_origem = (int)$_GET['rm_origem'];
$rm_destino = (int)$_GET['rm_destino'];

$id_adt_materia = 0;

//vamos descobrir o id do interessado pelo id do processo
$query = "SELECT id_interessado FROM processo WHERE id_processo = $id_processo"; 
$result = mysqli_query($conn,$query) or die(mysqli_error($conn)); 
if ($result)
{	
	$id_interessado = mysqli_fetch_row($result)[0];  
}

//INSERE A MATERIA
$query = "INSERT INTO adt_materia VALUES (null, $id_adt_materia_tipo, $id_processo, $id_processo_status, null, '$data_hora_atual', 0, null)";

//echo $query; 
//echo "<script>alert('$query');</script>"; 

if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
{
	//ID DA MATERIA
	$id_adt_materia = mysqli_insert_id($conn);
}
//echo($query);


//Insere a RM de origem e destino
$query = "INSERT INTO adt_materia_vinc_rm VALUES ($id_adt_materia,$rm_origem,$rm_destino)";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)));


//INSERE O ANDAMENTO DA MATÉRIA
//CRIADA
$query = "INSERT INTO adt_materia_andamento VALUES (null, $id_adt_materia, 1, $id_login, '$data_hora_atual', null)";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)));

?>
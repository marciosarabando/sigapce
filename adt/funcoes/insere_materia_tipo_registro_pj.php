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

$id_atividades = explode (',',$_GET['id_atividades']);
$id_processo_status = $_GET['id_processo_status'];
$id_login = $_SESSION['id_login_sfpc'];

$id_processo = $_GET['id_processo'];
$id_processo_status = $_GET['id_processo_status'];
$id_atvs_pces_qtd = explode (';',$_GET['id_atvs_pces']);
$id_adt_materia_tipo = $_GET['id_adt_materia_tipo'];
$nr_cr = $_GET['nr_cr'];
$val_cr = $_GET['val_cr'];

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

if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
{
	//ID DA MATERIA
	$id_adt_materia = mysqli_insert_id($conn);
}
//echo($query);

//@1,47,22,100;
//@2,48,29,300

/*
//se não for cancelamento, INSERE AS ATIVIDADES DA MATÉRIA
if($id_adt_materia_tipo <> 121) {

	for($h = 0; $h < count($id_atvs_pces_qtd); $h++)
	{
		$linha_id_atvs_pce_qtd = explode(',', $id_atvs_pces_qtd[$h]);
		
		$id_adt_atividade = $linha_id_atvs_pce_qtd[1];
		$id_adt_pce = $linha_id_atvs_pce_qtd[2];
		$qtd_max = $linha_id_atvs_pce_qtd[3];

		$query = "INSERT INTO adt_materia_atv_pce_pj VALUES ($id_adt_materia, $id_adt_atividade, $id_adt_pce, $qtd_max)";
		(mysqli_query($conn,$query) or die(mysqli_error($conn)));
	//	echo($query);
	}
}

*/

//insere ods dados do CR
if($id_adt_materia_tipo == 83 or $id_adt_materia_tipo == 120 or $id_adt_materia_tipo == 121) { //revalidação pede os dados do CR
	$query = "INSERT INTO adt_cr VALUES (null, $id_interessado, '$nr_cr', '$val_cr', $id_adt_materia)";
	if (mysqli_query($conn,$query) or die(mysqli_error($conn)));
}

//INSERE O ANDAMENTO DA MATÉRIA
//CRIADA
$query = "INSERT INTO adt_materia_andamento VALUES (null, $id_adt_materia, 1, $id_login, '$data_hora_atual', null)";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)));

//echo('MATERIA INSERIDA!');


?>
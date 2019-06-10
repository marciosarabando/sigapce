<?php
//INSERE MATERIA TIPO TRANSFERENCIA ARMA
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

//PEGA OS DADOS ENVIADOS
$id_processo = $_GET['id_processo'];
$id_interessado_cedente = $_GET['id_interessado_cedente'];
$txt_nr_arma = mb_strtoupper($_GET['txt_nr_arma'],'UTF-8');
$txt_nr_sigma = mb_strtoupper($_GET['txt_nr_sigma'],'UTF-8');
$id_origem = $_GET['id_origem'];
$id_marca = $_GET['id_marca'];
$id_modelo = $_GET['id_modelo'];
$id_acabamento = $_GET['id_acabamento'];
$id_acervo = $_GET['id_acervo'];
$id_processo_status = $_GET['id_processo_status'];

//TIPO DA MATERIA 4 = MATERIA TIPO TRANSFERÊNCIA DE ARMAMENTO
$id_adt_materia = 0;
$id_adt_materia_tipo = $_GET['id_adt_materia_tipo'];

//INSERE A MATERIA
$query = "INSERT INTO adt_materia VALUES (null, $id_adt_materia_tipo, $id_processo, $id_processo_status, null, '$data_hora_atual', 0, null)";
//echo($query);
if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
{
	//ID DA MATERIA
	$id_adt_materia = mysqli_insert_id($conn);
}
//echo("MATERIA CRIADA: $id_adt_materia");


//INSERE O ANDAMENTO DA MATÉRIA
//CRIADA
$query = "INSERT INTO adt_materia_andamento VALUES (null, $id_adt_materia, 1, $id_login, '$data_hora_atual', null)";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)));

//echo("MATERIA ANDAMENTO CRIADA: $id_adt_materia");


//VERIFICA SE A ARMA JÁ EXISTE NO SISTEMA
$id_adt_arma = 0;
$query = "SELECT id_adt_arma FROM adt_arma WHERE nr_arma = '$txt_nr_arma'";
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do{
		$id_adt_arma = $linha['id_adt_arma'];
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
}
//echo("ID ADT ARMA:" + $id_adt_arma);

//SE A ARMA NÃO EXISTE, ENTÃO INSERE A ARMA
if($id_adt_arma == 0)
{
	$query = "INSERT INTO adt_arma VALUES (null, $id_modelo, $id_origem, $id_acabamento, '$txt_nr_arma', '$txt_nr_sigma')";
//    echo($query);
	if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
	{
		//ID DA MATERIA
		$id_adt_arma = mysqli_insert_id($conn);
	}
}

//INSERE A MATERIA ARMA
$query = "INSERT INTO adt_materia_arma VALUES ($id_adt_materia, $id_adt_arma, $id_acervo, null, null, $id_interessado_cedente)";
(mysqli_query($conn,$query) or die(mysqli_error($conn)));



// //echo('MATERIA INSERIDA!');


?>
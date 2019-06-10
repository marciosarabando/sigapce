<?php
//INSERE MATERIA TIPO AQS ARMA
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

$id_processo = $_GET['id_processo'];
$id_fornecedor = $_GET['id_fornecedor'];
$txt_nr_nota_fiscal = $_GET['txt_nr_nota_fiscal'];
$txt_nr_arma = mb_strtoupper($_GET['txt_nr_arma'],'UTF-8');
$id_origem = $_GET['id_origem'];
$id_marca = $_GET['id_marca'];
$id_modelo = $_GET['id_modelo'];
$id_acabamento = $_GET['id_acabamento'];
$id_acervo = $_GET['id_acervo'];
$id_processo_status = $_GET['id_processo_status'];


if (isset($_GET['id_especie']))
{
    $id_especie = $_GET['id_especie'];
}
else
{
    $id_especie = null;
}

if (isset($_GET['txt_nr_sigma']))
{
    $txt_nr_sigma = $_GET['txt_nr_sigma'];
}
else
{
    $txt_nr_sigma = null;
}

if (isset($_GET['id_calibre']))
{
    $id_calibre = $_GET['id_calibre'];
}
else
{
    $id_calibre = null;
}

$id_adt_materia = 0;
$id_adt_materia_tipo = $_GET['id_adt_materia_tipo'];

//INSERE A MATERIA
$query = "INSERT INTO adt_materia VALUES (null, $id_adt_materia_tipo, $id_processo, $id_processo_status, null, '$data_hora_atual', 0, null)";

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
$id_adt_arma = 0; //verificar

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


//SE A ARMA NÃO EXISTE, ENTÃO INSERE A ARMA
if($id_adt_arma == 0)
	//echo "<script>alert('incluindo em adt-arma');</script>"; 
{
	$query = "INSERT INTO adt_arma VALUES (null, $id_modelo, $id_origem, $id_acabamento, '$txt_nr_arma', '$txt_nr_sigma')";

//echo $query; 

	if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
	{
		//ID DA MATERIA
		$id_adt_arma = mysqli_insert_id($conn);
	}
}

//INSERE A MATERIA ARMA
$query = "INSERT INTO adt_materia_arma VALUES ($id_adt_materia, $id_adt_arma, $id_acervo, '$txt_nr_nota_fiscal', $id_fornecedor, null)";
(mysqli_query($conn,$query) or die(mysqli_error($conn)));



// //echo('MATERIA INSERIDA!');


?>
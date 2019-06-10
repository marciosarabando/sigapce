<?php
//CRIA NOTA PARA BOLETIM
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
$data_hora_atual = date('YmdHis');
if (!isset($_SESSION)) 
{
	session_start();
}

$id_login = $_SESSION['id_login_sfpc'];
$id_materias = $_GET['id_materias'];
$id_nota = 0;

//INSERE A NOTA
$query = "INSERT INTO adt_nota VALUES (null, null, $id_login, '$data_hora_atual')";
if (mysqli_query($conn,$query) or die(mysqli_error($conn)))
{
	//ID DA NOTA
	$id_nota = mysqli_insert_id($conn);
}


$query = "SELECT id_adt_materia, id_adt_materia_tipo FROM adt_materia WHERE id_adt_materia in ($id_materias)";
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	do
	  {
	  	//PARA CADA LINHA, INSERE A MATÉRIA NO SUBITEM CORRESPONDENTE DO ADITAMENTO CONFORME O TIPO DA MATERIA
	  	$id_adt_materia = $linha['id_adt_materia'];
	  	$id_adt_materia_tipo = $linha['id_adt_materia_tipo'];

	  	$id_adt_subitem = 0;
	  	if($id_adt_materia_tipo == 1)
	  	{
	  		//CERTIFICADO DE REGISTRO
	  		$id_adt_subitem = 1;
	  	}
	  	else if($id_adt_materia_tipo == 2)
	  	{
	  		//ARMAMENTO REGISTRO
	  		$id_adt_subitem = 2;
	  	}
	  	else if ($id_adt_materia_tipo == 3)
	  	{
	  		//ARMAMENTO TRANSFERÊNCIA
	  		$id_adt_subitem = 3;	
	  	}

	  	$query = "INSERT INTO adt_nota_materias VALUES ($id_nota, $id_adt_materia, $id_adt_subitem)";
	  	(mysqli_query($conn,$query) or die(mysqli_error($conn)));

	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}


	echo("
			<p class='text-success'><i class='glyphicon glyphicon-info-sign'></i> NOTA PARA BOLETIM CRIADA COM SUCESSO!</p>
		");

?>
<?php
//INSERE MARCA ARMAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$txt_marca_arma = mb_strtoupper($_GET['txt_marca_arma'],'UTF-8');

//VERIFICA SE A MARCA DA ARMA INSERIDA JA EXISTE NO SISTEMA
$query = "SELECT id_adt_arma_marca, nm_adt_arma_marca FROM adt_arma_marca WHERE nm_adt_arma_marca = '$txt_marca_arma'";
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
$existe = 0;
if($totalLinhas > 0)
{
	do
	  {
	  		$existe = 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

if($existe == 0)
{
	$query = "INSERT INTO adt_arma_marca VALUES (null,'$txt_marca_arma')";
	(mysqli_query($conn,$query) or die(mysqli_error($conn)));

	//MARCA INSERIDA
	echo("<div class='alert alert-success'> <b><font color='green'> <i class='glyphicon glyphicon-ok'></i> Marca incluída com sucesso.</font></b> </div>");
}
else
{
	//MARCA NAO INSERIDA! JA EXISTE
	echo("<div class='alert alert-danger'> <b><font color='red'> <i class='glyphicon glyphicon-exclamation-sign'></i> Marca não inserida. </b> A marca informada já existe no sistema.</font> </div>");
}


?>
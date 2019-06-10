<?php
//INSERE MODELO ARMAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$nm_modelo = mb_strtoupper($_GET['nm_modelo'],'UTF-8');
$id_marca = $_GET['id_marca'];
$id_especie = $_GET['id_especie'];
$id_calibre = $_GET['id_calibre'];
$id_funcionamento =  $_GET['id_funcionamento'];
$id_alma = $_GET['id_alma'];
$qtd_cano = $_GET['qtd_cano'];
$qtd_raia = $_GET['qtd_raia'];
$sentido_raia = $_GET['sentido_raia'];
$comp_cano = $_GET['comp_cano'];
$cap_carregador = $_GET['cap_carregador'];


//VERIFICA SE O MODELO DA ARMA INSERIDA JA EXISTE NO SISTEMA
$query = "SELECT id_adt_arma_modelo FROM adt_arma_modelo WHERE nm_arma_modelo = '$nm_modelo'";
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
	$query = "INSERT INTO adt_arma_modelo VALUES (null, $id_marca, $id_especie, $id_calibre, $id_funcionamento, $id_alma, '$nm_modelo', $qtd_cano, $qtd_raia, '$sentido_raia', '$comp_cano', $cap_carregador)";
	(mysqli_query($conn,$query) or die(mysqli_error($conn)));
	//echo($query);
	//MARCA INSERIDA
	echo("<div class='alert alert-success'> <b><font color='green'> <i class='glyphicon glyphicon-ok'></i> Modelo incluído com sucesso.</font></b> </div>");
}
else
{
	//MARCA NAO INSERIDA! JA EXISTE
	echo("<div class='alert alert-danger'> <b><font color='red'> <i class='glyphicon glyphicon-exclamation-sign'></i> Modelo não inserido. </b> O modelo informado já existe no sistema.</font> </div>");
}


?>
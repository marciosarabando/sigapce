<?php
//INSERE MARCA ARMAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$txt_nome_fornecedor = mb_strtoupper($_GET['txt_nome_fornecedor'],'UTF-8');
$txt_cnpj_fornecedor = mb_strtoupper($_GET['txt_cnpj_fornecedor'],'UTF-8');

//VERIFICA SE A MARCA DA ARMA INSERIDA JA EXISTE NO SISTEMA
$query = "SELECT id_adt_arma_fornecedor, nm_adt_arma_fornecedor, cnpj FROM adt_arma_fornecedor WHERE nm_adt_arma_fornecedor = '$txt_nome_fornecedor' OR cnpj = $txt_cnpj_fornecedor";
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
	$query = "INSERT INTO adt_arma_fornecedor VALUES (null,'$txt_nome_fornecedor','$txt_cnpj_fornecedor')";
	(mysqli_query($conn,$query) or die(mysqli_error($conn)));

	//FORNECEDOR INSERIDO
	echo("<div class='alert alert-success'> <b><font color='green'> <i class='glyphicon glyphicon-ok'></i> Fornecedor Incluído.</font></b> </div>");
}
else
{
	//FORNECEDOR NÃO INSERIDO! JA EXISTE
	echo("<div class='alert alert-danger'> <b><font color='red'> <i class='glyphicon glyphicon-exclamation-sign'></i> Fornecedor não inserido. </b> O fornecedor informado já existe no sistema.</font> </div>");
}


?>
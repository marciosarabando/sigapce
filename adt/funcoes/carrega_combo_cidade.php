<?php
//CARREGA COMBO CIDADE
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$uf_estado = $_GET['uf_estado'];

$query = "SELECT id_cidade, nm_cidade FROM cidade WHERE uf_cidade = '$uf_estado'";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	echo("<select id='cmb_cidade' name='cmb_cidade' class='form-control input-sm' onchange=''>");
	echo("<option value='0'>SELECIONE...</option>");
	do
	  {
	  		echo("<option value='". $linha['id_cidade'] . "'>" . $linha['nm_cidade'] . "</option>");
	   }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	echo("</select>");
}

?>
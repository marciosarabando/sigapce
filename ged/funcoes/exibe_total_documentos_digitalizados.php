<?php
//EXIBE TOTAL DOCUMENTOS DIGITALIZADOS
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "SELECT count(id_documento) as qtd_docs FROM documento";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	do
	  {
	  	$qtd_docs = $linha['qtd_docs'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

echo("
		<div class='panel panel-default'>
		  <div class='panel-body'>
		    <h4><p class='text-info'><span><i class='glyphicon glyphicon-file'></i></span> $qtd_docs documentos importados</h4>
		  </div>
		</div>
	");

?>
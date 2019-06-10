<?php
//EXIBE TOTAL DE PROTOCOLOS NO DIA
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$unidade = $_GET['id_unidade'];
$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];

$query = "SELECT carteira.ds_carteira, count(id_processo) as qtd_protocolo
			FROM processo 
			INNER JOIN carteira on processo.id_carteira = carteira.id_carteira
			WHERE dt_abertura_processo > '$data_inicio 00:00:01' AND dt_abertura_processo < '$data_fim 23:59:59'
			AND processo.id_unidade = $unidade
			group by processo.id_carteira";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
$total_protocolo_dia = 0;
if($totalLinhas > 0)
{
	echo("

			<table class='table table-condensed'>				
		");
	do
	{
	  	$ds_carteira = $linha['ds_carteira'];
		$qtd_protocolo = $linha['qtd_protocolo'];
		$total_protocolo_dia = $total_protocolo_dia + $qtd_protocolo;
				echo("<tr>");
					echo("<td>");
						echo($ds_carteira);
					echo("</td>");


					echo("<td>");
							echo($qtd_protocolo);
					echo("</td>");
				echo("</tr>");

	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);

	echo("
			</table>				
		");

	echo("<font size='5'>Total: " . $total_protocolo_dia . "</font>");

}

?>
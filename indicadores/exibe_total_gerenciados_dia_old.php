<?php
//EXIBE TOTAL GERENCIADOS NO DIA
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$unidade = $_GET['id_unidade'];

$query = "SELECT 
	        processo_status.nm_processo_status,
	        count(processo_status.id_processo_status) as qtd_gerenciado

			FROM processo_andamento
			INNER JOIN processo on processo.id_processo = processo_andamento.id_processo
			INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
			WHERE dt_processo_andamento > '$hoje 00:00:01' AND dt_processo_andamento < '$hoje 23:59:59'
			AND processo.id_unidade = $unidade
			AND processo_andamento.id_processo_andamento IN (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
			AND processo_andamento.id_processo_status <> 1
			GROUP BY processo_status.id_processo_status
		";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado

$total_gerenciado = 0;
if($totalLinhas > 0)
{
	echo("

			<table class='table table-condensed'>				
		");
	do
	{
	  	$nm_processo_status = $linha['nm_processo_status'];
		$qtd_gerenciado = $linha['qtd_gerenciado'];
		$total_gerenciado = $total_gerenciado + $qtd_gerenciado;
				echo("<tr>");
					echo("<td>");
						echo($nm_processo_status);
					echo("</td>");


					echo("<td>");
							echo($qtd_gerenciado);
					echo("</td>");
				echo("</tr>");

	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);

	echo("
			</table>				
		");

	echo("<font size='5'>Total: " . $total_gerenciado . "</font>");

}

?>
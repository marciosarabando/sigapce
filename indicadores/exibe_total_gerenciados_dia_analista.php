<?php
//EXIBE TOTAL GERENCIADOS NO DIA POR ANALISTA
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');
$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];

$unidade = $_GET['id_unidade'];

$query = "select l.nm_guerra , ps.nm_processo_status ,count(1) contador from processo_andamento pa, login l, processo_status ps where pa.id_login = l.id_login and pa.id_processo_status = ps.id_processo_status and id_unidade = $unidade and  pa.id_processo_status in (3,6,7,8) and dt_processo_andamento between '$data_inicio 00:00:01' and '$data_fim 23:59:59' group by pa.id_login, ps.nm_processo_status  order by date(dt_processo_andamento), nm_guerra asc";

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
	  		  	$nm_guerra = $linha['nm_guerra'];
				$nm_processo_status = $linha['nm_processo_status'];
				$contador = $linha['contador'];
				echo("<tr>");
					echo("<td>");
						echo($nm_guerra);
					echo("</td>");


					echo("<td>");
							echo($nm_processo_status);
					echo("</td>");
					
					
					
					echo("<td>");
							echo($contador);
					echo("</td>");
				echo("</tr>");

	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);

	echo("
			</table>				
		");

	

}

?>
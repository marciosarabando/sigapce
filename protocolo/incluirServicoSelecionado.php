<?php

$id_servico = $_GET['id_servico'];
//echo("$id_servico");


include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
					id_servico as id_servico,
					ds_servico as ds_servico
		 FROM servico WHERE id_servico in ($id_servico)";
// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	echo("<table class='table'>
				<thead>
				<tr>

					<th>
						SERVIÇO SOLICITADO NO PROCESSO
					</th>
					<th>
						
					</th>
				</tr>
				</thead>
				<tbody>
		");		
	do{
		echo("
				<tr class='success'>

					<td>
						" . $linha['ds_servico'] . "  
					</td>
					<td>
						<a href='javascript:excluirItemServico(" . $linha['id_servico'] . ");'><i class='glyphicon glyphicon-remove-sign'></i></a>
					</td>
				</tr>

		");			
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
	echo("</tbody></table>");
}

?>
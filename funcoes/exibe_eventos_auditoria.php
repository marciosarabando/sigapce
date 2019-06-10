<?php
include ("verificaAtenticacao.php");
include ("conexao.php");
//include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_tipo_evento = $_GET['id_tipo_evento'];


$query = "SELECT id_evento, dt_evento, ip_conexao, obs_evento FROM evento WHERE id_tipo_evento = $id_tipo_evento ORDER BY id_evento desc LIMIT 500";


$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_eventos' name='tb_eventos' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>ID</center>
							</th>
							<th>
								<center>DATA</center>
							</th>
							
							<th>
								<center>OBS</center>
							</th>

							<th>
								<center>IP</center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$id_evento = $linha['id_evento'];
		$dt_evento = $linha['dt_evento'];
		$ip_conexao = $linha['ip_conexao'];
		$obs_evento = $linha['obs_evento'];
		
		echo("

				<tr class='active'>
					
					
					
					<td>
						<center>".$id_evento."</center>
					</td>

					<td>
						<center>".date('d/m/Y H:i', strtotime($dt_evento))."</center>
					</td>

					<td>
						<center>".$obs_evento."</center>
					</td>

					<td>
						<center>".$ip_conexao."</center>
					</td>

					
				</tr>");
	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("</table>");
	    	echo("</div>");
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou resultado.</p>
		");
}

?>
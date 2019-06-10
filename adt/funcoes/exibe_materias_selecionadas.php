<?php
//EXIBE MATERIAS APROVADAS
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$filtro = $_GET['filtro'];

/* LÓGICA: 
ler os registros da tabela adt_materia e inserí-los na tabela 'adt_materia_pub'
remover os registros transferidos da tabela adt_materia
*/
if($filtro == '*') {
	$sql = "select * from adt_materia"; 
	$result = mysqli_query($conn, $sql); 
	
	for($linhas = 1; $linhas <= mysqli_num_rows($result); $linhas ++){
		$row_array = mysqli_fetch_row($result); 
		$id_adt_materia = $row_array[0]; 
		$id_adt_materia_tipo = $row_array[1];
		$id_processo = $row_array[2];
		$id_processo_status = $row_array[3];
		$txt_adt_materia = $row_array[4];
		$dt_criacao = $row_array[5];
		$st_publicada = $row_array[6];

		$sql2 = "INSERT INTO adt_materia_pub VALUES($id_adt_materia, $id_adt_materia_tipo, $id_processo, $id_processo_status, '$txt_adt_materia',
		'$dt_criacao', $st_publicada, null)"; 
		$result2 = mysqli_query($conn, $sql2) or die (mysqli_error($conn)); 

	//	$sql2 = "DELETE FROM adt_materia"; 
	//	$result2 = mysqli_query($conn, $sql2) or die (mysqli_error($conn)); 

	} //for linhas

	echo "<script>exibe_materias_aprovadas();</script>"; 

} //if filtro = *

$query = "SELECT 
				adt_materia_pub.id_adt_materia,
				processo.cd_protocolo_processo,
		        processo_status.nm_processo_status,
		        adt_materia_tipo.nm_adt_materia_tipo,
		        adt_materia_status.nm_adt_materia_status,
		        adt_materia_andamento.dt_adt_materia_andamento,
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_login,
		        processo_andamento.dt_processo_andamento

		FROM adt_materia_pub
		INNER JOIN adt_materia_tipo on adt_materia_tipo.id_adt_materia_tipo = adt_materia_pub.id_adt_materia_tipo
		INNER JOIN adt_materia_andamento on adt_materia_andamento.id_adt_materia = adt_materia_pub.id_adt_materia
		INNER JOIN adt_materia_status on adt_materia_status.id_adt_materia_status = adt_materia_andamento.id_adt_materia_status
		INNER JOIN login on login.id_login = adt_materia_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN processo on processo.id_processo = adt_materia_pub.id_processo
		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		INNER JOIN processo_status on processo_status.id_processo_status = adt_materia_pub.id_processo_status

		WHERE 	adt_materia_pub.st_publicada = 0 
				AND adt_materia_status.id_adt_materia_status = 2
				AND adt_materia_andamento.id_adt_materia_andamento IN 
				(
					SELECT max(adt_materia_andamento.id_adt_materia_andamento) FROM adt_materia_andamento WHERE adt_materia_andamento.id_adt_materia = adt_materia_pub.id_adt_materia
				)
				AND processo_andamento.id_processo_andamento IN
				(
					SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE
					processo.id_processo = processo_andamento.id_processo AND processo_andamento.id_processo_status in (6,7,13)
				)
		";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	echo("
					
			  				<table>
			  			
		");

	//cabeçalho das matérias aprovadas a serem selecionadas

	echo "<tr>
			<td colspan='2'>
			Matérias Selecionadas para o Aditamento<br><br>
			</td>
			<td><!-- <a href='#' onclick='exibe_materias_selecionadas()' title='Selecionar Todas'><img src='/sigapce/img/ff.png' width='25'></a>
	  			</font> --> 
	  		</td>
		</tr>"; 
	do
	  {

	  	$id_adt_materia = $linha['id_adt_materia'];
		$cd_protocolo_processo = $linha['cd_protocolo_processo'];
		$nm_processo_status = $linha['nm_processo_status'];
		$nm_adt_materia_tipo = $linha['nm_adt_materia_tipo'];
		$nm_adt_materia_status = $linha['nm_adt_materia_status'];
		$dt_adt_materia_andamento = $linha['dt_adt_materia_andamento'];
		$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		$nm_login = $linha['nm_login'];
		$gerenciado_por = $nm_posto_graduacao . " " . $nm_login;
		$dt_processo_andamento = $linha['dt_processo_andamento'];
	  	
	  	
	  	//linha da matéria a ser selecionada
	  		  	echo("
	  			<tr>
	  				<td>
	  					<img src='img/materia.png' width='30'>
	  				</td>

	  				<td>
	  						  						
	  						<font size='2' color=\"green\"><b> $cd_protocolo_processo </b> - 
	  						<b>$nm_adt_materia_tipo</b> - 
	  						$nm_processo_status
	  						EM
	  						".date('d/m/Y H:i', strtotime($dt_processo_andamento))."
	  						&nbsp;</font>
	  				</td>

	  				<td>
	  				<!-- ############### COMEÇA AQUI, com o botão de seleção ################-->

	  					<!-- <font color=\"green\" size='5'><a href=\"javascript:alert('Materia Selecionada');\" title='Selecionar matéria'><i class='glyphicon glyphicon-circle-arrow-right'></a></i> --> 
	  					</font>
	  				</td>
	  			</tr>
	  		");

	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("
	  		</table>
	  		");

	  //echo("<button class='btn btn-large btn-block btn-primary' type='button' onclick='criar_nota_materias_sisprot()'>GERAR NOTA PARA BOLETIM</button>");

	  
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não Há Matérias Aprovadas Pendentes de Publicação.</p>
		");
}
	
?>

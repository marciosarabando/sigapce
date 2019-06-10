<?php
//EXIBE MATERIAS PENDENTES DE PUBLICAÇÃO

include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');


$query = "SELECT 
				adt_materia.id_adt_materia,
				processo.cd_protocolo_processo,
		        processo_status.nm_processo_status,
		        adt_materia_tipo.nm_adt_materia_tipo,
		        adt_materia_status.nm_adt_materia_status,
		        adt_materia_andamento.dt_adt_materia_andamento,
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_login

		FROM adt_materia
		INNER JOIN adt_materia_tipo on adt_materia_tipo.id_adt_materia_tipo = adt_materia.id_adt_materia_tipo
		INNER JOIN adt_materia_andamento on adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia
		INNER JOIN adt_materia_status on adt_materia_status.id_adt_materia_status = adt_materia_andamento.id_adt_materia_status
		INNER JOIN login on login.id_login = adt_materia_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN processo on processo.id_processo = adt_materia.id_processo
		INNER JOIN processo_status on processo_status.id_processo_status = adt_materia.id_processo_status 

		WHERE adt_materia.st_publicada = 0 AND adt_materia_status.id_adt_materia_status <> 2 AND adt_materia_status.id_adt_materia_status <> 3 
		AND adt_materia_andamento.id_adt_materia_andamento IN (SELECT max(adt_materia_andamento.id_adt_materia_andamento) FROM adt_materia_andamento WHERE adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia) ORDER BY id_adt_materia
		";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);



if($totalLinhas > 0)
{
	echo("
					<div class='table-responsive'>
			  				<table id='tb_revisao_materias' name='tb_revisao_materias' class='table table-condensed'>
			  					<thead>
									<tr>
										

										<th>
											<center>PROCESSO</center>
										</th>

										<th>
											<center>SITUAÇÃO</center>
										</th>


										<th>
											TIPO DA MATÉRIA
										</th>

										<th>
											STATUS
										</th>
										

										<th>
											<center>EM</center>
										</th>	
										
										<th>
											<center>POR</center>
										</th>

										<th>
											<center>AÇÃO</center>
										</th>

																
									</tr>
								</thead>
								<tbody>
		");

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
	  	
	  	
	  	echo("
	  			<tr>
	  				

	  				<td>
	  					
	  					<center><b><font color=\"green\"> $cd_protocolo_processo </font></b></center>
	  				</td>
	  			
	  				<td>
	  					<center>$nm_processo_status</center>
	  				</td>

	  				<td>
	  					$nm_adt_materia_tipo
	  				</td>

	  			
	  				<td>
	  					$nm_adt_materia_status
	  				</td>
	  			
	  			
	  				<td>
	  					<center>".date('d/m/Y H:i', strtotime($dt_adt_materia_andamento))."</center>
	  				</td>
	  				
	  			
	  				<td>
	  					<center>$gerenciado_por</center>
	  				</td>

	  				<td>
	  					

	  					<!-- Button trigger modal GERENCIAR MATÉRIA-->
						<center>
							<button type='button' id='btn_alterarEstadoLogin' onclick='exibirDetalhesMateria($id_adt_materia)' class='btn btn-warning btn-xs btn-block' data-toggle='modal' data-target='#myModalGerenciarMateria'>
								<i class='glyphicon glyphicon-cog'></i> GERENCIAR
							</button>
						</center>


	  				</td>

	  				
	  			</tr>
	  		");

	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("</tbody>
	  		</table>
	  		</div>");

	  //echo("<button class='btn btn-large btn-block btn-primary' type='button' onclick='criar_nota_materias_sisprot()'>GERAR NOTA PARA BOLETIM</button>");

	  
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não Há Matérias Pendentes de Revisão.</p>
		");
}
	
?>

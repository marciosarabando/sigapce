
<?php
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
include ("../sae/funcoes/formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_unidade = $_GET['id_unidade'];
$id_carteira = $_GET['id_carteira'];
$id_servico = $_GET['id_servico'];
$id_status = $_GET['id_status'];
$tp_interessado = $_GET['tp_interessado'];
$id_procurador = $_GET['id_procurador'];
$dt_inicial = formataData($_GET['dt_inicial']);
$dt_inicial .= " 00:00:01";
$dt_final = formataData($_GET['dt_final']);
$dt_final .= " 23:59:59";
$tipo_pesquisa = $_GET['tipo_pesquisa'];

if($tipo_pesquisa == 1)
//EXIBE POR DATA DO PROTOCOLO
{
$query = "SELECT 
			unidade.nm_unidade,
			processo.dt_abertura_processo,
		    processo.cd_protocolo_processo,
		    carteira.sg_carteira,
		    servico.ds_servico,
		    processo_status.nm_processo_status,
		    interessado.nm_interessado,
		    interessado.sg_tipo_interessado,
		    procurador.nm_procurador,
		    processo_andamento.dt_processo_andamento
		FROM processo
		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		INNER JOIN processo_servico on processo_servico.id_processo = processo.id_processo
		INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
		INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
		INNER JOIN carteira on carteira.id_carteira = processo.id_carteira
		INNER JOIN servico on servico.id_servico = processo_servico.id_servico
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		LEFT JOIN procurador on procurador.id_procurador = processo.id_procurador
		WHERE
		processo.dt_abertura_processo > '$dt_inicial' AND processo.dt_abertura_processo < '$dt_final' 
		AND processo_andamento.id_processo_andamento = (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
			";
}
else
//EXIBE POR DATA DO STATUS
{
$query = "SELECT 
			unidade.nm_unidade,
			processo.dt_abertura_processo,
		    processo.cd_protocolo_processo,
		    carteira.sg_carteira,
		    servico.ds_servico,
		    processo_status.nm_processo_status,
		    interessado.nm_interessado,
		    interessado.sg_tipo_interessado,
		    procurador.nm_procurador,
		    processo_andamento.dt_processo_andamento
		FROM processo
		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		INNER JOIN processo_servico on processo_servico.id_processo = processo.id_processo
		INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
		INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
		INNER JOIN carteira on carteira.id_carteira = processo.id_carteira
		INNER JOIN servico on servico.id_servico = processo_servico.id_servico
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		LEFT JOIN procurador on procurador.id_procurador = processo.id_procurador
		WHERE
		processo_andamento.id_processo_andamento = (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
		AND
		processo_andamento.dt_processo_andamento > '$dt_inicial' AND processo_andamento.dt_processo_andamento < '$dt_final'
		";
}



if($id_unidade > 0)
{
	$query .= " AND processo.id_unidade = $id_unidade";
}
if($id_carteira > 0)
{
	$query .= " AND processo.id_carteira = $id_carteira";
}
if($id_servico > 0)
{
	$query .= " AND processo_servico.id_servico = $id_servico";
}
if($id_status > 0)
{
	$query .= " AND processo_andamento.id_processo_status = $id_status";
}
if($tp_interessado != 'TODOS')
{
	$query .= " AND interessado.sg_tipo_interessado = '$tp_interessado'";
}
if($id_procurador > 0)
{
	$query .= " AND procurador.id_procurador = $id_procurador";
}

if($tipo_pesquisa == 1)
{
	$query .= " ORDER BY processo.dt_abertura_processo";
}
else
{
	$query .= " ORDER BY processo_andamento.dt_processo_andamento desc";
}


$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0 && $totalLinhas < 2000)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_relatorio' name='tb_relatorio' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>UNIDADE</center>
							</th>

							<th>
								<center>RECEBIDO</center>
							</th>

							<th>
								<center>PROTOCOLO</center>
							</th>

							<th>
								<center>TIPO</center>
							</th>

							<th>
								<center>INTERESSADO</center>
							</th>

							<th>
								<center>PROCURADOR</center>
							</th>
							
							<th>
								<center>CARTEIRA</center>
							</th>

							<th>
								<center>SERVIÇO</center>
							</th>

							<th>
								<center>STATUS</center>
							</th>

							<th>
								<center>GERENCIADO</center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$nm_unidade = $linha['nm_unidade'];
	  	$dt_abertura_processo = $linha['dt_abertura_processo'];
		$cd_protocolo_processo = $linha['cd_protocolo_processo'];
		$sg_carteira = $linha['sg_carteira'];
		$ds_servico = $linha['ds_servico'];
		$nm_processo_status = $linha['nm_processo_status'];
		$sg_tipo_interessado = $linha['sg_tipo_interessado'];
		$nm_interessado = $linha['nm_interessado'];
		$nm_procurador = $linha['nm_procurador'];
		$dt_processo_andamento = $linha['dt_processo_andamento'];
		
		echo("

				<tr class='active'>
					
					<td>
						<center>".$nm_unidade."</center>
					</td>

					<td>
						<center>".date('d/m/Y H:i', strtotime($dt_abertura_processo))."</center>
					</td>

					<td>
						<center>
						<a href=\"javascript:exibeDetalhesProcesso('$cd_protocolo_processo')\"\><center><b><font color=\"green\"> ".$cd_protocolo_processo."</font></b></center></a>
						</center>
					</td>

					<td>
						<center>".$sg_tipo_interessado."</center>
					</td>

					<td>
						<center>".$nm_interessado."</center>
					</td>

					<td>
						<center>".$nm_procurador."</center>
					</td>

					<td>
						<center>".$sg_carteira."</center>
					</td>

					<td>
						<center>".$ds_servico."</center>
					</td>

					<td>
						<center>".$nm_processo_status."</center>
					</td>

					<td>
						<center>".date('d/m/Y H:i', strtotime($dt_processo_andamento))."</center>
					</td>

					
				</tr>");
	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("</tbody></table>");
	    	echo("</div>");
}
else if ($totalLinhas > 2000)
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa excedeu o limite de 2000 registros. Diminua o período da consulta ou insira mais filtros e tente novamente!</p>
		");
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou resultado.</p>
		");
}

?>
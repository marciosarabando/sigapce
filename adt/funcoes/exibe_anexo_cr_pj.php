<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<link href="../../css/bootstrap.css" rel="stylesheet">
		<link rel="shortcut icon" href="../../img/favicon-2rm.png">

	</head>
<body>

<?php
//EXIBE O ANEXO AO CERTIFICADO DE REGISTRO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("../../funcoes/formata_dados.php");
include ("../../funcoes/parametro.php");

if (!isset($_SESSION)) 
{
	session_start();
}
$id_unidade_login = $_SESSION['id_unidade_sfpc'];


mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
$data_hora_atual = date('YmdHis');
$data_atual_formato_dd_mm_yyyy = date('d/m/Y');
$hora_atual = date('H:i');

$id_processo = $_GET['id_processo'];

$urlBRASAO2RM = "../../img/brasao2rm.png"; 

$data_por_extenso = retornaDataExtenso($data_hora_atual);

$assinatura_anexo_cr = retornaValorParametro($id_unidade_login,4,'assinatura_anexo_cr');

$query = "SELECT 
				interessado.cr_interessado,
		        interessado.nm_interessado,
		        adt_pce.nr_ordem_pce,
		        adt_pce.sg_grupo_pce,
		        adt_pce.nm_pce,
		        adt_materia_atv_pce_pj.qt_max,
		        adt_unidade_medida.sg_unidade_medida,
		        adt_atividade_pj.nm_completo_adt_atividade_pj,
		        processo.cd_protocolo_processo
		FROM adt_materia
		INNER JOIN processo on processo.id_processo = adt_materia.id_processo
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		RIGHT JOIN adt_materia_atv_pce_pj on adt_materia_atv_pce_pj.id_adt_materia = adt_materia.id_adt_materia
		INNER JOIN adt_atividade_pj on adt_atividade_pj.id_adt_atividade_pj = adt_materia_atv_pce_pj.id_adt_atividade
		LEFT JOIN adt_pce on adt_pce.id_adt_pce = adt_materia_atv_pce_pj.id_adt_pce
		LEFT JOIN adt_unidade_medida on adt_unidade_medida.id_adt_unidade_medida = adt_pce.id_adt_unidade_medida
		WHERE adt_materia.id_processo = $id_processo";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
$count = 0;
if($totalLinhas > 0)
{
	do
	  {
	  	$cr_interessado[$count] = $linha['cr_interessado'];
	  	$nm_interessado[$count] = $linha['nm_interessado'];
	  	$nr_ordem_pce[$count] = $linha['nr_ordem_pce'];
	  	$sg_grupo_pce[$count] = $linha['sg_grupo_pce'];
	  	$nm_pce[$count] = $linha['nm_pce'];
	  	$qt_max[$count] = $linha['qt_max'];
	  	$sg_unidade_medida[$count] = $linha['sg_unidade_medida'];
	  	$nm_completo_adt_atividade_pj[$count] = $linha['nm_completo_adt_atividade_pj'];
	  	$cd_protocolo_processo = $linha['cd_protocolo_processo'];

	  	$count = $count + 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

if($cr_interessado[0] == "")
{
	$cr_interessado[0] = "*** INICIAL ***";
}

echo("
		<div class='row-clearfix'>
			<div class='col-md-12'>

					<center>
						<img src=" .$urlBRASAO2RM. " height='200'/>
						<br><br>
						<b>ANEXO AO CERTIFICADO DE REGISTRO N° $cr_interessado[0] - SIGMA N° $cr_interessado[0] - SFPC 2ª RM</b>
						<br>
						<b>PROPRIETÁRIO: $nm_interessado[0]</b>
						<br><br>
						<b>RELAÇÃO DE PRODUTOS CONTROLADOS</b>
			    		<br><br>
		    		</center>
					
					<div class='table-responsive'>
						<table class='table table-condensed table-bordered'>
							<thead>
								<tr>
									<td width='10'>
										<font size=1><b>N° ORD.</b></font>
									</td>
									<td width='10'>
										<font size=1><b>GRUPO</b></font>
									</td>
									<td>
										<font size=1><b>DESCRIÇÃO DO PRODUTO</b></font>
									</td>
									<td width='10'>
										<font size=1><b>QTD MAX</b></font>
									</td>
									<td width='10'>
										<font size=1><b>UND. MDD</b></font>
									</td>
									<td>
										<font size=1><b>ATIVIDADE</b></font>
									</td>
								</tr>
							</thead>
							<tbody>
	");

					//EXIBE OS PRODUTOS CONTROLADOS RELACIONADOS COM A MATÉRIA
					for($h = 0; $h < $count; $h++)
					{
						if($nr_ordem_pce[$h] == "")
						{
							$nr_ordem_pce[$h] = '-';	
						}
						if($sg_grupo_pce[$h] == "")
						{
							$sg_grupo_pce[$h] = '-';	
						}
						if($nm_pce[$h] == "")
						{
							$nm_pce[$h] = '-';	
						}
						if($qt_max[$h] == 0)
						{
							$qt_max[$h] = '-';	
						}
						if($sg_unidade_medida[$h] == "")
						{
							$sg_unidade_medida[$h] = '-';	
						}


						echo("
								<tr>
									<td style='white-space: initial'>
										<font size=1><center>$nr_ordem_pce[$h]</center></font>
									</td>
									
									<td style='white-space: initial'>
										<font size=1><center>$sg_grupo_pce[$h]</center></font>
									</td>
								
									<td style='white-space: initial'>
										<font size=1>$nm_pce[$h]</font>
									</td>
								
									<td style='white-space: initial'>
										<font size=1><center>$qt_max[$h]</center></font>
									</td>
								
									<td style='white-space: initial'>
										<font size=1><center>$sg_unidade_medida[$h]</center></font>
									</td>
								
									<td style='white-space: initial'>
										<font size=1>$nm_completo_adt_atividade_pj[$h]</font>
									</td>
								</tr>

							");

					}

echo("
							</tbody>
						</table>
					</div>

		    		
					<div style='text-align: right;'>

						<br><br>
					    
					    $data_por_extenso
					    <br><br><br><br>
					   	__________________________________________________
					   	<br>					   	
					   	 $assinatura_anexo_cr
					   	<br>
					   	SFPC/2

					</div>

					<br><br><br>

					<div class='panel panel-default'>
						<div class='panel-body'>
							<font size=1>Este anexo ao CR foi emitido em <b>$data_atual_formato_dd_mm_yyyy às $hora_atual horas</b> pelo SIGAPCE, Sistema de Gerenciamento de Atividades com Produtos Controlados, conforme </b>DEFERIMENTO</b> do processo e encontra-se disponível para consulta interna informando o código do SISPROT Nº<b> $cd_protocolo_processo</b>.</font>
						</div>
					</div>
					
					
		</div>
	</div>
    

    ");

?>


</body>
</html>

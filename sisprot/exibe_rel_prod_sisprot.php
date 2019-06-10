
<?php
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
include ("../sae/funcoes/formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_unidade = $_GET['id_unidade'];
$id_carteira = $_GET['id_carteira'];
$id_servico = $_GET['id_servico'];
$tp_interessado = $_GET['tp_interessado'];
$qtd_amostragem = 0; //$_GET['qtd_amostragem'];
$dt_inicial = formataData($_GET['dt_inicial']);
$dt_inicial .= " 00:00:01";
$dt_final = formataData($_GET['dt_final']);
$dt_final .= " 23:59:59";

$query2 = "CALL relatorio_v4('$dt_inicial', '$dt_final', $id_unidade, $qtd_amostragem, $id_carteira, $id_servico, '$tp_interessado'  )";

$query = "select SERVICO, PROTOCOLO, DATA_DE_ENTRADA, DATA_DE_DISTRIBUICAO, TEMPO_DE_PROCESSAMENTO, ENTRADA_EM_ANALISE, CONCLUSAO_DA_ANALISE, TEMPO_DE_ANALISE, TEMPO_DE_RECEBIMENTO_E_ANALISE, ENTRADA_EM_REGISTRO, DATA_DE_ENTREGA, TEMPO_DE_REGISTRO, TEMPO_TOTAL from PERFORMANCE_CARTEIRA";

$query3 = "select VAL1, VAL2, VAL3, VAL4, VAL5 from MEDIA";

$dados2 = mysqli_query($conn,$query2) or die(mysqli_error($conn));
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$dados3 = mysqli_query($conn,$query3) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$linha3 = mysqli_fetch_assoc($dados3);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0 && $totalLinhas < 2000)
{

	echo("
			<div class='tabbable' id='tabs_relatorio'>
				<ul class='nav nav-tabs'>
					<li class='active'>
						<a href='#panel-resultado-pesquisa' data-toggle='tab'><div id='div_tab_resultado'></div>DADOS</a>
					</li>
					<li>
						<a href='#panel-detalhes-processo' data-toggle='tab'><div id='div_tab_nr_processo'></div></a>
					</li>
				</ul>

				
				<div class='tab-content'>
					<!-- Inicio da TAB Resultado Pesquisa-->
					<div class='tab-pane active' id='panel-resultado-pesquisa'>


						<div class='table-responsive'>
				  				<table id='tb_relatorio' name='tb_relatorio' class='table table-condensed table-bordered'>
				  					<thead>
										<tr>
											<th>
												<center>SERVICO</center>
											</th>

											<th>
												<center>PROTOCOLO</center>
											</th>

											<th>
												<center>DATA ENTRADA</center>
											</th>

											<th>
												<center>DATA DE DISTRIBUIÇÃO</center>
											</th>

											<th >
												<center>TEMPO</center>
											</th>
											
											<th>
												<center>ENTRADA EM ANÁLISE</center>
											</th>

											<th>
												<center>CONCLUSÃO DA ANÁLISE</center>
											</th>

											<th>
												<center>TEMPO</center>
											</th>

											<th>
												<center>TEMPO DE RECEBIMENTO E ANÁLISE</center>
											</th>
											<th>
												<center>DATA DE ENTRADA REGISTRO</center>
											</th>
											<th>
												<center>DATA DE CONCLUSÃO DO REGISTRO</center>
											</th>
											<th>
												<center>TEMPO REGISTRO</center>
											</th>
											<th>
												<center>TEMPO TOTAL</center>
											</th>
																															
											
										</tr>
									</thead>
									<tbody>
		");
	do
	  {
	  	
	  	$SERVICO = $linha['SERVICO'];
	  	$PROTOCOLO = $linha['PROTOCOLO'];
		$DATA_DE_ENTRADA = $linha['DATA_DE_ENTRADA'];
		$DATA_DE_DISTRIBUICAO = $linha['DATA_DE_DISTRIBUICAO'];
		$TEMPO_DE_PROCESSAMENTO = $linha['TEMPO_DE_PROCESSAMENTO'];
		$ENTRADA_EM_ANALISE = $linha['ENTRADA_EM_ANALISE'];
		$CONCLUSAO_DA_ANALISE = $linha['CONCLUSAO_DA_ANALISE'];
		$TEMPO_DE_ANALISE = $linha['TEMPO_DE_ANALISE'];
		$TEMPO_DE_RECEBIMENTO_E_ANALISE = $linha['TEMPO_DE_RECEBIMENTO_E_ANALISE'];
		$ENTRADA_EM_REGISTRO = $linha['ENTRADA_EM_REGISTRO'];
		$DATA_DE_ENTREGA = $linha['DATA_DE_ENTREGA'];
		$TEMPO_DE_REGISTRO = $linha['TEMPO_DE_REGISTRO'];
		$TEMPO_TOTAL = $linha['TEMPO_TOTAL'];
		$MEDIA_TEMPO_DE_PROCESSAMENTO = $linha3['VAL1'];
		$MEDIA_TEMPO_DE_ANALISE = $linha3['VAL2'];
		$MEDIA_TEMPO_DE_RECEBIMENTO_E_ANALISE = $linha3['VAL3'];
		$MEDIA_TEMPO_DE_REGISTRO = $linha3['VAL4'];
		$MEDIA_TEMPO_TOTAL = $linha3['VAL5'];
		
		
		if($TEMPO_DE_PROCESSAMENTO <= 1) {
			$tempo_prot = "style='background-color:green'";
										 }
		else 
		if
		($TEMPO_DE_PROCESSAMENTO == 2 ) {
			$tempo_prot = "style='background-color:blue'";
										 }
		else 
		if($TEMPO_DE_PROCESSAMENTO == 3 or $TEMPO_DE_PROCESSAMENTO == 4 ) {
			$tempo_prot = "style='background-color:red'";
										 }
		else if($TEMPO_DE_PROCESSAMENTO>4){
			$tempo_prot = "style='color: white;background-color:black'";
			 }
			 
			 
			 
		if($TEMPO_DE_ANALISE <= 1) {
			$tempo_process = "style='background-color:green'";
										 } 
		else if($TEMPO_DE_ANALISE >= 2 and $TEMPO_DE_ANALISE <= 10 ) {
			$tempo_process = "style='background-color:blue'";
										 }
		else if($TEMPO_DE_ANALISE >= 11 and $TEMPO_DE_ANALISE <= 20 ) {
			$tempo_process = "style='background-color:red'";
										 }	 
		else if($TEMPO_DE_ANALISE >= 21) {
			$tempo_process = "style='color: white;background-color:black'";
										 }	 		

										 
		if($TEMPO_DE_RECEBIMENTO_E_ANALISE <= 4) {
			$tempo_recan = "style='background-color:green'";
										 } 
		else if($TEMPO_DE_RECEBIMENTO_E_ANALISE >= 5 and $TEMPO_DE_RECEBIMENTO_E_ANALISE <= 13 ) {
			$tempo_recan = "style='background-color:blue'";
										 }
		else if($TEMPO_DE_RECEBIMENTO_E_ANALISE >= 14 and $TEMPO_DE_RECEBIMENTO_E_ANALISE <= 24 ) {
			$tempo_recan = "style='background-color:red'";
										 }	 
		else if($TEMPO_DE_RECEBIMENTO_E_ANALISE >= 25) {
			$tempo_recan = "style='color: white;background-color:black'";
										 }				

		if($TEMPO_DE_REGISTRO <= 8) {
			$tempo_reg = "style='background-color:green'";
										 } 
		else if($TEMPO_DE_REGISTRO >= 9 and $TEMPO_DE_REGISTRO <= 29 ) {
			$tempo_reg = "style='background-color:blue'";
										 }
		else if($TEMPO_DE_REGISTRO >= 30 and $TEMPO_DE_REGISTRO <= 48 ) {
			$tempo_reg = "style='background-color:red'";
										 }	 
		else if($TEMPO_DE_REGISTRO >= 49) {
			$tempo_reg = "style='color: white;background-color:black'";
										 }											 
			 
		if($TEMPO_TOTAL <= 20) {
			$tempo_tot = "style='background-color:green'";
										 } 
		else if($TEMPO_TOTAL >= 21 and $TEMPO_TOTAL <= 30 ) {
			$tempo_tot = "style='background-color:blue'";
										 }
		else if($TEMPO_TOTAL >= 31 and $TEMPO_TOTAL <= 100 ) {
			$tempo_tot = "style='background-color:red'";
										 }	 
		else if($TEMPO_TOTAL >= 101) {
			$tempo_tot = "style='color: white;background-color:black'";
										 }	
			 
		
		
		echo("

				<tr class='active'>
					
					<td>
						<center>".$SERVICO."</center>
					</td>
					
					<td>
						<center>
						<a href=\"javascript:exibeDetalhesProcesso('$PROTOCOLO')\"\><center><b><font color=\"green\"> ".$PROTOCOLO."</font></b></center></a>
						</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($DATA_DE_ENTRADA))."</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($DATA_DE_DISTRIBUICAO))."</center>
					</td> 
					
					<td	".$tempo_prot."> 
						<center>".$TEMPO_DE_PROCESSAMENTO."</center>
					</td>

					<td>
						<center>".date('d/m/Y', strtotime($ENTRADA_EM_ANALISE))."</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($CONCLUSAO_DA_ANALISE))."</center>
					</td>
					
					<td ".$tempo_process.">
						<center>".$TEMPO_DE_ANALISE."</center>
					</td>

						
					<td ".$tempo_recan.">
						<center>".$TEMPO_DE_RECEBIMENTO_E_ANALISE."</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($ENTRADA_EM_REGISTRO))."</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($DATA_DE_ENTREGA))."</center>
					</td>
					
					<td ".$tempo_reg.">
						<center>".$TEMPO_DE_REGISTRO."</center>
					</td>
					
					<td ".$tempo_tot.">
						<center>".$TEMPO_TOTAL."</center>
					</td>
					
					
					
				</tr>");

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);


  		
		echo("<tr><td style='background-color:#000033'></td><td style='background-color:#000033'></td><td style='background-color:#000033'></td><td style='color: white;background-color:#000033'><center>MÉDIA DISTRIBUIÇÃO</center></td><td style='color: white;background-color:#000033'><center>".$MEDIA_TEMPO_DE_PROCESSAMENTO."</center></td><td style='background-color:#000033'></td><td style='color: white;background-color:#000033'><center>MÉDIA ANÁLISE</center></td><td style='color: white;background-color:#000033'><center>".$MEDIA_TEMPO_DE_ANALISE."</center></td><td style='color: white;background-color:#000033'><center>".$MEDIA_TEMPO_DE_RECEBIMENTO_E_ANALISE."</center></td><td style='color: white;background-color:#000033'></td><td style='color: white;background-color:#000033'><center>MÉDIA REGISTRO</center></td><td style='color: white;background-color:#000033'><center>".$MEDIA_TEMPO_DE_REGISTRO."</center></td><td style='color: white;background-color:#000033'><center>".$MEDIA_TEMPO_TOTAL."</center></td><tr>");
	  
	  while($linha3 = mysqli_fetch_assoc($dados3));
	  mysqli_free_result($dados3);
	  
	  echo("</tbody></table>");

	  echo("</div>");

  	  echo("
		</div>
		<!-- FIM da TAB Resultado Pesquisa-->

		<!-- INICIO da TAB Detalhes do Processo-->
		<div class='tab-pane' id='panel-detalhes-processo'>
					
					<div id='div_detalhes_processo'></div>

				</div>
				<!-- FIM da TAB Detalhes do Processo-->
			</div>
		</div>
  	");
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
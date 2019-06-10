
<?php
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
include ("../sae/funcoes/formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_unidade = $_GET['id_unidade'];
$id_carteira = $_GET['id_carteira'];
$id_status = $_GET['id_status'];
$id_prazo = $_GET['id_prazo'];


$query2 = "SELECT 
 ds_carteira CARTEIRA, ps.nm_processo_status as STATUS, COUNT(1) as QUANTIDADE
FROM
    (SELECT 
        MAX(id_processo_andamento) AS id_processo_andamento
    FROM
        processo_andamento
    GROUP BY id_processo) base,
    processo_andamento pa,
    processo p,
    processo_status ps,
    servico s,
    processo_servico pser,
    carteira c
WHERE
    pa.id_processo_status NOT IN (7 , 8, 9, 10, 11, 12)
        AND base.id_processo_andamento = pa.id_processo_andamento
        AND p.id_processo = pa.id_processo
        and p.id_carteira = c.id_carteira
        and p.id_processo = pser.id_processo
        and pser.id_servico = s.id_servico
        AND id_unidade = $id_unidade
        and p.id_carteira = if($id_carteira=0, p.id_carteira ,$id_carteira ) 
		and pa.id_processo_status = if($id_status=0, pa.id_processo_status ,$id_status )
        and pa.id_processo_status = ps.id_processo_status
                and 
				                case
                when $id_prazo =0
                then dt_abertura_processo  < (date_add(now(), INTERVAL  -1 month))
				when $id_prazo =1
                then dt_abertura_processo >= (date_add(now(), INTERVAL  -1 month))
                else 
                dt_abertura_processo = dt_abertura_processo
                end
GROUP BY   ds_carteira, pa.id_processo_status order by  ds_carteira, ps.nm_processo_status, dt_abertura_processo asc";

$query = "SELECT  ds_carteira CARTEIRA,
p.cd_protocolo_processo as NR_PROTOCOLO, dt_abertura_processo as DATA_ABERTURA_PROCESSO , ps.nm_processo_status as STATUS
FROM
    (SELECT 
        MAX(id_processo_andamento) AS id_processo_andamento
    FROM
        processo_andamento
    GROUP BY id_processo) base,
    processo_andamento pa,
    processo p,
    processo_status ps,
    servico s,
    processo_servico pser,
    carteira c
WHERE
    pa.id_processo_status NOT IN (7 , 8, 9, 10, 11, 12)
        AND base.id_processo_andamento = pa.id_processo_andamento
        AND p.id_processo = pa.id_processo
        and p.id_carteira = c.id_carteira
        and p.id_processo = pser.id_processo
        and pser.id_servico = s.id_servico
        AND id_unidade = $id_unidade
		and pa.id_processo_status = if($id_status=0, pa.id_processo_status ,$id_status )
        and p.id_carteira = if($id_carteira=0, p.id_carteira ,$id_carteira ) 
        and pa.id_processo_status = ps.id_processo_status
                and 
				case
                when $id_prazo =0
                then dt_abertura_processo  < (date_add(now(), INTERVAL  -1 month))
				when $id_prazo =1
                then dt_abertura_processo >= (date_add(now(), INTERVAL  -1 month))
                else 
                dt_abertura_processo = dt_abertura_processo
                end
GROUP BY ds_carteira,  p.cd_protocolo_processo , dt_abertura_processo, pa.id_processo_status order by ds_carteira, ps.nm_processo_status, dt_abertura_processo asc";

//$query3 = "select VAL1, VAL2, VAL3, VAL4, VAL5 from MEDIA";

$dados2 = mysqli_query($conn,$query2) or die(mysql_error());
$dados = mysqli_query($conn,$query) or die(mysql_error());
//$dados3 = mysqli_query($conn,$query3) or die(mysql_error());
$linha2 = mysqli_fetch_assoc($dados2);
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0 && $totalLinhas < 5000)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_relatorio' name='tb_relatorio' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>CARTEIRA</center>
							</th>

							<th>
								<center>STATUS</center>
							</th>

							<th>
								<center>QUANTIDADE</center>
							</th>
							
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$CARTEIRA = $linha2['CARTEIRA'];
	  	$STATUS = $linha2['STATUS'];
		$QUANTIDADE = $linha2['QUANTIDADE'];
		
		
		echo("

				<tr class='active'>
					
					<td>
						<center>".$CARTEIRA."</center>
					</td>
					
					<td>
						<center>".$STATUS."</center>
					</td>
					
					<td>
						<center>".$QUANTIDADE."</center>
					</td>
					
					
				</tr>

				
				");
				

	    	

	 }while($linha2 = mysqli_fetch_assoc($dados2));
	  mysqli_free_result($dados2);

	  echo("		</tbody>
				</table>
			</div>");
  		


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
													<center>CARTEIRA</center>
												</th>
												<th>
													<center>NR PROTOCOLO</center>
												</th>

												<th>
													<center>DATA ABERTURA PROCESSO</center>
												</th>

												<th>
													<center>STATUS</center>
												</th>
												
											</tr>
										</thead>
										<tbody>
				");
	do
	  {
	  	$CARTEIRA2 = $linha['CARTEIRA'];
	  	$NR_PROTOCOLO = $linha['NR_PROTOCOLO'];
	  	$DATA_ABERTURA_PROCESSO = $linha['DATA_ABERTURA_PROCESSO'];
		$STATUS2 = $linha['STATUS'];
		
		
		echo("

				<tr class='active'>
					<td>
						<center>".$CARTEIRA2."</center>
					</td>
					<td>
						<center>
						<a href=\"javascript:exibeDetalhesProcesso('$NR_PROTOCOLO')\"\><center><b><font color=\"green\"> ".$NR_PROTOCOLO."</font></b></center></a>
						</center>
					</td>
					
					<td>
						<center>".date('d/m/Y', strtotime($DATA_ABERTURA_PROCESSO))."</center>
					</td>
					
					<td>
						<center>".$STATUS2."</center>
					</td>
					
					
				</tr>


				


				");
				

	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	
	echo("

							</tbody>
						</table>
					</div>
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
else if ($totalLinhas > 5000)
{

echo("
		<div class='table-responsive'>
  				<table id='tb_relatorio' name='tb_relatorio' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>CARTEIRA</center>
							</th>

							<th>
								<center>STATUS</center>
							</th>

							<th>
								<center>QUANTIDADE</center>
							</th>
							
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$CARTEIRA = $linha2['CARTEIRA'];
	  	$STATUS = $linha2['STATUS'];
		$QUANTIDADE = $linha2['QUANTIDADE'];
		
		
		echo("

				<tr class='active'>
					
					<td>
						<center>".$CARTEIRA."</center>
					</td>
					
					<td>
						<center>".$STATUS."</center>
					</td>
					
					<td>
						<center>".$QUANTIDADE."</center>
					</td>
					
					
				</tr>");
				

	    	

	 }while($linha2 = mysqli_fetch_assoc($dados2));
	  mysqli_free_result($dados2);



}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou resultado.</p>
		");
}

?>
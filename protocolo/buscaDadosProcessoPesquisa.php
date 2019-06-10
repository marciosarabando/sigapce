<?php
include ("../funcoes/verificaAtenticacao.php");

function limpaCPF_CNPJ($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor;
}

function formataData($valor)
{
$valor = trim($valor);
$dia = substr($valor, -10, 2);
$mes = substr($valor, -7, 2);
$ano = substr($valor, -4, 4);

$valor = $ano;
$valor .= "-";
$valor .= $mes;
$valor .= "-";
$valor .= $dia;
return $valor;
}

function formataCPF($valor)
{
$valor = trim($valor);
$primeiro = substr($valor, -11, 3);
$segundo = substr($valor, -8, 3);
$terceiro = substr($valor, -5, 3);
$digito = substr($valor, -2, 2);

$valor = $primeiro;
$valor .= ".";
$valor .= $segundo;
$valor .= ".";
$valor .= $terceiro;
$valor .= "-";
$valor .= $digito;

return $valor;
}

function formataCNPJ($valor)
{
$valor = trim($valor);
$primeiro = substr($valor, -14, 2);
$segundo = substr($valor, -12, 3);
$terceiro = substr($valor, -9, 3);
$quarto = substr($valor, -6, 4);
$digito = substr($valor, -2, 2);

$valor = $primeiro;
$valor .= ".";
$valor .= $segundo;
$valor .= ".";
$valor .= $terceiro;
$valor .= "/";
$valor .= $quarto;
$valor .= "-";
$valor .= $digito;

return $valor;
}

$info = array("1","2","5");
$warning = array("3","4","16");
$danger = array("7");
$success = array("6","8","9","10","11","12","13","14","15");
$processo_pronto = array("8");

$tipo_pesquisa = $_GET['tipo_pesquisa'];
$valor = $_GET['valor_pesquisa'];

//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
	session_start();
}
$id_unidade = $_SESSION['id_unidade_sfpc'];

//CAMPOS
$id_processo = null;
$cd_protocolo_processo = null;
$dt_abertura_processo = null;
$nm_interessado = null;
$cpf_interessado = null;
$cnpj_interessado = null;
$status_processo = null;
$id_processo_status = null;
$nm_posto_graduacao = null;
$nm_guerra = null;
$sg_carteira = null;

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = " 
		SELECT 	
				processo.id_processo,
				processo.cd_protocolo_processo,
				processo.dt_abertura_processo,
		        interessado.nm_interessado,
		        interessado.cpf_interessado,
		        interessado.cnpj_interessado,
		        processo_status.nm_processo_status,
		        processo_status.id_processo_status,
		        servico.ds_servico,
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_guerra,
		        carteira.sg_carteira
		       
		        
		FROM 

		processo

		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		INNER JOIN login on login.id_login = processo_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
		INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
		INNER JOIN carteira on carteira.id_carteira = processo.id_carteira
		INNER JOIN processo_servico on processo_servico.id_processo = processo.id_processo
        INNER JOIN servico on servico.id_servico = processo_servico.id_servico

		WHERE 
		processo_andamento.id_processo_andamento IN (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
		
";

if($tipo_pesquisa == "data")
{
	$valor = formataData($valor);
	$query .= " AND dt_abertura_processo > '".$valor." 00:00:01' and dt_abertura_processo < '".$valor." 23:59:59'";
	$query .= " AND processo.id_unidade = ".$id_unidade."";
}
else if ($tipo_pesquisa == "protocolo")
{
	$query .= " AND processo.cd_protocolo_processo = '".$valor."'";
}
else if ($tipo_pesquisa == "cpf")
{
	$valor = limpaCPF_CNPJ($valor);
	if($valor == null) {$valor='0';}
	$query .= " AND interessado.cpf_interessado = '".$valor."'";
}
else if ($tipo_pesquisa == "cnpj")
{
	$valor = limpaCPF_CNPJ($valor);
	if($valor == null) {$valor='0';}
	$query .= " AND interessado.cnpj_interessado = '".$valor."'";
}
else if ($tipo_pesquisa == "cr")
{
	if($valor == null) {$valor='0';}
	$query .= " AND interessado.cr_interessado = '".$valor."'";
}
else if ($tipo_pesquisa == "tr")
{
	if($valor == null) {$valor='0';}
	$query .= " AND interessado.tr_interessado = '".$valor."'";
}
else if ($tipo_pesquisa == "requerente")
{
	$query .= " AND interessado.nm_interessado LIKE '".$valor."%'";
}
else if ($tipo_pesquisa == "processos_prontos")
{
	$query .= " AND processo_andamento.id_processo_status = 8 AND unidade.id_unidade = ".$id_unidade."";
}

$query .= " GROUP BY processo.id_processo ORDER by processo.dt_abertura_processo desc";

//echo($query);

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	echo("
		<div class='table-responsive'>
  				<table id='tb_protocolos' name='tb_protocolos' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>PROTOCOLO</center>
							</th>
							<th>
								<center>CARTEIRA</center>
							</th>
							<th>
								<center>DATA</center>
							</th>
							<th>
								REQUERENTE
							</th>
							<th>
								<center>CPF/CNPJ</center>
							</th>
							<th>
								<center>GERENCIADO POR</center>
							</th>
							<th>
								<center>STATUS</center>
							</th>
							<th>
								<center>Ação</center>
							</th>
						</tr>
					</thead>
					<tbody>
							");
	
	//$count = 0;
	do
	  {
	  	$id_processo = $linha['id_processo'];
		$cd_protocolo_processo = $linha['cd_protocolo_processo'];
		$dt_abertura_processo = $linha['dt_abertura_processo'];
		$nm_interessado = $linha['nm_interessado'];
		$cpf_interessado = $linha['cpf_interessado'];
		$cnpj_interessado = $linha['cnpj_interessado'];
		$status_processo = $linha['nm_processo_status'];
		$id_processo_status = $linha['id_processo_status'];
		$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		$nm_guerra = $linha['nm_guerra'];
		$sg_carteira = $linha['sg_carteira'];
		$ds_servico = $linha['ds_servico'];
		

		echo("

						<tr class='active'>
							<td>
								<a href=\"javascript:exibeDetalhesProcesso('$cd_protocolo_processo')\"\><center><b><font color=\"green\"> ".$cd_protocolo_processo."</font></b></center></a>
							</td>
							<td>
								<center>".$sg_carteira."</center>
							</td>
							<td>
								<center>".date('d/m/Y H:i', strtotime($dt_abertura_processo))."</center>
							</td>
							<td>
								".$nm_interessado."
							</td>
			");
			if($cpf_interessado != null)
			{
			echo("
							<td>
								<center>".formataCPF($cpf_interessado)."</center>
							</td>
				");
			}
			if($cnpj_interessado != null)
			{
			echo("
							<td>
								<center>".formataCNPJ($cnpj_interessado)."</center>
							</td>
				");
			}
			echo("
							<td>
								<center>".$nm_posto_graduacao." ".$nm_guerra."</center>
							</td>
							
				");


			


			if (in_array($id_processo_status, $info)) { 
			    echo   ("<td width='180'>
							<font color='blue'><center><b>".$status_processo."</b></center></font>
						</td>");
			}
			
			else if (in_array($id_processo_status, $warning)) { 
			    echo   ("<td>
							<font color='#FFBF00'><center><b>".$status_processo."</b></center></font>
						</td>");
			}

			else if (in_array($id_processo_status, $danger)) { 
			    echo   ("<td>
							<font color='red'><center><b>".$status_processo."</b></center></font>
						</td>");
			}
			
			else if (in_array($id_processo_status, $success)) { 
			    echo   ("<td>
							<font color='green'><center><b>".$status_processo."</b></center></font>
						</td>");
			}

			if (in_array($id_processo_status, $processo_pronto))
			{
				echo("
						<td>
							<!-- Button trigger modal ALTERA STATUS-->
							<center>
							<button type='button' id='btn_entregar_processo_$id_processo' onclick='' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModalEntregaProcesso_$id_processo'>
							  <i class='glyphicon glyphicon-cog'></i> ENTREGAR PROCESSO
							</button>
							</center>

							<!-- Modal -->
							<div class='modal fade' id='myModalEntregaProcesso_$id_processo' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
							  <div class='modal-dialog' role='document'>
							    <div class='modal-content'>
							      <div class='modal-header'>
							        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
							        <h4 class='modal-title' id='myModalLabel'>
							        	<b><i class='glyphicon glyphicon-check'></i>ENTREGA DE PROCESSO PRONTO</b>
								        <br>
								        PROTOCOLO: <font color='green'><b>$cd_protocolo_processo</b></font>
								        <input type=hidden id='txt_id_processo' name='txt_id_processo' value='$id_processo'></input>
								        <input type=hidden id='txt_cd_protocolo_processo' name='txt_cd_protocolo_processo' value='$cd_protocolo_processo'></input>
							        </h4>
							      </div>
							      <div class='modal-body'>
							      		<table class='table table-condensed table-bordered'>
											<tr>
												<td>
													<label>REQUERENTE:</label> 
												</td>
												<td>
													<label>$nm_interessado</label> 
												</td>
											<tr>
											<tr>
												<td>
													<label>ASSUNTO:</label> 
												</td>
												<td>
													<label>$ds_servico</label> 
												</td>
											<tr>
										</table>
							      		
					 					<div class='alert alert-success' role='alert'>
					 						<p>1 - Confira as informações do <b>processo</b>;</p>
					 						<p>2 - Realize o <b>procedimento de entrega</b> ao interessado;</p>
					 						<p>3 - Clique em <b>Confirmar Entrega!</b></p>
					 					</div>

					 					<label>(PREENCHIMENTO NÃO OBRIGATÓRIO)</label>
				 						<textarea id='txt_observacao_entrega' class='form-control upper' rows='3' maxlength=400 placeholder='INFORME AQUI, CASO EXISTA ALGUMA INFORMAÇÃO EXTRA SOBRE A ENTREGA DESTE PROCESSO'></textarea>
							      		
							      		
								  </div>
							      <div class='modal-footer'>
							        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
							        <button type='button' name='btn_entregar_processo_confirma' onClick='entregarProcessoPronto($id_processo)' class='btn btn-success' data-dismiss='modal'>CONFIRMAR ENTREGA</button>
							      </div>
							     </div>
							  </div>
							</div>
						</td>
					");
			}
			else
			{
				echo("
						<td>
						</td>
					");
			}

			echo("
						</tr>
			");
					

		//$count .= $count + 1;
 	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);



echo("</tbody>
  				</table>
  				<button type='button' onclick='imprimirResultadoPesquisa()' class='btn btn-default'><i class='glyphicon glyphicon-print'></i> IMPRIMIR</button>
  		</div>

	");

}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou registros.</p>
		");
}

?>
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

$tipo_pesquisa = $_GET['tipo_pesquisa'];
$valor = $_GET['valor_pesquisa'];
$dt_filtro_pesquisa = $_GET['dt_filtro_pesquisa'];

//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
	session_start();
}
$id_unidade = $_SESSION['id_unidade_sfpc'];
$id_login = $_SESSION['id_login_sfpc'];

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
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_guerra,
		        carteira.sg_carteira,
		        processo_andamento.dt_processo_andamento
		        
		FROM 

		processo

		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		INNER JOIN login on login.id_login = processo_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
		INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
		INNER JOIN carteira on carteira.id_carteira = processo.id_carteira

		WHERE 
		processo_andamento.id_processo_andamento IN (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
		AND processo.id_carteira in (SELECT id_carteira FROM login_carteira WHERE id_login = $id_login)
		AND processo.id_unidade in (SELECT id_unidade FROM login_unidade WHERE id_login = $id_login)		
";



if ($tipo_pesquisa == "protocolo")
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
else if($tipo_pesquisa == "status")
{
	$query .= " AND processo_status.id_processo_status = '".$valor."'";
	if($dt_filtro_pesquisa != "")
	{
		$data_inicio = formataData($dt_filtro_pesquisa);
		$data_inicio .= " ";
		$data_inicio .= "00:01";

		$data_fim = formataData($dt_filtro_pesquisa);
		$data_fim .= " ";
		$data_fim .= "23:59";

		$query .= " AND processo_andamento.dt_processo_andamento > '$data_inicio' AND processo_andamento.dt_processo_andamento < '$data_fim'";
	}
	$query .= " AND processo.id_unidade = $id_unidade";
}

$query .= " ORDER by processo.dt_abertura_processo desc";

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
  				<table class='table table-condensed table-bordered' id='tb_processos_pesquisa'>
  					<thead>
						<tr>
							<th>
								<center>Nº PROTOCOLO</center>
							</th>
							<th>
								<center>CARTEIRA</center>
							</th>
							<th>
								<center>DATA DA SOLICITAÇÃO</center>
							</th>
							<th>
								INTERESSADO
							</th>
							<th>
								<center>CPF/CNPJ</center>
							</th>
							<th>
								<center>GERENCIADO POR</center>
							</th>
							<th>
								<center>DATA DO GERENCIAMENTO</center>
							</th>
							<th>
								<center>STATUS</center>
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
		$dt_processo_andamento = $linha['dt_processo_andamento'];

		echo("

						<tr class='active'>
							<td>
								<a href=\"javascript:exibeTelaDetalhesProcessoAnalise('$cd_protocolo_processo')\"\><center><b><font color=\"green\"> ".$cd_protocolo_processo."</font></b></center></a>
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

			echo("<td>");
			echo("<div id='div_login_responsavel_$id_processo'>");
			echo("
								<center>".$nm_posto_graduacao." ".$nm_guerra."</center>
				");
			echo("</div>");
			echo("</td>");

			
			echo("<td>");
			echo("<div id='div_dt_gerenciamento_$id_processo'>");
				echo("
							<center>". date('d/m/Y H:i', strtotime($dt_processo_andamento)) . "</center>												
					");
			echo("</div>");
			echo("</td>");


			$info = array("1","2","5");
			$warning = array("3","4", "16");
			$danger = array("7");
			$success = array("6","8","9","10","11","12","13","14","15");

			//echo("<td width='201'>");
			echo("<td width='180'>");
			echo("<div id='div_status_$id_processo'>");
			
				if (in_array($id_processo_status, $info)) { 
				    echo   ("
								<font color='blue'><center><b>".$status_processo."</b></center></font>
							");
				}
				
				else if (in_array($id_processo_status, $warning)) { 
				    echo   ("
								<font color='#FFBF00'><center><b>".$status_processo."</b></center></font>
							");
				}

				else if (in_array($id_processo_status, $danger)) { 
				    echo   ("
								<font color='red'><center><b>".$status_processo."</b></center></font>
							");
				}
				
				else if (in_array($id_processo_status, $success)) { 
				    echo   ("
								<font color='green'><center><b>".$status_processo."</b></center></font>
							");
				}

			echo("</div>");
			echo("</td>");
			
			$login_responsavel = $nm_posto_graduacao." ".$nm_guerra;
			//SE O STATUS FOR PROTOCOLADO EXIBE O BOTÃO PARA DISTRIBUIR
			if($id_processo_status == 1)
			{
				echo("
					<td>
						<div id='div_btn_distribuir_$id_processo'>
							<center><button class='btn btn-warning btn-sm' type='button' onclick=\"distribuir_processo($id_processo, '$cd_protocolo_processo', '$login_responsavel')\"><i class='glyphicon glyphicon-cog'></i> DISTRIBUIR</button></center>
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
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou Registros ou Usuário não possui permissão para analisar o processo.</p>
		");
}

?>
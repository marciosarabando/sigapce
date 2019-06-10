<?php
//EXIBE LISTA DE DOCUMENTOS DIGITALIZADOS
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("../../funcoes/formata_dados.php");

if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['ambiente']))
{
	$ambiente = $_SESSION['ambiente'];
	if($ambiente == "DESENV")
	{
		$tempDir = $_SESSION['url_ged_server_local'];
	}
	else if($ambiente == "HOM")
	{
		$tempDir = $_SESSION['url_ged_server_hom'];
	}
	else if($ambiente == "PROD")
	{
		$tempDir = $_SESSION['url_ged_server_prd'];
	}
	
}

$id_tipo_documento = $_GET['tipo_documento'];
$id_indexador = $_GET['indexador'];
$id_formato_indexador = $_GET['formato_indexador'];
$valor = $_GET['valor'];


//Verifica o Formato do Indexador e Remove a Máscara do Valor
$RG_CPF_CNPJ_PLACA = array("4","5","6","7");
if (in_array($id_formato_indexador, $RG_CPF_CNPJ_PLACA))
{
	$valor = limpaCPF_CNPJ($valor);
}

mysqli_query($conn,"SET NAMES 'utf8';");

//echo($valor);

//VERIFICA SE O FILTRO SELECIONADO É POR DATA DO UPLOAD E MONTA A QUERY
if($id_indexador == 'dt_upload')
{
	$valor = formataData($valor);
	$query = "SELECT 
				documento.id_documento,
				processo.cd_protocolo_processo,
				carteira.ds_carteira,
			    documento.cpf,
			    documento.cnpj,
			    interessado.nm_interessado,
			    documento_tipo.nm_documento_tipo,
			    documento.dt_upload,
			    posto_graduacao.nm_posto_graduacao,
	            login.nm_guerra,
			    documento.path,
			    documento.nm_arquivo,
			    documento.nm_extensao
			FROM documento
			LEFT JOIN processo on processo.id_processo = documento.id_processo
			LEFT JOIN interessado on interessado.cpf_interessado = documento.cpf OR interessado.cnpj_interessado = documento.cnpj
			INNER JOIN documento_tipo on documento_tipo.id_documento_tipo = documento.id_documento_tipo
			INNER JOIN documento_valor on documento_valor.id_documento = documento.id_documento
			LEFT JOIN carteira on carteira.id_carteira = documento.id_carteira
			INNER JOIN login on login.id_login = documento.id_login
	        INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE
			documento.dt_upload > '$valor 00:00:01' and documento.dt_upload < '$valor 23:59:59'
			group by documento.id_documento
			order by documento.id_documento DESC
	";
}
else
//MONTA A QUERY DE ACORDO COM O FILTRO ESCOLHIDO
{
	$query = "SELECT 
				documento.id_documento,
				processo.cd_protocolo_processo,
				carteira.ds_carteira,
			    documento.cpf,
			    documento.cnpj,
			    interessado.nm_interessado,
			    documento_tipo.nm_documento_tipo,
			    documento.dt_upload,
			    posto_graduacao.nm_posto_graduacao,
	            login.nm_guerra,
			    documento.path,
			    documento.nm_arquivo,
			    documento.nm_extensao
			FROM documento
			LEFT JOIN processo on processo.id_processo = documento.id_processo
			LEFT JOIN interessado on interessado.cpf_interessado = documento.cpf OR interessado.cnpj_interessado = documento.cnpj
			INNER JOIN documento_tipo on documento_tipo.id_documento_tipo = documento.id_documento_tipo
			INNER JOIN documento_valor on documento_valor.id_documento = documento.id_documento
			LEFT JOIN carteira on carteira.id_carteira = documento.id_carteira
			INNER JOIN login on login.id_login = documento.id_login
	        INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE
			documento_valor.id_documento_indexador = $id_indexador AND
			documento_valor.valor = '$valor' AND documento.id_documento_tipo = $id_tipo_documento
			order by documento.id_documento DESC
	";
}

//echo($query);

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_docs' name='tb_docs' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th width=110>
								<center><font size=2>CPF/CNPJ</font></center>
							</th>

							<th>
								<center><font size=2>SISPROT</font></center>
							</th>

							<th>
								<center><font size=2>TIPO DO DOCUMENTO</font></center>
							</th>

							<th>
								<center><font size=2>CARTEIRA</font></center>
							</th>

							<th>
								<center><font size=2>REQUERENTE</font></center>
							</th>
							
							<th>
								<center><font size=2>PUBLICADO POR</font></center>
							</th>

							<th>
								<center><font size=2>DATA IMPORTAÇÃO</font></center>
							</th>

							<th>
								<center><font size=2>ABRIR</font></center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	$cd_protocolo_processo = $linha['cd_protocolo_processo'];
	  	$ds_carteira = $linha['ds_carteira'];
	  	$cpf = $linha['cpf'];
	  	$cnpj = $linha['cnpj'];
		$nm_interessado = $linha['nm_interessado'];
		$nm_documento_tipo = $linha['nm_documento_tipo'];
		$dt_upload = $linha['dt_upload'];
		$nm_guerra = $linha['nm_posto_graduacao'] . " " . $linha['nm_guerra'];
		$path = $tempDir . $linha['path'] . "/" . $linha['nm_arquivo'] . "." .  $linha['nm_extensao'];

		$cpf_cnpj = null;
		if($cpf != "null")
		{
			$cpf_cnpj = formataCPF($cpf);
		}
		else
		{
			$cpf_cnpj = formataCNPJ($cnpj);
		}

		if($cd_protocolo_processo == "")
		{
			$cd_protocolo_processo = "NÃO VINCULADO";	
		}

		if($ds_carteira == "")
		{
			$ds_carteira = "NÃO INFORMADA";	
		}		

		if($nm_interessado == "")
		{
			$nm_interessado = "NÃO VINCULADO SISPROT";
		}

		echo("

				<tr class='active'>
					
					<td>
						<b><center><font size=2 color='black'>". $cpf_cnpj ."</font></center></b>
					</td>

					<td>
			");
		if($cd_protocolo_processo != "NÃO VINCULADO")
		{
			echo("<a href=\"javascript:exibeDetalhesProcesso('$cd_protocolo_processo')\"\><center><b><font color=\"green\"> ".$cd_protocolo_processo."</font></b></center></a>");
		}
		else
		{
			echo("<center><font size=2>". $cd_protocolo_processo. "</font></center>");
		}
						
						
		echo("	
					</td>

					<td>
						<center><font size=2>".$nm_documento_tipo."</font></center>
					</td>

					<td>
						<center><font size=2>". $ds_carteira. "</font></center>
					</td>

					<td>
						<center><font size=2>".$nm_interessado."</font></center>
					</td>

					<td>
						<center><font size=2>".$nm_guerra."</font></center>
					</td>

					<td width='150'>
						<center><font size=2>".date('d/m/Y H:i', strtotime($dt_upload))."</font></center>
					</td>

					<td>
						<center><a href='".$path."' target='_blank'><img src='img/icone_pdf.png' class='img' width='20'></a></center>
					</td>
			");

		echo("</tr>");
	    	

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
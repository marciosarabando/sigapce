<?php
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
//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$nr_unidade = $_SESSION['nr_unidade_sfpc'];
$id_unidade = $_SESSION['id_unidade_sfpc'];

//RECEBE VALORES PELO GET
$id_interessado = $_GET['id_interessado'];
$nm_procurador = $_GET['nm_procurador'];
$id_servicos_selecionados = $_GET['id_servicos_selecionados'];
$id_carteira = $_GET['id_carteira'];
$txt_observacao = mb_strtoupper($_GET['txt_observacao'],'UTF-8');
$grus_processo = $_GET['grus_processo'];

//echo($grus_processo);

//Dados Processo
$id_processo = null;
date_default_timezone_set('America/Sao_Paulo');
$dt_abertura_processo = date('Y-m-d H:i');
$id_processo_status = 1;
$dt_processo_andamento = date('Y-m-d H:i');
$obs_processo_andamento = null;
$id_procurador = null;
$cpf = null;
$cnpj = null;

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

if($nm_procurador != null)
{
	//DESCOBRE O ID DO PROCURADOR
	$query = "SELECT id_procurador FROM procurador WHERE nm_procurador = '$nm_procurador'";
	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	if($totalLinhas > 0)
	{
	do
		  {
		  		$id_procurador = $linha['id_procurador'];
		  }while($linha = mysqli_fetch_assoc($dados));
		  mysqli_free_result($dados);
	}
}

if($id_procurador == null)
{
	$query = "INSERT INTO processo VALUES (null,$id_interessado,$id_unidade,null,$id_carteira,null,'$dt_abertura_processo',null)";	
}
else
{
	$query = "INSERT INTO processo VALUES (null,$id_interessado,$id_unidade,$id_procurador,$id_carteira,null,'$dt_abertura_processo',null)";	
}

mysqli_query($conn,$query) or die(mysql_error());

//Recupera ID do Processo
$query = "SELECT max(id_processo) AS id_processo FROM processo WHERE id_interessado = $id_interessado AND id_unidade = $id_unidade";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do
	  {
	  		$id_processo = $linha['id_processo'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

//Insere os Serviços Selecionados
$query = "SELECT id_servico FROM servico WHERE id_servico in ($id_servicos_selecionados)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do
	  {	  		
	  		$query = "INSERT INTO processo_servico VALUES ($id_processo,".$linha['id_servico'].")";
			mysqli_query($conn,$query) or die(mysql_error());
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}


//Insere o Andamento do Processo
$query = "INSERT INTO processo_andamento VALUES (null,$id_processo,$id_login,$id_processo_status,'$dt_processo_andamento','$txt_observacao')";
mysqli_query($conn,$query) or die(mysql_error());

$ano_atual = date('Y');
$data_inicio_ano = $ano_atual;
$data_inicio_ano .= "-01-01";
$data_final_ano = $ano_atual;
$data_final_ano .= "-12-31";

$qtd_processos_ano = null;
//Busca a quantidade de Registros do Ano para Criar o NUP
$query = "SELECT count(id_processo) AS qtd_processos_ano FROM processo WHERE id_unidade = $id_unidade AND dt_abertura_processo BETWEEN '$data_inicio_ano' AND '$data_final_ano'";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do
	  {
	  		$qtd_processos_ano = $linha['qtd_processos_ano'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}


//Monta o NUP
$NUP = $nr_unidade;
$NUP .= $qtd_processos_ano;
$NUP .= date('Y');

//INSERE O NUP NO PROCESSO
$query = "UPDATE processo SET cd_protocolo_processo = '$NUP' WHERE id_processo = $id_processo";
mysqli_query($conn,$query) or die(mysql_error());

//Recupera Informacoes do Processo para Exibir os Dados do Protocolo Gerado
$cpf = null;
$cnpj = null;
$nm_interessado = null;
$nm_procurador = null;
$nm_unidade = null;

//Preenche o combo com os serviços relacionados a carteira selecionada.
//Conecta no Banco de Dados
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "SELECT cd_protocolo_processo AS CD_PROTOCOLO,
		dt_abertura_processo AS DATA_PROTOCOLO,
		interessado.cpf_interessado AS CPF,
		interessado.cnpj_interessado AS CNPJ,
		interessado.nm_interessado AS NM_INTERESSADO,
		procurador.nm_procurador AS NM_PROCURADOR,
		unidade.nm_unidade AS NM_UNIDADE
FROM processo
INNER JOIN interessado ON processo.id_interessado = interessado.id_interessado
LEFT JOIN procurador ON processo.id_procurador = procurador.id_procurador
INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
WHERE id_processo = $id_processo";
// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	do{
			if($linha['CPF'] != null)
			{
				$cpf = $linha['CPF'];
			}
			else
			{
				$cnpj = $linha['CNPJ'];
			}
			$nm_interessado = $linha['NM_INTERESSADO'];
			$nm_procurador = $linha['NM_PROCURADOR'];
			$nm_unidade = $linha['NM_UNIDADE'];
		}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
}


$query = "SELECT 
					id_servico as id_servico,
					ds_servico as ds_servico
		 FROM servico WHERE id_servico in (" . $id_servicos_selecionados . ")";
// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

// include ("../funcoes/qrcode.php");

// if($cpf !=null)
// 	gerarQRCODE($NUP, $cpf, "cpf");
// else
// 	gerarQRCODE($NUP, $cnpj, "cnpj");

//gerarQRCODE($NUP, $cpf_cnpj);

$urlQRCODE = "protocolo/qrcode/".$NUP.".png"; 
$urlBRASAO2RM = "img/brasao2rm.png"; 

if($grus_processo != "0")
{
	//INSERE AS GRUS ASSOCIADAS AO PROCESSO
	$mais_de_uma_gru = strripos($grus_processo, "-");

	if($mais_de_uma_gru)
	{
		$linhas_grus = split ("-",$grus_processo);
		for($x = 0; $x < count($linhas_grus); $x++)
		{
			$linha_gru = split (';',$linhas_grus[$x]);	
		
			$vl_referencia = $linha_gru[0];
			$vl_competencia = $linha_gru[1];
			$vl_valor = $linha_gru[2];
			$vl_autenticacao = $linha_gru[3];

			//INSERE A GRU NO PROCESSO
			$query = "INSERT INTO gru VALUES (null, $vl_referencia,'$vl_competencia', $vl_valor, '$vl_autenticacao')";
			if (mysqli_query($conn,$query) or die(mysql_error()))
			{
				//ID DO REGISTRO INSERIDO
	   			$id_gru = mysqli_insert_id($conn);
	   			$query = "INSERT INTO gru_processo VALUES ($id_gru, $id_processo)";
	   			mysqli_query($conn,$query) or die(mysql_error());
			}
		}
	}
	else
	{
		$linha_gru = split (';',$grus_processo);	
		$vl_referencia = $linha_gru[0];
		$vl_competencia = $linha_gru[1];
		$vl_valor = $linha_gru[2];
		$vl_autenticacao = $linha_gru[3];
		//INSERE A GRU NO PROCESSO
		$query = "INSERT INTO gru VALUES (null, $vl_referencia,'$vl_competencia', $vl_valor, '$vl_autenticacao')";
		if (mysqli_query($conn,$query) or die(mysql_error()))
		{
			//ID DO REGISTRO INSERIDO
			$id_gru = mysqli_insert_id($conn);
			$query = "INSERT INTO gru_processo VALUES ($id_gru, $id_processo)";
			mysqli_query($conn,$query) or die(mysql_error());
		}
	}
}




echo (
		"		
		<div class='container' id='div_protocolo'>
			<div class='row'>
				<div class='col-md-12'>
		
					
					
					<table class='table table-bordered'>
						<tr>
							<td colspan=2>
								<center>
									<img src=" .$urlBRASAO2RM. " height='200'/>
									<h4>SISTEMA DE PROTOCOLO ELETRÔNICO SFPC/2</h4>
								</center>
							</td>
						<tr>

						<tr>
							<td valign='middle'>
								<label>PROTOCOLO:</label>
							</td>
							<td>
								<font color='green' size='5'><b>". $NUP ."</b></font>
							</td>
						<tr>

						<tr>
							<td>
								<label>DATA/HORA:</label> 
							</td>
							<td>
								<label>". date('d/m/Y H:i', strtotime($dt_abertura_processo)) ." h</label> 
							</td>
						<tr>

						<tr>
							<td>

								<label>UNIDADE SFPC:</label> 
							</td>
							<td>
								<label>". $nm_unidade ."</label> 
							</td>
						<tr>
			");

			echo("       
						<tr>
							<td>
								<label>SOLICITAÇÃO:</label> 
							</td>
							<td>
								
								
					");

			if($totalLinhas > 0)
			{
				do{
					echo("<label>" . $linha['ds_servico'] . "</label><br>");			
				}while($linha = mysqli_fetch_assoc($dados));
				mysqli_free_result($dados);
			}

			echo("</td></tr>");


			echo("
						

						<tr>
							<td>
								<label>REQUERENTE:</label> 
							</td>
							<td>
								<label>". $nm_interessado ."</label> 
							</td>
						<tr>
						
					");
			if($cpf != null)
			{
				echo("<tr>
						<td>
							<label>CPF</label>
						</td>
						<td>
							<label>". formataCPF($cpf) ."</label> 
						</td>
					  <tr>
				 ");
			}
			else
			{
				echo("<tr>
						<td>
							<label>CNPJ</label>
						</td>
						<td>
							<label>". formataCNPJ($cnpj) ."</label> 
						</td>
					  <tr>
				 ");
			}



			if($nm_procurador != null)
			{
				echo("
						<tr>	
							<td>
								<label>PROCURAÇÃO?</label> 
							</td>
							<td>
								<label>SIM</label> 
							</td>
						<tr>
						<tr>
							<td>
								<label>PROCURADOR:</label> 
							</td>
							<td>
								<label>". $nm_procurador ."</label> 
							</td>
							
						<tr>
					");
			}
			else
			{
				echo("
						<tr>
							<td>
								<label>PROCURAÇÃO?</label> 
							</td>
							<td>
								<label>NÃO</label> 
							</td>
						<tr>
					");
			}

			echo("
						<tr>
							<td>
								<!-- <center><img src=" .$urlQRCODE. " /></center> -->
							</td>
							<td>
								<h5>Para consultar o andamento de seu processo acesse</h5> 
								<h5>protocolosfpc.2rm.eb.mil.br</h5>
								<h5>e entre com o seu CPF/CNPJ e o nº do protocolo.</h5>
							</td>
						<tr>
					");

			echo("</table><br>");
			//echo("</div>");

echo("</div></div></div>");

echo("<button type='button' onclick='imprimirProtocolo()' class='btn btn-default'><i class='glyphicon glyphicon-print'></i> IMPRIMIR</button>");



?>

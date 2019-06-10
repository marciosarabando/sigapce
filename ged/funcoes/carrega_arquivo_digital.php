<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
$data_hora_atual = date('YmdHis');


//DEFINE O DIRETÓRIO DESTINO DA GRAVAÇÃO DAS IMAGENS
$path_arq_ged = "digitalizacoes/ged";


//PEGA OS VALORES PELO GET
$cpf_cnpj = $_GET['cpf_cnpj'];
$sg_documento_tipo = $_GET['sg_documento_tipo'];
$arquivo = $_GET['id_nm_arquivo'];
$id_carteira = $_GET['id_carteira'];
$id_servico = $_GET['id_servico'];
$id_documento_tipo = split (';',$_GET['id_documento_tipo']);
$id_vl_indexadores = split (';',$_GET['id_vl_indexadores']);
$tipo_pessoa = $_GET['tipo_pessoa'];


//DEFINE O NOME E CAMINHO DE GRAVAÇÃO DO ARQUIVO ENVIADO
$path_arq = getcwd();
$path_arq = substr($path_arq, 0, -7);
$path_arq .= $path_arq_ged . "/";

$nm_arquivo = $cpf_cnpj;
$nm_arquivo .= "_";
$nm_arquivo .= $sg_documento_tipo;
$nm_arquivo .= "_";
$nm_arquivo .= $data_hora_atual;

//$path_arq .= $nm_arquivo . "." . $extensao;

//SE O ARQUIVO FOI RECEBIDO NO SERVIDOR COPIA PARA O DIRETÓRIO DESTINO
if (isset($_FILES[$arquivo])) 
{
    //echo "Arquivo recebido no servidor!";
    //NOME TEMPORÁRIO
	$file_tmp = $_FILES[$arquivo]["tmp_name"];
	 //NOME DO ARQUIVO NO COMPUTADOR
	$file_name = $_FILES[$arquivo]["name"];
	//TAMANHO DO ARQUIVO
	$file_size = $_FILES[$arquivo]["size"];
	//MIME DO ARQUIVO
	$file_type = $_FILES[$arquivo]["type"];

	list ($tipo, $extensao) = split ('[/.-]', $file_type);

	//DEFINE O NOME COM A EXTENSÃO DO ARQUIVO CARREGADO
	$path_arq .= $nm_arquivo . "." . $extensao;

	//REALIZA A CÓPIA DO DIRETÓRIO TEMPORÁRIO PARA O DESTINO
	copy($file_tmp, $path_arq);	
}
else
{  
 	echo "<b><font size=2>FALHA - Arquivo não carregado no servidor </font></b><br>";    
}


//SE O ARQUIVO FOI COPIADO COM SUCESSO GRAVA OS DADOS NA BASE
if (file_exists($path_arq) && isset($_FILES[$arquivo])) 
{
	$dados_arquivo = $nm_arquivo . "," . $extensao . "," . $file_size . "," . $path_arq_ged;
    insereDadosDocumentoImportado($dados_arquivo, $tipo_pessoa, $cpf_cnpj, $id_carteira, $id_servico, $id_documento_tipo, $id_vl_indexadores);

} 
else 
{
    echo "<b><font size=2>FALHA - Os dados do arquivo não foram gravados na base de dados</font></b>";
}

function insereDadosDocumentoImportado($dados_arquivo, $tipo_pessoa, $cpf_cnpj, $id_carteira, $id_servico, $id_documento_tipo, $id_vl_indexadores)
{
	include ("../../funcoes/conexao.php");
	mysqli_query($conn,"SET NAMES 'utf8';"); 
	date_default_timezone_set('America/Sao_Paulo');
	$data_hora_atual = date('YmdHis');

	//VERIFICA O AMBIENTE E PEGA O CAMINHO ONDE SERAO GRAVADAS AS IMAGENS
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
	//PEGA O ID LOGIN DO MILITAR LOGADO
	$id_login = $_SESSION['id_login_sfpc'];

	$cpf = "null";
	$cnpj = "null";
	if($tipo_pessoa == "PF")
	{
		$cpf = $cpf_cnpj;
	}
	else
	{
		$cnpj = $cpf_cnpj;
	}

	if($id_servico == "0")
	{
		$id_servico = "null";
	}

	$dados_arquivo = split (',',$dados_arquivo);
	$nm_arquivo = $dados_arquivo[0];
	$nm_extensao = $dados_arquivo[1];
	$vl_tamanho = $dados_arquivo[2];
	$path = $dados_arquivo[3];


	//Verifica se o Protocolo Informado existe no SISPROT e pega o ID do Processo para vincular ao documento inserido
	$cd_protocolo_processo_sisprot = "";
	$id_processo = null;
	for($x = 0; $x < count($id_vl_indexadores); $x++)
	{
		$id_vl_indexador = split ('vl_index',$id_vl_indexadores[$x]);
		$id_indexador = $id_vl_indexador[0];
		$valor = $id_vl_indexador[1];

		if($id_indexador == 1)
		{
			$cd_protocolo_processo_sisprot = $valor;
		}
	}
	if($cd_protocolo_processo_sisprot != "")
	{
		$query = "SELECT id_processo FROM processo WHERE cd_protocolo_processo = '$cd_protocolo_processo_sisprot'";
		// // executa a query
		$dados = mysqli_query($conn,$query) or die(mysql_error());
		// transforma os dados em um array
		$linha = mysqli_fetch_assoc($dados);
		// calcula quantos dados retornaram
		$totalLinhas = mysqli_num_rows($dados);
		if($totalLinhas > 0)
		{
		 do{
		 		$id_processo = $linha['id_processo'];	
	 		}while($linha = mysqli_fetch_assoc($dados));
			mysqli_free_result($dados);
		}
	}


	//INSERE OS DADOS DO DOCUMENTO IMPORTADO
	if($id_processo != null)
	{
		$query = "INSERT INTO documento VALUES (null,$id_documento_tipo[0],$id_login,$id_processo,$id_carteira,$id_servico,'$cpf','$cnpj','$nm_arquivo','$nm_extensao',$vl_tamanho,'$path','$data_hora_atual')";
	}
	else
	{
		$query = "INSERT INTO documento VALUES (null,$id_documento_tipo[0],$id_login,null,$id_carteira,$id_servico,'$cpf','$cnpj','$nm_arquivo','$nm_extensao',$vl_tamanho,'$path','$data_hora_atual')";
	}

	if (mysqli_query($conn,$query) or die(mysql_error()))
	{
		//ID DO REGISTRO INSERIDO
		$id_documento = mysqli_insert_id($conn);
		if($tipo_pessoa == "PF")
		{
			$query = "INSERT into documento_valor VALUES ($id_documento,3,'$cpf')";
		}
		else
		{
			$query = "INSERT into documento_valor VALUES ($id_documento,4,'$cnpj')";	
		}
		mysqli_query($conn,$query) or die(mysql_error());
		
		for($x = 0; $x < count($id_vl_indexadores); $x++)
		{
			$id_vl_indexador = split ('vl_index',$id_vl_indexadores[$x]);
			$id_indexador = $id_vl_indexador[0];
			$valor = $id_vl_indexador[1];
			$valor = strtoupper($valor);

			if($id_indexador == 1 && $valor != "")
			{
				$query = "INSERT into documento_valor VALUES ($id_documento,$id_indexador,'$valor')";
				mysqli_query($conn,$query) or die(mysql_error());
				//$query = "UPDATE documento SET id_processo = '$valor' WHERE id_documento = $id_documento";
				//mysqli_query($conn,$query) or die(mysql_error());
			}
			else if($valor != "")
			{
				$query = "INSERT into documento_valor VALUES ($id_documento,$id_indexador,'$valor')";
				mysqli_query($conn,$query) or die(mysql_error());
			}

		}
		echo("
				<table>
					<tr>
						<td width='25'>
							<font color='green'><h5><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span></h5></font>
						</td>
						<td>
							<font color='blue'><a href='" . $tempDir . "digitalizacoes/ged/$nm_arquivo.$nm_extensao' target='blank' title='Visualizar'><h5><span class='glyphicon glyphicon-download-alt' aria-hidden='true'></span></h5></a></font>
						</td>
					</tr>
				</table>
			");
		//echo("<font color='blue'><a href='" . $tempDir . "digitalizacoes/ged/$nm_arquivo.$nm_extensao' target='blank' title='Visualizar'><h5><span class='glyphicon glyphicon-download-alt' aria-hidden='true'></span></h5></a></font>");
	}
	else
	{
		echo("<font color='red'><h5><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span></h5></font>");	
	}

}

?>
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

$cpf_cnpj = $_GET['cpf_cnpj'];

$cpf_cnpj = limpaCPF_CNPJ($cpf_cnpj);

$id_interessado = null;
$sg_tipo_interessado = null;
$ds_tipo_interessado = null;
$nm_interessado = null;
$cidade_interessado = null;
$uf_cidade = null;
$cpf_interessado = null;
$cnpj_interessado = null;
$cr_interessado = null;
$tr_interessado = null;
$nr_tel_interessado = null;
$email_interessado = null;
$interessadoPJ = null;

if(strlen($cpf_cnpj) > 11)
{
	$interessadoPJ = "PJ";
}

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "SELECT 	
				interessado.id_interessado AS ID_INTERESSADO,
				interessado.sg_tipo_interessado AS SG_TP_INTERESSADO,
				ds_tipo_interessado AS TP_INTERESSADO,
				nm_interessado AS NOME, 
		        cidade.nm_cidade AS CIDADE,
		        cidade.uf_cidade AS UF,
				cpf_interessado AS CPF, 
		        cnpj_interessado AS CNPJ,
		        cr_interessado AS CR,
		        tr_interessado AS TR,
		        nr_tel_interessado AS FONE, 
		        email_interessado AS EMAIL 
			FROM interessado 
			INNER JOIN cidade on cidade.id_cidade = interessado.id_cidade
			INNER JOIN tipo_interessado on tipo_interessado.sg_tipo_interessado = interessado.sg_tipo_interessado
			WHERE cpf_interessado = '$cpf_cnpj' OR cnpj_interessado = '$cpf_cnpj'";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	  {
	  	$id_interessado = $linha['ID_INTERESSADO'];
	  	$sg_tipo_interessado = $linha['SG_TP_INTERESSADO'];
		$ds_tipo_interessado = $linha['TP_INTERESSADO'];
		$nm_interessado = $linha['NOME'];
		$cidade_interessado = $linha['CIDADE'];
		$uf_cidade = $linha['UF'];
		$cpf_interessado = $linha['CPF'];
		$cnpj_interessado = $linha['CNPJ'];
		$cr_interessado = $linha['CR'];
		$tr_interessado = $linha['TR'];
		$nr_tel_interessado = $linha['FONE'];
		$email_interessado = $linha['EMAIL'];
		
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

echo(
"
<input id='id_interessado' value='" . $id_interessado . "' hidden></input>
<fieldset>

			<legend>Dados do Interessado</legend>
			<div id='div_msg_interessado_localizado' class='alert alert-success' role='alert'><i class='glyphicon glyphicon-info-sign'></i> Clique em CONFIRMAR para continuar ou ALTERAR para alterar o cadastro do Interessado.</div>
			<div class='table-responsive'>
  				<table class='table table-condensed table-bordered'>
");
if($cpf_interessado != null)
{
	echo("
					<tr>
						<td>
							<label>CPF:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cpf_interessado' name='cpf_interessado' placeholder='Digite o CPF' value='". $cpf_interessado ."' disabled>
						</td>
					<tr>
		");
}
if($cnpj_interessado != null)
{
	echo("

					<tr>
						<td>
							<label>CNPJ:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cnpj_interessado' name='cnpj_interessado' placeholder='Digite o CNPJ' value='". $cnpj_interessado ."' disabled>  
						</td>
					<tr>
		");
}					
echo(
"
					<tr>
						<td>
							<label>TIPO INTERESSADO:</label> 
						</td>
						<td>
							<select id='cmb_tipoInteressado' name='cmb_tipoInteressado' class='form-control' disabled>
");
							//Preenche o combo Tipo Interessado
							//Conecta no Banco de Dados
							include ("../funcoes/conexao.php");
							mysqli_query($conn,"SET NAMES 'utf8';");
							$query = "SELECT 
												sg_tipo_interessado as sg_tipo_interessado,
												ds_tipo_interessado as ds_tipo_interessado												
									 FROM tipo_interessado order by 1 desc";
							// executa a query
							$dados = mysqli_query($conn,$query) or die(mysql_error());
							// transforma os dados em um array
							$linha = mysqli_fetch_assoc($dados);
							// calcula quantos dados retornaram
							$totalLinhas = mysqli_num_rows($dados);

							if($totalLinhas > 0)
							{
								echo("<option value='0'>SELECIONE...</option>");
								do{
									if($interessadoPJ == "PJ")
									{
										echo("<option value='PJ' selected>PESSOA JURÍDICA</option>");
									}
									else if($linha['sg_tipo_interessado'] != "PJ")
									{
										if($sg_tipo_interessado == $linha['sg_tipo_interessado'])
										{
											echo("<option value='". $linha['sg_tipo_interessado'] . "' selected>" . $linha['ds_tipo_interessado'] . "</option>");
										}
										else
										{
											echo("<option value='". $linha['sg_tipo_interessado'] . "'>" . $linha['ds_tipo_interessado'] . "</option>");
										}
									}
								}while($linha = mysqli_fetch_assoc($dados));
								mysqli_free_result($dados);
							}
echo(" 
							</select>
						</td>
					<tr>
					<tr>
						<td>
							<label>NOME:</label> 
						</td>
						<td>
							<input type='text' class='upper form-control' id='nm_interessado' name='nm_interessado' maxlength=60 placeholder='Digite o Nome do Interessado' value='". $nm_interessado ."' disabled> 
						</td>
					<tr>
");

echo("
					<tr>
						<td>
							<label>CIDADE:</label> 
						</td>
						<td>
					<select id='cmb_cidade' name='cmb_cidade' class='form-control' disabled>
	");

			 		//Preenche o combo cidade
					//Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT 
										id_cidade as id_cidade,
										uf_cidade as uf_cidade,
										nm_cidade as nm_cidade
							 FROM cidade";
					// executa a query
					$dados = mysqli_query($conn,$query) or die(mysql_error());
					// transforma os dados em um array
					$linha = mysqli_fetch_assoc($dados);
					// calcula quantos dados retornaram
					$totalLinhas = mysqli_num_rows($dados);

					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							
							if($cidade_interessado == $linha['nm_cidade'])
							{
								echo("<option value='". $linha['id_cidade'] . "' selected>" . $linha['nm_cidade'] . " - " . $linha['uf_cidade'] . "</option>");
							}
							else
							{
								echo("<option value='". $linha['id_cidade'] . "'>" . $linha['nm_cidade'] . " - " . $linha['uf_cidade'] . "</option>");
							}

						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}

				

echo("
						</select>
						</td>
					<tr>
	");
if($sg_tipo_interessado == 'PJ')
{
	echo("
					<tr>
						<td>
							<label>CR:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cr_interessado' name='cr_interessado' maxlength=20 placeholder='Digite o CR (Caso Possua)' onkeypress='return SomenteNumero(event)' value='". $cr_interessado ."' disabled>  
						</td> 
					<tr>
					<tr>
						<td>
							<label>Cod Rastreabilidade (TR):</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='tr_interessado' name='tr_interessado' placeholder='Digite o TR (Caso Possua)' maxlength=20 value='". $tr_interessado ."' disabled>  
						</td>
					<tr>
		");
}
else
{
	echo("
					<tr>
						<td>
							<label>CR:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cr_interessado' maxlength=20 name='cr_interessado' placeholder='Digite o CR' onkeypress='return SomenteNumero(event)' value='". $cr_interessado ."' disabled>  
						</td>
					<tr>
		");
}
echo(" 
					<tr>
						<td>
							<label>CONTATO:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='nr_tel_interessado' name='nr_tel_interessado' placeholder='Digite o Telefone com DDD' value='". $nr_tel_interessado ."' disabled>  
						</td>
					<tr>
					<tr>
						<td>
							<label>E-MAIL:</label> 
						</td>
						<td>
							<input type='text' class='lower form-control' id='email_interessado' name='email_interessado' maxlength=40 placeholder='Digite o e-mail' value='". $email_interessado ."' disabled>  
						</td>
					<tr>

				</table>
			</div>
			<div id='div_btn_confirmaCadastro'>
				<button type='button' onClick='confirmaCadastroInteressado()' class='btn btn-success'><i class='glyphicon glyphicon-ok'></i> CONFIRMAR</button>
				<button type='button' id='btn_alterarDadosInteressado' onClick='liberaCamposInteressadoAlteracao()' class='btn btn-primary'><i class='glyphicon glyphicon-pencil'></i> ALTERAR</button>
			</div>

			<div id='mensagem_cadastro_atualizado' hidden>
				<div class=\"system-message message-(warning|success|info|busy) [fade in]\">
				  <p><div class=\"alert alert-success\">
				  <img width=\"20\" height=\"20\" src=\"img/ico_sucesso.gif\" class=\"img-polaroid\"> Cadastro Confirmado</a>
				</div></p>
				</div>
			</div>
</fieldset>

"
);

}

//MONTA TELA DE CADASTRO DO INTERESSADO
//Se não encontrou os dados do Interessado Abre os Campos para Digitação
else
{
echo("
		<fieldset>

			<legend>Dados do Interessado</legend>
			<div class='alert alert-info' role='alert'><center><b>INTERESSADO NÃO ENCONTRADO NO SISTEMA!</b><br> REALIZE O CADASTRO PARA CONTINUAR.</center></div>
			<div class='table-responsive'>
  				<table class='table table-condensed table-bordered'>
  	");
					


if($interessadoPJ != "PJ")
{
	echo("
					<tr>
						<td>
							<label>CPF:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cpf_interessado' name='cpf_interessado' placeholder='Digite o CPF' value='".$cpf_cnpj."' disabled>
						</td>
					<tr>
		");
}
else
{
	echo("

					<tr>
						<td>
							<label>CNPJ:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cnpj_interessado' name='cnpj_interessado' placeholder='Digite o CNPJ'  value='".$cpf_cnpj."' disabled>  
						</td>
					<tr>
		");
}

echo("				
					<tr>
						<td>
							<label>TIPO INTERESSADO:</label> 
						</td>
						<td>
							
");
							if($interessadoPJ == "PJ")
							{
								echo("<select id='cmb_tipoInteressado' name='cmb_tipoInteressado' class='form-control' disabled>");
							}
							else
							{
								echo("<select id='cmb_tipoInteressado' name='cmb_tipoInteressado' class='form-control'>");
							}
							//Preenche o combo Tipo Interessado
							//Conecta no Banco de Dados
							include ("../funcoes/conexao.php");
							mysqli_query($conn,"SET NAMES 'utf8';");
							$query = "SELECT 
												sg_tipo_interessado as sg_tipo_interessado,
												ds_tipo_interessado as ds_tipo_interessado												
									 FROM tipo_interessado order by 1 desc";
							// executa a query
							$dados = mysqli_query($conn,$query) or die(mysql_error());
							// transforma os dados em um array
							$linha = mysqli_fetch_assoc($dados);
							// calcula quantos dados retornaram
							$totalLinhas = mysqli_num_rows($dados);

							if($totalLinhas > 0)
							{
								echo("<option value='0'>SELECIONE...</option>");
								do{
									if($interessadoPJ == "PJ")
									{
										echo("<option value='PJ' selected>PESSOA JURÍDICA</option>");
									}
									else
									{
										if($linha['sg_tipo_interessado'] != "PJ")
										{
											echo("<option value='". $linha['sg_tipo_interessado'] . "'>" . $linha['ds_tipo_interessado'] . "</option>");
										}
									}
								}while($linha = mysqli_fetch_assoc($dados));
								mysqli_free_result($dados);
							}
echo(" 
							</select>
						</td>
					<tr>
");



echo("
					<tr>
						<td>
							<label>NOME:</label> 
						</td>
						<td>
							<input type='text' class='upper form-control' id='nm_interessado' name='nm_interessado' maxlength=60 placeholder='NOME ou RAZÃO SOCIAL'> 
						</td>
					<tr>

					<tr>
						<td>
							<label>CIDADE:</label> 
						</td>
						<td>
					<select id='cmb_cidade' name='cmb_cidade' class='form-control'>
	");

			 		//Preenche o combo cidade
					//Conecta no Banco de Dados
					include ("../funcoes/conexao.php");
					mysqli_query($conn,"SET NAMES 'utf8';");
					$query = "SELECT 
										id_cidade as id_cidade,
										uf_cidade as uf_cidade,
										nm_cidade as nm_cidade
							 FROM cidade";
					// executa a query
					$dados = mysqli_query($conn,$query) or die(mysql_error());
					// transforma os dados em um array
					$linha = mysqli_fetch_assoc($dados);
					// calcula quantos dados retornaram
					$totalLinhas = mysqli_num_rows($dados);

					if($totalLinhas > 0)
					{
						echo("<option value='0'>SELECIONE...</option>");
						do{
							echo("<option value='". $linha['id_cidade'] . "'>" . $linha['nm_cidade'] . " - " . $linha['uf_cidade'] . "</option>");
						}while($linha = mysqli_fetch_assoc($dados));
						mysqli_free_result($dados);
					}

echo("
						</select>
						</td>
					<tr>
	");
if($interessadoPJ == "PJ")
{
	echo("
					<tr>
						<td>
							<label>CR:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cr_interessado' name='cr_interessado' maxlength=20 placeholder='Digite o CR (Caso Possua)' onkeypress='return SomenteNumero(event)'>  
						</td>
					<tr>
					<tr>
						<td>
							<label>Cod Rastreabilidade (TR):</label> 
						</td>
						<td> 
							<input type='text' class='form-control' id='tr_interessado' name='tr_interessado' maxlength=20 placeholder='Digite o TR (Caso Possua)'>  
						</td>
					<tr>
		");
}
else
{
	echo("
					<tr>
						<td>
							<label>CR:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='cr_interessado' name='cr_interessado' maxlength=20 placeholder='Digite o CR (Caso Possua)' onkeypress='return SomenteNumero(event)'>  
						</td>
					<tr>
		");
}
echo(" 
					<tr>
						<td>
							<label>CONTATO:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='nr_tel_interessado' name='nr_tel_interessado' placeholder='Digite o Telefone com DDD'>  
						</td>
					<tr>
					<tr>
						<td>
							<label>E-MAIL:</label> 
						</td>
						<td>
							<input type='text' class='lower form-control' id='email_interessado' name='email_interessado' placeholder='E-mail de contato do interessado'>  
						</td>
					<tr>

				</table>
			</div>
			<div id='div_btn_salvaDadosInteressado'>
				<button type='button' onClick='gravarDadosInteressado(\"incluir\")' class='btn btn-success'><i class='glyphicon glyphicon-floppy-disk'></i> Salvar Dados do Interessado</button>
			</div>

</fieldset>

"
);
}


?>
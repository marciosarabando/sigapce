<?php
include ("../funcoes/verificaAtenticacao.php");

function mask($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
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

$cd_protocolo = $_GET['cd_protocolo'];
$urlQRCODE = "protocolo/qrcode/".$cd_protocolo.".png"; 
$pathQRCODE = getcwd()."/qrcode/".$cd_protocolo.".png";
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "SELECT	
				processo.id_processo,
				processo.cd_protocolo_processo,
				processo.dt_abertura_processo,
		        interessado.nm_interessado,
		        interessado.cpf_interessado,
		        interessado.cnpj_interessado,
		        interessado.nr_tel_interessado,
		        interessado.email_interessado,
		        processo_status.id_processo_status,
		        processo_status.ds_processo_status,
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_guerra,
		        carteira.ds_carteira,
		        servico.ds_servico,
		        unidade.nm_unidade,
		        procurador.nm_procurador,
                cidade.nm_cidade,
                cidade.uf_cidade,
                max(documento.id_documento) as id_documento,
                documento.path,
                documento.nm_arquivo,
                documento.nm_extensao
                
		FROM 
		processo
		INNER JOIN processo_andamento on processo_andamento.id_processo = processo.id_processo
		
		
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		INNER JOIN login on login.id_login = processo_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
		INNER JOIN carteira on carteira.id_carteira = processo.id_carteira
		INNER JOIN unidade on unidade.id_unidade = processo.id_unidade
        INNER JOIN cidade on interessado.id_cidade = cidade.id_cidade
        INNER JOIN processo_servico on processo_servico.id_processo = processo.id_processo
        INNER JOIN servico on servico.id_servico = processo_servico.id_servico
		LEFT JOIN procurador on procurador.id_procurador = processo.id_procurador
		LEFT JOIN documento on documento.id_processo = processo.id_processo
		LEFT JOIN documento_tipo on documento.id_documento_tipo = documento_tipo.id_documento_tipo
		WHERE 
		processo_andamento.id_processo_andamento IN (SELECT max(processo_andamento.id_processo_andamento) FROM processo_andamento WHERE processo_andamento.id_processo = processo.id_processo)
		AND processo.cd_protocolo_processo = '". $cd_protocolo ."'
		";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{

	
	do
	  {
	  	$id_processo = $linha['id_processo'];
		$cd_protocolo_processo = $linha['cd_protocolo_processo'];
		$dt_abertura_processo = date('d/m/Y H:i', strtotime($linha['dt_abertura_processo']));
		$nm_interessado = $linha['nm_interessado'];
		$cpf_interessado = $linha['cpf_interessado'];
		$cnpj_interessado = $linha['cnpj_interessado'];
		$cidade_interessado = $linha['nm_cidade'];
		$cidade_interessado .= " - ";
		$cidade_interessado .= $linha['uf_cidade'];
		$nr_tel_interessado = $linha['nr_tel_interessado'];
		$email_interessado = $linha['email_interessado'];
		$id_processo_status = $linha['id_processo_status'];
		$status_processo = $linha['ds_processo_status'];
		$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		$nm_guerra = $linha['nm_guerra'];
		$ds_carteira = $linha['ds_carteira'];
		$ds_servico = $linha['ds_servico'];
		$nm_unidade = $linha['nm_unidade'];
		$nm_procurador = $linha['nm_procurador'];
		$id_documento = $linha['id_documento'];
		$path_arquivo_digital = $tempDir . $linha['path'] . "/" . $linha['nm_arquivo'] . "." .  $linha['nm_extensao'];
		
		
		//Coloca Alert na Cor conforme o Status

		$info = array("1","2","5");
		$warning = array("3","4","16");
		$danger = array("7");
		$success = array("6","8","9","10","11","12","13","14","15");
		$pronto = array("8");
		
		if (in_array($id_processo_status, $info)) { 
		    echo "<div class='alert alert-info' role='alert'>";
		}
		
		else if (in_array($id_processo_status, $warning)) { 
		    echo "<div class='alert alert-warning' role='alert'>";
		}

		else if (in_array($id_processo_status, $danger)) { 
		    echo "<div class='alert alert-danger' role='alert'>";
		}
		
		else if (in_array($id_processo_status, $success)) { 
		    echo "<div class='alert alert-success' role='alert'>";
		}

		if (in_array($id_processo_status, $pronto))
		{
			echo("
						<table>
							<tr>
								");
								//echo($pathQRCODE);
								if (file_exists($pathQRCODE)) 
								{ 
									    echo("	<td>
													<img src=" .$urlQRCODE. " />
												</td>
											");
								}

			echo("				
								<td width='80%'>
									<h3>PROTOCOLO Nº: <label>". $cd_protocolo_processo ."</label></h3>
									<h4><label>". $status_processo ."</label></h4>
								</td>
								<td vAlign='bottom' Align='right'>
								
									<!-- Button trigger modal ALTERA STATUS-->
									<center>
									<button type='button' id='btn_entregar_processo' onclick='' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModalEntregaProcesso'>
									  <i class='glyphicon glyphicon-cog'></i> ENTREGAR PROCESSO PRONTO
									</button>
									</center>
								
								</td>
							</tr>
						</table>
					</div>
				");
			echo("
					<!-- Modal -->
					<div class='modal fade' id='myModalEntregaProcesso' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
					  <div class='modal-dialog' role='document'>
					    <div class='modal-content'>
					      <div class='modal-header'>
					        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
					        <h4 class='modal-title' id='myModalLabel'>
					        	<b><i class='glyphicon glyphicon-check'></i>ENTREGA DE PROCESSO PRONTO</b>
						        <br>
						        PROTOCOLO: <font color='green'><b>$cd_protocolo_processo</b></font>
						        <input type=hidden id='txt_id_processo' name='txt_id_processo' value='$id_processo'></input>
						        <input type=hidden id='txt_cd_protocolo_processo' name='txt_cd_protocolo_processo' value='$cd_protocolo'></input>
					        </h4>
					      </div>
					      <div class='modal-body'>
					      		<table class='table table-condensed table-bordered'>
									<tr>
										<td>
											<label>REQUERENTE:</label> 
										</td>
										<td>
											<label>". $nm_interessado ."</label> 
										</td>
									<tr>
									<tr>
										<td>
											<label>SOLICITAÇÃO:</label> 
										</td>
										<td>
											<label>". $ds_servico ."</label> 
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
					        <button type='button' name='btn_entregar_processo_confirma' onClick='entregarProcessoProntoDetalhes()' class='btn btn-success' data-dismiss='modal'>CONFIRMAR ENTREGA</button>
					      </div>
					     </div>
					  </div>
					</div>
					");
		}
		else
		{
			echo("
							<table>
								<tr>");
									if (file_exists($pathQRCODE)) 
									{ 
										    echo("	<td>
														<img src=" .$urlQRCODE. " />
													</td>
												");
									}
									
									if ($id_documento > 0)
									{
											echo("	<td>
														<div class='alert alert-info' role='alert'>
															<center><a href='".$path_arquivo_digital."' target='_blank'><img src='img/icone_pdf.png' class='img' width='80'></a></center>    
															<p>
															<label>PROCESSO DIGITAL</label>
														</div>
													</td>
												");
									} 
									
			echo("
									<td>
										<h3>PROTOCOLO Nº: <label>". $cd_protocolo_processo ."</label></h3>
										<h4><label>". $status_processo ."</label></h4>
									</td>

				");





			echo("
								</tr>
							</table>
					</div>
				");
		}



		echo("
				<div class='row'>
					<div class='col-md-6'>
						<fieldset>
							<legend>&nbspDADOS DA SOLICITAÇÃO</legend>
							
							
							<table class='table table-condensed'>

								<tr>
									<td class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
										<label>OM SFPC:</label>
									</td>
									<td>
										<label>" . $nm_unidade . "</label>
									</td>
								</tr>
	
								<tr>
									<td class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
										<label>CARTEIRA:</label>
									</td>
									<td>
										<label>" . $ds_carteira . "</label>
									</td>
								</tr>

								

								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>INTERESSADO:</label>
									</td>
									<td>
										<label>" . $nm_interessado . "</label>
									</td>
								</tr>
			");
			if($cpf_interessado != null)
			{
				echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>CPF:</label>
									</td>
									<td>
										<label>" . formataCPF($cpf_interessado) . "</label>
									</td>
								</tr>
					");
			}
			if($cnpj_interessado != null)
			{
				echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>CNPJ:</label>
									</td>
									<td>
										<label>" . formataCNPJ($cnpj_interessado) . "</label>
									</td>
								</tr>
					");
			}

			echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>CIDADE:</label>
									</td>
									<td>
										<label>". $cidade_interessado ."</label>
									</td>
								</tr>
				");

			echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>CONTATO:</label>
									</td>
									<td>
										<label>". $nr_tel_interessado ."</label>
									</td>
								</tr>
				");

			echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>E-MAIL:</label>
									</td>
									<td>
										<label>". $email_interessado ."</label>
									</td>
								</tr>
				");



			echo("
								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>PROCURADOR / REPRESENTANTE:</label>
									</td>
									<td>
				");
			if($nm_procurador != null)
			{
				echo("<label>" . $nm_procurador . "</label>");
			}
			else
			{
				echo("<label>NÃO</label>");
			}
			echo("
									</td>
								</tr>");

			echo("

								<tr>
									<td class='col-xs-2 col-sm-3 col-md-2 col-lg-2'>
										<label>PROTOCOLADO EM:</label>
									</td>
									<td>
										<label>" . $dt_abertura_processo . "</label>
									</td>
								</tr>
							</table>

						</fieldset>
					</div>

				");
}while($linha = mysqli_fetch_assoc($dados));
 mysqli_free_result($dados);
}

//PREENCHE O SERVIÇO SOLICITADO
echo("
		<div class='col-md-6'>
			<div class='row'>
			<div class='col-md-12'>
			<fieldset>
					<legend>SERVIÇOS SOLICITADOS</legend>
					
						<table class='table' align='left' >
	");

$query = "SELECT 
					servico.ds_servico
			FROM processo_servico
			INNER JOIN servico on servico.id_servico = processo_servico.id_servico
			WHERE processo_servico.id_processo = $id_processo";
		
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado

if($totalLinhas > 0)
{
echo("
					
					<tbody>
");
	
	do
	  {
	  	$ds_servico = $linha['ds_servico'];
	  

	
	  		echo("<tr class='active'>");


	  	echo("
	  				<td>
	  					". $ds_servico ."
	  				</td>
	  			
	  			</tr>


	  		");

	  
	   }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);


	echo("		

					</tbody>
				</table>
			</fieldset>
			</div>
			</div>
		");

//CASO EXISTA, EXIBE AS GRUS ASSOCIADAS AO PROCESSO
$query = "SELECT 
				gru.id_gru,
				gru.nr_referencia,
				gru.competencia,
				gru.valor,
				gru.nr_autenticacao
			FROM gru
			INNER JOIN gru_processo on gru_processo.id_gru = gru.id_gru
			WHERE gru_processo.id_processo = $id_processo";	
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	echo ("<div class='row'>
			<div class='col-md-12'>
			<fieldset>
					<legend>GRU DO PROCESSO</legend>					
						<table class='table' align='left' >
						<thead>
							<th>
								REFERÊNCIA
							</th>
							<th>
								COMPETÊNCIA
							</th>
							<th>
								VALOR
							</th>
							<th>
								AUTENTICAÇÃO
							</th>
						</thead>

						<tbody>
			
		");
	do
	{
		$id_gru = $linha['id_gru'];
		$nr_referencia = $linha['nr_referencia'];
		$competencia = $linha['competencia'];
		$valor = $linha['valor'];
		$nr_autenticacao = $linha['nr_autenticacao'];
		$nr_autenticacao = mask($nr_autenticacao, '#.###.###.###.###.###');

		echo("
				<tr>
					<td>
						$nr_referencia
					</td>
					<td>
						$competencia
					</td>
					<td>
						R$ $valor
					</td>
					<td>
						$nr_autenticacao
					</td>
				</tr>
			");

	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
	
	echo ("
			</tbody>
			</table>
			</fieldset>		
			</div>
			</div>");
}


	//FECHA DIV DA COLUNA
	echo("</div>");	

	//FECHA DIV DA LINHA
	echo("</div>");



//PREENCHE DADOS DO ANDAMENTO DO PROCESSO
echo("
		<div class='row'>
		<div class='col-md-12'>
			<fieldset>
					<legend>&nbspANDAMENTO DO PROCESSO</legend>
					
						<table class='table' align='left' >
	");

$query = "SELECT 
					processo_andamento.dt_processo_andamento,
			        processo_status.nm_processo_status,
			        posto_graduacao.nm_posto_graduacao,
			        login.nm_guerra,
			        processo_andamento.obs_processo_andamento
			FROM processo_andamento
			INNER JOIN processo_status on processo_status.id_processo_status = processo_andamento.id_processo_status
			INNER JOIN login on login.id_login = processo_andamento.id_login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE processo_andamento.id_processo = $id_processo";
		
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
$repeticao = 1;
if($totalLinhas > 0)
{
echo("
					<thead>
						<tr>
							<th>
								ESTADO
							</th>
							<th>
								RESPONSÁVEL
							</th>

							<th>
								DATA DO REGISTRO
							</th>
							<th>
								OBSERVAÇÃO
							</th>
						</tr>
					</thead>
					<tbody>
");
	
	do
	  {
	  	$data_processo_andamento = date('d/m/Y H:i', strtotime($linha['dt_processo_andamento']));
	  	$status_processo_andamento = $linha['nm_processo_status'];
	  	$operador =  $linha['nm_posto_graduacao'];
	  	$operador .= " ";
	  	$operador .=  $linha['nm_guerra'];
	  	$obs_processo_andamento = $linha['obs_processo_andamento'];

	  	if($repeticao == $totalLinhas)
	  	{
	  		echo("<tr class='success'>");
	  	}
	  	else
	  	{
	  		echo("<tr class='active'>");
	  	}


	  	echo("
	  				<td>
	  					". $status_processo_andamento ."
	  				</td>
	  				<td>
	  					". $operador ."
	  				</td>
	  				<td>
	  					". $data_processo_andamento ."
	  				</td>
	  				<td>
	  					". $obs_processo_andamento ."
	  				</td>
	  			</tr>



	  		");

	  	$repeticao++;
	   }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);


	echo("		

					</tbody>
				</table>
			</fieldset>
		");

	//FECHA DIV DA COLUNA
	echo("</div>");	

	//FECHA DIV DA LINHA
	echo("</div>");

}


//PREENCHE AS NOTAS INFORMATIVAS DO PROCESSO
echo("
		<div class='row'>
			<div class='col-md-12'>
				<fieldset>
						<legend>&nbspNOTAS INFORMATIVAS DO PROCESSO</legend>
						<table class='table' align='left' >
	");

$query = "SELECT
					nota_informativa.id_processo,
			        posto_graduacao.nm_posto_graduacao,
			        login.nm_guerra,
			        nota_informativa.dt_nota_informativa,
			        nota_informativa.ds_nota_informativa,
			        nota_informativa.st_ciente_nota_informativa,
			        nota_informativa.dt_ciente_nota_informativa,
			        nota_informativa.ip_ciente_nota_informativa
			FROM 
					nota_informativa
			INNER JOIN login on nota_informativa.id_login = login.id_login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE nota_informativa.id_processo = $id_processo";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
echo("
					<thead>
						<tr>
							<th>
								DATA DA NOTA
							</th>
							<th>
								CRIADA POR
							</th>
							<th>
								MENSAGEM
							</th>
							<th>
								SITUAÇÃO
							</th>
						</tr>
					</thead>
					<tbody>
");


do
	  {
	  	$dt_nota_informativa = date('d/m/Y H:i', strtotime($linha['dt_nota_informativa']));
	  	$operador =  $linha['nm_posto_graduacao'];
	  	$operador .= " ";
	  	$operador .=  $linha['nm_guerra'];
	  	$ds_nota_informativa = $linha['ds_nota_informativa'];
	  	$st_ciente_nota_informativa =  $linha['st_ciente_nota_informativa'];
	  	$dt_ciente_nota_informativa = date('d/m/Y H:i', strtotime($linha['dt_ciente_nota_informativa']));
	  	$ip_ciente_nota_informativa = $linha['ip_ciente_nota_informativa'];

	  	echo("
	  			<tr class='active'>
	  				
	  				<td>
	  					". $dt_nota_informativa ."
	  				</td>
	  				<td>
	  					". $operador ."
	  				</td>
	  				<td>
	  					". $ds_nota_informativa ."
	  				</td>
	  				
	  		");
	  	
	  	if($st_ciente_nota_informativa == 1)
	  	{
	  		echo("
	  				<td>
	  					<b><font color=\"green\">CIENTE em ". $dt_ciente_nota_informativa ." - IP: ". $ip_ciente_nota_informativa ."</font></b>
	  				</td>
	  		");
	  	}
	  	else
	  	{
	  		echo("
	  				<td>
	  					<b><font color=\"red\">NÃO LIDA</font></b>
	  				</td>
	  		");
	  	}


	  	echo("
	  			</tr>
	  		");
	  		


	   }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);


	echo("			

							</tbody>
						</table>
					</fieldset>
				</div>
			</div>
		");

}
else
{
	echo("
			<p class='text-info'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> Não existem Notas Informativas neste processo</p>
		");
}
	
//FECHA DIV DO PAINEL
echo("</div>");	
}


?>
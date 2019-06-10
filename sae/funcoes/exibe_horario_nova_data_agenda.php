<?php
//Exibe horários para criação da Agenda de Atendimento
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("../../funcoes/parametro.php");
include ("formata_dados.php");

if (!isset($_SESSION)) 
{
  session_start();
}
if(isset($_SESSION['id_login_sfpc']))
{
  	$id_unidade_sfpc = $_SESSION['id_unidade_sfpc'];
  	$ambiente = $_SESSION['ambiente'];
	if($ambiente == "DESENV")
	{
		$path_sae = $_SESSION['url_sae_foto_local'];
	}
	else if($ambiente == "HOM")
	{
		$path_sae = $_SESSION['url_sae_foto_hom'];
	}
	else if($ambiente == "PROD")
	{
		$path_sae = $_SESSION['url_sae_foto_prd'];
	}
}


$path_sae .= "foto_usuario/";

mysqli_query($conn,"SET NAMES 'utf8';");

$data_selecionada = $_GET['data'];
$hr_inicio_manha = $_GET['hr_inicio_manha'];
$hr_fim_manha = $_GET['hr_fim_manha'];
$hr_inicio_tarde = $_GET['hr_inicio_tarde'];
$hr_fim_tarde = $_GET['hr_fim_tarde'];
$duracao_atendimento = $_GET['duracao_atendimento'];
$qtd_atendente = $_GET['qtd_atendente'];
$id_unidade = $_GET['id_unidade'];

//Verifica se a Data Escolhida é maio do que hoje
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i');

//CARREGA OS TIPOS DE ASSUNTOS PARA AGENDAMENTO
$array_assuntos = array();
$query = "SELECT id_agendamento_assunto, nm_agendamento_assunto FROM agendamento_assunto";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	{
	$id_agendamento_assunto = $linha['id_agendamento_assunto'];
	$nm_agendamento_assunto = $linha['nm_agendamento_assunto'];
	$array_assuntos[$id_agendamento_assunto] = $nm_agendamento_assunto;
	
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
}


//Verifica se a Data Selecionada já possui Janelas de Agendamento e se Está Liberada
$query = "SELECT id_agendamento_data, st_agendamento FROM agendamento_data WHERE dt_agendamento = '$data_selecionada 00:00:00' AND unidade_id_unidade = $id_unidade";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

$janela_criada = 0;
$id_agendamento_data = 0;
$st_agendamento = 0;
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	
	do
	  {
	  	$janela_criada = 1;
	  	$id_agendamento_data = $linha['id_agendamento_data'];
	  	$st_agendamento = $linha['st_agendamento'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  
	
}

//SE A JANELA JÁ ESTIVER LIBERADA VERIFICA SE POSSUI ALGUM USUÁRIO AGENDADO
$possui_usuario_agendado = 0;
$possui_usuario_agendado_manha = 0;
$possui_usuario_agendado_tarde = 0;
$query = "SELECT count(agendamento_requerente.id_agendamento_requerente) as qtd_usuarios_agendado
			FROM agendamento_requerente
			INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
			INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			WHERE agendamento_data.dt_agendamento = '$data_selecionada 00:00:00'
			AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) < '2016-01-01 12:01'
			AND agendamento_data.unidade_id_unidade = $id_unidade";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['qtd_usuarios_agendado'] > 0)
	  	{
	  		$possui_usuario_agendado = 1;
	  		$possui_usuario_agendado_manha = 1;
	  	}
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

$query = "SELECT count(agendamento_requerente.id_agendamento_requerente) as qtd_usuarios_agendado
			FROM agendamento_requerente
			INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
			INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			WHERE agendamento_data.dt_agendamento = '$data_selecionada 00:00:00'
			AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) > '2016-01-01 12:01'
			AND agendamento_data.unidade_id_unidade = $id_unidade";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['qtd_usuarios_agendado'] > 0)
	  	{
	  		$possui_usuario_agendado = 1;
	  		$possui_usuario_agendado_tarde = 1;
	  	}
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

//VERIFICA SE A JANELA ESTÁ LOTADA
$janela_lotada = 0;
$query = "SELECT count(dt_agendamento) as lotada
			FROM agendamento_data 
			WHERE unidade_id_unidade = $id_unidade
			AND agendamento_data.dt_agendamento = '$data_selecionada 00:00:00'
			AND agendamento_data.st_agendamento = 1
			AND agendamento_data.id_agendamento_data IN (SELECT id_agendamento_data FROM agendamento_horario WHERE id_agendamento_data = agendamento_data.id_agendamento_data AND qt_requerente_agendado = qt_max_agendamento_horario)
			AND agendamento_data.id_agendamento_data NOT IN (SELECT id_agendamento_data FROM agendamento_horario WHERE id_agendamento_data = agendamento_data.id_agendamento_data AND qt_requerente_agendado < qt_max_agendamento_horario)
		  ";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['lotada'] > 0)
	  	{
	  		$janela_lotada = 1;
	  	}
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

$data_selecionada_agenda = $data_selecionada ." 23:59";

if($data_selecionada_agenda > $hoje)
{

//Hora de Início Manhã em Data/Hora
$data_hora_selecionada_inicio_manha = $data_selecionada;
$data_hora_selecionada_inicio_manha .= " ";
$data_hora_selecionada_inicio_manha .= $hr_inicio_manha;
$data_hora_selecionada_inicio_manha = new DateTime($data_hora_selecionada_inicio_manha);

//Hora de Fim Manhã em Data/Hora
$data_hora_selecionada_fim_manha = $data_selecionada;
$data_hora_selecionada_fim_manha .= " ";
$data_hora_selecionada_fim_manha .= $hr_fim_manha;
$data_hora_selecionada_fim_manha = new DateTime($data_hora_selecionada_fim_manha);

//Hora de Início Tarde em Data/Hora
$data_hora_selecionada_inicio_tarde = $data_selecionada;
$data_hora_selecionada_inicio_tarde .= " ";
$data_hora_selecionada_inicio_tarde .= $hr_inicio_tarde;
$data_hora_selecionada_inicio_tarde = new DateTime($data_hora_selecionada_inicio_tarde);

//Hora de Fim Tarde em Data/Hora
$data_hora_selecionada_fim_tarde = $data_selecionada;
$data_hora_selecionada_fim_tarde .= " ";
$data_hora_selecionada_fim_tarde .= $hr_fim_tarde;
$data_hora_selecionada_fim_tarde = new DateTime($data_hora_selecionada_fim_tarde);

// Resgata diferença entre as datas Manha
$qtd_horas_atendimento_manha = $data_hora_selecionada_inicio_manha->diff($data_hora_selecionada_fim_manha);

// Resgata diferença entre as datas Tarde
$qtd_horas_atendimento_tarde = $data_hora_selecionada_inicio_tarde->diff($data_hora_selecionada_fim_tarde);


//Monta o DateInterval
$date_interval = "PT";
$date_interval .= $duracao_atendimento;
$date_interval .= "M";

//Pega a quantidade de Hora/Minutos do expediente da Manhã
$qtd_hora_manha = $qtd_horas_atendimento_manha->format('%h');
$qtd_min_manha = $qtd_horas_atendimento_manha->format('%i');

//Pega a quantidade de Hora/Minutos do expediente da Tarde
$qtd_hora_tarde = $qtd_horas_atendimento_tarde->format('%h');
$qtd_min_tarde = $qtd_horas_atendimento_tarde->format('%i');

//Calcula quantos Horarios de Atendimento serão gerados de acordo com a Duracao do Atendimento (Manhã)
$qtd_horario_atendimento_manha = intval((($qtd_hora_manha * 60) + $qtd_min_manha) / $duracao_atendimento);

//Calcula quantos Horarios de Atendimento serão gerados de acordo com a Duracao do Atendimento (Tarde)
$qtd_horario_atendimento_tarde = intval((($qtd_hora_tarde * 60) + $qtd_min_tarde) / $duracao_atendimento);


$hora_manha = $data_hora_selecionada_inicio_manha;
$hora_tarde = $data_hora_selecionada_inicio_tarde;

$data_escolhida = formataDataDDMMYYYY($data_selecionada);

$total_atendimento_dia = ($qtd_horario_atendimento_manha + $qtd_horario_atendimento_tarde) * $qtd_atendente;

$data_por_extenso = retornaDataExtenso($data_selecionada);


//CARREGA QUANTIDADE DE ATENDENTES DEFINIDA POR PADRÃO DA UNIDADE
$valor_qtd_atendente_parametro = retornaValorParametro($id_unidade_sfpc,2,'cmb_qtd_atendente');
$array_qtd_atendente = array("1","2","3","4","5","6","7","8","9","10");

//CARREGA QUANTIDADE MAX DE PROTOCOLO DEFINIDA POR PADRÃO DA UNIDADE
$valor_qtd_max_protocolo_horario_parametro = retornaValorParametro($id_unidade_sfpc,2,'cmb_qtd_max_protocolo_horario');
$array_qtd_max_protocolo_horario = array("1","2","3","4","5","6","7","8","9","10");


if($janela_criada == 0)
{
	echo("<div class='panel panel-default'>");
}
else
{
	if($st_agendamento == 0)
	{
		echo("<div class='panel panel-primary'>");
	}
	else
	{
		if($possui_usuario_agendado == 0)
		{
			echo("<div class='panel panel-success'>");
		}
		else if ($janela_lotada == 0)
		{
			echo("<div class='panel panel-warning'>");
		}
		else
		{
			echo("<div class='panel panel-danger'>");
		}
	}
}

echo("

		  <div class='panel-heading'>
		  	<table>
		  		<tr>
		  			<td width=60%>
					    <h2><b>$data_escolhida<b></h2>
					    <h5>$data_por_extenso<h5>
					    
					</td>
					<td align=right valign=center>
	");
			if($janela_criada == 0)
			{
				echo("<h4><span class='label label-primary'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> INCLUSÃO DE NOVA JANELA</span></h4>");
			}
			else
			{
				if($st_agendamento == 0)
				{
					echo("<h4><span class='label label-warning'><span class='glyphicon glyphicon-pause' aria-hidden='true'></span> AGUARDANDO LIBERAÇÃO</span></h4>");
				}
				else
				{
					if($possui_usuario_agendado == 0)
					{
						echo("<h4><span class='label label-success'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> AGENDAMENTO LIBERADO</span></h4>");	
					}
					else if ($janela_lotada == 0)
					{
						echo("<h4><span class='label label-warning'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> JANELA COM USUÁRIO AGENDADO</span></h4>");		
					}
					else
					{
						echo("<h4><span class='label label-danger'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> JANELA COM HORÁRIOS ESGOTADOS</span></h4>");		
					}
				}
			}

					    
echo("				</td>
				</tr>
	");


	echo("
				
		    </table>
		  </div>
		  <div class='panel-body'>
		 ");
  
	echo("
		  	<div class='row'>
		  		<div class='col-md-12'>
		  			
		  			<table class='table table-condensed'>
		  				<tr>
		  					<td>
					  			<table class='table table-condensed'>
					  				<thead>
										<tr bgcolor='#F5F5F5'>
											
											<th colspan=2>
												<center><h5><b>MANHÃ</b></h5></center>
											</th>
											<th>
												<center><h6>Nº DE ATENDENTE</h6></center>
											</th>
											<th>
												<center><h6>Nº MAX DE PROTOCOLO</h6></center>
											</th>
											<th>
												<h6>TIPO DE USUÁRIO PERMITIDO</h6>
											</th>
											<th>
												<h6>TIPO DE ASSUNTO PERMITIDO</h6>
											</th>

										</tr>
									</thead>
									<tbody>
	");
if($janela_criada == 0)
{
						for ($x = 1; $x <= $qtd_horario_atendimento_manha; $x++) 
						{
						    if($x == 1)
						    {
						    	$valor_hora_manha = $hora_manha->format('H:i');
						    	echo("
						    			<tr>
											<td width='10'>
												<center><input type='checkbox' name='chk_horas' value='$valor_hora_manha' checked></input></center>
											</td>

											<td width='70'>
												<b>
												<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
									");
													echo($hora_manha->format('H:i'));
								echo("
												</b>
											</td>
									");

								//COMBO QTD MAX DE ATENDENTE NO HORARIO
								echo("
											<td width='10'>
												<center>
													<select id='cmb_qtd_atendente_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_manha->format('Hi') . "' onchange=''>
									");

													for($h = 0; $h < count($array_qtd_atendente); $h++)
									                {
									                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
									                    {
									                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
									                    }
									                    else
									                    {
									                        echo("<option>".$array_qtd_atendente[$h]."</option>");
									                    }

									                }
								echo("
													</select>
												</center>
											</td>
									");

								

								//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
								echo("
											<td width='10'>
												<center>
													<select id='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "'>
									");

													for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
									                {
									                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
									                    {
									                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
									                    }
									                    else
									                    {
									                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
									                    }

									                }
								echo("
													</select>
												</center>
											</td>
									");

								//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
								echo("
											<td width='10'>
												
													<select id='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "'>
														<option value='0' selected>TODOS</option>
														<option value='1'>PRÓPRIO INTERESSADO</option>
														<option value='2'>PROCURADOR DESPACHANTE</option>
														<option value='3'>EMPRESA ATIVIDADE PCE</option>
													</select>
												
											</td>
									");

								//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
								echo("
											<td width='10'>
												
												<!-- Button trigger modal -->
												<center>
												<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_manha->format('Hi') ."'>
												  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
												</button>

												</center>

												<!-- Modal -->
												<div class='modal fade' id='myModalAssuntos". $hora_manha->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
												  <div class='modal-dialog' role='document'>
												    <div class='modal-content'>
												      <div class='modal-header'>
												        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_manha->format('H:i') ." </h4>
												      </div>
												      
												      <div class='modal-body'>

												      		<div class='panel panel-default'>
															  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
															  	<div class='panel-body'>
												      		");

															foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
															{	
																//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
																echo("
															  		<input type='checkbox' name='chk_assunto_" . $hora_manha->format('Hi') . "' value='" . $id_agendamento_assunto . "' onClick='validaSelecaoAssunto(\"chk_assunto_" . $hora_manha->format('Hi') . "\")' checked></input>
															  		<label>" . $nm_agendamento_assunto . "</label>
															  		<br>
																");
															}
												      		echo("
												      			</div>
															</div>
													  </div>

												      <div class='modal-footer'>
												        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
												        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
												      </div>
												     </div>
												  </div>
												</div>


												
											</td>
									");


								echo("
										</tr>
						    		");
						    }
						    else
							{
								$hora_manha = $hora_manha->add(new DateInterval($date_interval));
								$valor_hora_manha = $hora_manha->format('H:i');
								echo("
						    			<tr>
											<td width='10'>
												<center><input type='checkbox' name='chk_horas' value='$valor_hora_manha' checked></center>
											</td>
											<td width='70'>
												<b>
												<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
									");
													echo($hora_manha->format('H:i'));
								echo("
												</b>
											</td>
									");

								//COMBO QTD MAX DE ATENDENTE NO HORARIO
								echo("
											<td width='10'>
												<center>
													<select id='cmb_qtd_atendente_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_manha->format('Hi') . "' onchange=''>
									");

													for($h = 0; $h < count($array_qtd_atendente); $h++)
									                {
									                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
									                    {
									                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
									                    }
									                    else
									                    {
									                        echo("<option>".$array_qtd_atendente[$h]."</option>");
									                    }

									                }
								echo("
													</select>
												</center>
											</td>
									");

								
											
								//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
								echo("
											<td width='10'>
												<center>
													<select id='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "'>
									");

													for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
									                {
									                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
									                    {
									                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
									                    }
									                    else
									                    {
									                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
									                    }

									                }
								echo("
													</select>
												</center>
											</td>
									");

								//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
								echo("
											<td width='10'>
												
													<select id='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "'>
														<option value='0' selected>TODOS</option>
														<option value='1'>PRÓPRIO INTERESSADO</option>
														<option value='2'>PROCURADOR DESPACHANTE</option>
														<option value='3'>EMPRESA ATIVIDADE PCE</option>
													</select>
												
											</td>
									");

								//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
								echo("
											<td width='180'>
												<!-- Button trigger modal -->
												<center>
												<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_manha->format('Hi') ."'>
												  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
												</button>
												</center>

												<!-- Modal -->
												<div class='modal fade' id='myModalAssuntos". $hora_manha->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
												  <div class='modal-dialog' role='document'>
												    <div class='modal-content'>
												      <div class='modal-header'>
												        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_manha->format('H:i') ." </h4>
												      </div>
												      
												      <div class='modal-body'>

												      		<div class='panel panel-default'>
															  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
															  	<div class='panel-body'>
												      		");

															foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
															{	
																//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
																echo("
															  		<input type='checkbox' name='chk_assunto_" . $hora_manha->format('Hi') . "' value='" . $id_agendamento_assunto . "' onClick='validaSelecaoAssunto(\"chk_assunto_" . $hora_manha->format('Hi') . "\")' checked></input>
															  		<label>" . $nm_agendamento_assunto . "</label>
															  		<br>
																");
															}
												      		echo("
												      			</div>
															</div>
													  </div>

												      <div class='modal-footer'>
												        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
												        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
												      </div>
												     </div>
												  </div>
												</div>
											</td>
									");


								echo("
										</tr>
						    		");

							}
						}

}
//Busca os Horários DA MANHÃ Criados para a Data Selecionada
else
{
	$query = "SELECT 
				count(agendamento_horario.id_agendamento_horario) as qt_usuario_agendado_hr,
				agendamento_requerente.id_agendamento_login,
				agendamento_data.id_agendamento_data,
				agendamento_horario.id_agendamento_horario,
			    agendamento_horario.hr_agendamento_horario,
			    agendamento_horario.qt_max_agendamento_horario,
			    agendamento_horario.qt_requerente_agendado,
			    posto_graduacao.nm_posto_graduacao,
			    login.nm_guerra,
			    agendamento_horario.qt_max_protocolo_horario
			FROM agendamento_horario
			LEFT JOIN agendamento_requerente on agendamento_requerente.id_agendamento_horario = agendamento_horario.id_agendamento_horario
			INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			INNER JOIN login on login.id_login = agendamento_data.id_login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE agendamento_data.dt_agendamento = '$data_selecionada 00:00:00' AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) < '2016-01-01 12:01' AND unidade_id_unidade = $id_unidade
			group by agendamento_horario.id_agendamento_horario";

	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	$qtd_total_horarios = $totalLinhas;
	
	if($totalLinhas > 0)
	{
		
		do
		  {
		  	$horario_lotado_manha = 0;
		  	$id_agendamento_login = $linha['id_agendamento_login'];
		  	$qt_usuario_agendado_hr = $linha['qt_usuario_agendado_hr'];
		  	$id_agendamento_horario = $linha['id_agendamento_horario'];
		  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
		  	$qt_max_agendamento_horario = $linha['qt_max_agendamento_horario'];
		  	$qt_max_protocolo_horario = $linha['qt_max_protocolo_horario'];
		  	$qt_requerente_agendado = $linha['qt_requerente_agendado'];
		  	$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		  	$nm_guerra = $linha['nm_guerra'];
		  	$login_criador_janela = $nm_posto_graduacao;
		  	$login_criador_janela .= " ";
		  	$login_criador_janela .= $nm_guerra;

		  	//BUSCA OS TIPOS DE USUÁRIOS PERMITIDOS SE AGENDAR NO HORÁRIO
		  	$query_busca_tipos_usuarios_permitidos_horario = "SELECT 
				agendamento_login_tipo.nm_agendamento_login_tipo
				FROM agendamento_horario
				INNER JOIN agendamento_tipo_usuario_horario on agendamento_tipo_usuario_horario.id_agendamento_horario = agendamento_horario.id_agendamento_horario
				INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_tipo_usuario_horario.id_agendamento_login_tipo
				WHERE agendamento_horario.id_agendamento_horario = $id_agendamento_horario
				";

			$dados_tipos_usuario = mysqli_query($conn,$query_busca_tipos_usuarios_permitidos_horario) or die(mysql_error());
			$linha_tipos_usuario = mysqli_fetch_assoc($dados_tipos_usuario);
			// calcula quantos dados retornaram
			$totalLinhas_tipo_usuario = mysqli_num_rows($dados_tipos_usuario);
			
			$tipo_usuario_permitido_no_horario = "";

			if($totalLinhas_tipo_usuario > 0)
			{
				
				do
				  {				  	
				  	$nm_agendamento_login_tipo = $linha_tipos_usuario['nm_agendamento_login_tipo'];

				  	if($tipo_usuario_permitido_no_horario == "")
				  	{
				  		$tipo_usuario_permitido_no_horario .= "<span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $nm_agendamento_login_tipo;
				  	}
				  	else
				  	{
				  		$tipo_usuario_permitido_no_horario .= "<br> <span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $nm_agendamento_login_tipo;
				  	}

				  }while($linha_tipos_usuario = mysqli_fetch_assoc($dados_tipos_usuario));
		  		  mysqli_free_result($dados_tipos_usuario); 
			}
			//FIM DA BUSCA DOS USUARIOS PERMITIDOS


			//BUSCA OS TIPOS DE ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
		  	$query_busca_assuntos_permitidos_horario = "SELECT 
				agendamento_assunto.nm_agendamento_assunto
			FROM agendamento_assunto_horario
			INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_assunto_horario.id_agendamento_assunto
			WHERE id_agendamento_horario = $id_agendamento_horario
				";

			$dados_tipos_assunto = mysqli_query($conn,$query_busca_assuntos_permitidos_horario) or die(mysql_error());
			$linha_tipos_assunto = mysqli_fetch_assoc($dados_tipos_assunto);
			// calcula quantos dados retornaram
			$totalLinhas_tipo_assunto = mysqli_num_rows($dados_tipos_assunto);
			
			$tipo_assunto_permitido_no_horario = "";

			if($totalLinhas_tipo_assunto > 0)
			{
				
				do
				  {				  	
				  	$nm_agendamento_assunto = $linha_tipos_assunto['nm_agendamento_assunto'];

				  	if($tipo_assunto_permitido_no_horario == "")
				  	{
				  		$tipo_assunto_permitido_no_horario .= "<span class='glyphicon glyphicon-comment' aria-hidden='true'></span> " . $nm_agendamento_assunto;
				  	}
				  	else
				  	{
				  		$tipo_assunto_permitido_no_horario .= "<br> <span class='glyphicon glyphicon-comment' aria-hidden='true'></span> " . $nm_agendamento_assunto;
				  	}

				  }while($linha_tipos_assunto = mysqli_fetch_assoc($dados_tipos_assunto));
		  		  mysqli_free_result($dados_tipos_assunto); 
			}
			//FIM DOS ASSUNTOS PERMITIDOS

			


		  	if($qt_requerente_agendado == $qt_max_agendamento_horario)
		  	{
		  		$horario_lotado_manha = 1;
		  	}

		  	if($horario_lotado_manha == 1)
		  	{
		  		//echo("<tr bgcolor='#FFA07A'>");
		  		echo("<tr>");	
		  	}
		  	else
		  	{
		  		echo("<tr>");	
		  	}

		  	echo("
						<td width='35'>
							<center>
				");


		  	//BOTAO DE EXIBICAO DOS DADOS DOS USUÁRIOS AGENDADOS
			if($id_agendamento_login <> null)
			{
				//Busca dados usuários agendados na hora
				$query_popover = "SELECT
							agendamento_assunto.nm_agendamento_assunto,
							agendamento_login.cpf_login,
							agendamento_login.nm_completo,
						    agendamento_login_tipo.nm_agendamento_login_tipo,
						    agendamento_login.nr_celular,
						    agendamento_login.email,
						    arquivo.binario,
							arquivo.tipo,
							agendamento_requerente_andamento.id_agendamento_status,
							agendamento_status.nm_status
						FROM agendamento_requerente
						INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
						INNER JOIN agendamento_status on agendamento_status.id_agendamento_status = agendamento_requerente_andamento.id_agendamento_status
						INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
						INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
						INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_requerente.id_agendamento_login
						INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
						INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_requerente.id_agendamento_assunto
						INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
						WHERE agendamento_horario.id_agendamento_horario = $id_agendamento_horario
						AND agendamento_requerente_andamento.id_agendamento_status IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
						ORDER BY agendamento_requerente.id_agendamento_requerente";
				$dados_popover = mysqli_query($conn,$query_popover) or die(mysql_error());
				$linha_popover = mysqli_fetch_assoc($dados_popover);
				// calcula quantos dados retornaram
				$totalLinhas_popover = mysqli_num_rows($dados_popover);
				if($totalLinhas_popover > 0)
				{
					
					//Botao Qtd usuarios agendados na hora
					if($horario_lotado_manha == 0)
		  			{
		  				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='exibePopover(\"$id_agendamento_horario\",\"$hr_agendamento_horario\")' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $qt_usuario_agendado_hr . " </button>");		
		  			}
		  			else
		  			{
		  				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='exibePopover(\"$id_agendamento_horario\",\"$hr_agendamento_horario\")' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $qt_usuario_agendado_hr . " </button>");	
		  			}
					

					$dados_usuarios_agendados_horario =  array();
					$x = 0;

					do
					{
						//$foto = base64_encode($linha_popover['binario']);
						$nm_agendamento_assunto = $linha_popover['nm_agendamento_assunto'];
						$id_agendamento_status = $linha_popover['id_agendamento_status'];
						$nm_agendamento_assunto = $linha_popover['nm_agendamento_assunto'];
						$nm_status = $linha_popover['nm_status'];
	  					$tipo_arquivo = $linha_popover['tipo'];
					  	$nm_completo = $linha_popover['nm_completo'];
					  	$cpf = $linha_popover['cpf_login'];
					  	$nm_agendamento_login_tipo = $linha_popover['nm_agendamento_login_tipo'];
					  	$nr_celular = $linha_popover['nr_celular'];
					  	$email = $linha_popover['email'];
					  	$foto = $path_sae;
					  	$foto .= $cpf;
					  	$foto .= ".jpg";

					  	$dados_usuarios_agendados_horario[$x] = $nm_completo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $foto;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $tipo_arquivo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_agendamento_login_tipo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nr_celular;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $email;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_agendamento_assunto;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $id_agendamento_status;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_status;
					  	
					  	$x = $x + 1;

					}while($linha_popover = mysqli_fetch_assoc($dados_popover));
		  			mysqli_free_result($dados_popover); 

		  			echo("<div id='div-popover_$id_agendamento_horario' class='hide' data-placement='left'>");

		  			for($h = 0; $h < count($dados_usuarios_agendados_horario); $h++)
	   				{
	   					$dados_usuario_agendado_horario = explode(";",$dados_usuarios_agendados_horario[$h]);
	   					echo("<div class='table-responsive'>
  								<table>
  									<tr>
  										<td colspan=2>
  											
  							");
	   									echo("<h5><b>");
					   					echo($dados_usuario_agendado_horario[6]);
					   					echo("</b></h5>");

	   									echo("<h6><b>");
					   					echo($dados_usuario_agendado_horario[0]);
					   					echo("</b></h6>");


	   						echo("		</td>
  									</tr>");


	   							echo("<tr>");
	   								echo("<td width=100>");
	   										//echo("<img id='thumbnail' src='data:".$dados_usuario_agendado_horario[2].";base64,".$dados_usuario_agendado_horario[1]."' width='100' class='img-thumbnail'>");
	   										echo("<img id='thumbnail' src='".$dados_usuario_agendado_horario[1]."' width='100' class='img-thumbnail'>");
	   										

	   								echo("</td>");

	   								echo("<td width=230>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[3]);
					   					echo("</h6>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[4]);
					   					echo("</h6>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[5]);
					   					echo("</h6>");

					   				echo("</td>");
					   			echo("</tr>");

					   			echo("<tr>");
					   				echo("<td colspan=2>");
					   					echo("<h6>");

					   					//echo("<b>SITUAÇÃO: </b>"); 

					   					if($dados_usuario_agendado_horario[7] == 1 || $dados_usuario_agendado_horario[7] == 2)
					   					{
					   						echo("<b><font color='green'>" . $dados_usuario_agendado_horario[8] . "</font></b>");
					   					}
					   					else if ($dados_usuario_agendado_horario[7] == 4 || $dados_usuario_agendado_horario[7] == 3)
					   					{
					   						echo("<b><font color='red'>" . $dados_usuario_agendado_horario[8] . "</font></b>");
					   					}
					   					
					   					echo("</h6>");
					   				echo("</td>");
					   			echo("</tr>");

					   		echo("</table>");
					   	echo("</div>");

	   					echo("<hr>");
	   					
	   				}
	   				echo("</div>");
		  			//Div do Popover com usuários agendados na hora
					//echo("<div id='div-popover_$id_agendamento_horario' class='hide' data-placement='left'>" . $dados_usuarios_agendados_horario[0] . "</div>");
		  		}
			}
			else if($st_agendamento == 0)
			{
				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> 0</button>");		
			}
			else
			{
				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> 0</button>");		
			}


			echo("
							</center>
						</td>
						<td width='70'>
							<b>
				");


		  	if($id_agendamento_login <> null)
		  	{
		  		if($horario_lotado_manha == 0)
		  		{
		  			echo("<font color='orange'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");
		  		}
		  		else
		  		{
		  			echo("<font color='red'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
		  		}
		  		
		  	}
		  	else
		  	{
		  		if($st_agendamento == 0)
				{
					echo("<font color='blue'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
				}
				else
				{
		  			echo("<font color='green'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
		  		}
		  	}

		  	






		  	if($id_agendamento_login <> null)
		  	{
		  		if($horario_lotado_manha == 0)
		  		{
		  			echo("<font color='orange'> " . $hr_agendamento_horario . "</font>");
		  		}
		  		else
		  		{
		  			echo("<font color='red'> " . $hr_agendamento_horario . "</font>");	
		  		}
				
			}
			else
			{
				if($st_agendamento == 0)
				{
					echo("<font color='blue'> " . $hr_agendamento_horario . "</font>");	
				}
				else
				{
					echo("<font color='green'> " . $hr_agendamento_horario . "</font>");	
				}
			}
			echo("
							</b>
				");

			

			echo("						
					</td>
	    		");

			//QUANTIDADE MAX DE AGENDAMENTO POR HORÁRIO MANHA
			echo("<td width='10'>
						<center><b><font size='3'>" . $qt_max_agendamento_horario . "</font></b></center>
				</td>");


			//QUANTIDADE MAX DE PROTOCOLO POR HORÁRIO MANHA
			echo("<td width='100'>
						<center><b><font size='3'>" . $qt_max_protocolo_horario . "</font></b></center>
				</td>");

			//TIPO DE USUÁRIO PERMITIDO SE AGENDAR NO HORÁRIO MANHA
			echo("<td width='190'>
						<b><font size='1'>" . $tipo_usuario_permitido_no_horario . "</font></b>
				</td>");

			//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO MANHA
			echo("<td>
						<b><font size='1'>" . $tipo_assunto_permitido_no_horario . "</font></b>
				</td>");


			echo("</tr>");


		  }while($linha = mysqli_fetch_assoc($dados));
		  mysqli_free_result($dados); 

	}
	else
	//No caso onde não possui horários criados de manhã
	//Da apção de criar e liberar
	{
		for ($x = 1; $x <= $qtd_horario_atendimento_manha; $x++) 
		{
		    if($x == 1)
		    {
		    	$valor_hora_manha = $hora_manha->format('H:i');
		    	echo("
		    			<tr>
							<td width='10'>
								<center><input type='checkbox' name='chk_horas' value='$valor_hora_manha' checked></input></center>
							</td>
							<td width='70'>
								<b>
								<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
					");
									echo($hora_manha->format('H:i'));
				echo("
								</b>
							</td>
		    		");

				//COMBO QTD MAX DE ATENDENTE NO HORARIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_atendente_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_manha->format('Hi') . "' onchange=''>
					");

									for($h = 0; $h < count($array_qtd_atendente); $h++)
					                {
					                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_atendente[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");


				//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "'>
					");

									for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
					                {
					                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");

				//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
				echo("
							<td width='10'>
								
									<select id='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "'>
										<option value='0' selected>TODOS</option>
										<option value='1'>PRÓPRIO INTERESSADO</option>
										<option value='2'>PROCURADOR DESPACHANTE</option>
										<option value='3'>EMPRESA ATIVIDADE PCE</option>
									</select>
								
							</td>
					");

				//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
				//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
					echo("
					<td width='10'>
						<!-- Button trigger modal -->
						<center>
						<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
						  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
						</button>
						</center>

						<!-- Modal -->
						<div class='modal fade' id='myModalAssuntos". $hora_manha->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_manha->format('H:i') ." </h4>
						      </div>
						      
						      <div class='modal-body'>

						      		<div class='panel panel-default'>
									  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
									  	<div class='panel-body'>
						      		");

									foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
									{	
										//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
										echo("
									  		<input type='checkbox' name='chk_assunto_" . $hora_manha->format('Hi') . "' value='" . $id_agendamento_assunto . "' checked></input>
									  		<label>" . $nm_agendamento_assunto . "</label>
									  		<br>
										");
									}
						      		echo("
						      			</div>
									</div>
							  </div>

						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
						      </div>
						     </div>
						  </div>
						</div>
					</td>
					");

				echo("</tr>");
		    }
		    else
			{
				$hora_manha = $hora_manha->add(new DateInterval($date_interval));
				$valor_hora_manha = $hora_manha->format('H:i');
				echo("
		    			<tr>
							<td width='10'>
								<center><input type='checkbox' name='chk_horas' value='$valor_hora_manha' checked></center>
							</td>
							<td width='70'>
								<b>
								<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
					");
									echo($hora_manha->format('H:i'));
				echo("
								</b>
							</td>
		    		");

				//COMBO QTD MAX DE ATENDENTE NO HORARIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_atendente_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_manha->format('Hi') . "' onchange=''>
					");

									for($h = 0; $h < count($array_qtd_atendente); $h++)
					                {
					                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_atendente[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");


				//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_manha->format('Hi') . "'>
					");

									for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
					                {
					                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");

				//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
				echo("
							<td width='10'>
								
									<select id='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_manha->format('Hi') . "'>
										<option value='0' selected>TODOS</option>
										<option value='1'>PRÓPRIO INTERESSADO</option>
										<option value='2'>PROCURADOR DESPACHANTE</option>
										<option value='3'>EMPRESA ATIVIDADE PCE</option>
									</select>
								
							</td>
					");

				//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
				//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
					echo("
					<td width='10'>
						<!-- Button trigger modal -->
						<center>
						<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
						  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
						</button>
						</center>

						<!-- Modal -->
						<div class='modal fade' id='myModalAssuntos". $hora_manha->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_manha->format('H:i') ." </h4>
						      </div>
						      
						      <div class='modal-body'>

						      		<div class='panel panel-default'>
									  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
									  	<div class='panel-body'>
						      		");

									foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
									{	
										//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
										echo("
									  		<input type='checkbox' name='chk_assunto_" . $hora_manha->format('Hi') . "' value='" . $id_agendamento_assunto . "' checked></input>
									  		<label>" . $nm_agendamento_assunto . "</label>
									  		<br>
										");
									}
						      		echo("
						      			</div>
									</div>
							  </div>

						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
						      </div>
						     </div>
						  </div>
						</div>
					</td>
					");

				echo("</tr>");




			}
		}
		if($id_unidade_sfpc == $id_unidade)
		{
			echo("
					<tr>
						<td colspan=2>
							<button type='button' class='btn btn-success btn-block btn-xs' onclick='insereDataHoraAgenda(1)'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> Abrir Manhã</button>
						</td>
					</tr>
				");
		}

	}
}									

echo("
									</tbody>
								</table>					
				  			</td>
				  		</tr>
	");




//TABELA DE HORÁRIOS DA TARDE
echo("
				  			<td>
						  		<table class='table table-condensed'>
					  				<thead>
										<tr bgcolor='#F5F5F5'>
											<th colspan=2>
												<center><h5><b>TARDE</b></h5></center>
											</th>
											<th>
												<center><h6>Nº DE ATENDENTE</h6></center>
											</th>
											<th>
												<center><h6>Nº MAX DE PROTOCOLO</h6></center>
											</th>
											<th>
												<h6>TIPO DE USUÁRIO PERMITIDO</h6>
											</th>
											<th>
												<h6>TIPO DE ASSUNTO PERMITIDO</h6>
											</th>
											
										</tr>
									</thead>
									<tbody>
	");
								
if($janela_criada == 0)
{							     
							     for ($y = 1; $y <= $qtd_horario_atendimento_tarde; $y++) 
									{
									    if($y == 1)
									    {
									    	$valor_hora_tarde = $hora_tarde->format('H:i');
									    	echo("
									    			<tr>
														<td width='10'>
															<center><input type='checkbox' name='chk_horas' value='$valor_hora_tarde' checked></center>
														</td>
														<td width='70'>
															<b>
															<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
												");
																echo($hora_tarde->format('H:i'));
											echo("
															</b>
														</td>
												");

											//COMBO QTD MAX DE ATENDENTE NO HORARIO
											echo("
														<td width='10'>
															<center>
																<select id='cmb_qtd_atendente_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_tarde->format('Hi') . "' onchange=''>
												");

																for($h = 0; $h < count($array_qtd_atendente); $h++)
												                {
												                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
												                    {
												                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
												                    }
												                    else
												                    {
												                        echo("<option>".$array_qtd_atendente[$h]."</option>");
												                    }

												                }
											echo("
																</select>
															</center>
														</td>
												");

											//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
											echo("
														<td width='10'>
															<center>
																<select id='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "'>
												");

																for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
												                {
												                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
												                    {
												                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
												                    }
												                    else
												                    {
												                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
												                    }

												                }
											echo("
																</select>
															</center>
														</td>
												");


											//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
											echo("
														<td width='10'>
															
																<select id='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "'>
																	<option value='0' selected>TODOS</option>
																	<option value='1'>PRÓPRIO INTERESSADO</option>
																	<option value='2'>PROCURADOR DESPACHANTE</option>
																	<option value='3'>EMPRESA ATIVIDADE PCE</option>
																</select>
															
														</td>
												");

											//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
											echo("
											<td width='180'>
												<!-- Button trigger modal -->
												<center>
												<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
												  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
												</button>
												</center>

												<!-- Modal -->
												<div class='modal fade' id='myModalAssuntos". $hora_tarde->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
												  <div class='modal-dialog' role='document'>
												    <div class='modal-content'>
												      <div class='modal-header'>
												        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_tarde->format('H:i') ." </h4>
												      </div>
												      
												      <div class='modal-body'>

												      		<div class='panel panel-default'>
															  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
															  	<div class='panel-body'>
												      		");

															foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
															{	
																//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
																echo("
															  		<input type='checkbox' name='chk_assunto_" . $hora_tarde->format('Hi') . "' value='" . $id_agendamento_assunto . "' onClick='validaSelecaoAssunto(\"chk_assunto_" . $hora_tarde->format('Hi') . "\")' checked></input>
															  		<label>" . $nm_agendamento_assunto . "</label>
															  		<br>
																");
															}
												      		echo("
												      			</div>
															</div>
													  </div>

												      <div class='modal-footer'>
												        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
												        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
												      </div>
												     </div>
												  </div>
												</div>


											</td>
											");

											echo("
													</tr>
									    		");
									    }
									    else
										{
											$hora_tarde = $hora_tarde->add(new DateInterval($date_interval));
											$valor_hora_tarde = $hora_tarde->format('H:i');
											echo("
									    			<tr>
														<td width='10'>
															<center><input type='checkbox' name='chk_horas' value='$valor_hora_tarde' checked></center>
														</td>
														<td width='70'>
															<b>
															<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
												");
																echo($hora_tarde->format('H:i'));
											echo("
															</b>
														</td>
												");

											//COMBO QTD MAX DE ATENDENTE NO HORARIO
											echo("
														<td width='10'>
															<center>
																<select id='cmb_qtd_atendente_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_tarde->format('Hi') . "' onchange=''>
												");

																for($h = 0; $h < count($array_qtd_atendente); $h++)
												                {
												                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
												                    {
												                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
												                    }
												                    else
												                    {
												                        echo("<option>".$array_qtd_atendente[$h]."</option>");
												                    }

												                }
											echo("
																</select>
															</center>
														</td>
												");

											//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
											echo("
														<td width='10'>
															<center>
																<select id='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "'>
												");

																for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
												                {
												                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
												                    {
												                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
												                    }
												                    else
												                    {
												                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
												                    }

												                }
											echo("
																</select>
															</center>
														</td>
												");

											//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
											echo("
														<td width='10'>
															
																<select id='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "'>
																	<option value='0' selected>TODOS</option>
																	<option value='1'>PRÓPRIO INTERESSADO</option>
																	<option value='2'>PROCURADOR DESPACHANTE</option>
																	<option value='3'>EMPRESA ATIVIDADE PCE</option>
																</select>
															
														</td>
												");

											//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
											echo("
											<td width='10'>
												<!-- Button trigger modal -->
												<center>
												<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
												  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
												</button>
												</center>

												<!-- Modal -->
												<div class='modal fade' id='myModalAssuntos". $hora_tarde->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
												  <div class='modal-dialog' role='document'>
												    <div class='modal-content'>
												      <div class='modal-header'>
												        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_tarde->format('H:i') ." </h4>
												      </div>
												      
												      <div class='modal-body'>

												      		<div class='panel panel-default'>
															  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
															  	<div class='panel-body'>
												      		");

															foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
															{	
																//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
																echo("
															  		<input type='checkbox' name='chk_assunto_" . $hora_tarde->format('Hi') . "' value='" . $id_agendamento_assunto . "' onClick='validaSelecaoAssunto(\"chk_assunto_" . $hora_tarde->format('Hi') . "\")' checked></input>
															  		<label>" . $nm_agendamento_assunto . "</label>
															  		<br>
																");
															}
												      		echo("
												      			</div>
															</div>
													  </div>

												      <div class='modal-footer'>
												        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
												        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
												      </div>
												     </div>
												  </div>
												</div>
											</td>
											");

											echo("
													</tr>
									    		");

										}
									}
}
//Busca os Horários DA TARDE Criados para a Data Selecionada
else
{
	$query = "SELECT 
				count(agendamento_horario.id_agendamento_horario) as qt_usuario_agendado_hr,
				agendamento_requerente.id_agendamento_login,
				agendamento_data.id_agendamento_data,
				agendamento_horario.id_agendamento_horario,
			    agendamento_horario.hr_agendamento_horario,
			    agendamento_horario.qt_max_agendamento_horario,
			    agendamento_horario.qt_requerente_agendado,
			    posto_graduacao.nm_posto_graduacao,
			    login.nm_guerra,
			    agendamento_horario.qt_max_protocolo_horario
			FROM agendamento_horario
			LEFT JOIN agendamento_requerente on agendamento_requerente.id_agendamento_horario = agendamento_horario.id_agendamento_horario
			INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			INNER JOIN login on login.id_login = agendamento_data.id_login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			WHERE agendamento_data.dt_agendamento = '$data_selecionada 00:00:00' AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) > '2016-01-01 12:00' AND unidade_id_unidade = $id_unidade
			group by agendamento_horario.id_agendamento_horario";

	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	$qtd_total_horarios = $qtd_total_horarios + $totalLinhas;
	

	//Exibe as Solicitacoes de Acesso ao Sistema
	if($totalLinhas > 0)
	{
		
		do
		  {
		  	$horario_lotado_tarde = 0;
		  	$id_agendamento_login = $linha['id_agendamento_login'];
		  	$qt_usuario_agendado_hr = $linha['qt_usuario_agendado_hr'];
		  	$id_agendamento_horario = $linha['id_agendamento_horario'];
		  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
		  	$qt_max_agendamento_horario = $linha['qt_max_agendamento_horario'];
		  	$qt_max_protocolo_horario = $linha['qt_max_protocolo_horario'];
		  	$qt_requerente_agendado = $linha['qt_requerente_agendado'];
		  	$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		  	$nm_guerra = $linha['nm_guerra'];
		  	$login_criador_janela = $nm_posto_graduacao;
		  	$login_criador_janela .= " ";
		  	$login_criador_janela .= $nm_guerra;


		  	//BUSCA OS TIPOS DE USUÁRIOS PERMITIDOS SE AGENDAR NO HORÁRIO
		  	$query_busca_tipos_usuarios_permitidos_horario = "SELECT 
				agendamento_login_tipo.nm_agendamento_login_tipo
				FROM agendamento_horario
				INNER JOIN agendamento_tipo_usuario_horario on agendamento_tipo_usuario_horario.id_agendamento_horario = agendamento_horario.id_agendamento_horario
				INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_tipo_usuario_horario.id_agendamento_login_tipo
				WHERE agendamento_horario.id_agendamento_horario = $id_agendamento_horario
				";

			$dados_tipos_usuario = mysqli_query($conn,$query_busca_tipos_usuarios_permitidos_horario) or die(mysql_error());
			$linha_tipos_usuario = mysqli_fetch_assoc($dados_tipos_usuario);
			// calcula quantos dados retornaram
			$totalLinhas_tipo_usuario = mysqli_num_rows($dados_tipos_usuario);
			
			$tipo_usuario_permitido_no_horario = "";

			if($totalLinhas_tipo_usuario > 0)
			{
				
				do
				  {				  	
				  	$nm_agendamento_login_tipo = $linha_tipos_usuario['nm_agendamento_login_tipo'];

				  	if($tipo_usuario_permitido_no_horario == "")
				  	{
				  		$tipo_usuario_permitido_no_horario .= "<span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $nm_agendamento_login_tipo;
				  	}
				  	else
				  	{
				  		$tipo_usuario_permitido_no_horario .= "<br> <span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $nm_agendamento_login_tipo;
				  	}

				  }while($linha_tipos_usuario = mysqli_fetch_assoc($dados_tipos_usuario));
		  		  mysqli_free_result($dados_tipos_usuario); 
			}
			//FIM DA BUSCA DOS USUARIOS PERMITIDOS


			//BUSCA OS TIPOS DE ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
		  	$query_busca_assuntos_permitidos_horario = "SELECT 
				agendamento_assunto.nm_agendamento_assunto
			FROM agendamento_assunto_horario
			INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_assunto_horario.id_agendamento_assunto
			WHERE id_agendamento_horario = $id_agendamento_horario
				";

			$dados_tipos_assunto = mysqli_query($conn,$query_busca_assuntos_permitidos_horario) or die(mysql_error());
			$linha_tipos_assunto = mysqli_fetch_assoc($dados_tipos_assunto);
			// calcula quantos dados retornaram
			$totalLinhas_tipo_assunto = mysqli_num_rows($dados_tipos_assunto);
			
			$tipo_assunto_permitido_no_horario = "";

			if($totalLinhas_tipo_assunto > 0)
			{
				
				do
				  {				  	
				  	$nm_agendamento_assunto = $linha_tipos_assunto['nm_agendamento_assunto'];

				  	if($tipo_assunto_permitido_no_horario == "")
				  	{
				  		$tipo_assunto_permitido_no_horario .= "<span class='glyphicon glyphicon-comment' aria-hidden='true'></span> " . $nm_agendamento_assunto;
				  	}
				  	else
				  	{
				  		$tipo_assunto_permitido_no_horario .= "<br> <span class='glyphicon glyphicon-comment' aria-hidden='true'></span> " . $nm_agendamento_assunto;
				  	}

				  }while($linha_tipos_assunto = mysqli_fetch_assoc($dados_tipos_assunto));
		  		  mysqli_free_result($dados_tipos_assunto); 
			}
			//FIM DOS ASSUNTOS PERMITIDOS


		  	if($qt_requerente_agendado == $qt_max_agendamento_horario)
		  	{
		  		$horario_lotado_tarde = 1;
		  	}
		  	
		  	if($horario_lotado_tarde == 1)
		  	{
		  		echo("<tr>");
		  	}
		  	else
		  	{
		  		echo("<tr>");
		  	}


		  	echo("
						<td width='10'>
							<center>
				");

		  	if($id_agendamento_login <> null)
			{
				//Busca dados usuários agendados na hora
				$query_popover = "SELECT
							agendamento_assunto.nm_agendamento_assunto,
							agendamento_login.cpf_login, 
							agendamento_login.nm_completo,
						    agendamento_login_tipo.nm_agendamento_login_tipo,
						    agendamento_login.nr_celular,
						    agendamento_login.email,
						    arquivo.binario,
							arquivo.tipo,
							agendamento_requerente_andamento.id_agendamento_status,
							agendamento_status.nm_status
						FROM agendamento_requerente
						INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
						INNER JOIN agendamento_status on agendamento_status.id_agendamento_status = agendamento_requerente_andamento.id_agendamento_status
						INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
						INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
						INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_requerente.id_agendamento_login
						INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
						INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
						INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_requerente.id_agendamento_assunto
						WHERE agendamento_horario.id_agendamento_horario = $id_agendamento_horario
						AND agendamento_requerente_andamento.id_agendamento_status IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
						ORDER BY agendamento_requerente.id_agendamento_requerente";
				$dados_popover = mysqli_query($conn,$query_popover) or die(mysql_error());
				$linha_popover = mysqli_fetch_assoc($dados_popover);
				// calcula quantos dados retornaram
				$totalLinhas_popover = mysqli_num_rows($dados_popover);
				if($totalLinhas_popover > 0)
				{
					
					//Botao Qtd usuarios agendados na hora
					if($horario_lotado_tarde == 0)
		  			{
		  				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='exibePopover(\"$id_agendamento_horario\",\"$hr_agendamento_horario\")' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $qt_usuario_agendado_hr . " </button>");
		  			}
		  			else
		  			{
		  				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='exibePopover(\"$id_agendamento_horario\",\"$hr_agendamento_horario\")' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $qt_usuario_agendado_hr . " </button>");	
		  			}			
					

					$dados_usuarios_agendados_horario =  array();
					$x = 0;

					do
					{
						//$foto = base64_encode($linha_popover['binario']);
						$id_agendamento_status = $linha_popover['id_agendamento_status'];
						$nm_agendamento_assunto = $linha_popover['nm_agendamento_assunto'];
						$nm_status = $linha_popover['nm_status'];
	  					$tipo_arquivo = $linha_popover['tipo'];
					  	$nm_completo = $linha_popover['nm_completo'];
					  	$cpf = $linha_popover['cpf_login'];
					  	$nm_agendamento_login_tipo = $linha_popover['nm_agendamento_login_tipo'];
					  	$nr_celular = $linha_popover['nr_celular'];
					  	$email = $linha_popover['email'];
					  	$foto = $path_sae;
					  	$foto .= $cpf;
					  	$foto .= ".jpg";


					  	$dados_usuarios_agendados_horario[$x] = $nm_completo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $foto;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $tipo_arquivo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_agendamento_login_tipo;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nr_celular;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $email;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_agendamento_assunto;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $id_agendamento_status;
					  	$dados_usuarios_agendados_horario[$x] .= ";";
					  	$dados_usuarios_agendados_horario[$x] .= $nm_status;
					  	
					  	
					  	$x = $x + 1;

					}while($linha_popover = mysqli_fetch_assoc($dados_popover));
		  			mysqli_free_result($dados_popover); 

		  			echo("<div id='div-popover_$id_agendamento_horario' class='hide' data-placement='left'>");

		  			for($h = 0; $h < count($dados_usuarios_agendados_horario); $h++)
	   				{
	   					$dados_usuario_agendado_horario = explode(";",$dados_usuarios_agendados_horario[$h]);
	   					echo("<div class='table-responsive'>
  								<table>
  									<tr>
  										<td colspan=2>
  											
  							");

	   									echo("<h5><b>");
					   					echo($dados_usuario_agendado_horario[6]);
					   					echo("</b></h5>");

	   									echo("<h6><b>");
					   					echo($dados_usuario_agendado_horario[0]);
					   					echo("</b></h6>");


	   						echo("		</td>
  									</tr>");


	   							echo("<tr>");
	   								echo("<td width=100>");
	   										//echo("<img id='thumbnail' src='data:".$dados_usuario_agendado_horario[2].";base64,".$dados_usuario_agendado_horario[1]."' width='100' class='img-thumbnail'>");
	   								echo("<img id='thumbnail' src='".$dados_usuario_agendado_horario[1]."' width='100' class='img-thumbnail'>");

	   								echo("</td>");

	   								echo("<td width=230>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[3]);
					   					echo("</h6>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[4]);
					   					echo("</h6>");

					   					echo("<h6>");
					   					echo($dados_usuario_agendado_horario[5]);
					   					echo("</h6>");

					   				echo("</td>");
					   			echo("</tr>");


					   			echo("<tr>");
					   				echo("<td colspan=2>");
					   					echo("<h6>");

					   					//echo("<b>SITUAÇÃO: </b>"); 

					   					if($dados_usuario_agendado_horario[7] == 1 || $dados_usuario_agendado_horario[7] == 2)
					   					{
					   						echo("<b><font color='green'>" . $dados_usuario_agendado_horario[8] . "</font></b>");
					   					}
					   					else if ($dados_usuario_agendado_horario[7] == 4 || $dados_usuario_agendado_horario[7] == 3)
					   					{
					   						echo("<b><font color='red'>" . $dados_usuario_agendado_horario[8] . "</font></b>");
					   					}
					   					
					   					echo("</h6>");
					   				echo("</td>");
					   			echo("</tr>");

					   			// echo("<tr>");
					   			// 	echo("<td colspan=2>");
					   			// 		echo("<center><button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> LIBERAR HORÁRIO</button></center>");		
					   			// 	echo("</td>");
					   			// echo("</tr>");


					   		echo("</table>");
					   	echo("</div>");

	   					echo("<hr>");
	   					
	   				}

	   				echo("</div>");

		  			//Div do Popover com usuários agendados na hora
					//echo("<div id='div-popover_$id_agendamento_horario' class='hide' data-placement='left'>" . $dados_usuarios_agendados_horario[0] . "</div>");
		  		}				
			}
			else if($st_agendamento == 0)
			{
				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> 0</button>");		
			}
			else
			{
				echo("<button type='button' id='$id_agendamento_horario' name='$id_agendamento_horario' onClick='' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> 0</button>");			
			}
			//FIM DO BOTAO DE EXIBIÇAO DE USUÁRIOS AGENDADOS

			echo("		
							</center>
						</td>
						<td width='70'>
							<b>
				");

		  	if($id_agendamento_login <> null)
		  	{
		  		if($horario_lotado_tarde == 0)
		  		{
		  			echo("<font color='orange'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
		  		}
		  		else
		  		{
		  			echo("<font color='red'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
		  		}
		  		
		  	}
		  	else
		  	{
		  		if($st_agendamento == 0)
				{
					echo("<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
				}
				else
				{
		  			echo("<font color='green'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font>");	
		  		}
		  	}

		  	
		  	if($id_agendamento_login <> null)
		  	{
		  		if($horario_lotado_tarde == 0)
		  		{
		  			echo("<font color='orange'> " . $hr_agendamento_horario . "</font>");
		  		}
		  		else
		  		{
		  			echo("<font color='red'> " . $hr_agendamento_horario . "</font>");	
		  		}
				
			}
			else
			{
				if($st_agendamento == 0)
				{
					echo("<font color='blue'> " . $hr_agendamento_horario . "</font>");	
				}
				else
				{
					echo("<font color='green'> " . $hr_agendamento_horario . "</font>");	
				}
			}
			echo("
							</b>
				");

			echo("						
					</td>
	    		");

			//QUANTIDADE MAX DE AGENDAMENTO POR HORÁRIO
			echo("<td width='10'>
						<center><b><font size='3'>" . $qt_max_agendamento_horario . "</font></b></center>
				</td>");


			//QUANTIDADE MAX DE PROTOCOLO POR HORÁRIO
			echo("<td width='100'>
						<center><b><font size='3'>" . $qt_max_protocolo_horario . "</font></b></center>
				</td>");

			//TIPO DE USUÁRIO PERMITIDO SE AGENDAR NO HORÁRIO
			echo("<td width='190'>
						<b><font size='1'>" . $tipo_usuario_permitido_no_horario . "</font></b>
				</td>");

			//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
			echo("<td>
						<b><font size='1'>" . $tipo_assunto_permitido_no_horario . "</font></b>
				</td>");


			echo("</tr>");


		  }while($linha = mysqli_fetch_assoc($dados));
		  mysqli_free_result($dados);
		  
		
	}
	else
	//NO CASO ONDE NÃO POSSUI HORÁRIOS CRIADOS A TARDE
	//DA APÇÃO DE CRIAR E LIBERAR
	{
		for ($y = 1; $y <= $qtd_horario_atendimento_tarde; $y++) 
		{
		    if($y == 1)
		    {
		    	$valor_hora_tarde = $hora_tarde->format('H:i');
		    	echo("
		    			<tr>
							<td width='10'>
								<center><input type='checkbox' name='chk_horas' value='$valor_hora_tarde' checked></center>
							</td>
							<td width='70'>
								<b>
								<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
					");
									echo($hora_tarde->format('H:i'));
				echo("
								</b>
							</td>
		    		");


				//COMBO QTD MAX DE ATENDENTE NO HORARIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_atendente_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_tarde->format('Hi') . "' onchange=''>
					");

									for($h = 0; $h < count($array_qtd_atendente); $h++)
					                {
					                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_atendente[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");


				//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "'>
					");

									for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
					                {
					                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");

				//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
				echo("
							<td width='10'>
								
									<select id='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "'>
										<option value='0' selected>TODOS</option>
										<option value='1'>PRÓPRIO INTERESSADO</option>
										<option value='2'>PROCURADOR DESPACHANTE</option>
										<option value='3'>EMPRESA ATIVIDADE PCE</option>
									</select>
								
							</td>
					");

				//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
				//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
					echo("
					<td width='10'>
						<!-- Button trigger modal -->
						<center>
						<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
						  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
						</button>
						</center>

						<!-- Modal -->
						<div class='modal fade' id='myModalAssuntos". $hora_tarde->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_tarde->format('H:i') ." </h4>
						      </div>
						      
						      <div class='modal-body'>

						      		<div class='panel panel-default'>
									  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
									  	<div class='panel-body'>
						      		");

									foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
									{	
										//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
										echo("
									  		<input type='checkbox' name='chk_assunto_" . $hora_tarde->format('Hi') . "' value='" . $id_agendamento_assunto . "' checked></input>
									  		<label>" . $nm_agendamento_assunto . "</label>
									  		<br>
										");
									}
						      		echo("
						      			</div>
									</div>
							  </div>

						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
						      </div>
						     </div>
						  </div>
						</div>
					</td>
					");


			echo("</tr>");

		    }
		    else
			{
				$hora_tarde = $hora_tarde->add(new DateInterval($date_interval));
				$valor_hora_tarde = $hora_tarde->format('H:i');
				echo("
		    			<tr>
							<td width='40'>
								<center><input type='checkbox' name='chk_horas' value='$valor_hora_tarde' checked></center>
							</td>
							<td width='70'>
								<b>
								<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></font> 
					");
									echo($hora_tarde->format('H:i'));
				echo("
								</b>
							</td>
		    		");

				//COMBO QTD MAX DE ATENDENTE NO HORARIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_atendente_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_atendente_horario_" . $hora_tarde->format('Hi') . "' onchange=''>
					");

									for($h = 0; $h < count($array_qtd_atendente); $h++)
					                {
					                    if($array_qtd_atendente[$h] == $valor_qtd_atendente_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_atendente[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_atendente[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");


				//COMBO QTD MAX DE PROTOCOLO NO HORARIO POR USUÁRIO
				echo("
							<td width='10'>
								<center>
									<select id='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "' name='cmb_qtd_max_protocolo_horario_". $hora_tarde->format('Hi') . "'>
					");

									for($h = 0; $h < count($array_qtd_max_protocolo_horario); $h++)
					                {
					                    if($array_qtd_max_protocolo_horario[$h] == $valor_qtd_max_protocolo_horario_parametro)
					                    {
					                        echo("<option selected>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }
					                    else
					                    {
					                        echo("<option>".$array_qtd_max_protocolo_horario[$h]."</option>");
					                    }

					                }
				echo("
									</select>
								</center>
							</td>
					");

				//COMBO TIPO DE USUÁRIO QUE PODE SE AGENDAR NO HORARIO
				echo("
							<td width='10'>
								
									<select id='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "' name='cmb_tipo_usuario_permitido_horario_". $hora_tarde->format('Hi') . "'>
										<option value='0' selected>TODOS</option>
										<option value='1'>PRÓPRIO INTERESSADO</option>
										<option value='2'>PROCURADOR DESPACHANTE</option>
										<option value='3'>EMPRESA ATIVIDADE PCE</option>
									</select>
								
							</td>
					");

				//ASSUNTOS PERMITIDOS SE AGENDAR NO HORÁRIO
				//RESTRIÇÃO DE ASSUNTOS NO HORÁRIO
					echo("
					<td width='10'>
						<!-- Button trigger modal -->
						<center>
						<button type='button' id='btn_tipo_assunto' onclick='' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#myModalAssuntos". $hora_tarde->format('Hi') ."'>
						  <i class='glyphicon glyphicon-cog'></i> ESPECIFICAR
						</button>
						</center>

						<!-- Modal -->
						<div class='modal fade' id='myModalAssuntos". $hora_tarde->format('Hi') ."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel'>HORA: ". $hora_tarde->format('H:i') ." </h4>
						      </div>
						      
						      <div class='modal-body'>

						      		<div class='panel panel-default'>
									  	<div class='panel-heading'>ASSUNTOS ATENDIDOS NO HORÁRIO</div>
									  	<div class='panel-body'>
						      		");

									foreach($array_assuntos as $id_agendamento_assunto => $nm_agendamento_assunto) 
									{	
										//echo($id_agendamento_assunto . " - " . $nm_agendamento_assunto);
										echo("
									  		<input type='checkbox' name='chk_assunto_" . $hora_tarde->format('Hi') . "' value='" . $id_agendamento_assunto . "' checked></input>
									  		<label>" . $nm_agendamento_assunto . "</label>
									  		<br>
										");
									}
						      		echo("
						      			</div>
									</div>
							  </div>

						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btn_confima_restricao_assunto_hora' onClick='' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
						      </div>
						     </div>
						  </div>
						</div>
					</td>
					");


			echo("</tr>");


			}
		}
		if($id_unidade_sfpc == $id_unidade)
		{
			echo("
					<tr>
						<td colspan=6>
							<button type='button' class='btn btn-success btn-block btn-xs' onclick='insereDataHoraAgenda(1)'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> Abrir Tarde</button>
						</td>
					</tr>
				");
		}
	}
}

									
echo("
									</tbody>
								</table>
							</td>
						</tr>
					</table>
					</center>
		  		</div>
	");
echo("</div>");


//EXIBE OS HORÁRIO DESAGENDADOS E DA A OPÇÃO PARA REALIZAR A LIBERAÇÃO DO HORÁRIO

//BUSCA A RELAÇÃO DE HORÁRIOS DESAGENDADOS
$query = "SELECT
			agendamento_requerente.id_agendamento_horario,
			agendamento_login.id_agendamento_login,
			agendamento_horario.hr_agendamento_horario,
			agendamento_assunto.nm_agendamento_assunto,
			agendamento_login.nm_completo,
			agendamento_login_tipo.nm_agendamento_login_tipo,
			agendamento_login.nr_celular
			
		FROM agendamento_requerente
		INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
		INNER JOIN agendamento_status on agendamento_status.id_agendamento_status = agendamento_requerente_andamento.id_agendamento_status
		INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
		INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
		INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_requerente.id_agendamento_login
		INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
		INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
		INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_requerente.id_agendamento_assunto
		WHERE 
		agendamento_requerente_andamento.id_agendamento_status IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
		AND agendamento_requerente_andamento.id_agendamento_status = 4
		AND agendamento_data.id_agendamento_data = $id_agendamento_data
		AND agendamento_requerente.st_agendamento_requerente_agendado = 0
		ORDER BY agendamento_requerente.id_agendamento_horario";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	echo("<div class='row'>");
		echo("<div class='col-md-12'>");
			echo("<label>AGENDAMENTO DESMARCADO PELO USUÁRIO</label>");
			echo("<div class='panel panel-default'>
						  <div class='panel-body'>

						  	<div class='table-responsive'>
  								<table class='table table-condensed'>
  								<thead>
  									<th width='90'>
  										<font size=2>HORA</font>
  									</th>

  									<th width='100'>
  										<font size=2>ASSUNTO</font>
  									</th>

  									<th>
  										<font size=2>USUÁRIO</font>
  									</th>

  									<th>
  										<font size=2>TIPO</font>
  									</th>

  									<th>
  										<font size=2>CELULAR</font>
  									</th>

  									<th>
  										<font size=2>AÇÃO</font>
  									</th>
  								</thead>
  								<tbody>
						  
				");
				do
				  {
				  	$id_agendamento_horario = $linha['id_agendamento_horario'];
				  	$id_agendamento_login = $linha['id_agendamento_login'];
				  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
					$nm_agendamento_assunto = $linha['nm_agendamento_assunto'];
					$nm_completo = $linha['nm_completo'];
					$nm_agendamento_login_tipo = $linha['nm_agendamento_login_tipo'];
					$nr_celular = $linha['nr_celular'];
					$nm_div_linha_acao = $id_agendamento_login;
					$nm_div_linha_acao .= $id_agendamento_horario;

					echo("							
							<tr>
								<td>
									<h5><font color='red'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> ". $hr_agendamento_horario ."</font></h5>
								</td>
								<td>
									<h6><font color='red'>". $nm_agendamento_assunto ."</font></h6>
								</td>
								<td>
									<h6><font color='red'>". $nm_completo ."</font></h6>
								</td>
								<td>
									<h6><font color='red'>". $nm_agendamento_login_tipo ."</font></h6>
								</td>
								<td>
									<h6><font color='red'>". $nr_celular ."</font></h6>
								</td>
								<td>
									<div id='". $nm_div_linha_acao ."'>
										<h6><button type='button' id='' name='' onClick='liberaVagaHorarioDesmarcado($id_agendamento_login, $id_agendamento_horario, $nm_div_linha_acao)' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> LIBERAR VAGA</button></h6>
									</div>
								</td>

							</tr>
						");


				  }while($linha = mysqli_fetch_assoc($dados));
				  mysqli_free_result($dados);
				  			echo("</tbody></table></div>");
						echo("</div>");
				echo("</div>");

		echo("</div>");
	echo("</div>");

}





//EXIBE OS PAINEIS DE CAPACIDADE E LEGENDA
echo("<div class='row'>");
	
	echo("<div class='col-md-6'>");
		//echo("TESTE 1");
		if($janela_criada == 1)
		{
			$qtd_total_atendimento = intval($qtd_total_horarios * $qt_max_agendamento_horario);
			echo("
								  	
					<div class='panel panel-default'>
					  <div class='panel-body'>
					    <h5><label>$qt_max_agendamento_horario Atendentes<label></h5>
					    <h5><label>$qtd_total_horarios Horarios<label></h5>
						<h5><label>$qtd_total_atendimento Atendimentos<label></h5>
						<h5><label>$login_criador_janela</label></h5>
					 </div>
					</div>
									
			");
		}
		else
		{
			echo("
									
					<div class='panel panel-default'>
					  <div class='panel-body'>
					    <h5><label>$qtd_atendente Atendentes<label></h5>
					    <h5><label>$qtd_horario_atendimento_manha Horarios Manhã<label></h5>
						<h5><label>$qtd_horario_atendimento_tarde Horarios Tarde<label></h5>
						<h5><label>$duracao_atendimento min / Atendimento</label></h5>
						<h5><label>$total_atendimento_dia Atendimentos / Dia</label></h5>
					 </div>
					</div>

			");
		}

	echo("</div>");

	echo("<div class='col-md-6'>");

		echo("
					<div class='panel panel-default'>
						<div class='panel-body'>
						  	<font color='blue'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> - <b>Bloqueado</b></font>
						  	<br>
						   	<font color='green'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> - <b>Livre</b></font>
						   	<br>
						   	<font color='orange'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> - <b>Agendado</b></font>
						   	<br>
						   	<font color='red'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> - <b>Lotado</b></font>
						</div>
					</div>	
			");
	
	echo("</div>");

echo("</div>");


echo("
		  		</div>
		  	</div>
		  </div>
		
	");



//EXIBE O BOTAO PARA CRIAR, EXCLUIR OU LIBERAR JANELA
if($janela_criada == 0)
{
	//Libera o Botao do Inserir Janela se o Usuário Logado Pertence a OM Selecionada
	if($id_unidade_sfpc == $id_unidade)
	{
		echo("	
			  <button type='button' class='btn btn-primary btn-lg btn-block btn-sm' onclick='insereDataHoraAgenda(0)'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> CRIAR ESTA JANELA NA AGENDA</button>
			");
	}
}
else
{
	//Libera os Botoes do Liberar ou Excluir ou Reagendar a Janela se o Usuário Logado Pertence a OM Selecionada
	if($id_unidade_sfpc == $id_unidade)
	{
		if($st_agendamento == 0)
		{
			echo("
				
					<div class='row'>
						<div class='col-md-6'>
						  <button type='button' class='btn btn-success btn-lg btn-block btn-sm' onclick='liberaJanelaAgendamento($id_agendamento_data)'><span class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span> LIBERAR AGENDAMENTO</button>
						</div>
						<div class='col-md-6'>
						  <button type='button' class='btn btn-danger btn-lg btn-block btn-sm' onclick='excluiJanelaAgendamento($id_agendamento_data)'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> EXCLUIR ESTA JANELA</button>
						</div>

					</div>  
				
			");	
		}
		else if($st_agendamento == 1 && $possui_usuario_agendado == 0)
		{
			echo("
				
					<div class='row'>
				
						<div class='col-md-12'>
						  <button type='button' class='btn btn-danger btn-lg btn-block btn-sm' onclick='excluiJanelaAgendamento($id_agendamento_data)'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> EXCLUIR ESTA JANELA</button>
						</div>

					</div>  
				
			");	

		}
		else if($st_agendamento == 1 && $possui_usuario_agendado == 1)
		{
			echo("
				
					<div class='row'>
				
						<div class='col-md-12'>
						  

						  <!-- Button trigger modal TRANSFERE USUÁRIOS AGENDADOS-->
							<center>
							<button type='button' id='btn_transfere_usuarios_agendados' onclick='' class='btn btn-danger btn-lg btn-block btn-sm' data-toggle='modal' data-target='#myModalTranfereUsuariosAgendados'>
							  <i class='glyphicon glyphicon-retweet'></i> TRANSFERIR AGENDAMENTOS PARA OUTRA DATA
							</button>
							</center>

							<!-- Modal -->
							<div class='modal fade' id='myModalTranfereUsuariosAgendados' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
							  <div class='modal-dialog' role='document'>
							    <div class='modal-content'>
							      <div class='modal-header'>
							        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
							        <h4 class='modal-title' id='myModalLabel'>
							        	<font color='red'><b><i class='glyphicon glyphicon-calendar'></i> TRANSFERÊNCIA DE DATA DE ATENDIMENTO</b></font>
								        <br>
								        <font color='red'>DE: <b>$data_escolhida</b></font>
								        
							        </h4>
							      </div>
							      <div class='modal-body'>
				");
				
				if($possui_usuario_agendado_manha == 1 && $possui_usuario_agendado_tarde == 1)
				{
				echo("
							      		<label>PERÍODO:</label>
							      		<div class='radio'>
										  <label>
										    <input type='radio' name='rdb_periodo' id='rdb_periodo' value='dia' checked>
										     <b>Integral</b> (Todos os usuários agendados no dia)
										  </label>
										</div>
					");
				}

				if($possui_usuario_agendado_manha == 1 && $possui_usuario_agendado_tarde == 0)
				{
				echo("
										<div class='radio'>
										  <label>
										    <input type='radio' name='rdb_periodo' id='rdb_periodo' value='manha' checked>
										     <b>Manhã</b> (Apenas usuários agendados na manhã)
										  </label>
										</div>
					");
				}
				else if($possui_usuario_agendado_manha == 1 && $possui_usuario_agendado_tarde == 1)
				{
				echo("
										<div class='radio'>
										  <label>
										    <input type='radio' name='rdb_periodo' id='rdb_periodo' value='manha'>
										     <b>Manhã</b> (Apenas usuários agendados na manhã)
										  </label>
										</div>
					");
				}

				if($possui_usuario_agendado_tarde == 1 && $possui_usuario_agendado_manha == 0)
				{

				echo("
										<div class='radio'>
										  <label>
										    <input type='radio' name='rdb_periodo' id='rdb_periodo' value='tarde' checked>
										     <b>Tarde</b> (Apenas usuários agendados na tarde)
										  </label>
										</div>
					");
				}
				else if($possui_usuario_agendado_tarde == 1 && $possui_usuario_agendado_manha == 1)
				{

				echo("
										<div class='radio'>
										  <label>
										    <input type='radio' name='rdb_periodo' id='rdb_periodo' value='tarde'>
										     <b>Tarde</b> (Apenas usuários agendados na tarde)
										  </label>
										</div>
					");
				}

					echo("
										<div class='row'>
		  									<div class='col-md-4'>
												<label>Data Futura:</label>
												<div class='input-group date' id='dt_reagendamento'>
												<input type='text' class='form-control' id='txt_data_futura_reagendamento' name='txt_data_futura_reagendamento' value=''/>
													<span class='input-group-addon'>
							                        	<span class='glyphicon glyphicon-calendar'></span>
								                    </span>
								                </div>
							                </div>
							            </div>

										<br>
							      		
					 					<div class='alert alert-danger' role='alert'>
					 						<p>1 - Selecione o <b>Período</b> que deseja transferir;</p>
					 						<p>2 - Escolha uma <b>Data futura</b>;</p>
					 						<p>3 - Clique em <b>Confirmar Transferência!</b></p>
					 					</div>

					 					
							      		
								  </div>
							      <div class='modal-footer'>
							        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
							        <button type='button' name='btn_transfere_agendamentos_confirma' onClick='transferirUsuariosAgendados()' class='btn btn-success' data-dismiss='modal'>CONFIRMAR TRANSFERÊNCIA</button>
							      </div>
							     </div>
							  </div>
							</div>



						</div>

					</div>  
				
			");	

		}

	}
}


echo("</div>");

}
else
{
	echo("	<div class='alert alert-danger' role='alert'>
			  <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
			  <span class='sr-only'>Error:</span>
			  	A data a ser disponibilizada deve ser maior do que a data de hoje!
			</div>");
}
?>
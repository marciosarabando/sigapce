<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$data = formataData($_GET['data']);
$data_atendimento = retornaDataExtenso($data);
$id_unidade = $_GET['id_unidade'];




function exibeAgendamentosDataHorarioUnidade($data, $hora, $id_unidade)
{
	if (!isset($_SESSION)) 
	{
		session_start();
	}
	if(isset($_SESSION['ambiente']))
	{
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

	date_default_timezone_set('America/Sao_Paulo');
	$hoje = date('Y-m-d');
	$data_selecionada = substr($data, -19, 10);
	$btn_presenca = 0;

	//VERIFICA SE A DATA SELECIONADA É IGUAL A HOJE PARA LIBERAR OS BOTÕES DE CONFIRMAÇÃO DE PRESENÇA
	//echo($data_selecionada);
	//echo($hoje);
	if($data_selecionada <= $hoje)
	{
		$btn_presenca = 1;
	}
	include ("../../funcoes/conexao.php");
	
	mysqli_query($conn,"SET NAMES 'utf8';");
	$query = "SELECT 
			agendamento_requerente.id_agendamento_requerente,
			agendamento_status.id_agendamento_status,
			agendamento_status.nm_status,
			arquivo.binario,
			arquivo.tipo,
	        agendamento_horario.id_agendamento_horario,
	        agendamento_horario.hr_agendamento_horario,
	        agendamento_login.id_agendamento_login,
	        agendamento_login.cpf_login,
	        agendamento_login.nm_completo,
	        agendamento_login_tipo.nm_agendamento_login_tipo,
	        agendamento_login.nr_celular,
	        agendamento_login.email,
	        cidade.nm_cidade,
	        cidade.uf_cidade,
	        agendamento_assunto.nm_agendamento_assunto,
	        agendamento_requerente_andamento.dt_agendamento_requerente_andamento , IFNULL (GROUP_CONCAT(interessado.nm_interessado , '<BR>', case when sg_tipo_interessado = 'PJ' then insert(INSERT( INSERT( INSERT( interessado.cnpj_interessado, 13, 0, '-' ), 6, 0, '.' ), 3, 0, '.' ), 11, 0, '/' ) else INSERT( INSERT( INSERT( interessado.cpf_interessado, 10, 0, '-' ), 7, 0, '.' ), 4, 0, '.' ) end   SEPARATOR '<BR><BR>' ), 'PRÓPRIO INTERESSADO')identificador
		FROM agendamento_requerente
		INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
		INNER JOIN agendamento_status on agendamento_status.id_agendamento_status = agendamento_requerente_andamento.id_agendamento_status
		INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
	left JOIN (select * from agendamento_horario_interessado) base  on agendamento_horario.id_agendamento_horario = base.id_agendamento_horario and base.id_agendamento_login = agendamento_requerente.id_agendamento_login
	left JOIN interessado on interessado.id_interessado =  base.id_interessado 
		INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
		INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_requerente.id_agendamento_login
		INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
		INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
		INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
		INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_requerente.id_agendamento_assunto
		WHERE agendamento_data.dt_agendamento = '$data' AND agendamento_data.unidade_id_unidade = $id_unidade
		AND agendamento_requerente_andamento.id_agendamento_status IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
		AND agendamento_horario.hr_agendamento_horario = '$hora'  group by agendamento_requerente.id_agendamento_requerente,
			agendamento_status.id_agendamento_status,
			agendamento_status.nm_status,
			arquivo.binario,
			arquivo.tipo,
	        agendamento_horario.id_agendamento_horario,
	        agendamento_horario.hr_agendamento_horario,
	        agendamento_login.id_agendamento_login,
	        agendamento_login.cpf_login,
	        agendamento_login.nm_completo,
	        agendamento_login_tipo.nm_agendamento_login_tipo,
	        agendamento_login.nr_celular,
	        agendamento_login.email,
	        cidade.nm_cidade,
	        cidade.uf_cidade,
	        agendamento_assunto.nm_agendamento_assunto,
	        agendamento_requerente_andamento.dt_agendamento_requerente_andamento

			";
// in IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
//echo($query);

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{

	echo("
		<div class='table-responsive'>
  				<table id='' name='tb_usuarios_agendados' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>ASSUNTO</center>
							</th>

							<th style='display: none;'>
								<center>ID</center>
							</th>
							<th>
								<center>FOTO</center>
							</th>
							
							
							<th>
								<center>NOME</center>
							</th>

							<th>
								<center>TIPO</center>
							</th>

							<th>
								<center>CPF</center>
							</th>

							<th>
								<center>CELULAR</center>
							</th>
							<th>
								<center>CIDADE</center>
							</th>
                            
                             ");
            
            /*
            COLUNA COM OS DADOS DOS INTERESSADOS VINCULADOS AO HORÁRIO
            DESCOMENTAR DIA 13/06/2019
					<th>
								<center>INTERESSADOS</center>
				    </th>
            */
            
            echo("
							        
							<th>
								<center>PRESENÇA</center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$id_agendamento_login = $linha['id_agendamento_login'];
	  	$id_agendamento_requerente = $linha['id_agendamento_requerente'];
	  	$id_agendamento_status = $linha['id_agendamento_status'];
	  	$nm_status = $linha['nm_status'];
	  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
	  	//$foto = base64_encode($linha['binario']);
	  	$foto = "http://agendamentosfpc.2rm.eb.mil.br/img/" . "foto_usuario/" . $linha['cpf_login'] . ".jpg";
	  	$tipo_arquivo = $linha['tipo'];
		$cpf = formataCPF($linha['cpf_login']);
		$nome = $linha['nm_completo'];
		$tipo_login = $linha['nm_agendamento_login_tipo'];
		$nr_celular = $linha['nr_celular'];
		$cidade = $linha['nm_cidade'];
		$cidade .= " - ";
		$cidade .= $linha['uf_cidade'];
		$nm_agendamento_assunto = $linha['nm_agendamento_assunto'];
		$dt_agendamento_requerente_andamento = $linha['dt_agendamento_requerente_andamento'];
		$interessados = $linha['identificador'];
		

		//<center><img id='thumbnail' src='data:".$tipo_arquivo.";base64,".$foto."' width='130' class='img-thumbnail'></center>
		
		echo("

				<tr class='active'>

					<td>
						<font color='green'><center><h5>".$nm_agendamento_assunto."</h5></center></font>
					</td>
					
					<td style='display: none;'>
						<center>".$id_agendamento_login."</center>
					</td>

					

					<td width=100>
						
						<center><img id='thumbnail' src='".$foto."' width='130' class='img-thumbnail'></center>
					</td>

					

					<td>
						<font color='green'><center><h5>".$nome."</h5></center></font>
					</td>

					<td>
						<font color='green'><center><h5>".$tipo_login."</h5></center></font>
					</td>

					<td>
						<font color='green'><center><h5>".$cpf."</h5></center></font>
					</td>

					<td>
						<font color='green'><center><h5>".$nr_celular."</h5></center></font>
					</td>


					<td>
						<font color='green'><center><h5>".$cidade."</h5></center></font>
					</td>
            ");
            
            /*
            COLUNA COM OS DADOS DOS INTERESSADOS VINCULADOS AO HORÁRIO
            DESCOMENTAR DIA 13/06/2019
					<td>
						<font color='green'><center><h5>".$interessados."</h5></center></font>
					</td>
            */
            
            echo("
					<td><center>
					
			");

		if($id_agendamento_status == 1)
		{
			echo("
					<div id='div_presenca_$id_agendamento_requerente'>
				");

			if($btn_presenca == 1)
			{
				echo("
							<!-- Button trigger modal USUARIO PRESENTE -->
							<button type='button' id='btn_presente_$id_agendamento_requerente' onclick='' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModalPresente_$id_agendamento_requerente'>
							  <i class='glyphicon glyphicon-thumbs-up'></i>
							</button>
					");
			}
			else
			{
				echo("
							<!-- Button trigger modal USUARIO PRESENTE -->
							<button type='button' id='btn_presente_$id_agendamento_requerente' onclick='' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModalPresente_$id_agendamento_requerente' disabled>
							  <i class='glyphicon glyphicon-thumbs-up'></i>
							</button>
					");
			}


			echo("
						<!-- Modal -->
						<div class='modal fade' id='myModalPresente_$id_agendamento_requerente' tabindex='-1' role='dialog' aria-labelledby='myModalLabel_$id_agendamento_requerente'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel_$id_agendamento_requerente'>REGISTRO DE PRESENÇA</h4>
						      </div>
						      <div class='modal-body'>
						      		
				 					<div class='alert alert-success' role='alert'>
				 						<p><label>USUÁRIO: $nome</label>
				 						<p><b><i class='glyphicon glyphicon-info-sign'></i> Clique em PRESENTE para registrar a Presença do usuário.</b></p>
				 						
				 					</div>
						      		
							  </div>
						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btnConfirmaPresencaAusencia_$id_agendamento_requerente' onclick='marcaPresencaAusenciaAtendimento($id_agendamento_requerente, \"div_presenca_$id_agendamento_requerente\",\"presente\")' class='btn btn-success' data-dismiss='modal'>PRESENTE</button>
						      </div>
						    </div>
						  </div>
						</div>
				");

			if($btn_presenca == 1)
			{
				echo("
						<!-- Button trigger modal USUARIO AUSENTE -->
						<button type='button' id='btn_ausente_$id_agendamento_requerente' onclick='' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#myModalAusente$id_agendamento_requerente'>
						  <i class='glyphicon glyphicon-thumbs-down'></i>
						</button>
					");
			}
			else
			{
				echo("
						<!-- Button trigger modal USUARIO AUSENTE -->
						<button type='button' id='btn_ausente_$id_agendamento_requerente' onclick='' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#myModalAusente$id_agendamento_requerente' disabled>
						  <i class='glyphicon glyphicon-thumbs-down'></i>
						</button>
					");
			}

			echo("
						<!-- Modal -->
						<div class='modal fade' id='myModalAusente$id_agendamento_requerente' tabindex='-1' role='dialog' aria-labelledby='myModalLabel_$id_agendamento_requerente'>
						  <div class='modal-dialog' role='document'>
						    <div class='modal-content'>
						      <div class='modal-header'>
						        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						        <h4 class='modal-title' id='myModalLabel_$id_agendamento_requerente'>REGISTRO DE AUSÊNCIA</h4>
						      </div>
						      <div class='modal-body'>
						      		
				 					<div class='alert alert-danger' role='alert'>
				 						<p><label>USUÁRIO: $nome</label>
				 						<p><b><i class='glyphicon glyphicon-info-sign'></i> Clique em AUSENTE para registrar a Ausência do usuário.</b></p>
				 						
				 					</div>
						      		
							  </div>
						      <div class='modal-footer'>
						        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
						        <button type='button' name='btnConfirmaPresencaAusencia_$id_agendamento_requerente' onclick='marcaPresencaAusenciaAtendimento($id_agendamento_requerente, \"div_presenca_$id_agendamento_requerente\",\"ausente\")' class='btn btn-danger' data-dismiss='modal'>AUSENTE</button>
						      </div>
						     </div>
						  </div>
						</div>

						
			
					</div>
				");
		}
		else if ($id_agendamento_status == 2)
		{
			echo("<font color='green'><center><h5><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> <b>$nm_status</b></h5></center></font>");
		}
		else if ($id_agendamento_status == 3)
		{
			echo("<font color='red'><center><h5><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span> <b>$nm_status</b></h5></center></font>");
		}
		else if ($id_agendamento_status == 4)
		{
			echo("<font color='red'><center>Agendamento desmarcado pelo usuário em <b>$dt_agendamento_requerente_andamento</b></center></font>");
		}
		
		
		echo("</center></td>");

	    
	    echo("</tr>");
	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("</table>");
	  echo("</div>");
	}
}

//BUSCA OS HORÁRIOS AGENDADOS PARA A DATA
$query = "SELECT 
			agendamento_horario.id_agendamento_horario,
			agendamento_data.dt_agendamento,
	        agendamento_horario.hr_agendamento_horario
	        
		FROM agendamento_requerente
		INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
		INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
		INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
		
		WHERE agendamento_data.dt_agendamento = '$data' AND agendamento_data.unidade_id_unidade = $id_unidade
		AND agendamento_requerente_andamento.id_agendamento_status = 1
		group by agendamento_horario.hr_agendamento_horario
		order by agendamento_horario.id_agendamento_horario

			";

echo("
	<div class='panel panel-default'>
		<div class='panel-heading'>
			
			<font color='green'><h4><span class='glyphicon glyphicon-calendar'></span> $data_atendimento <button class='btn btn-success btn-xs' onclick='imprimeRelaAgendamentoDia()'> <span class='glyphicon glyphicon-print' aria-hidden='true'></span></button></h4></font>
		
		</div>
		<div class='panel-body'>
	    <!-- ... Corpo do Painel ... -->
	  
	");

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
echo("<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>");
if($totalLinhas > 0)
{

	
	do
	  {
	  	$id_agendamento_horario = $linha['id_agendamento_horario'];
	  	$dt_agendamento = $linha['dt_agendamento'];
	  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
	  	
	  	echo("
	  	<div class='panel panel-default'>
		    <div class='panel-heading' role='tab' id='$hr_agendamento_horario'>
		      <h4 class='panel-title'>
		        <a class='collapsed' role='button' data-toggle='collapse' data-parent='#accordion' href='#$id_agendamento_horario' aria-expanded='false' aria-controls='$id_agendamento_horario'>
		          <font color='green'><span class='glyphicon glyphicon-time' aria-hidden='true'></span> $hr_agendamento_horario h</font> 
		        </a>
		      </h4>
		    </div>
		    <div id='$id_agendamento_horario' class='panel-collapse collapse' role='tabpanel' aria-labelledby='$hr_agendamento_horario'>
		      <div class='panel-body'>
		");
		exibeAgendamentosDataHorarioUnidade($dt_agendamento, $hr_agendamento_horario, $id_unidade);

		echo("
			 </div>
		    </div>
		  </div>
		");
	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	 
echo("</div>");
echo("</div>");
echo("</div>");
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não existem usuários agendados nesta data.</p>
		");
}

?>
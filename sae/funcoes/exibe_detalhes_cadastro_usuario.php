<?php
//Exibe Detalhes do Cadastro do Usuário Requerente
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_agendamento_login = $_GET['id_agendamento_login'];

$azul = array("1");
$verde = array("2");
$vermelho = array("3");
$amarelo = array("4","5");

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

$path_sae .= "foto_usuario/";


$query = "SELECT 
					agendamento_login.id_agendamento_login,
					binario,
					arquivo.tipo,
					cpf_login, 
			        nm_completo, 
			        nr_celular,
                    email,
                    agendamento_login_tipo.id_agendamento_login_tipo as id_tipo_login,
                    agendamento_login_tipo.nm_agendamento_login_tipo as nm_tipo_login,
			        agendamento_login_historico.dt_agendamento_login_historico as data,
			        agendamento_login_status.nm_status as status,
			        cidade.nm_cidade as cidade,
			        cidade.uf_cidade as uf,
			        agendamento_login_status.id_agendamento_login_status as id_status,
                    st_login
			FROM agendamento_login
			INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
			INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
			INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
			INNER JOIN agendamento_login_historico on agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login
			INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
			WHERE agendamento_login_historico.id_agendamento_login_historico IN (SELECT max(agendamento_login_historico.id_agendamento_login_historico) FROM agendamento_login_historico WHERE agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login)
            AND agendamento_login.id_agendamento_login = $id_agendamento_login
		";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Exibe o Cadastro do Interessado
if($totalLinhas > 0)
{

	do
	  {
	  	
	  	$id_agendamento_login = $linha['id_agendamento_login'];
	  	$foto = base64_encode($linha['binario']);
	  	$tipo_arquivo = $linha['tipo'];
		$cpf = $linha['cpf_login'];
		$nome = $linha['nm_completo'];
		$cidade = $linha['cidade'];
		$cidade .= " - ";
		$cidade .= $linha['uf'];
		$status = $linha['status'];
		$data = $linha['data'];
		$id_status = $linha['id_status'];
		$celular = $linha['nr_celular'];
		$email = $linha['email'];
		$st_login = $linha['st_login'];
		$nm_tipo_login = $linha['nm_tipo_login'];
		$path_sae .= $cpf . ".jpg";

		echo("<div class='panel panel-default'>");

		if (in_array($id_status, $azul)) 
		{
			echo ("<div class='alert alert-info' role='alert'>");
		}
		if (in_array($id_status, $verde)) 
		{
			echo ("<div class='alert alert-success' role='alert'>");
		}
		if (in_array($id_status, $vermelho)) 
		{
			echo ("<div class='alert alert-danger' role='alert'>");
		}
		if (in_array($id_status, $amarelo)) 
		{
			echo ("<div class='alert alert-warning' role='alert'>");
		}

		//echo($path_foto_usuario);

		echo ("
						<div class='table-responsive'>
							<table>
								<tr>
									<td width='20%'>
										
										<center><img id='thumbnail' src='" . $path_sae . "' width='130' class='img-rounded'></center>
									</td>
									<td width='80%'>
										<h3><label>".$nome."</label></h3>
										<h4><label>CPF: ".formataCPF($cpf)."</label></h4>
										<h4><label>SITUAÇÃO: ".$status."</label></h4>
									</td>
									<td vAlign='bottom' Align='right'>

										<!-- Button trigger modal RESETA SENHA-->
										<center>
										<button type='button' id='btn_reseta_senha_login' onclick='' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#myModalSenhaLogin'>
										  <i class='glyphicon glyphicon-lock'></i> RESETAR SENHA
										</button>
										</center>
										<br>


									
										<!-- Button trigger modal ALTERA STATUS-->
										<center>
										<button type='button' id='btn_alterarEstadoLogin' onclick='carregaComboStatusLogin($id_agendamento_login)' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#myModalEstadoLogin'>
										  <i class='glyphicon glyphicon-cog'></i> ALTERAR STATUS
										</button>
										</center>

									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			");
			
		echo("
					<!-- Modal -->
									<div class='modal fade' id='myModalSenhaLogin' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
									  <div class='modal-dialog' role='document'>
									    <div class='modal-content'>
									      <div class='modal-header'>
									        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
									        <h4 class='modal-title' id='myModalLabel'>CPF: ".formataCPF($cpf) ." </h4>
									      </div>
									      <div class='modal-body'>
									      		<div>
								 					<div class='alert alert-warning' role='alert'>
								 						<p><b><i class='glyphicon glyphicon-info-sign'></i> RESET DE SENHA</b></p>
								 						<p>Clique em confirmar para resetar a senha do Login.</p>
								 						<p>A nova senha será o <b>CPF</b> do usuário sem pontos e traço. Ex.: $cpf</p>
								 					</div>
									      		</div>
									      		
										  </div>
									      <div class='modal-footer'>
									        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
									        <button type='button' name='resetaSenhaLogin' onClick='resetaSenhaLogin($id_agendamento_login)' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
									      </div>
									     </div>
									  </div>
									</div>
			");
		
		echo("
					<!-- Modal -->
									<div class='modal fade' id='myModalEstadoLogin' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
									  <div class='modal-dialog' role='document'>
									    <div class='modal-content'>
									      <div class='modal-header'>
									        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
									        <h4 class='modal-title' id='myModalLabel'>CPF: ".formataCPF($cpf) ." </h4>
									      </div>
									      <div class='modal-body'>
									      		<div id='div_cmbStatusLogin'>
								 					<!-- ... Div Alimentada pela página sae/funcoes/carrega_combo_status_login_modal.php ... -->
									      		</div>
			
									      		<!-- ... No caso do OPERADOR solicitar CORREÇÃO DE DADOS ou SUSPENDER deverá informar o Motivo ... -->
									      		<div id='div_obs_info_login' hidden>

										      		<label>INFORMAÇÃO AO USUÁRIO (PREENCHIMENTO OBRIGATÓRIO)</label>
			 										<textarea id='txt_observacao_login' class='form-control upper' rows='3' maxlength=200 placeholder='INFORME AQUI O MOTIVO DA CORREÇÃO, INDEFERIMENTO OU SUSPENSÃO DO LOGIN'></textarea>


									      		</div>
									      		
										  </div>
									      <div class='modal-footer'>
									        <button type='button' class='btn btn-default' data-dismiss='modal'>Fechar</button>
									        <button type='button' name='alterarEstadoProcesso' onClick='alteraStatusLogin($id_agendamento_login)' class='btn btn-success' data-dismiss='modal'>CONFIRMAR</button>
									      </div>
									     </div>
									  </div>
									</div>
			");
			
	
		

		echo("
		<div class='row'>
					<div class='col-md-6'>
							<label>INFORMAÇÕES DO USUÁRIO</label>
							<div class='table-responsive'>
	  							<table class='table table-condensed table-bordered'>
	  								<tr>
										<td>
											<label>NOME COMPLETO:</label>
										</td>
										<td>
											<label>" . $nome . "</label>

										</td>
									</tr>
									<tr>
										<td>
											<label>CPF:</label>
										</td>
										<td>
											<label>" . formataCPF($cpf) . "</label>

										</td>
									</tr>
									<tr>
										<td>
											<label>PERFIL:</label>
										</td>
										<td>
											<label>" . $nm_tipo_login . "</label>

										</td>
									</tr>
									<tr>
										<td>
											<label>CIDADE:</label>
										</td>
										<td>
											<label>" . $cidade . "</label>

										</td>
									</tr>
									<tr>
										<td>
											<label>CELULAR:</label>
										</td>
										<td>
											<label>" . $celular . "</label>

										</td>
									</tr>
									<tr>
										<td>
											<label>E-MAIL:</label>
										</td>
										<td>
											<label>" . $email . "</label>

										</td>
									</tr>
									
								</table>
							</div>
					</div>
	
		");
		}while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

echo("<div id='div_historico_status_login'></div>");


}

echo("<input type='hidden' id='txt_id_agendamento_login' value='$id_agendamento_login'></input>");


?>
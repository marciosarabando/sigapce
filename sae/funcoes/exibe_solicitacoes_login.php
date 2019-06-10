<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$filtro = $_GET['filtro'];
$id_unidade = $_GET['id_unidade'];


$azul = array("1");
$verde = array("2");
$vermelho = array("3","6");
$amarelo = array("4","5");


$query = "SELECT 
					agendamento_login.id_agendamento_login,
					binario,
					cpf_login, 
			        nm_completo, 
			        nr_celular, 
			        agendamento_login_historico.dt_agendamento_login_historico as data,
			        agendamento_login_status.nm_status as status,
			        cidade.nm_cidade as cidade,
			        cidade.uf_cidade as uf,
			        agendamento_login_status.id_agendamento_login_status as id_status,
			        agendamento_login_tipo.nm_agendamento_login_tipo as perfil
			FROM agendamento_login
			INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
			INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
			INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
			INNER JOIN agendamento_login_historico on agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login
			INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
			WHERE agendamento_login_historico.id_agendamento_login_historico IN (SELECT max(agendamento_login_historico.id_agendamento_login_historico) FROM agendamento_login_historico WHERE agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login)
			";

if($id_unidade > 0)
{
	$query .=" AND cidade.id_cidade IN (SELECT id_cidade FROM agendamento_unidade_cidade WHERE id_unidade = $id_unidade)";
}

if($filtro == 1)
{
	$query .=" AND agendamento_login_status.id_agendamento_login_status = 1";
}

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_solicitacoes_acesso' name='tb_solicitacoes_acesso' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th style='display: none;'>
								<center>ID</center>
							</th>
							<th>
								<center>CPF</center>
							</th>
							<th>
								<center>NOME</center>
							</th>
							<th>
								<center>PERFIL</center>
							</th>
							<th>
								<center>CIDADE</center>
							</th>
							<th>
								<center>DATA DA SOLICITAÇÃO</center>
							</th>
							<th>
								<center>STATUS</center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$id_agendamento_login = $linha['id_agendamento_login'];
	  	$foto = base64_encode($linha['binario']);
		$cpf = $linha['cpf_login'];
		$nome = $linha['nm_completo'];
		$cidade = $linha['cidade'];
		$cidade .= " - ";
		$cidade .= $linha['uf'];
		$status = $linha['status'];
		$data = $linha['data'];
		$id_status = $linha['id_status'];
		$peril = $linha['perfil'];
		echo("

				<tr class='active'>
					
					<td style='display: none;'>
						<center>".$id_agendamento_login."</center>
					</td>

					<td width='100'>
						<center><a href=\"javascript:exibeDetalhesCadastroUsuario($id_agendamento_login)\"\><center><b><font color=\"green\"> ".formataCPF($cpf)."</font></b></center></a>
					</td>
					
					<td>
						<center>".$nome."</center>
					</td>

					<td>
						<center>".$peril."</center>
					</td>

					<td>
						<center>".$cidade."</center>
					</td>

					<td width='150'>
						<center>".date('d/m/Y H:i', strtotime($data))."</center>
					</td>
			");
			if (in_array($id_status, $azul)) 
			{ 
		    	echo   ("
						<td>
							<font color='blue'><center><b>".$status."</b></center></font>
						</td>
						");
	    	}
	    	if (in_array($id_status, $verde)) 
			{ 
		    	echo   ("
						<td>
							<font color='green'><center><b>".$status."</b></center></font>
						</td>
						");
	    	}
	    	if (in_array($id_status, $vermelho)) 
			{ 
		    	echo   ("
						<td>
							<font color='red'><center><b>".$status."</b></center></font>
						</td>
						");
	    	}
	    	if (in_array($id_status, $amarelo)) 
			{ 
		    	echo   ("
						<td>
							<font color='#FFBF00'><center><b>".$status."</b></center></font>
						</td>
						");
	    	}

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
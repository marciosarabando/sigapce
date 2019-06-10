<?php
//Exibe Histórico do Cadastro Login
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_agendamento_login = $_GET['id_agendamento_login'];

$azul = array("1");
$verde = array("2");
$vermelho = array("3","6");
$amarelo = array("4","5");

$query = "SELECT 
				agendamento_login_historico.id_agendamento_login_historico,
				agendamento_login_status.id_agendamento_login_status as id_status,
				agendamento_login_status.nm_status as status,
				agendamento_login_historico.dt_agendamento_login_historico as data_historico,
		        agendamento_login_historico.obs as obs
		FROM agendamento_login_historico
		INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
		INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_login_historico.id_agendamento_login
		WHERE agendamento_login.id_agendamento_login = $id_agendamento_login order by id_agendamento_login_historico
		";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
$repeticao = 1;
//Exibe o Cadastro do Interessado
if($totalLinhas > 0)
{
	echo("
		
					<div class='col-md-6'>
							<label>HISTÓRICO DO STATUS DO LOGIN</label>
							<div class='table-responsive'>
	  							<table class='table table-condensed table-bordered'>
									<thead>
										<tr>
											<th>
												SITUAÇÃO
											</th>
											
											<th>
												DATA
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
	  	$id_status = $linha['id_status'];
	  	$status = $linha['status'];
	  	$data_historico = $linha['data_historico'];
	  	$obs = $linha['obs'];
	  	$obs = str_replace("ˆ", " ", $obs);


		if($repeticao == $totalLinhas)
		{
			echo("<tr class='success'>");
		}
		else
		{
			echo("<tr class='active'>");
		}

	  	if (in_array($id_status, $azul)) 
		{ 
	    	echo   ("
					<td width='100'>
						<font color='blue'><b>".$status."</b></font>
					</td>
					");
    	}
    	if (in_array($id_status, $verde)) 
		{ 
	    	echo   ("
					<td width='100'>
						<font color='green'><b>".$status."</b></font>
					</td>
					");
    	}
    	if (in_array($id_status, $vermelho)) 
		{ 
	    	echo   ("
					<td width='100'>
						<font color='red'><b>".$status."</b></font>
					</td>
					");
    	}
    	if (in_array($id_status, $amarelo)) 
		{ 
	    	echo   ("
					<td width='100'>
						<font color='#FFBF00'><b>".$status."</b></font>
					</td>
					");
    	}
    	echo("
		  				<td>
		  					".date('d/m/Y H:i', strtotime($data_historico))."
		  				</td>
		  				<td>
		  					".$obs."
		  				</td>
		  				
					</tr>
			");
    $repeticao++;
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);

		  echo("
						</table>
					</div>
				</div>
			</div>
			");
}

?>
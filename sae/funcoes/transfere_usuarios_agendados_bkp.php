<?php
//Insere Data/Hora Criadas na Agenda
include ("formata_dados.php");
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$id_unidade = $_GET['id_unidade'];
$data_selecionada = $_GET['data_selecionada'];
$data_futura = formataData($_GET['data_futura']);
$periodo = $_GET['periodo'];

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i');

include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$data_do_reagendamento = formataDataDDMMYYYY($data_futura);
$data_selecionada_futura = $data_futura ." 23:59";

//VERIFICA SE A DATA ESCOLHIDA É MAIOR DO QUE HOJE
if($data_selecionada_futura > $hoje)
{
	//echo("Data OK");


	//VERIFICA SE A DATA SELECIONA POSSUI AGENDAMENTOS
	$query = "SELECT 
				count(id_agendamento_horario) as qtd_usuarios_agendados_dia
			  FROM agendamento_horario
			  INNER JOIN agendamento_data ON agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			  WHERE dt_agendamento = '$data_selecionada' 
			  AND agendamento_horario.qt_requerente_agendado > 0
			  AND agendamento_data.unidade_id_unidade = $id_unidade";
	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	$qtd_usuarios_agendados_dia = 0;
	if($totalLinhas > 0)
	{
		do
		  {
		  	$qtd_usuarios_agendados_dia = $linha['qtd_usuarios_agendados_dia'];
		  }while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	}

	//Se existir usuários agendados
	if($qtd_usuarios_agendados_dia > 0)
	{

		//VERIFICA SE A DATA FUTURA ESCOLHIDA ESTÁ CRIADA
		$query = "SELECT 
						agendamento_data.id_agendamento_data
				  FROM agendamento_data 
				  WHERE dt_agendamento = '$data_futura' 
				  AND unidade_id_unidade = $id_unidade";

		$dados = mysqli_query($conn,$query) or die(mysql_error());
		$linha = mysqli_fetch_assoc($dados);
		// calcula quantos dados retornaram
		$totalLinhas = mysqli_num_rows($dados);
		$data_futura_criada = 0;
		if($totalLinhas > 0)
		{
			do
			  {
			  	$data_futura_criada = 1;
			  }while($linha = mysqli_fetch_assoc($dados));
			mysqli_free_result($dados);
		}

		//SE A DATA FUTURA NÃO POSSUI JANELA CRIADA, ENTÃO CRIA A JANELA NOS MESMOS PADRÕES
		if($data_futura_criada == 0)
		{
			//INSERE A DATA NA AGENDA
			$query = "INSERT INTO agendamento_data VALUES (null,$id_login,$id_unidade,'$data_futura',1)";
			if (mysqli_query($conn,$query) or die(mysql_error()))
			{ 

			   //ID DO REGISTRO INSERIDO
			   $id_agendamento_data = mysqli_insert_id($conn); 

			   //BUSCA OS HORARIOS DA ANTIGA DATA E INSERE OS HORÁRIOS
			   if($periodo == 'dia')
			   {
				   $query = "INSERT INTO agendamento_horario SELECT 
				   					null,
				   					$id_agendamento_data,
									agendamento_horario.hr_agendamento_horario,
									agendamento_horario.qt_max_agendamento_horario,
							        agendamento_horario.qt_requerente_agendado
							FROM agendamento_horario
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada'
							AND agendamento_data.unidade_id_unidade = $id_unidade";
					mysqli_query($conn,$query) or die(mysql_error());
				}
				if($periodo == 'manha')
				{
					$query = "INSERT INTO agendamento_horario SELECT 
				   					null,
				   					$id_agendamento_data,
									agendamento_horario.hr_agendamento_horario,
									agendamento_horario.qt_max_agendamento_horario,
							        agendamento_horario.qt_requerente_agendado
							FROM agendamento_horario
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada'
							AND agendamento_data.unidade_id_unidade = $id_unidade
							AND agendamento_horario.hr_agendamento_horario <= '12:00'";
					mysqli_query($conn,$query) or die(mysql_error());

					// $query = "INSERT INTO agendamento_horario SELECT 
				 //   					null,
				 //   					$id_agendamento_data,
					// 				agendamento_horario.hr_agendamento_horario,
					// 				agendamento_horario.qt_max_agendamento_horario,
					// 		        0
					// 		FROM agendamento_horario
					// 		INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
					// 		WHERE agendamento_data.dt_agendamento = '$data_selecionada'
					// 		AND agendamento_data.unidade_id_unidade = $id_unidade
					// 		AND agendamento_horario.hr_agendamento_horario >= '12:00'";
					// mysqli_query($conn,$query) or die(mysql_error());
				}
				else if($periodo == 'tarde')
				{
					// $query = "INSERT INTO agendamento_horario SELECT 
				 //   					null,
				 //   					$id_agendamento_data,
					// 				agendamento_horario.hr_agendamento_horario,
					// 				agendamento_horario.qt_max_agendamento_horario,
					// 		        0
					// 		FROM agendamento_horario
					// 		INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
					// 		WHERE agendamento_data.dt_agendamento = '$data_selecionada'
					// 		AND agendamento_data.unidade_id_unidade = $id_unidade
					// 		AND agendamento_horario.hr_agendamento_horario <= '12:00'";
					// mysqli_query($conn,$query) or die(mysql_error());

					$query = "INSERT INTO agendamento_horario SELECT 
				   					null,
				   					$id_agendamento_data,
									agendamento_horario.hr_agendamento_horario,
									agendamento_horario.qt_max_agendamento_horario,
							        agendamento_horario.qt_requerente_agendado
							FROM agendamento_horario
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada'
							AND agendamento_data.unidade_id_unidade = $id_unidade
							AND agendamento_horario.hr_agendamento_horario >= '12:00'";
					mysqli_query($conn,$query) or die(mysql_error());
				}

				
				//REALIZA A INCLUSÃO DOS USUÁRIOS AGENDADOS NA DATA FUTURA CRIADA
				if($periodo == 'dia')
				{
					$query = "INSERT INTO agendamento_requerente SELECT
								null,
							    ag_hr_novo.id_agendamento_horario,
								agendamento_requerente.id_agendamento_login,
							    'Agendamento transferido do dia $data_selecionada'
							FROM agendamento_requerente
							INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
							INNER JOIN agendamento_horario AS ag_hr_novo ON ag_hr_novo.hr_agendamento_horario = agendamento_horario.hr_agendamento_horario AND ag_hr_novo.id_agendamento_data = $id_agendamento_data
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada' OR dt_agendamento = '$data_futura'
							AND agendamento_data.unidade_id_unidade = $id_unidade
							";
				}
				else if($periodo == 'manha')
				{
					$query = "INSERT INTO agendamento_requerente SELECT
								null,
							    ag_hr_novo.id_agendamento_horario,
								agendamento_requerente.id_agendamento_login,
							    'Agendamento transferido do dia $data_selecionada'
							FROM agendamento_requerente
							INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
							INNER JOIN agendamento_horario AS ag_hr_novo ON ag_hr_novo.hr_agendamento_horario = agendamento_horario.hr_agendamento_horario AND ag_hr_novo.id_agendamento_data = $id_agendamento_data
							AND CONCAT('2016-01-01 ',ag_hr_novo.hr_agendamento_horario) < '2016-01-01 12:01'
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada' OR dt_agendamento = '$data_futura'
							AND agendamento_data.unidade_id_unidade = $id_unidade";
				}
				else if($periodo == 'tarde')
				{
					$query = "INSERT INTO agendamento_requerente SELECT
								null,
							    ag_hr_novo.id_agendamento_horario,
								agendamento_requerente.id_agendamento_login,
							    'Agendamento transferido do dia $data_selecionada'
							FROM agendamento_requerente
							INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
							INNER JOIN agendamento_horario AS ag_hr_novo ON ag_hr_novo.hr_agendamento_horario = agendamento_horario.hr_agendamento_horario AND ag_hr_novo.id_agendamento_data = $id_agendamento_data
							AND CONCAT('2016-01-01 ',ag_hr_novo.hr_agendamento_horario) > '2016-01-01 12:01'
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada' OR dt_agendamento = '$data_futura'
							AND agendamento_data.unidade_id_unidade = $id_unidade";
				}
				mysqli_query($conn,$query) or die(mysql_error());

				//REALIZA A INCLUSÃO NA TABELA DE ANDAMENTO DO AGENDAMENTO_REQUERENTE
				if($periodo == 'dia')
				{
					$query = "INSERT INTO agendamento_requerente_andamento SELECT 
									null,
									agendamento_requerente.id_agendamento_requerente,
									1,
								    '$hoje',
								    'Agendamento transferido do dia $data_selecionada'
								FROM agendamento_requerente
								INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
								INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
								WHERE agendamento_data.dt_agendamento = '$data_futura'
								AND agendamento_data.unidade_id_unidade = $id_unidade

								";
					mysqli_query($conn,$query) or die(mysql_error());
				}
				else if($periodo == 'manha')
				{
					$query = "INSERT INTO agendamento_requerente_andamento SELECT 
									null,
									agendamento_requerente.id_agendamento_requerente,
									1,
								    '$hoje',
								    'Agendamento transferido do dia $data_selecionada'
								FROM agendamento_requerente
								INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
								INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
								WHERE agendamento_data.dt_agendamento = '$data_futura'
								AND agendamento_data.unidade_id_unidade = $id_unidade
								AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) < '2016-01-01 12:01'
								";
					mysqli_query($conn,$query) or die(mysql_error());
				}
				else if($periodo == 'tarde')
				{
					$query = "INSERT INTO agendamento_requerente_andamento SELECT 
									null,
									agendamento_requerente.id_agendamento_requerente,
									1,
								    '$hoje',
								    'Agendamento transferido do dia $data_selecionada'
								FROM agendamento_requerente
								INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
								INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
								WHERE agendamento_data.dt_agendamento = '$data_futura'
								AND agendamento_data.unidade_id_unidade = $id_unidade
								AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) > '2016-01-01 12:01'
								";
					mysqli_query($conn,$query) or die(mysql_error());
				}
				

				//REMOVE OS AGENDAMENTOS DA DATA ANTIGA
				$query = "	DELETE 
							FROM agendamento_requerente_andamento 
							WHERE id_agendamento_requerente in
							(
								SELECT agendamento_requerente.id_agendamento_requerente FROM agendamento_requerente
								INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
								INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
								WHERE agendamento_data.dt_agendamento = '$data_selecionada'
								AND agendamento_data.unidade_id_unidade = $id_unidade
							";
				if($periodo == 'dia')
				{
					$query .= ")";
				}
				else if($periodo == 'manha')
				{
					$query .= " AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) < '2016-01-01 12:01')";
				}
				else if($periodo == 'tarde')
				{
					$query .= " AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) > '2016-01-01 12:01')";
				}

				mysqli_query($conn,$query) or die(mysql_error());

				$query = "	DELETE TB1 FROM agendamento_requerente AS TB1
							INNER JOIN agendamento_requerente AS TB2 on TB1.id_agendamento_requerente = TB2.id_agendamento_requerente
							INNER JOIN agendamento_horario ON agendamento_horario.id_agendamento_horario = TB2.id_agendamento_horario
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada'
							AND agendamento_data.unidade_id_unidade = $id_unidade";
				
				if($periodo == 'manha')
				{
					$query .= " AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) < '2016-01-01 12:01'";
				}
				else if($periodo == 'tarde')
				{
					$query .= " AND CONCAT('2016-01-01 ',agendamento_horario.hr_agendamento_horario) > '2016-01-01 12:01'";
				}
				
				mysqli_query($conn,$query) or die(mysql_error());

				$query = "	DELETE TB1 FROM agendamento_horario AS TB1
							INNER JOIN agendamento_horario AS TB2 ON TB1.id_agendamento_horario = TB2.id_agendamento_horario
							INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = TB2.id_agendamento_data
							WHERE agendamento_data.dt_agendamento = '$data_selecionada'
							AND agendamento_data.unidade_id_unidade = $id_unidade";

				if($periodo == 'manha')
				{
					$query .= " AND CONCAT('2016-01-01 ',TB2.hr_agendamento_horario) < '2016-01-01 12:01'";
				}
				else if($periodo == 'tarde')
				{
					$query .= " AND CONCAT('2016-01-01 ',TB2.hr_agendamento_horario) > '2016-01-01 12:01'";
				}
				mysqli_query($conn,$query) or die(mysql_error());

				//VERIFICA SE NAO POSSUI MAIS NENHUM HORÁRIO DISPONÍVEL PARA DELETAR A DATA
				$query = "SELECT 
						agendamento_horario.id_agendamento_horario
				  FROM agendamento_horario
				  INNER JOIN agendamento_data ON agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
				  WHERE agendamento_data.dt_agendamento = '$data_selecionada'
				  AND unidade_id_unidade = $id_unidade";

				$dados = mysqli_query($conn,$query) or die(mysql_error());
				$linha = mysqli_fetch_assoc($dados);
				// calcula quantos dados retornaram
				$totalLinhas = mysqli_num_rows($dados);
				$data_vazia = 1;
				if($totalLinhas > 0)
				{
					do
					  {
					  	$data_vazia = 0;
					  }while($linha = mysqli_fetch_assoc($dados));
					mysqli_free_result($dados);
				}


				if($data_vazia == 1)
				{
					$query = "DELETE FROM agendamento_data WHERE dt_agendamento = '$data_selecionada' AND unidade_id_unidade = $id_unidade";
					mysqli_query($conn,$query) or die(mysql_error());
					
				}
				else
				{
					//VERIFICA SE NAO POSSUI NENHUM USUÁRIO MAIS AGENDADO NA DATA E EXCLUI
					$possui_usuario_agendado = 0;
					$query = "SELECT count(agendamento_requerente.id_agendamento_requerente) as qtd_usuarios_agendado
					FROM agendamento_requerente
					INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
					INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
					WHERE agendamento_data.dt_agendamento = '$data_selecionada 00:00:00'
					AND agendamento_data.unidade_id_unidade = $id_unidade
					";
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
						  	}
						  }while($linha = mysqli_fetch_assoc($dados));
						  mysqli_free_result($dados);
					}

					if($possui_usuario_agendado == 0)
					{
						$query = "DELETE FROM agendamento_horario WHERE id_agendamento_data in (select id_agendamento_data from agendamento_data WHERE dt_agendamento = '$data_selecionada' AND agendamento_data.unidade_id_unidade = $id_unidade)";
						mysqli_query($conn,$query) or die(mysql_error());
						
						$query = "DELETE FROM agendamento_data WHERE dt_agendamento = '$data_selecionada' AND agendamento_data.unidade_id_unidade = $id_unidade";
						mysqli_query($conn,$query) or die(mysql_error());

						$data_vazia = 1;
					}
				}
				
				

				echo("
					<div class='alert alert-success' role='alert'>
						<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> TRANSFERÊNCIA REALIZADA PARA <b>$data_do_reagendamento</b>!</h3>
						
						<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Os usuários agendados desta data foram transferidos com sucesso!.
					</div>
				");


			  

			}
			else
			{ 
			   echo "FALHA NA GRAVAÇÃO DA DATA"; 
			} 

		}
		//DATA FUTURA JÁ POSSUI JANELA CRIADA
		else
		{
			echo("
				<div class='alert alert-danger' role='alert'>
					<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data_do_reagendamento</b> DATA INDISPONÍVEL!</h3>
					
					<h4><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Escolha uma <b>DATA LIVRE</b> para realizar a transferência.</h4>
					<h4>A data desejada <b>não pode</b> conter janela criada.</h4>
				</div>
			");

		}



	}
}
else
{
	echo("
			<div class='alert alert-danger' role='alert'>
				<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data_do_reagendamento</b> DATA INVÁLIDA!</h3>
				
				<h4><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Escolha uma <b>Data Futura</b> para realizar a transferência.</h4>
			</div>
		");
}

?>
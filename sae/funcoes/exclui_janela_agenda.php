<?php
//Insere Data/Hora Criadas na Agenda
include ("formata_dados.php");
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$id_agendamento_data = $_GET['id_agendamento_data'];
$data = $_GET['data'];

include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//VERIFICA SE NENHUM USUÁRIO SE AGENDOU ANTES DE EXCLUIR
$possui_usuario_agendado = 0;
$query = "SELECT count(agendamento_requerente.id_agendamento_requerente) as qtd_usuarios_agendado
			FROM agendamento_requerente
			INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
			INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			WHERE agendamento_data.dt_agendamento = '$data 00:00:00' AND agendamento_data.id_agendamento_data = $id_agendamento_data";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['qtd_usuarios_agendado'] > 0)
	  		$possui_usuario_agendado = 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

if($possui_usuario_agendado == 0)
{
	//EXCLUI A JANELA SELECIONADA

	//EXCLUI OS ASSUNTOS PERMITIDOS NO HORARIOS
	$query = "DELETE FROM `agendamento_assunto_horario` WHERE id_agendamento_horario in (SELECT id_agendamento_horario FROM `agendamento_horario` WHERE id_agendamento_data = $id_agendamento_data)";
	mysqli_query($conn,$query) or die(mysql_error());

	//EXCLUI OS USUARIOS PERMITIDOS NO HORARIOS
	$query = "DELETE FROM `agendamento_tipo_usuario_horario` WHERE id_agendamento_horario in (SELECT id_agendamento_horario FROM `agendamento_horario` WHERE id_agendamento_data = $id_agendamento_data)";
	mysqli_query($conn,$query) or die(mysql_error());

	//EXCLUI OS HORARIOS
	$query = "DELETE FROM agendamento_horario WHERE id_agendamento_data = $id_agendamento_data";
	if (mysqli_query($conn,$query) or die(mysql_error()))
	{ 
		//EXCLUI A DATA
		$query = "DELETE FROM agendamento_data WHERE id_agendamento_data = $id_agendamento_data";
		if (mysqli_query($conn,$query) or die(mysql_error()))
		{ 

		}
		else
		{ 
		   echo "FALHA NA EXCLUSÃO DA DATA"; 
		} 
	}
	else
	{ 
	   echo "FALHA NA EXCLUSÃO DO HORÁRIO"; 
	} 
	$data = formataDataDDMMYYYY($data);
	echo("
			<div class='alert alert-info' role='alert'>
				<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data</b> JANELA EXCLUÍDA!</h3>
				
				<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Janela excluída da agenda.
			</div>

		");
}
else
{
	echo("
			<div class='alert alert-danger' role='alert'>
				<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data</b> JANELA COM USUÁRIO AGENDADO!</h3>
				<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> A Janela não pode ser excluída da agenda, pois existem usuários agendados nesta data.
			</div>

		");
}

?>
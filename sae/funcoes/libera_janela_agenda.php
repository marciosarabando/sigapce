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

//LIBERA A JANELA SELECIONADA NA AGENDA PARA O PÚBLICO SE AGENDAR
$query = "UPDATE agendamento_data SET st_agendamento = 1, id_login = $id_login WHERE id_agendamento_data = $id_agendamento_data";
if (mysqli_query($conn,$query) or die(mysql_error()))
{ 

}
else
{ 
   echo "FALHA NA LIBERAÇÃO DA DATA"; 
} 

$data = formataDataDDMMYYYY($data);

echo("
		<div class='alert alert-success' role='alert'>
			<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data</b> JANELA LIBERADA!</h3>
			
			<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Janela disponível para agendamento pelo público externo.
		</div>

	");

?>
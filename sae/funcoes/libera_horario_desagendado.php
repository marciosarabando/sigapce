<?php
//LIBERAR VAGA DE HORÁRIO DESAGENDADO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_agendamento_login = $_GET['id_agendamento_login'];
$id_agendamento_horario = $_GET['id_agendamento_horario'];

//MUDA O STATUS DO AGENDAMENTO DO USUÁRIO
$query = "UPDATE agendamento_requerente SET st_agendamento_requerente_agendado = 3 WHERE id_agendamento_login = $id_agendamento_login AND id_agendamento_horario = $id_agendamento_horario";
mysqli_query($conn,$query) or die(mysql_error());

//LIBERA A VAGA NO HORARIO SELECIONADO
$query = "UPDATE agendamento_horario SET qt_requerente_agendado = qt_requerente_agendado - 1 WHERE id_agendamento_horario = $id_agendamento_horario";
mysqli_query($conn,$query) or die(mysql_error());

echo("<h6><font color='green'>VAGA LIBERADA!</font></h6>");

?>
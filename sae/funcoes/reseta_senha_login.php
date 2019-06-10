<?php
//RESETA A SENHA DO LOGIN
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$id_agendamento_login = $_GET['id_agendamento_login'];

$query = "UPDATE agendamento_login SET nm_senha = md5(cpf_login) WHERE id_agendamento_login = $id_agendamento_login";
mysqli_query($conn,$query) or die(mysql_error());
?>
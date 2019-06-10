<?php
include ("../funcoes/verificaAtenticacao.php");


//PEGA VALORES NA QUERY STRING
$id_login = $_GET['id_login'];
$posto_grad = $_GET['posto_grad'];
$nm_guerra = mb_strtoupper($_GET['nm_guerra'],'UTF-8');
$nm_completo = mb_strtoupper($_GET['nm_completo'],'UTF-8');
$email = $_GET['email'];
$ompertencente = $_GET['ompertencente'];
$perfilacesso = $_GET['perfilacesso'];
$st_ativo = $_GET['st_ativo'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//DELETA OS ACESSOS ANTIGOS
$query = "UPDATE login SET id_posto_graduacao = $posto_grad, id_unidade = $ompertencente, nm_completo = '$nm_completo', nm_email = '$email', id_login_perfil = $perfilacesso, st_ativo = $st_ativo WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());


echo("
		<br>
		<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            DADOS DO USUÁRIO ATUALIZADOS COM SUCESSO!
	     </div>
	");
?>
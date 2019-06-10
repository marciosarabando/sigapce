<?php
include ("../funcoes/verificaAtenticacao.php");


//PEGA VALORES NA QUERY STRING
$id_login = $_GET['id_login'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//RESETA SENHA USUARIO
$query = "UPDATE login SET nm_senha = md5(lower(nm_login)) WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());


echo("
		<br>
		<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            SENHA RESETADA COM SUCESSO!
	     </div>
	");
?>
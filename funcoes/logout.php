<?php
if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['login_sfpc']))
{
	include "conexao.php";
	$id_login_online = $_SESSION['id_login_sfpc'];
	$query = "DELETE FROM login_oline WHERE id_login_oline = $id_login_online";
	mysqli_query($conn,$query) or die(mysql_error());
}
//session_destroy();
unset( $_SESSION['login_sfpc'] );
unset( $_SESSION['id_unidade_sfpc'] );
unset( $_SESSION['nm_unidade_sfpc'] );
unset( $_SESSION['nr_unidade_sfpc'] );
unset( $_SESSION['id_login_perfil'] );
unset( $_SESSION['nm_login_perfil'] );
unset( $_SESSION['id_modulo_login_permissao'] );
unset( $_SESSION['dt_ultimo_acesso_login'] );


$redirect = "../index.php";
header("location:$redirect");
?>
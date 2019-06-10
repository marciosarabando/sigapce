<?php
	if (!isset($_SESSION)) 
	{
		session_start();
	}
	if(isset($_SESSION['login_sfpc']))
	{
		$nm_guerra = $_SESSION['login_sfpc'];
		include("usuario_online.php");
	}
	else
	{
		$redirect = "funcoes/logout.php";
		header("location:$redirect");
	}		
?>
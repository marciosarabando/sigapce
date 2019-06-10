<?php
//Busca os Ids dos Módulos que o usuário possui acesso
if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$id_modulo_login = null;

//Conecta no Banco de Dados
include ("conexao.php");

mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT id_modulo FROM modulo_permissao WHERE id_login = $id_login";
// executa a query
$dados2 = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha2 = mysqli_fetch_assoc($dados2);
// calcula quantos dados retornaram
$totalLinhas2 = mysqli_num_rows($dados2);

if($totalLinhas2 > 0)
{	
	
	do{
		if($id_modulo_login == null)
		{
			$id_modulo_login = $linha2['id_modulo'];
		}
		else
		{
			$id_modulo_login .= ',';
			$id_modulo_login .= $linha2['id_modulo'];
		}
	}while($linha2 = mysqli_fetch_assoc($dados2));
	mysqli_free_result($dados2);
}

$_SESSION['id_modulo_login_permissao'] = $id_modulo_login;
//fim Busca Módulos Acesso
?>
<?php
//Conecta no Banco de Dados
include ("conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
include ("obtemIP.php");
include("auditoria.php");

//Pega login e senha digitados
$login = $_POST['login_sfpc'];
$senha = md5($_POST['senha_sfpc']);
$senha_digitada = $_POST['senha_sfpc'];

$usuarioLogado = "";
$id_login = "";
$nm_unidade = "";

$query = "SELECT
				login.id_login AS ID_LOGIN,
				posto_graduacao.nm_posto_graduacao AS POSTO,
				login.nm_login AS LOGIN,
				login.nm_guerra AS NM_GUERRA,
			    login.nm_senha AS SENHA,
			    unidade.id_unidade AS ID_UNIDADE,
			    unidade.nm_unidade AS UNIDADE,
			    unidade.nr_unidade AS NR_UNIDADE,
			    login_perfil.nm_login_perfil AS NM_LOGIN_PERFIL,
			    login_perfil.id_login_perfil AS ID_LOGIN_PERFIL,
			    (SELECT max(dt_evento) FROM evento WHERE id_tipo_evento = 2 AND obs_evento = 'Login realizado!: $login') AS DT_ULTIMO_ACESSO
			FROM login
			inner join posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			inner join unidade on unidade.id_unidade = login.id_unidade
			inner join login_perfil on login_perfil.id_login_perfil = login.id_login_perfil
			WHERE login.nm_login = '$login' AND login.nm_senha = '$senha' AND st_ativo = 1";

// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{	
	do{
		$usuarioLogado = $linha['POSTO'];
		$usuarioLogado .= " ";
		$usuarioLogado .= $linha['NM_GUERRA'];
		$id_login = $linha['ID_LOGIN'];
		$id_unidade = $linha['ID_UNIDADE'];
		$nm_unidade = $linha['UNIDADE'];
		$nr_unidade = $linha['NR_UNIDADE'];
		$nm_login_perfil = $linha['NM_LOGIN_PERFIL'];
		$id_login_perfil = $linha['ID_LOGIN_PERFIL'];
		$dt_ultimo_acesso_login = $linha['DT_ULTIMO_ACESSO'];
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
	
	if (!isset($_SESSION)) 
	{
		session_start();
	}
	$_SESSION['login_sfpc'] = $usuarioLogado;	
	$_SESSION['id_login_sfpc'] = $id_login;
	$_SESSION['id_unidade_sfpc'] = $id_unidade;
	$_SESSION['nm_unidade_sfpc'] = $nm_unidade;
	$_SESSION['nr_unidade_sfpc'] = $nr_unidade;
	$_SESSION['id_login_perfil'] = $id_login_perfil;
	$_SESSION['nm_login_perfil'] = $nm_login_perfil;
	//$_SESSION['id_modulo_login_permissao'] = $id_modulo_login;
	$_SESSION['dt_ultimo_acesso_login'] = $dt_ultimo_acesso_login;

	//Pega os Modulos que o usuario tem permissao
	include("modulo_permissao.php");

	include("usuario_online.php");

	logar_login_sucesso($login);
	
	if($login == $senha_digitada)
	{
		$redirect = "../home.php?url=senha_expirada";
		echo "<meta http-equiv='refresh' content='0;URL=../home.php?url=senha_expirada'>";
	}
	else
	{
		$redirect = "../home.php";
		echo "<meta http-equiv='refresh' content='0;URL=../home.php'>";
	}
}
else
{	
	logar_falha_autenticação($login, $senha_digitada);
	echo "<div align='center'><br><br><h2>Usu&aacute;rio e/ou senha inv&aacute;lido(s)!</h2></div>"; 
	echo "<h3><center><font color='red'>Qualquer acesso não autorizado é considerado crime informático prórpio previsto no art. 10 da lei 9296/96 (Lei federal Brasileira).</font></center></h3>";
	echo "<center><font color='red'>IP REGISTRADO: ". getenv("REMOTE_ADDR") . "</font></center>";
	echo "<meta http-equiv='refresh' content='4;URL=../index.php'>"; 	
}
?>
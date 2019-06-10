<?php
include ("../funcoes/verificaAtenticacao.php");


//PEGA VALORES NA QUERY STRING
$posto_grad = $_GET['posto_grad'];
$nm_login = $_GET['nm_login'];
$nm_guerra = mb_strtoupper($_GET['nm_guerra'],'UTF-8');
$nm_completo = mb_strtoupper($_GET['nm_completo'],'UTF-8');
$senha = md5($nm_login);
$nm_login = mb_strtoupper($nm_login,'UTF-8');
$email = $_GET['email'];
$ompertencente = $_GET['ompertencente'];
$perfilacesso = $_GET['perfilacesso'];
$id_carteiras = $_GET['id_carteiras'];
$id_modulos = $_GET['id_modulos'];
$id_unidades_acesso = $_GET['id_unidades_acesso'];

$id_login = null;
$st_login_existe = false;

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//VERIFICA SE JÁ EXISTE LOGIN CADASTRADO
$query = "SELECT id_login FROM login WHERE nm_login = '$nm_login'";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
do
  {
  		$id_login = $linha['id_login'];
  		$st_login_existe = true;
  }while($linha = mysqli_fetch_assoc($dados));
  mysqli_free_result($dados);
}

if($st_login_existe == false)
{
	//INSERE O LOGIN
	$query = "INSERT INTO login VALUES (null,$posto_grad,$perfilacesso,$ompertencente,'$nm_login','$nm_guerra','$senha','$nm_completo','$email',1)";
	mysqli_query($conn,$query) or die(mysql_error());

	//BUSCA O ID DO LOGIN INSERIDO
	$query = "SELECT id_login FROM login WHERE nm_login = '$nm_login'";
	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	if($totalLinhas > 0)
	{
	do
	  {
	  		$id_login = $linha['id_login'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	}

	//INSERE O ACESSO NAS CARTEIRAS INCLUIDAS
	if($id_carteiras != "")
	{
		$query = "SELECT id_carteira FROM carteira WHERE id_carteira in ($id_carteiras)";
		$dados = mysqli_query($conn,$query) or die(mysql_error());
		$linha = mysqli_fetch_assoc($dados);
		// calcula quantos dados retornaram
		$totalLinhas = mysqli_num_rows($dados);
		if($totalLinhas > 0)
		{
			do
			  {	  		
			  		$query = "INSERT INTO login_carteira VALUES ($id_login,".$linha['id_carteira'].")";
					mysqli_query($conn,$query) or die(mysql_error());
			  }while($linha = mysqli_fetch_assoc($dados));
			  mysqli_free_result($dados);
		}
	}

	//INSERE O ACESSO NOS MODULOS SELECIONADOS
	if($id_modulos != "")
	{
		$query = "SELECT id_modulo FROM modulo WHERE id_modulo in ($id_modulos)";
		$dados = mysqli_query($conn,$query) or die(mysql_error());
		$linha = mysqli_fetch_assoc($dados);
		// calcula quantos dados retornaram
		$totalLinhas = mysqli_num_rows($dados);
		if($totalLinhas > 0)
		{
			do
			  {	  		
			  		$query = "INSERT INTO modulo_permissao VALUES (".$linha['id_modulo'].",$id_login)";
					mysqli_query($conn,$query) or die(mysql_error());
			  }while($linha = mysqli_fetch_assoc($dados));
			  mysqli_free_result($dados);
		}
	}

	//INSERE O ACESSO NAS UNIDADES SELECIONADAS
	$query = "SELECT id_unidade FROM unidade WHERE id_unidade in ($id_unidades_acesso) OR id_unidade in ($ompertencente)";
	$dados = mysqli_query($conn,$query) or die(mysql_error());
	$linha = mysqli_fetch_assoc($dados);
	// calcula quantos dados retornaram
	$totalLinhas = mysqli_num_rows($dados);
	if($totalLinhas > 0)
	{
		do
		  {	  		
		  		$query = "INSERT INTO login_unidade VALUES ($id_login,".$linha['id_unidade'].")";
				mysqli_query($conn,$query) or die(mysql_error());
		  }while($linha = mysqli_fetch_assoc($dados));
		  mysqli_free_result($dados);
	}

	echo(
	"
			<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            USUÁRIO CADASTRADO COM SUCESSO!
	        </div>
	"
		);
}
else
{
	echo(
	"
			<div class='alert alert-danger alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            O LOGIN <strong>$nm_login</strong> JÁ ESTÁ EM USO NO SISTEMA! ESCOLHA OUTRO LOGIN!
	        </div>
	"
		);

}

?>
<?php
$id_login = $_GET['id_login'];
$id_modulos = $_GET['id_modulos'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//DELETA OS ACESSOS ANTIGOS
$query = "DELETE FROM modulo_permissao WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());

//INSERE O ACESSO NOS MÓDULOS
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

echo("
		<br>
		<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            ACESSO EM MÓDULOS ATUALIZADO COM SUCESSO!
	     </div>
	");
?>
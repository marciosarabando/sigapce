<?php
$id_login = $_GET['id_login'];
$id_carteiras = $_GET['id_carteiras'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//DELETA OS ACESSOS ANTIGOS
$query = "DELETE FROM login_carteira WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());

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

echo("
		<br>
		<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            ACESSO EM CARTEIRAS ATUALIZADO COM SUCESSO!
	     </div>
	");
?>
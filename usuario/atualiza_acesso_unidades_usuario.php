<?php
$id_login = $_GET['id_login'];
$id_unidades_selecionadas = $_GET['id_unidades_selecionadas'];

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//DELETA OS ACESSOS ANTIGOS
$query = "DELETE FROM login_unidade WHERE id_login = $id_login";
mysqli_query($conn,$query) or die(mysql_error());

//INSERE O ACESSO NAS UNIDADES INCLUIDAS
if($id_unidades_selecionadas != "")
{
	$query = "SELECT id_unidade FROM unidade WHERE id_unidade in ($id_unidades_selecionadas)";
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
}

echo("
		<br>
		<div class='alert alert-success alert-dismissible' role='alert' id='div_msg_result_insert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	            ACESSO EM UNIDADES ATUALIZADO COM SUCESSO!
	     </div>
	");
?>
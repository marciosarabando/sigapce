<?php
include ("../funcoes/verificaAtenticacao.php");
$id_procurador = $_GET['id_procurador'];
$nm_procurador = null;
$nr_tel_procurador = null;
$email_procurador = null;

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 	
				id_procurador AS ID_PROCURADOR,
				nm_procurador AS NM_PROCURADOR,
				nr_tel_procurador AS TELEFONE, 
		        email_procurador AS EMAIL
			FROM procurador
			WHERE id_procurador = $id_procurador";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	do
	  {
	  	$id_procurador = $linha['ID_PROCURADOR'];
	  	$nm_procurador = $linha['NM_PROCURADOR'];
		$nr_tel_procurador = $linha['TELEFONE'];
		$email_procurador = $linha['EMAIL'];
	
		
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

}


echo("
		<input id='id_procurador' value='" . $id_procurador . "' hidden></input>
		<table class='table table-condensed table-bordered'>
					<tr>
						<td>
							<label>NOME:</label> 
						</td>
						<td>
							<input type='text' class='upper form-control' id='nm_procurador' name='nm_procurador' placeholder='Nome do Procurador' value='". $nm_procurador ."'> 
						</td>
					</tr>
					<tr>
						<td>
							<label>TELEFONE:</label> 
						</td>
						<td>
							<input type='text' class='form-control' id='nr_tel_procurador' name='nr_tel_procurador' placeholder='Celular de contato do Procurador' value='". $nr_tel_procurador ."'> 
						</td>
					</tr>
					<tr>
						<td>
							<label>E-MAIL:</label> 
						</td>
						<td>
							
							<input type='text' class='form-control' id='email_procurador' name='email_procurador' placeholder='E-mail de contato do Procurador' value='". $email_procurador ."'> 
						</td>
					</tr>
		</table>

		<div id='div_btn_confirmaCadastroProcurador'>
		");
		if($nm_procurador == null)
		{
			echo("<button type='button' onClick='gravarDadosProcurador(\"incluir\")' class='btn btn-success'><i class='glyphicon glyphicon-ok'></i> CONFIRMAR</button>");
		}
		else
		{
			echo("<button type='button' onClick='gravarDadosProcurador(\"atualizar\")' class='btn btn-success'><i class='glyphicon glyphicon-ok'></i> CONFIRMAR</button>");
		}


echo("
				<button type='button' onClick='liberaCamposProcurador()' id='btn_alterarDadosProcurador' class='btn btn-primary'><i class='glyphicon glyphicon-pencil'></i> ALTERAR</button>
				<button type='button' onclick='escolheOutroProcurador()' class='btn btn-primary'><i class='glyphicon glyphicon-search'></i> SELECIONAR OUTRO PROCURADOR</button>
		</div>

		
	");
?>
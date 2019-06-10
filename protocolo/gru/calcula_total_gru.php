<?php
//CALCULA TOTAL GRU
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$valor = null;
$qtd = null;

$query = "SELECT SUM(valor) as valor FROM gru";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	  {
	  	$valor = $linha['valor'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

$query = "SELECT count(id_gru) as qtd FROM gru";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	do
	  {
	  	$qtd = $linha['qtd'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}


$valor = number_format($valor, 2, ',', '.');

echo("
		<table>


			<tr>
	    		<td>
	    			<h5>TOTAL DE GRU:</h5>
	    		</td>
	    		<td>
	    			<h5><b><font color='green'>$qtd</font></b></h5>
	    		</td>
	    	</tr>

	  		<tr>
	    		<td>
	    			<h5>ARRECADAÇÃO:</h5>
	    		</td>
	    		<td>
	    			<h5><b><font color='green'>R$ $valor</font></b></h5>
	    		</td>
	    	</tr>

	  		

	    	
	    </table>
	");

?>
<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");  

$id_carteira = $_GET['id_carteira'];

$query = "SELECT id_servico, ds_servico FROM servico WHERE id_carteira = $id_carteira";
// // executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
 echo("	
 		<div class='row'>
 			<div class='col-md-4'>
				<label>SERVIÇO</label>
			</div>

			<div class='col-md-8'>
				<select id='cmb_servico' name='cmb_servico' class='form-control input-sm' onchange=''>
				<option value='0' selected>*** DOCUMENTO NÃO ASSOCIADO A SERVIÇO ***</option>
	"
	);	
 do{
      echo("<option value='". $linha['id_servico'] . "'>" . $linha['ds_servico'] . "</option>");  
 	}while($linha = mysqli_fetch_assoc($dados));
 mysqli_free_result($dados); 
 echo("
 				</select>
 			</div>
 		</div>
 	");
}
?>
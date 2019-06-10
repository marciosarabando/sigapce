
<?php
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
define("Version", "12345");

$id_carteira = $_GET['id_carteira'];

//echo($id_carteira);

$query = "SELECT id_servico, ds_servico FROM servico WHERE id_servico  not in (11,26, 62, 63,67,68,72, 75,76, 79, 81, 65)";

if($id_carteira > 0)
{
	$query .= " and id_carteira = $id_carteira";
}


echo("<select id='cmb_servico' name='cmb_servico' class='form-control input-sm' onchange=''>");

//Preenche o combo do tipo de solicitação
//Conecta no Banco de Dados
include ("funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");


// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

echo("<option value='0' selected>*** TODOS ***</option>");

if($totalLinhas > 0)
{
do{


echo("<option value='". $linha['id_servico'] . "'>" . $linha['ds_servico'] . "</option>");


}while($linha = mysqli_fetch_assoc($dados));
mysqli_free_result($dados);

}
         
echo("</select>");

?>
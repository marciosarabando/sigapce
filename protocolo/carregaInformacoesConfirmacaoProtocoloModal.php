<?php
$tipo_doc_interessado = $_GET['tipo_doc_interessado'];
$cpf_cnpj = $_GET['cpf_cnpj'];
$nm_interessado = $_GET['interessado'];
$carteira = $_GET['carteira'];
$id_servico = $_GET['id_servico'];
$ic_procurador = $_GET['ic_procurador'];
$nm_procurador = mb_strtoupper($_GET['nm_procurador'],'UTF-8');

//Preenche o combo com os serviços relacionados a carteira selecionada.
//Conecta no Banco de Dados
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
					id_servico as id_servico,
					ds_servico as ds_servico
		 FROM servico WHERE id_servico in (" . $id_servico . ")";
// executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);


echo (
		"CONFERÊNCIA DA SOLICITAÇÃO:
		<br><br>
		<table class='table table-condensed table-bordered'>
			<tr>
				<td>
					<label>INTERESSADO:</label> 
				</td>
				<td>
					<label>". $nm_interessado ."</label> 
				</td>
			<tr>
			<tr>
				<td>
		");
if($tipo_doc_interessado == "PF")
{
	echo("<label>CPF</label> ");
}
else
{
	echo("<label>CNPJ:</label> ");
}

echo("       
				</td>
				<td>
					<label>". $cpf_cnpj ."</label> 
				</td>
			<tr>
			<tr>
				<td>
					<label>CARTEIRA:</label> 
				</td>
				<td>
					<label>". $carteira ."</label> 
				</td>
			<tr>
			<tr>
				<td>
					<label>SERVIÇO SOLICITADO:</label> 
				</td>
				<td>
					
					
		");

if($totalLinhas > 0)
{
	//echo("<option value='0'>SELECIONE...</option>");
	do{
		echo("<label>" . $linha['ds_servico'] . "</label><br>");			
	}while($linha = mysqli_fetch_assoc($dados));
	mysqli_free_result($dados);
}

echo("</td></tr>");

if($ic_procurador == "SIM")
{
	echo("
			<tr>
				<td>
					<label>PROCURAÇÃO?</label> 
				</td>
				<td>
					<label>SIM</label> 
				</td>
			<tr>
			<tr>
				<td>
					<label>PROCURADOR:</label> 
				</td>
				<td>
					<label>". $nm_procurador ."</label> 
				</td>
			<tr>
		");

}
else
{
	echo("
			<tr>
				<td>
					<label>PROCURAÇÃO?</label> 
				</td>
				<td>
					<label>NÃO</label> 
				</td>
			<tr>
		");
}

echo("</table>");

?>
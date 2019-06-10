<?php
$id_carteira = $_GET['id_carteira'];
$sg_tipo_interessado = $_GET['sg_tipo_interessado'];
$tp_interessado = null;

//Monta o Where de Acordo com o tipo de interessado
if($sg_tipo_interessado == 'PF')
{
	$tp_interessado = "st_pf = 1";
}
else if($sg_tipo_interessado == 'PJ')
{
	$tp_interessado = "st_pj = 1";
}
else if($sg_tipo_interessado == 'MIL')
{
	$tp_interessado = "st_mil = 1";
}
else if($sg_tipo_interessado == 'MAG')
{
	$tp_interessado = "st_mag = 1";
}

?>

<label>SELECIONE O SERVIÇO</label>
<select id="cmb_servico" name="cmb_servico" class="form-control" onchange='servicoSelecionado()'>
 	<?php
 		//Preenche o combo com os serviços relacionados a carteira selecionada.
		//Conecta no Banco de Dados
		include ("../funcoes/conexao.php");
		mysqli_query($conn,"SET NAMES 'utf8';");
		$query = "SELECT 
							id_servico as id_servico,
							ds_servico as ds_servico
				 FROM servico WHERE id_carteira = $id_carteira and id_servico  not in (11,26, 62, 63,67,68,72, 75,76, 79, 81) AND " . $tp_interessado . " ORDER BY ds_servico";
		// executa a query
		$dados = mysqli_query($conn,$query) or die(mysql_error());
		// transforma os dados em um array
		$linha = mysqli_fetch_assoc($dados);
		// calcula quantos dados retornaram
		$totalLinhas = mysqli_num_rows($dados);

		if($totalLinhas > 0)
		{
			echo("<option value='0'>SELECIONE...</option>");
			do{
				echo("<option value='". $linha['id_servico'] . "'>" . $linha['ds_servico'] . "</option>");			
			}while($linha = mysqli_fetch_assoc($dados));
			mysqli_free_result($dados);
		}
	?>						  
</select>
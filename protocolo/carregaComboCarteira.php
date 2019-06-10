<label>SELECIONE A CARTEIRA</label>

<select id="cmb_carteira" name="cmb_carteira" class="form-control" onchange="carregaComboServico()">
 	<?php
 		//Preenche o combo do tipo de solicitação
		//Conecta no Banco de Dados
		include ("../funcoes/conexao.php");
		mysqli_query($conn,"SET NAMES 'utf8';");
		$query = "SELECT 
							id_carteira as id_carteira,
							ds_carteira as ds_carteira
				 FROM carteira where id_carteira not in (6,8,9,10,13) order by ds_carteira";
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
				echo("<option value='". $linha['id_carteira'] . "'>" . $linha['ds_carteira'] . "</option>");			
			}while($linha = mysqli_fetch_assoc($dados));
			mysqli_free_result($dados);
		}
	?>						  
</select>
<br>
<div class="row">
	<div class="col-md-6">
		<select id="cmb_procurador" name="cmb_procurador" class="form-control" onchange="mostraDadosProcurador()">
		 	<?php
		 		//Preenche o combo do tipo de solicitação
				//Conecta no Banco de Dados
				include ("../funcoes/conexao.php");
				mysqli_query($conn,"SET NAMES 'utf8';");
				$query = "SELECT 
									id_procurador as id_procurador,
									nm_procurador as nm_procurador
						 FROM procurador order by nm_procurador";
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
						echo("<option value='". $linha['id_procurador'] . "'>" . $linha['nm_procurador'] . "</option>");			
					}while($linha = mysqli_fetch_assoc($dados));
					mysqli_free_result($dados);
				}
			?>						  
		</select>
	</div>
	<div class="col-md-1">
		<button onClick="criarCamposProcuradorInserir()" class="btn btn-default" id="btn_incluir_procurador"><i class="glyphicon glyphicon-plus-sign"></i> Incluir</button>
	</div>
</div>
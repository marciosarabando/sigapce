<?php
//BUSCAR DADOS DO CEDENTE DO ARMAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

$tipo_pessoa_cedente = $_GET['tipo_pessoa_cedente'];
$cpf_cnpj = $_GET['cpf_cnpj'];

if($tipo_pessoa_cedente == 'PF')
{
	$query = "SELECT id_interessado, nm_interessado, cr_interessado FROM interessado WHERE cpf_interessado = '$cpf_cnpj'";
}
else
{
	$query = "SELECT id_interessado, nm_interessado, cr_interessado FROM interessado WHERE cnpj_interessado = '$cpf_cnpj'";
}

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	do
	  {
	  		$id_interessado = $linha['id_interessado'];
	  		$nm_interessado = $linha['nm_interessado'];
	  		$cr_interessado = $linha['cr_interessado'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  echo("

	  			<div class='row'>
		 	 		<div class='col-md-3'>
		 	 			<label>CEDENTE:</label>
		 	 		</div>

		 	 		<div class='col-md-7'>
		 	 			<input type='text' class='form-control input-sm' value='$nm_interessado' disabled></input>
		 	 		</div>
		 	 	</div>

		 	 	<p>

		 	 	<div class='row'>
		 	 		<div class='col-md-3'>
		 	 			<label>CR:</label>
		 	 		</div>

		 	 		<div class='col-md-7'>
		 	 			<input type='text' class='form-control input-sm' value='$cr_interessado' disabled></input>
		 	 		</div>
		 	 	</div>

		 	 	<input id='txt_id_interessado_cedente' value='$id_interessado' hidden></input>

	  	");

}
else
{
	echo("
				<div class='row'>
		 	 		<div class='col-md-3'>
		 	 			<label>NOME:</label>
		 	 		</div>
		 	 		<div class='col-md-7'>
		 	 			<input id='txt_nome_cedente' type='text' class='upper form-control input-sm' onblur='exibe_botao_salvar_cedente()' placeholder='Insira o nome do Cedente'></input>
		 	 		</div>
		 	 	</div>

		 	 	<p>

		 	 	<div class='row'>
		 	 		<div class='col-md-3'>
		 	 			<label>CR:</label>
		 	 		</div>
		 	 		<div class='col-md-3'>
		 	 			<input id='txt_cr_cedente' type='text' class='form-control input-sm' onkeypress='return SomenteNumero(event)'></input>
		 	 		</div>

		 	 		<div class='col-md-1'>
		 	 			<label>FONE:</label>
		 	 		</div>
		 	 		<div class='col-md-3'>
		 	 			<input id='txt_fone_cedente' type='tel' class='form-control input-sm'></input>
		 	 		</div>
		 	 	</div>

		 ");


	echo("

		 	 	<p>

		 	 	<div class='row'>
		 	 		<div class='col-md-3'>
		 	 			<label>ESTADO:</label>
		 	 		</div>

		 	 		<div class='col-md-2'>
		");

						//CARREGA ESTADOS
						echo("<select id='cmb_estado' name='cmb_estado' class='form-control input-sm' onchange='carrega_combo_cidade_form_transf_arma()'>");
						include ("../funcoes/conexao.php");
						mysqli_query($conn,"SET NAMES 'utf8';");
						$query = "SELECT 
										 DISTINCT uf_cidade
									FROM cidade order by uf_cidade";
						$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
						$linha = mysqli_fetch_assoc($dados);
						$totalLinhas = mysqli_num_rows($dados);

						if($totalLinhas > 0)
						{
							echo("<option value='0'>UF...</option>");
							do{
								echo("<option value='". $linha['uf_cidade'] . "'>" . $linha['uf_cidade'] . "</option>");
							}while($linha = mysqli_fetch_assoc($dados));
							mysqli_free_result($dados);
						}

						echo("</select>");

					echo("</div>");

					echo("<div class='col-md-2'>
				 	 			<label>CIDADE:</label>
				 	 	  </div>
				 	 	  
				 	 	  <div class='col-md-3'>
				 	 		  <div id='div_cidades'>
				 	 		  		<select id='cmb_cidade' name='cmb_cidade' class='form-control input-sm' onchange=''>
				 	 		  			<option value='0'>CIDADE...</option>
				 	 		  		</select>
				 	 		  </div>		
				 	 	  </div>

			 		</div>
			 	 	
			 	 	<p>
			 	 	
			 	 	<div class='row'>
			 	 		<div class='col-md-3'>
			 	 			<label>E-MAIL:</label>
			 	 		</div>
			 	 		<div class='col-md-7'>
			 	 			<input id='email_cedente' type='text' class='lower form-control input-sm' onkeyup='exibe_botao_salvar_cedente()'></input>
			 	 		</div>
			 	 	</div>

			 	 	<p>
			 	 	
			 	 	<div class='row' id='div_btn_salvar_cedente' hidden>
			 	 		<div class='col-md-10'>
			 	 			<button class='btn btn-primary btn-block' onclick='salva_dados_cedente()'><span class='glyphicon glyphicon-floppy-disk'></span> SALVAR</button>
			 	 		</div>
			 	 	</div>

		");

}


?>
<?php
//EXIBE OS DETALHES DA MATÉRIA SELECIONADA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

$id_adt_materia = $_GET['id_adt_materia'];

$cpf_interessado_cedente = null;
$cnpj_interessado_cedente = null;

$nm_interessado_cedente = null;
$cr_interessado_cedente = null;

//CABEÇALHO DA MATÉRIA
$query = "SELECT 
				adt_materia.id_adt_materia,
		        adt_materia.id_adt_materia_tipo,
				processo.cd_protocolo_processo,
		        adt_materia_tipo.nm_adt_materia_tipo,
		        adt_materia_status.nm_adt_materia_status,
		        posto_graduacao.nm_posto_graduacao,
        		login.nm_guerra,
		        adt_materia_andamento.dt_adt_materia_andamento,
		        interessado.nm_interessado,
		        interessado.cr_interessado,
		        interessado.cpf_interessado,
		        interessado.cnpj_interessado,
		        servico.ds_servico, 
		        adt_materia.id_processo_status
		        
		FROM adt_materia
		
		LEFT JOIN processo on processo.id_processo = adt_materia.id_processo
		LEFT JOIN interessado on interessado.id_interessado = processo.id_interessado
		INNER JOIN adt_materia_tipo on  adt_materia_tipo.id_adt_materia_tipo = adt_materia.id_adt_materia_tipo
		INNER JOIN adt_materia_andamento on adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia
		INNER JOIN adt_materia_status on adt_materia_status.id_adt_materia_status = adt_materia_andamento.id_adt_materia_status
		INNER JOIN login on login.id_login = adt_materia_andamento.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		LEFT JOIN processo_servico on processo_servico.id_processo = processo.id_processo
		LEFT JOIN servico on servico.id_servico = processo_servico.id_servico
		
		WHERE 
		adt_materia_andamento.id_adt_materia_andamento IN (SELECT max(adt_materia_andamento.id_adt_materia_andamento) FROM adt_materia_andamento WHERE adt_materia_andamento.id_adt_materia = adt_materia.id_adt_materia)
		AND adt_materia.id_adt_materia = $id_adt_materia";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{
	do
	  {

  			$id_adt_materia = $linha['id_adt_materia'];
	        $id_adt_materia_tipo = $linha['id_adt_materia_tipo'];
			$cd_protocolo_processo = $linha['cd_protocolo_processo'];
	        $nm_adt_materia_tipo = $linha['nm_adt_materia_tipo'];
	        $nm_adt_materia_status = $linha['nm_adt_materia_status'];
	        $nm_posto_graduacao = $linha['nm_posto_graduacao'];
    		$nm_guerra = $linha['nm_guerra'];
	        $dt_adt_materia_andamento = $linha['dt_adt_materia_andamento'];
	        $nm_interessado = $linha['nm_interessado'];
	        $cr_interessado = $linha['cr_interessado'];
	        $cpf_interessado = $linha['cpf_interessado'];
	        $cnpj_interessado = $linha['cnpj_interessado'];
	        $gerenciado_por = $nm_posto_graduacao . " " . $nm_guerra;
	        $ds_servico = $linha['ds_servico'];
	        $id_processo_status = $linha['id_processo_status'];

	        if($id_processo_status == 6){
	        	$cor_status = 'green';
	        	$deferido = 'DEFERIDO';
	        }

	        if($id_processo_status == 7) {
	        	$cor_status = 'red';
	        	$deferido = 'INDEFERIDO';
	        }

	        if($id_processo_status == 13) {
	        	$cor_status = 'green';
	        	$deferido = 'DEFERIDO PARCIALMENTE'; 
	        }


	        //VERIFICA SE PESSOA JURIDICA
		  	if($cnpj_interessado != '')
		  	{
		  		$tipo_pessoa_requerente = 'PJ';
		  	}
		  	else
		  	{
		  		$tipo_pessoa_requerente = 'PF';	
		  	}

		  	if(trim($cpf_interessado) == '') {
				$cnpj_interessado =  preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5", $cnpj_interessado);
				$cpf_cnpj = "CNPJ"; 
				$nome_rs = "Razão Social";  
			}
			else {
				$cpf_interessado = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf_interessado);
				$cpf_cnpj = "CPF"; 
				$nome_rs = "Nome"; 
			}
	

	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  //EXIBE O CABECALHO DA MATÉRIA
	  echo("  			

	  			<table class='table table-condensed table-bordered'>
	  				
	  				<tr>
	  				<!-- 
	  					<td>
	  						<b>MATÉRIA:</b> $nm_adt_materia_tipo
	  					</td>
	  				--> 	
	  					<td>
	  						<b>SITUAÇÃO:</b> $nm_adt_materia_status
	  					</td>
	  				<!--  </tr> 

	  				<tr> --> 
	  					<td>
	  						<b>EM:</b> ".date('d/m/Y H:i', strtotime($dt_adt_materia_andamento))."
	  					</td>
	  					<td>
	  						<b>POR:</b> $gerenciado_por
	  					</td>
	  				</tr>
	  				</table>

	  				<font size=3 color='green'><b>PROCESSO: $cd_protocolo_processo</b></font>
	  			<p>

	  				<table class='table table-condensed table-bordered'>

					<tr>	
	  					<td colspan=2>
	  						<b>REQUERENTE:</b> $nm_interessado
	  					</td>
	  				</tr>
	  				

	  				<tr>
	  					<td>
	  						");
	  						 	if($tipo_pessoa_requerente == 'PF')
	  							{
	  								echo("<b>CPF:</b> $cpf_interessado");
	  							}
	  							else 
	  							{
	  								echo("<b>CNPJ:</b> $cnpj_interessado");
	  							} 
	  			echo(" 
	  					</td>
	  					<td>
	  						<b>CR:</b> $cr_interessado
	  					</td>
	  				</tr>

	  				<tr>
	  					<td colspan=2>
	  						<b>SERVIÇO:</b> $ds_servico
	  					</td>
	  					
	  				</tr>

	  				<tr>
	  					<td colspan=2>
	  						<font color = $cor_status><b>PRCESSO $deferido
	  					</td>
	  					
	  				</tr>

	  			</table>
	  	");

}

//EXIBE OS DETALHES DA MATÉRIA CONFORME O TIPO DA MATÉRIA
///////////////////////////////////////////////////////////

//compatibilização de variáveis
$adt_materia_tipo = $id_adt_materia_tipo;  

include('../trata_tipo_materia.php'); 

//////////////////////////////////////////////////////////
//echo $id_adt_materia_tipo; 

//formatando cpf / cnpj para o cedente de uma transferência
if(trim($cpf_interessado_cedente) == '') {
	$cpf_interessado_cedente =  preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5",
	$cnpj_interessado_cedente);

	$cpf_cnpj_cedente = "CNPJ"; 
	  
}
else {
	$cpf_interessado_cedente = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf_interessado_cedente);
	$cpf_cnpj_cedente = "CPF"; 
	
}


echo "
<table width='100%'>
<tr>
"; 
######################################
# Matérias sobre CR
######################################
if(
	$adt_materia_tipo == 1
 or $adt_materia_tipo == 12	
 or $adt_materia_tipo == 14
 or $adt_materia_tipo == 22
 or $adt_materia_tipo == 82
 or $adt_materia_tipo == 83
 or $adt_materia_tipo == 120
 or $adt_materia_tipo == 121
 )

 { 
	
	echo "
			<!-- CABEÇALHO --> 
			<table width='100%' class='table table-condensed table-bordered'>
			<tr>"; 
				
	if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121)
		echo "<td align='left'><b>Atividades</b></td>";
			
		if(
			$adt_materia_tipo == 1
		or $adt_materia_tipo == 12	
		or $adt_materia_tipo == 22
		or $adt_materia_tipo == 83
		or $adt_materia_tipo == 120
		or $adt_materia_tipo == 121
		) 
		{ //precisa dos dados do CR
			echo "<td align='left'><b>Nr CR</b></td>"; 

			if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121)
				echo "<td align='left'><b>Validade</b></td>";
			else
				echo "<td align='center'><b>Data do Cancelamento</b></td>";	 
		}

			echo "</tr>";
	///// fim do cabeçalho /////

			echo "					
			<tr>
			"; 

				if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121)
					echo "<td align='left' width='30%'>$atividades</td>"; 
			

				if(	$adt_materia_tipo == 1
					or $adt_materia_tipo == 12
					or $adt_materia_tipo == 22
					or $adt_materia_tipo == 83
					or $adt_materia_tipo == 120
					or $adt_materia_tipo == 121
				)
				{ //precisa dos dados do CR
					echo "<td align='left' width='10%'>$nr_cr</td>

						<td align='left' width='10%'>$validade_cr</td>";  
				}
								
			echo "</tr>
			</table>"; 

			 
} //if matérias sobre cr

#########################################################
# Mudança de vinculaçãode RM
#########################################################
if($adt_materia_tipo == 2)
{ 
	
	echo "
			<!-- CABEÇALHO --> 
			<table width='100%' class='table table-condensed table-bordered'>
			<tr>"; 
				
		echo "<td align='left'><b>Atividade</b></td>";
		
		echo "</tr>";
	///// fim do cabeçalho /////

			echo "					
			<tr>
			"; 

			echo "<td align='left' width='30%'>Mudança de vinculação da &nbsp;$rm_origem&ordf; &nbsp;para a &nbsp;$rm_destino&ordf; &nbsp;RM.</td>"; 
	
									
			echo "</tr>
			</table>"; 

			 
} //if mudança de vinculação de RM

##########################################################
#Matérias sobre Armamento
##########################################################
if(
				$adt_materia_tipo == 35 
			 or $adt_materia_tipo == 25	
			 or $adt_materia_tipo == 27
			 or $adt_materia_tipo == 36
			 or $adt_materia_tipo == 78
			 or $adt_materia_tipo == 84
			 or $adt_materia_tipo == 85
			 or $adt_materia_tipo == 86
			 or $adt_materia_tipo == 88
			 or $adt_materia_tipo == 46
			 or $adt_materia_tipo == 40
			 or $adt_materia_tipo == 43
			 or $adt_materia_tipo == 44
			 or $adt_materia_tipo == 47
			 or $adt_materia_tipo == 48
			 or $adt_materia_tipo == 49
			 or $adt_materia_tipo == 50
			 or $adt_materia_tipo == 51
			 or $adt_materia_tipo == 77
			 or $adt_materia_tipo == 102
			 or $adt_materia_tipo == 28
			 or $adt_materia_tipo == 30
			 or $adt_materia_tipo == 31
			 or $adt_materia_tipo == 32
			 or $adt_materia_tipo == 33
			 or $adt_materia_tipo == 34
			 or $adt_materia_tipo == 24
			 or $adt_materia_tipo == 37
			 or $adt_materia_tipo == 38
			 or $adt_materia_tipo == 39
			 or $adt_materia_tipo == 42
			 or $adt_materia_tipo == 74
		  ) 
		  { //Aquisição, autorização de Armto (pf e pj)  

			echo "
				<!-- DADOS DA MATÉRIA EM SI (uma por linha) --> 
				<table class='table table-condensed table-bordered'> 
					<tr>
						<td align='center' colspan='9'><font size='1'>
							<b>Acervo: $arma_acervo</b>
						</td>	
					</tr>"; 
					
				//se for transferência, exibir os dados do cedente				
				if(
						$adt_materia_tipo == 46
					 or $adt_materia_tipo == 40
					 or $adt_materia_tipo == 43
					 or $adt_materia_tipo == 44
					 or $adt_materia_tipo == 47
					 or $adt_materia_tipo == 48
					 or $adt_materia_tipo == 49
					 or $adt_materia_tipo == 50
					 or $adt_materia_tipo == 51
					 or $adt_materia_tipo == 77
				) 
				{
						echo "
					<tr>
						<td align='left' colspan='4' ><font size='1'>
							<b>DE: </b> $nm_interessado_cedente
						</td>

						<td align='left' colspan='2'> <font size='1'>
							<b>CR:</b> $cr_interessado_cedente 
						</td>	

						<td align='left' colspan='2' ><font size='1'> 
							<b>$cpf_cnpj_cedente:</b> $cpf_interessado_cedente<!-- CPF OU CNPJ --> 
						</td>
					</tr>
					";
					
					$nome_rs = "PARA";
					
					echo "
						<tr>
						<td align='left' colspan='4'><font size='1'>
							<b>$nome_rs:</b> $nm_interessado
						</td>

						<td align='left' colspan='2'> <font size='1'>
							<b>CR:</b> $cr_interessado 
						</td>

						<td align='left' colspan='2' > <font size='1'>
							<b>$cpf_cnpj:</b> $cpf_interessado<!-- CPF OU CNPJ --> 
						</td>
					</tr>";  
				}

/*
				else { //se nao for transferência

					echo "
					<tr>
						<td align='left' colspan='5' width='60%'> <font size='1'>
							<b>$nome_rs:</b> $nome_interessado
						</td>

						<td align='left' width='12%'> 
							<b>CR:</b> $cr_interessado 
						</td>

						<td align='left' colspan='2' width='25%'> 
							<b>CPF/CNPJ:</b> $cpf<!-- CPF OU CNPJ --> 
						</td>
					</tr>
					"; 

				}
*/				

					echo " 
					<!-- CABEÇALHO DOS DADOS DA ARMA --> 

					<tr> 
						<td align='center'>
							<font size='1'><b>Espécie</b>
						</td>

						<td align='center'>
							<font size='1'><b>Func.</b>
						</td>		

						<td align='center'>
							<font size='1'><b>Marca</b>
						</td>

						<td align='center'>
							<font size='1'><b>Calibre</b>
						</td>

						<td align='center'>
							<font size='1'><b>Modelo</b>
						</td>

						<td align='center'>
							<font size='1'><b>Acab.</b>
						</td>

						<td align='center'>
							<font size='1'><b>N Série</b>
						</td>

						<td align='center'>
							<font size='1'><b>Origem</b>
						</td>

						
					</tr> 

					<!-- DADOS DA ARMA --> 

					<tr> 
						<td align='center'>
							<font size='1'>$arma_especie
						</td>

						<td align='center'>
							<font size='1'>$arma_funcionamento
						</td>		

						<td align='center'>
							<font size='1'>$arma_fornecedor
						</td>

						<td align='center'>
							<font size='1'>$arma_calibre
						</td>

						<td align='center'>
							<font size='1'>$arma_modelo
						</td>

						<td align='center'>
							<font size='1'>$arma_acabamento
						</td>

						<td align='center'>
							<font size='1'>$nr_arma
						</td>

						<td align='center'>
							<font size='1'>$arma_pais_origem
						</td>
					</tr> 
					"; 

					//se não for transferência, mostrar os dados do fornecedor
					if(
						$adt_materia_tipo <> 46
					and $adt_materia_tipo <> 40
					and $adt_materia_tipo <> 43
					and $adt_materia_tipo <> 44
					and $adt_materia_tipo <> 47
					and $adt_materia_tipo <> 48
					and $adt_materia_tipo <> 49
					and $adt_materia_tipo <> 50
					and $adt_materia_tipo <> 51
					and $adt_materia_tipo <> 77
					and $adt_materia_tipo <> 36
					and $adt_materia_tipo <> 88
					and $adt_materia_tipo <> 25
					and $adt_materia_tipo <> 27
					and $adt_materia_tipo <> 30
					and $adt_materia_tipo <> 31
					and $adt_materia_tipo <> 32
					and $adt_materia_tipo <> 33
					and $adt_materia_tipo <> 34
					and $adt_materia_tipo <> 24
					and $adt_materia_tipo <> 37
					and $adt_materia_tipo <> 38
					and $adt_materia_tipo <> 39
					and $adt_materia_tipo <> 42
					and $adt_materia_tipo <> 74
					)
					{
						echo "
					
						<!-- DADOS DO FORNECEDOR --> 

						<tr>"; 

						//se não for autorização para compra, nem exclusão, mostrar o número da Nota Fiscal
						if($adt_materia_tipo <> 84 and $adt_materia_tipo <> 78) 
						{
							echo "
							<td align='left'>
								<font size='1'><b>N. Fiscal:</b> $nr_nota_fiscal
							</td>"; 
						}

						if ($adt_materia_tipo <> 78) //se não for exclusão, mostra o fornecedor
						{

							echo "
								<td align='left' colspan='8'> <font size='1'>
									<b>Fornecedor:</b> $nm_fornecedor<br>
									<b>CNPJ:</b> $cnpj_fornecedor 
								</td>
							</tr>"; 
						}
					} //if tipo <> 46...

					echo "
					</table>
					<br>"; 
				} // if materias sobre armamento

echo "</table>"; 


echo("<input type='text' id='txt_id_materia_selecionada' value='$id_adt_materia' hidden></input>");

?>
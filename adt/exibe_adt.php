<?php
include ("funcoes/verificaAtenticacao.php");
define("Version", "2");
if (!isset($_SESSION)) 
{
  session_start();
}
if(isset($_SESSION['id_login_sfpc']))
{

  $id_login_logado = $_SESSION['id_login_sfpc'];
  $id_login_perfil = $_SESSION['id_login_perfil'];
  $id_unidade_sfpc = $_SESSION['id_unidade_sfpc'];
}
$cpf_interessado_cedente = null;
$cnpj_interessado_cedente  = null;
$nm_interessado_cedente = null; 
$cr_interessado_cedente = null;

include ("funcoes/conexao.php");

?>

<ol class="breadcrumb">
  <li><a href="home.php">SIGAPCE</a></li>
  <li><a href="adt.php">ADT</a></li>
  <li class="active"><a href="adt.php?url=aditamento">GESTÃO DE ADITAMENTOS</a></li>
  <li class="active"><a href="adt.php?url=exibe_adt&id_adt=<?php echo $id_adt; ?>">EXIBIR ADITAMENTO</a></li>
</ol>

<?php 

//$id_adt definido em adt.php

$sql = "SELECT * FROM adt WHERE id_adt = $id_adt"; 
$result = mysqli_query($conn, $sql); 

if($result) {
	$row_array = mysqli_fetch_row($result); 
	$data = date("d/m/Y", strtotime($row_array[2]));
	$nr_adt =  $row_array[3];
	$nr_bar =  $row_array[4];  

	$a_data = explode('/', $data); 
	$dia = $a_data[0]; 
	$mes = $a_data[1];
	$ano = $a_data[2];

	include('adt/checa_mes.php');

	$data_txt = "$dia de $mes_n de $ano";   
	
}

echo "
<table width='90%' align='center' border='0'><!-- tabela principal --> 
	<tr>
		<td align='center'>
			<img src='img/brasao_republica.png' width='99'>
			<br><br>
		</td>
	</tr>	

	<tr>
		<td align='center'>
			MINISTÉRIO DA DEFESA<br>
			EXÉRCITO BRASILEIRO <br>
			COMANDO MILITAR DO SUDESTE<br>
			COMANDO DA 2a REGIÃO MILITAR<br>
			(Cmdo das Armas Prov PR/1890)<br>
			“REGIÃO DAS BANDEIRAS”<br><br>


			Quartel-General em São Paulo, $data_txt
			<br><br><br>
		</td>
	</tr>
	
	<tr>
		<td align='center'>
			<h3><b>ADITAMENTO $nr_adt AO BAR $nr_bar</b></h3> 
			<br><br><br>
		</td>	
	</tr>


	<tr>
		<td align='center'>
			Para conhecimento desta 2ª Região Militar e devida execução, publico o seguinte:
			<br><br>
		</td>
	<tr>

	<tr>
		<td align='center'>		
			<b>1ª PARTE – SERVIÇOS DIÁRIOS</b><br>
			Sem alteração<br><br>

			<b>2ª PARTE – INSTRUÇÃO</b><br> 
			Sem alteração<br><br>

			<b>3ª PARTE – ASSUNTOS GERAIS E ADMINISTRATIVOS</b>
			<br><br><br>

		</td>
	</tr>	

	<tr>
		<td>
			<b>1. ASSUNTOS GERAIS:</b><br>
			- Sem alteração
			<br><br>
		</td>
	</tr>

	<tr>
		<td>
			<b>2. ASSUNTOS ADMINISTRATIVOS:</b>
		</td>
	</tr>

	"; 

	############## <!-- AQUI COMEÇA A INSERÇÃO DAS MATÉRIAS --> #################
	$sql = "
	SELECT
	t1.*,
	t2.*,
	t3.cd_protocolo_processo, 
	t4.nm_interessado,
	t4.cpf_interessado,
	t4.cnpj_interessado, 
	t4.cr_interessado, 
	t2.id_adt_materia_tipo 
	
	FROM
	adt_materia AS t1,
	adt_materia_tipo AS t2,
	processo AS t3,  
	interessado AS t4
	
	WHERE t1.id_adt_materia_tipo = t2.id_adt_materia_tipo
	AND t1.id_processo = t3.id_processo
	AND t3.id_interessado = t4.id_interessado
	AND t1.id_adt = $id_adt 
	ORDER BY t2.nm_adt_materia_tipo, t1.id_processo_status, t3.cd_protocolo_processo 
	"; 

	$result = mysqli_query($conn,$sql);

	//zerando as variáveis
	$titulo = null;  
	$titulo_ant = null; 
	$pre_texto = null; 
	$pos_texto = null;
	$pos_texto_ant = null; //'xxx';  
	$pre_texto_ant = null;  
	$adt_materia_tipo_ant = 0; 
	$id_processo_status_ant = 0; 

	$registros = mysqli_num_rows($result); 

	for($linhas=1; $linhas <= $registros; $linhas ++){
		$row_array = mysqli_fetch_row($result); 
		 
			$id_adt_materia = $row_array[0];

			//echo "$id_adt_materia<br>";

			$titulo = $row_array[9];
			$pre_texto = $row_array[10];
			$pos_texto= $row_array[11]; //para o registro final (o último da lista)

			$cd_protocolo_processo = $row_array[12];

			$nome_interessado = $row_array[13];
			$cpf_interessado = $row_array[14];
			$cnpj_interessado = $row_array[15];
			$cr_interessado = $row_array[16];
			$adt_materia_tipo = $row_array[17];
			$id_processo_status = $row_array[3];

			
			//aqui inserimos toda a macarronada do tratamento e levantamento de dados para cada tipo de matéria 
			include('trata_tipo_materia.php'); 

			//se for uma empresa (tiver cnpj) mostra o cnpj ao invés do cpf
			//a função 'preg-replace' é usada aqui para formatar os números do cpf ou cnpj 
					
			if(trim($cpf_interessado) == '') {
				$cpf =  preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5", $cnpj_interessado);
				$cpf_cnpj = "CNPJ"; 
				$nome_rs = "Razão Social";  
			}
			else {
				$cpf = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf_interessado);
				$cpf_cnpj = "CPF"; 
				$nome_rs = "Nome"; 
			}

			//agora para o cedente de uma transferência
			if(trim($cpf_interessado_cedente) == '') {
				$cpf_interessado_cedente =  preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5",
				$cnpj_interessado_cedente);

				$cpf_cnpj_cedente = "CNPJ"; 
				  
			}
			else {
				$cpf_interessado_cedente = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf_interessado_cedente);
				$cpf_cnpj_cedente = "CPF"; 
				
			}


			//se não tiver CR, não exibe.
			if(trim($cr_interessado) == '')
				$cr = ''; 
			else
				$cr = "CR: $cr_interessado -"; 


			//DE ACORDO COM O STATUS DO PROCESSO...
			if($id_processo_status == 6){
	        	$deferido = 'DEFERIDOS';
	        }

	        elseif($id_processo_status == 7) {
	        	$deferido = 'INDEFERIDOS';
	        }

	        elseif($id_processo_status == 13) {
	        	$deferido = 'DEFERIDOS PARCIALMENTE'; 
	        }


		if($pos_texto_ant == 'xxx') //se for o primeiro registro, assumir o pós texto anterior como o dele. 
			$pos_texto_ant = $pos_texto;

		$exibe_cab = 0; 


		if($titulo <> $titulo_ant) { //mudou o título, é um novo tipo de matéria. 
			$titulo_ant = $titulo; 
			$id_processo_status_ant = $id_processo_status; 

			$exibe_cab = 1; 

			//se a matéria anterior for uma cada registro não é uma tabela em si (como em concessão de CR), vamos fechar a tabela anterior
			//antes de começar um novo tipo de matéria
			
			if($adt_materia_tipo <> 1 and $adt_materia_tipo_ant == 1){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 1 and $adt_materia_tipo_ant == 2){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 12 and $adt_materia_tipo_ant == 12){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 14 and $adt_materia_tipo_ant == 14){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 22 and $adt_materia_tipo_ant == 22){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 82 and $adt_materia_tipo_ant == 82){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 83 and $adt_materia_tipo_ant == 83){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 120 and $adt_materia_tipo_ant == 120){ 
				echo "</table>
						<br>";
			}

			if($adt_materia_tipo <> 121 and $adt_materia_tipo_ant == 121){ 
				echo "</table>
						<br>";
			}


			$adt_materia_tipo_ant = $adt_materia_tipo; 


			if($titulo <> null) {//não é o primeiro registro, vamos exibir o pós texto do registro anterior

				/////// Texto de fechamento do registro anterior //////
				if(trim($pos_texto_ant) <> '') {
					echo "
					<tr>
						<td align='justify'>
							$pos_texto_ant
							<br><br>
						</td>
					</tr>
					";
				} // if pos_texto_ant <> ''

				$pos_texto_ant = $pos_texto; //agora assumimos o pós texto anterior como este registro aqui
			
			} //if titulo <> null

			////// Dados do registro atual ///////
		

			/////// Título da matéria //////
			echo "
			<tr>
				<td align='center'>
					<br><br>
					<b>$titulo</b>
				</td>
			</tr>
			"; 

			/////// Texto de abertura //////
			//if(trim($pre_texto) <> '') {
				echo "
				<tr>
					<td align='justify'>
						$pre_texto
						<br><br>
					</td>
				</tr>"; 

				echo "
		<tr>	
			<td> <!-- INÍCIO DA CÉLULA DOS DADOS DA MATÉRIA --> 

			"; 

			//} //if pre_texto <> ''
		} //if mudou o título


		//SE MUDOU O STATUS


		if($id_processo_status_ant <> $id_processo_status)
		{	
			$exibe_cab = 1;


			//se for uma matéria em que cada registro não é uma tabela independente, fechar a tabela. 
			if(
				$adt_materia_tipo == 1
			 or $adt_materia_tipo == 2	
			 or $adt_materia_tipo == 12	
			 or $adt_materia_tipo == 14
			 or $adt_materia_tipo == 22
			 or $adt_materia_tipo == 82
			 or $adt_materia_tipo == 83
			 or $adt_materia_tipo == 120
			 or $adt_materia_tipo == 121
			 )
			{
				echo "</table><br>";
			}			
		
			$id_processo_status_ant = $id_processo_status; 
		}	 

		if(
			$adt_materia_tipo == 1
		 or $adt_materia_tipo == 2	
		 or $adt_materia_tipo == 12	
		 or $adt_materia_tipo == 14
		 or $adt_materia_tipo == 22
		 or $adt_materia_tipo == 82
		 or $adt_materia_tipo == 83
		 or $adt_materia_tipo == 120
		 or $adt_materia_tipo == 121
		 )

		 { 
			if($exibe_cab == 1) {
				if($adt_materia_tipo == 2)
					$cpf_cnpj = 'CPF / CNPJ'; 
			
				echo "					
					<b>Processos $deferido</b><br><p></b>
					<!-- CABEÇALHO --> 
					<table width='100%' border='1' cellpadding='10'>
					<tr>
						<td align='left'><b>Processo</b></td>"; 

			if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121)
				echo "<td align='left'><b>Atividades</b></td>";
					
			echo "				
						<td align='left' valign='top'><b>$nome_rs</b></td>
						<td align='left' valign='top'><b>$cpf_cnpj</b></td>
				"; 

				if(
					$adt_materia_tipo == 1
				or $adt_materia_tipo == 2	
				or $adt_materia_tipo == 12	
				or $adt_materia_tipo == 22
				or $adt_materia_tipo == 83
				or $adt_materia_tipo == 120
				or $adt_materia_tipo == 121
				) 
				{ //precisa dos dados do CR
					echo "<td align='left'><b>Nr CR</b></td>"; 

					if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121 and $adt_materia_tipo <> 2)
						echo "<td align='left'><b>Validade</b></td>";

					if($adt_materia_tipo == 12 or $adt_materia_tipo == 121)
						echo "<td align='center'><b>Data do Cancelamento</b></td>";	 
				}

					echo "</tr>";
			} //if exibe_cab = 1

			if($adt_materia_tipo == 2)
				$atividades = "Mudança de vinculação da $rm_origem&ordf; para a $rm_destino&ordf; RM."; 

					echo "					
					<tr>
						<td align='left' valign='top' width='9%'>$cd_protocolo_processo</td>"; 

						if($adt_materia_tipo <> 12 and $adt_materia_tipo <> 121)
							echo "<td align='left' width='31%'>$atividades</td>"; 

						echo "
						<td align='left' valign='top' width='40%'>$nome_interessado</td>

						<td align='left' valign='top' width='15%'>$cpf <!-- CPF OU CNPJ --> </td>
						"; 

						if(	$adt_materia_tipo == 1
							or $adt_materia_tipo == 2
							or $adt_materia_tipo == 12
							or $adt_materia_tipo == 22
							or $adt_materia_tipo == 83
							or $adt_materia_tipo == 120
							or $adt_materia_tipo == 121
						)
						{ //precisa dos dados do CR
							echo "<td align='left' valign='top' width='10%'>$nr_cr</td>"; 

							if(	$adt_materia_tipo <> 2)
								echo "<td align='left' valign='top' width='10%'>$validade_cr</td>";  
						}
										
					echo "</tr>"; 
					 
				} //if tipo_materia = cr pessoa física ou jurídica

		
		
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

		  		if($exibe_cab == 1) //no caso dematérias sobre armanento, a variável '$exibe_cab' só define se exibimos o status ($deferido) ou não 
				{
				 	echo "<b>Processos $deferido</b><br><p></b>"; 
				}

			echo "
				<!-- DADOS DA MATÉRIA EM SI (uma por linha) --> 
				<table width='100%' border='1' cellpadding='10'>
					<tr>
						<td align='center' colspan='9'>
							<b>PROCESSO: $cd_protocolo_processo "; 

							echo "- Acervo: $arma_acervo</b>
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
						<td align='left' colspan='5' width='60%'>
							<b>DE: </b> $nm_interessado_cedente
						</td>

						<td align='left' width='12%'> 
							<b>CR:</b> $cr_interessado_cedente 
						</td>	

						<td align='left' colspan='2' width='25%'> 
							<b>$cpf_cnpj_cedente:</b> $cpf_interessado_cedente<!-- CPF OU CNPJ --> 
						</td>
					</tr>
					";
					
					$nome_rs = "PARA";
					
					echo "
						<tr>
						<td align='left' colspan='5' width='60%'>
							<b>$nome_rs:</b> $nome_interessado
						</td>

						<td align='left' width='12%'> 
							<b>CR:</b> $cr_interessado 
						</td>

						<td align='left' colspan='2' width='25%'> 
							<b>$cpf_cnpj:</b> $cpf<!-- CPF OU CNPJ --> 
						</td>
					</tr>";  
				}

				else { //se nao for transferência

					echo "
					<tr>
						<td align='left' colspan='5' width='60%'>
							<b>$nome_rs:</b> $nome_interessado
						</td>

						<td align='left' width='12%'> 
							<b>CR:</b> $cr_interessado 
						</td>

						<td align='left' colspan='2' width='25%'> 
							<b>$cpf_cnpj:</b> $cpf<!-- CPF OU CNPJ --> 
						</td>
					</tr>
					"; 

				}

					echo " 
					<!-- CABEÇALHO DOS DADOS DA ARMA --> 

					<tr> 
						<td align='center'>
							<b>Espécie</b>
						</td>

						<td align='center'>
							<b>Funcionamento</b>
						</td>		

						<td align='center'>
							<b>Marca</b>
						</td>

						<td align='center'>
							<b>Calibre</b>
						</td>

						<td align='center'>
							<b>Modelo</b>
						</td>
						
						<td align='center'>
							<b>Qtd Cano</b>
						</td>
						<td align='center'>
							<b>Comp Cano</b>
						</td>
						<td align='center'>
							<b>Alma</b>
						</td>
						<td align='center'>
							<b>Nº Raias</b>
						</td>
						<td align='center'>
							<b>Sentido</b>
						</td>
						<td align='center'>
							<b>Carregamento</b>
						</td>						
						<td align='center'>
							<b>Acabamento</b>
						</td>

						<td align='center'>
							<b>Nr de Série</b>
						</td>

						<td align='center'>
							<b>Origem</b>
						</td>
						
					</tr> 

					<!-- DADOS DA ARMA --> 

					<tr> 
						<td align='center'>
							$arma_especie
						</td>

						<td align='center'>
							$arma_funcionamento
						</td>		

						<td align='center'>
							$arma_fornecedor
						</td>

						<td align='center'>
							$arma_calibre
						</td>

						<td align='center'>
							$arma_modelo
						</td>

						<td align='center'>
							$qtd_cano
						</td>
						<td align='center'>
							$comprimento_cano
						</td>
						<td align='center'>
							$alma
						</td>						
						<td align='center'>
							$nr_raias
						</td>
						<td align='center'>
							$sentido_raia
						</td>
						<td align='center'>
							$qtd_carregamento
						</td>
						<td align='center'>
							$arma_acabamento
						</td>

						<td align='center'>
							$nr_arma
						</td>

						<td align='center'>
							$arma_pais_origem
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
								<b>Nota Fiscal Nr:</b> $nr_nota_fiscal
							</td>"; 
						}

						if ($adt_materia_tipo <> 78) //se não for exclusão, mostra o fornecedor
						{

							echo "
								<td align='left' colspan='8'> 
									<b>Fornecedor:</b> $nm_fornecedor<br>
									<b>CNPJ:</b> $cnpj_fornecedor 
								</td>
							</tr>"; 
						}
					} //if tipo <> 46

					echo "
					</table>
					<br>"; 
				} // if tipo materia = aquisicao de armto

			//echo "
			//</td> <!-- FIM DA CÉLULA DOS DADOS DA MATÉRIA --> 
		//</tr>
		//";

	} //for linhas 

	//se a matéria anterior for do tipo que cada registro não é uma tabela em si, mas uma linha da tabela, (como em concessão de CR), vamos fechar a tabela anterior
	//antes de começar um novo tipo de matéria
	
			if(
					$adt_materia_tipo_ant == 1
				or $adt_materia_tipo_ant == 2	
				or $adt_materia_tipo_ant == 12	
				or $adt_materia_tipo_ant == 14
				or $adt_materia_tipo_ant == 22
				or $adt_materia_tipo_ant == 82
				or $adt_materia_tipo_ant == 83
				or $adt_materia_tipo_ant == 120
				or $adt_materia_tipo_ant == 121
			)
			{ 
				echo "</table>
						<br>
					</td>
					</tr>	";
			}
			

	/////// Texto de fechamento do último registro //////
	//if(trim($pos_texto) <> '') {
		echo "
			<tr>
				<td align='justify'>
					$pos_texto
					<br><br>
				</td>
			</tr>
			";
	//} // if pos_texto <> ''
		

	echo "	
		<tr>
		<td align='center'>	
			<br>	
			<b>4ª PARTE – JUSTICA E DISCIPLINA</b><br>
			Sem alteração
		</td>
	</tr>
	"; 	

############# ASSINATURAS ##################



$sql3 = "SELECT * FROM adt_assinaturas WHERE id_unidade = $id_unidade_sfpc order by ordem"; 
$result3 = mysqli_query($conn,$sql3);	

if($result3) {
	$regs = mysqli_num_rows($result3);

	for($linhas3=1; $linhas3 <= $regs; $linhas3 ++) {
		$row_array3 = mysqli_fetch_row($result3);

		$nm_adt_assinaturas = $row_array3[0];
		$pg_adt_assinaturas = $row_array3[1];
		$funcao_adt_assinaturas = $row_array3[2];

		echo "
		<tr>
			<td width='100%' align='center'>
				<br><br><br><br><br><br>
				<b>$nm_adt_assinaturas - $pg_adt_assinaturas</b><br>
				$funcao_adt_assinaturas
			</td>
	  	</tr>
	  "; 

	} // for linhas3
	
} //if result2



echo "</table>";  // tabela principal


?>

<br><br>
<a href="adt.php?url=aditamento">Voltar</a>
<?php
//FORMULÁRIO DE INSERÇÃO DE DADOS PARA MATÉRIA
//TIPO DA MATÉRIA: AQUISIÇÃO

include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');

echo("

	");

$id_adt_materia_tipo = $_GET['id_adt_materia_tipo']; 
$id_processo = $_GET['id_processo']; 
?>


<div class='row'>
	<div class='col-md-12'>
		<fieldset>
		 	 	<legend>MATÉRIA PARA O ADITAMENTO AO BAR</legend>


		 	 	<!-- INSERIR AQUI OS DADOS DO CR (número / Validade) --> 
		 	 	
		 	 	<?php

		 	 	//revalidação / 2a via / cancelamento de CR PJ
		 	 	if($id_adt_materia_tipo == 83 or $id_adt_materia_tipo == 120 or $id_adt_materia_tipo == 121) 
		 	 	{
		 	 		//procurando o CR do interessado
		 	 		$sql_cr = "
		 	 		SELECT t2.cr_interessado

		 	 		FROM processo as t1, interessado as t2

		 	 		WHERE t1.id_interessado = t2.id_interessado
		 	 		AND t1.id_processo = $id_processo
		 	 		"; 

		 	 		$result_cr = mysqli_query($conn, $sql_cr); 

		 	 		$cr_interessado = null; 

		 	 		if($result_cr) {
		 	 			$row_array_cr = mysqli_fetch_row($result_cr); 
		 	 			$cr_interessado = $row_array_cr[0]; 
		 	 		}

			 	 	echo ' 
			 	 	<div class="row">
			 	 		<div class="col-md-4">
		               		<label>NÚMERO DO CR</label>
		              	</div>

		              	<div class="col-md-8">
		              		<input type="text" class="form-control input-sm" id="txt_nr_cr" name="txt_nr_cr" value="' . $cr_interessado . '" placeholder="Digite o Número do CR"/>
		              	</div>
			        </div>
					
					<br>

			        <div class="row">
	 					<div class="col-md-4">';

	                		if($id_adt_materia_tipo == 121) 
	                			echo '<labeL>Data do Cancelamento</label>'; 
	                		else
	                			echo '<labeL>VALIDADE</label>';
	              		

	              		echo '</div>  
	              		
	              		<div class="col-md-8">
	              		';

            		$data = date("Y-m-d"); 

					$arr_data = explode("-",$data);
					$dia_cr = $arr_data[2];
					$mes_cr = $arr_data[1];
					$ano_cr = $arr_data[0];


					echo '<select id = "dia_val_cr" name = "dia_val_cr">';

					for ($dia = 1; $dia <= 31; $dia ++) {
						if ($dia == (int)$dia_cr) 
						    $selected = ' selected="selected"';
						else
						    $selected = '';

						echo '<option value="' . $dia . '"' . $selected . '>' . sprintf("%02d", $dia);
					}
					echo '</select>';


					echo '<select id = "mes_val_cr" name = "mes_val_cr">';

					for ($mes = 1; $mes <= 12; $mes ++) {
						if ($mes == (int)$mes_cr) 
						    $selected = ' selected="selected"';
						else
						    $selected = '';

						echo '<option value="' . $mes . '"' . $selected . '>' . sprintf("%02d", $mes);
					}
					echo '</select>';


					echo '<select id = "ano_val_cr" name = "ano_val_cr">';

					for ($ano = 2010; $ano <= 2045; $ano ++) {
						if ($ano == (int)$ano_cr) 
						    $selected = ' selected="selected"';
						else
						    $selected = '';

						echo '<option value="' . $ano . '"' . $selected . '>' . $ano;
					}
					echo '</select>
				
	              		</div>
	            	</div> 
	            				
	            	<br>'; 
        		} // if tipo = 105 /revalidação de cr pj

        		//se não for cancelamento, pedir as atividades PCE
        		if($id_adt_materia_tipo <> 121)
        		{
		 	 
		 	 		echo "
		 	 	 <div class='row' id='div_combo_tipo_atividade'>
		 	 		<div class='col-md-4'>
	               		<label>TIPO DE ATIVIDADE</label>
	              	</div>
	              	<div class='col-md-8'>Conforme registro";

	              	/*
	              	echo "<select id='cmb_tipo_atividade' name='cmb_tipo_atividade' class='form-control input-sm' onchange='carrega_combo_atividades_pj()'>
		 	 						<option value='0'>SELECIONE...</option>
		 	 		"; 
		 	 						 
								      //Preenche o combo do FORNECEDOR
								      //Conecta no Banco de Dados
								      include ("../../funcoes/conexao.php");
								      mysqli_query($conn,"SET NAMES 'utf8';");
								      $query = "SELECT 
								      					id_adt_atividade_pj_tipo,
								      					nm_adt_atividade_pj_tipo
								      	 			FROM adt_atividade_pj_tipo";
								      // executa a query
								      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
								      // transforma os dados em um array
								      $linha = mysqli_fetch_assoc($dados);
								      // calcula quantos dados retornaram
								      $totalLinhas = mysqli_num_rows($dados);

								      if($totalLinhas > 0)
								      {
								        do{
								          echo("<option value='". $linha['id_adt_atividade_pj_tipo'] . "'>" . $linha['nm_adt_atividade_pj_tipo'] . "</option>");

								        }while($linha = mysqli_fetch_assoc($dados));
								        mysqli_free_result($dados);
								        
								      }
					echo "			                  
		 	 			</select>	
	              	</div>
		 	 	</div>

		 	 	<p>

		 	 	<div id='div_combo_atividades'></div>

		 	 	<p>

		 	 	<div id='div_st_informa_pce'></div>

				<div id='div_combo_tipo_pce'></div>		 	 		

				<p>

				<div id='div_combo_pce'></div>		

				<p>

				<div id='div_st_informa_quantidade'></div>

								
				<div id='div_quantidade' hidden>
					
					<div class='row'>
			 			<div class='col-md-4'>
							<label>QUANTIDADE MÁXIMA</label>
						</div>

						<div class='col-md-2'>
							<input type='text' onblur='' class='form-control input-sm' id='txt_qtd_max' name='txt_qtd_max' placeholder='QTD MAX'/>
						</div>

						<div class='col-md-2'>
							<label><div id='div_unidade_pce'></div></label>
						</div>
					</div>
				</div>


				<p>

				<div id='div_btn_adicionar_atv_pce_pj' hidden>
					<button class='btn btn-small btn-block btn-primary' type='button' onclick='incluir_atividade_pce_selecionada()'><i class='glyphicon glyphicon-arrow-down'></i> Incluir Atividade / PCE</button>
				</div>		


		 	 	<p>

		 	 	<div class='panel panel-default' id='div_quadro_atv_pce_selecionadas' hidden>
					<div class='panel-heading'><div class='row'>
									<div class='col-md-5'>
										<label>ATIVIDADE</label>
									</div>
									
									<div class='col-md-5'>
										<label>PRODUTO</label>
									</div>
									
									<div class='col-md-1'>
										<label>QTD</label>
									</div>
									
									<div class='col-md-1' id='div_acao_quadro_atividades_pce_pj'>
										<label>AÇÃO</label>
									</div>
								</div></div>
						<div class='panel-body'>
		 	 				<div id='div_atividades_selecionadas'>
		 	 					
		 	 				</div>
		 	 			</div>
		 	 		
		 	 	</div>


		 	 	<p>	
			

		 	 	<div class='row' id='div_btn_novo_finalizar' hidden>
		 	 		<div class='col-md-6'>
			 	 		<div id='div_btn_nova_atv_pce_pj'>
							<button class='btn btn-small btn-block btn-primary' type='button' onclick='nova_inclusao_atv_pce_pj()'><i class='glyphicon glyphicon-plus'></i> NOVA ATIVIDADE / PCE</button>
						</div>
					</div>
					"; 
*/
				} //if tipo <> 121

				?>
				<!--
					<div class='col-md-6'>
			 	 		<div id='div_btn_confirmar_atv_pce_pj'>
							<button class='btn btn-small btn-block btn-success' type='button' onclick='concluir_atividade_pce_selecionada()'><i class='glyphicon glyphicon-ok'></i> CONCLUIR INCLUSÃO</button>
						</div>
					</div>
				</div>

-->


				<p>		

	<!-- Gambiarra para ativar o botão de confirmar sem precisar do focus ou onchange nos inputs acima --> 	
    <iframe style='width:0;height:0;border:0; border:none;' onload='concluir_atividade_pce_selecionada()'></iframe>
		 	 	
		 	 	<?php 

		 	 	if($id_adt_materia_tipo <> 121) {		
		 	 /*	echo "<div id='div_info_atv_pce_pj' class='alert alert-info'>
		 	 		<p class='text-info'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> Informe as Atividades / PCE / Quantidades Deferidas no Processo</p>
		 	 	</div>";
		 	 	*/ 
		 	 }

		 	 ?>
<!-- 
		 	 	<div id='div_info_prosseguir_status' class='alert alert-success' hidden>
		 	 		<p class='text-success'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> <b>ATIVIDADES / PCE INFORMADOS!</b> Prossiga com a alteração do Estado do Processo.</p>
		 	 	</div>
-->
		</fieldset>
	</div>
</div>


<input type='text' id='txt_id_atv_pce_incluidos' hidden></input>
<input type='text' id='txt_nr_ordem_atv_pce_incluidos' value='1' hidden></input>



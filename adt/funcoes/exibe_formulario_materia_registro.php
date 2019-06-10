<?php
//FORM GESTÃO DE ADITAMENTOS
include ("../../funcoes/verificaAtenticacao.php");
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

include ("../../funcoes/conexao.php");

$id_adt_materia_tipo = $_GET['id_adt_materia_tipo']; 
$id_processo = $_GET['id_processo']; 

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Serviço de Fiscalização de Produtos Controlados SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="TELA DE GESTÃO DE ADITAMENTO">
  <meta name="author" content="1 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  
  <script src="adt/adt.js?<?php echo Version; ?>" type="text/javascript"></script>

  <script src="js/jquery.dataTables.js"></script>
  <style type="text/css">
    @import "css/jquery.dataTables.css";
  </style>

  <script src="js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="js/jquery.printElement.js" type="text/javascript"></script>

</head> 


<div class='row'>
	<div class='col-md-12'>
		<fieldset>
		 	 	<legend>MATÉRIA PARA O ADITAMENTO AO BAR</legend>

		 	 	<?php
		 	 	//se a matéria não for de cancelamento, vamos pedir as atividadades do CR
		 	 	if($id_adt_materia_tipo <> 12) {
		 	 		echo "
		 	 	
			 	 	<div id='div_info_atividades' class='alert alert-info'>
			 	 		<p class='text-info'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> Informe as atividades solicitadas pelo Requerente e demais dados abaixo</p>
			 	 	</div>
			 	 	
			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_1' name='chk_atividade' value='1'> </input> TIRO ESPORTIVO 
			 	 	</label>

			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_2' name='chk_atividade' value='2'>  </input> CAÇADOR
			 	 	</label>

			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_3' name='chk_atividade' value='3'>  </input> COLECIONADOR
			 	 	</label>

			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_3' name='chk_atividade' value='4'>  </input> INSTRUTOR
			 	 	</label>

			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_3' name='chk_atividade' value='5'>  </input> PROCURADOR
			 	 	</label>

			 	 	<label class='checkbox'>
			 	 		<input type='checkbox' id='chk_3' name='chk_atividade' value='6'>  </input> UTILIZAÇÃO DE VEÍCULO BLINDADO
			 	 	</label>

			 	 	<br>

			 	 	"; 
			 	 } // if tipo < 12 (cancelamento de cr)

		 	 	//INSERIR AQUI OS DADOS DO CR (número / Validade)
		 	 	 
		 	 	//2a via / cancelamento / revalidação de CR
		 	 	if($id_adt_materia_tipo == 1 or $id_adt_materia_tipo == 12 or $id_adt_materia_tipo == 22) 
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
			 	 		<div class="col-md-3">
		               		<label>NÚMERO DO CR</label>
		              	</div>

		              	<div class="col-md-7">
		              		<input type="text" class="form-control input-sm" id="txt_nr_cr" name="txt_nr_cr" value="' . $cr_interessado . '" placeholder="Digite o Número do CR"/>
		              	</div>
			        </div>
					
					<br>

			        <div class="row">
	 					<div class="col-md-3">'; 

	 						if($id_adt_materia_tipo == 12) 
	                			echo '<labeL>Data do Cancelamento</label>'; 
	                		else
	                			echo '<labeL>VALIDADE</label>';
	              		
	              		echo '</div>  
	              		
	              		<div class="col-md-7">
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
						echo '</select>';
						
              		echo '</div>
            		</div> '; 
				} //if tipo materia = 22            

            	?>
            					
            	<br>

		 	 	<button class="btn btn-success btn-block" id='btn_confirma_atividades' type="button" onclick='confirmar_atividades_selecionadas()'>CONFIRMAR INFORMAÇÕES</button>

		 	 	<div id='div_info_prosseguir_status' class="alert alert-success" hidden>
		 	 		<p class='text-success'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> <b>Atividades Informadas!</b> Prossiga com a alteração do Estado do Processo.</p>
		 	 	</div>

		</fieldset>
	</div>
</div>	
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

$rm_origem_ant = null;
$rm_destino_ant = null;

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

		 	 		/*
		 	 		echo ' 
			 	 	<div class="row">
			 	 		<div class="col-md-3">
		               		<label>NÚMERO DO CR</label>
		              	</div>

		              	<div class="col-md-7">
		              		<input type="text" class="form-control input-sm" id="txt_nr_cr" name="txt_nr_cr" value="' . $cr_interessado . '" placeholder="Digite o Número do CR"/>
		              	</div>
			        </div>
					
					<br>';
					*/
					
					////////////// RM ORIGEM /////////////////
					echo '
			        <div class="row">
	 					<div class="col-md-3">'; 

               			echo '<labeL>RM DE ORIGEM</label>';
	              		
	              		echo '</div>  
	              		
	              		<div class="col-md-7">
	                	'; 


                		$data = date("Y-m-d"); 

						$arr_data = explode("-",$data);
						$dia_cr = $arr_data[2];
						$mes_cr = $arr_data[1];
						$ano_cr = $arr_data[0];


						echo '<select id = "rm_origem" name = "rm_origem">';

						for ($rm_origem = 1; $rm_origem <= 12; $rm_origem ++) {
							if ($rm_origem == (int)$rm_origem_ant) 
							    $selected = ' selected="selected"';
							else
							    $selected = '';

							echo '<option value="' . $rm_origem . '"' . $selected . '>' . $rm_origem . '&ordf; &nbsp;RM';
						}
						echo '</select>
						<br><br>';
					
              		echo '</div>
            		</div> '; 


            		/////////// RM DESTINO /////////////

            		echo '<div class="row">
	 					<div class="col-md-3">'; 

               			echo '<labeL>RM DE DESTINO</label>';
	              		
	              		echo '</div>  
	              		
	              		<div class="col-md-7">
	                	'; 


                		$data = date("Y-m-d"); 

						$arr_data = explode("-",$data);
						$dia_cr = $arr_data[2];
						$mes_cr = $arr_data[1];
						$ano_cr = $arr_data[0];


						echo '<select id = "rm_destino" name = "rm_destino">';

						for ($rm_destino = 1; $rm_destino <= 12; $rm_destino ++) {
							if ($rm_destino == (int)$rm_destino_ant) 
							    $selected = ' selected="selected"';
							else
							    $selected = '';

							echo '<option value="' . $rm_destino . '"' . $selected . '>' . $rm_destino . '&ordf; &nbsp;RM';
						}
						echo '</select>';
					
              		echo '</div>
            		</div> '; 

            	?>
            					
            	<br>

		 	 	<button class="btn btn-success btn-block" id='btn_confirma_atividades' type="button" onclick='confirmar_atividades_selecionadas()'>CONFIRMAR INFORMAÇÕES</button>

		 	 	<div id='div_info_prosseguir_status' class="alert alert-success" hidden>
		 	 		<p class='text-success'>&nbsp<i class='glyphicon glyphicon-info-sign'></i> <b>Dados Informados!</b> Prossiga com a alteração do Estado do Processo.</p>
		 	 	</div>

		</fieldset>
	</div>
</div>	
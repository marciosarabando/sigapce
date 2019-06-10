<?php
//Insere Data/Hora Criadas na Agenda
include ("formata_dados.php");
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

if (!isset($_SESSION)) 
{
	session_start();
}
$id_login = $_SESSION['id_login_sfpc'];
$nr_unidade = $_GET['nr_unidade'];
$dt_agendamento = substr($_GET['dt_agendamento'],-16,10);
$dados_janela = explode ('@',$_GET['dados']);

function cria_horarios_data($id_agendamento_data, $dados_janela)
{
	include ("../../funcoes/conexao.php");
	mysqli_query($conn,"SET NAMES 'utf8';");
	//INSERE OS HORÁRIOS SELECIONADOS COM SUAS RESPECTIVAS PARAMETRIZAÇÕES
	for($h = 0; $h < count($dados_janela); $h++)
	{
			//$hora = $horas[$h];
			$linha_dados = $dados_janela[$h];
			$linha_dados_hora = explode('-', $linha_dados);

			$hora = $linha_dados_hora[0];
			$qtd_atendente_horario = $linha_dados_hora[1];
			$qtd_protocolo_max_horario = $linha_dados_hora[2];
			$id_tipo_usuario_permitido = $linha_dados_hora[3];
			$id_assuntos_permitido_horario = explode(',', $linha_dados_hora[4]);

			//echo($hora . " " . $qtd_atendente_horario . " " . $qtd_protocolo_max_horario . " " . $id_tipo_usuario_permitido . " " . $id_assuntos_permitido_horario);
			//echo("<br>");

			$query = "INSERT INTO agendamento_horario VALUES (null,$id_agendamento_data,'$hora','$qtd_atendente_horario',0,'$qtd_protocolo_max_horario')";
			if (mysqli_query($conn,$query) or die(mysql_error()))
			{
				//ID DO REGISTRO DO HORÁRIO INSERIDO
					$id_agendamento_horario = mysqli_insert_id($conn); 
				
				//INSERE O TIPO DE USUÁRIO PERMITIDO A SE AGENDAR NO HORÁRIO
				//SE O VALOR SELECIONADO FOR 0 ENTÃO LIBERA PARA TODOS
				if($id_tipo_usuario_permitido == 0)
				{
					$query = "SELECT id_agendamento_login_tipo FROM agendamento_login_tipo";
					$dados = mysqli_query($conn,$query) or die(mysql_error());
					$linha = mysqli_fetch_assoc($dados);
					// calcula quantos dados retornaram
					$totalLinhas = mysqli_num_rows($dados);
					if($totalLinhas > 0)
					{
						do
						{
						  	$id_agendamento_login_tipo = $linha['id_agendamento_login_tipo'];

							$query = "INSERT INTO agendamento_tipo_usuario_horario VALUES ($id_agendamento_horario,$id_agendamento_login_tipo)";
							//echo($query);
							mysqli_query($conn,$query) or die(mysql_error());

						}while($linha = mysqli_fetch_assoc($dados));
							mysqli_free_result($dados);
						}
				}
				//CASO O TIPO DE USUÁRIO TENHA SIDO ESPECIFICADO, ENTÃO LIBERA APENAS O TIPO DE USUÁRIO SELECIONADO
				else
				{
					$query = "INSERT INTO agendamento_tipo_usuario_horario VALUES ($id_agendamento_horario,$id_tipo_usuario_permitido)";
					mysqli_query($conn,$query) or die(mysql_error());
				}


				//LIBERA OS ASSUNTOS PARA O HORÁRIO CRIADO
				for($w = 0; $w < count($id_assuntos_permitido_horario); $w++)
					{
						$id_assunto_permitido_horario = $id_assuntos_permitido_horario[$w];
						$query = "INSERT INTO agendamento_assunto_horario VALUES ($id_agendamento_horario,$id_assunto_permitido_horario)";
					mysqli_query($conn,$query) or die(mysql_error());
					}

			}
			else
			{ 
			   echo "FALHA NA GRAVAÇÃO DA HORA"; 
			}
	}
}



//VERIFICA SE A DATA JA EXISTE, POIS PODE SE TRATAR DE APENAS UMA LIBERAÇÃO DE PERÍODO
$query = "SELECT id_agendamento_data FROM agendamento_data WHERE dt_agendamento = '$dt_agendamento' AND unidade_id_unidade = $nr_unidade";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
$id_agendamento_data = 0;
if($totalLinhas > 0)
{
	
	do
	  {
	  	$id_agendamento_data = $linha['id_agendamento_data'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}
//echo($id_agendamento_data);
//SE A DATA EXISTE, ENTÃO CRIA APENAS OS HORÁRIOS SELECIONADOS DO DIA
if($id_agendamento_data > 0)
{
	//echo('data existente');
	cria_horarios_data($id_agendamento_data, $dados_janela);
}
//SE A DATA NAO EXISTE, ENTÃO CRIA A DATA
else
{
	//INSERE A DATA NA AGENDA
	$query = "INSERT INTO agendamento_data VALUES (null,$id_login,$nr_unidade,'$dt_agendamento',0)";
	if (mysqli_query($conn,$query) or die(mysql_error()))
	{ 

	   //ID DO REGISTRO INSERIDO
	   $id_agendamento_data = mysqli_insert_id($conn); 

	   cria_horarios_data($id_agendamento_data, $dados_janela);

	   

	   	$data_inserida = formataDataDDMMYYYY($dt_agendamento);
		echo("
			<div class='alert alert-info' role='alert'>
				<h3><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> <b>$data_inserida</b> JANELA CRIADA!</h3>
				
				<span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Acesse a agenda para realizar a liberação desta janela.
			</div>

		");


	}

}
?>
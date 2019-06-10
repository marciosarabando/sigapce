<?php
//Exibe Data/Horas Futuras Criadas na Agenda
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");

$unidade_sfpc = $_GET['unidade_sfpc'];

mysqli_query($conn,"SET NAMES 'utf8';");

date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d');

//BUSCA AS DATAS LIBERADAS E NAO LIBERADAS FUTURAS QUE NAO POSSUI NENHUM AGENDAMENTO NA UNIDADE SELECIONADA
$query = "	SELECT dt_agendamento, st_agendamento 
			FROM agendamento_data 
			WHERE unidade_id_unidade = $unidade_sfpc 
			AND dt_agendamento >= '$hoje'
			AND agendamento_data.id_agendamento_data NOT IN
          	(
            SELECT agendamento_data.id_agendamento_data 
			FROM agendamento_data 
			INNER JOIN agendamento_horario ON agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			INNER JOIN agendamento_requerente ON agendamento_requerente.id_agendamento_horario = agendamento_horario.id_agendamento_horario
			WHERE agendamento_data.dt_agendamento >= '$hoje' AND agendamento_data.unidade_id_unidade = $unidade_sfpc group by agendamento_data.id_agendamento_data
		  	)
		";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

$data_lib = null;
$data_bloc = null;

if($totalLinhas > 0)
{
	
	do
	  {
	  	if($linha['st_agendamento'] == 0)
	  	{
		  	if($data_bloc == null)
		  	{
		  		$data_bloc = substr($linha['dt_agendamento'],-19,10);
		  	}
		  	else
		  	{
		  		$data_bloc .= ",";
		  		$data_bloc .= substr($linha['dt_agendamento'],-19,10);	
		  	}
		}
		else
		{
			if($data_lib == null)
		  	{
		  		$data_lib = substr($linha['dt_agendamento'],-19,10);
		  	}
		  	else
		  	{
		  		$data_lib .= ",";
		  		$data_lib .= substr($linha['dt_agendamento'],-19,10);	
		  	}
		}
	  	//echo($data);
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

//BUSCA AS DATAS QUE POSSUEM AGENDAMENTO PARA A UNIDADE E QUE AINDA NÃO ESTÃO LOTADAS
$query = "SELECT 
			agendamento_data.dt_agendamento
			FROM agendamento_horario
			INNER JOIN agendamento_data ON agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			AND agendamento_data.dt_agendamento >= '$hoje' AND agendamento_horario.qt_requerente_agendado < agendamento_horario.qt_max_agendamento_horario
			WHERE agendamento_data.unidade_id_unidade = $unidade_sfpc AND agendamento_data.st_agendamento = 1
			AND agendamento_data.id_agendamento_data IN
          	(
            SELECT agendamento_data.id_agendamento_data 
			FROM agendamento_data 
			INNER JOIN agendamento_horario ON agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
			INNER JOIN agendamento_requerente ON agendamento_requerente.id_agendamento_horario = agendamento_horario.id_agendamento_horario
			WHERE agendamento_data.dt_agendamento >= '$hoje' AND agendamento_data.unidade_id_unidade = $unidade_sfpc group by agendamento_data.id_agendamento_data
		  	)
			group by agendamento_data.dt_agendamento
		  ";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

$data_age = null;

if($totalLinhas > 0)
{
	
	do
	  {
	  	
		if($data_age == null)
	  	{
	  		$data_age = substr($linha['dt_agendamento'],-19,10);
	  	}
	  	else
	  	{
	  		$data_age .= ",";
	  		$data_age .= substr($linha['dt_agendamento'],-19,10);	
	  	}
		
	  	
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

//BUSCA AS DATAS QUE ESTÃO LOTADAS
$query = "SELECT dt_agendamento
			FROM agendamento_data 
			WHERE unidade_id_unidade = $unidade_sfpc
			AND dt_agendamento >= '$hoje'
			AND agendamento_data.st_agendamento = 1
			AND agendamento_data.id_agendamento_data IN (SELECT id_agendamento_data FROM agendamento_horario WHERE id_agendamento_data = agendamento_data.id_agendamento_data AND qt_requerente_agendado = qt_max_agendamento_horario)
			AND agendamento_data.id_agendamento_data NOT IN (SELECT id_agendamento_data FROM agendamento_horario WHERE id_agendamento_data = agendamento_data.id_agendamento_data AND qt_requerente_agendado < qt_max_agendamento_horario)
		  ";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
$totalLinhas = mysqli_num_rows($dados);

$data_lot = null;

if($totalLinhas > 0)
{
	
	do
	  {
	  	
		if($data_lot == null)
	  	{
	  		$data_lot = substr($linha['dt_agendamento'],-19,10);
	  	}
	  	else
	  	{
	  		$data_lot .= ",";
	  		$data_lot .= substr($linha['dt_agendamento'],-19,10);	
	  	}
		
	  	
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}


echo("<input type='hidden' id='dt_futuras_bloc' type='text' value='$data_bloc'>");
echo("<input type='hidden' id='dt_futuras_lib' type='text' value='$data_lib'>");
echo("<input type='hidden' id='dt_futuras_age' type='text' value='$data_age'>");
echo("<input type='hidden' id='dt_futuras_lot' type='text' value='$data_lot'>");


?>
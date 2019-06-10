<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$data = formataData($_GET['data']);
$data_atendimento = retornaDataExtenso($data);
$id_unidade = $_GET['id_unidade'];
$nm_unidade = $_GET['nm_unidade'];
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('d/m/Y h:i');
//Gera lista Usuarios Agendados para Imprimir

if(isset($_SESSION['login_sfpc']))
{
 	$nm_guerra = $_SESSION['login_sfpc'];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>SIGAPCE - 2ª RM</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Sistema de Controle de Processos SFPC 2RM" content="">
	<meta name="2 Ten Sarabando" content="Sistema de Fiscalizaçãos SFPC 2RM">


	<link href="../../css/bootstrap.min.css" rel="stylesheet" media="all">
	<link href="../../css/style.css" rel="stylesheet">


	<!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../img/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../img/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../img/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="../../img/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="img/favicon-2rm.png">

	<script type="text/javascript" src="../../js/jquery.min.js"></script>
	<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
	
	<script>
		function imprimir() 
		{
			window.print(); 
        	setTimeout('window.close()',1000); 
		}
	</script>

</head>

<body onload='imprimir()'>

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">

<?php
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
		agendamento_requerente.id_agendamento_requerente,
		agendamento_status.id_agendamento_status,
		agendamento_status.nm_status,
        agendamento_horario.id_agendamento_horario,
		agendamento_assunto.nm_agendamento_assunto,
        agendamento_horario.hr_agendamento_horario,
        agendamento_login.id_agendamento_login,
        agendamento_login.cpf_login,
        agendamento_login.nm_completo,
        agendamento_login_tipo.nm_agendamento_login_tipo,
        agendamento_login.nr_celular,
        agendamento_login.email,
        cidade.nm_cidade,
        cidade.uf_cidade,
        agendamento_requerente_andamento.dt_agendamento_requerente_andamento , IFNULL (GROUP_CONCAT(interessado.nm_interessado , '<BR>', case when sg_tipo_interessado = 'PJ' then insert(INSERT( INSERT( INSERT( interessado.cnpj_interessado, 13, 0, '-' ), 6, 0, '.' ), 3, 0, '.' ), 11, 0, '/' ) else INSERT( INSERT( INSERT( interessado.cpf_interessado, 10, 0, '-' ), 7, 0, '.' ), 4, 0, '.' ) end   SEPARATOR '<BR><BR>' ), 'PRÓPRIO INTERESSADO')identificador
	FROM agendamento_requerente
	INNER JOIN agendamento_requerente_andamento on agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente
	INNER JOIN agendamento_status on agendamento_status.id_agendamento_status = agendamento_requerente_andamento.id_agendamento_status
	INNER JOIN agendamento_horario on agendamento_horario.id_agendamento_horario = agendamento_requerente.id_agendamento_horario
    left JOIN (select * from agendamento_horario_interessado) base  on agendamento_horario.id_agendamento_horario = base.id_agendamento_horario and base.id_agendamento_login = agendamento_requerente.id_agendamento_login
	left JOIN interessado on interessado.id_interessado =  base.id_interessado 
	INNER JOIN agendamento_data on agendamento_data.id_agendamento_data = agendamento_horario.id_agendamento_data
	INNER JOIN agendamento_login on agendamento_login.id_agendamento_login = agendamento_requerente.id_agendamento_login
	INNER JOIN agendamento_login_tipo on agendamento_login_tipo.id_agendamento_login_tipo = agendamento_login.id_agendamento_login_tipo
	INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
	INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
	INNER JOIN agendamento_assunto on agendamento_assunto.id_agendamento_assunto = agendamento_requerente.id_agendamento_assunto
	WHERE agendamento_data.dt_agendamento = '$data' AND agendamento_data.unidade_id_unidade = $id_unidade
	AND agendamento_requerente_andamento.id_agendamento_status <> 4
	AND agendamento_requerente_andamento.id_agendamento_status IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
	group by agendamento_requerente.id_agendamento_requerente,
			agendamento_status.id_agendamento_status,
			agendamento_status.nm_status,
			arquivo.binario,
			arquivo.tipo,
	        agendamento_horario.id_agendamento_horario,
	        agendamento_horario.hr_agendamento_horario,
	        agendamento_login.id_agendamento_login,
	        agendamento_login.cpf_login,
	        agendamento_login.nm_completo,
	        agendamento_login_tipo.nm_agendamento_login_tipo,
	        agendamento_login.nr_celular,
	        agendamento_login.email,
	        cidade.nm_cidade,
	        cidade.uf_cidade,
	        agendamento_assunto.nm_agendamento_assunto,
	        agendamento_requerente_andamento.dt_agendamento_requerente_andamento ORDER BY agendamento_horario.id_agendamento_horario";
// in IN (SELECT max(agendamento_requerente_andamento.id_agendamento_status) FROM agendamento_requerente_andamento WHERE agendamento_requerente_andamento.id_agendamento_requerente = agendamento_requerente.id_agendamento_requerente)
//echo($query);

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{

echo("
	<br>
	<div class='container'>
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  	<center>
		  	<h4>SAE - SISTEMA DE AGENDAMENTO ELETRÔNICO DA SFPC/2</h4>
		  	<h5><b>$data_atendimento</b></h5>
			<h6>$nm_unidade</h6>
			
			</center>
		 </div>
	</div>

	
	
	
	
	
				<table class='table table-condensed table-bordered'>
					<thead>
					<tr>
						
						<th>
							<center>ASSUNTO</center>
						</th>

						<th>
							<center>HORA</center>
						</th>
						
						
						<th>
							NOME
						</th>

						<th>
							TIPO
						</th>

						 ");
            
                    /*
                    COLUNA COM OS DADOS DOS INTERESSADOS VINCULADOS AO HORÁRIO
                    DESCOMENTAR DIA 13/06/2019
                            <th>
                                        <center>INTERESSADOS</center>
                            </th>
                    */

                    echo("

						<th>
							<center>CIDADE</center>
						</th>
						
					</tr>
				</thead>
				<tbody>
	");
do
  {
  	
  	$nm_agendamento_assunto = $linha['nm_agendamento_assunto'];
  	$id_agendamento_login = $linha['id_agendamento_login'];
  	$id_agendamento_requerente = $linha['id_agendamento_requerente'];
  	$id_agendamento_status = $linha['id_agendamento_status'];
  	$nm_status = $linha['nm_status'];
  	$hr_agendamento_horario = $linha['hr_agendamento_horario'];
	$cpf = formataCPF($linha['cpf_login']);
	$nome = $linha['nm_completo'];
	$tipo_login = $linha['nm_agendamento_login_tipo'];
	$interessados = $linha['identificador'];
	$email = $linha['email'];
	$cidade = $linha['nm_cidade'];
	$cidade .= " - ";
	$cidade .= $linha['uf_cidade'];

	
	echo("

			<tr>
				
				<td>
					<center><h6><b>".$nm_agendamento_assunto."</b></h6></center>
				</td>
				

				<td>
					<center><h6><b>".$hr_agendamento_horario."</b></h6></center>
				</td>

				

				<td>
					<font color='black'><h6>".$nome."</h6></font>
				</td>

				<td>
					<font color='black'><h6>".$tipo_login."</h6></font>
				</td>
                
                 ");
            
            /*
            COLUNA COM OS DADOS DOS INTERESSADOS VINCULADOS AO HORÁRIO
            DESCOMENTAR DIA 13/06/2019
					<td width='120'>
					   <font color='black'><center><h6>".$interessados."</h6></center></font>
				    </td>
            */
            
            echo("

				

				<td>
					<font color='black'><center><h6>".$cidade."</h6></center></font>
				</td>

				
				
		");

	

    
    echo("</tr>");
    	

 }while($linha = mysqli_fetch_assoc($dados));
  mysqli_free_result($dados);
  echo("</table>");

  echo("
  	
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  <h6>
		  	<center>Seção de Fiscalização de Produtos Controlados - Cmdo 2ª RM - emitido em: <b>$hoje</b> por $nm_guerra</center>
		  	<center>www.2rm.eb.mil.br/sfpc/sigapce</center>
		  	</h6>
		 </div>
	
	");
}

?>

		</div>
	</div>
</div>


</body>

</html>

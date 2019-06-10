<?php
//EXIBE GRUS CADASTRADAS
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

function mask($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}

$query = "SELECT 
				gru.id_gru,
				processo.cd_protocolo_processo,
				gru.nr_referencia,
				gru.competencia,
				gru.valor,
				gru.nr_autenticacao 
			FROM gru
			INNER JOIN gru_processo on gru_processo.id_gru = gru.id_gru
			INNER JOIN processo on processo.id_processo = gru_processo.id_processo";

//echo($query);

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{

	echo("
			<div class='row'>
				<div class='col-md-12'>
					<div class='table-responsive'>
		  				<table id='tb_gru' name='tb_gru' class='table table-condensed table-bordered'>
		  					<thead>
								<tr>
									<th>
										<center>ID</center>
									</th>
									<th>
										<center>PROTOCOLO</center>
									</th>
									<th>
										<center>REFERENCIA</center>
									</th>
									<th>
										<center>COMPETENCIA</center>
									</th>
									<th>
										<center>VALOR</center>
									</th>
									<th>
										<center>AUTENTICAÇÃO</center>
									</th>
								</tr>
							</thead>
							<tbody>
		");
	do
	  {
	  		$id_gru = $linha['id_gru'];
	  		$cd_protocolo = $linha['cd_protocolo_processo'];
	  		$referencia = $linha['nr_referencia'];
	  		$competencia = $linha['competencia'];
	  		$valor = $linha['valor'];
	  		$autenticacao = $linha['nr_autenticacao'];
	  		$autenticacao = mask($autenticacao, '#.###.###.###.###.###');


	  		echo("
	  				<tr>
	  					<td>
	  						<center>$id_gru</center>
	  					</td>
	  					<td>
	  						<center>$cd_protocolo </center>
	  					</td>
	  					<td>
	  						<center>$referencia </center>
	  					</td>
	  					<td>
	  						<center>$competencia</center>
	  					</td>
	  					<td>
	  						<center>R$ $valor</center>
	  					</td>
	  					<td>
	  						<center>$autenticacao </center>
	  					</td>
	  				</tr>
	  			");


	  }while($linha = mysqli_fetch_assoc($dados));
 	mysqli_free_result($dados);


 	echo("</table></tbody></div></div></div>");
}
else
{
		echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou registros.</p>
		");
}

?>
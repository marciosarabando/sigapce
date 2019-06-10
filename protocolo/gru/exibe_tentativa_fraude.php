<?php
//EXIBE TENTATIVAS DE FRAUDE GRU
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
				gru.nr_autenticacao, 
				gru_tentativa_fraude.dt_gru_tentativa_fraude,
		        interessado.nm_interessado,
		        processo.cd_protocolo_processo,
		        procurador.nm_procurador,
		        posto_graduacao.nm_posto_graduacao,
		        login.nm_guerra
		FROM gru_tentativa_fraude
		INNER JOIN gru on gru.id_gru = gru_tentativa_fraude.id_gru
		INNER JOIN gru_processo on gru_processo.id_gru = gru_tentativa_fraude.id_gru
		INNER JOIN processo on processo.id_processo = gru_processo.id_processo
		INNER JOIN login on login.id_login = gru_tentativa_fraude.id_login
		INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
		INNER JOIN interessado on interessado.id_interessado = gru_tentativa_fraude.id_interessado
		LEFT JOIN procurador on procurador.id_procurador = gru_tentativa_fraude.id_procurador
";

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
		  				<table id='tb_gru_fraude' name='tb_gru_fraude' class='table table-condensed table-bordered'>
		  					<thead>
								<tr>
									<th>
										<center>AUTENTICAÇÃO GRU</center>
									</th>
									<th>
										<center>DATA DA TENTATIVA</center>
									</th>
									<th>
										<center>INTERESSADO</center>
									</th>
									<th>
										<center>PROCESSO VINCULADO</center>
									</th>
									<th>
										<center>PROCURADOR</center>
									</th>
									<th>
										<center>PROTOCOLISTA</center>
									</th>
								</tr>
							</thead>
							<tbody>
		");
	do
	  {
	  		$nr_autenticacao = $linha['nr_autenticacao'];
	  		$nr_autenticacao = mask($nr_autenticacao, '#.###.###.###.###.###');
	  		$dt_gru_tentativa_fraude = $linha['dt_gru_tentativa_fraude'];
	  		$nm_interessado = $linha['nm_interessado'];
	  		$cd_protocolo_processo = $linha['cd_protocolo_processo'];
	  		$nm_procurador = $linha['nm_procurador'];
	  		$nm_posto_graduacao = $linha['nm_posto_graduacao'];
	  		$nm_guerra = $linha['nm_guerra'];


	  		echo("
	  				<tr>
	  					<td>
	  						<center>$nr_autenticacao</center>
	  					</td>
	  					<td>
	  						<center>$dt_gru_tentativa_fraude</center>
	  					</td>
	  					<td>
	  						<center>$nm_interessado </center>
	  					</td>
	  					<td>
	  						<center>$cd_protocolo_processo </center>
	  					</td>
	  					<td>
	  						<center>$nm_procurador</center>
	  					</td>
	  					<td>
	  						<center>$nm_posto_graduacao $nm_guerra</center>
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

<?php
include ("verificaAtenticacao.php");
include ("conexao.php");
//include ("formata_dados.php");
mysqli_query($conn,"SET NAMES 'utf8';");



$query = "SELECT login_oline.id_login_oline,
				 posto_graduacao.nm_posto_graduacao,
				 login.nm_login,
				 login.nm_guerra,
				 login_oline.ip_login_online,
				 (SELECT max(dt_evento) FROM evento WHERE id_tipo_evento = 2 AND obs_evento = 'Login realizado!: '+login.nm_login) AS DT_ULTIMO_ACESSO
			FROM login_oline
			INNER JOIN login on login.id_login = login_oline.id_login_oline
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao";

//echo($query);
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

if($totalLinhas > 0)
{

	echo("
		<div class='table-responsive'>
  				<table id='tb_login_online' name='tb_login_online' class='table table-condensed table-bordered'>
  					<thead>
						<tr>
							<th>
								<center>ID</center>
							</th>

							<th>
								<center>LOGIN</center>
							</th>

							<th>
								<center>NOME DE GUERRA</center>
							</th>

							<th>
								<center>IP</center>
							</th>

							<th>
								<center>DT_ULTIMO_ACESSO</center>
							</th>
						</tr>
					</thead>
					<tbody>
		");
	do
	  {
	  	
	  	$id_login_online = $linha['id_login_oline'];
	  	$nm_login = $linha['nm_login'];
	  	$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		$nm_guerra = $nm_posto_graduacao;
		$nm_guerra .= " ";
		$nm_guerra .= $linha['nm_guerra'];
		$ip_login_online = $linha['ip_login_online'];
		$DT_ULTIMO_ACESSO = $linha['DT_ULTIMO_ACESSO'];
		
		
		echo("

				<tr class='active'>
					
					
					
					<td>
						<center>".$id_login_online."</center>
					</td>

					<td>
						<center>".$nm_login."</center>
					</td>

					<td>
						<center>".$nm_guerra."</center>
					</td>

					<td>
						<center>".$ip_login_online."</center>
					</td>

					<td>
						<center>".$DT_ULTIMO_ACESSO."</center>
					</td>

					
				</tr>");
	    	

	 }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  echo("</table>");
	    	echo("</div>");
}
else
{
	echo("
			<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> A pesquisa não encontrou resultado.</p>
		");
}

?>
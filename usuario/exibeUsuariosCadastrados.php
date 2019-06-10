<?php

include ("../funcoes/verificaAtenticacao.php");
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

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

//CRIA A QUERY DE ACORDO COM O PERFIL DE ACESSO DO USUÁRIO LOGADO

$query = null;
//SE FOR ADMINISTRADOR DO SISTEMA
if($id_login_perfil == 1)
{
	$query = "SELECT
				login.id_login, 	
				login.nm_login,
				posto_graduacao.nm_posto_graduacao,
				login.nm_guerra,
		        login.nm_completo,
		        unidade.nm_unidade,
		        login_perfil.nm_login_perfil,
		        login.st_ativo
			FROM login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			INNER JOIN unidade on unidade.id_unidade = login.id_unidade
			INNER JOIN login_perfil on login_perfil.id_login_perfil = login.id_login_perfil";
	
}
//SE FOR CHEFE DO SFPC DA REGIÃO OU ADMINISTRADOR DO SISTEMA
if($id_login_perfil == 2)
{
	$query = "SELECT
				login.id_login, 	
				login.nm_login,
				posto_graduacao.nm_posto_graduacao,
				login.nm_guerra,
		        login.nm_completo,
		        unidade.nm_unidade,
		        login_perfil.nm_login_perfil,
		        login.st_ativo
			FROM login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			INNER JOIN unidade on unidade.id_unidade = login.id_unidade
			INNER JOIN login_perfil on login_perfil.id_login_perfil = login.id_login_perfil
			WHERE login_perfil.id_login_perfil <> 1
			";
	
}
//CHEFE DE UNIDADE SUBORDINADA
else if($id_login_perfil == 3)
{
	$query = "SELECT
				login.id_login, 	
				login.nm_login,
				posto_graduacao.nm_posto_graduacao,
				login.nm_guerra,
		        login.nm_completo,
		        unidade.nm_unidade,
		        login_perfil.nm_login_perfil,
		        login.st_ativo
			FROM login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			INNER JOIN unidade on unidade.id_unidade = login.id_unidade
			INNER JOIN login_perfil on login_perfil.id_login_perfil = login.id_login_perfil
            WHERE login.id_unidade in (SELECT id_unidade FROM login_unidade WHERE id_login = $id_login_logado)";
    
}

//CHEFE DE CARTEIRA REGIAO MILITAR
else if($id_login_perfil == 4)
{
	$query = "SELECT
				login.id_login, 	
				login.nm_login,
				posto_graduacao.nm_posto_graduacao,
				login.nm_guerra,
		        login.nm_completo,
		        unidade.nm_unidade,
		        login_perfil.nm_login_perfil,
		        login.st_ativo
			FROM login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			INNER JOIN unidade on unidade.id_unidade = login.id_unidade
			INNER JOIN login_perfil on login_perfil.id_login_perfil = login.id_login_perfil
            LEFT JOIN login_carteira on login_carteira.id_login = login.id_login
			WHERE login_carteira.id_carteira in (SELECT id_carteira from login_carteira where id_login = $id_login_logado)
            AND login.id_login_perfil <> 1 AND login.id_login_perfil <> 2
            AND login.id_unidade in (SELECT id_unidade FROM login_unidade WHERE id_login = $id_login_logado)
            group by id_login";
}

if($id_login_perfil == 1 || $id_login_perfil == 2 || $id_login_perfil == 3 || $id_login_perfil == 4)
{
	echo("<button type='button' class='btn btn-primary' onclick='novo_usuario()'><i class='glyphicon glyphicon-plus'></i> NOVO USUÁRIO</button>
			<br><br>");
}

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);


//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{
	echo("
			
			<fieldset>
	            <legend>USUÁRIOS DO SISTEMA</legend>
			<table class='table' align='left' id='tb_usuarios'>
					<thead>
						<tr>
							<th>
								LOGIN
							</th>
							<th>
								NOME DE GUERRA
							</th>

							<th>
								NOME COMPLETO
							</th>
							<th>
								OM SFPC PERTENCENTE
							</th>
							<th>
								PERFIL DE ACESSO
							</th>
							<th>
								STATUS
							</th>
						</tr>
					</thead>
					<tbody>
");
	do
	  {
	  	$id_login = $linha['id_login'];
	  	$nm_login = $linha['nm_login'];
		$nm_guerra =  $linha['nm_posto_graduacao'];
	  	$nm_guerra .= " ";
	  	$nm_guerra .=  $linha['nm_guerra'];
		$nm_completo = $linha['nm_completo'];
		$nm_unidade = $linha['nm_unidade'];
		$nm_login_perfil = $linha['nm_login_perfil'];
		$st_ativo = $linha['st_ativo'];

		echo("
				<tr class='active'>
	  				<td>
	  					<a href=\"javascript:exibeDetalhesCadastroUsuario('$id_login')\"\><font color='green'><b>". $nm_login ."</b></font></a>
	  				</td>
	  				<td>
	  					". $nm_guerra ."
	  				</td>
	  				<td>
	  					". $nm_completo ."
	  				</td>
	  				<td>
	  					". $nm_unidade ."
	  				</td>
	  				<td>
	  					". $nm_login_perfil ."
	  				</td>
	  		");

		if($st_ativo == "1")
		{
			echo("<td>
	  					<font color='green'><b>ATIVO</b></font>
	  			</td>");
		}
		else
		{
			echo("<td>
	  					<font color='red'><b>DESATIVADO</b></font>
	  			</td>");
		}
		echo("</tr>");
		}while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
	  	
	  	echo("</tbody></table>");
}			


	


?>
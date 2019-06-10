<?php
//Mostra na Página Noticia_Sae Quantidade de Solicitações Pendentes
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

if (!isset($_SESSION)) 
{
	session_start();
}
$id_unidade = $_SESSION['id_unidade_sfpc'];
$id_login_perfil = $_SESSION['id_login_perfil'];
//Perfil de Acesso
$AdminsSFPC = array("1","2","3","4");
$Analista = array("5");
$Atendimento = array("6");

//Acesso a HOME
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='home.php'><span><h1><i class='glyphicon glyphicon-home'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='home.php'><h6>HOME SIGAPCE</h6></a></center>           				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");

$query = "	SELECT 
				count(agendamento_login.id_agendamento_login) as qtd_login_pendente				
			FROM agendamento_login
			INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
			INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
			INNER JOIN agendamento_login_historico on agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login
			INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
			WHERE agendamento_login_historico.id_agendamento_login_historico IN (SELECT max(agendamento_login_historico.id_agendamento_login_historico) FROM agendamento_login_historico WHERE agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login)
			AND agendamento_login_status.id_agendamento_login_status = 1
			AND cidade.id_cidade IN (SELECT id_cidade FROM agendamento_unidade_cidade WHERE id_unidade = $id_unidade)
			";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);

//Exibe as Solicitacoes de Acesso ao Sistema
if($id_login_perfil <> 6) 
{ 

if($totalLinhas > 0)
{
	do
	  {
	  	$qtd_login_pendente = $linha['qtd_login_pendente'];
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	echo("
		
			<div class='col-md-2'>
		        <div class='panel panel-default'>
		            <div class='panel-heading'>
		            <center>
		                	<table>
		                		<tr>
			                		<td>
			                			<a href='sae.php?url=controle_acesso'><span style='text-align: right;'><h1><i class='glyphicon glyphicon-user'></i></h1></span></a>
			                		</td>
									<td>
										<center><a href='sae.php?url=controle_acesso'><h1>$qtd_login_pendente</h1></a></center>
			                		</td>
		                		</tr>
		                		<tr>
		                			<td colspan='2'>
		                				<center><a href='sae.php?url=controle_acesso'><center><h6>SOLICITAÇÕES DE ACESSO PENDENTES</h6></a></center>		                				
		                			</td>
		                		</tr>
		                	</table>
		            </div>
		           </center>
		        </div>
		    </div>
		

		");
}

//Acesso a Agenda
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='sae.php?url=gerenciar_agenda'><span><h1><i class='glyphicon glyphicon-calendar'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='sae.php?url=gerenciar_agenda'><h6>GERENCIAR AGENDA</h6></a></center>   				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");
}
//Atendimento de Usuários Agendados
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='sae.php?url=atender_usuarios_agendados'><span><h1><i class='glyphicon glyphicon-comment'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='sae.php?url=atender_usuarios_agendados'><h6>ATENDIMENTO</h6></a></center>   				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");

//Tuturial Utilização
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='sae.php?url=tutorial'><span><h1><i class='glyphicon glyphicon-film'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='sae.php?url=tutorial'><h6>TUTORIAL DE UTILIZAÇÃO</h6></a></center>   				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");


?>
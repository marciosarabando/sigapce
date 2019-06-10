<?php
//Mostra na Dasboard SISPROT
include ("../funcoes/verificaAtenticacao.php");
include ("../funcoes/conexao.php");
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
$modulo_sisprot = 1;

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

//CONSULTAR
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><span><a href='sisprot.php?url=protocolo_consulta'><h1><i class='glyphicon glyphicon-search'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='sisprot.php?url=protocolo_consulta'><h6><label>CONSULTAR</label></h6></a></center>           				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");

//Protocolar
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><span><a href='sisprot.php?url=protocolo_novo'><h1><i class='glyphicon glyphicon-download-alt'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='home.php'><h6><label>PROTOCOLAR</label></h6></a></center>           				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");

//Analisar Processo
if (in_array($id_login_perfil, $AdminsSFPC) || in_array($id_login_perfil, $Analista)) 
{ 
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><span><a href='sisprot.php?url=processo_analise'><h1><i class='glyphicon glyphicon-eye-open'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='home.php'><h6><label>ANALISAR</label></h6></a></center>           				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>
	");
}

echo("
    <div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                    <table>
                        <tr>
                            <td>
                                <center><span><a href='sisprot.php?url=gru'><h1><i class='glyphicon glyphicon-barcode'></i></h1></span></a></center>
                            </td>
                            
                        </tr>
                        <tr height=50>
                            <td>

                                <center><a href='home.php'><h6><label>CONTROLE DE GRU</label></h6></a></center>                          
                            
                            </td>
                        </tr>
                    </table>
             </center>
            </div>
           
        </div>
    </div>

    ");

// $query = "	SELECT 
// 				count(agendamento_login.id_agendamento_login) as qtd_login_pendente				
// 			FROM agendamento_login
// 			INNER JOIN cidade on cidade.id_cidade = agendamento_login.id_cidade
// 			INNER JOIN arquivo on arquivo.id_arquivo = agendamento_login.id_arquivo
// 			INNER JOIN agendamento_login_historico on agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login
// 			INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
// 			WHERE agendamento_login_historico.id_agendamento_login_historico IN (SELECT max(agendamento_login_historico.id_agendamento_login_historico) FROM agendamento_login_historico WHERE agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login)
// 			AND agendamento_login_status.id_agendamento_login_status = 1
// 			AND cidade.id_cidade IN (SELECT id_cidade FROM agendamento_unidade_cidade WHERE id_unidade = $id_unidade)
// 			";

// $dados = mysqli_query($conn,$query) or die(mysql_error());
// $linha = mysqli_fetch_assoc($dados);
// // calcula quantos dados retornaram
// $totalLinhas = mysqli_num_rows($dados);

// //Exibe as Solicitacoes de Acesso ao Sistema
// if($totalLinhas > 0)
// {
// 	do
// 	  {
// 	  	$qtd_login_pendente = $linha['qtd_login_pendente'];
// 	  }while($linha = mysqli_fetch_assoc($dados));
// 	  mysqli_free_result($dados);

// 	echo("
		
// 			<div class='col-md-2'>
// 		        <div class='panel panel-default'>
// 		            <div class='panel-heading'>
// 		                	<table>
// 		                		<tr>
// 			                		<td>
// 			                			<span style='text-align: right;'><h1><i class='glyphicon glyphicon-user'></i></h1></span>
// 			                		</td>
// 									<td>
// 										<center><h1>$qtd_login_pendente</h1></center>
// 			                		</td>
// 		                		</tr>
// 		                		<tr>
// 		                			<td colspan='2'>
// 		                				<a href='sae.php?url=controle_acesso'><center><h6>SOLICITAÇÕES DE ACESSO PENDENTES</h6></a>		                				
// 		                			</center>
// 		                			</td>
// 		                		</tr>
// 		                	</table>
// 		            </div>
		           
// 		        </div>
// 		    </div>
		

// 		");
// }


?>
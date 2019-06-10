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


//PESQUISA DE DOCUMENTO DIGITALIZADO
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='ged.php?url=pesquisa_documento'><span><h1><i class='glyphicon glyphicon-search'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='ged.php?url=pesquisa_documento'><h6>BUSCAR DOCUMENTO</h6></a></center>   				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");

//UPLOAD DE DOCUMENTO DIGITALIZADO
echo("
    <div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                    <table>
                        <tr>
                            <td>
                                <center><a href='ged.php?url=upload_documento'><span><h1><i class='glyphicon glyphicon-import'></i></h1></span></a></center>
                            </td>
                            
                        </tr>
                        <tr height=50>
                            <td>

                                <center><a href='ged.php?url=upload_documento'><h6>IMPORTAR DOCUMENTO</h6></a></center>                  
                            
                            </td>
                        </tr>
                    </table>
             </center>
            </div>
           
        </div>
    </div>

    ");



?>
<?php
//Mostra a Página Noticia_ADT
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


//GESTÃO DE MATÉRIAS
echo("
	<div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                	<table>
                		<tr>
	                		<td>
	                			<center><a href='adt.php?url=materia'><span><h1><i class='glyphicon glyphicon-cog'></i></h1></span></a></center>
	                		</td>
							
                		</tr>
                		<tr height=50>
                			<td>

                				<center><a href='adt.php?url=materia'><h6>GESTÃO DE MATÉRIAS</h6></a></center>   				
                			
                			</td>
                		</tr>
                	</table>
             </center>
            </div>
           
        </div>
    </div>

	");


//GESTÃO DE ADITAMENTO
echo("
    <div class='col-md-2'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
            <center>
                    <table>
                        <tr>
                            <td>
                                <center><a href='adt.php?url=aditamento'><span><h1><i class='glyphicon glyphicon-list-alt'></i></h1></span></a></center>
                            </td>
                            
                        </tr>
                        <tr height=50>
                            <td>

                                <center><a href='adt.php?url=aditamento'><h6>GESTÃO DE ADITAMENTOS</h6></a></center>                  
                            
                            </td>
                        </tr>
                    </table>
             </center>
            </div>
           
        </div>
    </div>

    ");



?>
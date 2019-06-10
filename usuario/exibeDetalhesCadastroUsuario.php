<?php

$id_login = $_GET['id_login'];
if (!isset($_SESSION)) 
{
	session_start();
}
if(isset($_SESSION['login_sfpc']))
{

 	$id_login_logado = $_SESSION['id_login_sfpc'];
}

//BUSCAR DADOS DE LOGIN
//ACESSO EM CARTEIRAS
//ACESOS EM UNIDADES

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");

$query = "SELECT
				login.id_login, 	
				login.nm_login,
				posto_graduacao.id_posto_graduacao,
				posto_graduacao.nm_posto_graduacao,
				login.nm_guerra,
		        login.nm_completo,
		        login.nm_email,
		        unidade.id_unidade,
		        unidade.nm_unidade,
		        login_perfil.id_login_perfil,
		        login_perfil.nm_login_perfil,
		        login.st_ativo
			FROM login
			INNER JOIN posto_graduacao on posto_graduacao.id_posto_graduacao = login.id_posto_graduacao
			INNER JOIN unidade on unidade.id_unidade = login.id_unidade
			INNER JOIN login_perfil on login_perfil.id_login_perfil = login.id_login_perfil
			WHERE login.id_login = $id_login
			";

$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Se encontrou o Cadastro Exibe os Dados do Interessado
if($totalLinhas > 0)
{

	
	do
	  {
	  	$id_login = $linha['id_login'];
		$nm_login = $linha['nm_login'];
		$id_posto_graduacao = $linha['id_posto_graduacao'];
		$nm_posto_graduacao = $linha['nm_posto_graduacao'];
		$nm_guerra =  $linha['nm_posto_graduacao'];
	  	$nm_guerra .= " ";
	  	$nm_guerra .=  $linha['nm_guerra'];
	  	$guerra = $linha['nm_guerra'];
		$nm_completo = $linha['nm_completo'];
		$id_unidade = $linha['id_unidade'];
		$nm_unidade = $linha['nm_unidade'];
		$id_login_perfil = $linha['id_login_perfil'];
		$nm_login_perfil = $linha['nm_login_perfil'];
		$email = $linha['nm_email'];
		$st_ativo = $linha['st_ativo'];
		
	}while($linha = mysqli_fetch_assoc($dados));
	 mysqli_free_result($dados);
}

if($st_ativo == 1)
{
	echo ("
			<div class='panel panel-default'>
				<div class='alert alert-success' role='alert'>
					<h3><label>".$nm_guerra."</label></h3>
					<h4><label>".$nm_unidade."</label></h4>
				</div>
			</div>
		");
}
else
{
		echo ("
			<div class='panel panel-default'>
				<div class='alert alert-danger' role='alert'>
					<h3><label>".$nm_guerra."</label></h3>
					<h4><label>".$nm_unidade."</label></h4>
					<h4><label>LOGIN DESATIVADO</label></h4>
				</div>
			</div>
		");
}

echo("
		<div class='row'>
					<div class='col-md-6'>
						
							
							<label>INFORMAÇÕES DO USUÁRIO</label>
							<div class='table-responsive'>
  							<table class='table table-condensed table-bordered'>
								<tr>
									<td>
										<label>LOGIN:</label>
									</td>
									<td>
										<label>" . $nm_login . "</label>

									</td>
								</tr>

								<tr>
									<td>
										<label>POSTO/GRADUAÇÃO:</label>
									</td>
									<td>
									<select id='cmb_posto_graduacao' name='cmb_posto_graduacao' class='form-control' onchange='mostraBotaoAtualizaDadosUsuario()'>
	");
								//Preenche o combo Posto/Graduacao
								//Conecta no Banco de Dados
								include ("../funcoes/conexao.php");
								mysqli_query($conn,"SET NAMES 'utf8';");
								$query = "SELECT 
													id_posto_graduacao,
													nm_posto_graduacao
										 FROM posto_graduacao";
								// executa a query
								$dados = mysqli_query($conn,$query) or die(mysql_error());
								// transforma os dados em um array
								$linha = mysqli_fetch_assoc($dados);
								// calcula quantos dados retornaram
								$totalLinhas = mysqli_num_rows($dados);

								if($totalLinhas > 0)
								{
									//echo("<option value='0'>SELECIONE...</option>");
									do{
										
										if($nm_posto_graduacao == $linha['nm_posto_graduacao'])
										{
											echo("<option value='". $linha['id_posto_graduacao'] . "' selected>" . $linha['nm_posto_graduacao'] . "</option>");
										}
										else
										{
											echo("<option value='". $linha['id_posto_graduacao'] . "'>" . $linha['nm_posto_graduacao'] . "</option>");
										}

									}while($linha = mysqli_fetch_assoc($dados));
									mysqli_free_result($dados);
								}



	echo("
									</td>
								</tr>
								<tr>
									<td>
										<label>NOME GUERRA:</label>
									</td>
									<td>
										
										<input type='text' class='upper form-control' onClick='mostraBotaoAtualizaDadosUsuario()' id='nm_guerra' name='nm_guerra' placeholder='Digite o Nome de Guerra do Usuário' value='". $guerra ."'>
									</td>
								</tr>
								<tr>
									<td>
										<label>NOME COMPLETO:</label>
									</td>
									<td>
										
										<input type='text' class='upper form-control' onClick='mostraBotaoAtualizaDadosUsuario()' id='nm_completo' name='nm_completo' placeholder='Digite o Nome Completo do Usuário' value='". $nm_completo ."'>
									</td>
								</tr>
								<tr>
									<td>
										<label>E-MAIL:</label>
									</td>
									<td>
										<input type='text' class='form-control' onClick='mostraBotaoAtualizaDadosUsuario()' id='email' name='email' placeholder='Digite o e-mail' value='". $email ."'>
									</td>
								</tr>
								<tr>
									<td>
										<label>OM SFPC PERTENCENTE:</label>
									</td>
									<td>
										<select id='cmb_unidade_pertencente' name='cmb_unidade_pertencente' class='form-control' onchange='mostraBotaoAtualizaDadosUsuario()'>
	");
								//Preenche o combo UNIDADES OM SFPC
								//Conecta no Banco de Dados
								include ("../funcoes/conexao.php");
								mysqli_query($conn,"SET NAMES 'utf8';");
								$query = "SELECT 
													id_unidade,
													nm_unidade
										 FROM unidade";
								// executa a query
								$dados = mysqli_query($conn,$query) or die(mysql_error());
								// transforma os dados em um array
								$linha = mysqli_fetch_assoc($dados);
								// calcula quantos dados retornaram
								$totalLinhas = mysqli_num_rows($dados);

								if($totalLinhas > 0)
								{
									//echo("<option value='0'>SELECIONE...</option>");
									do{
										
										if($nm_unidade == $linha['nm_unidade'])
										{
											echo("<option value='". $linha['id_unidade'] . "' selected>" . $linha['nm_unidade'] . "</option>");
										}
										else
										{
											echo("<option value='". $linha['id_unidade'] . "'>" . $linha['nm_unidade'] . "</option>");
										}

									}while($linha = mysqli_fetch_assoc($dados));
									mysqli_free_result($dados);
								}



	echo("
									</td>
								</tr>
								<tr>
									<td>
										<label>PERFIL DE ACESSO:</label>
									</td>
									<td>
										<select id='cmb_perfil_acesso' name='cmb_perfil_acesso' class='form-control' onchange='mostraBotaoAtualizaDadosUsuario()'>
	");
								//Preenche o combo Perfil de Acesso
								//Conecta no Banco de Dados
								include ("../funcoes/conexao.php");
								mysqli_query($conn,"SET NAMES 'utf8';");
								$query = "SELECT 
													id_login_perfil,
													nm_login_perfil
										 FROM login_perfil";
								// executa a query
								$dados = mysqli_query($conn,$query) or die(mysql_error());
								// transforma os dados em um array
								$linha = mysqli_fetch_assoc($dados);
								// calcula quantos dados retornaram
								$totalLinhas = mysqli_num_rows($dados);

								if($totalLinhas > 0)
								{
									//echo("<option value='0'>SELECIONE...</option>");
									do{
										
										if($nm_login_perfil == $linha['nm_login_perfil'])
										{
											echo("<option value='". $linha['id_login_perfil'] . "' selected>" . $linha['nm_login_perfil'] . "</option>");
										}
										else
										{
											echo("<option value='". $linha['id_login_perfil'] . "'>" . $linha['nm_login_perfil'] . "</option>");
										}

									}while($linha = mysqli_fetch_assoc($dados));
									mysqli_free_result($dados);
								}



	echo("
									</td>
								</tr>
								<tr>
									<td>
										<label>STATUS:</label>
									</td>
									<td>
										<select id='cmb_status_login' name='cmb_status_login' class='form-control' onchange='mostraBotaoAtualizaDadosUsuario()'>
		");
								if($st_ativo == 1)
								{
									echo("
											<option value=1 selected>ATIVO</option>
											<option value=0>DESATIVADO</option>
										");
								}
								else
								{
									echo("
											<option value=1>ATIVO</option>
											<option value=0 selected>DESATIVADO</option>
										");

								}

	echo("							
									</td>
								</tr>
		");
	
	echo("
								<tr>
									<td colspan='2'>
										<button type='button' id='btn_resetarSenhaUsuario' onclick='resetarSenhaUsuario()' class='btn btn-warning btn-sm btn-block'>
											<i class='glyphicon glyphicon glyphicon-refresh'></i>  RESETAR SENHA
										</button>
									</td>
								</tr>
		");



	echo("		</table>
			</div>
						
		");

//DIV BOTAO ATUALIZA DADOS DO USUÁRIO
echo("	
		<div id='div_btn_salva_dados_usuario' hidden>
			<button type='button' id='btn_atualizaDadosUsuário' onclick='atualizaDadosUsuario()' class='btn btn-success btn-sm btn-block'>
					<i class='glyphicon glyphicon-floppy-disk'></i>  SALVAR ALTERAÇÕES DE INFORMAÇÕES DO USUÁRIO
			</button>
		</div>
		
		<div class='col-md-12' id='div_resultado_dados_usuario'>
			
		</div>
	");
		
//FECHA DIV LINHA COLUNA DAS INFORMACOES DO USUÁRIO
echo("</div>");

//COLUNA ACESSO CARTEIRAS
echo("<div class='col-md-2'>
			<label>CARTEIRAS:</label>
			<select multiple id='sel_mult_carteiras' class='form-control' size=10 style='height: 100%;'>
		");

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
						id_carteira,
						sg_carteira
			 FROM carteira
			 where id_carteira not in (SELECT id_carteira from login_carteira WHERE id_login = $id_login)
			 AND id_carteira in (SELECT id_carteira FROM login_carteira WHERE id_login = $id_login_logado)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do
	  {
	  	
	  	echo("<option value='". $linha['id_carteira'] . "'>" . $linha['sg_carteira'] . "</option>");

	  	}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	
}
echo("</select></div>");

//DIV DOS CONTROLES DE ACESSO CARTEIRAS
echo("<div class='col-md-2'>
		<br><br><br>

		<center><button type='button' class='btn btn-default' id='btnAddAcessoCarteira' onclick='addAcessoCarteiraUsuario()'><i class='glyphicon glyphicon-arrow-right'></i></button></center>

		<br>

		<center><button type='button' class='btn btn-default' id='btnAddAcessoCarteira' onclick='removeAcessoCarteiraUsuario()'><i class='glyphicon glyphicon-arrow-left'></i></button></center>
		

	</div>");

//BUSCA CARTEIRAS QUE O MILITAR POSSUI ACESSO
	echo("<div class='col-md-2'>
			<label>POSSUI ACESSO EM:</label>
			<select multiple id='sel_mult_carteiras_acesso' class='form-control' size=10 style='height: 100%;'>
		");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
						id_carteira,
						sg_carteira
			 FROM carteira
			 where id_carteira in (SELECT id_carteira from login_carteira WHERE id_login = $id_login)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{

	
	do
	  {
	  	
	  	echo("<option value='". $linha['id_carteira'] . "'>" . $linha['sg_carteira'] . "</option>");

	  	}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	
}
echo("</select><br></div>");


//COLUNA ACESSO AOS MÓDULOS
echo("<div class='col-md-2'>
			<label>MÓDULOS:</label>
			<select multiple id='sel_mult_modulo' class='form-control' size=5 style='height: 100%;'>
		");

include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
						id_modulo,
						nm_modulo
			 FROM modulo
			 where id_modulo not in (SELECT id_modulo from modulo_permissao WHERE id_login = $id_login)
			 AND id_modulo in (SELECT id_modulo FROM modulo_permissao WHERE id_login = $id_login_logado)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
	do
	  {
	  	
	  	echo("<option value='". $linha['id_modulo'] . "'>" . $linha['nm_modulo'] . "</option>");

	  	}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	
}
echo("</select></div>");

//DIV DOS CONTROLES DE ACESSO AOS MÓDULOS
echo("<div class='col-md-2'>
		<br><br>

		<center><button type='button' class='btn btn-default' id='btnAddAcessoModulo' onclick='addAcessoModuloUsuario()'><i class='glyphicon glyphicon-arrow-right'></i></button></center>

		<br>

		<center><button type='button' class='btn btn-default' id='btnAddAcessoModulo' onclick='removeAcessoModuloUsuario()'><i class='glyphicon glyphicon-arrow-left'></i></button></center>
		

	</div>");

//BUSCA MODULOS QUE O MILITAR POSSUI ACESSO
	echo("<div class='col-md-2'>
			<label>POSSUI ACESSO EM:</label>
			<select multiple id='sel_mult_modulos_acesso' class='form-control' size=5 style='height: 100%;'>
		");
include ("../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");
$query = "SELECT 
						id_modulo,
						nm_modulo
			 FROM modulo
			 where id_modulo in (SELECT id_modulo from modulo_permissao WHERE id_login = $id_login)";
$dados = mysqli_query($conn,$query) or die(mysql_error());
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{

	
	do
	  {
	  	
	  	echo("<option value='". $linha['id_modulo'] . "'>" . $linha['nm_modulo'] . "</option>");

	  	}while($linha = mysqli_fetch_assoc($dados));
		mysqli_free_result($dados);
	
}
echo("</select></div>");



//DIV BOTAO ATUALIZA ACESSO CARTEIRAS USUÁRIO
echo("

		
			<div class='col-md-6' id='div_btn_atualiza_carteira' hidden>
				<br>
				<button type='button' id='btn_atualizaAcessoCarteiras' onclick='atualizaAcessoCarteiras()' class='btn btn-success btn-sm btn-block'>
						<i class='glyphicon glyphicon-floppy-disk'></i>  SALVAR ALTERAÇÕES DE ACESSO EM CARTEIRAS
				</button>
				<br>
			</div>

			<div class='col-md-6' id='div_resultado_acesso_carteiras'>
				<br>
			</div>
		
	");


//DIV BOTAO ATUALIZA ACESSO MODULOS USUÁRIO
echo("

		
			<div class='col-md-6' id='div_btn_atualiza_modulo' hidden>
				<br>
				<button type='button' id='btn_atualizaAcessoModulos' onclick='atualizaAcessoModulos()' class='btn btn-success btn-sm btn-block'>
						<i class='glyphicon glyphicon-floppy-disk'></i>  SALVAR ALTERAÇÕES DE ACESSO EM MODULOS
				</button>
				<br>
			</div>

			<div class='col-md-6' id='div_resultado_acesso_modulos'>
				<br>
			</div>
		
	");


//FECHA DIV LINHA 1
	echo("
		</div>
	");



//ABRE DIV LINHA E COLUNA 12 PARA OMS QUE O USUÁRIO POSSUI PERMISSÃO
echo("<div class='row'>
					<div class='col-md-12'><br>");

echo("
<label>UNIDADES DE SFPC QUE O USUÁRIO POSSUI ACESSO</label>
                

                <div class='table-responsive'>
                  <table class='table table-condensed table-bordered' id='tb_unidades_acesso'>
                    <thead>
                      <tr>
                       
                        <th>
                        
                          <label class='btn btn-primary btn-xs btn-block'>
                              <input type='checkbox' name='unidades[]'  autocomplete='off' onClick='MarcarDesmarcarTodasUnidades(this.checked)'> Marcar / Desmarcar Todas
                          </label>
                        </th>
                       
                        
                        
                      </tr>
                    </thead>
                  <tbody>
");
                    
                      //INSERE AS UNIDADES OM SFPC QUE O MILITAR POSSUI ACESSO E MARCA SELECIONADO
                      //Conecta no Banco de Dados
                      include ("../funcoes/conexao.php");
                      mysqli_query($conn,"SET NAMES 'utf8';");
                      $query1 = "SELECT 
                                      unidade.id_unidade,
                                      nm_unidade,
                                      cidade.nm_cidade,
                                      cidade.uf_cidade

                                  FROM unidade
                                  INNER JOIN cidade on cidade.id_cidade = unidade.id_cidade
                                  INNER JOIN login_unidade on unidade.id_unidade = login_unidade.id_unidade
                                  WHERE login_unidade.id_login = $id_login
                           ";
                      // executa a query
                      $dados1 = mysqli_query($conn,$query1) or die(mysql_error());
                      // transforma os dados em um array
                      $linha1 = mysqli_fetch_assoc($dados1);
                      // calcula quantos dados retornaram
                      $totalLinhas1 = mysqli_num_rows($dados1);
                      $id_unidades_possui_acesso = null;
                      if($totalLinhas1 > 0)
                      {
                        do{
                            $id_unidade = $linha1['id_unidade'];
                            $nm_unidade = $linha1['nm_unidade'];
                            $nm_cidade = $linha1['nm_cidade'];
                            $nm_cidade .= " - ";
                            $nm_cidade .= $linha1['uf_cidade'];



                            echo("

                                  <tr class='active'>
                                    
                                    <td>
                                      
                                      <label class='btn btn-default btn-sm btn-block'>
                                                <input type='checkbox' name='unidades[]' checked autocomplete='off' onClick='inclui_exclui_unidade_selecionada(". $id_unidade .")' value='". $id_unidade ."'> ". $nm_unidade ." - ". $nm_cidade ."
                                      </label>
                                    </td>
                                    
                                    
                                  </tr>


                                ");
                           $id_unidades_possui_acesso .= $id_unidade;
                           $id_unidades_possui_acesso .= ",";

                          }while($linha1 = mysqli_fetch_assoc($dados1));
                          mysqli_free_result($dados1);
                      }
                      //$tamanho = strlen($id_unidades_possui_acesso);
                      $id_unidades_possui_acesso = substr($id_unidades_possui_acesso, 0, -1);

                      //INSERE AS UNIDADES FALTANTES QUE O MILITAR NÃO POSSUI ACESSO
                      $query2 = "SELECT 
                                      unidade.id_unidade,
                                      nm_unidade,
                                      cidade.nm_cidade,
                                      cidade.uf_cidade

                                  FROM unidade
                                  INNER JOIN cidade on cidade.id_cidade = unidade.id_cidade
                                  INNER JOIN login_unidade on unidade.id_unidade = login_unidade.id_unidade
                                  WHERE login_unidade.id_login = $id_login_logado
                                  AND unidade.id_unidade NOT IN (SELECT id_unidade FROM login_unidade WHERE id_login = $id_login)
                           ";
                      // executa a query
                      $dados2 = mysqli_query($conn,$query2) or die(mysql_error());
                      // transforma os dados em um array
                      $linha2 = mysqli_fetch_assoc($dados2);
                      // calcula quantos dados retornaram
                      $totalLinhas2 = mysqli_num_rows($dados2);

                      if($totalLinhas2 > 0)
                      {
                        do{
                            $id_unidade = $linha2['id_unidade'];
                            $nm_unidade = $linha2['nm_unidade'];
                            $nm_cidade = $linha2['nm_cidade'];
                            $nm_cidade .= " - ";
                            $nm_cidade .= $linha2['uf_cidade'];



                            echo("

                                  <tr class='active'>
                                    
                                    <td>
                                      
                                      <label class='btn btn-default btn-sm btn-block'>
                                                <input type='checkbox' name='unidades[]' autocomplete='off' onClick='inclui_exclui_unidade_selecionada(". $id_unidade .")' value='". $id_unidade ."'> ". $nm_unidade ." - ". $nm_cidade ."
                                      </label>
                                    </td>
                                    
                                    
                                  </tr>


                                ");
                        



                          }while($linha2 = mysqli_fetch_assoc($dados2));
                          mysqli_free_result($dados2);
                      }



                    
echo("

                  </tbody>
                </table>


                </div>

                
                
");
 
 //DIV BOTAO ATUALIZA ACESSO NAS UNIDADES QUE O USUÁRIO POSSUI ACESSO
echo("

		
			<div class='col-md-12' id='div_btn_atualiza_unidades_usuario'>
				
				<button type='button' id='btn_atualizaAcessoUnidades' onclick='atualizaAcessoUnidades()' class='btn btn-success btn-sm btn-block'>
						<i class='glyphicon glyphicon-floppy-disk'></i>  SALVAR ALTERAÇÕES DE ACESSO EM UNIDADES
				</button>
				<br>
			</div>

			<div class='col-md-12' id='div_resultado_acesso_unidades'>
				<br>
			</div>
		
	");



	//FECHA DIV coluna
		echo("
			</div>
		");
//FECHA DIV LINHA 2
	echo("
		</div>
	");

echo("<input id='id_login' value='$id_login' hidden></input>
	  <input id='id_unidades_selecionadas' value = ". $id_unidades_possui_acesso ." hidden></input>
	  <input id='id_carteiras_selecionadas' hidden></input>
	  <input id='id_modulos_selecionados' hidden></input>");

?>
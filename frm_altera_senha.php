<?php
include ("funcoes/verificaAtenticacao.php");
include("funcoes/modulo_permissao.php");
$url = $_GET['url'];


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Sistema de Protocolo Eletronico SFPC - 2RM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PAGINA DE ALTERAÇÃO DE SENHA">
  <meta name="author" content="2 TEN SARABANDO">

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
  
  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/moment.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
 
  <script src="alterasenha/senha.js" type="text/javascript"></script>

 </head>

<body>




	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h2 class="panel-title">ALTERAÇÃO DA SENHA DE ACESSO AO SIGAPCE</h2>
	    UNIDADE: <?php if(isset($nm_unidade)){echo($nm_unidade);}?>
	  </div>
		<div class="panel-body">
		    <!-- ... Corpo do Painel ... -->
		    
			<!-- ... Div Linha ... -->
		    <div class="row">
		    	<!-- ... Div Coluna da Esquerda ... -->
		    	<div class="col-md-12">

              <div class="row">

                <div class="col-md-3">
        
                  <form>
                        <div class="form-group">
                          <label for="txt_nova_senha">Nova Senha</label>
                          <input type="password" class="form-control" id="txt_nova_senha" maxlength="20" placeholder="Digite a Nova Senha">
                        </div>
                        <div class="form-group">
                          <label for="txt_confirmacao_nova_senha">Confirme Nova Senha</label>
                          <input type="password" class="form-control" id="txt_confirmacao_nova_senha" maxlength="20" placeholder="Confirme a Nova Senha">
                        </div>
                        <br>
                        <button type="button" onclick="alterarSenha()" class="btn btn-success btn-lg btn-block btn-sm"><i class="glyphicon glyphicon-pencil"></i> ALTERAR</button>
                </form>
                
                <br>

                <div id="div_mensagem">
                   
                </div>
                  
                  <div id="div_msg_sucesso" hidden>
                      <div class='alert alert-success' role='alert'><i class='glyphicon glyphicon-ok-sign'></i> <b>Senha Alterada com Sucesso!</b></div>
                  </div>

                  <div id="div_msg_falha" hidden>
                      <div class='alert alert-danger' role='alert'><i class='glyphicon glyphicon-exclamation-sign'></i><b> Senha não Alterada!</b> A Senha não confere com a confirmação, tente novamente!</div>
                  </div>

                  <div id="div_msg_alerta" hidden>
                      <div class='alert alert-warning' role='alert'><i class='glyphicon glyphicon-exclamation-sign'></i><b> Senha não Alterada!</b> A Senha deve possuir no mínimo 6 caracteres</div>
                  </div>
                 
                  <div id="div_msg_info" hidden>
                      <div class='alert alert-info' role='alert'><i class='glyphicon glyphicon-exclamation-sign'></i><b> Informe a nova Senha e Confirme!</b> Mínimo 6 caracteres</div>
                  </div>

                  <?php
                    if($url == 'senha_expirada')
                    {
                      echo("<div id='div_senha_expirada'>");
                    }
                    else
                    {
                      echo("<div id='div_senha_expirada' hidden>");
                    }
                    echo("
                              <div class='alert alert-danger' role='alert'><i class='glyphicon glyphicon-exclamation-sign'></i><b> SENHA EXPIRADA!</b> Altere sua senha de acesso para continuar.</div>
                            </div>
                      ");
                  ?>
                  
                     

                </div>

              </div>

		    	</div>
		    </div>
		</div>
	</div>

</body>

</html>
<?php
//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
  session_start();
}
//$id_login = $_SESSION['id_login_sfpc'];
$id_agendamento_login = $_GET['id_agendamento_login'];
//$id_login_perfil = $_SESSION['id_login_perfil'];
?>



<div class='form-group'>
  <label for='cmb_status_login'>SELECIONE O NOVO STATUS:</label>
  <select id="cmb_status_login" name="cmb_status_login" class="form-control" onchange="exibeOcultaDivInformacoesMotivoStatusLogin()">
    <?php
      //Preenche o combo do tipo de solicitação
      //Conecta no Banco de Dados
      include ("../../funcoes/conexao.php");
      mysqli_query($conn,"SET NAMES 'utf8';");
      $query = "SELECT  agendamento_login_status.id_agendamento_login_status,
                        agendamento_login_status.nm_status
                FROM
                    agendamento_login_status

                WHERE
                    id_agendamento_login_status > 1 

                AND id_agendamento_login_status <> 
                (
                  SELECT 
                  agendamento_login_status.id_agendamento_login_status
                      
                  FROM agendamento_login
                  
                  INNER JOIN agendamento_login_historico on agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login
                  INNER JOIN agendamento_login_status on agendamento_login_status.id_agendamento_login_status = agendamento_login_historico.id_agendamento_login_status
                  WHERE agendamento_login_historico.id_agendamento_login_historico IN (SELECT max(agendamento_login_historico.id_agendamento_login_historico) FROM agendamento_login_historico WHERE agendamento_login_historico.id_agendamento_login = agendamento_login.id_agendamento_login)
                  AND agendamento_login.id_agendamento_login = $id_agendamento_login
                )
           ";
      // executa a query
      $dados = mysqli_query($conn,$query) or die(mysql_error());
      // transforma os dados em um array
      $linha = mysqli_fetch_assoc($dados);
      // calcula quantos dados retornaram
      $totalLinhas = mysqli_num_rows($dados);

      if($totalLinhas > 0)
      {
        do{
          echo("<option value='". $linha['id_agendamento_login_status'] . "'>" . $linha['nm_status'] . "</option>");

        }while($linha = mysqli_fetch_assoc($dados));
        mysqli_free_result($dados);
        
      }
    ?>              
  </select>
</div>
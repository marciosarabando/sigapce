<?php
//Pega Valores na SESSION
if (!isset($_SESSION)) 
{
  session_start();
}
  $id_login = $_SESSION['id_login_sfpc'];
  $id_processo_status_atual = $_GET['id_processo_status'];
  $id_login_perfil = $_SESSION['id_login_perfil'];
?>



<div class='form-group'>
  <label for='txt_msg_nota_informativa'>SELECIONE O NOVO STATUS:</label>
  <select id="cmb_status_dependencia" name="cmb_status_dependencia" class="form-control" onchange="">
    <?php
      //Preenche o combo do tipo de solicitação
      //Conecta no Banco de Dados
      include ("../funcoes/conexao.php");
      mysqli_query($conn,"SET NAMES 'utf8';");
      $query = "SELECT  processo_status.id_processo_status,
                        processo_status.nm_processo_status
                FROM
                        processo_status
                WHERE
                        processo_status.id_processo_status in (SELECT id_processo_status_avanca FROM status_dependencia where id_processo_status = $id_processo_status_atual)
                ORDER BY processo_status.nm_processo_status
           ";
      // executa a query
      $dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
      // transforma os dados em um array
      $linha = mysqli_fetch_assoc($dados);
      // calcula quantos dados retornaram
      $totalLinhas = mysqli_num_rows($dados);

      if($totalLinhas > 0)
      {
        do{
          echo("<option value='". $linha['id_processo_status'] . "'>" . $linha['nm_processo_status'] . "</option>");

          if(($id_login_perfil == 1 || $id_login_perfil == 2 || $id_login_perfil == 3 || $id_login_perfil == 4) && ($id_processo_status_atual == 6 || $id_processo_status_atual == 7 || $id_processo_status_atual == 13))
          {
            echo("<option value='5'>RE-ANÁLISE</option>");
          }
        }while($linha = mysqli_fetch_assoc($dados));
        mysqli_free_result($dados);
        
      }
    ?>              
  </select>
</div>
  
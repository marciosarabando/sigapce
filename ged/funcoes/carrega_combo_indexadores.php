<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");  

$id_tipo_documento = $_GET['tipo_documento'];

?>
<div class='form-group'>
  <label>PESQUISAR POR</label><br>
  <?php
    echo("<select id='cmb_indexador' name='cmb_indexador' class='form-control input-sm' onchange='carregaCampoParametro()'>"); 
    //Preenche o combo do tipo de documento
    // //Conecta no Banco de Dados

    $query = "SELECT 
                  documento_indexador.id_documento_indexador,
                  documento_indexador.id_documento_indexador_formato,
                  documento_indexador.nm_documento_indexador
              FROM documento_tipo_indexadores
              INNER JOIN documento_tipo on documento_tipo.id_documento_tipo = documento_tipo_indexadores.id_documento_tipo
              INNER JOIN documento_indexador on documento_indexador.id_documento_indexador = documento_tipo_indexadores.id_documento_indexador
              WHERE documento_tipo.id_documento_tipo = $id_tipo_documento
              ORDER BY documento_tipo_indexadores.nr_ordem";

              //echo($query);

    // // executa a query
    $dados = mysqli_query($conn,$query) or die(mysql_error());
    // transforma os dados em um array
    $linha = mysqli_fetch_assoc($dados);
    // calcula quantos dados retornaram
    $totalLinhas = mysqli_num_rows($dados);

    if($totalLinhas > 0)
    {
     do{
          echo("<option value='". $linha['id_documento_indexador'] . ",". $linha['id_documento_indexador_formato'] ."'>" . $linha['nm_documento_indexador'] . "</option>");  
     }while($linha = mysqli_fetch_assoc($dados));
     mysqli_free_result($dados); 
     echo("<option value='dt_upload,3'>DATA DA IMPORTAÇÃO</option>");  
    }
  ?>
  </select>
</div>
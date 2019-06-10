<?php
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';");  

$id_documento_tipo = $_GET['id_documento_tipo'];
$id_indexadores = null;

$query = "SELECT 
                  documento_indexador.id_documento_indexador,
                  documento_indexador.id_documento_indexador_formato,
                  documento_indexador.nm_documento_indexador
              FROM documento_tipo_indexadores
              INNER JOIN documento_tipo on documento_tipo.id_documento_tipo = documento_tipo_indexadores.id_documento_tipo
              INNER JOIN documento_indexador on documento_indexador.id_documento_indexador = documento_tipo_indexadores.id_documento_indexador
              WHERE documento_tipo.id_documento_tipo = $id_documento_tipo AND documento_tipo_indexadores.id_documento_indexador not in (3,4)
              ORDER BY documento_tipo_indexadores.nr_ordem";
// // executa a query
$dados = mysqli_query($conn,$query) or die(mysql_error());
// transforma os dados em um array
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
if($totalLinhas > 0)
{
 do{
 		$nm_documento_indexador = $linha['nm_documento_indexador'];
 		$id_documento_indexador = $linha['id_documento_indexador'];
 		if($id_indexadores == null)
 		{
 			$id_indexadores = $id_documento_indexador;
 		}
 		else
 		{
 			$id_indexadores .= "|";
 			$id_indexadores .= $id_documento_indexador;
 		}
 		echo("	
	 		<div class='row'>
	 			<div class='col-md-4'>
					<label>$nm_documento_indexador</label>
				</div>

				<div class='col-md-8'>
					<input type='text' onblur='' class='form-control input-sm upper' id='txt_$id_documento_indexador' name='txt_$id_documento_indexador'/>
				</div>
			</div>
			<p>
		"
		);	
 	}while($linha = mysqli_fetch_assoc($dados));
 mysqli_free_result($dados); 

echo("<input id='txt_id_indexadores' value='$id_indexadores' hidden></input>");

}
?>
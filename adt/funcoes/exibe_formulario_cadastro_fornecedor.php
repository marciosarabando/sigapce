<?php
//EXIBE CADASTRO DE NOVO FORNECEDOR DE ARMA
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 

//BOTAO DE INCLUSÃO DO FORNECEDOR
echo("	
		<div id='div_btn_novo_fornecedor'>
			<button id='btn_incluir_novo_fornecedor' class='btn btn-primary btn-sm' onclick='mostra_form_novo_fornecedor()'><i class='glyphicon glyphicon-plus-sign'></i> INCLUIR NOVO FORNECEDOR</button>
		</div>
	");

//FORM INPUT NOVO FORNECEDOR
echo("
		<div class='row' id='div_form_cad_fornecedor' hidden>
            
            <div class='row'>
                <div class='col-md-3'>	
                    <label>RAZÃO SOCIAL:</label>
                </div>
                <div class='col-md-7'>	
                    <input id='txt_nome_fornecedor' type='text' class='upper form-control input-sm'></input>
                </div>
                
            </div>
            
            <div class='row'>
            
                <div class='col-md-3'>	
                    <label>CNPJ:</label>
                </div>
                <div class='col-md-7'>	
                    <input id='txt_cnpj_fornecedor' type='text' class='upper form-control input-sm'></input>
                </div>
            
            
                <div class='col-md-2'>
                    <button id='btn_insere_fornecedor' class='btn btn-success btn-sm' onclick='incluir_novo_fornecedor()'><i class='glyphicon glyphicon-floppy-disk'></i></button>
                </div>
            
            </div>
            
		</div>
	");


echo("<hr>");

//EXIBE CADASTRO DE FORNECEDOR
$query = "SELECT id_adt_arma_fornecedor, nm_adt_arma_fornecedor, cnpj FROM adt_arma_fornecedor";
$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
$count = 0;
if($totalLinhas > 0)
{
	do
	  {
	  		$id_adt_arma_marca[$count] = $linha['id_adt_arma_fornecedor'];
            $nm_adt_arma_fornecedor[$count] = $linha['nm_adt_arma_fornecedor'];
	  		$cnpj[$count] = $linha['cnpj'];
	  		$count = $count + 1;
	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);

	  echo("
	  		<div class='panel panel-default'>
				<div class='panel-body'>

		  			<div class='table-responsive'>
		  				<table id='tb_fornecedor' class='table table-responsive table-condensed'>
		  					<thead>
		  						<th>
		  							RAZÃO SOCIAL
		  						</th>
                                <th>
		  							CNPJ
		  						</th>
		  					</thead>
		  					<tbody>
	  	");
	  for($x = 0; $x < $count; $x++)
	  {
	  	echo("
			  					<tr>
			  						<td>
			  							<font color='green'><b>$nm_adt_arma_fornecedor[$x]</b></font>
			  						</td>
                                    <td>
			  							<font color='green'><b>$cnpj[$x]</b></font>
			  						</td>
			  					</tr>
			");		  				
	  }

		  echo("			</tbody>
		  				</table>
		  			</div>
		  		</div>
		  	</div>
	  		");

}
else
{
	echo("<p class='text-danger'><i class='glyphicon glyphicon-info-sign'></i> Não foram encontrados registros de Fornecedor.</p>");
}

?>
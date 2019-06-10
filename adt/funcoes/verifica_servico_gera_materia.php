<?php
//VERIFICA SE O SERVICO DO PROCESSO A SER DEFERIDO OU INDEFERIDO GERA MATERIA PARA ADITAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 
date_default_timezone_set('America/Sao_Paulo');
if (!isset($_SESSION)) 
{
	session_start();
}

$id_unidade_usuario = $_SESSION['id_unidade_sfpc'];
$id_processo = $_GET['id_processo'];


$query = "SELECT 
			servico.id_adt_materia_tipo,
			interessado.cpf_interessado,
			interessado.cnpj_interessado,
			servico.st_adt_possui_form,
			parametro.vl_parametro as st_unidade_gera_adt
		FROM processo 
		INNER JOIN processo_servico on processo_servico.id_processo = processo.id_processo
		INNER JOIN servico on servico.id_servico = processo_servico.id_servico
		INNER JOIN interessado on interessado.id_interessado = processo.id_interessado
		LEFT JOIN parametro on parametro.id_modulo = 4 AND parametro.nm_parametro = 'st_unidade_gera_adt' AND parametro.id_unidade = $id_unidade_usuario
		WHERE processo.id_processo = $id_processo";

$dados = mysqli_query($conn,$query) or die(mysqli_error($conn));
$linha = mysqli_fetch_assoc($dados);
// calcula quantos dados retornaram
$totalLinhas = mysqli_num_rows($dados);
//Exibe as Solicitacoes de Acesso ao Sistema
if($totalLinhas > 0)
{
	do
	  {
	  	$id_adt_materia_tipo = $linha['id_adt_materia_tipo'];
	  	$cpf_interessado = $linha['cpf_interessado'];
	  	$cnpj_interessado = $linha['cnpj_interessado'];
	  	$st_adt_possui_form = $linha['st_adt_possui_form'];
	  	$st_unidade_gera_adt = $linha['st_unidade_gera_adt'];

	  	//VERIFICA SE A UNIDADE TRABALHA COM O ADITAMENTO NO SIGAPCE
	  	// if($st_unidade_gera_adt == '1')
	  	// {

	  	// }

	  	//VERIFICA SE PESSOA JURIDICA
	  	if($cnpj_interessado != '')
	  	{
	  		$tipo_pessoa_interessado = 'PJ';
	  	}
	  	else
	  	{
	  		$tipo_pessoa_interessado = 'PF';	
	  	}

	  	//VERIFICA SE TRATA-SE DE REGISTRO DE PESSOA JURÍDICA E ALTERA O TIPO DA MATERIA PARA 2
	  	//PARA EXIBIR O FORMULÁRIO DE PREENCHIMENTO DAS ATIVIDADES / PCE PARA PJ
	  	if($cnpj_interessado != '' && $id_adt_materia_tipo == 1)
	  	{
	  		$id_adt_materia_tipo = 2;
	  	}


	  }while($linha = mysqli_fetch_assoc($dados));
	  mysqli_free_result($dados);
}

echo("<input hidden id='st_unidade_gera_adt' value='$st_unidade_gera_adt'></input>");
echo("<input hidden id='txt_id_adt_materia_tipo' value='$id_adt_materia_tipo'></input>");
echo("<input hidden id='txt_st_adt_possui_form' value='$st_adt_possui_form'></input>");
echo("<input hidden id='txt_tp_pessoa_interessado' value='$tipo_pessoa_interessado'></input>");

?>
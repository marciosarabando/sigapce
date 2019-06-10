<?php
//SALVA DADOS DO CEDENTE - FORMULÁRIO TRANSFERÊNCIA DE ARMAMENTO
include ("../../funcoes/verificaAtenticacao.php");
include ("../../funcoes/conexao.php");
mysqli_query($conn,"SET NAMES 'utf8';"); 


$tipo_pessoa_cedente = $_GET['tipo_pessoa_cedente'];
$cpf_cedente = $_GET['cpf_cedente'];
$cnpj_cedente = $_GET['cnpj_cedente'];
$txt_nome_cedente = mb_strtoupper($_GET['txt_nome_cedente'],'UTF-8');
$txt_cr_cedente = $_GET['txt_cr_cedente'];
$txt_fone_cedente = $_GET['txt_fone_cedente'];
$id_cidade = $_GET['id_cidade'];
$email_cedente = $_GET['email_cedente'];

$query = "INSERT INTO interessado VALUES (null, $id_cidade, '$tipo_pessoa_cedente', '$txt_nome_cedente', '$cpf_cedente', '$cnpj_cedente', '$txt_cr_cedente', null, '$txt_fone_cedente', '$email_cedente')";
mysqli_query($conn,$query) or die(mysqli_error($conn));

//echo($query);


?>
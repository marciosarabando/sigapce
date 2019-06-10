<?php
//Atualiza Parametro
include ("../../funcoes/parametro.php");

$id_unidade = $_GET['id_unidade'];
$id_modulo = $_GET['id_modulo'];
$nm_parametro = $_GET['nm_parametro'];
$valor = $_GET['valor'];
//echo("chegou aui");
atualizaValorParametro($id_unidade,$id_modulo,$nm_parametro,$valor);

?>
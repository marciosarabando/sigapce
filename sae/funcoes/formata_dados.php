<?php
function limpaCPF_CNPJ($valor){
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor;
}

function formataData($valor)
{
if($valor <> '')
{
    $valor = trim($valor);
    $dia = substr($valor, -10, 2);
    $mes = substr($valor, -7, 2);
    $ano = substr($valor, -4, 4);

    $valor = $ano;
    $valor .= "-";
    $valor .= $mes;
    $valor .= "-";
    $valor .= $dia;
}
return $valor;
}

function formataDataDDMMYYYY($valor)
{
//2016-07-20
$valor = trim($valor);
$ano = substr($valor, -10, 4);
$mes = substr($valor, -5, 2);
$dia = substr($valor, -2, 2);

$valor = $dia;
$valor .= "/";
$valor .= $mes;
$valor .= "/";
$valor .= $ano;
return $valor;
}

function formataCPF($valor)
{
$valor = trim($valor);
$primeiro = substr($valor, -11, 3);
$segundo = substr($valor, -8, 3);
$terceiro = substr($valor, -5, 3);
$digito = substr($valor, -2, 2);

$valor = $primeiro;
$valor .= ".";
$valor .= $segundo;
$valor .= ".";
$valor .= $terceiro;
$valor .= "-";
$valor .= $digito;

return $valor;
}

function formataCNPJ($valor)
{
$valor = trim($valor);
$primeiro = substr($valor, -14, 2);
$segundo = substr($valor, -12, 3);
$terceiro = substr($valor, -9, 3);
$quarto = substr($valor, -6, 4);
$digito = substr($valor, -2, 2);

$valor = $primeiro;
$valor .= ".";
$valor .= $segundo;
$valor .= ".";
$valor .= $terceiro;
$valor .= "/";
$valor .= $quarto;
$valor .= "-";
$valor .= $digito;

return $valor;
}

function retornaDataExtenso($data_selecionada)
{

$stamp = strtotime(str_replace("/","-",$data_selecionada));

	$data = date('D',$stamp);
    $mes = date('M',$stamp);
    $dia = date('d',$stamp);
    $ano = date('Y',$stamp);

$semana = array(
    'Sun' => 'Domingo', 
    'Mon' => 'Segunda-Feira',
    'Tue' => 'Terca-Feira',
    'Wed' => 'Quarta-Feira',
    'Thu' => 'Quinta-Feira',
    'Fri' => 'Sexta-Feira',
    'Sat' => 'Sábado'
);

$mes_extenso = array(
    'Jan' => 'Janeiro',
    'Feb' => 'Fevereiro',
    'Mar' => 'Marco',
    'Apr' => 'Abril',
    'May' => 'Maio',
    'Jun' => 'Junho',
    'Jul' => 'Julho',
    'Aug' => 'Agosto',
    'Nov' => 'Novembro',
    'Sep' => 'Setembro',
    'Oct' => 'Outubro',
    'Dec' => 'Dezembro'
);

$data_extenso = $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
return $data_extenso;
}
?>
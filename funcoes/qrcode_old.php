<?php

function gerarQRCODE($protocolo, $cpf_cnpj, $tipo_pessoa)
{
	//$cd_protocolo = "00162017";
	//$filename = "../sisprot/qrcode/00162017.png";
	include("../componentes/qrcode/qrlib.php");
	//QRcode::png($cd_protocolo);
	//QRcode::png($cd_protocolo, $filename); 
	//echo '<img src="level_M.png" />';

	//include('../lib/full/qrlib.php'); 
	//include('config.php'); 

	// how to save PNG codes to server 
	$tempDir = getcwd();
	$tempDir .= "/qrcode/"; 
	 
	 
	// we need to generate filename somehow,  
	// with md5 or with database ID used to obtains $codeContents... 
	//$fileName = '005_file_'.md5($codeContents).'.png'; 
	$fileName = $protocolo.'.png'; 
	 
	$pngAbsoluteFilePath = $tempDir.$fileName; 
	$urlRelativeFilePath = "/protocolo/qrcode/".$fileName; 

	//$codeContents = "http://192.168.10.192/consultaonline/buscaDadosProcessoInteressado.php?cpf_cnpj=". $cpf_cnpj ."&protocolo=". $protocolo ."&tipo_pesquisa=".$tipo_pessoa; 
	 $codeContents = "http://www.2rm.eb.mil.br/sfpc/consultaonline/buscaDadosProcessoInteressado.php?cpf_cnpj=". $cpf_cnpj ."&protocolo=". $protocolo ."&tipo_pesquisa=".$tipo_pessoa; 
	// generating 
	if (!file_exists($pngAbsoluteFilePath)) { 
	    QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 2); 
	    //echo 'File generated!'; 
	    //echo '<hr />'; 
	} else { 
	    //echo 'File already generated! We can use this cached file to speed up site on common codes!'; 
	    //echo '<hr />'; 
	} 
	 
	//echo 'Server PNG File: '.$pngAbsoluteFilePath; 
	//echo '<hr />'; 
	 
	// displaying 
	//echo '<img src="'.$urlRelativeFilePath.'" />';
}

?>
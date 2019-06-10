// Função que verifica se o navegador tem suporte AJAX 
function AjaxF()
{
	var ajax;
	
	try
	{
		ajax = new XMLHttpRequest();
	} 
	catch(e) 
	{
		try
		{
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e) 
		{
			try 
			{
				ajax = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) 
			{
				alert("Seu browser não da suporte à AJAX!");
				return false;
			}
		}
	}
	return ajax;
}


var myVar;

$(document).ready(function()
{

	//setInterval(function(){ atualizaDadosIndicadoresTela() }, 1000);
	//exibeIndicadoresUnidade();
	var id_unidade = document.getElementById('cmb_unidade').value;
	myVar = setInterval(function(){ atualizaDadosIndicadoresTela(id_unidade) }, 1000);




});

function exibeIndicadoresUnidade()
{
	clearInterval(myVar);
	var id_unidade = document.getElementById('cmb_unidade').value;
	myVar = setInterval(function(){ atualizaDadosIndicadoresTela(id_unidade) }, 1000);
}


function atualizaDadosIndicadoresTela(id_unidade)
{
	//alert('entrou');
	//setInterval(exibeTotalProtocoloUnidadeDia(), 1000);
	//setInterval(exibeTotalGerenciadoUnidadeDia(), 1000);
	//nm_unidade = document.getElementById('cmb_unidade').options[e.selectedIndex].value;

	var e = document.getElementById("cmb_unidade");
	var nm_unidade = e.options[e.selectedIndex].text;

	$("#div_unidade").html(nm_unidade);
	exibeTotalProtocoloUnidadeDia(id_unidade);
	exibeTotalGerenciadoUnidadeDia(id_unidade);
	exibeTotalGerenciadoUnidadeDiaAnalista(id_unidade);
}

function exibeTotalProtocoloUnidadeDia(id_unidade)
{
	//alert('TESTE');
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_protocolados_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_protocolos_dia.php?id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

function exibeTotalGerenciadoUnidadeDia(id_unidade)
{
	//alert('TESTE');
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_gerenciados_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_gerenciados_dia.php?id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

function exibeTotalGerenciadoUnidadeDiaAnalista(id_unidade)
{
	//alert('TESTE');
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_gerenciados_analista_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_gerenciados_dia_analista.php?id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}
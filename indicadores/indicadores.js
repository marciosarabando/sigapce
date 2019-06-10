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

//$(document).ready(function()
//{
	//setInterval(function(){ atualizaDadosIndicadoresTela() }, 1000);
	//exibeIndicadoresUnidade();

//	var id_unidade = document.getElementById('cmb_unidade').value;
//	myVar = setInterval(function(){ atualizaDadosIndicadoresTela(id_unidade) }, 1000);

//});

function exibeIndicadoresUnidade()
{
	clearInterval(myVar);
	var id_unidade = document.getElementById('cmb_unidade').value;
	myVar = setInterval(function(){ atualizaDadosIndicadoresTela(id_unidade) }, 1000);
}

function exibeIndicadoresUnidadePeriodo()
{
	//alert('exibe');
	clearInterval(myVar);
	var id_unidade = document.getElementById('cmb_unidade').value;
	atualizaDadosIndicadoresTela(id_unidade);
	//$('#cmb_unidade').focus();
}

function muda_tipo_pesquisa()
{
	var tipo_pesquisa = $("input[name='rdb_tipo_pesquisa']:checked").val();
	//alert(tipo_pesquisa);
	if(tipo_pesquisa == 1)
	{
		var data = new Date();

		var dia = data.getDate();
		var mes = data.getMonth() + 1;
		var ano = data.getFullYear();

		if(mes < 10)
		{
			mes = '0' + mes;
		}

		var data_atual = dia + '/' + mes + '/' + ano;

		document.getElementById("txt_dt_inicio_periodo").value = data_atual;
		document.getElementById("txt_dt_fim_periodo").value = data_atual;

		document.getElementById("txt_dt_inicio_periodo").disabled = true;
		document.getElementById("txt_dt_fim_periodo").disabled = true;
		document.getElementById("btn_pesquisa_periodo").disabled = true;

		exibeIndicadoresUnidade();
	}
	else
	{
		document.getElementById("txt_dt_inicio_periodo").disabled = false;
		document.getElementById("txt_dt_fim_periodo").disabled = false;
		document.getElementById("btn_pesquisa_periodo").disabled = false;
		
		clearInterval(myVar);
	}
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
	
	var RegExp = /[/|']/g;
	var data_inicio = document.getElementById('txt_dt_inicio_periodo').value;
	data_inicio = data_inicio.replace(RegExp,"-");
	var data_fim = document.getElementById('txt_dt_fim_periodo').value;
	data_fim = data_fim.replace(RegExp,"-");
	var data_inicio_y_m_d = data_inicio.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');
	var data_fim_y_m_d = data_fim.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');

	//alert(dataFormatada);

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_protocolados_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_protocolos_dia.php?id_unidade="+id_unidade+"&data_inicio="+data_inicio_y_m_d+"&data_fim="+data_fim_y_m_d;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

function exibeTotalGerenciadoUnidadeDia(id_unidade)
{
	var RegExp = /[/|']/g;
	var data_inicio = document.getElementById('txt_dt_inicio_periodo').value;
	data_inicio = data_inicio.replace(RegExp,"-");
	var data_fim = document.getElementById('txt_dt_fim_periodo').value;
	data_fim = data_fim.replace(RegExp,"-");
	var data_inicio_y_m_d = data_inicio.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');
	var data_fim_y_m_d = data_fim.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_gerenciados_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_gerenciados_dia.php?id_unidade="+id_unidade+"&data_inicio="+data_inicio_y_m_d+"&data_fim="+data_fim_y_m_d;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

function exibeTotalGerenciadoUnidadeDiaAnalista(id_unidade)
{
	var RegExp = /[/|']/g;
	var data_inicio = document.getElementById('txt_dt_inicio_periodo').value;
	data_inicio = data_inicio.replace(RegExp,"-");
	var data_fim = document.getElementById('txt_dt_fim_periodo').value;
	data_fim = data_fim.replace(RegExp,"-");
	var data_inicio_y_m_d = data_inicio.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');
	var data_fim_y_m_d = data_fim.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_gerenciados_analista_dia').innerHTML = ajax.responseText;
	 	}
	}
	url = "indicadores/exibe_total_gerenciados_dia_analista.php?id_unidade="+id_unidade+"&data_inicio="+data_inicio_y_m_d+"&data_fim="+data_fim_y_m_d;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

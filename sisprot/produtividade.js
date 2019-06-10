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

function exibeRelatorioPerformanceSisprot()
{
	var id_unidade = document.getElementById('cmb_unidade').value;
//	var qtd_amostragem = document.getElementById('qtd_amostragem').value;
	var id_carteira = document.getElementById('cmb_carteira').value;
	var id_servico = document.getElementById('cmb_servico').value;
	var tp_interessado = document.getElementById('cmb_interessado').value;
	var dt_inicial = document.getElementById('txt_dt_inicio_periodo').value;
	var dt_final = document.getElementById('txt_dt_fim_periodo').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_result_rel').innerHTML = ajax.responseText;
	 	}
	}

	//"&qtd_amostragem="+qtd_amostragem+
	url = "sisprot/exibe_rel_prod_sisprot.php?id_unidade="+id_unidade+"&dt_inicial="+dt_inicial+"&dt_final="+dt_final+"&id_carteira="+id_carteira+"&id_servico="+id_servico+"&tp_interessado="+tp_interessado;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();


}

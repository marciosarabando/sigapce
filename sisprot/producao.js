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

function exibeRelatorioProducaoSisprot()
{
	var id_unidade = document.getElementById('cmb_unidade').value;
	var id_carteira = document.getElementById('cmb_carteira').value;
	var id_status = document.getElementById('cmb_status').value;
	var id_prazo = document.getElementById('cmb_prazo').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_result_rel').innerHTML = ajax.responseText;
	 	}
	}

	//"&qtd_amostragem="+qtd_amostragem+
	url = "sisprot/exibe_prod.php?id_unidade="+id_unidade+"&id_carteira="+id_carteira+"&id_status="+id_status+"&id_prazo="+id_prazo;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();


}

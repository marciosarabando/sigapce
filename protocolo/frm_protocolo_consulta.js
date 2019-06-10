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

function alteraCampoPesquisa()
{
	document.getElementById('div_pesquisa_data').style.display = 'none';
	document.getElementById('div_pesquisa_protocolo').style.display = 'none';
	document.getElementById('div_pesquisa_cpf').style.display = 'none';
	document.getElementById('div_pesquisa_cnpj').style.display = 'none';
	document.getElementById('div_resultado_consulta').style.display = 'none';
	document.getElementById('div_pesquisa_cr').style.display = 'none';
	document.getElementById('div_pesquisa_tr').style.display = 'none';
	document.getElementById('div_nm_requerente').style.display = 'none';
	document.getElementById('div_pesquisa_prontos').style.display = 'none';

	var tipo_pesquisa = document.getElementById('cmb_tipo_pesquisa').value;
	//alert(tipo_pesquisa);

	if(tipo_pesquisa == 'protocolo')
	{
		document.getElementById('div_pesquisa_protocolo').style.display = 'block';
		$( "#txt_pesquisa_protocolo" ).focus();
	}
	else if(tipo_pesquisa == 'data')
	{
		document.getElementById('div_pesquisa_data').style.display = 'block';
		buscaProcessosPesquisa();
	}
	else if(tipo_pesquisa == 'cpf')
	{
		document.getElementById('div_pesquisa_cpf').style.display = 'block';
		$( "#txt_pesquisa_cpf" ).focus();
	}
	else if(tipo_pesquisa == 'cnpj')
	{
		document.getElementById('div_pesquisa_cnpj').style.display = 'block';
		$( "#txt_pesquisa_cnpj" ).focus();
	}
	else if(tipo_pesquisa == 'cr')
	{
		document.getElementById('div_pesquisa_cr').style.display = 'block';
		$( "#txt_pesquisa_cr" ).focus();
	}
	else if(tipo_pesquisa == 'tr')
	{
		document.getElementById('div_pesquisa_tr').style.display = 'block';
		$( "#txt_pesquisa_tr" ).focus();
	}
	else if(tipo_pesquisa == 'requerente')
	{
		document.getElementById('div_nm_requerente').style.display = 'block';
		$( "#txt_nm_requerente" ).focus();
	}
	else if(tipo_pesquisa == 'processos_prontos')
	{
		document.getElementById('div_pesquisa_prontos').style.display = 'block';
		buscaProcessosPesquisa();

	}
}

function buscaProcessosPesquisa()
{
	document.getElementById('div_resultado_consulta').style.display = 'block';
	var tipo_pesquisa = document.getElementById('cmb_tipo_pesquisa').value;
	var ajax = AjaxF();	
	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_consulta').innerHTML = ajax.responseText;
	 	}
	 }

	var valor_pesquisa = null;

	if(tipo_pesquisa == 'protocolo')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_protocolo').value;
	}
	else if(tipo_pesquisa == 'data')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_data').value;
	}
	else if(tipo_pesquisa == 'cpf')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_cpf').value;
	}
	else if(tipo_pesquisa == 'cnpj')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_cnpj').value;
	}
	else if(tipo_pesquisa == 'cr')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_cr').value;
	}
	else if(tipo_pesquisa == 'tr')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_tr').value;
	}
	else if(tipo_pesquisa == 'requerente')
	{
		valor_pesquisa = document.getElementById('txt_nm_requerente').value;
	}
	else if(tipo_pesquisa == 'processos_prontos')
	{
		valor_pesquisa = 'processos_prontos';
	}

	url = "protocolo/buscaDadosProcessoPesquisa.php?tipo_pesquisa="+tipo_pesquisa+"&valor_pesquisa="+valor_pesquisa;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();


	$(document).ready(function() 
		{
	        $('#tb_protocolos').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
		            "aaSorting": [[0, "desc"]]
		        }

		    );
    	} );

	

}

function exibeDetalhesProcesso(cd_protocolo_processo)
{
	var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_painel_dados_consulta').innerHTML = ajax.responseText;
	 	}
	 }

	url = "protocolo/exibeDetalhesProcesso.php?cd_protocolo="+cd_protocolo_processo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function imprimirResultadoPesquisa()
{
	$("#div_resultado_consulta").printElement();
}

function entregarProcessoProntoDetalhes()
{
	var id_processo = document.getElementById('txt_id_processo').value;
	var cd_protocolo_processo = document.getElementById('txt_cd_protocolo_processo').value;
	var id_processo_status_novo = 10;
	var obs_processo_andamento = document.getElementById('txt_observacao_entrega').value;

    var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_processado').innerHTML = ajax.responseText;
	 	}
	 }

	url = "protocolo/entrega_processo_pronto.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status_novo+"&obs_processo_andamento="+obs_processo_andamento;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	setTimeout(function() { exibeDetalhesProcesso(cd_protocolo_processo); }, 500);	
}

function entregarProcessoPronto(id_processo)
{
	var cd_protocolo_processo = document.getElementById('txt_cd_protocolo_processo').value;
	var id_processo_status_novo = 10;
	var obs_processo_andamento = document.getElementById('txt_observacao_entrega').value;
	//alert(id_processo);

    var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_processado').innerHTML = ajax.responseText;
	 	}
	 }

	url = "protocolo/entrega_processo_pronto.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status_novo+"&obs_processo_andamento="+obs_processo_andamento;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	setTimeout(function() { buscaProcessosPesquisa(); }, 500);	
	
}
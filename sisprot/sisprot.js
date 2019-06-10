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

function exibeDashBoard()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_dashboard_sisprot').innerHTML = ajax.responseText;
	 	}
	}


	url = "sisprot/exibe_dashboard_sisprot.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function carregaComboServico()
{
	var id_carteira = document.getElementById('cmb_carteira').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_combo_servico').innerHTML = ajax.responseText;
	 	}
	}


	url = "sisprot/carrega_combo_servico_frm_rel.php?id_carteira="+id_carteira;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibeRelatorioSisprot()
{
	$('#div_tab_nr_processo').html("");
	$('#div_tab_resultado').click();

	var id_unidade = document.getElementById('cmb_unidade').value;
	var id_carteira = document.getElementById('cmb_carteira').value;
	var id_servico = document.getElementById('cmb_servico').value;
	var id_status = document.getElementById('cmb_status').value;
	var tp_interessado = document.getElementById('cmb_interessado').value;
	var id_procurador = document.getElementById('cmb_procurador').value;
	var dt_inicial = document.getElementById('txt_dt_inicio_periodo').value;
	var dt_final = document.getElementById('txt_dt_fim_periodo').value;
	var tipo_pesquisa = $("input[name='rdb_tipo_pesquisa']:checked").val();

	//alert(tipo_pesquisa);

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_result_rel').innerHTML = ajax.responseText;
	 	}
	}


	url = "sisprot/exibe_rel_sisprot.php?id_unidade="+id_unidade+"&id_carteira="+id_carteira+"&id_servico="+id_servico+"&id_status="+id_status+"&tp_interessado="+tp_interessado+"&id_procurador="+id_procurador+"&dt_inicial="+dt_inicial+"&dt_final="+dt_final+"&tipo_pesquisa="+tipo_pesquisa;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$(document).ready(function() 
		{
	        $('#tb_relatorio').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]]
		            
		        }

		    );
    	} );
}

function exibeDetalhesProcesso(cd_protocolo_processo)
{
	//alert(cd_protocolo_processo);
	$('#div_tab_nr_processo').html(cd_protocolo_processo);
    var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_detalhes_processo').innerHTML = ajax.responseText;
	 	}
	}

	url = "protocolo/exibeDetalhesProcesso.php?cd_protocolo="+cd_protocolo_processo;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$('#div_tab_nr_processo').click();

}

 
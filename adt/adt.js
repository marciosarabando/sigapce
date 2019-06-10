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

$(function () 
{
	$('#txt_data_adt').datetimepicker({format: 'DD/MM/YYYY'});
    $('#txt_data_adt').mask('99/99/9999');
});

function exibeDashBoard()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_dashboard_adt').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/exibe_dashboard_adt.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	//exibeTotalDocumentosDigitalizados();
}

function exibe_materias_pendentes()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_materias_pendentes').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/exibe_materias_pendentes.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$(document).ready(function() 
	{
	    $('#tb_revisao_materias').dataTable
	    ( 
	    	{
	            "language": {"url": "js/dataTablesPT.js"},
	            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
	            "aaSorting": [[6, "asc"]]
	        }
	    );
	});
}

function criar_nota_materias_sisprot()
{
	//Pegas as Matérias Selecionadas

	var chk_materias = document.getElementsByName('chk_materia');
	var id_materias = 0;
	for (var x = 0; x < chk_materias.length; x++)
    {
    	if(chk_materias[x].checked == true)
    	{
    		if(id_materias == 0)
    		{
    			id_materias = chk_materias[x].value;
    		}
    		else
    		{
    			id_materias += ",";
    			id_materias += chk_materias[x].value;
    		}
    	}
    }

    //alert(id_materias);


	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_notas_criadas').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/insere_nota_boletim.php?id_materias="+id_materias;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	exibe_materias_pendentes();
}

function exibirDetalhesMateria(id_adt_materia)
{
	
	$("#div_botoes_controles").show();
	$("#div_solicita_correcao").hide();
	document.getElementById('txt_correcao_materia').value = "";

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_detalhes_materia').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/exibe_detalhes_materia.php?id_adt_materia="+id_adt_materia;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function aprovar_materia()
{
	var id_materia_selecionada = document.getElementById('txt_id_materia_selecionada').value;
	//alert(id_materia_selecionada);
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_detalhes_materia').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/aprova_materia.php?id_adt_materia="+id_materia_selecionada;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	exibe_materias_pendentes();
}

function solicitar_correcao_materia()
{
	$("#div_botoes_controles").hide();
	$("#div_solicita_correcao").show();
	$("#txt_correcao_materia").focus();

}

function enviar_solicitacao_correcao_materia()
{
	
	var id_materia_selecionada = document.getElementById('txt_id_materia_selecionada').value;
	var txt_correcao_materia = document.getElementById('txt_correcao_materia').value;
	
	if(txt_correcao_materia != "")
	{
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('div_detalhes_materia').innerHTML = ajax.responseText;
		 	}
		}

		url = "adt/funcoes/solicita_correcao_materia.php?id_adt_materia="+id_materia_selecionada+"&txt_correcao_materia="+txt_correcao_materia;

		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
		$("#btn_fechar_modal").click();
		exibe_materias_pendentes();
	}
	else
	{
		$("#txt_correcao_materia").focus();
	}

}

function inclui_novo_adt()
{
	//alert('novo adt');

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_botao').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/teste_botao.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibe_materias_aprovadas()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_materias_aprovadas').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/exibe_materias_aprovadas.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	// $(document).ready(function() 
	// {
	//     $('#tb_revisao_materias').dataTable
	//     ( 
	//     	{
	//             "language": {"url": "js/dataTablesPT.js"},
	//             "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
	//             "aaSorting": [[0, "desc"]]
	//         }
	//     );
	// });
}

function exibe_materias_selecionadas(filtro)
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_materias_selecionadas').innerHTML = ajax.responseText;
	 	}
	}

	url = "adt/funcoes/exibe_materias_selecionadas.php?filtro=" + filtro;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

}


function seleciona_todas_materias() {
	var materias = document.getElementById('sel_mult_materias');
	var materias_sel = document.getElementById('sel_mult_materias_sel');
	var jaexiste = 0;
		
			
		//Adiciona os Itens Selecionados
		for(i=0; i<=materias.length-1; i++)
		{
		
			if(materias_sel.length > 0) {
	

				for(x=0; x<=materias_sel.length -1; x++)
				{
					
					if(materias.options[i].value == materias_sel.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
			}
			
				if(jaexiste == 0)
				{
									
					opt = new Option( materias.options[i].text, materias.options[i].value);
					materias_sel.options[materias_sel.length] = opt;
					//inclui_exclui_materia_selecionada(materias.options[i].value);
					verificaDados();  
				}
			
		}
		
		//Remove os Itens selecionados da lista de matérias aprovadas
		var materias = document.getElementById('sel_mult_materias');
		var materias_sel = document.getElementById('sel_mult_materias_sel');
		for(y=0; y<=materias.length-1; y++)
		{
				//alert(materias.options[y].value);
				for(z=0; z<=materias_sel.length-1; z++)
				{
					
					if(materias.options[y].value == materias_sel.options[z].value)
						{
							materias.remove(y);
						}
				}
			
		}
}


function addMateria()
{
	var materias = document.getElementById('sel_mult_materias');
	var materias_sel = document.getElementById('sel_mult_materias_sel');
	var jaexiste = 0;
					
		//Adiciona os Itens Selecionados
		for(i=0; i<=materias.length-1; i++)
		{
		
			if(materias.options[i].selected == true) {
			

			if(materias_sel.length > 0) {
	

				for(x=0; x<=materias_sel.length -1; x++)
				{
					
					if(materias.options[i].value == materias_sel.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
			}
			
				if(jaexiste == 0)
				{
									
					opt = new Option( materias.options[i].text, materias.options[i].value);
					materias_sel.options[materias_sel.length] = opt;
					//inclui_exclui_materia_selecionada(materias.options[i].value);
					verificaDados(); 
				}
			}
		}
		
		//Remove os Itens selecionados da lista de matérias aprovadas
		var materias = document.getElementById('sel_mult_materias');
		var materias_sel = document.getElementById('sel_mult_materias_sel');
		for(y=0; y<=materias.length-1; y++)
		{
			if(materias.options[y].selected == true)
			{
				//alert(materias.options[y].value);
				for(z=0; z<=materias_sel.length-1; z++)
				{
					
					if(materias.options[y].value == materias_sel.options[z].value)
						{
							materias.remove(y);
						}
				}
			}
		}
}


function removeMateria()
{
	var materias_sel = document.getElementById('sel_mult_materias');
	var materias = document.getElementById('sel_mult_materias_sel');
	var jaexiste = 0;
		
		//Adiciona os Itens Selecionados
		for(i=0; i<=materias.length-1; i++)
		{
			if(materias.options[i].selected == true)
			{
				
				for(x=0; x<=materias_sel.length-1; x++)
				{
					if(materias.options[i].value == materias_sel.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
				if(jaexiste == 0)
					{
						opt = new Option( materias.options[i].text, materias.options[i].value);
						materias_sel.options[materias_sel.length] = opt;
						//inclui_exclui_materia_selecionada(materias.options[i].value);
					}
			}
		}
		
		//Remove os Itens selecionados da lista de matérias seleecionadas
		var materias_sel = document.getElementById('sel_mult_materias_sel');
		var materias = document.getElementById('sel_mult_materias');
		for(i=0; i<=materias_sel.length-1; i++)
		{
			if(materias_sel.options[i].selected == true)
			{
				for(x=0; x<=materias_sel.length-1; x++)
				{
					if(materias_sel.options[i].value == materias_sel.options[x].value)
						{
							materias_sel.remove(i);
							verificaDados(); 
						}
				}
			}
		}
		//mostraBotaoAtualizamaterias();
		//ocultaDivMensagemCarteiraAtualizada();
}

function inclui_exclui_materia_selecionada(id_materia)
{
	
	var itemSelecionado = id_materia;
	var dados = document.getElementById('id_materias_selecionadas').value;

	if(dados == '')
	{
		dados = id_materia;
		document.getElementById('id_materias_selecionadas').value = dados;
	}
	else
	{	
	 	if(dados.match(itemSelecionado))
	 	{
  			//Se clicou denovo mesmo item remove da lista
  			//var dados = document.getElementById('id_servico_selecionado').value;
			var dados1 = dados.split(',');
			var result = null;
			for (var i in dados1) {
				if(dados1[i] != id_materia)
			    {
			    	if(result == null)
			    		result = dados1[i];
			    	else
			    		result = result + ',' + dados1[i];
			    }
			}
			document.getElementById('id_materias_selecionadas').value = result;
		}
		else
		{
		 	dados = document.getElementById('id_materias_selecionadas').value + "," + id_materia;
			document.getElementById('id_materias_selecionadas').value = dados;
		}
	}
	
	mostraBotaoAtualizaCarteiras();
	ocultaDivMensagemCarteiraAtualizada();
}

function verificaDados()
{
	var materias_sel = document.getElementById('sel_mult_materias_sel');
	var txt_nr_adt = document.getElementById('txt_nr_adt').value;
	var txt_nr_bar = document.getElementById('txt_nr_bar').value;
	var txt_data_adt = document.getElementById('txt_data_adt').value;

	var mostra_btn_salvar = 1; 
 
 	//se algum dos campos estiver vazio, não mostra o bptão 'salvar'
 	if (txt_nr_adt.length == 0) {
   		mostra_btn_salvar = 0;
 	}

	if (txt_nr_bar.length == 0) {
   		mostra_btn_salvar = 0;
 	}

 	if (txt_data_adt.length == 0) {
   		mostra_btn_salvar = 0;
 	}

	//se a lista de matérias selecionadas ficar vazia, não mostra o botão 'salvar'
	if(materias_sel.length == 0) {
		mostra_btn_salvar = 0;
	}


	if(mostra_btn_salvar == 0) {
		try{
			document.getElementById('div_btn_salva_adt').style.display = 'none';
		}
	catch(err){} 
	}

	else if(mostra_btn_salvar == 1) {
		try{
			document.getElementById('div_btn_salva_adt').style.display = 'block';
		}
	catch(err){} 
	}
}

function grava_adt()
{
	var id_materias = null;
	var materias_sel = document.getElementById('sel_mult_materias_sel');
			
	for(x=0; x<=materias_sel.length-1; x++)
	{
		if(id_materias == null)
		{
			id_materias = materias_sel.options[x].value;
		}
		else
		{
			id_materias = id_materias + ',' + materias_sel.options[x].value;
		}
		
	}
	
	if(id_materias != null) {
		document.getElementById('input_materias').value = id_materias;
		document.getElementById('my_form').submit(); 
	}

	/*
	var ajax = AjaxF();
	
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_acesso_carteiras').innerHTML = ajax.responseText;
	 	}
	}
	

	url = "adt/salva_adt.php?id_login="+id_login+"&id_materiass="+id_materias;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	ocultaBotaoAtualizaCarteiras();
	mostraDivMensagemCarteiraAtualizada();
	*/
}

function voltar_adt() {
	location.href="adt.php?url=aditamento"; 
}

function teste() {
	//teste de duplo clique (quero exibir os dados da matéria ao dar duplo clique nela, clique simples sleciona). 

	alert("Duplo clique OK"); 

	//var targLink    = document.getElementById ("something");
	//var clickEvent  = document.createEvent ('MouseEvents');
	//clickEvent.initEvent ('dblclick', true, true);
	//targLink.dispatchEvent (clickEvent);

}

function verificaDadosTipoMateria() {
	var tipo_materia = document.getElementById('tipo_materia').value;

	var mostra_btn_salvar = 1; 
 
 	//se algum dos campos estiver vazio, não mostra o bptão 'salvar'

 	if (tipo_materia.length == 0) {
   		mostra_btn_salvar = 0;
 	}

	if(mostra_btn_salvar == 0) {
		try{
			document.getElementById('div_btn_salva_tipo').style.display = 'none';
		}
	catch(err){} 
	}

	else if(mostra_btn_salvar == 1) {
		try{
			document.getElementById('div_btn_salva_tipo').style.display = 'block';
		}
	catch(err){} 
	}
}

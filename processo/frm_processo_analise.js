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

function carregaComboCarteira()
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_cmb_carteira').innerHTML = ajax.responseText;
	 	}
	 }
	
	 ajax.open("GET", "processo/carregaComboCarteiraAcesso.php?", false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
	 $( "#txt_pesquisa_protocolo" ).focus();
}

function alteraCampoPesquisa()
{
	document.getElementById('div_pesquisa_protocolo').style.display = 'none';
	document.getElementById('div_pesquisa_status').style.display = 'none';
	document.getElementById('div_pesquisa_cpf').style.display = 'none';
	document.getElementById('div_pesquisa_cnpj').style.display = 'none';
	document.getElementById('div_pesquisa_cr').style.display = 'none';
	document.getElementById('div_pesquisa_tr').style.display = 'none';
	document.getElementById('div_nm_requerente').style.display = 'none';
	document.getElementById('div_resultado_consulta').style.display = 'none';

	var tipo_pesquisa = document.getElementById('cmb_tipo_pesquisa').value;
	//alert(tipo_pesquisa);

	if(tipo_pesquisa == 'protocolo')
	{
		document.getElementById('div_pesquisa_protocolo').style.display = 'block';
		$( "#txt_pesquisa_protocolo" ).focus();
	}
	else if(tipo_pesquisa == 'status')
	{
		document.getElementById('div_pesquisa_status').style.display = 'block';
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
}

function mostra_oculta_FiltroData()
{
	var tipo_pesquisa_filtro = document.getElementById('cmb_filtro_data').value;
	document.getElementById('div_resultado_consulta').style.display = 'none';
	if(tipo_pesquisa_filtro == "data")
	{
		document.getElementById('div_txt_data_filtro').style.display = 'block';
	}
	else
	{
		document.getElementById('div_txt_data_filtro').style.display = 'none';
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
	var dt_filtro_pesquisa = "";

	if(tipo_pesquisa == 'protocolo')
	{
		valor_pesquisa = document.getElementById('txt_pesquisa_protocolo').value;
	}
	else if(tipo_pesquisa == 'status')
	{
		valor_pesquisa = document.getElementById('cmb_status').value;
		var tipo_pesquisa_filtro = document.getElementById('cmb_filtro_data').value;
		if(tipo_pesquisa_filtro == "data")
		{
			dt_filtro_pesquisa = document.getElementById('txt_data_filtro').value;
		}
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

	url = "processo/buscaDadosProcessoPesquisa.php?tipo_pesquisa="+tipo_pesquisa+"&valor_pesquisa="+valor_pesquisa+"&dt_filtro_pesquisa="+dt_filtro_pesquisa;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();


	$(document).ready(function() 
		{
	        $('#tb_processos_pesquisa').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
		            "aaSorting": [[0, "desc"]]
		        }

		    );
    	} );

}

function limpaCamposPesquisa()
{
	//alert('entrou nessa porra');
	document.getElementById('cmb_tipo_pesquisa').value = "protocolo";
	alteraCampoPesquisa();
	document.getElementById('txt_pesquisa_protocolo').focus();

}

function exibeTelaDetalhesProcessoAnalise(cd_protocolo_processo)
{
	var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_painel_analise_processo').innerHTML = ajax.responseText;
	 	}
	 }

	url = "processo/exibeDetalhesProcessoAnalise.php?cd_protocolo="+cd_protocolo_processo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function incluiNotaInformativaProcesso()
{
	
	var msg_nota_informativa = document.getElementById('txt_msg_nota_informativa').value;
	
	var RegExp = /["|']/g;
 	msg_nota_informativa=msg_nota_informativa.replace(RegExp,"");
    
	var id_processo = document.getElementById('txt_id_processo').value;
	var cd_protocolo_processo = document.getElementById('txt_cd_protocolo_processo').value;
	
	var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_painel_dados_consulta').innerHTML = ajax.responseText;
	 	}
	 }

	url = "processo/incluiNotaInformativaProcesso.php?id_processo="+id_processo+"&msg_nota_informativa="+msg_nota_informativa;
	
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	setTimeout(function() { exibeTelaDetalhesProcessoAnalise(cd_protocolo_processo); }, 500);	
}


function carregaComboStatusProcesso()
{
	 var id_processo_status = document.getElementById('txt_id_processo_status').value;
	 var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_cmbStatusProcessoDependente').innerHTML = ajax.responseText;
	 	}
	 }

	url = "processo/carregaComboStatusProcessoDependente.php?id_processo_status="+id_processo_status;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}

function avancaFluxoProcesso()
{
	 var id_processo_status_novo = document.getElementById('cmb_status_dependencia').value;
	 var id_processo = document.getElementById('txt_id_processo').value;
	 var obs_processo_andamento = document.getElementById('txt_obs_andamento_processo').value;
	 var cd_protocolo_processo = document.getElementById('txt_cd_protocolo_processo').value;
     
     var ajax = AjaxF();	
	 
	 //VERIFICA SE O PROCESSO ESTÁ SENDO DEFERIDO OU INDEFERIDO OU DEFERIDO PARCIALMENTE PARA GERAR MATÉRIA
	 if(id_processo_status_novo == 6 || id_processo_status_novo == 7 || id_processo_status_novo == 13)
	 {
	 	//VERIFICA SE O SERVICO E A UNIDADE GERA MATERIA PARA ADITAMENTO
	 	verificaServicoGeraMateria(id_processo);

 		
 		//st_adt = document.getElementById('txt_st_adt').value;
 		st_unidade_gera_adt = document.getElementById('st_unidade_gera_adt').value;
 		id_adt_materia_tipo = document.getElementById('txt_id_adt_materia_tipo').value;
 		st_adt_materia_informado = document.getElementById('txt_id_adt_materia_informado').value;
 		st_adt_possui_form = document.getElementById('txt_st_adt_possui_form').value;
 		tp_pessoa_interessado = document.getElementById('txt_tp_pessoa_interessado').value;

 		
 		// alert(st_unidade_gera_adt);
	 	// alert(id_adt_materia_tipo);
		// alert(st_adt_materia_informado);
		// alert(st_adt_possui_form);

	 	//SE A UNIDADE GERA ADITAMENTO E O SERVIÇO GERA A MATERIA E AINDA NÃO FOI INFORMADO
	 	//EXIBE O FORMULARIO DE PREENCHIMENTO
	 	if(id_adt_materia_tipo > 0 && st_adt_materia_informado == 0 && st_adt_possui_form == 1 && st_unidade_gera_adt == 1)
	 	{
	 		//ESTE SERVIÇO GERA MATÉRIA
	 		//alert('ESTE SERVICO GERA MATERIA E POSSUI FORMULÁRIO A SER PREENCHIDO');
	 		

	 		//SE NÃO FOR MATÉRIA DE REGISTRO DE PESSOA JURÍDICA, EXIBE O FORMULÁRIO
	 		//if(id_adt_materia_tipo != 104)
	 		//{
	 			$('#btn_alterarEstadoProcesso').hide();
	 			exibeFormularioPreencimentoDadosMateria(id_processo,id_adt_materia_tipo,id_processo);
	 		//}
	 		//else
	 	//	{
	 			//alert('GERA MATERIA PADRÃO E NÃO EXIBE O FORMULÁRIO PARA REGISTRO DE PJ');

	 	//	}


	 	}
	 	else if(st_adt_possui_form == 1 && st_adt_materia_informado == 1 && st_unidade_gera_adt == 1)
	 	{
	 		//ENTÃO DEFERE OU INDEFERE APÓS A MATÉRIA TER SIDO INFORMADA NA UNIDADE QUE GERA ADITAMENTO
	 		//alert('MATERIA INFORMADA, O PROCESO SERÁ DEFERIDO E A MATÉRIA SALVA');
	 		avancaFluxoProcessoPosMateriaAdt(id_processo_status_novo,id_processo,obs_processo_andamento,cd_protocolo_processo,1);
	 	}
	 	else if(id_adt_materia_tipo != 0 && st_adt_possui_form == 0 && st_unidade_gera_adt == 1)
	 	{
	 		//NESTE CASO NÃO POSSUI FORMULÁRIO A SER PREENCHIDO, 
	 		//ENTÃO CRIA A MATÉRIA PADRÃO CONFORME O TIPO DA MATÉRIA ESPECIFICADO
	 		//alert('GERA MATERIA, MAS NÃO POSSUI FORM! AVANÇA O FLUXO CRIANDO A MATÉRIA PADRÃO');
	 		avancaFluxoProcessoPosMateriaAdt(id_processo_status_novo,id_processo,obs_processo_andamento,cd_protocolo_processo,1);
	 	}
	 	else
	 	{
	 		//alert('NÃO GERA MATÉRIA, AVANÇA O FLUXO NORMALMENTE');
	 		avancaFluxoProcessoPosMateriaAdt(id_processo_status_novo,id_processo,obs_processo_andamento,cd_protocolo_processo,0);
	 	}
	 	
	 }
	 //AQUI AVANCA O FLUXO NORMALMENTE PARA TODOS OS STATUS, EXCETO DEFERIDO / DEF PARCIALMENTE / INDEFERIDO
	 else
	 {
		avancaFluxoProcessoPosMateriaAdt(id_processo_status_novo,id_processo,obs_processo_andamento,cd_protocolo_processo,0);
	 }

}


function avancaFluxoProcessoPosMateriaAdt(id_processo_status_novo,id_processo,obs_processo_andamento,cd_protocolo_processo, st_adt_materia_informado)
{
	if(st_adt_materia_informado == 1)
	{
		var id_adt_materia_tipo = document.getElementById('txt_id_adt_materia_tipo').value;
		//alert('matéria tipo: ' + id_adt_materia_tipo);

		insere_materia_aditamento(id_processo, id_adt_materia_tipo, id_processo_status_novo);
	}
	
    
    ///*
	var ajax = AjaxF();	
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_corpo_painel_dados_consulta').innerHTML = ajax.responseText;
		}
	}
 	url = "processo/avancaFluxoProcesso.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status_novo+"&obs_processo_andamento="+obs_processo_andamento;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	setTimeout(function() { exibeTelaDetalhesProcessoAnalise(cd_protocolo_processo); }, 500);
    //*/
    
    
    
    
}

function insere_materia_aditamento(id_processo, id_adt_materia_tipo, id_processo_status)
{
	//alert('Processo: ' + id_processo);
	//alert('id_adt_materia_tipo: ' + id_adt_materia_tipo);
	var ajax = AjaxF();	
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_st_materia_adt').innerHTML = ajax.responseText;
		}
	}
	var url = "";
	var nr_cr = null; 
	var data_val_cr = null; 

	//MATERIA 2a via / REGISTRO  / revalidação/ cancelamento de CR PESSOA FÍSICA
	if(
			id_adt_materia_tipo == 1 
		 || id_adt_materia_tipo == 14 
		 || id_adt_materia_tipo == 22 
		 || id_adt_materia_tipo == 12
	 )
	{
		//alert('Entrou pega atv selecionadas');
		//PEGA AS ATIVIDADES SELECIONADAS
		var chk_atividade = document.getElementsByName('chk_atividade');
		var id_atividades = null;
	
		//se for 2a via, revalidação ou cancelamento, pede os dados do CR
		if (id_adt_materia_tipo == 1 || id_adt_materia_tipo == 22 || id_adt_materia_tipo == 12) 
		{	
			var nr_cr = document.getElementById('txt_nr_cr').value;		

			//compondo a data de validade do CR no formato yyyy-mm-dd para o mysql (se for cancelamento, será considerada como data de cancelamento)
			var selector = document.getElementById('dia_val_cr');
	    	var dia_val_cr = selector[selector.selectedIndex].value;

			var selector = document.getElementById('mes_val_cr');
	    	var mes_val_cr = selector[selector.selectedIndex].value;

			var selector = document.getElementById('ano_val_cr');
	    	var ano_val_cr = selector[selector.selectedIndex].value;
			
			var data_val_cr = ano_val_cr + '-' + mes_val_cr + '-' + dia_val_cr; 
		}

		//alert(chk_atividade);
		for (var x = 0; x < chk_atividade.length; x++)
    	{
    		//alert('entrou no for');
    		if(chk_atividade[x].checked == true)
			{
				if(x == 0)
				{
					id_atividades = chk_atividade[x].value;	
				}
				else if(x != 0 && id_atividades != null)
				{
					id_atividades += ",";	
					id_atividades += chk_atividade[x].value;	
				}
				else
				{
					id_atividades = chk_atividade[x].value;	
				}    				
			}
    	}

    	//se for um cancelamento, não usaremos as atividades
    	if (id_adt_materia_tipo == 12)
    	{
    		id_atividades = ''; 
    	} 
    	
    	url = "adt/funcoes/insere_materia_tipo_registro.php?id_processo="+id_processo+"&id_atividades="+id_atividades+"&id_processo_status="+id_processo_status+"&id_adt_materia_tipo="+id_adt_materia_tipo +"&nr_cr="+nr_cr+"&val_cr="+data_val_cr;
		//alert(url); 
	}

	//MATERIA REGISTRO / revalidação / 2a via DE PESSOA JURÍDICA 
	else if(
		   id_adt_materia_tipo == 82 
     	|| id_adt_materia_tipo == 83 
		|| id_adt_materia_tipo == 120 
		|| id_adt_materia_tipo == 121
	)
	{
		var id_atvs_pces = ''; //document.getElementById('txt_id_atv_pce_incluidos').value;
		var nr_cr = null; 
		var data_val_cr = null; 
		
		//se for revalidação ou 2a via, pede os dados do CR
		if (
			id_adt_materia_tipo == 83 
			|| id_adt_materia_tipo == 120 
			|| id_adt_materia_tipo == 121
			) 
		{	
			var nr_cr = document.getElementById('txt_nr_cr').value;		

			//compondo a data de validade do CR no formato yyyy-mm-dd para o mysql
			var selector = document.getElementById('dia_val_cr');
	    	var dia_val_cr = selector[selector.selectedIndex].value;

			var selector = document.getElementById('mes_val_cr');
	    	var mes_val_cr = selector[selector.selectedIndex].value;

			var selector = document.getElementById('ano_val_cr');
	    	var ano_val_cr = selector[selector.selectedIndex].value;
			
			var data_val_cr = ano_val_cr + '-' + mes_val_cr + '-' + dia_val_cr; 
		}

//se for um cancelamento, não usaremos as atividades
    	if (id_adt_materia_tipo == 12)
    	{
    		id_atvs_pces = ''; 
    	} 

		url = "adt/funcoes/insere_materia_tipo_registro_pj.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status+"&id_atvs_pces="+id_atvs_pces+"&id_adt_materia_tipo="+id_adt_materia_tipo+"&nr_cr="+nr_cr+"&val_cr="+data_val_cr;
	}


	//se for mudança de vinculação de RM
	if (id_adt_materia_tipo == 2) 
	{	

		var selector = document.getElementById('rm_origem');
    	var rm_origem = selector[selector.selectedIndex].value;

		var selector = document.getElementById('rm_destino');
    	var rm_destino = selector[selector.selectedIndex].value;

    	//alert('da ' + rm_origem + ' para a ' + rm_destino);

		url = "adt/funcoes/insere_materia_tipo_vinculacao_rm.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status+"&id_adt_materia_tipo="+id_adt_materia_tipo+"&rm_origem="+rm_origem+"&rm_destino="+rm_destino;

	}


	//MATERIA AQUISIÇÃO DE ARMAMENTO
	else if(
				id_adt_materia_tipo == 35 
			 || id_adt_materia_tipo == 25
			 || id_adt_materia_tipo == 27
			 || id_adt_materia_tipo == 30
			 || id_adt_materia_tipo == 31
			 || id_adt_materia_tipo == 32
			 || id_adt_materia_tipo == 33
			 || id_adt_materia_tipo == 34
			 || id_adt_materia_tipo == 36
			 || id_adt_materia_tipo == 78
			 || id_adt_materia_tipo == 84 
			 || id_adt_materia_tipo == 85
			 || id_adt_materia_tipo == 86
			 || id_adt_materia_tipo == 88
			 || id_adt_materia_tipo == 102
			 || id_adt_materia_tipo == 28
			 || id_adt_materia_tipo == 24
			 || id_adt_materia_tipo == 37
			 || id_adt_materia_tipo == 38
			 || id_adt_materia_tipo == 39
			 || id_adt_materia_tipo == 42
			 || id_adt_materia_tipo == 74
		)
	{
		//PEGA OS DADOS DO FORMULÁRIO DE AQUISIÇÃO DE ARMAMENTO
		var id_fornecedor = document.getElementById('cmb_fornecedor').value;

		if(id_adt_materia_tipo != 84) {
			var txt_nr_nota_fiscal = document.getElementById('txt_nr_nota_fiscal').value;
		}
		else {
			var txt_nr_nota_fiscal = ''; 
		}

		if(
			   id_adt_materia_tipo == 84
			|| id_adt_materia_tipo == 33
			|| id_adt_materia_tipo == 86
			|| id_adt_materia_tipo == 35
			|| id_adt_materia_tipo == 34
			|| id_adt_materia_tipo == 85
		)
		{
			var txt_nr_sigma = ''; 			
		}
		else
		{
			var txt_nr_sigma = document.getElementById('txt_nr_sigma').value;
		}

		var txt_nr_arma = document.getElementById('txt_nr_arma').value;
		var txt_nr_sigma = document.getElementById('txt_nr_sigma').value;
		var id_origem = document.getElementById('cmb_arma_pais_origem').value;
		var id_marca = document.getElementById('cmb_marca').value;
		var id_modelo = document.getElementById('cmb_modelo').value;
		var id_acabamento = document.getElementById('cmb_arma_acabamento').value;
		var id_acervo = document.getElementById('cmb_acervo').value;

		url = "adt/funcoes/insere_materia_tipo_aqs_arma.php?id_processo="+id_processo+"&id_fornecedor="+id_fornecedor+"&txt_nr_nota_fiscal="+txt_nr_nota_fiscal+"&txt_nr_arma="+txt_nr_arma+"&id_origem="+id_origem+"&id_marca="+id_marca+"&id_modelo="+id_modelo+"&id_acabamento="+id_acabamento+"&id_acervo="+id_acervo+"&id_processo_status="+id_processo_status+"&id_adt_materia_tipo="+id_adt_materia_tipo+"&txt_nr_sigma="+txt_nr_sigma;
				
	}

	//MATERIA TRANSFERENCIA DE ARMAMENTO
	else if(
			id_adt_materia_tipo == 39 
		 || id_adt_materia_tipo == 40 
		 || id_adt_materia_tipo == 43 
		 || id_adt_materia_tipo == 44 
		 || id_adt_materia_tipo == 46
		 || id_adt_materia_tipo == 47
		 || id_adt_materia_tipo == 48
		 || id_adt_materia_tipo == 49
		 || id_adt_materia_tipo == 50
		 || id_adt_materia_tipo == 51
		 || id_adt_materia_tipo == 77
		 )
	{
		//PEGA OS DADOS DO FORMULÁRIO DE TRANSFERENCIA DE ARMAMENTO
		var id_interessado_cedente = document.getElementById('txt_id_interessado_cedente').value;
		var txt_nr_arma = document.getElementById('txt_nr_arma').value;
		var txt_nr_sigma = document.getElementById('txt_nr_sigma').value;
		var id_origem = document.getElementById('cmb_arma_pais_origem').value;
		var id_marca = document.getElementById('cmb_marca').value;
		var id_modelo = document.getElementById('cmb_modelo').value;
		var id_acabamento = document.getElementById('cmb_arma_acabamento').value;
		var id_acervo = document.getElementById('cmb_acervo').value;

		if (id_adt_materia_tipo == 39) {
			var txt_nr_sinarm = document.getElementById('txt_nr_sinarm').value;
		}
		else
			var txt_nr_sinarm = ''; 	

		url = "adt/funcoes/insere_materia_tipo_transf_arma.php?id_processo="+id_processo+"&id_interessado_cedente="+id_interessado_cedente+"&txt_nr_arma="+txt_nr_arma+"&txt_nr_sigma="+txt_nr_sigma+"&id_origem="+id_origem+"&id_marca="+id_marca+"&id_modelo="+id_modelo+"&id_acabamento="+id_acabamento+"&id_acervo="+id_acervo+"&id_processo_status="+id_processo_status+"&id_adt_materia_tipo="+id_adt_materia_tipo+"&nr_sinarm="+txt_nr_sinarm;
	}


	/////////////////// Aqui é feita a chamada do programa php que deveria fazer a inclusão dos dados da arma na tabela 'adt-arma' //////////
 	//alert(url);
 	//location.href=url; 
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	//alert('pausa');

}

function carrega_combo_acabamento_arma()
{
	//alert('entrou');
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_cmb_acabamento').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_acabamento.php";
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	$('#cmb_acabamento').focus();
}

function confirmar_atividades_selecionadas()
{
	$('#chk_1').attr('disabled', 'disabled');
	$('#chk_2').attr('disabled', 'disabled');
	$('#chk_3').attr('disabled', 'disabled');
	$('#div_info_atividades').hide();
	$('#btn_confirma_atividades').hide();
	$('#btn_alterarEstadoProcesso').show();
	$('#div_info_prosseguir_status').show();
	$('#txt_id_adt_materia_informado').val('1');
}

function verificaServicoGeraMateria(id_processo)
{
	//VERIFICA SE A MATERIA JÁ FOI GERADA
	if($('#div_st_materia_adt').html() == "")
	{
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			{
				document.getElementById('div_st_materia_adt').innerHTML = ajax.responseText;
			}
		}
	 	url = "adt/funcoes/verifica_servico_gera_materia.php?id_processo="+id_processo;
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
	}
}

function exibeFormularioPreencimentoDadosMateria(id_processo, id_adt_materia_tipo, id_processo)
{
	var ajax = AjaxF();
	url = "";
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_dados_materia_adt').innerHTML = ajax.responseText;
		}
	}

	//REGISTRO / cancelamento / revalidação / 2a via DE CR PESSOA FÍSICA
	if(id_adt_materia_tipo == 1 || id_adt_materia_tipo == 12 || id_adt_materia_tipo == 14 || id_adt_materia_tipo == 22)
	{
		url = "adt/funcoes/exibe_formulario_materia_registro.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;
 	}

 	//REGISTRO / revalidação / 2a via / cancelamento de CD PESSOA JURÍDICA
 	else if(id_adt_materia_tipo == 82 || id_adt_materia_tipo == 83 || id_adt_materia_tipo == 120 || id_adt_materia_tipo == 121)
 	{
 		url = "adt/funcoes/exibe_formulario_materia_registro_pj.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;
 	}

 	//Mudança de vinculação de RM
 	else if(id_adt_materia_tipo == 2)
 	{
 		url = "adt/funcoes/exibe_formulario_materia_vinculacao_rm.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;	
 	}

 	//ARMAMENTO AQUISIÇÃO PF / Autz / PJ
 	else if(
 		   id_adt_materia_tipo == 35 
 		|| id_adt_materia_tipo == 25
 		|| id_adt_materia_tipo == 27
 		|| id_adt_materia_tipo == 30
 		|| id_adt_materia_tipo == 31
 		|| id_adt_materia_tipo == 32
 		|| id_adt_materia_tipo == 33
 		|| id_adt_materia_tipo == 34
 		|| id_adt_materia_tipo == 36
 		|| id_adt_materia_tipo == 78
 		|| id_adt_materia_tipo == 84 
 		|| id_adt_materia_tipo == 85
 		|| id_adt_materia_tipo == 86
 		|| id_adt_materia_tipo == 88
 		|| id_adt_materia_tipo == 102
 		|| id_adt_materia_tipo == 28
 		|| id_adt_materia_tipo == 24
 		|| id_adt_materia_tipo == 37
 		|| id_adt_materia_tipo == 38
 		|| id_adt_materia_tipo == 39
 		|| id_adt_materia_tipo == 42
 		|| id_adt_materia_tipo == 74
 		)
 	{
 		url = "adt/funcoes/exibe_formulario_materia_aquisicao.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;
 	}

 	//ARMAMENTO TRANSFERÊNCIA ARMA
 	else if(
 		id_adt_materia_tipo == 40
 	 || id_adt_materia_tipo == 43	
 	 || id_adt_materia_tipo == 44
 	 || id_adt_materia_tipo == 46
 	 || id_adt_materia_tipo == 47
 	 || id_adt_materia_tipo == 48
 	 || id_adt_materia_tipo == 49
 	 || id_adt_materia_tipo == 50
 	 || id_adt_materia_tipo == 51
 	 || id_adt_materia_tipo == 77
 	 )
 	{
 		url = "adt/funcoes/exibe_formulario_materia_transferencia.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;
 	}

 	else if(id_adt_materia_tipo == 39)
 	{
 		url = "adt/funcoes/exibe_formulario_materia_transferencia_sinarm_sigma.php?id_adt_materia_tipo="+id_adt_materia_tipo+"&id_processo="+id_processo;	
 	}


 	//alert(url);
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	if(id_adt_materia_tipo == 4)
	{
		$("#cpf_cedente").mask("999.999.999-99");
		$("#cnpj_cedente").mask("99.999.999/9999-99");
		$("#cpf_cedente").focus();
	}

}

function mostra_campo_tipo_cedente(tipo_pessoa_cedente)
{
	document.getElementById('cpf_cedente').value = '';
	document.getElementById('cnpj_cedente').value = '';	
	document.getElementById('txt_nr_arma').value = '';
	$("#txt_nr_arma").prop("disabled", false);
	$("#btn_busca_dados_arma").prop("disabled", false);

	if(tipo_pessoa_cedente == 'PF')
	{
		$("#div_cnpj").hide();
		$("#div_cpf").show();
		$("#div_dados_cedente_arma").hide();
		$("#div_nr_arma").hide();
		$("#div_dados_arma").hide();
		$("#cpf_cedente").focus();
	}
	else
	{
		$("#div_cnpj").show();
		$("#div_cpf").hide();
		$("#div_dados_cedente_arma").hide();
		$("#div_nr_arma").hide();
		$("#div_dados_arma").hide();
		$("#cnpj_cedente").focus();
	}

}

function buscaDadosCedenteArmamento(tipo_pessoa_cedente)
{
	//VERIFICA O TIPO DE PESSOA SELECIONADA
	if(tipo_pessoa_cedente == 'PF')
	{
		var cpf_cedente = document.getElementById('cpf_cedente').value;
		cpf_cedente = cpf_cedente.replace(/[^\d]+/g,'');
		if(cpf_cedente.length == 11)
		{
			//alert(cpf_cedente);
			if(validarCPF(cpf_cedente))
			{
				//alert('CPF VÁLIDO');
				$("#div_dados_cedente_arma").show();
				var ajax = AjaxF();
				ajax.onreadystatechange = function(){
				if(ajax.readyState == 4)
					{
						document.getElementById('div_dados_cedente_arma').innerHTML = ajax.responseText;
					}
				}
			 	url = "adt/funcoes/busca_dados_cedente_arma.php?tipo_pessoa_cedente="+tipo_pessoa_cedente+"&cpf_cnpj="+cpf_cedente;
				ajax.open("GET",url, false);
				ajax.setRequestHeader("Content-Type", "text/html");
				ajax.send();
				$("#txt_nome_cedente").focus();
				//Inicio Mascara Telefone
				$('input[type=tel]').focusout(function(){
					var phone, element;
					element = $(this);
					element.unmask();
					phone = element.val().replace(/\D/g, '');
					if(phone.length > 10) {
						element.mask("(99) 99999-999?9");
					} else {
						element.mask("(99) 9999-9999?9");
					}
				}).trigger('focusout');
				//Fim Mascara Telefone
				document.getElementById('st_cedente').value = "1";

			}
			else
			{
				//alert('CPF INVÁLIDO');
				$("#div_msg_cpf_cnpj").html("<span class='label label-danger'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> CPF INVÁLIDO!</span>");	
				
			}
		}
		else
		{
			$("#div_msg_cpf_cnpj").html("");
			$("#div_dados_cedente_arma").html("");
			$("#div_nr_arma").hide();
			document.getElementById('st_cedente').value = "0";
		}
	}
	else
	{
		var cnpj_cedente = document.getElementById('cnpj_cedente').value;
		cnpj_cedente = cnpj_cedente.replace(/[^\d]+/g,'');
		if(cnpj_cedente.length == 14)
		{
			if(validarCNPJ(cnpj_cedente))
			{
				//alert('CNPJ VÁLIDO');
				$("#div_dados_cedente_arma").show();
				var ajax = AjaxF();
				ajax.onreadystatechange = function(){
				if(ajax.readyState == 4)
					{
						document.getElementById('div_dados_cedente_arma').innerHTML = ajax.responseText;
					}
				}
			 	url = "adt/funcoes/busca_dados_cedente_arma.php?tipo_pessoa_cedente="+tipo_pessoa_cedente+"&cpf_cnpj="+cnpj_cedente;
				ajax.open("GET",url, false);
				ajax.setRequestHeader("Content-Type", "text/html");
				ajax.send();
				$("#txt_nome_cedente").focus();
				//Inicio Mascara Telefone
				$('input[type=tel]').focusout(function(){
					var phone, element;
					element = $(this);
					element.unmask();
					phone = element.val().replace(/\D/g, '');
					if(phone.length > 10) {
						element.mask("(99) 99999-999?9");
					} else {
						element.mask("(99) 9999-9999?9");
					}
				}).trigger('focusout');
				//Fim Mascara Telefone
				document.getElementById('st_cedente').value = "1";

			}
			else
			{
				//alert('CNPJ INVÁLIDO');	
				$("#div_msg_cpf_cnpj").html("<span class='label label-danger'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> CNPJ INVÁLIDO! Corrija o CNPJ e tente novamente!</span>");	
			}
		}
		else
		{
			$("#div_msg_cpf_cnpj").html("");
			$("#div_dados_cedente_arma").html("");
			$("#div_nr_arma").hide();
			document.getElementById('st_cedente').value = "0";
		}

	}

	//VERIFICA SE O CEDENTE FOI ENCONTRADO
	var st_cedente = document.getElementById('st_cedente').value;
	if(st_cedente == '1')
	{
		//alert('cedente encontrado');
		//$("#div_nr_arma").show();
		//$("#txt_nr_arma").focus();
		$("#div_cmb_acervo").show();
		$("#cmb_acervo").focus();
	}
	else
	{
		//alert('NÃO encontrado');	
	}
}

function exibe_campo_nr_arma()
{
	$("#div_nr_arma").show();
	$("#txt_nr_arma").focus();
}

function exibe_cadastro_marca_arma()
{
	//alert('INCLUSAO DE NOVA MARCA DE ARMA');
	$("#div_title_modal_cadastro_geral").html("MARCA DE ARMAMENTO");

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/exibe_formulario_cadastro_marca_arma.php";
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	
	$('#tb_marca_armamento').dataTable
    ( 
    	{
            "language": {"url": "js/dataTablesPT.js"},
            "lengthMenu": [[5, 10, -1], [5, 10, "Todos"]],
            "aaSorting": [[0, "asc"]]
        }
    );
	
}

function exibe_cadastro_fornecedor()
{
	//alert('INCLUSAO DE NOVA MARCA DE ARMA');
	$("#div_title_modal_cadastro_geral").html("FORNECEDOR");

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/exibe_formulario_cadastro_fornecedor.php";
    
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	
	$('#tb_fornecedor').dataTable
    ( 
    	{
            "language": {"url": "js/dataTablesPT.js"},
            "lengthMenu": [[5, 10, -1], [5, 10, "Todos"]],
            "aaSorting": [[0, "asc"]]
        }
    );
	
}

function exibe_cadastro_modelo_arma()
{
	//alert('INCLUSAO DE NOVO MODELO DE ARMA');
	var txt_marca_arma = document.getElementById("cmb_marca").options[document.getElementById("cmb_marca").selectedIndex].text;
	var id_marca = document.getElementById("cmb_marca").value;

	$("#div_title_modal_cadastro_geral").html("MODELOS DA " + txt_marca_arma);
	$("#div_modal_body_cadastro_geral").html("");

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/exibe_formulario_cadastro_modelo_arma.php?id_marca="+id_marca;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	
	$('#tb_modelo_armamento').dataTable
    ( 
    	{
            "language": {"url": "js/dataTablesPT.js"},
            "lengthMenu": [[5, 10, -1], [5, 10, "Todos"]],
            "aaSorting": [[0, "asc"]]
        }
    );
}

function mostra_form_nova_marca_arma()
{
	$("#div_btn_nova_marca").hide();
	$("#div_form_cad_marca").show();
	$("#txt_cad_marca").focus();
}

function mostra_form_novo_modelo_arma()
{
	$("#div_btn_novo_modelo").hide();
	$("#div_form_cad_modelo").show();
	$("#txt_cad_nm_modelo").focus();
	$("#div_btn_salvar_modelo").show();
}

function mostra_form_novo_fornecedor()
{
	$("#div_btn_novo_fornecedor").hide();
	$("#div_form_cad_fornecedor").show();
	$("#txt_nome_fornecedor").focus(); 
    $("#txt_cnpj_fornecedor").mask("99.999.999/9999-99");
}


function incluir_nova_marca_arma()
{
	var txt_marca_arma = document.getElementById('txt_cad_marca').value;
	
	txt_marca_arma = txt_marca_arma.trim();
	txt_marca_arma = txt_marca_arma.toUpperCase();

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/insere_marca_arma.php?txt_marca_arma="+txt_marca_arma;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	
	carrega_combo_marca();
	
	//Seleciona o valor incluído na Combo Marca
	for (i = 0; i < document.getElementById("cmb_marca").length; i++) 
	{
		//teste = document.getElementById("cmb_marca").options[i].text;
    	//alert(teste);
    	if(document.getElementById("cmb_marca").options[i].text == txt_marca_arma)
    	{	
    		document.getElementById("cmb_marca").selectedIndex = i;
    	}
	}

	carrega_combo_modelo_arma_form_transf();
	//$("#cmb_modelo").focus();
}

function incluir_novo_fornecedor()
{
	var txt_nome_fornecedor = document.getElementById('txt_nome_fornecedor').value;
    var txt_cnpj_fornecedor = document.getElementById('txt_cnpj_fornecedor').value;
	
	txt_nome_fornecedor = txt_nome_fornecedor.trim();
	txt_nome_fornecedor = txt_nome_fornecedor.toUpperCase();
    
    txt_cnpj_fornecedor = txt_cnpj_fornecedor.replace(/[^\d]+/g,'');

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/insere_fornecedor.php?txt_nome_fornecedor="+txt_nome_fornecedor+"&txt_cnpj_fornecedor="+txt_cnpj_fornecedor;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
    
    carrega_combo_fornecedor();
    
    
   
    
}

function carrega_combo_fornecedor()
{
    var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_combo_fornecedor').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_fornecedor.php";
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function incluir_novo_modelo_arma()
{
	
	if(valida_formulario_inclusao_modelo_arma())
	{
		id_marca = document.getElementById('txt_id_marca').value;
		id_especie = document.getElementById('cmb_especie').value;
		id_calibre = document.getElementById('cmb_calibre').value;
		id_funcionamento = document.getElementById('cmb_funcionamento').value;
		id_alma = document.getElementById('cmb_alma').value;
		nm_modelo = document.getElementById('txt_cad_nm_modelo').value;
		qtd_cano = document.getElementById('cmb_qtd_cano').value;
		qtd_raia = document.getElementById('txt_qtd_raia').value;
		sentido_raia = document.getElementById('cmb_sentido_raia').value;
		comp_cano = document.getElementById('txt_comprimento_cano').value;
		cap_carregador = document.getElementById('txt_nr_capacidade_carregador').value;

		if(id_alma == 1)
		{
			qtd_raia = 0;
			sentido_raia = "S/A";
		}

		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			{
				document.getElementById('div_modal_body_cadastro_geral').innerHTML = ajax.responseText;
			}
		}
	 	url = "adt/funcoes/insere_modelo_arma.php?nm_modelo="+nm_modelo+"&id_marca="+id_marca+"&id_especie="+id_especie+"&id_calibre="+id_calibre+"&id_funcionamento="+id_funcionamento+"&id_alma="+id_alma+"&qtd_cano="+qtd_cano+"&qtd_raia="+qtd_raia+"&sentido_raia="+sentido_raia+"&comp_cano="+comp_cano+"&cap_carregador="+cap_carregador;
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();

		carrega_combo_modelo_arma_form_transf();

	}
	else
	{
		//alert('Falta dados');
	}

}

function valida_formulario_inclusao_modelo_arma()
{
	//VALIDA PREENCHIMENTO DO FORMULÁRIO
	id_marca = document.getElementById('txt_id_marca').value;
	id_especie = document.getElementById('cmb_especie').value;
	id_calibre = document.getElementById('cmb_calibre').value;
	id_funcionamento = document.getElementById('cmb_funcionamento').value;
	id_alma = document.getElementById('cmb_alma').value;
	nm_modelo = document.getElementById('txt_cad_nm_modelo').value;
	qtd_cano = document.getElementById('cmb_qtd_cano').value;
	qtd_raia = document.getElementById('txt_qtd_raia').value;
	sentido_raia = document.getElementById('cmb_sentido_raia').value;
	comp_cano = document.getElementById('txt_comprimento_cano').value;
	cap_carregador = document.getElementById('txt_nr_capacidade_carregador').value;

	
	if(nm_modelo == "")
	{
		$("#div_valida_modelo").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#txt_cad_nm_modelo").focus();
		return false;
	}
	else
	{
		$("#div_valida_modelo").html("");
	}
	
	if(id_especie == 0)
	{
		$("#div_valida_especie").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#cmb_especie").focus();	
		return false;
	}
	else
	{
		$("#div_valida_especie").html("");
	}

	if(id_calibre == 0)
	{
		$("#div_valida_calibre").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#cmb_calibre").focus();	
		return false;
	}
	else
	{
		$("#div_valida_calibre").html("");
	}

	if(id_funcionamento == 0)
	{
		$("#div_valida_funcionamento").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#cmb_funcionamento").focus();	
		return false;
	}
	else
	{
		$("#div_valida_funcionamento").html("");
	}

	if(id_alma == 0)
	{
		$("#div_valida_alma").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#cmb_alma").focus();	
		return false;
	}
	else
	{
		$("#div_valida_alma").html("");
	}

	if(id_alma == 2)
	{
		if(sentido_raia == 0)
		{
			$("#div_valida_sentido_raia").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
			$("#cmb_sentido_raia").focus();	
			return false;
		}
		else
		{
			$("#div_valida_sentido_raia").html("");
		}

		if(qtd_raia == 0)
		{
			$("#div_valida_qtd_raia").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
			$("#txt_qtd_raia").focus();	
			return false;
		}
		else
		{
			$("#div_valida_qtd_raia").html("");
		}
	}

	if(qtd_cano == 0)
	{
		$("#div_valida_qtd_cano").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#cmb_qtd_cano").focus();	
		return false;
	}
	else
	{
		$("#div_valida_qtd_cano").html("");
	}

	if(comp_cano == "")
	{
		$("#div_valida_comprimento_cano").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#txt_comprimento_cano").focus();	
		return false;
	}
	else
	{
		$("#div_valida_comprimento_cano").html("");
	}

	if(cap_carregador == "")
	{
		$("#div_valida_capacidade_carregador").html("<font size='4' color='red'><i class='glyphicon glyphicon-info-sign'></i></font>");
		$("#txt_comprimento_cano").focus();	
		return false;
	}
	else
	{
		$("#div_valida_capacidade_carregador").html("");
	}

	return true;


}

function carrega_combo_cidade_form_transf_arma()
{
	var cmb_estado = document.getElementById('cmb_estado').value;
	//alert(cmb_estado);
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_cidades').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_cidade.php?uf_estado="+cmb_estado;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	$("#cmb_cidade").focus();
}

function exibe_botao_salvar_cedente()
{
	$("#div_btn_salvar_cedente").hide();
	var txt_nome_cedente = document.getElementById('txt_nome_cedente').value;
	var txt_cr_cedente = document.getElementById('txt_cr_cedente').value;
	var txt_fone_cedente = document.getElementById('txt_fone_cedente').value;
	var cmb_estado = document.getElementById('cmb_estado').value;
	var cmb_cidade = document.getElementById('cmb_cidade').value;
	var email_cedente = document.getElementById('email_cedente').value;

	if(txt_nome_cedente == "")
	{
		$("#txt_nome_cedente").focus();
	}
	else if(txt_cr_cedente == "")
	{
		$("#txt_cr_cedente").focus();
	}
	else if(txt_fone_cedente == "")
	{
		$("#txt_fone_cedente").focus();
	}
	else if(cmb_estado == 0)
	{
		$("#cmb_estado").focus();
	}
	else if(cmb_cidade == 0)
	{
		$("#cmb_cidade").focus();
	}
	else if(email_cedente == "")
	{
		$("#email_cedente").focus();
	}
	else
	{
		//$("#div_btn_salvar_cedente").show();
		if(validacaoEmail())
		{
			$("#div_btn_salvar_cedente").show();
		}
	}
}

function salva_dados_cedente()
{
	
	if(document.getElementById("rdb_cpf").checked==true)
	{
		tipo_pessoa_cedente = "PF";
		cpf_cedente = document.getElementById('cpf_cedente').value;
		cpf_cedente = cpf_cedente.replace(/[^\d]+/g,'');
		cnpj_cedente = "";
	}
	else
	{
		tipo_pessoa_cedente = "PJ";
		cnpj_cedente = document.getElementById('cnpj_cedente').value;
		cnpj_cedente = cnpj_cedente.replace(/[^\d]+/g,'');
		cpf_cedente = "";
	}
	
	
	var txt_nome_cedente = document.getElementById('txt_nome_cedente').value;
	var txt_cr_cedente = document.getElementById('txt_cr_cedente').value;
	var txt_fone_cedente = document.getElementById('txt_fone_cedente').value;
	var id_cidade = document.getElementById('cmb_cidade').value;
	var email_cedente = document.getElementById('email_cedente').value;
	

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_dados_cedente_arma').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/salva_dados_cedente.php?tipo_pessoa_cedente="+tipo_pessoa_cedente+"&cpf_cedente="+cpf_cedente+"&cnpj_cedente="+cnpj_cedente+"&txt_nome_cedente="+txt_nome_cedente+"&txt_cr_cedente="+txt_cr_cedente+"&txt_fone_cedente="+txt_fone_cedente+"&id_cidade="+id_cidade+"&email_cedente="+email_cedente;
	//alert(url);
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	buscaDadosCedenteArmamento(tipo_pessoa_cedente);
}

function busca_dados_arma(mostra_sigma)
{
    $("#div_btn_confirma_dados").hide();
	var nr_arma = document.getElementById('txt_nr_arma').value;
    var id_adt_materia_tipo = document.getElementById('txt_id_adt_materia_tipo').value;

	//alert('OIA!' + id_adt_materia_tipo); 
	
	if(nr_arma != "")
	{
		$("#div_dados_arma").show();
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			{
				document.getElementById('div_dados_arma').innerHTML = ajax.responseText;
			}
		}
	 	url = "adt/funcoes/busca_dados_arma.php?nr_arma="+nr_arma+"&mostra_sigma="+mostra_sigma+"&id_adt_materia_tipo="+id_adt_materia_tipo;
		//alert(url);
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
		var st_arma = document.getElementById('st_arma').value;
		if(st_arma == '0')
		{
			//ARMA NAO ENCONTRADA
			$("#txt_nr_sigma").focus();
			
			if(mostra_sigma == 1)
			{
				$("#div_marca_arma").hide();
			}
			else			
			{
				carrega_combo_marca(); 
				$("#div_marca_arma").show();
			}	
			//$("#txt_nr_arma").prop("disabled", true);
			$("#btn_busca_dados_arma").prop("disabled", true);
		}
	}
	else
	{
		$("#txt_nr_arma").focus();
	}
}

function busca_dados_arma_enter(mostra_sigma)
{
	//alert('teste');
	
	$("#txt_nr_arma").keypress(function(e) {
      if(e.which == 13) {
        // enter pressed
        busca_dados_arma(mostra_sigma);
      }
    });
}

function exibe_oculta_marca_arma_form_transf_arma(mostra_sigma)
{
	if(mostra_sigma == 1)
	{
		var nr_sigma = document.getElementById('txt_nr_sigma').value;
		if(nr_sigma.length < 1)
    	{
    		//alert('campo vazio');
    		$("#div_marca_arma").hide();
    	}
    	else
    	{
    		carrega_combo_marca();
   			$("#div_marca_arma").show();	
   	   	}
    
    } //if mostra sigma = 1

    
}

function libera_combo_marca()
{
	//alert('liberou a marca'); 
	carrega_combo_marca();
   	$("#div_marca_arma").show();
}

function carrega_combo_marca()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_combo_marca').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_marca.php";
	//alert(url);
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibe_oculta_form_arma_trans()
{
	var nr_arma = document.getElementById('txt_nr_arma').value;
	if(nr_arma.length < 1)
    {
    	$("#div_dados_arma").hide();
    }
}

function carrega_combo_modelo_arma_form_transf()
{
	var id_marca = document.getElementById('cmb_marca').value;

	if(id_marca > 0)
	{
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			{
				document.getElementById('div_combo_modelo_form_transf').innerHTML = ajax.responseText;
			}
		}
	 	url = "adt/funcoes/carrega_combo_modelo_arma.php?id_marca="+id_marca;
		//alert(url);
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
		$("#div_modelo_arma").show();
		$("#div_btn_incluir_marca").hide();
		$("#cmb_modelo").focus();
	}
	else
	{
		$("#div_modelo_arma").hide();
		$("#div_btn_incluir_marca").show();	
	}
}



function exibe_combo_origem_form_transf()
{
	$("#div_btn_incluir_modelo").hide();
	$("#div_origem_arma").show();
	$("#cmb_arma_pais_origem").focus();
}

function exibe_combo_acabamento_form_transf()
{
	$("#div_acabamento_arma").show();
	$("#cmb_arma_acabamento").focus();	
}

function validacaoEmail() 
{
	email = document.getElementById('email_cedente').value;
	usuario = email.substring(0, email.indexOf("@"));
	dominio = email.substring(email.indexOf("@")+ 1, email.length);
	if ((usuario.length >=1) &&
	    (dominio.length >=3) && 
	    (usuario.search("@")==-1) && 
	    (dominio.search("@")==-1) &&
	    (usuario.search(" ")==-1) && 
	    (dominio.search(" ")==-1) &&
	    (dominio.search(".")!=-1) &&      
	    (dominio.indexOf(".") >=1)&& 
	    (dominio.lastIndexOf(".") < dominio.length - 1)) {
	//document.getElementById("msgemail").innerHTML="E-mail válido";
	return(true);
	}
	else{
	//document.getElementById("msgemail").innerHTML="<font color='red'>Email inválido </font>";
	//alert("E-mail invalido");
	return(false);
	}
}

function nao_aceita_espaco(e)
{
	var tecla=(window.event)?event.keyCode:e.which;   
	if(tecla == 32)
	{
		//alert('tecla espaço pressionada!');
		return false;
	}

}

function exibe_oculta_input_raia_arma()
{
	var id_alma = document.getElementById('cmb_alma').value;
	if(id_alma == 2)
	{
		//RAIADA, EXIBE CAMPOS
		$("#div_campos_raia_arma").show();
	}
	else
	{
		//LISA, OCULTA CAMPOS
		$("#div_campos_raia_arma").hide();
	}
}

function validarCPF(cpf) 
{  
	//validaDadosliberaBotaoSolicitaAcesso();
	//var divBtnSolicitaAcesso = document.getElementById('divBtnSolicitaAcesso');
	//var div_instrucoes = document.getElementById('div_instrucoes');
	//var cpf = document.getElementById('cpf').value;
	//var divCPF = document.getElementById('divCPF');
	cpf = cpf.replace(/[^\d]+/g,'');
	var cpfValido = false;
	if(cpf.length == 11)
	{
	    // Elimina CPFs invalidos conhecidos    
	    if (cpf.length != 11 || 
	        cpf == "00000000000" || 
	        cpf == "11111111111" || 
	        cpf == "22222222222" || 
	        cpf == "33333333333" || 
	        cpf == "44444444444" || 
	        cpf == "55555555555" || 
	        cpf == "66666666666" || 
	        cpf == "77777777777" || 
	        cpf == "88888888888" || 
	        cpf == "99999999999")
	    {
	        cpfValido = false;       
	    }
	    else
	    {
		    // Valida 1o digito 
		    add = 0;    
		    for (i=0; i < 9; i ++)       
		        add += parseInt(cpf.charAt(i)) * (10 - i);  
		        rev = 11 - (add % 11);  
		        if (rev == 10 || rev == 11)     
		            rev = 0;    
		        if (rev != parseInt(cpf.charAt(9)))     
		            cpfValido = false;       
		    // Valida 2o digito 
		    add = 0;    
		    for (i = 0; i < 10; i ++)        
		        add += parseInt(cpf.charAt(i)) * (11 - i);  
		    rev = 11 - (add % 11);  
		    if (rev == 10 || rev == 11) 
		        rev = 0;    
		    if (rev != parseInt(cpf.charAt(10)))
		    {
		    	cpfValido = false;
		    }
		    else
		    {
		    	cpfValido = true;	
		    }
		}
	    

	    if(cpfValido == true)
	    {
	    	
	    	return true;
	    }
	    else
	    {
	    	//document.getElementById('cpf').focus();
	    	return false;
	    }
	    
	}
}

function validarCNPJ(cnpj)
{
	var str = cnpj;
    str = str.replace('.','');
    str = str.replace('.','');
    str = str.replace('.','');
    str = str.replace('-','');
    str = str.replace('/','');
    cnpj = str;
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15)
        return false;
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1))
    {
        digitos_iguais = 0;
        break;
    }
    if (!digitos_iguais)
    {
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
    {
    	document.getElementById('cnpj').focus();
        return false;
    }
}

function carrega_combo_modelo()
{
	var id_marca = document.getElementById('cmb_marca').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_cmb_modelo').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_modelo.php?id_marca="+id_marca;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	$("#cmb_modelo").focus();
}

function carrega_combo_atividades_pj()
{
	$("#div_combo_atividades").show();
	$("#div_combo_pce").hide();
	$("#div_combo_tipo_pce").hide();
	$("#div_quantidade").hide();
	$("#div_btn_adicionar_atv_pce_pj").hide();

	var id_atividade_pj_tipo = document.getElementById('cmb_tipo_atividade').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_combo_atividades').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_atividade.php?id_atividade_pj_tipo="+id_atividade_pj_tipo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function carrega_combo_tipo_pce()
{
	//Verifica se a atividade selecionada exige informar o PCE
	verifica_atividade_possui_pce();
	var st_informa_pce = document.getElementById('txt_st_informa_pce').value;

	if(st_informa_pce == 1)
	{
		$("#div_combo_pce").hide();
		$("#div_quantidade").hide();
		$("#div_btn_adicionar_atv_pce_pj").hide();
		$("#div_combo_tipo_pce").show();
		var nm_atividade_selecionada = document.getElementById("cmb_atividade").options[document.getElementById("cmb_atividade").selectedIndex].text;
		
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			{
				document.getElementById('div_combo_tipo_pce').innerHTML = ajax.responseText;
			}
		}
	 	url = "adt/funcoes/carrega_combo_tipo_pce.php?nm_atividade_selecionada=" + nm_atividade_selecionada;
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
	}
	else
	{
		$("#div_combo_tipo_pce").hide();
		$("#div_combo_pce").hide();
		$("#div_quantidade").hide();
		$("#div_btn_adicionar_atv_pce_pj").show();
	}
}

function verifica_atividade_possui_pce()
{
	var id_adt_atividade_pj = document.getElementById('cmb_atividade').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_st_informa_pce').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/verifica_atividade_informa_pce.php?id_adt_atividade_pj="+id_adt_atividade_pj;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	
}

function carrega_combo_pce()
{
	$("#div_combo_pce").show();
	$("#div_quantidade").hide();
	var id_adt_pce_tipo = document.getElementById('cmb_tipo_pce').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_combo_pce').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/carrega_combo_pce.php?id_adt_pce_tipo="+id_adt_pce_tipo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function habilita_botao_adicionar_atv_pce_pj()
{
	//VERIFICA SE A QUANTIDADE DEVE SER INFORMADA
	var id_adt_atividade_pj = document.getElementById('cmb_atividade').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_st_informa_quantidade').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/verifica_atividade_informa_quantidade.php?id_adt_atividade_pj="+id_adt_atividade_pj;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	var st_atividade_informa_quantidade = $('#txt_st_informa_quantidade').val();

	if(st_atividade_informa_quantidade == 1)
	{
		exibe_unidade_quantidade_pce();
		$("#div_quantidade").show();
		$("#txt_qtd_max").focus();
	}
	else
	{
		$("#div_quantidade").hide();	
	}

	$("#div_btn_adicionar_atv_pce_pj").show();

}

function exibe_unidade_quantidade_pce()
{
	var id_pce = document.getElementById('cmb_pce').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById('div_unidade_pce').innerHTML = ajax.responseText;
		}
	}
 	url = "adt/funcoes/exibe_unidade_medida_pce_selecionado.php?id_pce="+id_pce;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function incluir_atividade_pce_selecionada()
{
	$("#div_info_atv_pce_pj").hide();
	$("#div_quadro_atv_pce_selecionadas").show();

	var st_informa_pce = document.getElementById('txt_st_informa_pce').value;

	var id_atividade_pj = document.getElementById('cmb_atividade').value;
	var nm_atividade = document.getElementById("cmb_atividade").options[document.getElementById("cmb_atividade").selectedIndex].text;
	
	var id_pce = 0;
	var nm_pce = "-";
	var qtd_max = 0;

	if(st_informa_pce == 1)
	{
		id_pce = document.getElementById('cmb_pce').value;
		nm_pce = document.getElementById("cmb_pce").options[document.getElementById("cmb_pce").selectedIndex].text;
		qtd_max = document.getElementById('txt_qtd_max').value;
	}
	
	
	var nr_ordem_atv_pce_incluidos = parseInt(document.getElementById('txt_nr_ordem_atv_pce_incluidos').value);
	
	if(qtd_max == '')
	{
		qtd_max = 0;
	}

	var qtd_max_tela;
	if(qtd_max == 0)
	{
		qtd_max_tela = '-';
	}
	else
	{
		qtd_max_tela = qtd_max;
	}

	var id_atv_pce_incluidos = document.getElementById('txt_id_atv_pce_incluidos').value;

	var linha_item_atv_pce_incluido;
	linha_item_atv_pce_incluido = "@";
	linha_item_atv_pce_incluido += nr_ordem_atv_pce_incluidos;
	linha_item_atv_pce_incluido += ",";
	linha_item_atv_pce_incluido += id_atividade_pj;
	linha_item_atv_pce_incluido += ",";
	linha_item_atv_pce_incluido += id_pce;
	linha_item_atv_pce_incluido += ",";
	linha_item_atv_pce_incluido += qtd_max;

	

	if (id_atv_pce_incluidos == '')
	{
		id_atv_pce_incluidos = linha_item_atv_pce_incluido;
	}
	else
	{
		id_atv_pce_incluidos += ";";
		id_atv_pce_incluidos += linha_item_atv_pce_incluido;
	}

	//INCLUI A ATIVIDADE SELECIONADA NO TXT HIDE
	document.getElementById('txt_id_atv_pce_incluidos').value = id_atv_pce_incluidos;

	var nm_div_linha_atv_incluida = 'div_linha_atv_';
	nm_div_linha_atv_incluida += nr_ordem_atv_pce_incluidos;
	
	var linha_atividade = "<div class='row' id='" + nm_div_linha_atv_incluida + "'>";
	linha_atividade += "<div class='col-md-5'>";
	linha_atividade += "<label id='lbl_produto' value='1'><i class='glyphicon glyphicon-list-alt'></i> " + nm_atividade + "</label>";
	linha_atividade += "</div>";
	linha_atividade += "";
	linha_atividade += "<div class='col-md-5'>";
	linha_atividade += "<label id='lbl_quantidade' value='1'>" + nm_pce + "</label>";
	linha_atividade += "</div>";
	linha_atividade += "";
	linha_atividade += "<div class='col-md-1'>";
	linha_atividade += "<label id='lbl_atividade' value='1'>" + qtd_max_tela + "</label>";
	linha_atividade += "</div>";
	linha_atividade += "";
	linha_atividade += "<div class='col-md-1' id='div_btn_remover_linha_" + nr_ordem_atv_pce_incluidos + "'>";
	linha_atividade += "<center><a href='javascript:excluir_item_atividade_pj(" + nr_ordem_atv_pce_incluidos + ");'><font color='red'><i class='glyphicon glyphicon-remove-sign'></i></font></a></center>";
	linha_atividade += "</div>";
	linha_atividade += "</div>";

	//INCLUI A ATIVIDADE SELECIONADA NA TELA
	$('#div_atividades_selecionadas').html($('#div_atividades_selecionadas').html() + linha_atividade);

	nr_ordem_atv_pce_incluidos += 1;
	document.getElementById('txt_nr_ordem_atv_pce_incluidos').value = nr_ordem_atv_pce_incluidos;

	document.getElementById('txt_qtd_max').value = "";
	$("#div_quantidade").hide();
	$("#div_combo_pce").hide();
	$("#div_combo_tipo_pce").hide();
	$("#div_btn_adicionar_atv_pce_pj").hide();
	document.getElementById("cmb_tipo_atividade").options[0].selected = "true";
	document.getElementById("cmb_atividade").options[0].selected = "true";
	$("#div_combo_tipo_atividade").hide();
	$("#div_combo_atividades").hide();

	
	$("#div_btn_novo_finalizar").show();
}

function excluir_item_atividade_pj(nr_ordem_atv_pce_incluidos)
{
	
	var id_atv_pce_incluidos = document.getElementById('txt_id_atv_pce_incluidos').value;
	var array_atv_pce_incluido = id_atv_pce_incluidos.split(';');
	var nova_linha_atv_pce_incluido;
	for (var x = 0; x < array_atv_pce_incluido.length; x++)
    {
    	linha_item_array_atv_pce_incluido = array_atv_pce_incluido[x];
    
    	if(!linha_item_array_atv_pce_incluido.match('@'+nr_ordem_atv_pce_incluidos))
    	{
    
    		if(nova_linha_atv_pce_incluido == null)
    		{
    			nova_linha_atv_pce_incluido = linha_item_array_atv_pce_incluido;
    		}
    		else
    		{
    			nova_linha_atv_pce_incluido += ";";
    			nova_linha_atv_pce_incluido += linha_item_array_atv_pce_incluido;
    		}
    	}
    }
    

    //Oculta a DIV da Linha Removida
    var nm_div_linha_atv_pce_removida = "#div_linha_atv_" + nr_ordem_atv_pce_incluidos;
    $(nm_div_linha_atv_pce_removida).hide();
    
    if(array_atv_pce_incluido.length > 1)
    {
    	document.getElementById('txt_id_atv_pce_incluidos').value = nova_linha_atv_pce_incluido;
    }
    else
    {
    	document.getElementById('txt_id_atv_pce_incluidos').value = "";
    	document.getElementById('txt_nr_ordem_atv_pce_incluidos').value = "1";
    	$("#div_quadro_atv_pce_selecionadas").hide();
    	$("#div_info_atv_pce_pj").show();
    	nova_inclusao_atv_pce_pj();
    }
}

function nova_inclusao_atv_pce_pj()
{
	$("#div_btn_novo_finalizar").hide();
	$("#div_combo_tipo_atividade").show();
	$("#cmb_tipo_atividade").focus();
}

function concluir_atividade_pce_selecionada()
{
	$("#div_btn_novo_finalizar").hide();
	$("#div_info_prosseguir_status").show();
	$('#btn_alterarEstadoProcesso').show();

	$('#div_acao_quadro_atividades_pce_pj').hide();


	var nr_ordem_atv_pce_incluidos = parseInt(document.getElementById('txt_nr_ordem_atv_pce_incluidos').value);

	for (x = 1; x < nr_ordem_atv_pce_incluidos; x++)
	{
		var nm_div_remover_linha = '#div_btn_remover_linha_' + x;
		$(nm_div_remover_linha).hide();
		//alert(nm_div_remover_linha);
	}

	$('#txt_id_adt_materia_informado').val('1');

		//$('#div_btn_remover_linha_1').hide();
	
}

function exibe_anexo_atv_pce_processo(id_processo)
{
	url = "adt/funcoes/exibe_anexo_cr_pj.php?id_processo=" + id_processo;
	window.open(url, '_blank');
}

function exibeComboFornecedorArma(mostra_fornecedor)
{
    var tipo_acervo = parseInt(document.getElementById('cmb_acervo').value); 

	if(tipo_acervo > 0)
	{	
		if (mostra_fornecedor == 1)
		{	
			$("#div_cmb_fornecedor").show();
		}
		else
		{
			$("#div_txt_nr_arma").show();
		}	
	}


}

function exibeCampoNotaFiscalArma(mostra_nf)
{
    var mostra_nf = document.getElementById('txt_mostra_nf').value; 
	if(mostra_nf == 1) 
	{
		$("#div_txt_nr_nota_fiscal").show();
		$("#txt_nr_nota_fiscal").focus();
	}
	else
	{
		exibeCampoNumeroArma(mostra_nf); 		 
	}
}

function exibeCampoNumeroArma(mostra_nf)
{
	//verifica se o nr da nota/siscomex foi digitado caso precise da NF
	var nr_nota_fiscal = $("#txt_nr_nota_fiscal").val();
	if(nr_nota_fiscal != '' || mostra_nf == 0)
	{
		$("#div_txt_nr_arma").show();		
	}
	else
	{
		$("#txt_nr_nota_fiscal").focus();
		$("#div_txt_nr_arma").hide();
		$("#div_cmb_origem").hide();
		$("#div_cmb_marca").hide();
	}
	
}

function exibeComboOrigemArma()
{
	//verifica se o nr Arma foi digitado
	var nr_arma = $("#txt_nr_arma").val();
	if(nr_arma != '')
	{
		$("#div_cmb_origem").show();
	}
	else
	{
		$("#txt_nr_arma").focus();
		$("#div_cmb_origem").hide();
		$("#div_cmb_marca").hide();
	}
}

function exibeComboMarcaArma()
{
	$("#div_cmb_marca").show();
	$("#cmb_marca").focus();
}


function valida_campos_form_adt_aqs_libera_botao_confirmar()
{

	$("#div_btn_confirma_dados").hide();
	//Pega os valores do form
	var cmb_acervo = document.getElementById('cmb_acervo').value;
	var cmb_fornecedor = document.getElementById('cmb_fornecedor').value;
	var txt_nr_nota_fiscal = document.getElementById('txt_nr_nota_fiscal').value;
	var txt_nr_arma = document.getElementById('txt_nr_arma').value;
	var cmb_origem = document.getElementById('cmb_origem').value;
	var cmb_marca = document.getElementById('cmb_marca').value;
	//var cmb_modelo = document.getElementById('cmb_modelo').value;

//TEMPORARIAMENTE SUPRIMIDO

	//if(cmb_acervo != 0 && cmb_fornecedor == 0)
	//{
	//	$("#cmb_fornecedor").focus();
	//}

	//else if(txt_nr_nota_fiscal == "" && mostra_nf == 1)
	//{
	//	$("#txt_nr_nota_fiscal").focus();
	//}

	if(txt_nr_arma == "")
	{
		$("#txt_nr_arma").focus();
	}
	else if(cmb_origem == 0)
	{
		$("#cmb_origem").focus();
	}
	else if(cmb_marca == 0)
	{
		$("#cmb_marca").focus();
	}
	else
	{
		$("#div_info_dados_arma_aqs").hide();
		$("#div_btn_confirma_dados").show();
	}

}

function confirmar_dados_form_adt_aqs()
{
	$('#cmb_acervo').attr('disabled', 'disabled');
	$('#cmb_fornecedor').attr('disabled', 'disabled');
	$('#txt_nr_nota_fiscal').attr('disabled', 'disabled');
	$('#txt_nr_arma').attr('disabled', 'disabled');
	$('#cmb_origem').attr('disabled', 'disabled');
	$('#cmb_marca').attr('disabled', 'disabled');
	$('#cmb_modelo').attr('disabled', 'disabled');
	$('#cmb_acabamento').attr('disabled', 'disabled');
	$("#div_btn_confirma_dados").hide();
	$("#div_info_dados_arma_aqs").hide();
	$("#div_info_prosseguir_status").show();
	$('#btn_alterarEstadoProcesso').show();
	$('#txt_id_adt_materia_informado').val('1');
}

function confirmar_dados_form_adt_transf()
{
	$('#cpf_cedente').attr('disabled', 'disabled');
	$('#rdb_cpf').attr('disabled', 'disabled');
	$('#rdb_cnpj').attr('disabled', 'disabled');
	$('#cnpj_cedente').attr('disabled', 'disabled');
	$('#cmb_acervo').attr('disabled', 'disabled');
	$('#txt_nr_arma').attr('disabled', 'disabled');
	$('#txt_nr_sigma').attr('disabled', 'disabled');
	$('#cmb_marca').attr('disabled', 'disabled');
	$('#cmb_modelo').attr('disabled', 'disabled');
	$('#cmb_arma_pais_origem').attr('disabled', 'disabled');
	$('#cmb_arma_acabamento').attr('disabled', 'disabled');
    $('#txt_especie').attr('disabled', 'disabled');
    $('#txt_calibre').attr('disabled', 'disabled');
	$("#div_btn_confirma_dados").hide();
	$("#div_info_prosseguir_status").show();
	$('#btn_alterarEstadoProcesso').show();
	$('#txt_id_adt_materia_informado').val('1');
}


function valida_campos_form_adt_transf_arma_libera_botao_confirmar()
{
	$("#div_btn_confirma_dados").show();
}

function imprimirResultadoPesquisa()
{
	$("#div_resultado_consulta").printElement();
}

function distribuir_processo(id_processo, cd_protocolo_processo, login_responsavel)
{
	var id_processo_status_novo = 14;
	var obs_processo_andamento = "";
	var nm_div_btn_distribuir = "div_btn_distribuir_" + id_processo;
	var nm_div_status = "div_status_" + id_processo;
	var nm_div_dt_gerenciamento = "div_dt_gerenciamento_" + id_processo;
	var nm_div_login_responsavel = "div_login_responsavel_" + id_processo;

	var data_hora = new Date();
	var dia     = data_hora.getDate();           // 1-31
	var mes     = data_hora.getMonth();          // 0-11 (zero=janeiro)
	var ano4    = data_hora.getFullYear();       // 4 dígitos
	var hora    = data_hora.getHours();          // 0-23
	var min     = data_hora.getMinutes();        // 0-59
	
	// Formata a data e a hora (note o mês + 1)
	var str_data_hora = dia + '/' + (mes+1) + '/' + ano4 + ' ' + hora + ':' + min;
	

	// Mostra o resultado
	//alert('Hoje é ' + str_data + ' às ' + str_hora);

	var ajax = AjaxF();	
	
	ajax.onreadystatechange = function(){
	if(ajax.readyState == 4)
		{
			document.getElementById(nm_div_btn_distribuir).innerHTML = ajax.responseText;
		}
	}


 	url = "processo/avancaFluxoProcesso.php?id_processo="+id_processo+"&id_processo_status="+id_processo_status_novo+"&obs_processo_andamento="+obs_processo_andamento;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$('#'+nm_div_btn_distribuir).html("<font color='green'><center><b><i class='glyphicon glyphicon-ok'></i></b></center></font>");
	$('#'+nm_div_status).html("<center><b><font color='green'>DISTRIBUÍDO</font></b></center>");
	$('#'+nm_div_dt_gerenciamento).html("<center>" + str_data_hora + "</center>");
	$('#'+nm_div_login_responsavel).html("<center>" + login_responsavel + "</center>");

}
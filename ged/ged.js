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
	var count_form = new Number();
	
	var nm_obj_form_Upload = new String();

	var nm_txt_cnpf_cnpj_upload = new String();

	var nm_id_barra_upload = new String();

	var nm_div_result_upload = new String();

	var nm_id_arquivo = new String;
	
	count_form = 1;

	nm_obj_form_Upload = "formUpload" + count_form;

	nm_txt_cnpf_cnpj_upload = "txt_cpf_cnpj_upload" + count_form;

	nm_id_barra_upload = "pbar" + count_form;

	nm_id_percentual_completo = "id_percent_complete" + count_form;

	nm_div_result_upload = "div_resultUpload" + count_form;

	nm_id_arquivo = "arquivo" + count_form;

	//$('#div_info_fila_processamento').show();

	//BOTAO LIMPA JANELA PROCESSAMENTO
	$('#btn_limpa_janela_processamento').click(function () {
		$('#div_cpf_cnpj_upload').html("");
		$('#div_barra_upload').html("");
		$('#div_resul_upload').html("");
		$('#div_msg_err').html("");
		//$('#div_info_fila_processamento').show();
		count_form = 1;
		if(document.getElementById("rdb_cpf").checked==true)
		{
			$("#txt_cpf").focus();
		}
		else
		{
			$("#txt_cnpj" ).focus();	
		}
	});

	//Cria o primeiro formulário do Input Upload
	$('#div_form_upload').html("<form id='formUpload1' action='' method='post' enctype='multipart/form-data'><input id=" + nm_id_arquivo + " name=" + nm_id_arquivo + " size='30' accept='application/pdf' type='file' /> <!-- application/pdf --></form>");
	
	//MOSTRA O BOTÃO IMPORTAR APÓS SELECIONAR O ARQUIVO
	$('#'+nm_id_arquivo).change(function (event) 
	{
		if(document.getElementById("rdb_cpf").checked==true)
		{
			cpf_cnpj_preenchido = 0;
			cpf_cnpj = document.getElementById('txt_cpf').value;
			if(cpf_cnpj.length == 14)
			{
				cpf_cnpj_preenchido = 1;
			}
		}
		else
		{
			cpf_cnpj = document.getElementById('txt_cnpj').value;	
			if(cpf_cnpj.length == 18)
			{
				cpf_cnpj_preenchido = 1;
			}
		}

		if($('#'+nm_id_arquivo).val() != "" && cpf_cnpj_preenchido == 1)
			$('#div_btn_importar').show();
		else
			$('#div_btn_importar').hide();
	});

	//OCULTA BOTÃO IMPORTAR CASO O CPF TENHA SIDO APAGADO
	// $('#txt_cpf').change(function (event) 
	// {
	// 	alert('teste');
	// 	if(document.getElementById("rdb_cpf").checked==true)
	// 	{
	// 		cpf_cnpj = document.getElementById('txt_cpf').value;
	// 		if(cpf_cnpj.length < 14)
	// 		{
	// 			$('#div_btn_importar').hide();
	// 		}
	// 	}
	// });


	$('#btn_importar_documento').click(function () {

		//$('#div_info_fila_processamento').hide();
		
		if($('#'+nm_id_arquivo).val() == "")
		{
			alert("arquivo nao selecionado!");
		}


		$('#div_painel_upload').animate({
	 	scrollTop: 1000
	 	}, 1000, 'linear');
    	
    	var cpf_cnpj = null
    	var cpf_cnpj_preenchido = 0;
    	var cpf_cnpj_valido = false;
    	var sg_documento_tipo = document.getElementById('txt_sg_documento_tipo_selecionado').value;
    	
    	if(document.getElementById("rdb_cpf").checked==true)
		{
			cpf_cnpj = document.getElementById('txt_cpf').value;
			if(cpf_cnpj.length == 14)
			{
				cpf_cnpj_preenchido = 1;
			}
		}
		else
		{
			cpf_cnpj = document.getElementById('txt_cnpj').value;	
			if(cpf_cnpj.length == 18)
			{
				cpf_cnpj_preenchido = 1;
			}
		}

		//Valida se o CPF ou CNPJ estao preenchidos
		if(cpf_cnpj_preenchido > 0)
		{

			//Cria a linha com o CPF ou CNPJ na div CPF_CNPJ
			if(count_form == 1)
			{
				$('#div_cpf_cnpj_upload').html("<div class='row'><div class='col-md-12'><center><font color='green'><b><h5><label id='" + nm_txt_cnpf_cnpj_upload + "'></label></h5></b></font></center></div></div>");	
			}
			else
			{
				$('#div_cpf_cnpj_upload').html($('#div_cpf_cnpj_upload').html() + "<div class='row'><div class='col-md-12'><center><font color='green'><b><h5><label id='" + nm_txt_cnpf_cnpj_upload + "'></label></h5></b></font></center></div></div>");
			}
			
			//Seta o valor
			$("#" + nm_txt_cnpf_cnpj_upload).text(cpf_cnpj);

			//Nomes temporário antes do incremeto
			nm_div_result_upload_temp = nm_div_result_upload;
			nm_id_barra_upload_temp = nm_id_barra_upload;
			nm_id_percentual_completo_temp = nm_id_percentual_completo;
			nm_obj_form_Upload_temp = nm_obj_form_Upload;
			nm_id_aquivo_temp = nm_id_arquivo;

			//Cria a linha com a Barra de Progresso na div Barra de Progresso
			if(count_form == 1)
			{
				$('#div_barra_upload').html("<div class='row'><div class='col-md-12'><h5><div class='progress'><div id='" + nm_id_barra_upload_temp + "' class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%'><span id='" + nm_id_percentual_completo_temp + "'>0%</span></div></div></div></div></h5>");
			}
			else
			{
				$('#div_barra_upload').html($('#div_barra_upload').html() + "<div class='row'><div class='col-md-12'><div class='progress'><div id='" + nm_id_barra_upload_temp + "' class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%'><span id='" + nm_id_percentual_completo_temp + "'>0%</span></div></div></div></div>");
			}


			//Cria a linha que irá receber o resultado do Upload
			if(count_form == 1)
			{
				$('#div_resul_upload').html("<div class='row' style='min-height: 40px;'><div class='col-md-12'><div id='" + nm_div_result_upload_temp + "'></div></div></div>");
			}
			else
			{
				$('#div_resul_upload').html($('#div_resul_upload').html() + "<div class='row' style='min-height: 40px;'><div class='col-md-12'><div id='" + nm_div_result_upload_temp + "'></div></div></div>");
			}

			cpf_cnpj = cpf_cnpj.replace(/[^\d]+/g,'');

			//document.getElementById('div_barra_progresso').style.display = 'block';
			
			$("#"+nm_div_result_upload_temp).html("<font color='blue'><h5><span class='glyphicon glyphicon-time' aria-hidden='true'></span></h5></font>");

			
			//UPLOAD
			upload_arquivo(nm_obj_form_Upload_temp, nm_id_barra_upload_temp, nm_id_percentual_completo_temp, nm_div_result_upload_temp, cpf_cnpj, sg_documento_tipo, nm_id_aquivo_temp);

			
			
			count_form += 1;

			nm_obj_form_Upload = "formUpload" + count_form;
			nm_txt_cnpf_cnpj_upload = "txt_cpf_cnpj_upload" + count_form;
			nm_id_barra_upload = "pbar" + count_form;
			nm_id_percentual_completo = "id_percent_complete" + count_form;
			nm_div_result_upload = "div_resultUpload" + count_form;
			nm_id_arquivo = "arquivo" + count_form;
			

			$('#div_form_upload').html("<form id='" + nm_obj_form_Upload + "' action='' method='post' enctype='multipart/form-data'><input id=" + nm_id_arquivo + " name=" + nm_id_arquivo + " size='30' accept='application/pdf' type='file' /> <!-- application/pdf --></form>");


			//MOSTRA O BOTÃO IMPORTAR APÓS SELECIONAR O ARQUIVO
			$('#'+nm_id_arquivo).change(function (event) 
			{
				if($('#'+nm_id_arquivo).val() != "")
					$('#div_btn_importar').show();
				else
					$('#div_btn_importar').hide();
			});
		}
		//Caso o CPF ou CNPJ nao esteja preenchido
		else
		{
			if(document.getElementById("rdb_cpf").checked==true)
			{
				$("#div_txt_cpf").html("<div class='form-group has-error'><label class='control-label' for='txt_cpf'>Preenchimento obrigatório</label><input type='text' class='form-control' id='txt_cpf' name='txt_cpf' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pf\")'></div>");
				$("#txt_cpf").mask("999.999.999-99");
				$("#txt_cpf").focus();
			}
			else
			{
				$("#div_txt_cnpj").html("<div class='form-group has-error'><label class='control-label' for='txt_cnpj'>Preenchimento obrigatório</label><input type='text' class='form-control' id='txt_cnpj' name='txt_cnpj' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pj\")'></div>");
				$("#txt_cnpj").mask("99.999.999/9999-99");
				$("#txt_cnpj" ).focus();	
			}
		}
    });

});

function upload_arquivo(nm_obj_form_Upload_temp, nm_id_barra_upload_temp, nm_id_percentual_completo_temp, nm_div_result_upload_temp, cpf_cnpj, sg_documento_tipo, nm_id_aquivo_temp)
{
	//alert('chamou upload');

	// $id_carteira = $_GET['id_carteira'];
	// $id_servico = $_GET['id_servico'];
	// $id_documento_tipo = split (';',$_GET['id_documento_tipo']);
	// $id_vl_indexadores = split (';',$_GET['id_vl_indexadores']);
	// $dados_arquivo = split (',',$_GET['dados_arquivo']);
	// $tipo_pessoa = $_GET['tipo_pessoa'];

	var tipo_pessoa = null;
	if(document.getElementById("rdb_cpf").checked==true)
	{
		tipo_pessoa = "PF";
	}
	else
	{
		tipo_pessoa = "PJ";
	}
	var id_carteira = document.getElementById('cmb_carteira').value;
	var id_servico = document.getElementById('cmb_servico').value;
	var id_documento_tipo = document.getElementById('cmb_tipo_documento').value;
	var id_indexadores = null;
	var id_vl_indexadores = null;
	if ($('#txt_id_indexadores')[0]) 
	{
		id_indexadores = document.getElementById('txt_id_indexadores').value;
		id_indexadores = id_indexadores.split('|');

		for(var i = 0; i < id_indexadores.length; i++)
		{
			//alert(id_indexadores[i]);
			nm_txt_valor_indexador = "txt_";
			nm_txt_valor_indexador += id_indexadores[i];
			
			if(id_vl_indexadores == null)
			{
				id_vl_indexadores = id_indexadores[i];
				id_vl_indexadores += "vl_index";
				id_vl_indexadores += document.getElementById(nm_txt_valor_indexador).value;	
			}
			else
			{
				id_vl_indexadores += ";";
				id_vl_indexadores += id_indexadores[i];
				id_vl_indexadores += "vl_index";
				id_vl_indexadores += document.getElementById(nm_txt_valor_indexador).value;	
			}
		}
		
		var RegExp = /["|']/g;
 		id_vl_indexadores=id_vl_indexadores.replace(RegExp,"");
	}

	$("#"+nm_obj_form_Upload_temp).ajaxForm({


			uploadProgress: function(event, position, total, percenteComplete)
            {	
            	//alert(nm_id_percentual_completo_temp);
            	$("#"+nm_id_percentual_completo_temp).text(percenteComplete + "%");
            	$("#"+nm_id_barra_upload_temp).css({'width': percenteComplete +'%'});
            },

            success: function(data){

            	if(!data.match(/FALHA/))
		        {
		        	
		        	//alert(data);

		        	//Insere dados do documento importado
		        	//insereDadosDocumentoImportado(data, nm_div_result_upload_temp);

		        	//Muda a Barra de Progresso para 100% e na cor verde
		        	$("#"+nm_id_barra_upload_temp).html("<div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 100%'><span id='percentualUpload'>100%</span></div>");
		        	
		        	//Insere o ícone de OK e o Link para Visualizar o documento
		        	$("#"+nm_div_result_upload_temp).html(data);

		        	
		        	
		        }
		        else
		        {
		        	//alert('FALHA NA IMPORTAÇÃO DO ARQUIVO!');
		        	$("#"+nm_id_barra_upload_temp).html("<div class='progress-bar progress-bar-danger progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 100%'><span id='percentualUpload'>0%</span></div>");
		        	$("#"+nm_div_result_upload_temp).html("<font color='red'><h5><span class='glyphicon glyphicon-thumbs-down' aria-hidden='true'></span></h5></font>");
		        	$("#div_msg_err").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>FALHA NO PROCESSAMENTO</strong> <font color='red'>" + data + "</font></div>");


		        }
            },

            error: function(){

            },
            processData: false,
            contentType: false,
            type: 'POST',
            url: 'ged/funcoes/carrega_arquivo_digital.php?cpf_cnpj='+cpf_cnpj+'&sg_documento_tipo='+sg_documento_tipo+'&id_nm_arquivo='+nm_id_aquivo_temp+'&id_carteira='+id_carteira+'&id_servico='+id_servico+'&id_documento_tipo='+id_documento_tipo+'&id_vl_indexadores='+id_vl_indexadores+'&tipo_pessoa='+tipo_pessoa, // Url do lado server que vai receber o arquivo
            resetForm: true,
		}).submit();

		//Limpa os campos do formulario para nova inclusão
    	if(document.getElementById("rdb_cpf").checked==true)
		{
			$( "#txt_cpf" ).val("");
			$( "#txt_cpf" ).focus();
		}
		else
		{
			$( "#txt_cnpj" ).val("");
			$( "#txt_cnpj" ).focus();	
		}
		$('#div_btn_importar').hide();
		limpaCamposIndexadores();
		
}


function limpaCamposIndexadores()
{
	if ($('#txt_id_indexadores')[0]) 
	{
		id_indexadores = document.getElementById('txt_id_indexadores').value;
		id_indexadores = id_indexadores.split('|');

		for(var i = 0; i < id_indexadores.length; i++)
		{
			nm_txt_valor_indexador = "txt_";
			nm_txt_valor_indexador += id_indexadores[i];
			
			$('#'+nm_txt_valor_indexador).val("");
		}
	}
}

function exibeDetalhesProcesso(cd_protocolo_processo)
{
	var ajax = AjaxF();	
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_documentos_digitalizados').innerHTML = ajax.responseText;
	 	}
	 }

	url = "protocolo/exibeDetalhesProcesso.php?cd_protocolo="+cd_protocolo_processo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibeDashBoard()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_dashboard_ged').innerHTML = ajax.responseText;
	 	}
	}

	url = "ged/funcoes/exibe_dashboard_ged.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	exibeTotalDocumentosDigitalizados();
}

function exibeTotalDocumentosDigitalizados()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_total_documentos_digitalizados').innerHTML = ajax.responseText;
	 	}
	}

	url = "ged/funcoes/exibe_total_documentos_digitalizados.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

}

function InicializaPagina()
{

	$(document).keypress(function(e) {
	  if(e.which == 13) {
	    // enter pressed
	    buscaDocumento();
	  }
	});
	exibeTotalDocumentosDigitalizados();
}

function carregaComboIndexadores()
{
	document.getElementById('div_combo_indexadores').style.display = 'none';
	var tipo_documento = document.getElementById('cmb_tipo_documento').value;
	
	if(tipo_documento != 0)
	{
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('div_combo_indexadores').innerHTML = ajax.responseText;
		 	}
		}
		url = "ged/funcoes/carrega_combo_indexadores.php?tipo_documento="+tipo_documento;

		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();

		document.getElementById('div_combo_indexadores').style.display = 'block';
		
		carregaCampoParametro();
		
	}
}

function carregaCampoParametro()
{
	document.getElementById('div_documentos_digitalizados').style.display = 'none';
	var indexador = document.getElementById('cmb_indexador').value;
	var id_indexador_formato = indexador.split(",");


	if(id_indexador_formato[1] == 1)
	{		
	   	document.getElementById('div_campo_parametro_numero').style.display = 'none';
	   	document.getElementById('div_campo_parametro_data').style.display = 'none';
	   	document.getElementById('div_campo_parametro_rg').style.display = 'none';
	   	document.getElementById('div_campo_parametro_cpf').style.display = 'none';
	   	document.getElementById('div_campo_parametro_cnpj').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'block';
		$( "#txt_valor_texto" ).focus();
	}
	else if(id_indexador_formato[1] == 2)
	{
	   	document.getElementById('div_campo_parametro_data').style.display = 'none';
	   	document.getElementById('div_campo_parametro_rg').style.display = 'none';
	   	document.getElementById('div_campo_parametro_cpf').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'none';
		document.getElementById('div_campo_parametro_cnpj').style.display = 'none';
		document.getElementById('div_campo_parametro_numero').style.display = 'block';
		$( "#txt_valor_numero" ).focus();
	}
	else if(id_indexador_formato[1] == 3)
	{
		document.getElementById('div_campo_parametro_numero').style.display = 'none';
	   	document.getElementById('div_campo_parametro_rg').style.display = 'none';
	   	document.getElementById('div_campo_parametro_cpf').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'none';
		document.getElementById('div_campo_parametro_cnpj').style.display = 'none';
		document.getElementById('div_campo_parametro_data').style.display = 'block';
		$('#txt_valor_data').datetimepicker({format: 'DD/MM/YYYY'});
		$( "#txt_valor_data" ).focus();
	}
	else if(id_indexador_formato[1] == 4)
	{
		document.getElementById('div_campo_parametro_numero').style.display = 'none';
	   	document.getElementById('div_campo_parametro_cpf').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'none';
		document.getElementById('div_campo_parametro_data').style.display = 'none';
		document.getElementById('div_campo_parametro_cnpj').style.display = 'none';
		document.getElementById('div_campo_parametro_rg').style.display = 'block';
		$( "#txt_valor_rg" ).focus();
	}
	else if(id_indexador_formato[1] == 5)
	{
		document.getElementById('div_campo_parametro_numero').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'none';
		document.getElementById('div_campo_parametro_data').style.display = 'none';
		document.getElementById('div_campo_parametro_rg').style.display = 'none';
		document.getElementById('div_campo_parametro_cnpj').style.display = 'none';
		document.getElementById('div_campo_parametro_cpf').style.display = 'block';
		$("#txt_valor_cpf").mask("999.999.999-99");
		$( "#txt_valor_cpf" ).focus();
	}

	else if(id_indexador_formato[1] == 6)
	{
		document.getElementById('div_campo_parametro_numero').style.display = 'none';
		document.getElementById('div_campo_parametro_texto').style.display = 'none';
		document.getElementById('div_campo_parametro_data').style.display = 'none';
		document.getElementById('div_campo_parametro_rg').style.display = 'none';
		document.getElementById('div_campo_parametro_cpf').style.display = 'none';
		document.getElementById('div_campo_parametro_cnpj').style.display = 'block';
		$("#txt_valor_cnpj").mask("99.999.999/9999-99");
		$( "#txt_valor_cnpj" ).focus();
	}

}


function buscaDocumento()
{
	

	document.getElementById('div_documentos_digitalizados').style.display = 'block';
	var tipo_documento = document.getElementById('cmb_tipo_documento').value;
	var indexador = document.getElementById('cmb_indexador').value;
	var valor = null;
	var id_indexador_formato = indexador.split(",");
	if(id_indexador_formato[1] == 1)
	{
		valor = document.getElementById('txt_valor_texto').value
	}
	else if(id_indexador_formato[1] == 2)
	{
		valor = document.getElementById('txt_valor_numero').value
	}
	else if(id_indexador_formato[1] == 3)
	{
		valor = document.getElementById('txt_valor_data').value
	}
	else if(id_indexador_formato[1] == 4)
	{
		valor = document.getElementById('txt_valor_rg').value
	}
	else if(id_indexador_formato[1] == 5)
	{
		valor = document.getElementById('txt_valor_cpf').value
	}
	else if(id_indexador_formato[1] == 6)
	{
		valor = document.getElementById('txt_valor_cnpj').value
	}

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_documentos_digitalizados').innerHTML = ajax.responseText;
	 	}
	}


	url = "ged/funcoes/exibe_lista_documento_digitalizado.php?tipo_documento="+tipo_documento+"&indexador="+id_indexador_formato[0]+"&formato_indexador="+id_indexador_formato[1]+"&valor="+valor;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$('#tb_docs').dataTable
    ( 
    	{
            "language": {"url": "js/dataTablesPT.js"},
            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]]
            
        }

    );
}

function carregaComboServico()
{
	document.getElementById('div_tipo_pessoa').style.display = 'block';

	var id_carteira = document.getElementById('cmb_carteira').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_combo_servico').innerHTML = ajax.responseText;
	 	}
	}
	url = "ged/funcoes/carrega_combo_servico.php?id_carteira="+id_carteira;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

}

function carregaIndexadoresDocumento()
{
	document.getElementById('div_indexadores_documento').style.display = 'block';
	document.getElementById('div_upload_documento').style.display = 'block';
	document.getElementById('div_botao_upload').style.display = 'block';
	document.getElementById('txt_sg_documento_tipo_selecionado').value = null;
	var id_sg_documento_tipo = document.getElementById('cmb_tipo_documento').value;
	var id_sg_documento_tipo = id_sg_documento_tipo.split(';');

	document.getElementById('txt_sg_documento_tipo_selecionado').value = id_sg_documento_tipo[1];

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_indexadores_documento').innerHTML = ajax.responseText;
	 	}
	}
	url = "ged/funcoes/carrega_indexadores_documento.php?id_documento_tipo="+id_sg_documento_tipo[0];

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}



function alteraTipoPF_PJ(el,estado) 
{
	var display = document.getElementById(el).style.display;

	document.getElementById('div_cpf').style.display = 'none';
	document.getElementById('div_cnpj').style.display = 'none';
	

	document.getElementById(el).style.display = 'block';
	if(estado == "none")
	    document.getElementById(el).style.display = 'block';
	else
	    document.getElementById(el).style.display = 'none';

	if(el == 'div_cpf')
	{
		$("#txt_cpf").mask("999.999.999-99");
		document.getElementById('txt_cpf').focus();
	}
	else
	{
		$("#txt_cnpj").mask("99.999.999/9999-99");
		document.getElementById('txt_cnpj').focus();
	}
	//document.getElementById('div_carteira').style.display = 'block';
	
}

function mostraPreViewDocumento(files) {
    if (files && files[0]) {
        var reader = new FileReader();
        var tamanhoPermitido = 1024 * 100;
        var arq = files[0];
        tamanho = arq.size;
		
		reader.onload = function (e) {
		$('#thumbnail_doc').attr('src', e.target.result);
		
		}
		reader.readAsDataURL(files[0]);
    }
}

function mostraComboTipoDocumento(tipo_pessoa)
{
	if(tipo_pessoa == 'pf')
    {
    	var cpf = document.getElementById('txt_cpf').value;
    	cpf = cpf.replace(/[^\d]+/g,'');
    	//valida CPF
    	if(cpf.length == 11)
    	{
	    	cpfValido = validarCPF(cpf);
	    	if(cpfValido)
	    	{
	    		$("#div_txt_cpf").html("<input type='text' class='form-control input-sm' id='txt_cpf' name='txt_cpf' value='" + cpf + "' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pf\")'>");
	    		$("#txt_cpf").mask("999.999.999-99");
	    		document.getElementById('div_combo_documento_tipo').style.display = 'block';
	    		$( "#cmb_tipo_documento" ).focus();
	    	}
	    	else
	    	{
	    		//CFP INVÁLIDO
	    		$("#div_txt_cpf").html("<div class='form-group has-error'><label class='control-label' for='txt_cpf'>CPF Inválido</label><input type='text' class='form-control input-sm' id='txt_cpf' name='txt_cpf' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pf\")'></div>");
				$("#txt_cpf").mask("999.999.999-99");
				$("#txt_cpf").focus();
	    	}
	    }
    }
    else if(tipo_pessoa == 'pj')
    {
    	var cnpj = document.getElementById('txt_cnpj').value;
    	cnpj = cnpj.replace(/[^\d]+/g,'');
    	//valida CNPJ
    	if(cnpj.length == 14)
    	{
    		cnpjValido = validarCNPJ(cnpj);
	    	if(cnpjValido)
	    	{
	    		$("#div_txt_cnpj").html("<input type='text' class='form-control input-sm' id='txt_cnpj' name='txt_cnpj' value='" + cnpj + "' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pj\")'>");
	    		$("#txt_cnpj").mask("99.999.999/9999-99");
				$("#txt_cnpj").focus();
	    		document.getElementById('div_combo_documento_tipo').style.display = 'block';
	    		$( "#cmb_tipo_documento" ).focus();
	    	}
	    	else
	    	{
	    		//CNPJ INVALIDO
	    		$("#div_txt_cnpj").html("<div class='form-group has-error'><label class='control-label' for='txt_cnpj'>CNPJ Inválido</label><input type='text' class='form-control' id='txt_cnpj' name='txt_cnpj' onkeypress='return SomenteNumero(event)' onkeyup='mostraComboTipoDocumento(\"pj\")'></div>");
	    		$("#txt_cnpj").mask("99.999.999/9999-99");
				$("#txt_cnpj").focus();
	    	}
	    }
    }
}


function validarCPF(cpf) 
{  
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
	    	return false;
	    }
	    
	}
}

function validarCNPJ(str)
{
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
        return false;
    }
}






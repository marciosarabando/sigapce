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

function MudarestadoComboProcurador(el,estado) 
{
	var display = document.getElementById(el).style.display;

	//document.getElementById(el).style.display = 'block';
	if(estado == "none")
	{
	    document.getElementById(el).style.display = 'none';
	    document.getElementById('div_btn_gerarProtocolo').style.display = 'block';
	    document.getElementById('div_obs_protocolo').style.display = 'block';
	    document.getElementById('div_procurador').style.display = 'none';
	    //document.getElementById('div_gru').style.display = 'block';
		$('html, body').animate({
		scrollTop: 1000
		}, 1000, 'linear');
	    verificaNecessidadeInclusaoGRU();
	}
	else
	{
	    document.getElementById(el).style.display = 'block';
	    document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	    document.getElementById('div_obs_protocolo').style.display = 'none';
	    document.getElementById('div_procurador').style.display = 'block';
	    document.getElementById('div_gru').style.display = 'none';
	    carregaComboProcurador();
	    $('html, body').animate({
		scrollTop: 1000
		}, 1000, 'linear');
	    
	}
}

function verificaNecessidadeInclusaoGRU()
{
	//document.getElementById('div_gru').style.display = 'block';
	id_servicos_selecionados = $('#id_servico_selecionado').val(); 
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
		{
		 		document.getElementById('div_st_gru').innerHTML = ajax.responseText;
		}
	}
	
	ajax.open("GET", "protocolo/gru/verifica_necessidade_inclusao_gru.php?id_servicos_selecionados="+ id_servicos_selecionados , false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	st_inclusao_gru = $('#div_st_gru').html();
	if(st_inclusao_gru == "1")
	{
		document.getElementById('div_gru').style.display = 'block';
		//document.getElementById('div_pergunta_procurador').style.display = 'none';
		document.getElementById('div_obs_protocolo').style.display = 'none';
		document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	}
	else if (st_inclusao_gru != "1" && document.getElementById('div_gru').style.display == 'block')
	{
		//liberaBotaoProtocolar();
		//document.getElementById('div_pergunta_procurador').style.display = 'none';
		document.getElementById('div_obs_protocolo').style.display = 'none';
		document.getElementById('div_btn_gerarProtocolo').style.display = 'none';

	}
	else
	{
		document.getElementById('div_pergunta_procurador').style.display = 'block';
		document.getElementById('div_obs_protocolo').style.display = 'block';
		document.getElementById('div_btn_gerarProtocolo').style.display = 'block';
	}

}


$(document).ready(function()
{
  var count_form_gru = new Number();
  count_form_gru = 1;

  var nm_txt_referencia = new String();
  var nm_txt_competencia = new String();
  var nm_txt_valor = new String();
  var nm_txt_autenticacao = new String();
  var nm_div_status_gru = new String();
  var nm_div_linha_gru = new String();

  $('#btn_nova_gru').click(function () 
  {
  	nm_txt_referencia = "txt_referencia" + count_form_gru;
    nm_txt_competencia = "txt_competencia" + count_form_gru;
    nm_txt_valor = "txt_valor" + count_form_gru;
    nm_txt_autenticacao = "txt_autenticacao" + count_form_gru;
    nm_div_status_gru = "div_status_gru" + count_form_gru;
    nm_div_linha_gru = "div_linha_gru" + count_form_gru;
    id_interessado = $('#id_interessado').val(); 

    if(document.getElementById('ic_procuradorSim').checked == true)
	{
		id_procurador = $('#id_procurador').val(); 
	}
	else
	{
		id_procurador = 0;
	}
    
    // alert(id_interessado);
    // alert(id_procurador);

  	if(count_form_gru == 1)
  	{
  		nm_div_linha_gru_2 = "div_linha_gru2";
  		
  		$('#div_campos_gru').html("<div id='"+nm_div_linha_gru+"'></div>");

  		$('#'+nm_div_linha_gru).html("<div class='row'><div class='col-md-2'><input type='text' onblur='validaPreenchimentoNrReferencia(\""+nm_txt_referencia+"\")' onKeyup='aceitaNrReferencia(\""+nm_txt_referencia+"\",\""+nm_txt_competencia+"\")' class='form-control input-sm' id='"+nm_txt_referencia+"' placeholder='Número de Referência' maxlength='6' onkeypress='return SomenteNumero(event)'/></div><div class='col-md-2'><input type='text' onfocus='validaPreenchimentoNrReferencia(\""+nm_txt_referencia+"\")' onblur='' onKeyup='aceitaCompetencia(\""+nm_txt_competencia+"\", \""+nm_txt_referencia+"\", \""+nm_txt_valor+"\")' class='form-control input-sm' id='"+nm_txt_competencia+"' placeholder='Mês/Ano Competência' maxlength='7' disabled/></div><div class='col-md-2'><input type='text' onfocus='validaPreenchimentoCompetencia(\""+nm_txt_competencia+"\")' onKeyup='validaPreenchimentoValorGRU(\""+nm_txt_valor+"\", \""+nm_txt_autenticacao+"\")' class='form-control input-sm' id='"+nm_txt_valor+"' placeholder='Valor' disabled/></div><div class='col-md-4'><input type='text' onfocus='validaPrenchimentoReferenciaCompetenciaValorGRU(\""+nm_txt_referencia+"\", \""+nm_txt_competencia+"\", \""+nm_txt_valor+"\")' onKeyup='validaGRU(\""+nm_txt_autenticacao+"\", \""+nm_div_status_gru+"\", \""+nm_txt_referencia+"\", \""+nm_txt_competencia+"\", \""+nm_txt_valor+"\", \""+nm_div_linha_gru+"\", \""+id_interessado+"\", \""+id_procurador+"\")' class='upper form-control input-sm' id='"+nm_txt_autenticacao+"' placeholder='Número de Autenticação' maxlength='21' disabled/></div><div class='col-md-2' id='"+nm_div_status_gru+"'></div></div>");

		$('#div_campos_gru').html($('#div_campos_gru').html()+"<br><div id='"+nm_div_linha_gru_2+"'></div>");

  	}
  	else
  	{
  		count_linha_gru = count_form_gru + 1;
  		
  		nm_div_linha_gru_posterior = "div_linha_gru" + count_linha_gru;
  		
  		$('#'+nm_div_linha_gru).html("<div class='row'><div class='col-md-2'><input type='text' onblur='validaPreenchimentoNrReferencia(\""+nm_txt_referencia+"\")' onKeyup='aceitaNrReferencia(\""+nm_txt_referencia+"\",\""+nm_txt_competencia+"\")' class='form-control input-sm' id='"+nm_txt_referencia+"' placeholder='Número de Referência' maxlength='6' onkeypress='return SomenteNumero(event)'/></div><div class='col-md-2'><input type='text' onfocus='validaPreenchimentoNrReferencia(\""+nm_txt_referencia+"\")' onblur='' onKeyup='aceitaCompetencia(\""+nm_txt_competencia+"\", \""+nm_txt_referencia+"\", \""+nm_txt_valor+"\")' class='form-control input-sm' id='"+nm_txt_competencia+"' placeholder='Mês/Ano Competência'maxlength='7' disabled/></div><div class='col-md-2'><input type='text' onfocus='validaPreenchimentoCompetencia(\""+nm_txt_competencia+"\")' onKeyup='validaPreenchimentoValorGRU(\""+nm_txt_valor+"\", \""+nm_txt_autenticacao+"\")' class='form-control input-sm' id='"+nm_txt_valor+"' placeholder='Valor' disabled/></div><div class='col-md-4'><input type='text' onfocus='validaPrenchimentoReferenciaCompetenciaValorGRU(\""+nm_txt_referencia+"\", \""+nm_txt_competencia+"\", \""+nm_txt_valor+"\")' onKeyup='validaGRU(\""+nm_txt_autenticacao+"\", \""+nm_div_status_gru+"\", \""+nm_txt_referencia+"\", \""+nm_txt_competencia+"\", \""+nm_txt_valor+"\", \""+nm_div_linha_gru+"\", \""+id_interessado+"\", \""+id_procurador+"\")' class='upper form-control input-sm' id='"+nm_txt_autenticacao+"' placeholder='Número de Autenticação'maxlength='21' disabled/></div><div class='col-md-2' id='"+nm_div_status_gru+"'></div></div><br>");

  		$('#div_campos_gru').append("<div id='"+nm_div_linha_gru_posterior+"'></div>");

  	}
  	
  	$("#"+nm_txt_competencia).mask("99/9999");
    $("#"+nm_txt_autenticacao).mask("*.***.***.***.***.***");
    $("#"+nm_txt_valor).maskMoney({
     prefix: "R$ ",
     decimal: ",",
     thousands: "."
 	});
	
	count_form_gru = count_form_gru + 1;
	$('#'+nm_txt_referencia).focus();
	$('#btn_nova_gru').prop("disabled", true);
	
  });

});

function aceitaNrReferencia(nm_txt_referencia, nm_txt_competencia)
{
	//alert(nm_txt_referencia);
	nr_referencia = document.getElementById(nm_txt_referencia).value;
	if(nr_referencia.length >= 5)
	{
		//$("#"+nm_txt_competencia).focus();
		$("#"+nm_txt_competencia).prop("disabled", false);
	}
	else
	{
		$("#"+nm_txt_competencia).prop("disabled", true);	
	}
}


function validaPreenchimentoNrReferencia(nm_txt_referencia)
{
	nr_referencia = document.getElementById(nm_txt_referencia).value;
	if(nr_referencia.length == '')
	{
		$("#"+nm_txt_referencia).focus();
	}
}

function aceitaCompetencia(nm_txt_competencia, nm_txt_referencia, nm_txt_valor)
{
	nr_referencia = document.getElementById(nm_txt_referencia).value;
	if(nr_referencia.length < 5)
	{
		$("#"+nm_txt_referencia).focus();
	}

	competencia = document.getElementById(nm_txt_competencia).value;
	mes_ano = competencia.split('/');

	competencia = competencia.replace(/[^\d]+/g,'');

	if(competencia.length == 6)
	{

		if(mes_ano[0] > '12' || mes_ano[1] < '2000')
		{
			alert('Data de Competência Inválida!');
			document.getElementById(nm_txt_competencia).value = '';
			$("#"+nm_txt_valor).prop("disabled", true);
			$("#"+nm_txt_competencia).focus();
		}
		else
		{
			$("#"+nm_txt_valor).prop("disabled", false);
			$("#"+nm_txt_valor).focus();
		}

	}
}

function validaPreenchimentoValorGRU(nm_txt_valor, nm_txt_autenticacao)
{

	valor_gru = document.getElementById(nm_txt_valor).value;
	valor_gru = valor_gru.substring(3);
	if(valor_gru == '0,00')
	{
		$("#"+nm_txt_autenticacao).prop("disabled", true);
		$("#"+nm_txt_valor).focus();
	}
	else
	{
		$("#"+nm_txt_autenticacao).prop("disabled", false);
	}
}

function validaPreenchimentoCompetencia(nm_txt_competencia)
{
	competencia = document.getElementById(nm_txt_competencia).value;
	competencia = competencia.replace(/[^\d]+/g,'');

	if(competencia.length < 6)
	{
		$("#"+nm_txt_competencia).focus();
	}
}

function validaPrenchimentoReferenciaCompetenciaValorGRU(nm_txt_referencia, nm_txt_competencia, nm_txt_valor)
{
	nr_referencia = document.getElementById(nm_txt_referencia).value;
	competencia = document.getElementById(nm_txt_competencia).value;
	valor_gru = document.getElementById(nm_txt_valor).value;
	
	if(nr_referencia == '')
	{
		$("#"+nm_txt_referencia).focus();
	}
	else if (competencia == '')
	{
		$("#"+nm_txt_competencia).focus();	
	}
}

function validaGRU(nm_txt_autenticacao, nm_div_status_gru, nm_txt_referencia, nm_txt_competencia, nm_txt_valor, nm_div_linha_gru, id_interessado, id_procurador)
{
	nr_autenticacao = document.getElementById(nm_txt_autenticacao).value;
	nr_autenticacao = nr_autenticacao.replace(/_/g,'');

	//alert(nr_autenticacao.length);

	if(nr_autenticacao.length > 20)
	{
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4)
			{
			 		document.getElementById(nm_div_status_gru).innerHTML = ajax.responseText;
			}
		}
		
		ajax.open("GET", "protocolo/gru/valida_gru.php?nr_autenticacao="+nr_autenticacao+"&id_interessado="+id_interessado+"&id_procurador="+id_procurador, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
	}

	status_gru = $('#'+nm_div_status_gru).html();

	if(status_gru.match("ok"))
	{
		$('#'+nm_div_status_gru).html("<h5><font color='green'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></font> &nbsp; <font color='blue'><span onclick='editaLinhaGRU(\""+nm_div_linha_gru+"\",\""+nm_txt_referencia+"\",\""+nm_txt_competencia+"\",\""+nm_txt_valor+"\",\""+nm_txt_autenticacao+"\",\""+nm_div_status_gru+"\")' class='glyphicon glyphicon-pencil' aria-hidden='true'></span></font> &nbsp; <font color='red'><span onclick='removeLinhaGRU(\""+nm_div_linha_gru+"\")' class='glyphicon glyphicon-remove' aria-hidden='true'></span></font></h5>");
		$('#btn_nova_gru').prop("disabled", false);
		$('#'+nm_txt_referencia).prop("disabled", true);
		$('#'+nm_txt_competencia).prop("disabled", true);
		$('#'+nm_txt_valor).prop("disabled", true);
		$('#'+nm_txt_autenticacao).prop("disabled", true);

		linha = nm_div_linha_gru.substring(13);
		if($('#nm_div_gru_incluida').val() == "")
		{
			$('#nm_div_gru_incluida').val(linha);
		}
		else
		{
			$('#nm_div_gru_incluida').val($('#nm_div_gru_incluida').val() + "," +linha);	
		}
		
		//document.getElementById('div_pergunta_procurador').style.display = 'block';
		document.getElementById('div_btn_gerarProtocolo').style.display = 'block';
		document.getElementById('div_obs_protocolo').style.display = 'block';
	}
	else if(!status_gru.match("ok") && nr_autenticacao.length > 20)
	{
		$('#'+nm_div_status_gru).html("<h6><font color='red'><span onclick='removeLinhaGRU(\""+nm_div_linha_gru+"\")' class='glyphicon glyphicon-remove' aria-hidden='true'></span></font> &nbsp;" + $('#'+nm_div_status_gru).html());
		document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
		document.getElementById('div_obs_protocolo').style.display = 'none';
	}
}



function removeLinhaGRU(nm_div_linha_gru)
{
	$('#'+nm_div_linha_gru).html("");
	linha = nm_div_linha_gru.substring(13);
	linhas_gru_incluidas = $('#nm_div_gru_incluida').val();
	linhas_gru_incluidas = linhas_gru_incluidas.replace(linha,'');
	linhas_gru_incluidas = linhas_gru_incluidas.replace(',,',',');
	if(linhas_gru_incluidas.substring(0,1) == ",")
	{
		linhas_gru_incluidas = linhas_gru_incluidas.substring(1);
	}
	$('#nm_div_gru_incluida').val(linhas_gru_incluidas);
	if(linhas_gru_incluidas == "")
	{
		document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
		document.getElementById('div_obs_protocolo').style.display = 'none';
	}
	else
	{
		document.getElementById('div_btn_gerarProtocolo').style.display = 'block';
		document.getElementById('div_obs_protocolo').style.display = 'block';	
	}
	$('#btn_nova_gru').prop("disabled", false);
}

function editaLinhaGRU(nm_div_linha_gru,nm_txt_referencia,nm_txt_competencia,nm_txt_valor,nm_txt_autenticacao,nm_div_status_gru)
{
	$('#'+nm_txt_referencia).prop("disabled", false);
	$('#'+nm_txt_competencia).prop("disabled", false);
	$('#'+nm_txt_valor).prop("disabled", false);
	$('#'+nm_txt_autenticacao).prop("disabled", false);
	$('#'+nm_txt_autenticacao).val("");
	$('#'+nm_div_status_gru).html("");
	$('#'+nm_txt_referencia).focus();
	document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	document.getElementById('div_obs_protocolo').style.display = 'none';
}


function limpaItensSelecionados()
{
	document.getElementById('id_servico_selecionado').value = '';
	document.getElementById('div_servicos_incluidos').innerHTML = '';
	$('#div_gru').hide();
}

// Função que faz as requisição Ajax ao arquivo PHP
function buscaInteressadoCPF()
{
	 desabilitaBotaoIncluirServico();
	 limpaItensSelecionados();
	 document.getElementById('cpf_invalido').style.display = 'none';
	 document.getElementById('inclusaoSolicitacao').style.display = 'none';
	 document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	 document.getElementById('div_obs_protocolo').style.display = 'none';
	 document.getElementById('div_pergunta_procurador').style.display = 'none';
	 document.getElementById('div_procurador').style.display = 'none';
	 document.getElementById('ic_procuradorNao').checked = false;
	 document.getElementById('ic_procuradorSim').checked = false;
	 
	 if(validarCPF())
	 {
	 	 document.getElementById('busca_cpf').focus();
	 	 document.getElementById('dadosInteressado').style.display = 'block';
		 var ajax = AjaxF();	
		
		 ajax.onreadystatechange = function(){
		 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('dadosInteressado').innerHTML = ajax.responseText;
		 	}
		 }
		
		 // Variável com os dados que serão enviados ao PHP
		 var dados = "cpf_cnpj="+document.getElementById('cpf').value;
		 ajax.open("GET", "protocolo/buscaDadosInteressado.php?"+dados, false);
		 ajax.setRequestHeader("Content-Type", "text/html");
		 ajax.send();
	 }
	 else
	 {	
	 	document.getElementById('cpf_invalido').style.display = 'block';
	 	document.getElementById('dadosInteressado').style.display = 'none';
	 }

	 jQuery(function($){
		   $("#cpf_interessado").mask("999.999.999-99");
	  });
	 $('html, body').animate({
	 scrollTop: 1000
	 }, 1000, 'linear');
}

function buscaInteressadoCNPJ()
{
	 desabilitaBotaoIncluirServico();
	 limpaItensSelecionados();
	 document.getElementById('cnpj_invalido').style.display = 'none';
	 document.getElementById('inclusaoSolicitacao').style.display = 'none';
	 document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	 document.getElementById('div_obs_protocolo').style.display = 'none';
	 document.getElementById('div_pergunta_procurador').style.display = 'none';
	 document.getElementById('div_procurador').style.display = 'none';
	 document.getElementById('ic_procuradorNao').checked = false;
	 document.getElementById('ic_procuradorSim').checked = false;
	 

	 if(validarCNPJ())
	 {
	 	 document.getElementById('busca_cnpj').focus();
	 	 document.getElementById('dadosInteressado').style.display = 'block';
		 var ajax = AjaxF();
		 ajax.onreadystatechange = function(){
		 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('dadosInteressado').innerHTML = ajax.responseText;
		 	}
		 }
		 // Variável com os dados que serão enviados ao PHP
		 var dados = "cpf_cnpj="+document.getElementById('cnpj').value;
		
		 ajax.open("GET", "protocolo/buscaDadosInteressado.php?"+dados, false);
		 ajax.setRequestHeader("Content-Type", "text/html");
		 ajax.send();
	 }
	 else
	 {	
	 	document.getElementById('cnpj_invalido').style.display = 'block';
	 	document.getElementById('dadosInteressado').style.display = 'none';
	 }

	 jQuery(function($){
		   $("#cnpj_interessado").mask("99.999.999/9999-99");
	  });
	 $('html, body').animate({
	 scrollTop: 1000
	 }, 1000, 'linear');
}

function confirmaCadastroInteressado()
{
	valido = 0;
	contato = document.getElementById('nr_tel_interessado').value;
	email = document.getElementById('email_interessado').value;

	if(contato == "")
	{
		alert('Atenção, O CONTATO do Interessado deve ser informado.');
	}
	else if(email == "")
	{
		alert('Atenção, O E-MAIL do Interessado deve ser informado.');
	}
	else
	{
		valido = 1;
	}

	if(valido == 1)
	{
		document.getElementById('div_btn_confirmaCadastro').style.display = 'none';
		document.getElementById('inclusaoSolicitacao').style.display = 'block';
		document.getElementById('mensagem_cadastro_atualizado').style.display = 'block';
		document.getElementById('div_comboServicos').style.display = 'none';
		document.getElementById('div_msg_interessado_localizado').style.display = 'none';
		carregaComboCarteira();

		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
			if(ajax.readyState == 4)
			{
				document.getElementById('dadosInteressado').innerHTML = ajax.responseText;
			}
		}
		//Verifica se o campo está aberto para edicao e chama a funcao para Atualizar o Cadastro
		if(document.getElementById('nm_interessado').disabled == false)
		{
			gravarDadosInteressado('atualizar');
		}
	}

}


function gravarDadosInteressado(acao)
{
	var valido = 0;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('dadosInteressado').innerHTML = ajax.responseText;
	 	}
	}

	var tipo_interessado = document.getElementById('cmb_tipoInteressado').value;
	var cpf = null;
	var cnpj = null;
	var nome = document.getElementById('nm_interessado').value;
	var cidade = document.getElementById('cmb_cidade').value;
	var cr = document.getElementById('cr_interessado').value;
	var tr = null;
	var contato = document.getElementById('nr_tel_interessado').value;
	var	email = document.getElementById('email_interessado').value;
	var url = null;

	if(contato == "")
	{
		alert('Atenção, O CONTATO do Interessado deve ser informado.');
	}
	else if(email == "")
	{
		alert('Atenção, O E-MAIL do Interessado deve ser informado.');
	}
	else
	{
		valido = 1;
	}

	if(valido == 1)
	{

		if(tipo_interessado == 'PJ')
		{
			cnpj = document.getElementById('cnpj_interessado').value;
			tr = document.getElementById('tr_interessado').value;
			url = "protocolo/gravarDadosInteressado.php?cpf_cnpj="+cnpj+"&sg_tipo_interessado="+tipo_interessado+"&nome="+nome+"&cidade="+cidade+"&cr="+cr+"&tr="+tr+"&contato="+contato+"&email="+email+"&acao="+acao;
		}
		else
		{
			cpf = document.getElementById('cpf_interessado').value;
			url = "protocolo/gravarDadosInteressado.php?cpf_cnpj="+cpf+"&sg_tipo_interessado="+tipo_interessado+"&nome="+nome+"&cidade="+cidade+"&cr="+cr+"&contato="+contato+"&email="+email+"&acao="+acao;	
		}
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();

		if(tipo_interessado == 'PJ')
		{
			buscaInteressadoCNPJ();
		}
		else
		{
			buscaInteressadoCPF();
		}
	}
}


function carregaComboCarteira()
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_comboCarteira').innerHTML = ajax.responseText;
	 	}
	 }
	
	 ajax.open("GET", "protocolo/carregaComboCarteira.php?", false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
}

function carregaComboServico()
{
	document.getElementById('div_comboServicos').style.display = 'block';
	desabilitaBotaoIncluirServico();
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_comboServicos').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 var dados = "id_carteira="+document.getElementById('cmb_carteira').value+"&sg_tipo_interessado="+document.getElementById('cmb_tipoInteressado').value;

	 ajax.open("GET", "protocolo/carregaComboServico.php?"+dados, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
}

function servicoSelecionado()
{
	document.getElementById('div_btn_incluirservico').style.display = 'block';
}

function desabilitaBotaoIncluirServico()
{
	document.getElementById('div_btn_incluirservico').style.display = 'none';
}

function incluirServico()
{
	document.getElementById('mensagem_cadastro_atualizado').style.display = 'none';
	document.getElementById('cmb_carteira').disabled = true;
	document.getElementById('div_pergunta_procurador').style.display = 'block';
	
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_servicos_incluidos').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP

	 var itemSelecionado = document.getElementById('cmb_servico').value;
	 var dados = document.getElementById('id_servico_selecionado').value;


	 if(dados == '')
	 {
		dados = document.getElementById('cmb_servico').value;
		document.getElementById('id_servico_selecionado').value = dados;
	 }
	 else
	 {
	 	if(dados.match(itemSelecionado))
	 	{
  			alert('Item de Serviço já Incluido na solicitação');
		}
		else
		{
		 	dados = document.getElementById('id_servico_selecionado').value + "," +document.getElementById('cmb_servico').value;
			document.getElementById('id_servico_selecionado').value = dados;
		}
	 }

	 var itens = document.getElementById('id_servico_selecionado').value;

	 ajax.open("GET", "protocolo/incluirServicoSelecionado.php?id_servico="+itens, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();

	 //verificaNecessidadeInclusaoGRU();
}

function excluirItemServico(id_servico)
{
	var dados = document.getElementById('id_servico_selecionado').value;
	var dados1 = dados.split(',');
	var result = null;
	for (var i in dados1) {
		if(dados1[i] != id_servico)
	    {
	    	if(result == null)
	    		result = dados1[i];
	    	else
	    		result = result + ',' + dados1[i];
	    }
	   
	}
	document.getElementById('id_servico_selecionado').value = result;
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_servicos_incluidos').innerHTML = ajax.responseText;
	 	}
	 }
	 var itens = document.getElementById('id_servico_selecionado').value;

	 ajax.open("GET", "protocolo/incluirServicoSelecionado.php?id_servico="+itens, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();

	 if (itens.length < 1)
	 {
	 	document.getElementById('div_pergunta_procurador').style.display = 'none';
	 	document.getElementById('div_procurador').style.display = 'none';
	 	document.getElementById('div_gru').style.display = 'none';
	 	document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	 	document.getElementById('div_obs_protocolo').style.display = 'none';
	 	document.getElementById('cmb_carteira').disabled = false;
	 	document.getElementById('ic_procuradorNao').checked = false;
		document.getElementById('ic_procuradorSim').checked = false;
	 }
}

function carregaInformacoesProtocoloModal()
{

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_janelaConfirmacaoProtocolo').innerHTML = ajax.responseText;
	 	}
	}

	var interessado = document.getElementById('nm_interessado').value;

	var cpf_cnpj = null; 
	var tipo_doc_interessado = null;
	var ic_procurador = null;
	var nm_procurador = null;

	if(document.getElementById('rdb_cpf').checked == true)
	{
		cpf_cnpj = document.getElementById('cpf_interessado').value;
		tipo_doc_interessado = 'PF';
	}
	else
	{
		cpf_cnpj = document.getElementById('cnpj_interessado').value;
		tipo_doc_interessado = 'PJ';
	}

	if(document.getElementById('ic_procuradorSim').checked == true)
	{
		ic_procurador = 'SIM';
		nm_procurador = document.getElementById('nm_procurador').value;
	}
	else
	{
		ic_procurador = 'NAO';
	}
	
	
	var e = document.getElementById("cmb_carteira");
	var carteira = e.options[e.selectedIndex].text;
	
	var itensServico = document.getElementById('id_servico_selecionado').value;

	var url = null;
	if(tipo_doc_interessado == 'PF')
	{
		url = "protocolo/carregaInformacoesConfirmacaoProtocoloModal.php?tipo_doc_interessado=PF&cpf_cnpj="+cpf_cnpj+"&ic_procurador="+ic_procurador+"&nm_procurador="+nm_procurador+"&interessado="+interessado+"&carteira="+carteira+"&id_servico="+itensServico;
	}
	else
	{
		url = "protocolo/carregaInformacoesConfirmacaoProtocoloModal.php?tipo_doc_interessado=PJ&cpf_cnpj="+cpf_cnpj+"&ic_procurador="+ic_procurador+"&nm_procurador="+nm_procurador+"&interessado="+interessado+"&carteira="+carteira+"&id_servico="+itensServico;
	}

 	ajax.open("GET", url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();


}



function carregaComboProcurador()
{
	//document.getElementById('div_combo_procurador').style.display = 'block';
	//desabilitaBotaoIncluirServico();
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_combo_procurador').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 //var dados = "id_carteira="+document.getElementById('cmb_carteira').value+"&sg_tipo_interessado="+document.getElementById('sg_tipo_interessado').value;

	 ajax.open("GET", "protocolo/carregaComboProcurador.php?", false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
}


function mostraDadosProcurador()
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_combo_procurador').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 var id_procurador = document.getElementById('cmb_procurador').value;

	 url = "protocolo/buscaDadosProcurador.php?id_procurador="+id_procurador

     //alert(url);

	 ajax.open("GET", url, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
	 jQuery(function($){
		   $("#nr_tel_procurador").mask("(99) 99999-9999");
	  });
	 document.getElementById('nm_procurador').disabled = true;
	 document.getElementById('nr_tel_procurador').disabled = true;
	 document.getElementById('email_procurador').disabled = true;
	 $('html, body').animate({
	 scrollTop: 1000
	 }, 1000, 'linear');
	 
}

function criarCamposProcuradorInserir()
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_combo_procurador').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 var dados = "id_procurador=0"

	 ajax.open("GET", "protocolo/buscaDadosProcurador.php?"+dados, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
	 jQuery(function($){
		   $("#nr_tel_procurador").mask("(99) 99999-9999");
	  });
}

function liberaCamposProcurador()
{
	document.getElementById('nm_procurador').disabled = false;
	document.getElementById('nr_tel_procurador').disabled = false;
	document.getElementById('email_procurador').disabled = false;
	document.getElementById("nm_procurador").focus();
}

function travaCamposProcurador()
{
	document.getElementById('nm_procurador').disabled = true;
	document.getElementById('nr_tel_procurador').disabled = true;
	document.getElementById('email_procurador').disabled = true;
}

function escolheOutroProcurador()
{
	document.getElementById('div_btn_gerarProtocolo').style.display = 'none';
	document.getElementById('div_obs_protocolo').style.display = 'none';
	document.getElementById('div_procurador').style.display = 'block';
	carregaComboProcurador();

}

function gravarDadosProcurador(acao)
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_dados_procurador').innerHTML = ajax.responseText;
	 	}
	}

	var nome = null;
	var telefone = null;
	var email = null;


	if(document.getElementById('nm_procurador').disabled == false)
	{
		nome = document.getElementById('nm_procurador').value;
		telefone = document.getElementById('nr_tel_procurador').value;
		email = document.getElementById('email_procurador').value;

		if(nome != "")
		{

			url = "protocolo/gravarDadosProcurador.php?nm_procurador="+nome+"&telefone="+telefone+"&email="+email+"&acao="+acao;
			//alert(url);
			ajax.open("GET",url, false);
			ajax.setRequestHeader("Content-Type", "text/html");
			ajax.send();

			travaCamposProcurador();
			//liberaBotaoProtocolar();
			//document.getElementById('div_gru').style.display = 'block';
			ocultaDivBotoesProcurador();
			//ocultaDivPerguntaProcurador();
			verificaNecessidadeInclusaoGRU();
			//alert('teste1');
		}
		else
		{
			alert('O nome do procurador deve ser informado!');
		}
	}
	else
	{
		//liberaBotaoProtocolar();
		//document.getElementById('div_gru').style.display = 'block';
		ocultaDivBotoesProcurador();
		//ocultaDivPerguntaProcurador();
		verificaNecessidadeInclusaoGRU();
		//alert('teste2');
	}
}

function liberaBotaoProtocolar()
{
	document.getElementById('div_btn_gerarProtocolo').style.display = 'block';
	document.getElementById('div_obs_protocolo').style.display = 'block';
	//document.getElementById('txt_observacao').focus();
	$('html, body').animate({
	scrollTop: 1000
	}, 1000, 'linear');
}

function ocultaDivBotoesProcurador()
{
	document.getElementById('div_btn_confirmaCadastroProcurador').style.display = 'none';
}

function ocultaDivPerguntaProcurador()
{
	document.getElementById('div_pergunta_procurador').style.display = 'none';
}

function liberaCamposInteressadoAlteracao()
{
	document.getElementById('div_msg_interessado_localizado').style.display = 'none';

	document.getElementById('nm_interessado').disabled = false;	
	var tipoInteressado = document.getElementById('cmb_tipoInteressado').value;
	if(tipoInteressado == 'PJ')
	{
		//document.getElementById('cnpj_interessado').disabled = false;
		document.getElementById('cr_interessado').disabled = false;
		document.getElementById('tr_interessado').disabled = false;
	}
	else
	{
		//document.getElementById('cpf_interessado').disabled = false;
		document.getElementById('cmb_tipoInteressado').disabled = false;
		document.getElementById('cr_interessado').disabled = false;
	}
	document.getElementById('cmb_cidade').disabled = false;
	document.getElementById('nr_tel_interessado').disabled = false;
	document.getElementById('email_interessado').disabled = false;

	document.getElementById('btn_alterarDadosInteressado').style.display = 'none';
	document.getElementById("cmb_tipoInteressado").focus();
}

function obtemGRUs()
{
	var linhas_gru_incluidas = $('#nm_div_gru_incluida').val();
	var array_linhas_gru_incluidas = linhas_gru_incluidas.split(',');
	var array_valores_gru_inserida = new Array();

	 for (i=0; i < array_linhas_gru_incluidas.length; i ++)
	 {
	 	nm_txt_referencia = "txt_referencia" + array_linhas_gru_incluidas[i];
	 	nm_txt_competencia = "txt_competencia" + array_linhas_gru_incluidas[i];
	 	nm_txt_valor = "txt_valor" + array_linhas_gru_incluidas[i];
	 	nm_txt_autenticacao = "txt_autenticacao" + array_linhas_gru_incluidas[i];

	 	vl_txt_referencia = $('#'+nm_txt_referencia).val();
	 	vl_txt_competencia = $('#'+nm_txt_competencia).val();
	 	vl_txt_valor = $('#'+nm_txt_valor).val();
	 	vl_txt_autenticacao = $('#'+nm_txt_autenticacao).val();

	 	vl_txt_valor = vl_txt_valor.substring(3);
	 	vl_txt_valor = vl_txt_valor.replace(/[\.-]/g, "");
	 	vl_txt_valor = vl_txt_valor.replace(',','.');

	 	vl_txt_autenticacao = vl_txt_autenticacao.replace(/[\.-]/g, "");
	 	vl_txt_autenticacao = vl_txt_autenticacao.toUpperCase();

	 	array_valores_gru_inserida[i] = vl_txt_referencia + ";" + vl_txt_competencia + ";" + vl_txt_valor + ";" + vl_txt_autenticacao;

	 }


	 for (x=0; x < array_valores_gru_inserida.length; x ++)
	 {
	 	if(x == 0)
	 		$('#valores_grus_inseridas').val(array_valores_gru_inserida[x]);
	 	else
	 		$('#valores_grus_inseridas').val($('#valores_grus_inseridas').val() + '-' + array_valores_gru_inserida[x]);
	 }

	 grus_processo = $('#valores_grus_inseridas').val();

	 //alert(grus_processo);
	 return grus_processo;


}

function gerarProtocolo()
{
	var id_interessado = document.getElementById('id_interessado').value;
	var id_servicos_selecionados = document.getElementById('id_servico_selecionado').value;
	var id_carteira = document.getElementById('cmb_carteira').value;
	var txt_observacao = document.getElementById('txt_observacao').value;
	var nm_procurador = null;
	var grus_processo = null;

	st_inclusao_gru = $('#div_st_gru').html();
	if(st_inclusao_gru == "1")
	{
		grus_processo = obtemGRUs();
	}
	else
	{
		grus_processo = "0";
	}


	if(document.getElementById('ic_procuradorSim').checked == true)
	{
		//id_procurador = document.getElementById('id_procurador').value;
		nm_procurador = document.getElementById('nm_procurador').value;
	}
	else
	{
		//id_procurador = null;
	}
	

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_painel').innerHTML = ajax.responseText;
	 	}
	}

	url = "protocolo/gerarProtocolo.php?id_interessado="+id_interessado+"&nm_procurador="+nm_procurador+"&id_servicos_selecionados="+id_servicos_selecionados+"&id_carteira="+id_carteira+"&txt_observacao="+txt_observacao+"&grus_processo="+grus_processo;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	//$('html, body').animate({
	//scrollTop: 1000
	//}, 1000, 'linear');
}

function imprimirProtocolo()
{
	//var conteudo = document.getElementById('div_corpo_painel').innerHTML;
	//tela_impressao = window.open('about:blank');
	//tela_impressao.document.write(conteudo);
	//tela_impressao.window.print();
	//tela_impressao.window.close();
	$("#div_corpo_painel").printElement();

	//$("#div_corpo_painel").click(function(){
	//			$('#toPrint').printElement();
	//	   });
}

function validarCPF() 
{  
	//validaDadosliberaBotaoSolicitaAcesso();
	//var divBtnSolicitaAcesso = document.getElementById('divBtnSolicitaAcesso');
	//var div_instrucoes = document.getElementById('div_instrucoes');
	var cpf = document.getElementById('cpf').value;
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
	    	document.getElementById('cpf').focus();
	    	return false;
	    }
	    
	}
}

function validarCNPJ()
{
	var str = document.getElementById('cnpj').value;
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


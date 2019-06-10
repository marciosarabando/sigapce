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

function exibePainelParametrosUnidade(unidade_login_mil_sfpc)
{
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_painel_parametros_atendimento_unidade').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_painel_parametros.php?id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	
	if(unidade_login_mil_sfpc != id_unidade)
	{
		document.getElementById("cmb_inicio_exp_manha").disabled = true;
		document.getElementById("cmb_fim_exp_manha").disabled = true;
		document.getElementById("cmb_inicio_exp_tarde").disabled = true;
		document.getElementById("cmb_fim_exp_tarde").disabled = true;
		document.getElementById("cmb_duracao_atendimento").disabled = true;
		document.getElementById("cmb_qtd_atendente").disabled = true;
		document.getElementById("qt_max_agendamento_data").disabled = true;
		document.getElementById("chk_exp_manha").disabled = true;
		document.getElementById("chk_exp_tarde").disabled = true;
	}
	else
	{
		document.getElementById("cmb_inicio_exp_manha").disabled = false;
		document.getElementById("cmb_fim_exp_manha").disabled = false;
		document.getElementById("cmb_inicio_exp_tarde").disabled = false;
		document.getElementById("cmb_fim_exp_tarde").disabled = false;
		document.getElementById("cmb_duracao_atendimento").disabled = false;
		document.getElementById("cmb_qtd_atendente").disabled = false;
		document.getElementById("qt_max_agendamento_data").disabled = false;
		document.getElementById("chk_exp_manha").disabled = false;
		document.getElementById("chk_exp_tarde").disabled = false;

	}

}


function carregaCalendario(unidade_login_mil_sfpc)
{
	
	exibePainelParametrosUnidade(unidade_login_mil_sfpc);

	document.getElementById('div_horarios').innerHTML = "";
	$(document).ready(function() {
    var initialLangCode = 'pt-br';

    $('#calendar').fullCalendar('destroy');
   
    // page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
        // put your options and callbacks here

        lang: initialLangCode,
        editable: true,
        selectable : true,
        selectHelper : true,
        businessHours : true,

        select: function (start, end, allDay) 
        {
          start = $.fullCalendar.formatRange(start, start, 'YYYY-MM-DD');
          datasel = new Date(start);
          exibeHorarioNovaDataAgenda(start);
        }
    })
    	
		$('.fc-today-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});
		$('.fc-next-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});
		$('.fc-prev-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});


 
	 
	      

	});
	exibeDataHoraFuturasCriadasAgenda();

}


function buscaSolicitacoesAcesso()
{
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var rads = document.getElementsByName('rdb_filtro');
	var filtro = 1;
  
	  for(var i = 0; i < rads.length; i++)
	  {
	  	if(rads[i].checked)
	   	{
	    	filtro = rads[i].value;
	  	}
	  }
	
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_solicitacoes_acesso').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_solicitacoes_login.php?filtro="+filtro+"&id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$(document).ready(function() 
		{
	        $('#tb_solicitacoes_acesso').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
		            "aaSorting": [[0, "asc"]]
		        }

		    );
    	} );
}


function exibeDashBoard()
{
	//exibeDashQuantidadeLoginPendente();
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_dashboard_sae').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_dashboard_sae.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibeDetalhesCadastroUsuario(id_agendamento_login)
{
	//alert(cpf);
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_controle_acesso').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_detalhes_cadastro_usuario.php?id_agendamento_login="+id_agendamento_login;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	exibeHistoricoStatusLogin(id_agendamento_login);
}

function carregaComboStatusLogin(id_agendamento_login)
{
	//var id_agendamento_login = document.getElementById('id_agendamento_login').value;
	exibeDetalhesCadastroUsuario(id_agendamento_login);
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_cmbStatusLogin').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/carrega_combo_status_login_modal.php?id_agendamento_login="+id_agendamento_login;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	exibeOcultaDivInformacoesMotivoStatusLogin();
	
}

function exibeOcultaDivInformacoesMotivoStatusLogin()
{
	var id_status_login = document.getElementById('cmb_status_login').value;
	//alert(id_status_login);
	if(id_status_login == 3 || id_status_login == 4 || id_status_login == 5)
	{
		document.getElementById('div_obs_info_login').style.display = 'block';
	}
	else
	{
		document.getElementById('div_obs_info_login').style.display = 'none';	
	}
}

function exibeHistoricoStatusLogin(id_agendamento_login)
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_historico_status_login').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_historico_status_login.php?id_agendamento_login="+id_agendamento_login;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	
}


function resetaSenhaLogin(id_agendamento_login)
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_historico_status_login').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/reseta_senha_login.php?id_agendamento_login="+id_agendamento_login;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	

	setTimeout(function() { exibeDetalhesCadastroUsuario(id_agendamento_login); }, 500);	
}

function alteraStatusLogin(id_agendamento_login)
{
	var id_status_login_novo = document.getElementById('cmb_status_login').value;
	var txt_observacao_login = document.getElementById('txt_observacao_login').value;
	
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_historico_status_login').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/altera_status_login.php?id_agendamento_login="+id_agendamento_login+"&id_status_login_novo="+id_status_login_novo+"&txt_observacao_login="+txt_observacao_login;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();	

	setTimeout(function() { exibeDetalhesCadastroUsuario(id_agendamento_login); }, 500);	
}



function exibeHorarioNovaDataAgenda(data)
{


	document.getElementById('dt_selecionada').value = data;
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var hr_inicio_manha = document.getElementById('cmb_inicio_exp_manha').value;
	var hr_fim_manha = document.getElementById('cmb_fim_exp_manha').value;
	var hr_inicio_tarde = document.getElementById('cmb_inicio_exp_tarde').value;
	var hr_fim_tarde = document.getElementById('cmb_fim_exp_tarde').value;
	var duracao_atendimento = document.getElementById('cmb_duracao_atendimento').value;
	var qtd_atendente = document.getElementById('cmb_qtd_atendente').value;
	var chk_exp_manha = document.getElementsByName('chk_exp_manha');
	var chk_exp_tarde = document.getElementsByName('chk_exp_tarde');

	//Verifica se Nao Há Expediente Manhã
	if(chk_exp_manha[0].checked == false)
	{
		hr_fim_manha = hr_inicio_manha;
	}

	//Verifica se Nao Há Expediente Tarde
	if(chk_exp_tarde[0].checked == false)
	{
		hr_fim_tarde = hr_inicio_tarde;
	}

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_horarios').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_horario_nova_data_agenda.php?data="+data+"&hr_inicio_manha="+hr_inicio_manha+"&hr_fim_manha="+hr_fim_manha+"&hr_inicio_tarde="+hr_inicio_tarde+"&hr_fim_tarde="+hr_fim_tarde+"&duracao_atendimento="+duracao_atendimento+"&qtd_atendente="+qtd_atendente+"&id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	 $(function () 
	 {
        $('#dt_reagendamento').datetimepicker({format: 'DD/MM/YYYY'});
        $('#txt_data_futura_reagendamento').mask('99/99/9999');
     	//$('#teste').mouseover(function(e){

     		  //alert($(this).val);
	          
	      //    exibePopover();
	    //});
	    //$('#teste').mouseleave(function(){
	      //    removePopover();
	    //});
     });
	 //alert('teste');
     
     
}

function verificaOndeEstaMouse()
{

}

function exibePopover(botao,hora)
{	
	var btn = '#';
	btn += botao;
	//alert(btn);
	var div = '#div-popover_';
	div += botao;
	$(btn).popover({
         
         html: true,
         title: hora + "h",
         container: "body",
         content: $(div).html() // Adiciona o conteúdo da div oculta para dentro do popover.
      });//.click(function (e) {
         //e.preventDefault();
         // Exibe o popover.
         //$(this).popover('show');
      //});
	$(btn).popover('show');
	$(btn).mouseleave(function(){
	         $(btn).popover('destroy');
	    });

}

function removePopover()
{
	// $(function () 
	// {
	$('#teste').popover('destroy');
	
	// });
//alert('saiu');
}

function validaSelecaoAssunto(nm_chk_assunto_horario)
{
	var st_assunto_marcado = false;
	var assuntos = document.getElementsByName(nm_chk_assunto_horario);
	for (var y = 0; y < assuntos.length; y++)
	{
		if(assuntos[y].checked == true)
		{
			//alert(assuntos[y].value);
			st_assunto_marcado = true;
		}
		else if (st_assunto_marcado == false && y == (assuntos.length - 1))
		{
			//alert('O ultimo foi verificado e nenhum foi marcado');
			alert("ATENÇÃO: NO MÍNIMO UM ASSUNTO DEVE PERMANECER SER MARCADO!");
			for (var x = 0; x < assuntos.length; x++)
			{
				assuntos[x].checked = true;
			}
		}
	}

	//if(st_assunto_marcado == false)
	//{
	//	alert("Marque ao menos um assunto para o horário especificado!");

	//}

}

function insereDataHoraAgenda(data_criada)
{
	
	var data_selecionada = document.getElementById('dt_selecionada').value;
	var nr_unidade = document.getElementById('cmb_om_atendimento').value;
	var qt_agendamento_max = document.getElementById('cmb_qtd_atendente').value;
	var hora = document.getElementsByName('chk_horas');
	var qt_atendente_hora;
	var qt_max_protocolo_hora;
	
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_horarios').innerHTML = ajax.responseText;
	 	}
	 }

	var dados = 0;
	

    for (var x = 0; x < hora.length; x++)
    {
    	if(hora[x].checked == true)
    	{
    		hora_linha = hora[x].value;
    		nm_cmb_qtd_atendente_horario = "cmb_qtd_atendente_horario_" + hora[x].value.replace(":","");
    		nm_cmb_qtd_max_protocolo_hora = "cmb_qtd_max_protocolo_horario_" + hora[x].value.replace(":","");
    		nm_cmb_tipo_usuario_permitido_horario = "cmb_tipo_usuario_permitido_horario_" + hora[x].value.replace(":","");
    		nm_chk_assunto_horario = "chk_assunto_" + hora[x].value.replace(":","");

    		qt_atendente_hora = document.getElementById(nm_cmb_qtd_atendente_horario).value;

    		qt_max_protocolo_hora = document.getElementById(nm_cmb_qtd_max_protocolo_hora).value;

    		tipo_usuario_permitido_horario = document.getElementById(nm_cmb_tipo_usuario_permitido_horario).value;

    		//PEGA OS ID DOS ASSUNTOS SELECIONADOS NO HORÁRIO
    		var assuntos = document.getElementsByName(nm_chk_assunto_horario);
    		var id_assuntos = null;
    		for (var y = 0; y < assuntos.length; y++)
			{
				if(assuntos[y].checked == true)
				{
					if(y == 0)
					{
						id_assuntos = assuntos[y].value;	
					}
					else if(y != 0 && id_assuntos != null)
					{
						id_assuntos += ",";	
						id_assuntos += assuntos[y].value;	
					}
					else
					{
						id_assuntos = assuntos[y].value;	
					}    					
				}

			}

	    	if(dados == 0)
	    	{
	    		
    			dados = hora_linha + "-" + qt_atendente_hora + "-" + qt_max_protocolo_hora + "-" + tipo_usuario_permitido_horario + "-" + id_assuntos;
	    		
	    	}
	    	else
	    	{
	    		dados += "@" + hora_linha + "-" + qt_atendente_hora + "-" + qt_max_protocolo_hora + "-" + tipo_usuario_permitido_horario + "-" + id_assuntos;
	    	}
	    }
    }

	url = "sae/funcoes/insere_data_hora_agenda.php?nr_unidade="+nr_unidade+"&dt_agendamento="+data_selecionada+"&dados="+dados;
	//alert(url);
	
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	if(data_criada == 0)
	{
		insereItenDataCalendario(data_selecionada);
	}
	else
	{
		exibeHorarioNovaDataAgenda(data_selecionada);
	}
}


function exibeDataHoraFuturasCriadasAgenda()
{
	var unidade_sfpc = document.getElementById('cmb_om_atendimento').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_data_criadas').innerHTML = ajax.responseText;
	 	}
	}
	url = "sae/funcoes/exibe_data_hora_futura_na_agenda.php?unidade_sfpc="+unidade_sfpc;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	var dt_futuras_bloc = document.getElementById('dt_futuras_bloc').value;
	var dt_futuras_lib = document.getElementById('dt_futuras_lib').value;
	var dt_futuras_age = document.getElementById('dt_futuras_age').value;
	var dt_futuras_lot = document.getElementById('dt_futuras_lot').value;

	//alert(dt_futuras_bloc);
	//alert(dt_futuras_lib);


	var datas_bloc = null;
	var datas_lib = null;
	var datas_age = null;
	var datas_lot = null;

	if(dt_futuras_bloc != '')
	{
		datas_bloc = dt_futuras_bloc.split(',');
		for (var dt = 0; dt < datas_bloc.length; dt++)
	    {
	    	insereItenDataCalendario(datas_bloc[dt]);
	    }
	}
	
	if(dt_futuras_lib != '')
	{
		datas_lib = dt_futuras_lib.split(',');
		for (var x = 0; x < datas_lib.length; x++)
	    {
	    	insereItenLiberadoDataCalendario(datas_lib[x]);
	    }
	}
	
	if(dt_futuras_age != '')
	{
		datas_age = dt_futuras_age.split(',');
		for (var x = 0; x < datas_age.length; x++)
	    {
	    	insereItenAgendadoDataCalendario(datas_age[x]);
	    }
	}

	if(dt_futuras_lot != '')
	{
		datas_lot = dt_futuras_lot.split(',');
		for (var x = 0; x < datas_lot.length; x++)
	    {
	    	insereItenAgendadoDataLotadaCalendario(datas_lot[x]);
	    }
	}

  

}

function insereItenDataCalendario(data)
{
	$('#calendar').fullCalendar(
	     'renderEvent',
	     {
	      id: data,
	      title: 'JAN - BLOQ',
	      start: data,
	      url: 'javascript:exibeHorarioNovaDataAgenda("'+data+'")',
	     }
	    );
}

function insereItenLiberadoDataCalendario(data)
{
	$('#calendar').fullCalendar(
	     'renderEvent',
	     {
	      id: data,
	      title: 'JAN - LIB',
	      start: data,
	      url: 'javascript:exibeHorarioNovaDataAgenda("'+data+'")',
	      color: 'green',
	     }
	    );
}

function insereItenAgendadoDataCalendario(data)
{
    $('#calendar').fullCalendar(
         'renderEvent',
         {
          id: data,
          title: 'JAN - AGEN',
          start: data,
          url: 'javascript:exibeHorarioNovaDataAgenda("'+data+'")',
          color: 'orange',
         }
        );
}

function insereItenAgendadoDataLotadaCalendario(data)
{
    $('#calendar').fullCalendar(
         'renderEvent',
         {
          id: data,
          title: 'JAN - LOT',
          start: data,
          url: 'javascript:exibeHorarioNovaDataAgenda("'+data+'")',
          color: 'red',
         }
        );
}

function excluiItenDataCalendario(data)
{
	$('#calendar').fullCalendar(
	     'removeEvents',data);

}

function liberaJanelaAgendamento(id_agendamento_data)
{
	var data_selecionada = document.getElementById('dt_selecionada').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_horarios').innerHTML = ajax.responseText;
	 	}
	 }


	url = "sae/funcoes/libera_janela_agenda.php?id_agendamento_data="+id_agendamento_data+"&data="+data_selecionada;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	excluiItenDataCalendario(data_selecionada);
	insereItenLiberadoDataCalendario(data_selecionada);

}

function excluiJanelaAgendamento(id_agendamento_data)
{
	var data_selecionada = document.getElementById('dt_selecionada').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_horarios').innerHTML = ajax.responseText;
	 	}
	 }


	url = "sae/funcoes/exclui_janela_agenda.php?id_agendamento_data="+id_agendamento_data+"&data="+data_selecionada;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	excluiItenDataCalendario(data_selecionada);

}

function atualizaParametro(parametro)
{
	var valor = document.getElementById(parametro).value;
	var unidade_sfpc = document.getElementById('cmb_om_atendimento').value;
	
	//alert('aqui');
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_data_criadas').innerHTML = ajax.responseText;
	 	}
	}
	url = "sae/funcoes/atualiza_parametro.php?id_unidade="+unidade_sfpc+"&id_modulo=2&nm_parametro="+parametro+"&valor="+valor;
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function exibeHorarioNovaDataAgendaAtualizaParametro(parametro)
{
	var data_selecionada = document.getElementById('dt_selecionada').value;
	if(data_selecionada != "")
	{
		setTimeout(function() 
		{ 
			exibeHorarioNovaDataAgenda(data_selecionada);
		}, 500);
		
	}
	atualizaParametro(parametro);
}

function selecionaExpedienteManha()
{
	var chk_exp_manha = document.getElementsByName('chk_exp_manha');
	var chk_exp_tarde = document.getElementsByName('chk_exp_tarde');
	var data_selecionada = document.getElementById('dt_selecionada').value;
	
	if(chk_exp_manha[0].checked == true)
	{
		//alert('COM EXP');
		document.getElementById('cmb_inicio_exp_manha').style.display = 'block';
		document.getElementById('cmb_fim_exp_manha').style.display = 'block';
		exibeHorarioNovaDataAgenda(data_selecionada);
	}
	else
	{
		//alert('SEM EXP');
		//Verifica se tarde e está desmarcado e marca
		if(chk_exp_tarde[0].checked == false)
		{
			chk_exp_tarde[0].checked = true;
			document.getElementById('cmb_inicio_exp_tarde').style.display = 'block';
			document.getElementById('cmb_fim_exp_tarde').style.display = 'block';
		}
		
		document.getElementById('cmb_inicio_exp_manha').style.display = 'none';
		document.getElementById('cmb_fim_exp_manha').style.display = 'none';
		exibeHorarioNovaDataAgenda(data_selecionada);
	}
	
}

function selecionaExpedienteTarde()
{
	var chk_exp_tarde = document.getElementsByName('chk_exp_tarde');
	var chk_exp_manha = document.getElementsByName('chk_exp_manha');
	var data_selecionada = document.getElementById('dt_selecionada').value;
	
	if(chk_exp_tarde[0].checked == true)
	{
		
		document.getElementById('cmb_inicio_exp_tarde').style.display = 'block';
		document.getElementById('cmb_fim_exp_tarde').style.display = 'block';
		exibeHorarioNovaDataAgenda(data_selecionada);

	}
	else
	{
		//alert('SEM EXP');
		//Verifica se manhã e está desmarcado e marca
		if(chk_exp_manha[0].checked == false)
		{
			chk_exp_manha[0].checked = true;
			document.getElementById('cmb_inicio_exp_manha').style.display = 'block';
			document.getElementById('cmb_fim_exp_manha').style.display = 'block';
		}
		document.getElementById('cmb_inicio_exp_tarde').style.display = 'none';
		document.getElementById('cmb_fim_exp_tarde').style.display = 'none';
		exibeHorarioNovaDataAgenda(data_selecionada);
	}
	
}

function exibeUsuariosAgendados()
{
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var data = document.getElementById('txt_data_atendimento_usuarios_agendados').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_usuarios_agendados').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/exibe_usuarios_agendados.php?data="+data+"&id_unidade="+id_unidade;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	//exibeHistoricoStatusLogin(id_agendamento_login);
}

function transferirUsuariosAgendados()
{
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var data_selecionada = document.getElementById('dt_selecionada').value;
	var data_futura = document.getElementById('txt_data_futura_reagendamento').value;
	var rdb_periodo = document.getElementsByName('rdb_periodo');
	var periodo;
	
	for(var i = 0; i < rdb_periodo.length; i++)
	{
	   	if(rdb_periodo[i].checked)
	   	{
	    	periodo = rdb_periodo[i].value;
		}
	}

	var data_futura_yyyy_mm_dd = data_futura.substring(6, 10);
	data_futura_yyyy_mm_dd += "-";
	data_futura_yyyy_mm_dd += data_futura.substring(3, 5);
	data_futura_yyyy_mm_dd += "-";
	data_futura_yyyy_mm_dd += data_futura.substring(0, 2);
	


	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_horarios').innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/transfere_usuarios_agendados.php?data_selecionada="+data_selecionada+"&data_futura="+data_futura+"&id_unidade="+id_unidade+"&periodo="+periodo;

	setTimeout(function() 
	{ 
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();	
	}, 500);
	
	

	setTimeout(function() 
	{ 
		//document.getElementById('div_horarios').innerHTML = "";
	$(document).ready(function() {
    var initialLangCode = 'pt-br';

    $('#calendar').fullCalendar('destroy');
   
    // page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
        // put your options and callbacks here

        lang: initialLangCode,
        editable: true,
        selectable : true,
        selectHelper : true,
        businessHours : true,

        select: function (start, end, allDay) 
        {
          start = $.fullCalendar.formatRange(start, start, 'YYYY-MM-DD');
          datasel = new Date(start);
          exibeHorarioNovaDataAgenda(start);
        }
    })
    	
		$('.fc-today-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});
		$('.fc-next-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});
		$('.fc-prev-button').click(function(){
		   exibeDataHoraFuturasCriadasAgenda();
		});
	});
	exibeDataHoraFuturasCriadasAgenda();	
	}, 900);	
}

function marcaPresencaAusenciaAtendimento(id_agendamento_requerente,div_presenca,situacao)
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById(div_presenca).innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/marca_presenca_ausencia_atendimento.php?id_agendamento_requerente="+id_agendamento_requerente+"&situacao="+situacao;

	setTimeout(function() 
	{ 
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();	
	}, 500);
}

function imprimeRelaAgendamentoDia()
{
	var id_unidade = document.getElementById('cmb_om_atendimento').value;
	var nm_unidade = document.getElementById('cmb_om_atendimento').options[document.getElementById("cmb_om_atendimento").selectedIndex].text;
	var data = document.getElementById('txt_data_atendimento_usuarios_agendados').value;
	url = "sae/funcoes/imprime_rela_agendados_dia.php?data="+data+"&id_unidade="+id_unidade+"&nm_unidade="+nm_unidade;

	window.open(url);

}

function liberaVagaHorarioDesmarcado(id_agendamento_login, id_agendamento_horario, nm_div_linha_acao)
{
	//alert(id_agendamento_login);
	//alert(id_agendamento_horario);
	//alert(nm_div_linha_acao);

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById(nm_div_linha_acao).innerHTML = ajax.responseText;
	 	}
	}


	url = "sae/funcoes/libera_horario_desagendado.php?id_agendamento_login="+id_agendamento_login+"&id_agendamento_horario="+id_agendamento_horario;

	setTimeout(function() 
	{ 
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();	
	}, 500);
}






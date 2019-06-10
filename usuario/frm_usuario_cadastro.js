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

function listaUsuariosCadastrados()
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_lista_usuarios').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 //var dados = "id_carteira="+document.getElementById('cmb_carteira').value+"&sg_tipo_interessado="+document.getElementById('sg_tipo_interessado').value;

	 ajax.open("GET", "usuario/exibeUsuariosCadastrados.php", false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
	 
	 
		$(document).ready(function() 
		{
	        $('#tb_usuarios').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]]
		            
		        }

		    );
    	} );

}

function exibeDetalhesCadastroUsuario(id_login)
{
	var ajax = AjaxF();
	 ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_corpo_painel_usuarios_sisprot').innerHTML = ajax.responseText;
	 	}
	 }
	 // Variável com os dados que serão enviados ao PHP
	 //var dados = "id_carteira="+document.getElementById('cmb_carteira').value+"&sg_tipo_interessado="+document.getElementById('sg_tipo_interessado').value;

	 ajax.open("GET", "usuario/exibeDetalhesCadastroUsuario.php?id_login="+id_login, false);
	 ajax.setRequestHeader("Content-Type", "text/html");
	 ajax.send();
}

function addAcessoCarteiraUsuario()
{
	
	var carteiras = document.getElementById('sel_mult_carteiras');
	var acessoemcarteiras = document.getElementById('sel_mult_carteiras_acesso');
	var jaexiste = 0;
		
		//Adiciona os Itens Selecionados
		for(i=0; i<=carteiras.length-1; i++)
		{
		
			if(carteiras.options[i].selected == true)
			{
				
				for(x=0; x<=acessoemcarteiras.length-1; x++)
				{
					if(carteiras.options[i].value == acessoemcarteiras.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
				if(jaexiste == 0)
				{
					opt = new Option( carteiras.options[i].text, carteiras.options[i].value);
					acessoemcarteiras.options[acessoemcarteiras.length] = opt;
					inclui_exclui_carteira_selecionada(carteiras.options[i].value);
				}
			}
		}
		
		//Remove os Itens Inseridos
		var carteiras = document.getElementById('sel_mult_carteiras');
		var acessoemcarteiras = document.getElementById('sel_mult_carteiras_acesso');
		for(y=0; y<=carteiras.length-1; y++)
		{
			if(carteiras.options[y].selected == true)
			{
				//alert(carteiras.options[y].value);
				for(z=0; z<=acessoemcarteiras.length-1; z++)
				{
					
					if(carteiras.options[y].value == acessoemcarteiras.options[z].value)
						{
							
							carteiras.remove(y);
						}
				}
			}
		}
}


function removeAcessoCarteiraUsuario()
{
	
	var acessoemcarteiras = document.getElementById('sel_mult_carteiras');
	var carteiras = document.getElementById('sel_mult_carteiras_acesso');
	var jaexiste = 0;
		
		//Adiciona os Itens Selecionados
		for(i=0; i<=carteiras.length-1; i++)
		{
			if(carteiras.options[i].selected == true)
			{
				
				for(x=0; x<=acessoemcarteiras.length-1; x++)
				{
					if(carteiras.options[i].value == acessoemcarteiras.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
				if(jaexiste == 0)
					{
						opt = new Option( carteiras.options[i].text, carteiras.options[i].value);
						acessoemcarteiras.options[acessoemcarteiras.length] = opt;
						inclui_exclui_carteira_selecionada(carteiras.options[i].value);
					}
			}
		}
		
		//Remove os Itens Inseridos
		var acessoemcarteiras = document.getElementById('sel_mult_carteiras');
		var carteiras = document.getElementById('sel_mult_carteiras_acesso');
		for(i=0; i<=acessoemcarteiras.length-1; i++)
		{
			if(carteiras.options[i].selected == true)
			{
				for(x=0; x<=acessoemcarteiras.length-1; x++)
				{
					if(carteiras.options[i].value == acessoemcarteiras.options[x].value)
						{
							carteiras.remove(i);
						}
				}
			}
		}
		//mostraBotaoAtualizaCarteiras();
		//ocultaDivMensagemCarteiraAtualizada();
}

function addAcessoModuloUsuario()
{
	
	var modulo = document.getElementById('sel_mult_modulo');
	var acessoemmodulos = document.getElementById('sel_mult_modulos_acesso');
	var jaexiste = 0;
		
		//Adiciona os Itens Selecionados
		for(i=0; i<=modulo.length-1; i++)
		{
		
			if(modulo.options[i].selected == true)
			{
				
				for(x=0; x<=acessoemmodulos.length-1; x++)
				{
					if(modulo.options[i].value == acessoemmodulos.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
				if(jaexiste == 0)
				{
					opt = new Option( modulo.options[i].text, modulo.options[i].value);
					acessoemmodulos.options[acessoemmodulos.length] = opt;
					inclui_exclui_modulo_selecionado(modulo.options[i].value);
				}
			}
		}
		
		//Remove os Itens Inseridos
		var modulo = document.getElementById('sel_mult_modulo');
		var acessoemmodulos = document.getElementById('sel_mult_modulos_acesso');
		for(y=0; y<=modulo.length-1; y++)
		{
			if(modulo.options[y].selected == true)
			{
				//alert(carteiras.options[y].value);
				for(z=0; z<=acessoemmodulos.length-1; z++)
				{
					
					if(modulo.options[y].value == acessoemmodulos.options[z].value)
						{
							
							modulo.remove(y);
						}
				}
			}
		}
}


function removeAcessoModuloUsuario()
{
	
	var acessoemmodulos = document.getElementById('sel_mult_modulo');
	var modulo = document.getElementById('sel_mult_modulos_acesso');
	var jaexiste = 0;
		
		//Adiciona os Itens Selecionados
		for(i=0; i<=modulo.length-1; i++)
		{
			if(modulo.options[i].selected == true)
			{
				
				for(x=0; x<=acessoemmodulos.length-1; x++)
				{
					if(modulo.options[i].value == acessoemmodulos.options[x].value)
					{
						jaexiste = 1;
					}
					
				}
				if(jaexiste == 0)
					{
						opt = new Option( modulo.options[i].text, modulo.options[i].value);
						acessoemmodulos.options[acessoemmodulos.length] = opt;
						inclui_exclui_modulo_selecionado(modulo.options[i].value);
					}
			}
		}
		
		//Remove os Itens Inseridos
		var acessoemmodulos = document.getElementById('sel_mult_modulo');
		var modulo = document.getElementById('sel_mult_modulos_acesso');
		for(i=0; i<=acessoemmodulos.length-1; i++)
		{
			if(modulo.options[i].selected == true)
			{
				for(x=0; x<=acessoemmodulos.length-1; x++)
				{
					if(modulo.options[i].value == acessoemmodulos.options[x].value)
						{
							modulo.remove(i);
						}
				}
			}
		}
		//mostraBotaoAtualizaCarteiras();
		//ocultaDivMensagemCarteiraAtualizada();
}



function novo_usuario()
{
	location.href="home.php?url=novo_usuario";
}

function inclui_exclui_carteira_selecionada(id_carteira)
{
	var itemSelecionado = id_carteira;
	var dados = document.getElementById('id_carteiras_selecionadas').value;

	if(dados == '')
	{
		dados = id_carteira;
		document.getElementById('id_carteiras_selecionadas').value = dados;
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
				if(dados1[i] != id_carteira)
			    {
			    	if(result == null)
			    		result = dados1[i];
			    	else
			    		result = result + ',' + dados1[i];
			    }
			}
			document.getElementById('id_carteiras_selecionadas').value = result;
		}
		else
		{
		 	dados = document.getElementById('id_carteiras_selecionadas').value + "," + id_carteira;
			document.getElementById('id_carteiras_selecionadas').value = dados;
		}
	}
	
	mostraBotaoAtualizaCarteiras();
	ocultaDivMensagemCarteiraAtualizada();
}

function inclui_exclui_modulo_selecionado(id_modulo)
{
	var itemSelecionado = id_modulo;
	var dados = document.getElementById('id_modulos_selecionados').value;

	if(dados == '')
	{
		dados = id_modulo;
		document.getElementById('id_modulos_selecionados').value = dados;
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
				if(dados1[i] != id_modulo)
			    {
			    	if(result == null)
			    		result = dados1[i];
			    	else
			    		result = result + ',' + dados1[i];
			    }
			}
			document.getElementById('id_modulos_selecionados').value = result;
		}
		else
		{
		 	dados = document.getElementById('id_modulos_selecionados').value + "," + id_modulo;
			document.getElementById('id_modulos_selecionados').value = dados;
		}
	}
	
	mostraBotaoAtualizaModulos();
	ocultaDivMensagemModuloAtualizado();
}

function inclui_exclui_unidade_selecionada(id_unidade)
{
	 var itemSelecionado = id_unidade;
	 var dados = document.getElementById('id_unidades_selecionadas').value;

	 if(dados == '')
	 {
		dados = id_unidade;
		document.getElementById('id_unidades_selecionadas').value = dados;
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
				if(dados1[i] != id_unidade)
			    {
			    	if(result == null)
			    		result = dados1[i];
			    	else
			    		result = result + ',' + dados1[i];
			    }
			}
			document.getElementById('id_unidades_selecionadas').value = result;
		}
		else
		{
		 	dados = document.getElementById('id_unidades_selecionadas').value + "," + id_unidade;
			document.getElementById('id_unidades_selecionadas').value = dados;
		}
	 }
}

function MarcarDesmarcarTodasUnidades(marcar)
{
		document.getElementById('id_unidades_selecionadas').value = "";
        var itens = document.getElementsByName('unidades[]');
        var desabilita;

        if(marcar){
            desabilita = true;
        }else{
            desabilita = false;
        }

        var i = 0;
        for(i=0; i<itens.length;i++){
            itens[i].checked = marcar;
            if(i > 0)
            {
            	
            	if(desabilita)
            	{
            		inclui_exclui_unidade_selecionada(itens[i].value);
            		itens[i].disabled = true;
            	}
            	else
            	{
            		itens[i].disabled = false;
            	}
            }
    
        }
}

function incluir_usuario()
{
	if(validaFormularioUsuarioNovo() == true)
	{
		//alert('validou');
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('div_resultado_novo_usuario').innerHTML = ajax.responseText;
		 	}
		}

		var posto_grad = document.getElementById('cmb_posto_graduacao').value;
		var nm_login = document.getElementById('nm_login').value;
		var nm_guerra = document.getElementById('nm_guerra').value;
		var nm_completo = document.getElementById('nm_completo').value;
		var email = document.getElementById('email').value;
		var ompertencente = document.getElementById('cmb_unidade_pertencente').value;
		var perfilacesso = document.getElementById('cmb_perfil_acesso').value;
		var id_carteiras = document.getElementById('id_carteiras_selecionadas').value;
		var id_modulos = document.getElementById('id_modulos_selecionados').value;
		var id_unidades_acesso = document.getElementById('id_unidades_selecionadas').value;


		url = "usuario/insereNovoUsuario.php?posto_grad="+posto_grad+"&nm_login="+nm_login+"&nm_guerra="+nm_guerra+"&nm_completo="+nm_completo+"&email="+email+"&ompertencente="+ompertencente+"&perfilacesso="+perfilacesso+"&id_carteiras="+id_carteiras+"&id_modulos="+id_modulos+"&id_unidades_acesso="+id_unidades_acesso;

		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
	}
	

}

function validaFormularioUsuarioNovo()
{
	var div_msg = document.getElementById('div_alert_erro');
	div_msg.style.display = 'none';

	var mensagem;
	var valido = true;

	var posto_grad = document.getElementById('cmb_posto_graduacao').value;
	var nm_login = document.getElementById('nm_login').value;
	var nm_guerra = document.getElementById('nm_guerra').value;
	var nm_completo = document.getElementById('nm_completo').value;
	var ompertencente = document.getElementById('cmb_unidade_pertencente').value;
	var perfilacesso = document.getElementById('cmb_perfil_acesso').value;
	var id_unidades_acesso = document.getElementById('id_unidades_selecionadas').value;

	if(posto_grad == 0)
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> SELECIONE O POSTO OU GRADUAÇÃO!";
		document.getElementById('cmb_posto_graduacao').focus();
	}
	else if(nm_login == "")
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> INFORME O LOGIN DO NOVO USUÁRIO!";
		document.getElementById('nm_login').focus();
	}
	else if(nm_guerra == "")
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> INFORME O NOME DE GUERRA!";
		document.getElementById('nm_guerra').focus();
	}
	else if(nm_completo == "")
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> INFORME O NOME COMPLETO!";
		document.getElementById('nm_completo').focus();
	}
	else if(ompertencente == 0)
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> SELECIONE A OM SFPC PERTENCENTE!";
		document.getElementById('cmb_unidade_pertencente').focus();
	}
	else if(perfilacesso == 0)
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> SELECIONE O PERFIL DE ACESSO AO SISPROT!";
		document.getElementById('cmb_perfil_acesso').focus();
	}
	
	else if(id_unidades_acesso == "")
	{
		valido = false;
		mensagem = "<strong>ATENÇÃO:</strong> SELECIONE AO MENOS UMA UNIDADE QUE POSSUIRÁ ACESSO!";
	}

	
	if(valido == false)
	{
		document.getElementById('div_alert_erro').style.display = 'block';
		document.getElementById('div_msg_erro').innerHTML = mensagem;
	}
	return valido;
}

function atualizaAcessoCarteiras()
{
	var id_login = document.getElementById('id_login').value;
	var id_carteiras = null;
	var acessoemcarteiras = document.getElementById('sel_mult_carteiras_acesso');
	for(x=0; x<=acessoemcarteiras.length-1; x++)
	{
		if(id_carteiras == null)
		{
			id_carteiras = acessoemcarteiras.options[x].value;
		}
		else
		{
			id_carteiras = id_carteiras + ',' + acessoemcarteiras.options[x].value;
		}
		
	}
	

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_acesso_carteiras').innerHTML = ajax.responseText;
	 	}
	}
	url = "usuario/atualiza_acesso_usuario_carteiras.php?id_login="+id_login+"&id_carteiras="+id_carteiras;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	ocultaBotaoAtualizaCarteiras();
	mostraDivMensagemCarteiraAtualizada();
}

function atualizaAcessoModulos()
{
	var id_login = document.getElementById('id_login').value;
	var id_modulos = null;
	var acessoemmodulos = document.getElementById('sel_mult_modulos_acesso');
	for(x=0; x<=acessoemmodulos.length-1; x++)
	{
		if(id_modulos == null)
		{
			id_modulos = acessoemmodulos.options[x].value;
		}
		else
		{
			id_modulos = id_modulos + ',' + acessoemmodulos.options[x].value;
		}
		
	}
	

	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_acesso_modulos').innerHTML = ajax.responseText;
	 	}
	}
	url = "usuario/atualiza_acesso_usuario_modulos.php?id_login="+id_login+"&id_modulos="+id_modulos;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	ocultaBotaoAtualizaModulos();
	mostraDivMensagemModuloAtualizado();
}

function atualizaDadosUsuario()
{
	
		//alert('validou');
		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		 	if(ajax.readyState == 4)
		 	{
		 		document.getElementById('div_resultado_dados_usuario').innerHTML = ajax.responseText;
		 	}
		}

		var id_login = document.getElementById('id_login').value;
		var posto_grad = document.getElementById('cmb_posto_graduacao').value;
		var nm_guerra = document.getElementById('nm_guerra').value;
		var nm_completo = document.getElementById('nm_completo').value;
		var email = document.getElementById('email').value;
		var ompertencente = document.getElementById('cmb_unidade_pertencente').value;
		var perfilacesso = document.getElementById('cmb_perfil_acesso').value;
		var st_ativo = document.getElementById('cmb_status_login').value;


		url = "usuario/atualiza_dados_usuario.php?id_login="+id_login+"&posto_grad="+posto_grad+"&nm_guerra="+nm_guerra+"&nm_completo="+nm_completo+"&email="+email+"&ompertencente="+ompertencente+"&perfilacesso="+perfilacesso+"&st_ativo="+st_ativo;

		//alert(url);
		ajax.open("GET",url, false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
		ocultaBotaoAtualizaDadosUsuario();
		//mostraDivMensagemDadosUsuarioAtualizado();
}

function resetarSenhaUsuario()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_dados_usuario').innerHTML = ajax.responseText;
	 	}
	}

	var id_login = document.getElementById('id_login').value;

	url = "usuario/reseta_senha_usuario.php?id_login="+id_login;

	//alert(url);
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}

function atualizaAcessoUnidades()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_resultado_acesso_unidades').innerHTML = ajax.responseText;
	 	}
	}

	var id_login = document.getElementById('id_login').value;
	var id_unidades_selecionadas = document.getElementById('id_unidades_selecionadas').value;
	

	url = "usuario/atualiza_acesso_unidades_usuario.php?id_login="+id_login+"&id_unidades_selecionadas="+id_unidades_selecionadas;

	//alert(url);
	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	ocultaBotaoAtualizaUnidadesUsuario();
}

function mostraBotaoAtualizaCarteiras()
{
	try{
			document.getElementById('div_btn_atualiza_carteira').style.display = 'block';
		}
	catch(err){}
}

function mostraBotaoAtualizaModulos()
{
	try{
			document.getElementById('div_btn_atualiza_modulo').style.display = 'block';
		}
	catch(err){}
}

function ocultaBotaoAtualizaCarteiras()
{
	document.getElementById('div_btn_atualiza_carteira').style.display = 'none';
}

function ocultaBotaoAtualizaModulos()
{
	document.getElementById('div_btn_atualiza_modulo').style.display = 'none';
}

function ocultaDivMensagemCarteiraAtualizada()
{
	try{
		document.getElementById('div_resultado_acesso_carteiras').style.display = 'none';
		}
	catch(err){}
}

function ocultaDivMensagemModuloAtualizado()
{
	try{
		document.getElementById('div_resultado_acesso_modulos').style.display = 'none';
		}
	catch(err){}
}

function mostraDivMensagemCarteiraAtualizada()
{
	document.getElementById('div_resultado_acesso_carteiras').style.display = 'block';
}

function mostraDivMensagemModuloAtualizado()
{
	document.getElementById('div_resultado_acesso_modulos').style.display = 'block';
}

function mostraBotaoAtualizaDadosUsuario()
{
	document.getElementById('div_btn_salva_dados_usuario').style.display = 'block';
}

function ocultaBotaoAtualizaDadosUsuario()
{
	document.getElementById('div_btn_salva_dados_usuario').style.display = 'none';
}

function mostraDivMensagemDadosUsuarioAtualizado()
{
	document.getElementById('div_resultado_dados_usuario').style.display = 'block';
}

function ocultaBotaoAtualizaUnidadesUsuario()
{
	document.getElementById('div_btn_atualiza_unidades_usuario').style.display = 'none';
}


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

function entrar()
{
	//alert('entrou');
	var login = document.getElementById('login_sfpc').value;
	var senha_login = document.getElementById('senha_sfpc').value;
	if(login == '')
	{
		document.getElementById('login_sfpc').focus();
	}
	else if (senha_login == '')
	{
		document.getElementById('senha_sfpc').focus();
	}
	else
	{
		document.getElementById('formLogin_siagpc').submit();
	}

}

function exibeEventosAuditoria()
{
	//exibeDashQuantidadeLoginPendente();
	var id_tipo_evento = document.getElementById('cmb_tipo_evento').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_result_auditoria').innerHTML = ajax.responseText;
	 	}
	}

	url = "funcoes/exibe_eventos_auditoria.php?id_tipo_evento="+id_tipo_evento;

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();

	$(document).ready(function() 
		{
	        $('#tb_eventos').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
		            "aaSorting": [[0, "desc"]]
		        }

		    );
    	} );
}

function exibeUsuariosOnline()
{
	//exibeDashQuantidadeLoginPendente();
	//var id_tipo_evento = document.getElementById('cmb_tipo_evento').value;
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_result_auditoria').innerHTML = ajax.responseText;
	 	}
	}

	url = "funcoes/exibe_usuarios_online.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
	$(document).ready(function() 
		{
	        $('#tb_login_online').dataTable
	        ( 
	        	{
		            "language": {"url": "js/dataTablesPT.js"},
		            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
		            "aaSorting": [[0, "desc"]]
		        }

		    );
    	} );

}
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

jQuery(function($){
	$( "#txt_nova_senha" ).focus();
});

function alterarSenha()
{
	document.getElementById('div_msg_sucesso').style.display = 'none';
	document.getElementById('div_msg_falha').style.display = 'none';
	document.getElementById('div_msg_alerta').style.display = 'none';
	document.getElementById('div_msg_info').style.display = 'none';
	var txtSenha = document.getElementById('txt_nova_senha').value;
	var txtConfirmaSenha = document.getElementById('txt_confirmacao_nova_senha').value;

	if (txtSenha != "" && txtConfirmaSenha != "")
	{
			if(txtSenha == txtConfirmaSenha)
			{
				if(txtSenha.length >= 6)
				{
					var ajax = AjaxF();	
					ajax.onreadystatechange = function(){
					 	if(ajax.readyState == 4)
					 	{
					 		document.getElementById('div_mensagem').innerHTML = ajax.responseText;
					 	}
					}
					txtSenha = encodeURIComponent(txtSenha);
					url = "alterasenha/alterarsenha.php?senha="+txtSenha+"&confirmacao="+txtConfirmaSenha;
					ajax.open("GET",url, false);
					ajax.setRequestHeader("Content-Type", "text/html");
					ajax.send();
					document.getElementById('txt_nova_senha').value = "";
					document.getElementById('txt_confirmacao_nova_senha').value = "";
					document.getElementById('div_senha_expirada').style.display = 'none';
					document.getElementById('div_msg_sucesso').style.display = 'block';
					setTimeout(function() { window.location='home.php'; }, 800);	
				}
				else
				{
					document.getElementById('div_msg_alerta').style.display = 'block';
				}
			}
			else
			{
				document.getElementById('div_msg_falha').style.display = 'block';
			}
	}
	else
	{
		document.getElementById('div_msg_info').style.display = 'block';
		document.getElementById('txt_nova_senha').focus();
	}

}


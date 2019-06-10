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

$(document).ready(function()
{
	exibe_totais_gru();

	$('#btn_gru_cadastrada').click(function () 
  	{
  		//alert('entrou');
  		//EXIBE RELA GRUS CADASTRADAS
  		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
		{
		 		document.getElementById('div_result').innerHTML = ajax.responseText;
		}
		}
		
		ajax.open("GET", "protocolo/gru/exibe_gru_cadastro.php", false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();
		$('#tb_gru').dataTable
    	( 
	    	{
	            "language": {"url": "js/dataTablesPT.js"},
	            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]]
	        }
    	);	
  	});


	$('#btn_tentativa_fraude').click(function () 
  	{  
  		//EXIBE RELA TENTATIVAS DE FRAUDE
  		var ajax = AjaxF();
		ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
		{
		 		document.getElementById('div_result').innerHTML = ajax.responseText;
		}
		}
		
		ajax.open("GET", "protocolo/gru/exibe_tentativa_fraude.php", false);
		ajax.setRequestHeader("Content-Type", "text/html");
		ajax.send();

		
		$('#tb_gru_fraude').dataTable
    	( 
	    	{
	            "language": {"url": "js/dataTablesPT.js"},
	            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]]
	        }
    	);	


  	});
});

function exibe_totais_gru()
{
	var ajax = AjaxF();
	ajax.onreadystatechange = function(){
	 	if(ajax.readyState == 4)
	 	{
	 		document.getElementById('div_totais_gru').innerHTML = ajax.responseText;
	 	}
	}

	url = "protocolo/gru/calcula_total_gru.php";

	ajax.open("GET",url, false);
	ajax.setRequestHeader("Content-Type", "text/html");
	ajax.send();
}
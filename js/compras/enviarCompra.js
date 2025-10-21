$(document).ready(function()
{
	$("#ventanaEnviarCompra").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:510,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				enviarOrdenCompra()				  	  
			},
			
		},
		close: function() 
		{
			$("#formularioEnviarCompra").html('');
		}
	});
});

function formularioEnviarCompra(idCompras)
{
	$('#ventanaEnviarCompra').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoCompras').html('<img src="'+ img_loader +'"/> Obteniendo detalles de compra');},
		type:"POST",
		url:base_url+'compras/formularioEnviarCompra',
		data:
		{
			"idCompras":idCompras
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEnviarCompra").html(data);
		},
		error:function(datos)
		{
			$("#formularioEnviarCompra").html("");
			notify('Error al obtener los detalles de la compra',500,5000,'',30,3);
		}
	});//Ajax	
}

function enviarOrdenCompra()
{
	var mensaje="";

	if(!validarEmail($("#txtCorreo").val()))
	{
		mensaje+="El correo es incorrecto <br />";										
	}
	
	if(!camposVacios($("#txtAsunto").val()))
	{
		mensaje+="El asunto es incorrecto <br />";	
	}
	
	if(!camposVacios($("#txtMensaje").val()))
	{
		mensaje+="El mensaje es incorrecto";	
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea enviar la orden de compra?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#enviandoCompra').html('<img src="'+ img_loader +'"/> Enviando la orden de compra');},
		type:"POST",
		url:base_url+"compras/enviarCompra",
		data:$('#frmEnviarCompra').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#enviandoCompra').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al enviar la orden de compra',500,5000,'error',30,3);
				break;
				case "1":
					notify('La orden de compra se ha enviado correctamente',500,5000,'',30,3);
					$('#ventanaEnviarCompra').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al enviar la orden',500,5000,'error',30,3);
			$('#enviandoCompra').html('');
		}
	});//Ajax	
}
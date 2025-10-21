function zipearFacturas(mes,anio,idEmisor,tipo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> El sistema esta zipeando las facturas...');},
		type:"POST",
		url:base_url+'reportes/zipearFacturas/'+mes+'/'+anio+'/'+idEmisor+'/'+tipo,
		data:
		{
			canceladas:		$('#selectCanceladas').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idCliente:		$('#txtBuscarCliente').val(),
			idFactura:		$('#txtBuscarFactura').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargaZip/'+data
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al zipear las facturas',500,5000,'error',2,5);
		}
	});	
}

function zipearFactura(idFactura)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoZip').html('<img src="'+ img_loader +'"/> El sistema esta zipeando la factura...');},
		type:"POST",
		url:base_url+'reportes/zipearFactura',
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoZip').html('');
			
			window.location.href=base_url+'reportes/descargaZip/'+data
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoZip").html('');
			notify('Error al zipear las facturas',500,5000,'error',2,5);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEnviarCorreoFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:750,
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
				enviarCorreoFactura()			  	  
			},
			
		},
		close: function() 
		{
			$("#formularioCorreoFactura").html('');
		}
	});
});

function formularioCorreoFactura(idFactura)
{
	$("#ventanaEnviarCorreoFactura").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioCorreo').html('<img src="'+ img_loader +'"/> Obteniendo detalles de factura...');},
		type:"POST",
		url:base_url+"facturacion/formularioCorreo",
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioCorreoFactura").html(data);
		},
		error:function(datos)
		{
			$("#formularioCorreoFactura").html('');	
		}
	});
}

function enviarCorreoFactura()
{
	if(!camposVacios($("#txtCorreo").val()))
	{
		notify('El correo es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#enviandoCorreoFactura').html('<img src="'+ img_loader +'"/> Se esta enviando el CFDI, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"facturacion/enviarFacturaAdjunta",
		data:
		{
			"idFactura":	$("#txtIdFacturaEnviar").val(),
			"email":		$("#txtCorreo").val(),
			"idUsuario":	$('#selectUsuariosEnviar').val(),
			"firma":		$('#txtFirma').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#enviandoCorreoFactura').html("");
			
			switch(parseInt(data))
			{
				case 0:
					notify('Error al enviar la factura',500,5000,'error',30,3);
				break;
				case 1:
					notify('La factura se ha enviado correctamente',500,5000,'',30,3);
					$("#ventanaEnviarCorreoFactura").dialog('close');
				break;
				case 2:
					notify('Error al enviar la factura, por favor verifique los correos electrónicos',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			$('#enviandoCorreoFactura').html("");
			notify('Error al enviar la factura',500,5000,'error',30,3);
		}
	});//Ajax	
}
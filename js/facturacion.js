/*Cancelación de facturas*/

function obtenerFacturaCancelar(idFactura)
{
	$('#ventanaCancelarFactura').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerFacturaCancelar').html('<img src="'+ img_loader +'"/> Obteniendo detalles de factura...');},
		type:"POST",
		url:base_url+"facturacion/motivosCancelacionFactura",
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerFacturaCancelar").html(data);
		},
		error:function(datos)
		{
			$("#obtenerFacturaCancelar").html('');
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaCancelarFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$('#cargandoCancelacion').html("");
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				cancelarCFDI()					  	  
			},
			
		},
		close: function() 
		{
			$("#ErrorCancelacion").fadeOut();
		}
	});
	
	$("#ventanaEnviarCorreo").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:480,
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
			$("#formularioCorreo").html('');
		}
	});
});

function opcionesCancelacionCfdi()
{
	$('#txtIdFacturaSustitucion').val("0");
	$('#txtBuscarFolioSustitución,#txtUuidSustitucion').val("");
	
	if($('#selectMotivoCancelacion').val()=="01")
	{
		$('#filaFolioSustitucion').fadeIn();
		$('#txtBuscarFolioSustitución').focus();
	}
	else
	{
		$('#filaFolioSustitucion').fadeOut()
	}
}

function formularioCorreo(idFactura)
{
	$("#ventanaEnviarCorreo").dialog('open');
	
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
			$("#formularioCorreo").html(data);
		},
		error:function(datos)
		{
			$("#formularioCorreo").html('');	
		}
	});
}

function enviarCorreoFactura()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#enviandoCorreo').html('<img src="'+ img_loader +'"/> Se esta enviando el CFDI, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"facturacion/enviarFacturaAdjunta",
		data:
		{
			"idFactura":	$("#txtIdFacturaEnviar").val(),
			"email":		$("#txtCorreo").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(parseInt(data))
			{
				case 0:
				$('#enviandoCorreo').html("");
				notify('Error al enviar la factura',500,5000,'error',30,3);
				break;
				case 1:
				$('#enviandoCorreo').html("");
				notify('La factura se ha enviado correctamente',500,5000,'',30,3);
				$("#ventanaEnviarCorreo").dialog('close');
				break;
				case 2:
				$('#enviandoCorreo').html("");
				notify('Error al enviar la factura, por favor verifique los correos electrónicos',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			$('#enviandoCorreo').html("");
			notify('Error al enviar la factura',500,5000,'error',30,3);
		}
	});//Ajax	
}

function cancelarCFDI()
{
	mensaje="";
	
	if($('#selectMotivoCancelacion').val()=="01" && $('#txtIdFacturaSustitucion').val()=="0")
	{
		mensaje+=" Seleccione el folio de sustitución<br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea cancelar el CFDI?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cancelandoCfdi').html('<img src="'+ img_loader +'"/> Se esta cancelando el CFDI, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"facturacion/cancelarCFDI",
		data:$('#frmCancelar').serialize()+'&motivosCancelacionSat='+$('#selectMotivoCancelacion option:selected').text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cancelandoCfdi').html("");
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,7000,'error',30,3);
				break;
					
				case "1":
					window.location.href=base_url+"reportes/facturacion";
				break;
				
				
			}
		},
		error:function(datos)
		{
			$('#cancelandoCfdi').html("");
			notify('Error al cancelar el CFDI, por favor verifique que el CFDI no haya sido previamente cancelado',500,5000,'error',30,3);
		}
	});//Ajax	
}
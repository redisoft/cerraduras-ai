function ocultarEventos()
{
	$('#eventoNotificacion').fadeOut()
}

function quitarNotificacion(i,idProducto)
{
	if(confirm('¿Realmente desea quitar la notificación de este servicio?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#servicio'+i).html('<img src="'+ img_loader +'"/> \
			Quitando la notificación...');
		},
		type:"POST",
		url:base_url+'clientes/quitarNotificacion',
		data:
		{
			"idProducto":idProducto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#servicio'+i).fadeOut()
			//$('#servicio'+i).html('')
		},
		error:function(datos)
		{
			$('#servicio'+i).html('')
		}
	});		
}

function quitarNotificacionCobro(i,idIngreso)
{
	if(confirm('¿Realmente desea quitar la notificación del cobro?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cobro'+i).html('<img src="'+ img_loader +'"/>Quitando la notificación...');
		},
		type:"POST",
		url:base_url+'configuracion/quitarNotificacionCobro',
		data:
		{
			"idIngreso":idIngreso,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cobro'+i).fadeOut()
		},
		error:function(datos)
		{
			$('#cobro'+i).html('')
		}
	});		
}

function quitarNotificacionPago(i,idEgreso)
{
	if(confirm('¿Realmente desea quitar la notificación del pago?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#pago'+i).html('<img src="'+ img_loader +'"/>Quitando la notificación...');
		},
		type:"POST",
		url:base_url+'configuracion/quitarNotificacionPago',
		data:
		{
			"idEgreso":idEgreso,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#pago'+i).fadeOut()
		},
		error:function(datos)
		{
			$('#pago'+i).html('')
		}
	});		
}

function quitarNotificacionCompra(i,idCompras)
{
	if(confirm('¿Realmente desea quitar la notificación de la compra?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#compra'+i).html('<img src="'+ img_loader +'"/>Quitando la notificación...');
		},
		type:"POST",
		url:base_url+'configuracion/quitarNotificacionCompra',
		data:
		{
			"idCompras":idCompras,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#compra'+i).fadeOut()
		},
		error:function(datos)
		{
			$('#compra'+i).html('')
		}
	});		
}

function configurarNotificaciones(criterio)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){},
		type:"POST",
		url:base_url+"configuracion/configurarNotificaciones/",
		data:
		{
			criterio:criterio
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			
		},
		error:function(datos)
		{
			
		}
	});	
}

//Para la ficha del proveedor
$(document).ready(function()
{
	$("#ventanaFichaProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Imprimir': function()
			{
				window.location.href=base_url+'proveedores/fichaPdf/'+$('#txtIdProveedorFicha').val();	 
			},
			'Enviar': function()
			{
				formularioCorreoFicha();				 
			},
			'Aceptar': function()
			{
				$(this).dialog('close');				 
			}
		},
		close: function()
		{
			$('#cargarFichaProveedor').html('');
		}
	});
	
	$("#ventanaEnviarFichaProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:470,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Enviar': function()
			{
				enviarFichaProveedor();			 
			},
		},
		close: function()
		{
			$('#formularioCorreoFicha').html('');
		}
	});
});

function enviarFichaProveedor()
{
	var mensaje="";

	if($("#txtAsuntoFichaProveedor").val()=="")
	{
		mensaje+="Por favor escriba el asunto del correo <br />";										
	} 
	
	if($("#txtCorreoFichaProveedor").val()=="")
	{
		mensaje+="El correo es requerido <br />";									
	}
	
	if($("#txtMensajeCorreo").val()=="")
	{
		mensaje+="Escriba el mensaje <br />";												
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#enviandoFichaProveedor').html('<img src="'+ img_loader +'"/> Se esta enviando el correo, por favor espere...');},
		type:"POST",
		url:base_url+"proveedores/enviarFichaProveedor",
		data:
		{
			"asunto":		$("#txtAsuntoFichaProveedor").val(),
			"correo":		$("#txtCorreoFichaProveedor").val(),
			"mensaje":		$("#txtMensajeCorreo").val(),
			"idProveedor":	$('#txtIdProveedorFicha').val(),
			"idUsuario":	$('#selectUsuariosEnviar').val(),
			"firma":		$('#txtFirma').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#enviandoFichaProveedor").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al enviar el correo',500,4000,"error",30,5);
				break;
				case "1":
					notify('El correo se ha enviado correctamente',500,4000,"",30,5);
					$('#ventanaEnviarFichaProveedor').dialog('close');
				break;
			}//switch
		},
		error:function(datos)
		{
			$("#enviandoFichaProveedor").html('');
			notify('Error al enviar el correo',500,4000,"error",30,5);
		}
	});
}

function formularioCorreoFicha()
{
	$('#ventanaEnviarFichaProveedor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioCorreoFicha').html('<img src="'+ img_loader +'"/> Cargando detalles de formulario...');},
		type:"POST",
		url:base_url+"proveedores/formularioCorreoFicha",
		data:
		{
			idProveedor:$('#txtIdProveedorFicha').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCorreoFicha').html(data);
		},
		error:function(datos)
		{
			$('#formularioCorreoFicha').html('');
		}
	});	
}

function obtenerFichaTecnicaProveedor(id)
{
	$('#ventanaFichaProveedor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarFichaProveedor').html('<img src="'+ img_loader +'"/> Cargando la ficha tecnica del proveedor, por favor espere...');},
		type:"POST",
		url:base_url+"proveedores/buscarProveedor/"+id,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarFichaProveedor').html(data);
		},
		error:function(datos)
		{
			$('#cargarFichaProveedor').html('');
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaRedisoft").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
			$("#formularioNotaCredito").html('');
		}
	});
});


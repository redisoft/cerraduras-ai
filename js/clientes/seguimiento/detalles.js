$(document).ready(function()
{
	$("#ventanaDetallesSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:620,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function()
		{
			$("#detallesSeguimiento").html('');
		}
	});
})

function detallesSeguimiento(idSeguimiento)
{
	$('#ventanaDetallesSeguimiento').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#detallesSeguimiento').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimiento/',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#detallesSeguimiento').html(data)
		},
		error:function(datos)
		{
			$('#detallesSeguimiento').html('Error al obtener los detalles del seguimiento')
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaFormularioSeguimientoDetalle").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarDetalleSeguimiento()			 
			}
		},
		close: function()
		{
			$("#formularioSeguimientoDetalle").html('');
		}
	});
})

function formularioSeguimientoDetalle(idSeguimiento)
{
	$('#ventanaFormularioSeguimientoDetalle').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSeguimientoDetalle').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'crm/formularioSeguimientoDetalle',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSeguimientoDetalle').html(data)
			$('#txtObservacionesSeguimiento').focus();
		},
		error:function(datos)
		{
			$('#formularioSeguimientoDetalle').html('Error al obtener los detalles del seguimiento')
		}
	});		
}

function registrarDetalleSeguimiento()
{
	var mensaje="";

	if(!camposVacios($('#txtObservacionesSeguimiento').val()))
	{
		mensaje+='Las observaciones del seguimiento son requeridas ';
		
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoDetalleSeguimiento').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"crm/registrarDetalleSeguimiento",
		data:$('#frmDetallesSeguimiento').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoDetalleSeguimiento').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡Error en el registro!',500,3000,'error',30,5);
				break;
				
				case "1":
					notify('¡Registro correcto!',500,3000,'',30,5);
					$('#ventanaFormularioSeguimientoDetalle').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoDetalleSeguimiento').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function borrarDetalleSeguimiento(idDetalle)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoDetallesSeguimiento').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"crm/borrarDetalleSeguimiento",
		data:
		{
			idDetalle:idDetalle
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoDetallesSeguimiento').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡Error al borrar el registro!',500,3000,'error',30,5);
				break;
				
				case "1":
					detallesSeguimiento($('#txtIdSeguimiento').val())
					notify('¡El registro se ha borrado correctamente!',500,3000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#procesandoDetallesSeguimiento').html('')
			notify('Error al borrar el registro',500,5000,'error',0,0);
		}
	});					  	  
}




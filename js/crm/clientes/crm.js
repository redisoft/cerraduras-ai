detalleCita		= false;
tipoSeguimiento	= 'clientes';
Fecha			= '';
Hora1			= '';
Hora2			= '';

function tipoSeguimientoCrm()
{
	if($('#selectTipoSeguimiento').val()=='Clientes')
	{
		formularioCrmClientes(Fecha,Hora1,Hora2)
	}
	else
	{
		formularioCrmProveedores(Fecha,Hora1)
	}
}

function formularioCrmClientes(fecha,hora1,hora2)
{
	$("#selectTipoSeguimiento").val('Clientes');
	
	Fecha	= fecha;
	Hora1	= hora1;
	Hora2	= hora2;
	
	if(detalleCita)
	{
		return;	
	}
	
	$('#ventanaFormularioCrmCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCrmClientes').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'crm/formularioCrmClientes',
		data:
		{
			fecha:fecha,
			"hora1":hora1+':00',
			"hora2":hora2+':00',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCrmClientes').html(data)
		},
		error:function(datos)
		{
			$('#formularioCrmClientes').html('');
			notify('Error al obtener el formulario de seguimiento',500,5000,'error',30,5);
		}
	});		
}

$(document).ready(function()
{	
	$("#ventanaFormularioCrmCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				if($('#selectTipoSeguimiento').val()=='Clientes')
				{
					registrarCrmCliente();
				}
				else
				{
					registrarCrmProveedor();
				}
			},
		},
		close: function() 
		{
			$("#formularioCrmClientes").html('');
			detalleCita	= false;
		}
	});
});

function obtenerContactosCliente(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContactosCliente').html('<img src="'+ img_loader +'"/>Cargando lista de contactos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerContactosCliente',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactosCliente').html(data)
		},
		error:function(datos)
		{
			$('#obtenerContactosCliente').html('');
			notify('Error al obtener los contactos del cliente',500,5000,'error',30,5);
		}
	});		
}


function registrarCrmCliente()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');
	
	/*if($('#selectStatus').val()!="3")
	{
		if($('#txtComentarios').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
		}
	}
	
	if($('#selectStatus').val()=="3")
	{
		if($('#txtBitacora').val()=="")
		{
			mensaje+='La bitácora es requerida<br />';
		}
	}*/
	
	if(estatus[1]!="3")
	{
		if($('#txtComentarios').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
		}
	}
	
	if(estatus[1]=="3")
	{
		if($('#txtBitacora').val()=="")
		{
			mensaje+='La bitácora es requerida<br />';
		}
	}
	
	if($('#txtIdClienteCrm').val()=="0")
	{
		mensaje+='Seleccione el cliente<br />';
	}
	
	if($('#txtIdClienteBusquedaCrm').val()!="0")
	{
		if($('#txtIdClienteBusquedaCrm').val()!=$('#txtIdCliente').val())
		{
			mensaje+='El cliente no coincide con el de la cotización o venta<br />';
		}
	}
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
	if($('#txtFechaSeguimiento').val()=="")
	{
		mensaje+='Debe seleccionar una fecha <br />';
	}

	responsables	= new String($('#selectResponsable').val());
	responsable		= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea agregar el seguimiento?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoCrmCliente').html('<img src="'+ img_loader +'"/>Se esta registrando el seguimiento...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimiento",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"bitacora":			$("#txtBitacora").val(),
			"fecha":			$('#txtFechaSeguimiento').val() + ' ' + $('#txtHoraSeguimiento').val(),
			"idCliente":		$('#txtIdCliente').val(),
			//"idStatus":			$('#selectStatus').val(),
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			"idServicio":		$('#selectServicio').val(),
			"idResponsable":	responsable[0],
			"fechaCierre":		$('#txtFechaCierre').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugar').val(),
			"email":			$("#txtEmailSeguimiento").val(),
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCotizacion":		$("#txtIdCotizacionCrm").val(),
			"idVenta":			$("#txtIdVentaCrm").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCrmCliente').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El seguimiento se ha registrado correctamente',500,5000,'',30,5);
					//obtenerSeguimientoCliente($('#txtIdCliente').val());
					$('#ventanaFormularioCrmCliente').dialog('close');
					obtenerTablero();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoCrmCliente').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});				
}

function definirFechaTablero(fecha)
{
	$('#txtFechaActual').val(fecha)
	
	obtenerTablero()
}


function obtenerTablero()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTablero').html('<img src="'+ img_loader +'"/>Cargando detalles de tablero de control...');
		},
		type:"POST",
		url:base_url+'crm/obtenerTablero',
		data:
		{
			"fecha":	$('#txtFechaActual').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTablero').html(data)
		},
		error:function(datos)
		{
			$('#obtenerTablero').html('');
			notify('Error al obtener los detalles del tablero',500,5000,'error',30,5);
		}
	});		
}

function borrarSeguimientoCrm()
{
	if(!confirm('¿Realmente desea borrar el seguimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Se esta borrando el seguimiento de CRM...');
		},
		type:"POST",
		url:base_url+'clientes/borrarSeguimientoErp',
		data:
		{
			"idSeguimiento":$('#txtIdSeguimiento').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			notify('¡Seguimiento borrado!',500,5000,'',30,5);
			$("#ventanaEditarSeguimiento").dialog('close');
			obtenerTablero();
		},
		error:function(datos)
		{
			$('#editandoCrm').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}
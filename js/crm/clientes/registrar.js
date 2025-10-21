function formularioCrmClientes(fecha,hora1,hora2)
{
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
			fecha:		fecha,
			"hora1":	hora1+':00',
			"hora2":	hora2+':00',
			tipo: 		$('#txtTipoRegistro').val()=='prospectos'?'1':'0'
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
				registrarCrmCliente();
			},
		},
		close: function() 
		{
			$("#formularioCrmClientes").html('');
		}
	});
});

function registrarCrmCliente()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');

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
			
			
			"idUsuarioRegistro":			$("#selectUsuarioRegistro").val(),
			"idEstatus":			$("#selectEstatus").val(),
			
			"idConcepto":			$("#selectConcepto").val(),
			
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
					obtenerLlamadas();
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
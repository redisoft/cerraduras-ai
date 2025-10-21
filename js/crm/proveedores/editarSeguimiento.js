$(document).ready(function()
{
	$("#ventanaEditarSeguimientoProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:540,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Borrar': function() 
			{
				borrarSeguimientoCrmProveedor()			 
			},
			'Editar': function() 
			{
				editarSeguimientoCrmProveedor()			 
			},
			
		},
		close: function()
		{
			$("#obtenerSeguimientoEditarProveedor").html('');
			detalleCita	= false;
		}
	});
});

function obtenerSeguimientoEditarProveedor(idSeguimiento)
{
	detalleCita=true;
	
	$("#ventanaEditarSeguimientoProveedor").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoEditarProveedor').html('<img src="'+ img_loader +'"/> Obteniendo los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerSeguimientoEditar',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoEditarProveedor').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoEditarProveedor').html('');
			notify('Error al obtener el seguimiento',500,5000,'error',0,0);
		}
	});		
}

function editarSeguimientoCrmProveedor()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');

	if($('#txtFechaEditar').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
	}
	
	/*if($('#selectStatus').val()!="3")
	{
		if($('#txtComentariosEditar').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
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
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
	responsables	= $('#selectResponsable').val();
	responsable		= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar el seguimiento?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrmProveedor').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
		},
		type:"POST",
		url:base_url+"proveedores/editarSeguimientoCrm",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"observaciones":	$("#txtObservacionesEditar").val(),
			"fecha":			$('#txtFechaEditar').val()+' '+$('#txtHoraSeguimiento').val(),
			"fechaCierre":		$('#txtFechaCierreEditar').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugarEditar').val(),
			"idProveedor":		$('#txtIdProveedor').val(),
			//"idStatus":			$('#selectStatus').val(),
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			"idServicio":		$('#selectServicio').val(),
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			"idResponsable":	responsable[0],
			"bitacora":			$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			"idCompra":			$("#txtIdCompraCrm").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrmProveedor').html('');
			
			switch(data)
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡Seguimiento editado!',500,5000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrmProveedor').html('')
			notify('Error al editar el seguimiento',500,5000,'error',30,5);
		}
	});					  	  
}

function borrarSeguimientoCrmProveedor()
{
	if(!confirm('¿Realmente desea borrar el seguimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrmProveedor').html('<img src="'+ img_loader +'"/> Se esta borrando el seguimiento...');
		},
		type:"POST",
		url:base_url+'proveedores/borrarSeguimiento',
		data:
		{
			"idSeguimiento":$('#txtIdSeguimiento').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('¡Seguimiento borrado!',500,5000,'',0,0);
			$('#editandoCrmProveedor').html('');
			$('#ventanaEditarSeguimientoProveedor').dialog('close');
			obtenerTablero();
		},
		error:function(datos)
		{
			$('#siguiendoClientes').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}
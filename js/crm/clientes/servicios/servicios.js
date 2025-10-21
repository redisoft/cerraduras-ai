//==================================================================================================//
//===================================       SEGUIMIENTOS        ====================================//
//==================================================================================================//
function obtenerSeguimientoServicio(idCotizacion,idSeguimiento)
{
	$('#ventanaSeguimientoServicio').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoServicio').html('<img src="'+ img_loader +'"/>Cargando la lista de seguimientos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerSeguimientoServicio',
		data:
		{
			"idServicio":	$('#txtIdServicioCrm').val(),
			"idCliente":	$('#txtIdClienteCrm').val(),
			inicio:			$('#txtInicioSeguimiento').val(),
			fin:			$('#txtFinSeguimiento').val(),
			idCotizacion:	idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoServicio').html(data);
			
			if(idSeguimiento>0)
			{
				
				
				window.setTimeout(function() 
				{
					detallesSeguimiento(idSeguimiento)
				}, 1100);  
			}
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoServicio').html('');
			notify('Error al obtener la lista de seguimientos',500,5000,'error',30,5);
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaSeguimientoServicio").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Registrar': function() 
			{
				if($('#txtRegistrarCrm').val()=="1")
				{
					formularioSeguimientoServicios()			 
				}
				else
				{
					notify('Sin permisos para registrar',500,5000,'error',30,5);
				}
			}
		},
		close: function()
		{
			$("#obtenerSeguimientoServicio").html('');
		}
	});
	
	//$('.ajax-pagSeguimiento > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagSeguimiento > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerSeguimientoServicio";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idServicio":	$('#txtIdServicioCrm').val(),
				"idCliente":	$('#txtIdClienteCrm').val(),
				inicio:			$('#txtInicioSeguimiento').val(),
				fin:			$('#txtFinSeguimiento').val(),
				idCotizacion:	$('#txtIdCotizacionSeguimiento').val()!='0'?$('#txtIdCotizacionSeguimiento').val():$('#txtIdVentaSeguimiento').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});//.ajax
});



//PARA EL SEGUIMIENTO
$(document).ready(function()
{
	$("#ventanaFormularioSeguimientoServicios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:720,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			/*'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},*/
			'Aceptar': function() 
			{
				registrarSeguimientoServicios();
			}
		},
		close: function()
		{
			$("#formularioSeguimientoServicios").html('');
		}
	});
});

function registrarSeguimientoServicios()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');

	if(!camposVacios($('#txtComentarios').val()))
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
	if(!camposVacios($('#txtFechaSeguimiento').val()))
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
			$('#registrandoSeguimientoServicios').html('<img src="'+ img_loader +'"/>Se esta registrando un seguimiento...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimiento",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"bitacora":			'',
			
			"fecha":			$('#txtFechaSeguimiento').val() + ' ' + $('#txtHoraSeguimiento').val(),
			"idCliente":		$('#txtIdClienteSeguimiento').val(),
			"idStatus":			$('#selectStatus').val(),
			"idStatusIgual":	0,
			"idServicio":		$('#txtIdServicioCrm').val(),
			"idResponsable":	responsable[0],
			"fechaCierre":		$('#txtFechaCierre').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugar').val(),
			"email":			$("#txtEmailSeguimiento").val(),
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCotizacion":		$("#txtIdCotizacionSeguimiento").val(),
			"idVenta":			$("#txtIdVentaSeguimiento").val(),
			
			"bitacora":			'',//$("#txtBitacora").val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoSeguimientoServicios').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El seguimiento se ha registrado correctamente',500,5000,'',30,5);
					obtenerSeguimientoServicio($('#txtIdCotizacionSeguimiento').val()!='0'?$('#txtIdCotizacionSeguimiento').val():$('#txtIdVentaSeguimiento').val());
					$('#ventanaFormularioSeguimientoServicios').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoSeguimientoServicios').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});				
}

function formularioSeguimientoServicios()
{
	$('#ventanaFormularioSeguimientoServicios').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSeguimiento').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para seguimiento, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/formularioSeguimientoServicios',
		data:
		{
			"idCliente":	$('#txtIdClienteSeguimiento').val(),
			"idServicio":	$('#txtIdServicioCrm').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSeguimientoServicios').html(data);
		},
		error:function(datos)
		{
			$('#formularioSeguimientoServicios').html('');
			notify('Error al obtener el formulario para seguimiento',500,5000,'error',0,0);
		}
	});		
}


$(document).ready(function()
{
	$("#ventanaFormularioSeguimientoEditar").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:720,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Editar': function() 
			{
				editarSeguimientoServicios();
			}
		},
		close: function()
		{
			$("#obtenerSeguimientoEditarServicio").html('');
		}
	});
});


function obtenerSeguimientoEditar(idSeguimiento)
{
	$('#ventanaFormularioSeguimientoEditar').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoEditarServicio').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para seguimiento, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerSeguimientoEditarServicio',
		data:
		{
			"idSeguimiento":	idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoEditarServicio').html(data);
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoEditarServicio').html('');
			notify('Error al obtener el formulario para seguimiento',500,5000,'error',0,0);
		}
	});		
}

function editarSeguimientoServicios()
{
	var mensaje="";
	
	status		= $('#selectStatus').val();
	estatus		= status.split('|');
	
	if($('#txtFechaEditar').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
	}
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
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
	
	responsables	= $('#selectResponsable').val();
	responsable		= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar el seguimiento CRM?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoSeguimientoServicios').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
		},
		type:"POST",
		url:base_url+"clientes/editarSeguimientoCrm",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"observaciones":	$("#txtObservacionesEditar").val(),
			"fecha":			$('#txtFechaEditar').val()+' '+$('#txtHoraSeguimiento').val(),
			"fechaCierre":		$('#txtFechaCierreEditar').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugarEditar').val(),
			"idStatusIgual":	0,
			"idCliente":		$('#txtIdClienteSeguimiento').val(),
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			"idServicio":		$('#selectServicio').val(),
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			"idResponsable":	responsable[0],
			
			"bitacora":			'',//$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
			
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCotizacion":		$("#txtIdCotizacionSeguimiento").val(),
			"idVenta":			$("#txtIdVentaSeguimiento").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoSeguimientoServicios').html('');
			
			switch(data)
			{
				case "0":
					
					notify('¡Error al editar el seguimiento!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡Seguimiento registrado!',500,5000,'',0,0);
					$('#editandoCrm').html('');
					obtenerSeguimientoServicio($('#txtIdCotizacionSeguimiento').val()!='0'?$('#txtIdCotizacionSeguimiento').val():$('#txtIdVentaSeguimiento').val());
					$('#ventanaFormularioSeguimientoEditar').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoSeguimientoServicios').html('')
			notify('Error al editar el seguimiento',500,5000,'error',0,0);
		}
	});					  	  
}

function borrarSeguimientoCrm(idSeguimiento)
{
	if(!confirm('¿Realmente desea borrar el seguimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoSeguimientoServicio').html('<img src="'+ img_loader +'"/>Se esta borrando el seguimiento de CRM...');
		},
		type:"POST",
		url:base_url+'clientes/borrarSeguimientoErp',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('¡Seguimiento borrado!',500,5000,'',30,5);
			$('#procesandoSeguimientoServicio').html('');
			obtenerSeguimientoServicio($('#txtIdCotizacionSeguimiento').val()!='0'?$('#txtIdCotizacionSeguimiento').val():$('#txtIdVentaSeguimiento').val());
		},
		error:function(datos)
		{
			$('#procesandoSeguimientoServicio').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}
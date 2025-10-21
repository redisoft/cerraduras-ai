//==================================================================================================//
//===================================       SEGUIMIENTOS        ====================================//
//==================================================================================================//
function seguimientoProveedores(idProveedor)
{
	$('#ventanaSeguimientoProveedores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#seguimientoProveedores').html('<img src="'+ img_loader +'"/>Cargando la lista de seguimientos...');
		},
		type:"POST",
		url:base_url+'proveedores/seguimientoProveedores',
		data:
		{
			"idProveedor":	idProveedor,
			inicio:			$('#txtInicioSeguimiento').val(),
			fin:			$('#txtFinSeguimiento').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#seguimientoProveedores').html(data)
		},
		error:function(datos)
		{
			$('#seguimientoProveedores').html('');
			notify('Error al obtener la lista de seguimientos',500,5000,'error',0,0);
		}
	});		
}

function obtenerSeguimientoProveedorFechas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#seguimientoProveedores').html('<img src="'+ img_loader +'"/>Cargando la lista de seguimientos...');
		},
		type:"POST",
		url:base_url+'proveedores/seguimientoProveedores',
		data:
		{
			idProveedor:  	$('#txtIdProveedor').val(),
			inicio:			$('#txtInicioSeguimiento').val(),
			fin:			$('#txtFinSeguimiento').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#seguimientoProveedores').html(data)
		},
		error:function(datos)
		{
			$('#seguimientoProveedores').html('');
			notify('Error al obtener la lista de seguimientos',500,5000,'error',0,0);
		}
	});		
}
	
function borrarSeguimientoCrm(idSeguimiento)
{
	if(confirm('¿Realmente desea borrar el seguimiento de CRM?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#siguiendoClientes').html('<img src="'+ img_loader +'"/> \Se esta borrando el seguimiento de CRM...');
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
			notify('¡Seguimiento borrado!',500,5000,'',0,0);
			$('#siguiendoClientes').html('');
			obtenerSeguimientoCliente($('#txtIdCliente').val());
		},
		error:function(datos)
		{
			$('#siguiendoClientes').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',30,5);
		}
	});		
}


$(document).ready(function()
{
	$("#ventanaSeguimientoProveedores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Agregar': function() 
			{
				if($("#txtCrmRegistrar").val()=="1")
				{
					formularioSeguimiento()			 
				}
				else
				{
					notify('Sin permisos para registrar',500,5000,'error',30,5);
				}
			}
		},
		close: function()
		{
			$("#cargarSeguimiento").html('');
		}
	});
	
	//$('.ajax-pagSeguimiento > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagSeguimiento > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#cargarSeguimiento";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idCliente:	$('#txtIdCliente').val(),
				inicio:		$('#txtInicioSeguimiento').val(),
				fin:		$('#txtFinSeguimiento').val()
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
	
	$("#ventanaSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Guardar': function() 
			{
					  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#errorSeguimiento").html('');
		}
	});
});

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//REGISTRAR SEGUIMIENTO
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$(document).ready(function()
{
	$("#ventanaFormularioSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:460,
		width:720,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarSeguimiento();
			}
		},
		close: function()
		{
			$("#formularioSeguimiento").html('');
		}
	});
});

	
function formularioSeguimiento()
{
	$('#ventanaFormularioSeguimiento').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSeguimiento').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para seguimiento, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/formularioSeguimiento',
		data:
		{
			"idProveedor":$('#txtIdProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSeguimiento').html(data);
		},
		error:function(datos)
		{
			$('#formularioSeguimiento').html('');
			notify('Error al obtener el formulario para seguimiento',500,5000,'error',0,0);
		}
	});		
}

function registrarSeguimiento()
{
	/*var mensaje="";
	
	if($('#selectStatus').val()!="3")
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
	
	if(confirm('¿Realmente desea agregar el seguimiento?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoSeguimiento').html('<img src="'+ img_loader +'"/>Se esta registrando un seguimiento...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarSeguimiento",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"bitacora":			$("#txtBitacora").val(),
			
			"fecha":			$('#txtFechaSeguimiento').val() + ' ' + $('#txtHoraSeguimiento').val(),
			"idProveedor":		$('#txtIdProveedor').val(),
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
			"idCompra":			$("#txtIdCompraCrm").val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoSeguimiento').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					seguimientoProveedores($('#txtIdProveedor').val());
					$('#ventanaFormularioSeguimiento').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoSeguimiento').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',30,5);
		}
	});				
}

	
$(document).ready(function()
{
	$("#ventanaEditarSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:540,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				editarSeguimientoCrm()			 
			}
		},
		close: function()
		{
			$("#obtenerSeguimientoEditar").html('');
		}
	});
});

function obtenerSeguimientoEditar(idSeguimiento)
{
	$("#ventanaEditarSeguimiento").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoEditar').html('<img src="'+ img_loader +'"/> Obteniendo los detalles del seguimiento...');
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
			$('#obtenerSeguimientoEditar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoEditar').html('');
			notify('Error al obtener el seguimiento',500,5000,'error',0,0);
		}
	});		
}

function editarSeguimientoCrm()
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
	responsable	= responsables.split("|");
	
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
			$('#editandoSeguimiento').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
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
			$('#editandoSeguimiento').html('');
			
			switch(data)
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡Seguimiento registrado!',500,5000,'',30,5);
					$('#editandoSeguimiento').html('');
					seguimientoProveedores($('#txtIdProveedor').val());
					$('#ventanaEditarSeguimiento').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error al editar el seguimiento',500,5000,'error',30,5);
		}
	});					  	  
}


function borrarSeguimiento(idSeguimiento)
{
	if(!confirm('¿Realmente desea borrar el seguimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#siguiendoClientes').html('<img src="'+ img_loader +'"/> Se esta borrando el seguimiento de CRM...');
		},
		type:"POST",
		url:base_url+'proveedores/borrarSeguimiento',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('¡Seguimiento borrado!',500,5000,'',0,0);
			$('#siguiendoClientes').html('');
			seguimientoProveedores($('#txtIdProveedor').val());
		},
		error:function(datos)
		{
			$('#siguiendoClientes').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}
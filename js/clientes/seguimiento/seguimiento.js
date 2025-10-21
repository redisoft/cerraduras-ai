//==================================================================================================//
//===================================       SEGUIMIENTOS        ====================================//
//==================================================================================================//
function obtenerSeguimientoCliente(idCliente)
{
	$('#ventanaSeguimientoClientes').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarSeguimiento').html('<img src="'+ img_loader +'"/>Cargando la lista de seguimientos...');
		},
		type:"POST",
		url:base_url+'clientes/seguimientoClientes',
		data:
		{
			"idCliente":	idCliente,
			inicio:			$('#txtInicioSeguimiento').val(),
			fin:			$('#txtFinSeguimiento').val(),
			tipo: 			$('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarSeguimiento').html(data)
		},
		error:function(datos)
		{
			$('#cargarSeguimiento').html('');
			notify('Error al obtener la lista de seguimientos',500,5000,'error',0,0);
		}
	});		
}

function obtenerSeguimientoClienteFechas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarSeguimiento').html('<img src="'+ img_loader +'"/>Cargando la lista de seguimientos...');
		},
		type:"POST",
		url:base_url+'clientes/seguimientoClientes',
		data:
		{
			idCliente:  $('#txtIdCliente').val(),
			inicio:		$('#txtInicioSeguimiento').val(),
			fin:		$('#txtFinSeguimiento').val(),
			tipo: 			$('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarSeguimiento').html(data)
		},
		error:function(datos)
		{
			$('#cargarSeguimiento').html('');
			notify('Error al obtener la lista de seguimientos',500,5000,'error',0,0);
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
			$('#siguiendoClientes').html('<img src="'+ img_loader +'"/>Se esta borrando el seguimiento de CRM...');
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
			obtenerClientes();
		},
		error:function(datos)
		{
			$('#siguiendoClientes').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}


$(document).ready(function()
{
	$("#ventanaSeguimientoClientes").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Registrar': function() 
			{
				if($('#txtRegistrarCrm').val()=="1")
				{
					if($('#txtTipoRegistro').val()=='prospectos')
					{
						if(obtenerNumeros($('#txtNumeroSeguimientosProspecto').val())>0) 
						{
							notify('Solo es posible registrar un seguimiento',500,5000,'error',30,5);
							return;
						}
					}
					
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

	/*$("#agregarSeguimiento").click(function(e)
	{
		$('#ventanaSeguimiento').dialog('open');
	});*/
	
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


$(document).ready(function()
{
	for(i=1;i<100;i++)
	{
		$("#btnNotas"+i).click(function(e)
		{
			$('#ventanaNotas').dialog('open');
		});
	}
	
	$("#ventanaNotas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:750,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				formularioRegistrarNota();
				$('#ventanaRegistrarNota').dialog('open');		 
			}
		},
		close: function()
		{
			$("#obtenerNotas").html('');
		}
	});
	
	$("#ventanaRegistrarNota").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:550,
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
				registrarNota();
			}
		},
		close: function()
		{
			$("#formularioRegistrarNota").html('');
		}
	});
	
	
	$("#ventanaEditarNota").dialog(
	{
		autoOpen:false,
		height:250,
		width:550,
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
				editarNota();
			}
		},
		close: function()
		{
			$("#obtenerNota").html('');
		}
	});
});

function editarNota()
{
	var mensaje="";

	if($('#txtFechaNota').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
	}

	if($('#txtComentariosNotas').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro de la nota?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNota').html('<img src="'+ img_loader +'"/> Editando la nota, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/editarNota',
		data:
		{
			"idNota":			$('#txtIdNota').val(),
			"idResponsable":	$('#selectResponsableNota').val(),
			"fecha":			$('#txtFechaNota').val(),
			"comentarios":		$('#txtComentariosNotas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNota').html('');
			notify('La nota se ha editado correctamente',500,5000,'',0,0);
			obtenerNotas($('#txtIdCliente').val());
			$('#ventanaEditarNota').dialog('close');
			
		},
		error:function(datos)
		{
			$('#editandoNota').html('');
			notify('Error al editar la nota',500,5000,'error',0,0);
		}
	});		
}

function obtenerNota(idNota)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNota').html('<img src="'+ img_loader +'"/> Obteniendo los detalles de la nota, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerNota',
		data:
		{
			"idNota":idNota,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNota').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNota').html('');
			notify('Error al obtener los detalles de la nota',500,5000,'error',0,0);
		}
	});		
}

function registrarNota()
{
	var mensaje="";

	if($('#txtFechaNota').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
	}

	if($('#txtComentariosNotas').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea registrar la nota?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoNota').html('<img src="'+ img_loader +'"/> Registrando la nota, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/registrarNota',
		data:
		{
			"idCliente":		$('#txtIdCliente').val(),
			"idResponsable":	$('#selectResponsableNota').val(),
			"fecha":			$('#txtFechaNota').val(),
			"comentarios":		$('#txtComentariosNotas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoNota').html('');
			notify('La nota se ha registrado correctamente',500,5000,'',0,0);
			obtenerNotas($('#txtIdCliente').val());
			$('#ventanaRegistrarNota').dialog('close');
			
		},
		error:function(datos)
		{
			$('#registrandoNota').html('');
			notify('Error al registrar la nota',500,5000,'error',0,0);
		}
	});		
}


function formularioRegistrarNota()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRegistrarNota').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para notas, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/formularioRegistrarNota',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRegistrarNota').html(data)
		},
		error:function(datos)
		{
			$('#formularioRegistrarNota').html('');
			notify('Error al obtener el formulario para notas',500,5000,'error',0,0);
		}
	});		
}

function obtenerNotas(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNotas').html('<img src="'+ img_loader +'"/> Cargando la lista de notas del cliente, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerNotas',
		data:
		{
			"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNotas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNotas').html('');
			notify('Error al obtener la lista de notas del cliente',500,5000,'error',0,0);
		}
	});		
}

function borrarNota(idNota)
{
	if(confirm('¿Realmente desea borrar la nota?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#actualizandoNotas').html('<img src="'+ img_loader +'"/> Se esta borrando la nota por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/borrarNota',
		data:
		{
			"idNota":idNota,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('¡Nota borrada!',500,5000,'',0,0);
			$('#actualizandoNotas').html('');
			obtenerNotas($('#txtIdCliente').val());
		},
		error:function(datos)
		{
			$('#actualizandoNotas').html('');
			notify('Error al borrar la nota',500,5000,'error',0,0);
		}
	});		
}
 



//PARA PROYECTOS
$(document).ready(function()
{
	$("#ventanaProyectos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
			'Agregar': function() 
			{
				formularioProyectos();
			}
		},
		close: function()
		{
			$("#obtenerProyectos").html('');
		}
	});
	
	$("#ventanaFormularioProyectos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:670,
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
				registrarProyecto();
			}
		},
		close: function()
		{
			$("#formularioProyectos").html('');
		}
	});
	
	$("#ventanaEditarProyecto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:670,
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
				editarProyecto();
			}
		},
		close: function()
		{
			$("#obtenerProyecto").html('');
		}
	});
});


function borrarProyecto(idSeguimiento)
{
	if(!confirm('¿Realmente desea borrar el proyecto?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#actualizandoProyectos').html('<img src="'+ img_loader +'"/> Borrando proyecto, por favor espere...');
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
			switch(data)
			{
				case "0":
				$('#actualizandoProyectos').html('')
				notify('¡Error al borrar el proyecto!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El proyecto se ha borrado correctamente!',500,5000,'',0,0);
				$('#actualizandoProyectos').html('');
				obtenerProyectos($('#txtIdCliente').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#actualizandoProyectos').html('');
			notify('¡Error al borrar el proyecto!',500,5000,'error',0,0);
		}
	});		
}

function obtenerProyecto(idSeguimiento)
{
	$('#ventanaEditarProyecto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProyecto').html('<img src="'+ img_loader +'"/> Obteniendo proyecto, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerProyecto',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProyecto').html(data);
		},
		error:function(datos)
		{
			$('#obtenerProyecto').html('');
			notify('Error al obtener el proyecto',500,5000,'error',0,0);
		}
	});		
}

function editarProyecto()
{
	var mensaje="";

	if($('#txtFechaProyecto').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
		
	}
	
	if($('#txtProyecto').val()=="")
	{
		mensaje+='El proyecto es requerido <br />';
	}
	
	if($('#txtAvance').val()=="")
	{
		mensaje+='El porcentaje de avance es requerido <br />';
	}
	
	if($('#txtComentariosErp').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoProyecto').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"clientes/editarProyecto",
		data:
		{
			"comentarios":	$("#txtComentariosProyecto").val(),
			"proyecto":		$("#txtProyecto").val(),
			"avance":		$("#txtAvance").val(),
			"fecha":		$('#txtFechaProyecto').val(),
			"tiempo":		$('#selectTiempo').val(),
			"meta":			$('#txtMeta').val(),
			"idSeguimiento":$('#txtIdSeguimiento').val(),
			"idStatus":		$('#selectStatusProyecto').val(),
			"idResponsable":$('#selectResponsableProyecto').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#editandoProyecto').html('');
				notify('¡Error en el registro!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡Registro correcto!',500,5000,'',0,0);
				$('#editandoProyecto').html('');
				obtenerProyectos($('#txtIdCliente').val());
				$('#ventanaEditarProyecto').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoProyecto').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function registrarProyecto()
{
	var mensaje="";

	if($('#txtFechaProyecto').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
		
	}
	
	if($('#txtProyecto').val()=="")
	{
		mensaje+='El proyecto es requerido <br />';
	}
	
	if($('#txtAvance').val()=="")
	{
		mensaje+='El porcentaje de avance es requerido <br />';
	}
	
	if($('#txtComentariosErp').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea realizar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProyecto').html('<img src="'+ img_loader +'"/> Realizando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"clientes/registrarProyecto",
		data:
		{
			"comentarios":	$("#txtComentariosProyecto").val(),
			"proyecto":		$("#txtProyecto").val(),
			"avance":		$("#txtAvance").val(),
			"fecha":		$('#txtFechaProyecto').val(),
			"tiempo":		$('#selectTiempo').val(),
			"meta":			$('#txtMeta').val(),
			"idCliente":	$('#txtIdCliente').val(),
			"idStatus":		$('#selectStatusProyecto').val(),
			"idResponsable":$('#selectResponsableProyecto').val(),
			"tipo":			3,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#registrandoProyecto').html('');
				notify('¡Error en el registro!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡Registro correcto!',500,5000,'',0,0);
				$('#registrandoProyecto').html('');
				obtenerProyectos($('#txtIdCliente').val());
				$('#ventanaFormularioProyectos').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoProyecto').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}


function formularioProyectos()
{
	$('#ventanaFormularioProyectos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProyectos').html('<img src="'+ img_loader +'"/> Obteniendo formulario para proyectos, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/formularioProyectos',
		data:
		{
			//"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProyectos').html(data);
		},
		error:function(datos)
		{
			$('#formularioProyectos').html('');
			notify('Error al obtener el formulario para proyectos',500,5000,'error',0,0);
		}
	});		
}

function obtenerProyectos(idCliente)
{
	$('#ventanaProyectos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProyectos').html('<img src="'+ img_loader +'"/> Obteniendo proyectos, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerProyectos',
		data:
		{
			"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProyectos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerProyectos').html('');
			notify('Error al obtener los proyectos',500,5000,'error',0,0);
		}
	});		
}

//PARA EL SEGUIMIENTO
$(document).ready(function()
{
	$("#ventanaFormularioSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
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
		url:base_url+'clientes/formularioSeguimiento',
		data:
		{
			"idCliente":	$('#txtIdCliente').val(),
			tipo: 			$('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSeguimiento').html(data);
			//obtenerEstatus()
		},
		error:function(datos)
		{
			$('#formularioSeguimiento').html('');
			notify('Error al obtener el formulario para seguimiento',500,5000,'error',0,0);
		}
	});		
}

function editarSeguimientoCrm()
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
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
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

			"idCliente":		$('#txtIdCliente').val(),
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			"idServicio":		$('#selectServicio').val(),
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			"idResponsable":	responsable[0],
			
			"bitacora":			$("#txtBitacora").val(),
			"email":			$("#txtEmailSeguimiento").val(),
			
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCotizacion":		$("#txtIdCotizacionCrm").val(),
			"idVenta":			$("#txtIdVentaCrm").val(),
			
			"idEstatus":		$("#selectEstatus").val(),
			"idUsuarioRegistro":$("#selectUsuarioRegistro").val(),
			
			"horaCierreFin":		$("#txtHoraCierreFin").val(),
			"alerta":				document.getElementById('chkAlertaSeguimiento').checked?'1':'0',
			
			"idConcepto":			$("#selectConcepto").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
					$('#editandoCrm').html('');
					notify('¡Error al editar el seguimiento!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡Seguimiento registrado!',500,5000,'',0,0);
					$('#editandoCrm').html('');
					obtenerSeguimientoCliente($('#txtIdCliente').val());
					$('#ventanaEditarSeguimiento').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error al editar el seguimiento',500,5000,'error',0,0);
		}
	});					  	  
}

function registrarSeguimiento()
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
			$('#cargandoSeguimiento').html('<img src="'+ img_loader +'"/>Se esta registrando un seguimiento...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimiento",
		data:
		{
			"comentarios":			$("#txtComentarios").val(),
			"bitacora":				$("#txtBitacora").val(),
			
			"fecha":				$('#txtFechaSeguimiento').val() + ' ' + $('#txtHoraSeguimiento').val(),
			"idCliente":			$('#txtIdCliente').val(),
			"idStatus":				estatus[0],
			"idStatusIgual":		estatus[1],
			"idServicio":			$('#selectServicio').val(),
			"idResponsable":		responsable[0],
			"fechaCierre":			$('#txtFechaCierre').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":				$('#txtLugar').val(),
			"email":				$("#txtEmailSeguimiento").val(),
			"idTiempo":				$("#selectTiempo").val(),
			"idContacto":			$("#selectContactos").val(),
			
			"idCotizacion":			$("#txtIdCotizacionCrm").val(),
			"idVenta":				$("#txtIdVentaCrm").val(),
			
			"idEstatus":			$("#selectEstatus").val(),
			"idUsuarioRegistro":	$("#selectUsuarioRegistro").val(),
			tipo: 					$('#txtTipoRegistro').val()=='prospectos'?'1':'0',
			
			"horaInicial":			$("#txtHoraCierre").val(),
			"horaCierreFin":		$("#txtHoraCierreFin").val(),
			"alerta":				document.getElementById('chkAlertaSeguimiento').checked?'1':'0',
			
			archivos: 				$('#txtTipoRegistro').val()=='prospectos'?ArchivosGlobal:null,
			"id":					$("#txtIdImagenes").val(),
			
			"idConcepto":			$("#selectConcepto").val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoSeguimiento').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
				
					if($('#txtTipoRegistro').val()=='prospectos')
					{
						obtenerClientes();	
						
						if(obtenerNumeros($('#txtAtrasosDisponible').val())==1)
						{
							obtenerAtrasos();
						}
					}
					
					notify('El seguimiento se ha registrado correctamente',500,5000,'',30,5);
					obtenerSeguimientoCliente($('#txtIdCliente').val());
					$('#ventanaFormularioSeguimiento').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoSeguimiento').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});				
}
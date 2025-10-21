//SERVICIOS
//=========================================================================================================================================//
function obtenerPromotores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPromotores').html('<img src="'+ img_loader +'"/> Obteniendo los registros.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPromotores',
		data:
		{
			criterio: 	$('#txtBuscarPromotor').val(),
			idUsuario: 	$('#selectPromotoresBusqueda').val(),
			idCampana: 	$('#selectCampanasBusqueda').val()
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$("#obtenerPromotores").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Promotores',500,5000,'error',30,3);
			$("#obtenerPromotores").html('');
		}
	});
}

function obtenerPromotoresAsignados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPromotoresAsignados').html('<img src="'+ img_loader +'"/> Obteniendo los registros.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPromotoresAsignados',
		data:
		{
			idPromotor: $('#selectPromotores').val(),
			idCampana: 	$('#selectCampanas').val()
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$("#obtenerPromotoresAsignados").html(data);
			$("#txtAsignados").val(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Promotores',500,5000,'error',30,3);
			$("#obtenerPromotoresAsignados").html('');
		}
	});
}

function formularioPromotores()
{
	$('#ventanaPromotores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPromotores').html('<img src="'+ img_loader +'"/> Obteniendo los datos el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioPromotores',
		data:
		{

		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$("#formularioPromotores").html(data);
			obtenerPromotoresAsignados()
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para el registro',500,5000,'error',30,3);
			$("#formularioPromotores").html('');
		}
	});
}

function registrarPromotores()
{
	mensaje="";
	
	meta	= obtenerNumeros($('#txtMeta').val());
	asignados	= obtenerNumeros($('#txtAsignados').val());
	
	if(meta==0 || meta > asignados  )
	{
		mensaje+="La meta es incorrecta <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoPromotores').html('<img src="'+ img_loader +'"/> Registrando, por favor espere..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarPromotores',
		data:
		{
			idUsuario: 				$('#selectPromotores').val(),
			idCampana: 				$('#selectCampanas').val(),
			meta: 					$('#txtMeta').val(),
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$('#registrandoPromotores').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					obtenerPromotores();
					$('#ventanaPromotores').dialog('close');
					notify(data[1],500,3000,'',30,5);
					$('#txtPromotoresEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el promotor',500,5000,'error',30,3);
			$("#registrandoPromotores").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaPromotores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:650,
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
				registrarPromotores();
			},
		},
		close: function() 
		{
			$("#formularioPromotores").html(''); 
		}
	});

	$("#ventanaEditarPromotores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:650,
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
				editarPromotores();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerPromotoresEditar').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagPromotores > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPromotores";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarPromotor').val(),
				idUsuario: 	$('#selectPromotoresBusqueda').val(),
				idCampana: 	$('#selectCampanasBusqueda').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPromotores').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
			},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
});

function obtenerPromotoresEditar(idPromotor)
{
	$('#ventanaEditarPromotores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el promotor..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPromotoresEditar',
		data:
		{
			idPromotor:idPromotor
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$("#obtenerPromotoresEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el promotor',500,5000,'error',30,3);
			$("#obtenerPromotoresEditar").html('');
		}
	});
}

function editarPromotores()
{
	mensaje="";
	
	if(!camposVacios($('#txtPromotor').val()))
	{
		mensaje+="El nombre del promotor es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del promotor?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPromotores').html('<img src="'+ img_loader +'"/> Editando el promotor, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarPromotores',
		data:
		{
			nombre: 					$('#txtPromotor').val(),
			idPromotor: 				$('#txtIdPromotor').val(),
			cantidadInscripcion: 		$('#txtPeriodicidadInscripcion').val(),
			cantidadColegiatura: 		$('#txtPeriodicidadColegiatura').val(),
			cantidadReinscripcion: 		$('#txtPeriodicidadReinscripcion').val(),
			editarAlumnos: 				document.getElementById('chkPromotorAlumnos').checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$('#editandoPromotores').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarPromotores').dialog('close');
					notify('El Promotor se ha editado correctamente',500,3000,'',30,5);
					obtenerPromotores();
					$('#txtPromotoresEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el promotor',500,5000,'error',30,3);
			$("#editandoPromotores").html('');
		}
	});		
}

function borrarPromotores(idMeta)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPromotores').html('<img src="'+ img_loader +'"/> Borrando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarPromotores',
		data:
		{
			idMeta: 	idMeta,
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$('#procesandoPromotores').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaPromotores'+idMeta).remove();
					$('#txtPromotoresEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#procesandoPromotores").html('');
		}
	});		
}
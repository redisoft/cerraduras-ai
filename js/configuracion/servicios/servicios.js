//SERVICIOS
//=========================================================================================================================================//
function obtenerServicios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicios').html('<img src="'+ img_loader +'"/> Obteniendo la lista de servicios.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerListaServicios',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerServicios").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de servicios',500,5000,'error',30,3);
			$("#obtenerServicios").html('');
		}
	});
}

function formularioServicios()
{
	$('#ventanaServicios').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioServicios').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el servicio..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioServicios',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioServicios").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el servicio',500,5000,'error',30,3);
			$("#formularioServicios").html('');
		}
	});
}

function registrarServicio()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoServicio').html('<img src="'+ img_loader +'"/> Registrandos el servicio, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarServicio',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			cliente: 	$('#selectTipoServicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoServicio').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerServicios();
					$('#ventanaServicios').dialog('close');
					notify('El servicio se ha registrado correctamente',500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el servicio',500,5000,'error',30,3);
			$("#registrandoServicio").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaServicios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				registrarServicio();
			},
		},
		close: function() 
		{
			$("#formularioServicios").html(''); 
		}
	});

	$("#ventanaEditarServicio").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				editarServicio();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerServicio').html('');
		}
	});
});

function obtenerServicio(idServicio)
{
	$('#ventanaEditarServicio').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el servicio..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerServicio',
		data:
		{
			idServicio:idServicio
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerServicio").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el servicio',500,5000,'error',30,3);
			$("#obtenerServicio").html('');
		}
	});
}

function editarServicio()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del servicio?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoServicio').html('<img src="'+ img_loader +'"/> Editando el servicio, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarServicio',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			idServicio: 	$('#txtIdServicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoServicio').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarServicio').dialog('close');
					notify('El servicio se ha editado correctamente',500,5000,'',30,5);
					obtenerServicios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el servicio',500,5000,'error',30,3);
			$("#editandoServicio").html('');
		}
	});		
}

function borrarServicio(idServicio)
{
	if(!confirm('¿Realmente desea borrar el registro del servicio?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoServicios').html('<img src="'+ img_loader +'"/> Borrando el servicio..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarServicio',
		data:
		{
			idServicio: 	idServicio,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoServicios').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el servicio',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El servicio se ha borrado correctamente',500,5000,'',30,5);
					$('#filaServicio'+idServicio).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el servicio',500,5000,'error',30,3);
			$("#procesandoServicios").html('');
		}
	});		
}
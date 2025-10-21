//SERVICIOS
//=========================================================================================================================================//
function obtenerEstatus()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEstatus').html('<img src="'+ img_loader +'"/> Obteniendo la lista de Estatus.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerEstatus',
		data:
		{
			tipo: $('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$("#obtenerEstatus").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Estatus',500,5000,'error',30,3);
			$("#obtenerEstatus").html('');
		}
	});
}

function formularioEstatus()
{
	$('#ventanaEstatus').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEstatus').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el Estatus..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioEstatus',
		data:
		{

		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$("#formularioEstatus").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el Estatus',500,5000,'error',30,3);
			$("#formularioEstatus").html('');
		}
	});
}

function registrarEstatus()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del Estatus es incorrecto <br />";
	}
	
	if(!camposVacios($('#txtColor').val()))
	{
		mensaje+="Seleccione el color <br />";
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
			$('#registrandoEstatus').html('<img src="'+ img_loader +'"/> Registrandos el Estatus, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarEstatus',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			color: 			$('#txtColor').val(),
			cliente: 		$('#selectTipoEstatus').val(),
			idEstatusIgual: $('#selectIgual').val(),
			tipo: 			$('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$('#registrandoEstatus').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerEstatus();
					$('#ventanaEstatus').dialog('close');
					notify(data[1],500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el Estatus',500,5000,'error',30,3);
			$("#registrandoEstatus").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaEstatus").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				registrarEstatus();
			},
		},
		close: function() 
		{
			$("#formularioEstatus").html(''); 
			$('#The_colorPicker').fadeOut();
		}
	});

	$("#ventanaEditarEstatus").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				editarEstatus();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerEstatusEditar').html('');
			$('#The_colorPicker').fadeOut();
		}
	});
});

function obtenerEstatusEditar(idEstatus)
{
	$('#ventanaEditarEstatus').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el Estatus..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerEstatusEditar',
		data:
		{
			idEstatus:idEstatus
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$("#obtenerEstatusEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el Estatus',500,5000,'error',30,3);
			$("#obtenerEstatusEditar").html('');
		}
	});
}

function editarEstatus()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del Estatus es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del Estatus?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEstatus').html('<img src="'+ img_loader +'"/> Editando el Estatus, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarEstatus',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			idEstatus: 		$('#txtIdEstatus').val(),
			color: 			$('#txtColor').val(),
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$('#editandoEstatus').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarEstatus').dialog('close');
					notify('El Estatus se ha editado correctamente',500,5000,'',30,5);
					obtenerEstatus();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el Estatus',500,5000,'error',30,3);
			$("#editandoEstatus").html('');
		}
	});		
}

function borrarEstatus(idEstatus)
{
	if(!confirm('¿Realmente desea borrar el registro del Estatus?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoEstatus').html('<img src="'+ img_loader +'"/> Borrando el Estatus..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarEstatus',
		data:
		{
			idEstatus: 	idEstatus,
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$('#procesandoEstatus').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el Estatus',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El Estatus se ha borrado correctamente',500,5000,'',30,5);
					$('#filaEstatus'+idEstatus).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el Estatus',500,5000,'error',30,3);
			$("#procesandoEstatus").html('');
		}
	});		
}
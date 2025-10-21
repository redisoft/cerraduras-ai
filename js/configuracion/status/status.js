//SERVICIOS
//=========================================================================================================================================//
function obtenerStatus()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerStatus').html('<img src="'+ img_loader +'"/> Obteniendo la lista de CRM.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerStatus',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerStatus").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de CRM',500,5000,'error',30,3);
			$("#obtenerStatus").html('');
		}
	});
}

function formularioStatus()
{
	$('#ventanaStatus').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioStatus').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el CRM..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioStatus',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioStatus").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el CRM',500,5000,'error',30,3);
			$("#formularioStatus").html('');
		}
	});
}

function registrarStatus()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del CRM es incorrecto <br />";
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
			$('#registrandoStatus').html('<img src="'+ img_loader +'"/> Registrandos el CRM, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarStatus',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			color: 			$('#txtColor').val(),
			cliente: 		$('#selectTipoStatus').val(),
			idStatusIgual: 	$('#selectIgual').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoStatus').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerStatus();
					$('#ventanaStatus').dialog('close');
					notify(data[1],500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el CRM',500,5000,'error',30,3);
			$("#registrandoStatus").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaStatus").dialog(
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
				registrarStatus();
			},
		},
		close: function() 
		{
			$("#formularioStatus").html(''); 
			$('#The_colorPicker').fadeOut();
		}
	});

	$("#ventanaEditarStatus").dialog(
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
				editarStatus();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerStatusEditar').html('');
			$('#The_colorPicker').fadeOut();
		}
	});
});

function obtenerStatusEditar(idStatus)
{
	$('#ventanaEditarStatus').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el CRM..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerStatusEditar',
		data:
		{
			idStatus:idStatus
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerStatusEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el CRM',500,5000,'error',30,3);
			$("#obtenerStatusEditar").html('');
		}
	});
}

function editarStatus()
{
	mensaje="";
	
	if(!camposVacios($('#txtNombre').val()))
	{
		mensaje+="El nombre del CRM es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del CRM?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoStatus').html('<img src="'+ img_loader +'"/> Editando el CRM, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarStatus',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			idStatus: 		$('#txtIdStatus').val(),
			color: 			$('#txtColor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoStatus').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarStatus').dialog('close');
					notify('El CRM se ha editado correctamente',500,5000,'',30,5);
					obtenerStatus();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el CRM',500,5000,'error',30,3);
			$("#editandoStatus").html('');
		}
	});		
}

function borrarStatus(idStatus)
{
	if(!confirm('¿Realmente desea borrar el registro del CRM?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoStatus').html('<img src="'+ img_loader +'"/> Borrando el CRM..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarStatus',
		data:
		{
			idStatus: 	idStatus,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoStatus').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el CRM',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El CRM se ha borrado correctamente',500,5000,'',30,5);
					$('#filaStatus'+idStatus).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el CRM',500,5000,'error',30,3);
			$("#procesandoStatus").html('');
		}
	});		
}
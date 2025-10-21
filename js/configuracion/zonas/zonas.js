$(document).ready(function()
{
	$("#ventanaZonas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
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
				registrarZona();
			},
		},
		close: function() 
		{
			$('#formularioZonas').html('');
		}
	});
	
	$("#ventanaEditarZona").dialog(
	{
		autoOpen:false,
		height:200,
		width:600,
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
				editarZona();
			},
		},
		close: function() 
		{
			$('#obtenerZona').html('');
		}
	});
});

function obtenerZonasCatalogo()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerZonasCatalogo').html('<img src="'+ img_loader +'"/> Obteniendo la lista de registros...');},
		type:"POST",
		url:base_url+'configuracion/obtenerZonasCatalogo',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerZonasCatalogo").html(data);
		},
		error:function(datos)
		{
			$("#obtenerZonasCatalogo").html('');
		}
	});
}


function formularioZonas()
{
	$("#ventanaZonas").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioZonas').html('<img src="'+ img_loader +'"/> Obteniendo detalles del registro...');},
		type:"POST",
		url:base_url+'configuracion/formularioZonas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioZonas").html(data);
		},
		error:function(datos)
		{
			$("#formularioZonas").html('');
		}
	});
}

function editarZona()
{
	if(!camposVacios($("#txtDescripcion").val()))
	{
		notify('La descripción es incorrecta',500,4000,"error",30,5);
		return;									
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoZona').html('<img src="'+ img_loader +'"/>Realizando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"configuracion/editarZona",
		data:
		{
			"descripcion":	$("#txtDescripcion").val(),
			"idZona":		$("#txtIdZona").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoZona').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no se ha editado',500,4000,"error",30,5);
				break;
				case "1":
					obtenerZonasCatalogo();
					notify('El registro ha sido exitoso',500,4000,"",30,5);
					$("#ventanaEditarZona").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoZona').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}

function obtenerZona(idZona)
{
	$("#ventanaEditarZona").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerZona').html('<img src="'+ img_loader +'"/> Obteniendo detalles del registro...');},
		type:"POST",
		url:base_url+'configuracion/obtenerZona',
		data:
		{
			"idZona":idZona
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerZona").html(data);
		},
		error:function(datos)
		{
			$("#obtenerZona").html('');
		}
	});
}

function registrarZona()
{
	if(!camposVacios($("#txtDescripcion").val()))
	{
		notify('La descripción es incorrecta',500,4000,"error",30,5);
		return;									
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoZona').html('<img src="'+ img_loader +'"/>Realizando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"configuracion/registrarZona",
		data:
		{
			"descripcion":	$("#txtDescripcion").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoZona').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				case "1":
					obtenerZonasCatalogo();
					notify('El registro ha sido exitoso',500,4000,"",30,5);
					$("#ventanaZonas").dialog('close');
				break;
			
			}
		},
		error:function(datos)
		{
			$('#registrandoZona').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}

function borrarZona(idZona)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+ img_loader +'"/>Borrando el registro...');
		},
		type:"POST",
		url:base_url+"configuracion/borrarZona",
		data:
		{
			idZona: idZona
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro, esta asociado a clientes',500,4000,"error",30,5);
				break;
				case "1":
					$('#filaZona'+idZona).remove()
					notify('El registro se ha borrado correctamente',500,4000,"",30,5);
				break;
			
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}
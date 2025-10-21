$(document).ready(function()
{
	$("#ventanaMotivos").dialog(
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
				registrarMotivo();
			},
		},
		close: function() 
		{
			$('#formularioMotivos').html('');
		}
	});
	
	$("#ventanaEditarMotivo").dialog(
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
				editarMotivo();
			},
		},
		close: function() 
		{
			$('#obtenerMotivo').html('');
		}
	});
});

function obtenerMotivos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMotivos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de registros...');},
		type:"POST",
		url:base_url+'configuracion/obtenerMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMotivos").html(data);
		},
		error:function(datos)
		{
			$("#obtenerMotivos").html('');
		}
	});
}


function formularioMotivos()
{
	$("#ventanaMotivos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioMotivos').html('<img src="'+ img_loader +'"/> Obteniendo detalles del registro...');},
		type:"POST",
		url:base_url+'configuracion/formularioMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioMotivos").html(data);
		},
		error:function(datos)
		{
			$("#formularioMotivos").html('');
		}
	});
}

function editarMotivo()
{
	if(!camposVacios($("#txtMotivo").val()))
	{
		notify('El motivo es incorrecto',500,4000,"error",30,5);
		return;									
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoMotivo').html('<img src="'+ img_loader +'"/>Realizando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"configuracion/editarMotivo",
		data:
		{
			"nombre":		$("#txtMotivo").val(),
			"idMotivo":		$("#txtIdMotivo").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoMotivo').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no se ha editado',500,4000,"error",30,5);
				break;
				case "1":
					obtenerMotivos();
					notify('El registro ha sido exitoso',500,4000,"",30,5);
					$("#ventanaEditarMotivo").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoMotivo').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}

function obtenerMotivo(idMotivo)
{
	$("#ventanaEditarMotivo").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMotivo').html('<img src="'+ img_loader +'"/> Obteniendo detalles del registro...');},
		type:"POST",
		url:base_url+'configuracion/obtenerMotivo',
		data:
		{
			"idMotivo":idMotivo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMotivo").html(data);
		},
		error:function(datos)
		{
			$("#obtenerMotivo").html('');
		}
	});
}

function registrarMotivo()
{
	if(!confirm('¿Realmente desea registrar el motivo?'))return;
	
	if(!camposVacios($("#txtMotivo").val()))
	{
		notify('El motivo es incorrecto',500,4000,"error",30,5);
		return;									
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoMotivo').html('<img src="'+ img_loader +'"/>Realizando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"configuracion/registrarMotivo",
		data:
		{
			"nombre":	$("#txtMotivo").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMotivo').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				case "1":
					obtenerMotivos();
					notify(data[1],500,4000,"",30,5);
					$("#ventanaMotivos").dialog('close');
				break;
			
			}
		},
		error:function(datos)
		{
			$('#registrandoMotivo').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}

function borrarMotivo(idMotivo)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoMotivos').html('<img src="'+ img_loader +'"/>Borrando el registro...');
		},
		type:"POST",
		url:base_url+"configuracion/borrarMotivo",
		data:
		{
			idMotivo: idMotivo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoMotivos').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro, esta asociado a clientes',500,4000,"error",30,5);
				break;
				case "1":
					$('#filaMotivo'+idMotivo).remove()
					notify('El registro se ha borrado correctamente',500,4000,"",30,5);
				break;
			
			}
		},
		error:function(datos)
		{
			$('#procesandoMotivos').html('');
			notify('Error al realizar el registro',500,4000,"error");
		}
	});
}
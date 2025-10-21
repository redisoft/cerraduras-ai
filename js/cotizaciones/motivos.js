//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//CATALOGOS COLORES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaMotivos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Guardar': function() 
			{
				registrarMotivo()
			},
		},
		close: function() 
		{
			$("#obtenerListaMotivos").html('');
		}
	});
	
	//$('.ajax-pagMotivos > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagMotivos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerMotivos";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo detalles de motivos...');},
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

function obtenerListaMotivos()
{
	$("#ventanaMotivos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerListaMotivos').html('<img src="'+ img_loader +'"/>Obteniendo la lista de motivos');},
		type:"POST",
		url:base_url+'motivos/obtenerListaMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerListaMotivos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de motivos',500,4000,"error"); 
			$('#obtenerListaMotivos').html('')
		}
	}); 	  
}

function registrarMotivo()
{
	if(!camposVacios($('#txtNombreMotivo').val()))
	{
		notify('El motivo es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoMotivo').html('<img src="'+ img_loader +'"/>Se esta registrando el motivo');},
		type:"POST",
		url:base_url+'motivos/registrarMotivo',
		data:
		{
			nombre: $('#txtNombreMotivo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMotivo').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro se ha guardado correctamente',500,5000,'',30,5);
					obtenerListaMotivos();
					obtenerMotivos();
					$('#txtNombreMotivo').val('')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el motivo',500,4000,"error"); 
			$('#registrandoMotivo').html('');
		}
	}); 	  
}

function obtenerMotivos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerListaMotivos').html('<img src="'+ img_loader +'"/>Obteniendo la lista de motivos');},
		type:"POST",
		url:base_url+'motivos/obtenerMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMotivos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de motivos',500,4000,"error"); 
			$('#obtenerMotivos').html('')
		}
	}); 	  
}

$(document).ready(function()
{
	$("#ventanaEditarMotivo").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				editarMotivo()
			},
		},
		close: function() 
		{
			$("#obtenerMotivo").html('');
		}
	});
});

function obtenerMotivo(idMotivo)
{
	$("#ventanaEditarMotivo").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMotivo').html('<img src="'+ img_loader +'"/>Obteniendo detalles de motivo');},
		type:"POST",
		url:base_url+'motivos/obtenerMotivo',
		data:
		{
			idMotivo:idMotivo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMotivo').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el motivo',500,4000,"error"); 
			$('#obtenerMotivo').html('')
		}
	}); 	  
}

function editarMotivo()
{
	if(!camposVacios($('#txtNombreMotivoEditar').val()))
	{
		notify('El motivo es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el motivo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoMotivo').html('<img src="'+ img_loader +'"/>Se esta editando el motivo');},
		type:"POST",
		url:base_url+'motivos/editarMotivo',
		data:
		{
			nombre: 	$('#txtNombreMotivoEditar').val(),
			idMotivo: 	$('#txtIdMotivoEditar').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoMotivo').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar el motivo, el registro no tuvo cambios',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro se ha guardado correctamente',500,5000,'',30,5);
					obtenerListaMotivos()
					obtenerMotivos();
					$("#ventanaEditarMotivo").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el motivo',500,4000,"error"); 
			$('#editandoMotivo').html('');
		}
	}); 	  
}

function borrarMotivo(idMotivo)
{
	if(!confirm('¿Realmente desea editar el motivo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoMotivo').html('<img src="'+ img_loader +'"/>Se esta editando el motivo');},
		type:"POST",
		url:base_url+'motivos/borrarMotivo',
		data:
		{
			idMotivo: 	idMotivo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMotivo').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el motivo, el registro esta asociado a productos',500,5000,'error',30,5);
				break;
				case "1":
					notify('El motivo se ha borrado correctamente',500,5000,'',30,5);
					obtenerListaMotivos()
					obtenerMotivos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el motivo',500,4000,"error"); 
			$('#registrandoMotivo').html('');
		}
	}); 	  
}



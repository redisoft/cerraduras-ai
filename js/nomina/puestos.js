//PARA LOS PUESTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaRegistrarPuesto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				registrarPuesto();
			},
		},
		close: function() 
		{
			$('#formularioPuestos').html('');
		}
	});
	
	$("#ventanaEditarPuesto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Editar': function() 
			{
				editarPuesto();
			},
		},
		close: function() 
		{
			$('#obtenerPuesto').html('');
		}
	});
});

function obtenerPuestos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPuestos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de puestos'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerPuestos',
		data:
		{
			criterio:	$('#txtBuscarPuesto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerPuestos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de puestos'+conexion,500,5000,'error',30,3);
			$("#obtenerPuestos").html('');
		}
	});		
}

function formularioPuestos()
{
	$("#ventanaRegistrarPuesto").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPuestos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para puestos'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioPuestos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioPuestos").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para puestos'+conexion,500,5000,'error',30,3);
			$("#formularioPuestos").html('');
		}
	});		
}

function obtenerPuesto(idPuesto)
{
	$("#ventanaEditarPuesto").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPuesto').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el puesto'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerPuesto',
		data:
		{
			idPuesto:idPuesto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerPuesto").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el puesto'+conexion,500,5000,'error',30,3);
			$("#obtenerPuesto").html('');
		}
	});		
}

function editarPuesto()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del puesto es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el puesto?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPuesto').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando el puesto'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/editarPuesto',
		data:
		{
			nombre:		$('#txtNombre').val(),
			idPuesto:	$('#txtIdPuesto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoPuesto').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar el puesto o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
				notify('El puesto se ha editado correctamente',500,5000,'',30,3);
				obtenerPuestos();
				$("#ventanaEditarPuesto").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el puesto'+conexion,500,5000,'error',30,3);
			$("#editandoPuesto").html('');
		}
	});		
}

function registrarPuesto()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del puesto es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPuesto').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando el puesto'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/registrarPuesto',
		data:
		{
			nombre:	$('#txtNombre').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPuesto').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
	
				case "1":
				notify('El puesto se ha registrado correctamente',500,5000,'',30,5);
				obtenerPuestos();
				$("#ventanaRegistrarPuesto").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el puesto',500,5000,'error',30,5);
			$("#registrandoPuesto").html('');
		}
	});		
}

function borrarPuesto(idPuesto)
{
	if(!confirm('¿Realmente desea borrar el registro del puesto?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando el puesto'+esperar);
		},
		type:"POST",
		url:base_url+"nomina/borrarPuesto",
		data:
		{
			"idPuesto":		idPuesto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al borrar el puesto, esta asociado a empleados',500,5000,'error',30,3);
				$('#procesandoInformacion').html('');
				break;
				
				case "1":
				$('#procesandoInformacion').html('');
				obtenerPuestos();
				notify('El puesto se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al puesto',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
//PARA LOS PUESTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtBuscarVehiculo').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerVehiculos();
		}
	});
	
	$("#ventanaRegistrarVehiculo").dialog(
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
				registrarVehiculo();
			},
		},
		close: function() 
		{
			$('#formularioVehiculos').html('');
		}
	});
	
	$("#ventanaEditarVehiculo").dialog(
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
				editarVehiculo();
			},
		},
		close: function() 
		{
			$('#obtenerVehiculo').html('');
		}
	});
});

function obtenerVehiculos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVehiculos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de registro'+esperar);
		},
		type:"POST",
		url:base_url+'administracion/obtenerVehiculos',
		data:
		{
			criterio:	$('#txtBuscarVehiculo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerVehiculos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de registro'+conexion,500,5000,'error',30,3);
			$("#obtenerVehiculos").html('');
		}
	});		
}

function formularioVehiculos()
{
	$("#ventanaRegistrarVehiculo").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioVehiculos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario'+esperar);
		},
		type:"POST",
		url:base_url+'administracion/formularioVehiculos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioVehiculos").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario'+conexion,500,5000,'error',30,3);
			$("#formularioVehiculos").html('');
		}
	});		
}

function obtenerVehiculo(idVehiculo)
{
	$("#ventanaEditarVehiculo").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVehiculo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el registro'+esperar);
		},
		type:"POST",
		url:base_url+'administracion/obtenerVehiculo',
		data:
		{
			idVehiculo:idVehiculo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerVehiculo").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el registro'+conexion,500,5000,'error',30,3);
			$("#obtenerVehiculo").html('');
		}
	});		
}

function editarVehiculo()
{
	if(!camposVacios($('#txtModelo').val()) || !camposVacios($('#txtMarca').val()))
	{
		notify('Todos los datos son requeridos',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoVehiculo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando el registro'+esperar);
		},
		type:"POST",
		url:base_url+'administracion/editarVehiculo',
		data:$('#frmVehiculos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoVehiculo').html('');
			
			switch(data)
			{
				case "0":
				notify('No hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,3);
					obtenerVehiculos();
					$("#ventanaEditarVehiculo").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro'+conexion,500,5000,'error',30,3);
			$("#editandoVehiculo").html('');
		}
	});		
}

function registrarVehiculo()
{
	if(!camposVacios($('#txtModelo').val()) || !camposVacios($('#txtMarca').val()))
	{
		notify('Todos los datos son requeridos',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoVehiculo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando'+esperar);
		},
		type:"POST",
		url:base_url+'administracion/registrarVehiculo',
		data:$('#frmVehiculos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoVehiculo').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
	
				case "1":
				notify('El registro ha sido exitoso',500,5000,'',30,5);
				obtenerVehiculos();
				$("#ventanaRegistrarVehiculo").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar',500,5000,'error',30,5);
			$("#registrandoVehiculo").html('');
		}
	});		
}

function borrarVehiculo(idVehiculo)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando el registro'+esperar);
		},
		type:"POST",
		url:base_url+"administracion/borrarVehiculo",
		data:
		{
			"idVehiculo":		idVehiculo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerVehiculos();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al registro',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
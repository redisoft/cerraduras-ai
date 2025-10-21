//PARA LOS MODELOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	obtenerTiendas();
	
	$("#ventanaTiendas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
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
				registrarTienda();
			},
		},
		close: function() 
		{
			$('#formularioTiendas').html('');
		}
	});
	
	$("#ventanaEditarTienda").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
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
				editarTienda();
			},
		},
		close: function() 
		{
			$('#obtenerTienda').html('');
		}
	});
});

function obtenerTiendas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTiendas').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de tiendas');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerTiendas',
		data:
		{
			//criterio:	$('#txtBuscarTalla').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerTiendas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de tiendas',500,5000,'error',30,3);
			$("#obtenerTiendas").html('');
		}
	});		
}

function formularioTiendas()
{
	$("#ventanaTiendas").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioTiendas').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para tiendas');
		},
		type:"POST",
		url:base_url+'tiendas/formularioTiendas',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioTiendas").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para tiendas',500,5000,'error',30,3);
			$("#formularioTiendas").html('');
		}
	});		
}

function obtenerTienda(idTienda)
{
	$("#ventanaEditarTienda").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTienda').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la talla');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerTienda',
		data:
		{
			idTienda:idTienda
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerTienda").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la tienda',500,5000,'error',30,3);
			$("#obtenerTienda").html('');
		}
	});		
}

function editarTienda()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre de la tienda es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la tienda?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoTienda').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando la tienda');
		},
		type:"POST",
		url:base_url+'tiendas/editarTienda',
		data:$('#frmTiendas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoTienda').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar el registro de la tienda o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
				notify('La tienda se ha editado correctamente',500,5000,'',30,3);
				obtenerTiendas();
				$("#ventanaEditarTienda").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la tienda',500,5000,'error',30,3);
			$("#editandoTienda").html('');
		}
	});		
}

function registrarTienda()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre de la tienda es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la tienda?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoTienda').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando la tienda');
		},
		type:"POST",
		url:base_url+'tiendas/registrarTienda',
		data:$('#frmTiendas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoTienda').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
					notify('La tienda se ha registrado correctamente',500,5000,'',30,3);
					obtenerTiendas();
					$("#ventanaTiendas").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la tienda',500,5000,'error',30,3);
			$("#registrandoTienda").html('');
		}
	});		
}

function borrarTienda(idTienda)
{
	if(!confirm('¿Realmente desea borrar el registro de la tienda?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTiendas').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando la tienda');
		},
		type:"POST",
		url:base_url+"tiendas/borrarTienda",
		data:
		{
			"idTienda":		idTienda,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTiendas').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al borrar la tienda, esta asociado a ventas',500,5000,'error',30,3);
				
				break;
				
				case "1":
				obtenerTiendas();
				notify('La tienda se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la tienda',500,5000,'error',30,3);
			$('#procesandoTiendas').html('');
		}
	});				  	  
}
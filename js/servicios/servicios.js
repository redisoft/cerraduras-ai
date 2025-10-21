$(document).ready(function()
{
	/*$("#txtBuscarServicio").autocomplete(
	{
		source:base_url+'configuracion/obtenerServicios',
		
		select:function( event, ui)
		{
			location.href=base_url+"inventarioProductos/servicios/0/"+ui.item.idProducto
		}
	});*/
	
	$("#txtBuscarServicio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerServicios();
		}, milisegundos);
	});
	
	obtenerServicios();
	
	$("#ventanaFormularioServicios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:780,
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
				registrarServicio()
			},
		},
		close: function() 
		{
			$("#formularioServicios").html('');	
		}
	});
	
	$(document).on("click", ".ajax-pagServicios > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerServicios";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarServicio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo servicios...</label>');
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

function obtenerServicios()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de productos...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerServicios",
		data:
		{
			criterio:	$('#txtBuscarServicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServicios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los productos',500,4000,"error");
			$("#obtenerProductos").html('');	
		}
	});				
}


function formularioServicios()
{
	$('#ventanaFormularioServicios').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioServicios').html('<img src="'+ img_loader +'"/> Preparando el formulario para servicios...');},
		type:"POST",
		url:base_url+"inventarioProductos/formularioServicios",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioServicios').html(data);
			$('#txtNombreProducto').focus();
		},
		error:function(datos)
		{
			$("#formularioServicios").html('');	
			notify('Error al preparar el formulario para servicios',500,5000,'error',30,5);
		}
	});					
}

function registrarServicio()
{
	var mensaje="";
	
	if(!camposVacios($("#txtNombreProducto").val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";										
	}

	if($("#selectLineas").val()=="0")
	{
		mensaje+="Seleccione la línea <br />";										
	}
	
	if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}
	
	if(!camposVacios($("#txtPlazo").val()))
	{
		mensaje+="El plazo es incorrecto<br />";										
	}		
	
	if($("#selectPeriodos").val()=="8")
	{
		if(!compararCantidades(0,$("#txtPlazo").val()))
		{
			mensaje+="El plazo debe ser cero <br />";											
		}						
	}

	if(!comprobarNumeros($("#txtPrecioA").val()) || parseFloat($("#txtPrecioA").val())<0)
	{
		mensaje+="El "+precioVentaA+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioB").val()) || parseFloat($("#txtPrecioB").val())<0)
	{
		mensaje+="El "+precioVentaB+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioC").val()) || parseFloat($("#txtPrecioC").val())<0)
	{
		mensaje+="El "+precioVentaC+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioD").val()) || parseFloat($("#txtPrecioD").val())<0)
	{
		mensaje+="El "+precioVentaD+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioE").val()) || parseFloat($("#txtPrecioE").val())<0)
	{
		mensaje+="El "+precioVentaE+" es incorrecto <br />";											
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el servicio?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoServicio').html('<img src="'+ img_loader +'"/> Registrando el servicio');},
		type:"POST",
		url:base_url+"inventarioProductos/registrarServicio",
		data:$('#frmServicios').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoServicio').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$("#ventanaFormularioServicios").dialog('close');
					obtenerServicios();
					notify('El servicio se ha registrado correctamente',500,4000,"",30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el servicio',500,4000,"error",30,5);
			$("#registrandoServicio").html('');	
		}
	});				
}

$(document).ready(function()
{
	$("#ventanaEditarServicio").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:780,
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
			$("#obtenerServicio").html('');
		}
	});
});

function obtenerServicio(idProducto)
{
	$('#ventanaEditarServicio').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarEditar').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerServicio/"+idProducto,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServicio').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del servicio',500,4000,"error",30,5);
			$("#obtenerServicio").html('');	
		}
	});					
}

function editarServicio()
{
	var mensaje="";

	if(!camposVacios($("#txtNombreProducto").val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";										
	}

	
	if($("#selectLineas").val()=="0")
	{
		mensaje+="Seleccione la línea <br />";										
	}
	
	if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}
	
	if(!camposVacios($("#txtPlazo").val()))
	{
		mensaje+="El plazo es incorrecto<br />";										
	}		
	
	if($("#selectPeriodos").val()=="8")
	{
		if(!compararCantidades(0,$("#txtPlazo").val()))
		{
			mensaje+="El plazo debe ser cero <br />";											
		}						
	}

	if(!comprobarNumeros($("#txtPrecioA").val()) || parseFloat($("#txtPrecioA").val())<0)
	{
		mensaje+="El "+precioVentaA+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioB").val()) || parseFloat($("#txtPrecioB").val())<0)
	{
		mensaje+="El "+precioVentaB+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioC").val()) || parseFloat($("#txtPrecioC").val())<0)
	{
		mensaje+="El "+precioVentaC+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioD").val()) || parseFloat($("#txtPrecioD").val())<0)
	{
		mensaje+="El "+precioVentaD+" es incorrecto <br />";											
	}
	
	if(!comprobarNumeros($("#txtPrecioE").val()) || parseFloat($("#txtPrecioE").val())<0)
	{
		mensaje+="El "+precioVentaE+" es incorrecto <br />";											
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el servicio?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoServicio').html('<img src="'+ img_loader +'"/> Editando el servicio');},
		type:"POST",
		url:base_url+"inventarioProductos/editarServicio",
		data:$('#frmServicios').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoServicio').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar el servicio',500,4000,"error",30,5);
				break;
				
				case "1":
					$("#ventanaEditarServicio").dialog('close');
					obtenerServicios();
					notify('El servicio se ha editado correctamente',500,4000,"",30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el servicio',500,4000,"error",30,5);
			$("#editandoServicio").html('');	
		}
	});				
}



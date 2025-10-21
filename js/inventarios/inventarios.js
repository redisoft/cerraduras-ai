$(document).ready(function()
{
	$("#txtBusquedasInventarios").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500;
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerInventarios();
		}, milisegundos);
	});

	$("#ventanaAgregarInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:330,
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
				registrarInventario()
			},
			
		},
		close: function() 
		{
			$("#formularioInventarios").html('');
		}
	});
});

function obtenerInventarios()
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
			$('#obtenerInventarios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de mobiliario y equipo...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerInventarios",
		data:
		{
			criterio:	$('#txtBusquedasInventarios').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerInventarios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del inventario',500,4000,"error",30,5);
			$("#obtenerInventarios").html("");	
		}
	});
}

function formularioInventarios()
{
	$('#ventanaAgregarInventario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioInventarios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de formulario...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/formularioInventarios",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioInventarios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del inventario',500,4000,"error",30,5);
			$("#formularioInventarios").html("");	
		}
	});
}

function registrarInventario()
{
	var mensaje="";

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre del producto es incorrecto <br />";										
	}

	if(!comprobarNumeros($("#txtCosto").val()) || parseFloat($("#txtCosto").val()) ==0)
	{
		mensaje+="El costo es incorrecto<br />";										
	}
	
	if(!comprobarNumeros($("#txtCantidad").val()))
	{
		mensaje+="La cantidad inicial es incorrecta<br />";										
	}
	
	if($("#selectProveedor").val()=="0")
	{
		mensaje+="Seleccione el proveedor<br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInventario').html('<img src="'+ img_loader +'"/> Registrando mobiliario y equipo...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/registrarInventario",
		data:$('#frmAgregarInventario').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoInventario").html('');
			data=eval(data)
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro ha sido correcto',500,4000,"",30,5);
					$('#ventanaAgregarInventario').dialog('close');
					obtenerInventarios();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoInventario").html('');
			notify('Error en el registro',500,4000,"error");
		}
	});
}


function obtenerInventario(idInventario)
{
	$('#ventanaEditarInventario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerInventario').html('<img src="'+ img_loader +'"/> Obteniendo detalles del inventario...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerInventario",
		data:
		{
			"idInventario":idInventario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerInventario').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del inventario',500,4000,"error");
			$("#obtenerInventario").html("");	
		}
	});
}

function borrarMobiliario(idInventario)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInventarios').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/borrarInventario",
		data:
		{
			"idInventario":	idInventario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#procesandoInventarios").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar al inventario',500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,4000,"",30,5);
					obtenerInventarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proveedor al inventario',500,4000,"error",30,5);
			$("#agregandoProveedor").html("");	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaEditarInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:780,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Agregar proveedor': function() 
			{
				formularioAgregarProveedor($('#txtIdInventario').val());
			},
			'Aceptar': function() 
			{
				editarInventario();
			},
		},
		close: function() 
		{
			$('#obtenerInventario').html('');
		}
	});
});

function editarInventario()
{
	var mensaje="";

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre del producto es incorrecto <br />";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error");
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoInventario').html('<img src="'+ img_loader +'"/> Se esta editando el registro...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/editarInventario",
		data:$('#frmEditarInventario').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoInventario").html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,4000,"error");
				break;
				
				case "1":
					notify('El registro ha sido correcto',500,4000,"",30,5);
					$('#ventanaEditarInventario').dialog('close');
					obtenerInventarios();
				break;
			}
		},
		error:function(datos)
		{
			$("#editandoInventario").html('');
			notify('Error en el registro',500,4000,"error");
		}
	});
}

//ASOCIAR PROVEEDOR CON INVENTARIO

$(document).ready(function()
{
	$("#ventanaAsociarProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				asociarProveedorInventario();
			},
		},
		close: function() 
		{
			$("#formularioAgregarProveedor").html('');
		}
	});
});

function formularioAgregarProveedor(idInventario)
{
	$('#ventanaAsociarProveedor').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarProveedor').html('<img src="'+ img_loader +'"/> Obteniendo los datos para asociar el producto al proveedor...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/formularioAgregarProveedor",
		data:
		{
			"idInventario":	idInventario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarProveedor').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para asociar al proveedor',500,4000,"error");
			$("#formularioAgregarProveedor").html("");	
		}
	});
}

function asociarProveedorInventario()
{
	var mensaje="";

	if($("#selectAsociarProveedor").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}

	if(!comprobarNumeros($("#txtCostoProveedor").val()) || parseFloat($("#txtCostoProveedor").val())==0)
	{
		mensaje+="El costo es incorrecto<br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el proveedor?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoProveedor').html('<img src="'+ img_loader +'"/> Registran el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/asociarProveedorInventario",
		data:
		{
			"idInventario":	$('#txtIdInventario').val(),
			"idProveedor":	$('#selectAsociarProveedor').val(),
			"costo":		$('#txtCostoProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#agregandoProveedor").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$("#ventanaAsociarProveedor").dialog('close');
					notify('El proveedor se ha agregado correctamente',500,4000,"",30,5);
					obtenerInventario($('#txtIdInventario').val())
					obtenerInventarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proveedor al inventario',500,4000,"error",30,5);
			$("#agregandoProveedor").html("");	
		}
	});
}

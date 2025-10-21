$(document).ready(function()
{
	$("#txtBusquedasServicios").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500;
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerServicios();
		}, milisegundos);
	});

	$("#ventanaRegistrarServicio").dialog(
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
				registrarServicio()
			},
			
		},
		close: function() 
		{
			$("#formularioServicios").html('');
		}
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
			$('#obtenerServicios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de servicios...');
		},
		type:"POST",
		url:base_url+"servicios/obtenerServicios",
		data:
		{
			criterio:	$('#txtBusquedasServicios').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServicios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del servicio',500,4000,"error",30,5);
			$("#obtenerServicios").html("");	
		}
	});
}

function formularioServicios()
{
	$('#ventanaRegistrarServicio').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioServicios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de formulario...');
		},
		type:"POST",
		url:base_url+"servicios/formularioServicios",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioServicios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del servicio',500,4000,"error",30,5);
			$("#formularioServicios").html("");	
		}
	});
}

function registrarServicio()
{
	var mensaje="";

	if(!camposVacios($("#txtNombreServicio").val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";										
	}

	if(!comprobarNumeros($("#txtCostoServicio").val()) || parseFloat($("#txtCostoServicio").val()) ==0)
	{
		mensaje+="El costo es incorrecto<br />";										
	}
	
	if($("#txtIdProveedorServicio").val()=="0")
	{
		mensaje+="Seleccione el proveedor<br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el servicio?')) return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoServicio').html('<img src="'+ img_loader +'"/> Registrando servicio...');
		},
		type:"POST",
		url:base_url+"servicios/registrarServicio",
		data:$('#frmAgregarServicio').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoServicio").html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro ha sido correcto',500,4000,"",30,5);
					$('#ventanaRegistrarServicio').dialog('close');
					obtenerServicios();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoServicio").html('');
			notify('Error en el registro',500,4000,"error");
		}
	});
}


function obtenerServicio(idServicio)
{
	$('#ventanaEditarServicio').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo detalles del servicio...');
		},
		type:"POST",
		url:base_url+"servicios/obtenerServicio",
		data:
		{
			"idServicio":idServicio,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServicio').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del servicio',500,4000,"error");
			$("#obtenerServicio").html("");	
		}
	});
}

function borrarServicio(idServicio)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoServicios').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"servicios/borrarServicio",
		data:
		{
			"idServicio":	idServicio,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#procesandoServicios").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar al servicio',500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,4000,"",30,5);
					$('#filaServicio'+idServicio).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el servicio',500,4000,"error",30,5);
			$("#agregandoProveedor").html("");	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaEditarServicio").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
			$('#obtenerServicio').html('');
		}
	});
});

function editarServicio()
{
	var mensaje="";

	if(!camposVacios($("#txtNombreServicio").val()))
	{
		mensaje+="El nombre del servicio es incorrecto <br />";										
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
			$('#editandoServicio').html('<img src="'+ img_loader +'"/> Se esta editando el registro...');
		},
		type:"POST",
		url:base_url+"servicios/editarServicio",
		data:$('#frmEditarServicio').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoServicio").html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro ha sido correcto',500,4000,"",30,5);
					$('#ventanaEditarServicio').dialog('close');
					obtenerServicios();
				break;
			}
		},
		error:function(datos)
		{
			$("#editandoServicio").html('');
			notify('Error en el registro',500,4000,"error",30,5);
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
		height:370,
		width:750,
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
				asociarProveedorServicio();
			},
		},
		close: function() 
		{
			$("#formularioAgregarProveedor").html('');
		}
	});
});

function formularioAgregarProveedor(idServicio)
{
	$('#ventanaAsociarProveedor').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarProveedor').html('<img src="'+ img_loader +'"/> Obteniendo los datos para asociar el servicio al proveedor...');
		},
		type:"POST",
		url:base_url+"servicios/formularioAgregarProveedor",
		data:
		{
			"idServicio":	idServicio,
			"opciones":		'1',
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

function asociarProveedorServicio()
{
	var mensaje="";

	if($("#txtIdProveedorServicio").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}

	if(!comprobarNumeros($("#txtCostoServicio").val()) || parseFloat($("#txtCostoServicio").val())==0)
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
		url:base_url+"servicios/asociarProveedorServicio",
		data:
		{
			"idServicio":	$('#txtIdServicio').val(),
			"idProveedor":	$('#txtIdProveedorServicio').val(),
			"costo":		$('#txtCostoServicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#agregandoProveedor").html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El proveedor se ha agregado correctamente',500,4000,"",30,5);
					formularioAgregarProveedor($('#txtIdServicio').val());
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proveedor al servicio',500,4000,"error",30,5);
			$("#agregandoProveedor").html("");	
		}
	});
}

function editarCostoProveedorServicio(idServicio,idProveedor,i)
{
	mensaje="";
	
	if(!comprobarNumeros($("#txtCostoServicio"+i).val()) || parseFloat($("#txtCostoServicio"+i).val())==0 )
	{
		mensaje+='El costo del producto es incorrecto';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error");
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el costo del servicio?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoProveedor').html('<img src="'+ img_loader +'"/> Editando el costo del producto...');
		},
		type:"POST",
		url:base_url+'servicios/editarCostoProveedorServicio',
		data:
		{
			"costo":		$('#txtCostoServicio'+i).val(),
			"idServicio":	idServicio,
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoProveedor').html('');
			
			switch(data)
			{
				case "0":
					notify('El precio del servicio no ha sido modificado',500,4000,"error",30,5);
				break;
				
				case "1":
					//formularioAgregarProveedor(idProveedor);
					notify('El costo se ha editado correctamente ',500,4000,"",30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('El precio del producto no ha sido modificado',500,4000,"error",30,5);
			$('#agregandoProveedor').html('')
		}
	});
}


function borrarProveedorServicio(idServicio,idProveedor)
{
	if(!confirm('¿Realmente desea borrar el proveedor?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoProveedor').html('<img src="'+ img_loader +'"/> Borrando proveedor...');
		},
		type:"POST",
		url:base_url+'servicios/borrarProveedorServicio',
		data:
		{
			"idServicio":	idServicio,
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoProveedor').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrado el proveedor del servicio',500,4000,"error");
				break;
				
				case "1":
					$('#filaServicioProveedor'+idProveedor).remove();
					notify('El proveedor se ha borrado correctamente',500,4000,"");
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al proveedor del servicio',500,4000,"error");
			$('#agregandoProveedor').html('')
		}
	});
}
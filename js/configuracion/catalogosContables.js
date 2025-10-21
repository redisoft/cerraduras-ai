//PARA LOS DEPARTAMENTOS
function formularioDepartamentos()
{
	$('#ventanaDepartamentos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el departamento..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioDepartamentos',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioDepartamentos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el departamento',500,5000,'error',30,3);
			$("#formularioDepartamentos").html('');
		}
	});
}

function registrarDepartamento()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del departamento es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoDepartamento').html('<img src="'+ img_loader +'"/> Registrando el departamento, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarDepartamento',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			tipo: 		$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar el departamento',500,5000,'error',30,3);
				$('#registrandoDepartamento').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el departamento',500,5000,'error',30,3);
			$("#registrandoDepartamento").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaDepartamentos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				registrarDepartamento();
			},
		},
		close: function() 
		{
			$("#formularioDepartamentos").html(''); 
		}
	});

	$("#ventanaEditarDepartamento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				editarDepartamento();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerDepartamento').html('');
		}
	});
});

function obtenerDepartamento(idDepartamento)
{
	$('#ventanaEditarDepartamento').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepartamento').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el departamento..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerDepartamento',
		data:
		{
			idDepartamento:idDepartamento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDepartamento").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el departamento',500,5000,'error',30,3);
			$("#obtenerDepartamento").html('');
		}
	});
}

function editarDepartamento()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del departamento es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del departamento?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDepartamento').html('<img src="'+ img_loader +'"/> Editando el departamento, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarDepartamento',
		data:
		{
			nombre: 			$('#txtNombre').val(),
			idDepartamento: 	$('#txtIdDepartamento').val(),
			tipo: 				$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar el departamento o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoDepartamento').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el departamento',500,5000,'error',30,3);
			$("#editandoDepartamento").html('');
		}
	});		
}

//PARA LOS PRODUCTOS
function formularioProductos()
{
	$('#ventanaProductos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProductos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el concepto..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioProductos',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioProductos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el concepto',500,5000,'error',30,3);
			$("#formularioProductos").html('');
		}
	});
}

function registrarProducto()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del concepto es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProducto').html('<img src="'+ img_loader +'"/> Registrando el concepto, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarProducto',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			tipo: 		$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar el concepto',500,5000,'error',30,3);
				$('#registrandoProducto').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,5000,'error',30,3);
			$("#registrandoProducto").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaProductos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				registrarProducto();
			},
		},
		close: function() 
		{
			$("#formularioProductos").html(''); 
		}
	});

	$("#ventanaEditarProducto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				editarProducto();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerProducto').html('');
		}
	});
});

function obtenerProducto(idProducto)
{
	$('#ventanaEditarProducto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProducto').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el concepto..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProducto',
		data:
		{
			idProducto:idProducto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProducto").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el producto',500,5000,'error',30,3);
			$("#obtenerProducto").html('');
		}
	});
}

function editarProducto()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del concepto es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del producto?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoProducto').html('<img src="'+ img_loader +'"/> Editando el concepto, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarProducto',
		data:
		{
			nombre: 			$('#txtNombre').val(),
			idProducto: 		$('#txtIdProducto').val(),
			tipo: 				$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar el concepto o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoProducto').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el producto',500,5000,'error',30,3);
			$("#editandoProducto").html('');
		}
	});		
}

//PARA LOS GASTOS
function formularioGastos()
{
	$('#ventanaGastos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioGastos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el gasto..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioGastos',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioGastos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el gasto',500,5000,'error',30,3);
			$("#formularioGastos").html('');
		}
	});
}

function registrarGasto()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del gasto es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoGasto').html('<img src="'+ img_loader +'"/> Registrando el gasto, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarGasto',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			tipo: 		$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar el gasto',500,5000,'error',30,3);
				$('#registrandoGasto').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el gasto',500,5000,'error',30,3);
			$("#registrandoGasto").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaGastos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				registrarGasto();
			},
		},
		close: function() 
		{
			$("#formularioGastos").html(''); 
		}
	});

	$("#ventanaEditarGasto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				editarGasto();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerGasto').html('');
		}
	});
});

function obtenerGasto(idGasto)
{
	$('#ventanaEditarGasto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGasto').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el gasto..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerGasto',
		data:
		{
			idGasto:idGasto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerGasto").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el gasto',500,5000,'error',30,3);
			$("#obtenerGasto").html('');
		}
	});
}

function editarGasto()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre del gasto es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del gasto?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoGasto').html('<img src="'+ img_loader +'"/> Editando el gasto, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarGasto',
		data:
		{
			nombre: 			$('#txtNombre').val(),
			idGasto: 		    $('#txtIdGasto').val(),
			tipo: 				$('#selectTipoRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar el gasto o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoGasto').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el gasto',500,5000,'error',30,3);
			$("#editandoGasto").html('');
		}
	});		
}

//PARA LOS NOMBRES
function formularioNombres()
{
	$('#ventanaNombres').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNombres').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el nombre..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioNombres',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioNombres").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el nombre',500,5000,'error',30,3);
			$("#formularioNombres").html('');
		}
	});
}

function registrarNombre()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoNombre').html('<img src="'+ img_loader +'"/> Registrando el nombre, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarNombre',
		data:
		{
			nombre: 	$('#txtNombre').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar el nombre',500,5000,'error',30,3);
				$('#registrandoNombre').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el nombre',500,5000,'error',30,3);
			$("#registrandoNombre").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaNombres").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				registrarNombre();
			},
		},
		close: function() 
		{
			$("#formularioNombres").html(''); 
		}
	});

	$("#ventanaEditarNombre").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				editarNombre();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerNombre').html('');
		}
	});
});

function obtenerNombre(idNombre)
{
	$('#ventanaEditarNombre').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGasto').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el nombre..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerNombre',
		data:
		{
			idNombre:idNombre
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNombre").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el nombre',500,5000,'error',30,3);
			$("#obtenerNombre").html('');
		}
	});
}

function editarNombre()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre  es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del nombre?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNombre').html('<img src="'+ img_loader +'"/> Editando el nombre, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarNombre',
		data:
		{
			nombre: 			$('#txtNombre').val(),
			idNombre: 		    $('#txtIdNombre').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar el nombre o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoNombre').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/catalogosContables';
				break;
			}
		},
		error:function(datos)
		{

			notify('Error al editar el nombre',500,5000,'error',30,3);
			$("#editandoNombre").html('');
		}
	});		
}
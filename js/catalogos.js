function catalogos()
{
	obtenerDepartamentos();
	obtenerNombres();
	obtenerProductos();
	obtenerTipoGasto();
}

//DEPARTAMENTOS
//-------------------------------------------------------------------------------------------------------------------------------------
function formularioDepartamentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar departamentos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioDepartamentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioDepartamentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para departamentos',500,5000,'error',2,5);
			$('#formularioDepartamentos').html('');
		}
	});					  	  
}

function registrarDepartamento()
{
	if(!camposVacios($('#txtNombreDepartamento').val()))
	{
		notify('El nombre del departamento es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoDepartamento').html('<img src="'+ img_loader +'"/> Registrando el departamento, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarDepartamento',
		data:
		{
			'nombre':			$('#txtNombreDepartamento').val(),
			tipo: 				$('#txtTipoRegistroCatalogos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#agregandoDepartamento').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					
					$('#txtNombreDepartamento').val('');
					notify(data[1],500,5000,'',30,5);
					obtenerDepartamentos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el departamento',500,5000,'error',0,0);
			$('#agregandoDepartamento').html('');
		}
	});					  	  
}

function obtenerDepartamentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo los departamentos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerDepartamentos',
		data:
		{
			tipo: $('#txtTipoSeccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDepartamentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los departamentos',500,5000,'error',2,5);
			$('#obtenerDepartamentos').html('');
		}
	});					  	  
}

//NOMBRES
//-------------------------------------------------------------------------------------------------------------------------------------

function formularioNombres()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNombres').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar nombres, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioNombres',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNombres').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para nombres',500,5000,'error',2,5);
			$('#formularioNombres').html('');
		}
	});					  	  
}

function registrarNombre()
{
	if(!camposVacios($('#txtNombre').val()))
	{
		notify('El nombre es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoNombre').html('<img src="'+ img_loader +'"/> Registrando el nombre, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarNombre',
		data:
		{
			'nombre':$('#txtNombre').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#agregandoNombre').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					
					$('#txtNombre').val('');
					notify(data[1],500,5000,'',30,5);
					obtenerNombres();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el nombre',500,5000,'error',0,0);
			$('#agregandoNombre').html('');
		}
	});					  	  
}

function obtenerNombres()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNombres').html('<img src="'+ img_loader +'"/> Obteniendo los nombres, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerNombres',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNombres').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los nombres',500,5000,'error',2,5);
			$('#obtenerNombres').html('');
		}
	});					  	  
}

//PRODUCTOS
//-------------------------------------------------------------------------------------------------------------------------------------
function formularioProductos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProductos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar productos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioProductos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProductos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para productos',500,5000,'error',2,5);
			$('#formularioProductos').html('');
		}
	});					  	  
}

function registrarProducto()
{
	if(!camposVacios($('#txtProducto').val()))
	{
		notify('El producto es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoProducto').html('<img src="'+ img_loader +'"/> Registrando el producto, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarProducto',
		data:
		{
			'nombre':			$('#txtProducto').val(),
			tipo: 				$('#txtTipoRegistroCatalogos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoProducto').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					
					$('#txtProducto').val('');
					notify(data[1],500,5000,'',30,5);
					obtenerProductos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,5000,'error',0,0);
			$('#agregandoProducto').html('');
		}
	});					  	  
}

function obtenerProductos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductos').html('<img src="'+ img_loader +'"/> Obteniendo los productos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerProductos',
		data:
		{
			tipo: $('#txtTipoSeccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los productos',500,5000,'error',2,5);
			$('#obtenerProductos').html('');
		}
	});					  	  
}

//PRODUCTOS
//-------------------------------------------------------------------------------------------------------------------------------------
function formularioTipoGastos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioGastos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar tipos de gastos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioTipoGasto',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioGastos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para tipos de gastos',500,5000,'error',2,5);
			$('#formularioGastos').html('');
		}
	});					  	  
}

function registrarTipoGasto()
{
	if(!camposVacios($('#txtTipoGasto').val()))
	{
		notify('El Tipo de gasto es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoGasto').html('<img src="'+ img_loader +'"/> Registrando el tipo de gasto, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarTipoGasto',
		data:
		{
			'nombre':			$('#txtTipoGasto').val(),
			tipo: 				$('#txtTipoRegistroCatalogos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoGasto').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					$('#txtTipoGasto').val('');
					notify(data[1],500,5000,'',30,5);
					obtenerTipoGasto();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el tipo de gasto',500,5000,'error',0,0);
			$('#agregandoGasto').html('');
		}
	});					  	  
}

function obtenerTipoGasto()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTipoGasto').html('<img src="'+ img_loader +'"/> Obteniendo los tipos de gastos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerTipoGasto',
		data:
		{
			tipo: $('#txtTipoSeccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTipoGasto').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los tipos de gastos',500,5000,'error',2,5);
			$('#obtenerTipoGasto').html('');
		}
	});					  	  
}
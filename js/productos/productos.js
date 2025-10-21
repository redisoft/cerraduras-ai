function obtenerProductos()
{
	if(ejecutar && ejecutar.readyState != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de productos...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerProductos",
		data:
		{
			criterio:		$('#txtBuscarProductoInventario').val(),
			orden:			$('#txtOrdenProductos').val(),
			minimo:			$('#selectStockBusqueda').val(),
			codigoInterno:	$('#txtBuscarProductoCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductos').html(data);
		},
		error:function(datos)
		{
			//notify('Error al obtener los productos',500,4000,"error");
			$("#obtenerProductos").html('');	
		}
	});				
}

function ordenInventarioProductos(orden)
{
	$('#txtOrdenProductos').val(orden);
	obtenerProductos();
}

$(document).ready(function()
{
	$('#txtBuscarProductoInventario,#txtBuscarProductoCodigo').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductos();
		}
	});
	
	/*$("#txtBuscarProductoInventario,#txtBuscarProductoCodigo").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductos();
		}, milisegundos);
	});*/

	$("#ventanaRegistrarInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
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
				registrarProducto()
			},
		},
		close: function() 
		{
			$("#formularioProductos").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagProductosInventario > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerProductos";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtBuscarProductoInventario').val(),
				orden:			$('#txtOrdenProductos').val(),
				minimo:			$('#selectStockBusqueda').val(),
				codigoInterno:	$('#txtBuscarProductoCodigo').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles...</label>');
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

function formularioProductos()
{
	$('#ventanaRegistrarInventario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProductos').html('<img src="'+ img_loader +'"/> Obteniendo detalles del producto, por favor espere...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/formularioProductos",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProductos').html(data);
			obtenerLineas();
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario del producto',500,4000,"error");
			$("#formularioProductos").html('');	
		}
	});				
}

function registrarProducto()
{
	var mensaje="";
	
	minimo	= obtenerNumeros($("#txtStockMinimo").val());
	maximo	= obtenerNumeros($("#txtStockMaximo").val());
	
	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre del producto es incorrecto <br />";										
	}
	
	if($("#selectLineas").val()=="0")
	{
		mensaje+="Por favor seleccione la línea<br />"
	}
	
	if(minimo>maximo)
	{
		mensaje+="Configure el stock mínimo y máximo correctamente<br />"
	}
	
	/*if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}*/
	
	if($("#selectProveedores").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}

	if($("#txtPrecioA").val()=="" || parseFloat($("#txtPrecioA").val())<0 || isNaN($("#txtPrecioA").val()) )
	{
		mensaje+="El "+precioVentaA+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioB").val()=="" || parseFloat($("#txtPrecioB").val())<0 || isNaN($("#txtPrecioB").val()) )
	{
		mensaje+="El "+precioVentaB+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioC").val()=="" || parseFloat($("#txtPrecioC").val())<0 || isNaN($("#txtPrecioC").val()) )
	{
		mensaje+="El "+precioVentaC+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioD").val()=="" || parseFloat($("#txtPrecioD").val())<0 || isNaN($("#txtPrecioD").val()) )
	{
		mensaje+="El "+precioVentaD+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioE").val()=="" || parseFloat($("#txtPrecioE").val())<0 || isNaN($("#txtPrecioE").val()) )
	{
		mensaje+="El "+precioVentaE+" es incorrecto <br />";											
	}
	
	if($("#txtInventarioInicial").val()=="" || parseFloat($("#txtInventarioInicial").val())<0 || isNaN($("#txtInventarioInicial").val()) )
	{
		mensaje+="El inventario inicial es incorrecto <br />";											
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el producto?')) return;

	var formData = new FormData($('#frmAgregarProducto')[0]);

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#registrandoInventario').html('<img src="'+ img_loader +'"/> Registrando el producto...');
		},
		
		url:base_url+'inventarioProductos/registrarProducto',
		data:formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInventario').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify(data[1],500,4000,"");
					$('#ventanaRegistrarInventario').dialog('close');
					obtenerProductos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,4000,"error",30,5);
			$('#registrandoInventario').html('')
		}
	});
}


//EDITAR LOS PRODUCTOS DE REVENTA
//===============================================================================================================
function obtenerDetallesProducto(idProducto)
{
	$('#ventanaEditarProductoInventario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerDetallesProducto').html('<img src="'+ img_loader +'"/> Obteniendo detalles del producto, por favor espere...');},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerDetallesProducto",
		data:
		{
			idProducto:idProducto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesProducto').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el detalle del producto',500,4000,"error");
			$("#obtenerDetallesProducto").html('');	
		}
	});				
}

$(document).ready(function()
{
	$("#ventanaEditarProductoInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
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
				editarProductoInventario()
			},
		},
		close: function() 
		{
			$("#obtenerDetallesProducto").html('');
		}
	});
});

function editarProductoInventario()
{
	var mensaje="";

	if($("#txtNombre").val()=="")
	{
		mensaje+="El nombre del producto es incorrecto <br />";										
	}
	
	if($("#selectLineas").val()=="0")
	{
		mensaje+="Por favor seleccione la línea<br />"
	}
	
	
	if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}
	
	if($("#txtInventarioInicial").val()=="" || parseFloat($("#txtInventarioInicial").val())<0 || isNaN($("#txtInventarioInicial").val()) )
	{
		mensaje+="El inventario inicial es incorrecto <br />";											
	}
	
	
	
	minimo	= obtenerNumeros($("#txtStockMinimo").val());
	maximo	= obtenerNumeros($("#txtStockMaximo").val());

	if(minimo>maximo)
	{
		mensaje+="Configure el stock mínimo y máximo correctamente<br />"
	}
	
	/*if($("#selectProveedores").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}*/
	
	if($("#txtPrecioA").val()=="" || parseFloat($("#txtPrecioA").val())<0 || isNaN($("#txtPrecioA").val()) )
	{
		mensaje+="El "+precioVentaA+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioB").val()=="" || parseFloat($("#txtPrecioB").val())<0 || isNaN($("#txtPrecioB").val()) )
	{
		mensaje+="El "+precioVentaB+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioC").val()=="" || parseFloat($("#txtPrecioC").val())<0 || isNaN($("#txtPrecioC").val()) )
	{
		mensaje+="El "+precioVentaC+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioD").val()=="" || parseFloat($("#txtPrecioD").val())<0 || isNaN($("#txtPrecioD").val()) )
	{
		mensaje+="El "+precioVentaD+" es incorrecto <br />";											
	}
	
	if($("#txtPrecioE").val()=="" || parseFloat($("#txtPrecioE").val())<0 || isNaN($("#txtPrecioE").val()) )
	{
		mensaje+="El "+precioVentaE+" es incorrecto <br />";											
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(confirm('¿Realmente desea editar el registro del producto?')==false)
	{
		return;
	}

	var formData = new FormData($('#frmEditarProducto')[0]);

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#editandoProducto').html('<img src="'+ img_loader +'"/> Editando el producto...');
		},
		
		url:base_url+'inventarioProductos/editarProducto',
		data:formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoProducto').html('');
			
			switch(parseInt(data))
			{
				case 0:
					notify('Error al editar el producto',500,4000,"error",30,5);
				break;
				
				case 1:
					notify('El producto se ha editado correctamente',500,4000,"");
					$('#ventanaEditarProductoInventario').dialog('close');
					obtenerProductos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al editar el producto',500,4000,"error",30,5);
			$('#editandoProducto').html('')
		}
	});
}

function borrarProducto(idProducto,actualizar)
{
	if(!confirm('¿Realmente desea borrar el producto?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Borrando el producto...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/borrarProducto',
		data:
		{
			"idProducto":	idProducto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el producto',500,4000,"error",30,5);
				break;
				
				case "1":
					$('#filaInventarioProducto'+idProducto).remove();
					notify('El producto se ha borrado correctamente',500,4000,"",30,5);
					
					if(actualizar==1)
					{
						$('#frmEditarProducto')[0].reset();	
						$("#txtIdProducto").val(0)
						$("#txtBuscarProductoCodigo").focus();
					}
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al desasociar al proveedor del producto',500,4000,"error",30,5);
			$('#exportandoDatos').html('')
		}
	});
}

function editarCostoProveedor(idProducto,idProveedor,i)
{
	mensaje="";
	
	if(isNaN($("#txtCostoProveedor"+i).val()) || parseFloat($("#txtCostoProveedor"+i).val())<0 || $("#txtCostoProveedor"+i).val()=="")
	{
		mensaje+='El costo del producto es incorrecto';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error");
		return;	
	}
	
	if(confirm('¿Realmente desea editar el costo del producto?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoAgregarProveedor').html('<img src="'+ img_loader +'"/> Editando el costo del producto...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/editarCostoProveedor',
		data:
		{
			"precio":		$('#txtCostoProveedor'+i).val(),
			"idProducto":	idProducto,
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoAgregarProveedor').html('');
			
			switch(parseInt(data))
			{
				case 0:
				
				notify('El precio del producto no ha sido modificado',500,4000,"error");
				break;
				
				case 1:
					obtenerProveedoresProductos(idProducto);
					notify('El costo se ha editado correctamente ',500,4000,"");
					
					obtenerProductos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('El precio del producto no ha sido modificado',500,4000,"error");
			$('#cargandoAgregarProveedor').html('')
		}
	});
}

function borrarProveedorProducto(idProducto,idProveedor)
{
	if(confirm('¿Realmente desea quitar la asociación del producto con el proveedor?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoAgregarProveedor').html('<img src="'+ img_loader +'"/> Borrando asociación...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/borrarProveedorProducto',
		data:
		{
			"idProducto":	idProducto,
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoAgregarProveedor').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al desasociar al proveedor del producto',500,4000,"error");
				break;
				
				case "1":
					obtenerProveedoresProductos(idProducto);
					notify('El proveedor se ha desasociado correctamente',500,4000,"");
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al desasociar al proveedor del producto',500,4000,"error");
			$('#cargandoAgregarProveedor').html('')
		}
	});
}

function imprimirProductos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/imprimirProductos',
		data:
		{
			criterio:		$('#txtBuscarProductoInventario').val(),
			orden:			$('#txtOrdenProductos').val(),
			minimo:			$('#selectStockBusqueda').val(),
			codigoInterno:	$('#txtBuscarProductoCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/InventarioProductos'
			$('#exportandoDatos').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#exportandoDatos").html('');
		}
	});		
}

//DETALLES DE PRODUCTOS ANALISIS
//===============================================================================================================

function obtenerDetalleProducto(idProducto)
{
	$('#ventanaDetallesProducto').dialog('open');
	 
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarDetallesProducto').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+"inventarioProductos/obtenerDetalleProducto/"+idProducto,
		data:
		{
			//"id":caja,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarDetallesProducto').html(data);
		},
		error:function(datos)
		{
			$("#cargarDetallesProducto").html('Error al obtener el producto');	
		}
	});				
}

$(document).ready(function()
{
	$("#ventanaDetallesProducto").dialog(
	{
		autoOpen:false,
		height:500,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			}
		},
		close: function() 
		{
			//$("#ErrorEditarCaja").fadeOut();
		}
	});
});



function obtenerProveedoresProductos(idProducto)
{
	$('#ventanaAgregarProveedores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarAgregarProveedor').html('<img src="'+ img_loader +'"/> Cargando lista de proveedores...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/obtenerTodosProveedores',
		data:
		{
			"idProducto":	idProducto,
			"editar":		1,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarAgregarProveedor').html(data)
		},
		error:function(datos)
		{
			$('#cargarAgregarProveedor').html('Error al obtener la lista de proveedores')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarProveedores").dialog(
	{
		autoOpen:false,
		height:300,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asociarProveedorProducto()			 
			}
		},
		close: function()
		{
			$("#cargandoAgregarProveedor").html('');
		}
	});
})

function asociarProveedorProducto()
{
	mensaje="";
				
	if($("#proveedoresProductos").val()=="0")
	{
		mensaje+='Debe seleccionar un proveedor <br />';
	}
	
	if(isNaN($("#txtCostoProducto").val()) || parseFloat($("#txtCostoProducto").val())<0 || $("#txtCostoProducto").val()=="")
	{
		mensaje+='El costo del producto es incorrecto';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(confirm('¿Realmente desea asociar este proveedor con el producto?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoAgregarProveedor').html('<img src="'+ img_loader +'"/>Se esta asociando el proveedor con el producto, por favor espere...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/asociarProveedorProducto',
		data:
		{
			"idProveedor":	$("#proveedoresProductos").val(),
			"idProducto": 	$("#txtIdProducto").val(),
			"precio": 		$("#txtCostoProducto").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoAgregarProveedor').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$('#cargandoAgregarProveedor').html('');
					obtenerProveedoresProductos($("#txtIdProducto").val());
					notify('El proveedor se ha asociado correctamente ',500,4000,"",30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoAgregarProveedor').html('');
			notify('Error al asociar el proveedor con el producto',500,4000,"error");;
		}
	});	
}

function alertaProducto(concepto,faltantes)
{
	notify('El producto "'+concepto+'" necesita una nueva compra de '+faltantes,500,5000,'error',40,20);
}


//ASIGNAR PROVEEDORES AL PRODUCTO

function formularioAsignarProveedor()
{
	if(obtenerNumeros($('#txtNumeroProductosAsignar').val())==0)
	{
		notify('Sin registros para asignar proveedor',500,4000,"error",30,5);
		return;	
	}
	
	$('#ventanaAsignarProveedor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAsignarProveedor').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/formularioAsignarProveedor',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAsignarProveedor').html(data)
		},
		error:function(datos)
		{
			$('#formularioAsignarProveedor').html('Error al obtener la lista de proveedores')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAsignarProveedor").dialog(
	{
		autoOpen:false,
		height:200,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asignarProveedor()			 
			}
		},
		close: function()
		{
			$("#formularioAsignarProveedor").html('');
		}
	});
})

function asignarProveedor()
{
	mensaje="";
				
	if($("#txtIdProveedorAsignar").val()=="0")
	{
		mensaje+='Debe seleccionar un proveedor <br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea asignar el proveedor?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asignandoProveedor').html('<img src="'+ img_loader +'"/>Se esta asignando el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/asignarProveedor',
		data:
		{
			idProveedor:	$("#txtIdProveedorAsignar").val(),
			criterio:		$('#txtBuscarProductoInventario').val(),
			orden:			$('#txtOrdenProductos').val(),
			minimo:			$('#selectStockBusqueda').val(),
			codigoInterno:	$('#txtBuscarProductoCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asignandoProveedor').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El proveedor se ha asignado correctamente ',500,4000,"",30,5);
					$('#ventanaAsignarProveedor').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#asignandoProveedor').html('');
			notify('Error al asignar el proveedor',500,4000,"error");;
		}
	});	
}


function borrarInventarioSucursal(idRelacion)
{
	if(!confirm('¿Realmente desea borrar el inventario de la sucursal?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Borrando el inventario...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/borrarInventarioSucursal',
		data:
		{
			"idRelacion":	idRelacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar el inventario',500,4000,"error",30,5);
				break;
				
				case "1":

					notify('El inventario se ha borrado correctamente',500,4000,"",30,5);
					
					obtenerProductos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al borrar los registros',500,4000,"error",30,5);
			$('#exportandoDatos').html('')
		}
	});
}


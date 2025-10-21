$(document).ready(function()
{
	$("#ventanaActualizarProducto").dialog(
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
			'Borrar': function() 
			{
				if(obtenerNumeros($('#txtIdProducto').val())==0)
				{
					notify('Seleccion el producto',500,4000,"error",30,5);
					return;
				}
				
				
				borrarProducto($('#txtIdProducto').val(),1)
			},
			'Aceptar': function() 
			{
				editarProductoActualizar()
			},
		},
		close: function() 
		{
			$("#formularioActualizarProducto").html('');
		}
	});
});

function formularioActualizarProducto()
{
	$('#ventanaActualizarProducto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioActualizarProducto').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/formularioActualizarProducto",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioActualizarProducto').html(data);
			
			
			sugerirSaltosCajasTexto()
			$("#txtBuscarProductoCodigo").focus();
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario del producto',500,4000,"error");
			$("#formularioActualizarProducto").html('');	
		}
	});				
}

function actualizarPorcentajesPrecios()
{
	precioA	= obtenerNumeros($("#txtPrecioA").val());
	precioB	= obtenerNumeros($("#txtPrecioB").val());
	precioC	= obtenerNumeros($("#txtPrecioC").val());

	precioAActual	= obtenerNumeros($("#txtPrecioAActual").val());
	precioBActual	= obtenerNumeros($("#txtPrecioBActual").val());
	precioCActual	= obtenerNumeros($("#txtPrecioCActual").val());
	
	if(precioAActual==0)
	{
		diferenciaA	= precioA - 0.1;
		porcentajeA	= (diferenciaA / 0.1) + 1;
	}
	else
	{
		diferenciaA	= precioA - precioAActual;
		
		porcentajeA	= (diferenciaA / precioAActual) * 100;	
	}
	
	if(precioBActual==0)
	{
		diferenciaB	= precioB - 0.1;
		porcentajeB	= (diferenciaB / 0.1) + 1;
	}
	else
	{
		diferenciaB	= precioB - precioBActual;
		
		porcentajeB	= (diferenciaB / precioBActual) * 100;	
	}
	
	if(precioCActual==0)
	{
		diferenciaC	= precioC - 0.1;
		porcentajeC	= (diferenciaC / 0.1) + 1;
	}
	else
	{
		diferenciaC	= precioA - precioCActual;
		
		porcentajeC	= (diferenciaC / precioCActual) * 100;	
	}
	

	$("#lblPorcentajeA").html(redondear(porcentajeA)+'%');
	$("#lblPorcentajeB").html(redondear(porcentajeB)+'%');
	$("#lblPorcentajeC").html(redondear(porcentajeC)+'%');
}

function obtenerProductosCampos(campo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#actualizandoProducto').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+"configuracion/obtenerProductosCampos",
		data:
		{
			campo: campo,
			criterio: campo=='codigoInterno'?$('#txtBuscarProductoCodigoActualizar').val():$('#txtBuscarProductoNombre').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#actualizandoProducto').html('');
			
			var producto	= $.parseJSON(data);
			
			if(producto.idProducto==0)
			{
				notify('El producto no se encuentra registrado',500,5000,'error',30,5);
				
				$("#txtNombre").val('');
				$("#txtIdProducto").val(0);
				$("#txtUnidad").val('');
				$("#txtIdUnidad").val(0);
				$("#txtPrecioC").val('');
				$("#txtPrecioA").val('');
				$("#txtPrecioB").val('');
				$("#txtApartirB").val('');
				$("#txtCodigoInterno").val('');

				$("#txtInventarioInicial,#txtInventarioActual").val('');
				$("#txtStockMinimo").val('');
				$("#txtStockMaximo").val('');
				$("#txtClaveProductoServicio").val('');
				$("#txtIdClave").val(0);

				$("#txtCostoProducto").val('');
				
				$("#txtPrecioCActual").val(0);
				$("#txtPrecioAActual").val(0);
				$("#txtPrecioBActual").val(0);
				
			}
			else
			{
				$("#txtNombre").val(producto.nombre);
				$("#txtIdProducto").val(producto.idProducto);
				$("#txtUnidad").val(producto.unidad);
				$("#txtIdUnidad").val(producto.idUnidad);
				$("#txtPrecioC").val(producto.precioC);
				$("#txtPrecioA").val(producto.precioA);
				$("#txtPrecioB").val(producto.precioB);
				$("#txtApartirB").val(producto.cantidadMayoreo);
				$("#txtCodigoInterno").val(producto.codigoInterno);

				$("#txtInventarioInicial,#txtInventarioActual").val(producto.stock);
				$("#txtStockMinimo").val(producto.stockMinimo);
				$("#txtStockMaximo").val(producto.stockMaximo);
				$("#txtClaveProductoServicio").val(producto.claveProducto);
				$("#txtIdClave").val(producto.idClave);
				
				$("#txtPrecioCActual").val(producto.precioC);
				$("#txtPrecioAActual").val(producto.precioA);
				$("#txtPrecioBActual").val(producto.precioB);

				$("#txtCostoProducto").val(producto.costo);

				setTimeout(function()
				{
					$("#txtBuscarProductoCodigoActualizar").val("");
					$("#txtBuscarProductoNombre").val("");
					$("#txtNombre").focus();
				},300);
			}
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario del producto',500,4000,"error");
			$("#actualizandoProducto").html('');	
		}
	});				
}


function editarProductoActualizar()
{
	var mensaje="";
	
	if(obtenerNumeros($("#txtIdProducto").val())==0)
	{
		notify('Seleccione un producto',500,4000,"error",30,5);
		return;									
	}

	if($("#txtNombre").val()=="")
	{
		mensaje+="El nombre del producto es incorrecto <br />";										
	}

	if(obtenerNumeros($("#txtInventarioInicial").val())<0)
	{
		mensaje+="El inventario inicial es incorrecto <br />";											
	}
	
	if(obtenerNumeros($("#txtStockMinimo").val())<0)
	{
		mensaje+="El stock mínimo es incorrecto<br />";											
	}
	

	if(obtenerNumeros($("#txtInventarioInicial").val())<0)
	{
		mensaje+="El "+precioVentaA+" es incorrecto <br />";											
	}
	
	if(obtenerNumeros($("#txtInventarioInicial").val())<0)
	{
		mensaje+="El "+precioVentaB+" es incorrecto <br />";											
	}
	
	if(obtenerNumeros($("#txtInventarioInicial").val())<0)
	{
		mensaje+="El "+precioVentaC+" es incorrecto <br />";											
	}
	
	url	=	base_url+'inventarioProductos/editarProductoActualizar'
	
	if($('#txtRegistroSucursales').val()=="1")
	{
		ban	= false;
		url	= base_url+'inventarioProductos/editarProductoActualizarSucursales'
		
		
		for(i=0;i<=obtenerNumeros($('#txtNumeroSucursales').val());i++)
		{
			if( $('#chkSucursal'+i).prop('checked') ) 
			{
				ban	= true;
				break;
			}
		}
		
		if(!ban)
		{
			mensaje+="Seleccione al menos una sucursal"
		}
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el registro del producto?'))return;

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#actualizandoProducto').html('<img src="'+ img_loader +'"/> Editando el producto...');
		},
		
		url:url,
		data:$('#frmEditarProducto').serialize(),
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#actualizandoProducto').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al editar el producto',500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El producto se ha editado correctamente',500,4000,"");
					$('#frmEditarProducto')[0].reset();	
					$("#txtIdProducto").val(0)
					$("#txtBuscarProductoCodigo").focus();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al editar el producto',500,4000,"error",30,5);
			$('#actualizandoProducto').html('')
		}
	});
}
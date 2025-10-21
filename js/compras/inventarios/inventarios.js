$(document).ready(function()
{
	$("#txtProveedores").autocomplete(
	{
		source:base_url+"configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{
			$("#proveedores").val(ui.item.idProveedor);
			$("#txtDiasCredito").val(ui.item.diasCredito);
			confirmarProveedor();
			//obtenerDiasCredito()
		}
	});
	
	$("#txtProveedorCompra").autocomplete(
	{
		source:base_url+"configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'compras/inventarios/fecha/0/'+ui.item.idProveedor
		}
	});
	
	$("#txtBusquedaOrden").autocomplete(
	{
		source:base_url+"configuracion/obtenerOrdenesCompra/2",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'compras/inventarios/fecha/'+ui.item.idCompras
		}
	});
	
	$("#txtBusquedasInventarios").autocomplete(
	{
		source:base_url+"configuracion/obtenerInventarioMobiliario",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'inventarioProductos/inventarios/0/'+ui.item.idInventario
		}
	});
	
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosInventarios();
		}, milisegundos);
	});
	
});

function busquedaFecha()
{
	window.location.href=base_url+'compras/inventarios/'+$('#FechaDia').val()
}

function obtenerTotal()
{
	total=0;
	numero=document.getElementById('indice').value;
	
	for(i=1;i<numero;i++) 
	{
		valor=parseFloat(document.getElementById('pagar_'+i).value);
		if(isNaN(valor))    
		{
			alert('La cantidad es incorrecta');
			document.getElementById('pagar_'+i).value=0;  
			
			return; 
		}
		total+=valor;
	}
	
	$("#totales").val(total);
}

caja=0;
idProductoProduccion=0;

proveedor=0;

function confirmarProveedor()
{
	calcularTotales();
	obtenerProductosInventarios();
}
	
function obtenerProductosInventarios()
{
	idProveedor		= $("#proveedores").val();
	proveedor		= idProveedor;

	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#productosKit').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+"compras/obtenerProductosInventarios",
		data:
		{
			"criterio":		$("#txtCriterio").val(),
			"idProveedor":	$("#proveedores").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$("#ErrorListaProductos").fadeOut();
			$('#productosKit').html(data);					   
		},
		error:function(datos)
		{
			notify('Error al mostrar la lista de productos',500,5000,"error");
		}
	});//Ajax
}

proveedoraso=0;

function formularioCompras()
{
	obtenerProductosInventarios();
	$('#ventanaComprasInventario').dialog('open');
}

$(document).ready(function()
{
	$("#ventanaComprasInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:620,
		width:1200,
		modal:true,
		resizable:false,
		
		buttons:
		{
			'Términos / Condiciones': function() 
			{
				$('#ventanaTerminos').dialog('open');				 
			},
			'Guardar': function() 
			{
				registrarCompraInventario();
			},
			
		},
		close: function() 
		{
		}
	})
	
	//$('.ajax-pag > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pag > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#productosKit";
		var link 	= $(this).attr('href');
		idProveedor	= $("#proveedores").val();

		$.ajax(
		{
			url:link,
			type:"POST",
			data:			
			{
				"criterio":		$("#txtCriterio").val(),
				"idProveedor":	$("#proveedores").val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
			success:function(html,textStatus)
			{
				setTimeout(function(){
				$(element).html(html);},300);
			},
			error:function(datos){$(element).html('Error '+ datos).show('slow');}
		});
	});//.ajax
});

function registrarCompraInventario()
{
	productos				= new Array();
	cantidad				= new Array();
	totales					= new Array();
	precioProducto			= new Array();
	fechas					= new Array();
	descuentos				= new Array();
	descuentosPorcentajes	= new Array();
	codigo					= "";
	mensaje					= "";
	v						= 0; // Indice de la matriz

	if($('#paginaActiva').val()=="0")
	{

		if($("#kitTotal").val()=="0")
		{
			mensaje+='Seleccione al menos un producto para realizar la compra <br />';
		}
		
		if($("#nombreKit").val()=="")
		{
			mensaje+='La descripción de la compra es incorrecta <br />';
		}

		if(mensaje.length>0)
		{
			notify(mensaje,500,5000,"error");
			return;
		}
	}
	
	b=false;
	for(i=0;i<fila;i++)
	{
		precio=parseFloat($('#totalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			//totalKit+=precio
			
			b=true;
			
			if($('#txtFechaEntregaProducto'+i).val()=="")		
			{
				mensaje+="Configure las fechas de entrega para los productos <br />";
				break;
			}
			
			productos[v]				= $('#idProducto'+i).val();
			cantidad[v]					= $('#cantidadProducto'+i).val();
			totales[v]					= $('#totalProducto'+i).val();
			precioProducto[v]			= $('#precioProducto'+i).val();
			proveedoraso				= $('#proveedorProducto'+i).val();
			fechas[v]					= $('#txtFechaEntregaProducto'+i).val();
			descuentos[v]				= $('#txtDescuentoTotal'+i).val();
			descuentosPorcentajes[v]	= $('#txtDescuentoProducto'+i).val();
			
			v++;
		}
	}
	
	if(!b)
	{
		mensaje+="Agregue al menos un producto a la compra <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;
	}

	if(!confirm('Realmente deseea realizar la compra')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoCompraProducto').html('<img src="'+ img_loader +'"/> Se esta realizando la compra, por favor espere...');},
		type:"POST",
		url:base_url+"compras/registrarCompraInventario",
		data:
		{
			"fecha":				$('#txtFechaCompra').val(),
			"nombreKit":			$("#nombreKit").val(),
			"productos":			productos,
			"cantidad":				cantidad,
			"idProveedor":			proveedoraso,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			"descuentos":			descuentos,
			"descuentosPorcentajes":descuentosPorcentajes,
			fechas:					fechas,
			
			"kitTotal":				$("#kitTotal").val(), 
			"descuentoPorcentaje":	$("#txtDescuentoPorcentaje").val(), 
			"descuento":			$("#txtDescuentoTotal").val(), 
			//"ivaPorcentaje":		$("#txtIvaPorcentaje").val(), 
			"ivaPorcentaje":		document.getElementById('chkIva').checked?$("#txtIvaPorcentaje").val():0,
			"iva":					$("#txtIva").val(), 
			"total":				$("#txtTotalCompra").val(),
			
			"diasCredito":			$('#txtDiasCredito').val(),
			fechaEntrega:			$('#txtFechaEntrega').val(),
			terminos:				$('#txtTerminos').val()  
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCompraProducto').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,"error",30,5);
				break;
				
				case "1":
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			obtenerProductosInventarios();
			notify('Error al registrar la compra',500,5000,"error");
		}
	});
}

fila=0; //Es el numero de fila del producto donde ira el kit


function quitarProductoKit(n)
{
	$('#filaProducto'+n).remove();
	calcularTotales();
}

function comprobarProveedor(n)
{
	ban=0;
	
	idProveedor=0;
	
	for(i=0;i<fila;i++)
	{
		if(!isNaN($('#proveedorProducto'+i).val()))
		{
			if($('#proveedorProducto'+i).val()!=$('#txtProveedor'+n).val())
			{
				return 1;
			}
		}
	}

	return 0;
}

function comprobarProducto(n)
{
	for(i=0;i<fila;i++)
	{
		if(!isNaN($('#idProducto'+i).val()))
		{
			if($('#idProducto'+i).val()==$('#agregar'+n).val())
			{
				cantidad	=parseFloat($('#cantidadProducto'+i).val())+parseFloat($('#cantidad'+n).val());
				total		=parseFloat($('#precioProducto'+i).val())*cantidad;
				total		=redondear(total);
				
				$('#cantidadProducto'+i).val(cantidad);
				$('#totalProducto'+i).val(total);
				
				$('#filaCantidad'+i).html(cantidad);
				$('#filaTotal'+i).html('$'+total);
				
				calcularTotales();
				
				$('#cantidad'+n).val('0')
				
				return 1;
			}
		}
	}

	return 0;
}

function agregarProductoKit(n,raton)//n es el numero de fila
{
	if(raton=='si')
	{
		$('#cantidad'+n).val(1)
	}
	
	if(comprobarProveedor(n)==1)
	{
		notify('Debe seleccionar productos de un solo proveedor',500,4000,"error");
		$('#cantidad'+n).val('0')
		return;
	}
	
	if(comprobarProducto(n)==1)
	{
		/*notify('Ya se ha agregado el producto a la compra',500,4000,"error");
		$('#cantidad'+n).val('0')*/
		return;
	}
	
	if(!comprobarNumeros($('#cantidad'+n).val()) || parseFloat($('#cantidad'+n).val())==0)
	{
		notify('La cantidad es incorrecta',500,4000,"error");
		return;
	}
	
	if(!comprobarNumeros($('#precio'+n).val()) || parseFloat($('#precio'+n).val())==0)
	{
		notify('El precio es incorrecto',500,4000,"error");
		return;
	}

	total	= parseFloat($('#precio'+n).val())*parseFloat($('#cantidad'+n).val())
	
	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td align="center">';
	producto+='<img style="cursor:pointer" onclick="quitarProductoKit('+fila+')" src="'+base_url+'img/borrar.png" width="25" tittle="Quitar producto"  />';
	producto+='</td>';
	producto+='<td align="center">'+$('#descripcion'+n).val()+'</td>';
	
	producto+='<td align="center">'+$('#txtNombreProveedor'+n).val()+'</td>';
	producto+='<td align="center"><input type="text" id="txtFechaEntregaProducto'+fila+'" style="width:80px" class="cajas" readonly="readonly" /></td>';
	producto+='<td align="center" id="filaCantidad'+fila+'">'+$('#cantidad'+n).val()+'</td>';
	producto+='<td align="center">$'+$('#precio'+n).val()+'</td>';
	producto+='<td align="center"><input type="text" id="txtDescuentoProducto'+fila+'" style="width:50px" class="cajas" onchange="calcularTotalFila('+fila+')" onkeypress="return soloDecimales(event)" maxlength="7" /></td>';
	producto+='<td align="right" id="filaTotal'+fila+'">$'+total+'</td>';
	producto+='<input type="hidden" id="totalProducto'+fila+'" value="'+total+'" />';
	producto+='<input type="hidden" id="idProducto'+fila+'" value="'+$('#agregar'+n).val()+'" />';
	producto+='<input type="hidden" id="cantidadProducto'+fila+'" value="'+$('#cantidad'+n).val()+'" />';
	producto+='<input type="hidden" id="precioProducto'+fila+'" value="'+$('#precio'+n).val()+'" />';
	producto+='<input type="hidden" id="proveedorProducto'+fila+'" value="'+$('#txtProveedor'+n).val()+'" />';
	producto+='<input type="hidden" id="txtDescuentoTotal'+fila+'" value="0" />';
	producto+='</tr>';
	
	$('#armarKit').append(producto); //Nombre de la tabla que contiene el kit
	$('#txtFechaEntregaProducto'+fila).datepicker();

	fila++;
	
	calcularTotales();
	$('#cantidad'+n).val('0')
	$('#pagoVenta').focus();
}

function calcularTotalFila(i) //Calcular el total por producto
{
	cantidad				= parseFloat($('#cantidadProducto'+i).val());
	precio					= parseFloat($('#precioProducto'+i).val());
	descuentoPorcentaje		= parseFloat($('#txtDescuentoProducto'+i).val());
	
	if(!comprobarNumeros(precio) || precio==0)
	{
		precio=1;
		$('#precioProducto'+i).val(1)
	}
	
	if(!comprobarNumeros(cantidad) || cantidad==0)
	{
		cantidad=1;
		$('#cantidadProducto'+i).val(1)
	}

	if(!comprobarNumeros(descuentoPorcentaje) || descuentoPorcentaje==0 || descuentoPorcentaje>99.99)
	{
		$('#txtDescuentoProducto'+i).val('')
		descuentoPorcentaje	= 0;
	}
	
	
	importe		= cantidad*precio;
	descuento	= descuentoPorcentaje>0?importe*(descuentoPorcentaje/100):0;
	importe		-=descuento;
	
	$('#txtDescuentoTotal'+i).val(descuento)
	$('#totalProducto'+i).val(importe)
	$('#filaTotal'+i).html('$'+redondear(importe));
	
	calcularTotales();
}

function calcularTotales()
{
	totalKit	=0;
	
	for(i=0;i<fila;i++)
	{
		precio	=parseFloat($('#totalProducto'+i).val());
		
		if(!isNaN(precio))
		{
			totalKit+=precio;
		}
	}
	
	$('#kitTotal').val(redondear(totalKit));
	
	descuento	=parseFloat($('#txtDescuentoPorcentaje').val());
	
	if(!comprobarNumeros(descuento)|| descuento==0 || descuento>99)
	{
		descuento=0;
		$('#txtDescuentoPorcentaje').val(0)
	}
	else
	{
		descuento	= descuento/100;
		descuento	= totalKit*descuento;
	}
	
	$('#txtDescuentoTotal').val(redondear(descuento))
	totalKit-=descuento;
	
	
	//iva			=parseFloat($('#txtIvaPorcentaje').val())/100;
	iva			= document.getElementById('chkIva').checked ? parseFloat($('#txtIvaPorcentaje').val())/100:0;
	totalVenta	= totalKit+(iva*totalKit)
	
	$('#txtIva').val(redondear(iva*totalKit))
	$('#txtTotalCompra').val(redondear(totalVenta))
}

$(function()
{
	$("#mostrar1").click(function(event) 
	{
		event.preventDefault();
		$("#productosKit").slideToggle();
	});
	
	$("#caja a1").click(function(event) 
	{
		event.preventDefault();
		$("#productosKit").slideUp();
	});
});

//Recibir compras

function obtenerProductosComprados(idCompra)
{
	$('#ventanaProductosRecibidos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarRecibidos').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la compra, por favor espere');
		},
		type:"POST",
		url:base_url+"compras/obtenerInventariosComprados",
		data:
		{
			"idCompras":idCompra,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarRecibidos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos de la compra',500,4000,"error");
			$("#cargarRecibidos").html('');
		}
	});//Ajax	
}

function inventariosRecibidos(idDetalle)
{
	$("#ventanaRecibirProducto").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProductosRecibidos').html('<img src="'+ img_loader +'"/> Obteniendo los productos recibidos...');
		},
		type:"POST",
		url:base_url+"compras/inventariosRecibidos",
		data:
		{
			"idDetalle":idDetalle,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProductosRecibidos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los productos recibidos',500,5000,'error',5,5);
			$("#formularioProductosRecibidos").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaProductosRecibidos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:970,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$("#Error-Recibido").fadeOut(); 
				$(this).dialog('close');
			}
		},
		close: function() 
		{
			$("#Error-Recibido").fadeOut();
		}
	});
	
	//RECIBIENDO LOS PRODUCTOS
	$("#ventanaRecibirProducto").dialog(
	{
		autoOpen:false,
		height:550,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				//$("#errorRecibido").html(''); 
				//$(this).dialog('close');
				recibirProductos();
			}
		},
		close: function() 
		{
			$("#errorRecibido").html(''); 
		}
	});
	
});

function recibirProductos()
{
	mensaje='';
	
	if($('#txtFechaRecibido').val()=="")
	{
		mensaje+='La fecha es incorrecta <br />';
	}
	
	if($('#txtCantidadRecibir').val()=="" || isNaN($('#txtCantidadRecibir').val())
		|| parseFloat($('#txtCantidadRecibir').val())<0 || $('#txtCantidadRecibir').val()=="0")
	{
		mensaje+='La cantidad ha recibir es incorrecta <br />';
	}
	
	if($('#txtRemision').val()=="")
	{
		mensaje+='La remisión es incorrecta ';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea recibir el producto?')) return;
	
	var formData = new FormData($('#frmProductoRecibido')[0]);
				
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#recibiendoProductos').html('<img src="'+ img_loader +'"/> Se esta recibiendo el producto...');
		},
		type:"POST",
		url:base_url+"compras/confirmarRecibirInventario",
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		/*{
			"idDetalle":	$('#txtIdDetalle').val(),
			"cantidad":		$('#txtCantidadRecibir').val(),
			"fecha":   		$('#txtFechaRecibido').val(),
			"remision":   	$('#txtRemision').val(),
			"factura":   	$('#selectFactura').val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#recibiendoProductos').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerProductosComprados($('#txtIdCompraRecibido').val());
					window.setTimeout("inventariosRecibidos("+$("#txtIdDetalle").val()+")",1000);
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al recibir el producto',500,5000,'error',5,5);
			$("#recibiendoProductos").html('');	
		}
	});
}

//PARA LOS PAGOS
//========================================================================================================//

function obtenerPagosCompras(idCompra)
{
	comprita	=idCompra;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarPagos').html('<img src="'+ img_loader +'"/>Obteniendo detalles de pagos...');
		},
		type:"POST",
		url:base_url+'compras/obtenerPagosCompras',
		data:
		{
			"idCompra":idCompra
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarPagos").html(data);
			catalogos();
		},
		error:function(datos)
		{
			$("#cargarPagos").html('');	
		}
	});//Ajax	
}

//--------------------------------------------------------------------------------------//
//PARA RECIBIR TODO LO COMPRADO
//--------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaRecibirTodosInventarios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:750,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			'Aceptar': function() 
			{
				recibirTodosInventarios();
			}
		},
		close: function() 
		{
			$("#formularioRecibirTodosInventarios").html(''); 
		}
	});
});

function formularioRecibirTodosInventarios(idCompras)
{
	$('#ventanaRecibirTodosInventarios').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRecibirTodosInventarios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de compras...');
		},
		type:"POST",
		url:base_url+"compras/formularioRecibirTodosInventarios",
		data:
		{
			"idCompras":idCompras,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRecibirTodosInventarios').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los productos comprados',500,5000,'error',5,5);
			$("#formularioRecibirTodosInventarios").html('');	
		}
	});
}

function recibirTodosInventarios()
{
	if(!confirm('¿Realmente desea recibir todo el producto?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#recibiendoTodosInventarios').html('<img src="'+ img_loader +'"/> Recibiendo todo el producto...');
		},
		type    : "POST",
		url     : base_url+"compras/recibirTodosInventarios",
		data	: 
		{
			"fecha":	$('#txtFechaRecibido').val(),
			"factura":	$('#selectFactura').val(),
			"remision":	$('#txtRemision').val(),
			"idCompra":	$('#txtIdComprita').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#recibiendoTodosInventarios').html('');
			
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,4);
				break;
						
				case "1":
					notify('Los productos se han recibido corractamente',500,5000,'',30,4);
					obtenerProductosComprados($('#txtIdComprita').val())
					$('#ventanaRecibirTodosInventarios').dialog('close');
				break;
			}
		},
		error: function(datos)
		{
			$('#recibiendoTodosInventarios').html('');
			notify('Error al recibir los productos',500,5000,'error',30,4);
		}
	});
}

 
 //==========================================================================================================//
function editarCostoInventario(i,idInventario,idProveedor)
{
	if(!comprobarNumeros($('#precio'+i).val()) || parseFloat($('#precio'+i).val())==0)
	{
		notify('El precio es incorrecto',500,5000,'error',30,4);
		return;
	}
	
	//if(!confirm('¿Realmente desea editar el precio?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			//$('#procesandoCompraProducto').html('<img src="'+ img_loader +'"/> Editando el precio del producto...');
		},
		type    : "POST",
		url     : base_url+"inventarioProductos/editarCostoInventario",
		data	: 
		{
			"idInventario":	idInventario,
			"idProveedor":	idProveedor,
			"costo":		$('#precio'+i).val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#procesandoCompraProducto').html('');
			
			/*switch(data)
			{
				case "0":
					notify('El precio no ha cambiado',500,5000,'error',30,4);
				break;
						
				case "1":
					notify('El precio se ha editado corractamente',500,5000,'',30,4);
				break;
			}*/
		},
		error: function(datos)
		{
			$('#procesandoCompraProducto').html('');
			notify('Error al editar el precio',500,5000,'error',30,4);
		}
	});
}

function borrarInventarioRecibido(idRecibido)
{
	if(!confirm('¿Realmente desea borrar el producto?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#recibiendoProductos').html('<img src="'+ img_loader +'"/> Borrando el producto...');
		},
		type    : "POST",
		url     : base_url+"compras/borrarInventarioRecibido",
		data	: 
		{
			"idRecibido":	idRecibido,
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#recibiendoProductos').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar los productos',500,5000,'error',30,4);
				break;
						
				case "1":
					notify('Los productos se han borrado corractamente',500,5000,'',30,4);
					
					/*obtenerProductosComprados($("#txtIdCompraRecibido").val());
					window.setTimeout("productosRecibidos("+$("#txtIdDetalle").val()+")",1000);*/
					
					obtenerProductosComprados($('#txtIdCompraRecibido').val());
					window.setTimeout("inventariosRecibidos("+$("#txtIdDetalle").val()+")",1000);
					
				break;
			}
		},
		error: function(datos)
		{
			$('#recibiendoProductos').html('');
			notify('Error al borrar los productos',500,5000,'error',30,4);
		}
	});
}
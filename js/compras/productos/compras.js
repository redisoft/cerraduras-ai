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
			window.location.href=base_url+'compras/productos/fecha/0/'+ui.item.idProveedor
		}
	});
	
	$("#txtBusquedaOrden").autocomplete(
	{
		source:base_url+"configuracion/obtenerOrdenesCompra/1",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'compras/productos/fecha/'+ui.item.idCompras
		}
	});
	
	$('#buscarNombre').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosReventa();
		}
	});

});

function busquedaFecha()
{
	window.location.href=base_url+'compras/productos/'+$('#FechaDia').val()
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
		obtenerProductosReventa();
	//}
}

function obtenerProductosReventa()
{
	idProveedor	= $("#proveedores").val();
	codigo		= "0";
	
	proveedor=idProveedor;
	
	try
	{
		if(document.getElementById('chkCodigo').checked==true)
		{
			codigo="1";
		}
	}
	catch(datos)
	{
		codigo="0"
	}

	if(ejecutar && ejecutar.readyState != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#productosKit').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+"compras/obtenerProductosReventa",
		data:
		{
			"buscame":		$("#buscarNombre").val(),
			"pagina":		$("#paginaActiva").val(),
			"idProveedor":	idProveedor,
			"codigoBarras":	codigo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$("#ErrorListaProductos").fadeOut();
			$('#productosKit').html(data);					   
		},
		error:function(datos)
		{
			//alert('Error al mostrar la lista de productos');
		}
	});//Ajax
}

proveedoraso	=0;

function formularioCompras()
{
	obtenerProductosReventa();
	$('#ventanaComprasProducto').dialog('open');
}

$(document).ready(function()
{

	$("#ventanaComprasProducto").dialog(
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
			'Aceptar': function() 
			{
				registrarCompraProductos()			  	  
			},
			
		},
		close: function() 
		{
			//$("#ErrorContactoAdd").fadeOut();
		}
	})

	//$('.ajax-pag > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pag > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#productosKit";
		var link = $(this).attr('href');
		idProveedor=$("#proveedores").val();
		//alert(idProveedor);
		$.ajax(
		{
			url:link,
			type:"POST",
			data:			
			{
				 "buscame":$("#buscarNombre").val(), 
				 "idProveedor":idProveedor
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

function registrarCompraProductos()
{
	productos		= new Array();
	cantidad		= new Array();
	totales			= new Array();
	precioProducto	= new Array();
	fechas			= new Array();
	descuentos		= new Array();
	descuentosPorcentajes		= new Array();
	codigo			= "";
	mensaje			= "";
	v				= 0; // Indice de la matriz

	if($("#kitTotal").val()=="0")
	{
		mensaje+='Seleccione al menos un producto para realizar la compra <br />';
	}
	
	if($("#nombreKit").val()=="")
	{
		mensaje+='La descripción de la compra es incorrecta <br />';
	}
	
	/*if($("#proveedores").val()=="0")
	{
		mensaje+='Es necesario seleccionar el proveedor';
	}*/
	
	b=false;
	for(i=0;i<fila;i++)
	{
		precio	= parseFloat($('#totalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			b=true;
			
			if($('#txtFechaEntregaProducto'+i).val()=="")		
			{
				mensaje+="Configure las fechas de entrega para los productos <br />";
				break;
			}
				
			totalKit+=precio
			
			productos[v]		= $('#idProducto'+i).val();
			cantidad[v]			= $('#cantidadProducto'+i).val();
			totales[v]			= $('#totalProducto'+i).val();
			precioProducto[v]	= $('#precioProducto'+i).val();
			proveedoraso		= $('#proveedorProducto'+i).val();
			fechas[v]			= $('#txtFechaEntregaProducto'+i).val();
			descuentos[v]		= $('#txtDescuentoTotal'+i).val();
			descuentosPorcentajes[v]		= $('#txtDescuentoProducto'+i).val();
			
			v++;
		}
	}
	
	if(!b)
	{
		mensaje+="Agregue al menos un producto";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,"error",30,5);
		return;
	}

	if(confirm('Realmente deseea realizar la compra')==false) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoCompraProducto').html('<img src="'+ img_loader +'"/> Se esta realizando la compra, por favor espere...');},
		type:"POST",
		url:base_url+"compras/registrarCompraProducto",
		data:
		{
			"fecha":				$('#txtFechaCompra').val(),
			"nombreKit":			$("#nombreKit").val(),
			"productos":			productos,
			"cantidad":				cantidad,
			"idProveedor":			proveedoraso,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			"fechas":				fechas,
			"descuentos":			descuentos,
			"descuentosPorcentajes":descuentosPorcentajes,

			"kitTotal":				$("#kitTotal").val(), //Es el subtotal en la venta
			"descuentoPorcentaje":	$("#txtDescuentoPorcentaje").val(), //Es el subtotal en la venta
			"descuento":			$("#txtDescuentoTotal").val(), //Es el subtotal en la venta
			"ivaPorcentaje":		document.getElementById('chkIva').checked?$("#txtIvaPorcentaje").val():0, //Es el subtotal en la venta
			"iva":					$("#txtIva").val(), //Es el subtotal en la venta
			"total":				$("#txtTotalCompra").val(), //Es el subtotal en la venta
			
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
				default:
					window.location.href=base_url+"compras/productos/";
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoCompraProducto').html('');
			notify('Error al registrar la compra',500,5000,"error",30,5);
		}
	});//Ajax		
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
				total		=redondeo2decimales(total);
				
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

function cargarProductoCompra(n,raton,StockMaximo)//n es el numero de fila
{
	stockMaximo	= comprobarNumeros(StockMaximo);
	
	if(stockMaximo!=0)
	{
		if(!confirm('El producto ya tiene su stock máximo, ¿Realmente desea agregarlo?'))
		{
			$('#cantidad'+n).val('0')
			return;
		}
		
	}
	
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

	total=parseFloat($('#precio'+n).val())*parseFloat($('#cantidad'+n).val())
	
	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td align="center">';
	producto+='<img style="cursor:pointer" onclick="quitarProductoKit('+fila+')" src="'+base_url+'img/borrar.png" width="18" tittle="Quitar producto"  />';
	producto+='</td>';
	producto+='<td align="center">'+$('#txtCodigoInterno'+n).val()+'</td>';
	producto+='<td align="center">'+$('#descripcion'+n).val()+'</td>';
	producto+='<td align="center">'+$('#txtNombreProveedor'+n).val()+'</td>';
	producto+='<td align="center"><input type="text" id="txtFechaEntregaProducto'+fila+'" style="width:80px" class="cajas" readonly="readonly" value="'+$('#txtFechaActual').val()+'"/></td>';
	producto+='<td align="center" id="filaCantidad'+fila+'">'+$('#cantidad'+n).val()+'</td>';
	producto+='<td align="center">$'+$('#precio'+n).val()+'</td>';
	producto+='<td align="center"><input type="text" id="txtDescuentoProducto'+fila+'" style="width:50px" class="cajas" onchange="calcularTotalFila('+fila+')" onkeypress="return soloDecimales(event)" maxlength="7" /></td>';
	producto+='<td align="right" id="filaTotal'+fila+'">$ '+total+'</td>';
	producto+='<input type="hidden" id="totalProducto'+fila+'" value="'+total+'" />';
	producto+='<input type="hidden" id="idProducto'+fila+'" value="'+$('#agregar'+n).val()+'" />';
	producto+='<input type="hidden" id="cantidadProducto'+fila+'" value="'+$('#cantidad'+n).val()+'" />';
	producto+='<input type="hidden" id="precioProducto'+fila+'" value="'+$('#precio'+n).val()+'" />';
	producto+='<input type="hidden" id="proveedorProducto'+fila+'" value="'+$('#txtProveedor'+n).val()+'" />';
	producto+='<input type="hidden" id="txtDescuentoTotal'+fila+'" value="0" />';
	producto+='</tr>';
	
	$('#armarKit').append(producto); //Nombre de la tabla que contiene el kit
	
	//document.getElementById('agregar'+n).checked=false;
	$('#txtFechaEntregaProducto'+fila).datepicker();
	fila++;
	
	calcularTotales();
	$('#cantidad'+n).val('0')
	$('#pagoVenta').focus();
	
	
	$("#armarKit tr:even").addClass("sombreado");
	$("#armarKit tr:odd").addClass("sinSombra");  
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

function calcularTotales() //Calular el total del kit de productos
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
	
	$('#kitTotal').val(totalKit);
	
	descuento	=parseFloat($('#txtDescuentoPorcentaje').val());
	
	if(Solo_Numerico(descuento)=="" || descuento==0 || descuento>99)
	{
		descuento=0;
		$('#txtDescuentoPorcentaje').val(0)
	}
	else
	{
		descuento	=descuento/100;
		descuento	=totalKit*descuento;
	}
	
	$('#txtDescuentoTotal').val(redondeo2decimales(descuento))
	totalKit-=descuento;
	
	
	iva			= document.getElementById('chkIva').checked ? parseFloat($('#txtIvaPorcentaje').val())/100:0;
	totalVenta	= totalKit+(iva*totalKit)
	
	$('#txtIva').val(redondeo2decimales(iva*totalKit))
	$('#txtTotalCompra').val(redondeo2decimales(totalVenta))
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
	$('#dialog-Recibido').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarRecibidos').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la compra, por favor espere');
		},
		type:"POST",
		url:base_url+"compras/obtenerCompradosProductos",
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
			//$("#errorRecibirCompras").fadeIn();
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#dialog-Recibido").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:1100,
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
			$("#cargarRecibidos").html('');
		}
	});
	
	$("#ventanaRecibidoProductos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				confirmarRecibirCompraProductos();
			}
		},
		close: function() 
		{
			$("#obtenerProductosRecibidos").html('');
		}
	});
});

function confirmarRecibirCompraProductos()
{
	mensaje='';
	
	if($('#txtFechaRecibido').val()=="")
	{
		mensaje+='La fecha es incorrecta <br />';
	}
	
	if($('#txtCantidadRecibir').val()=="" || isNaN($('#txtCantidadRecibir').val()) || parseFloat($('#txtCantidadRecibir').val())<0 || $('#txtCantidadRecibir').val()=="0")
	{
		mensaje+='La cantidad ha recibir es incorrecta <br />';
	}
	
	if($('#txtRemision').val()=="")
	{
		mensaje+='La remisión es incorrecta ';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea recibir el producto?')) return;
	
	var formData = new FormData($('#frmProductoRecibido')[0]);
				
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#recibiendoProductos').html('<img src="'+ img_loader +'"/> Se esta recibiendo el producto, por favor espere');
		},
		type:"POST",
		url:base_url+"compras/confirmarRecibirCompraProductos",
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
					obtenerProductosComprados($("#txtIdComprita").val());
					obtenerProductosRecibidos($('#txtIdDetalle').val());
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al recibir el producto',500,5000,'error',30,5);
			$("#recibiendoProductos").html('');	
		}
	});
}

function obtenerProductosRecibidos(idDetalle)
{
	$("#ventanaRecibidoProductos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductosRecibidos').html('<img src="'+ img_loader +'"/> Obteniendo detalle del producto, por favor espere');
		},
		type:"POST",
		url:base_url+"compras/obtenerProductosRecibidos",
		data:
		{
			"idDetalle":idDetalle,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductosRecibidos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos de la compra',500,4000,"error");
			$("#obtenerProductosRecibidos").html('');
		}
	});
}


function calcularCosto()
{
	porcentaje	= parseFloat($('#precio').val())*parseFloat($('#porcentajeDescuento').val());
	precio		= parseFloat($('#precio').val())-porcentaje;
	
	if(isNaN($('#precio').val()))
	{
		alert('El precio es incorrecto');
		
		$('#precio').val(0);
		precio=0;
	}
	
	$('#costo').val(precio);
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

function realizarPagoProveedorazo()
{
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaEngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	if($('#txtConcepto').val()=="0")
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#txtImporte').val()=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}

	if($('#selectDepartamento').val()=="0")
	{
		mensaje+="Seleccione el departamento <br />";
	}
	
	if($('#selectTipoGasto').val()=="0")
	{
		mensaje+="Seleccione el tipo de gasto <br />";
	}
	
	
	
	if($('#TipoPago').val()=="1")
	{
		$('#numeroCheque').val('');
		$('#numeroTransferencia').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#TipoPago').val()=="2")
	{
		$('#numeroTransferencia').val('');
		idNombre	=$('#selectNombres').val();
		
		if($('#numeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione el nombre <br />";
		}
	}

	if($('#TipoPago').val()=="3")
	{
		$('#numeroCheque').val('');
		idNombre	=0;
		
		if($('#numeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
	}

	if($('#cuentasBanco').val()=="0")
	{
		mensaje+="Seleccione un banco y una cuenta <br />";
	}
	
	var pagar	= parseFloat($('#montoPagar').val());
	var deuda	=parseFloat($('#T3').val());
	
	if (Solo_Numerico($('#montoPagar').val())=="" || $('#montoPagar').val()=="0" || pagar>deuda)
	{
		mensaje+="El monto  a pagar es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',32,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el cobro?')) return;
	
	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#cargandoPagos').html('<img src="'+ img_loader +'"/> Se esta realizando el pago, por favor espere...');
		},
		async   : true,
		type    : "POST",
		url     : base_url+"compras/realizarPago",
		data	: 
		{
			"idCompras":			$('#idCompras').val(),
			"montoPagar":			$('#montoPagar').val(),
			"cuentasBanco":			$('#cuentasBanco').val(),
			"numeroCheque":			$('#numeroCheque').val(),
			"numeroTransferencia":	$('#numeroTransferencia').val(),
			"formaPago":			$('#TipoPago').val(),
			"banco":				$('#listaBancos').val(),
			incluyeIva:				document.getElementById('chkIva').checked==true?1:0,
			nombreReceptor:			$('#txtNombreReceptor').val(),
			fecha:					$('#txtFechaEngreso').val(),
			idNombre:				idNombre,
			idProducto:				$('#txtConcepto').val(),
			idGasto:				$('#selectTipoGasto').val(),
			concepto:       		$('#txtDescripcionProducto').val(),
			iva:					$('#txtIva').val(),
			idDepartamento:			$('#selectDepartamento').val(),
			factura:				$('#txtFactura').val(),
			comentarios:			$('#txtComentarios').val(),
			idProveedor:			$('#txtIdProveedor').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#cargandoPagos').html('');
				notify('Error al registrar el pago',500,5000,'error',34,4);
				break;
						
				case "1":
				$('#cargandoPagos').html('');
				window.setTimeout("obtenerPagosCompras("+$('#idCompras').val()+")",300);
				notify('El pago se ha realizado correctamente',500,5000,'',34,4);
				break;
			}
		},
		error: function(datos)
		{
			notify('Error al registrar el pago',500,5000,'error',34,4);
			$('#cargandoPagos').html('');
		}
	});//Ajax
}

//PARA RECIBIR TODO LO COMPRADO
//--------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaRecibirTodosProductos").dialog(
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
				recibirTodosProductos();
			}
		},
		close: function() 
		{
			$("#formularioRecibirTodosProductos").html(''); 
		}
	});
});

function formularioRecibirTodosProductos(idCompras)
{
	$('#ventanaRecibirTodosProductos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRecibirTodosProductos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de compras...');
		},
		type:"POST",
		url:base_url+"compras/formularioRecibirTodosProductos",
		data:
		{
			"idCompras":idCompras,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRecibirTodosProductos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los productos comprados',500,5000,'error',5,5);
			$("#formularioRecibirTodosProductos").html('');	
		}
	});
}

function recibirTodosProductos()
{
	if(!confirm('¿Realmente desea recibir todo el producto?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#recibiendoTodosProductos').html('<img src="'+ img_loader +'"/> Recibiendo todo el producto...');
		},
		type    : "POST",
		url     : base_url+"compras/recibirTodosProductos",
		data	: 
		{
			"fecha":		$('#txtFechaRecibido').val(),
			"factura":		$('#selectFactura').val(),
			"remision":		$('#txtRemision').val(),
			"idCompra":		$('#txtIdComprita').val(),
			"idLicencia":	$('#selectLicencias').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#recibiendoTodosProductos').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,4);
				break;
						
				case "1":
					notify('Los productos se han recibido corractamente',500,5000,'',30,4);
					obtenerProductosComprados($('#txtIdComprita').val())
					
					$('#ventanaRecibirTodosProductos').dialog('close');
				break;
			}
		},
		error: function(datos)
		{
			$('#recibiendoTodosProductos').html('');
			notify('Error al recibir los productos',500,5000,'error',30,4);
		}
	});
}

function editarPrecioProducto(i,idProducto,idProveedor)
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
		url     : base_url+"inventarioProductos/editarCostoProveedor",
		data	: 
		{
			"idProducto":	idProducto,
			"idProveedor":	idProveedor,
			"precio":		$('#precio'+i).val(),
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

function borrarProductoRecibido(idRecibido)
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
		url     : base_url+"compras/borrarProductoRecibido",
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
					
					obtenerProductosComprados($("#txtIdComprita").val());
					obtenerProductosRecibidos($('#txtIdDetalle').val());
					
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

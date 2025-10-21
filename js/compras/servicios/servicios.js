
$(document).ready(function()
{
	$("#txtProveedores").autocomplete(
	{
		source:base_url+"configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{
			$("#proveedores").val(ui.item.idProveedor);
			$("#txtDiasCredito").val(ui.item.diasCredito);
			obtenerServiciosCompra();
			//obtenerDiasCredito()
		}
	});
	
	$("#txtProveedorCompra").autocomplete(
	{
		source:base_url+"configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'servicios/compras/fecha/0/'+ui.item.idProveedor
		}
	});
	
	$("#txtBusquedaOrden").autocomplete(
	{
		source:base_url+"configuracion/obtenerOrdenesCompra/3",
		
		select:function( event, ui)
		{			
			window.location.href=base_url+'servicios/compras/fecha/'+ui.item.idCompras
		}
	});
	
	$("#txtBuscarServicio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500;
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerServiciosCompra();
		}, milisegundos);
	});
});


function busquedaFecha()
{
	window.location.href=base_url+'servicios/compras/'+$('#FechaDia').val()
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


proveedoraso=0;

function formularioCompras()
{
	obtenerServiciosCompra();
	$('#ventanaComprasServicios').dialog('open');
}

$(document).ready(function()
{
	$("#ventanaComprasServicios").dialog(
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
			'Registrar': function() 
			{
				registrarCompra()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerServiciosCompra").html('');
		}
	})
	
	//$('.ajax-pag > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pag > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerServiciosCompra";
		var link 		= $(this).attr('href');
		idProveedor		= $("#proveedores").val();

		$.ajax(
		{
			url:link,
			type:"POST",
			data:			
			{
				 "buscame":		$("#txtBuscarServicio").val(), 
				 "idProveedor":	idProveedor
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

function comprobarProveedor(n)
{
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

function agregarServicioCompra(n,raton)//n es el numero de fila
{
	if(raton=='si')
	{
		$('#cantidad'+n).val(1)
	}
	
	if(comprobarProveedor(n)==1)
	{
		notify('Debe seleccionar servicios de un solo proveedor',500,4000,"error");
		$('#cantidad'+n).val('0')
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
	
	if(comprobarProducto(n)==1)
	{
		//notify('Ya se ha agregado el material a la compra',500,4000,"error");
		//$('#cantidad'+n).val('0')
		return;
	}
	
	total=parseFloat($('#precio'+n).val())*parseFloat($('#cantidad'+n).val())
	
	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td align="center">';
	producto+='<img style="cursor:pointer" onclick="quitarProductoKit('+fila+')" src="'+base_url+'img/borrar.png" width="18" tittle="Quitar producto"  />';
	producto+='</td>';
	producto+='<td align="center">'+$('#descripcion'+n).val()+'</td>';
	producto+='<td align="center">'+$('#txtNombreProveedor'+n).val()+'</td>';
	producto+='<td align="center"><input type="text" id="txtFechaEntregaProducto'+fila+'" style="width:80px" class="cajas" readonly="readonly" /></td>';
	producto+='<td align="center" id="filaCantidad'+fila+'">'+$('#cantidad'+n).val()+'</td>';
	producto+='<td align="center">$'+redondear($('#precio'+n).val())+'</td>';
	producto+='<td align="center"><input type="text" id="txtDescuentoProducto'+fila+'" style="width:50px" class="cajas" onchange="calcularTotalFila('+fila+')" onkeypress="return soloDecimales(event)" maxlength="7" /></td>';
	producto+='<td align="right" id="filaTotal'+fila+'">$ '+redondear(total)+'</td>';
	producto+='<input type="hidden" id="totalProducto'+fila+'" value="'+total+'" />';
	producto+='<input type="hidden" id="idProducto'+fila+'" value="'+$('#agregar'+n).val()+'" />';
	producto+='<input type="hidden" id="cantidadProducto'+fila+'" value="'+$('#cantidad'+n).val()+'" />';
	producto+='<input type="hidden" id="precioProducto'+fila+'" value="'+$('#precio'+n).val()+'" />';
	producto+='<input type="hidden" id="proveedorProducto'+fila+'" value="'+$('#txtProveedor'+n).val()+'" />';
	producto+='<input type="hidden" id="txtDescuentoTotal'+fila+'" value="0" />';
	producto+='</tr>';
	
	$('#tablaCompras').append(producto); //Nombre de la tabla que contiene el kit
	$('#txtFechaEntregaProducto'+fila).datepicker();
	//document.getElementById('agregar'+n).checked=false;
	
	fila++;
	
	calcularTotales();

	$('#cantidad'+n).val('0')
	$('#pagoVenta').focus();
	$('#txtBuscarServicio').val('');
	$('#txtBuscarServicio').focus();
	
	$("#tablaCompras tr:even").addClass("sombreado");
	$("#tablaCompras tr:odd").addClass("sinSombra");  
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
	
	$('#kitTotal').val(redondear(totalKit));
	
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
	
	$('#txtDescuentoTotal').val(redondear(descuento))
	totalKit-=descuento;
	
	
	//iva			=parseFloat($('#txtIvaPorcentaje').val())/100;
	iva			= document.getElementById('chkIva').checked ? parseFloat($('#txtIvaPorcentaje').val())/100:0;
	totalVenta	= totalKit+(iva*totalKit)
	
	$('#txtIva').val(redondear(iva*totalKit))
	$('#txtTotalCompra').val(redondear(totalVenta))
}

function registrarCompra()
{
	productos				= new Array();
	cantidad				= new Array();
	totales					= new Array();
	precioProducto			= new Array();
	codigo					= "";
	fechas					= new Array();
	descuentos				= new Array();
	descuentosPorcentajes	= new Array();
	v						= 0; // Indice de la matriz
	
	var mensaje="";

	if($("#kitTotal").val()=="0")
	{
		mensaje+="La lista de compras esta vacia <br />";
	}
	
	if($("#nombreKit").val()=="")
	{
		mensaje+="Por favor escriba una descripcion para la compra";
	}
	
	b=false;
	for(i=0;i<fila;i++)
	{
		precio=parseFloat($('#totalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			b=true;
			
			/*if($('#txtFechaEntregaProducto'+i).val()=="")		
			{
				mensaje+="Configure las fechas de entrega para los servicios <br />";
				break;
			}*/
			
			totalKit+=precio
			
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

	if(!confirm('¿Realmente deseea registrar la compra?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCompraServicios').html('<img src="'+ img_loader +'"/> Realizando la compra, por favor espere...');
		},
		type:"POST",
		url:base_url+"servicios/registrarCompra",
		data:
		{
			"fecha":				$('#txtFechaCompra').val(),
			"nombreKit":			$("#nombreKit").val(),
			"productos":			productos,
			"cantidad":				cantidad,
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
			"idProveedor":			proveedoraso,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			"diasCredito":			$('#txtDiasCredito').val(),
			fechaEntrega:			$('#txtFechaEntrega').val(),
			terminos:				$('#txtTerminos').val() 
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCompraServicios').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
					
				break;
				
				case "1":
					location.reload();
				break;
			}//switch
		},
		error:function(datos)
		{
			notify('Error al realizar la compra',500,4000,"error",30,5);
			$('#procesandoCompraServicios').html('');
		}
	});
}

function obtenerServiciosCompra()
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
		beforeSend:function(objeto){$('#obtenerServiciosCompra').html('<img src="'+ img_loader +'"/>Obteniendo la lista de conceptos, por favor espere...');},
		type:"POST",
		url:base_url+"servicios/obtenerServiciosCompra",
		data:
		{
			"criterio":		$("#txtBuscarServicio").val(),
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServiciosCompra').html(data);					   
		},
		error:function(datos)
		{
			$('#obtenerServiciosCompra').html('');	
			notify('Error al obtener el catálogo',500,4000,"error",30,5);	
		}
	});
}

function quitarProductoKit(n)
{
	$('#filaProducto'+n).remove();
	calcularTotales();
}

//PARA RECIBIR LO COMPRADO
//--------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaRecibirCompras").dialog(
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
				$(this).dialog('close');
			}
		},
		close: function() 
		{
			$("#cargarProductosRecibidos").html(''); 
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
	
	if($('#txtCantidadRecibir').val()=="" || isNaN($('#txtCantidadRecibir').val()) || parseFloat($('#txtCantidadRecibir').val())<0 || $('#txtCantidadRecibir').val()=="0")
	{
		mensaje+='La cantidad ha recibir es incorrecta <br />';
	}
	
	/*if($('#txtRemision').val()=="")
	{
		mensaje+='La remisión es incorrecta ';
	}*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea recibir el servicio?')) return;
	
	var formData = new FormData($('#frmProductoRecibido')[0]);
				
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#recibiendoProductos').html('<img src="'+ img_loader +'"/> Se esta recibiendo el servicio...');
		},
		type:"POST",
		url:base_url+"servicios/confirmarRecibirCompra",
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
			"idProveedor": 	$('#txtIdProveedorCompra').val(),
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
					obtenerProductosComprados($("#txtIdCompraRecibido").val());
					window.setTimeout("productosRecibidos("+$("#txtIdDetalle").val()+")",1000);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al recibir el servicio',500,5000,'error',5,5);
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

/*RECIBIR LAS COMPRAS*/

function productosRecibidos(idDetalle)
{
	$("#ventanaRecibirProducto").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProductosRecibidos').html('<img src="'+ img_loader +'"/> Obteniendo los servicios recibidos...');
		},
		type:"POST",
		url:base_url+"servicios/productosRecibidos",
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
			notify('Error al obtener los servicios recibidos',500,5000,'error',5,5);
			$("#formularioProductosRecibidos").html('');	
		}
	});
}

function obtenerProductosComprados(idCompra)
{
	$('#ventanaRecibirCompras').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarProductosRecibidos').html('<img src="'+ img_loader +'"/> Obteniendo los servicios comprados...');
		},
		type:"POST",
		url:base_url+"servicios/obtenerProductosComprados",
		data:
		{
			"idCompras":idCompra,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarProductosRecibidos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los servicios comprados',500,5000,'error',5,5);
			$("#cargarProductosRecibidos").html('');	
		}
	});//Ajax	
}

//PARA RECIBIR TODO LO COMPRADO
//--------------------------------------------------------------------------------------//
$(document).ready(function()
{
	$("#ventanaRecibirTodosMateriales").dialog(
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
				recibirTodosMateriales();
			}
		},
		close: function() 
		{
			$("#formularioRecibirTodosMateriales").html(''); 
		}
	});
});

function formularioRecibirTodosMateriales(idCompras)
{
	$('#ventanaRecibirTodosMateriales').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRecibirTodosMateriales').html('<img src="'+ img_loader +'"/> Obteniendo detalles de compras...');
		},
		type:"POST",
		url:base_url+"servicios/formularioRecibirTodosServicios",
		data:
		{
			"idCompras":idCompras,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRecibirTodosMateriales').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los servicios comprados',500,5000,'error',5,5);
			$("#formularioRecibirTodosMateriales").html('');	
		}
	});
}

function recibirTodosMateriales()
{
	if(!confirm('¿Realmente desea recibir todos los servicios?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#recibiendoTodosMateriales').html('<img src="'+ img_loader +'"/> Recibiendo todos los servicios...');
		},
		type    : "POST",
		url     : base_url+"servicios/recibirTodosServicios",
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
			$('#recibiendoTodosMateriales').html('');
			data	=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,4);
				break;
						
				case "1":
					notify('Los servicios se han recibido corractamente',500,5000,'',30,4);
					obtenerProductosComprados($('#txtIdComprita').val())
					$('#ventanaRecibirTodosMateriales').dialog('close');
				break;
				
				/*case "2":
					notify('Error al recibir los servicios, ya habia una recepción parcial',500,5000,'error',30,4);
				break;*/
			}
		},
		error: function(datos)
		{
			$('#recibiendoTodosMateriales').html('');
			notify('Error al recibir los servicios',500,5000,'error',30,4);
		}
	});
}

function borrarServicioRecibido(idRecibido)
{
	if(!confirm('¿Realmente desea borrar el servicio?')) return;
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#recibiendoProductos').html('<img src="'+ img_loader +'"/> Borrando el servicio...');
		},
		type    : "POST",
		url     : base_url+"servicios/borrarServicioRecibido",
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
					notify('Error al borrar los servicios',500,5000,'error',30,4);
				break;
						
				case "1":
					notify('Los servicios se han borrado corractamente',500,5000,'',30,4);

					obtenerProductosComprados($("#txtIdCompraRecibido").val());
					window.setTimeout("productosRecibidos("+$("#txtIdDetalle").val()+")",1000);
					
				break;
			}
		},
		error: function(datos)
		{
			$('#recibiendoProductos').html('');
			notify('Error al borrar los servicios',500,5000,'error',30,4);
		}
	});
}
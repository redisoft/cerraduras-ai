$(document).ready(function()
{
	//$('.ajax-pagVen > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagVen > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerProductosVenta";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarProducto').val(),
				idCliente:	$('#txtIdCliente').val(),
				idLinea:	$('#selectLineas').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
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
	
	$("#ventanaCotizaciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:570,
		width:920,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Procesar': function() 
			{
				formularioProcesarCotizacion()		  	  
			},
		},
		close: function() 
		{
			$("#formularioCotizaciones").html('');
		}
	});
	
	$("#ventanaEditarCotizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:570,
		width:920,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Procesar': function() 
			{
				formularioEditarCotizacion()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerCotizacion").html('');
		}
	});
});

/*function mostrarDatos()
{
	if($('#TipoPago').val()=="1")
	{
		$('#mostrarCheques').fadeOut();
		$('#filaNombre').fadeOut();
		$('#mostrarTransferencia').fadeOut();
	}
	
	if($('#TipoPago').val()=="2")
	{
		$('#mostrarCheques').fadeIn();
		$('#mostrarTransferencia').fadeOut();
		$('#filaNombre').fadeIn();
	}
	
	if($('#TipoPago').val()=="3")
	{
		$('#mostrarCheques').fadeOut();
		$('#mostrarTransferencia').fadeIn();
		$('#filaNombre').fadeIn();
	}
}*/

/*function buscarCuentas()
{
	div = document.getElementById('listaBancos');
	idBanco=div.value;
	
	$("#cargarCuenta").load(base_url+"ficha/obtenerCuentas/"+idBanco);
}*/

function registrarCotizacion()
{
	mensaje			= "";
	/*productos		= new Array();
	cantidad		= new Array();
	totales			= new Array();
	precioProducto	= new Array();
	servicios		= new Array();
	fechas			= new Array();
	nombres			= new Array();
	descuentos		= new Array();*/
	
	m				= 0;
	
	if($("#txtSubTotal").val()=="0" || parseFloat($("#txtSubTotal").val())=="0")
	{
		mensaje+="No se han agregado productos para la cotización <br />";
	}

	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	
	v=0;
	
	for(i=0;i<fila;i++)
	{
		precio=parseFloat($('#txtTotalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			//totalKit+=precio
			
			/*productos[v]		= $('#txtIdProducto'+i).val();
			nombres[v]			= $('#txtNombreProducto'+i).val();
			cantidad[v]			= $('#txtCantidadProducto'+i).val();
			totales[v]			= $('#txtTotalProducto'+i).val();
			precioProducto[v]	= $('#txtPrecioProducto'+i).val();
			servicios[v]		= $('#txtServicio'+i).val();
			fechas[v]			= $('#txtFechaInicio'+i).val();
			descuentos[v]		= $('#txtDescuentoPorcentaje'+i).val()+'|'+$('#txtDescuentoProducto'+i).val();
			
			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}*/

			v++;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	//faltantes	= comprobarFaltantesProductos();

	//if(faltantes.length==0)
	//{
		if(!confirm('¿Realmente deseea realizar la cotización?')) return;
	//}
	
	/*if(faltantes.length>0)
	{
		if(!confirm('Alerta, los siguientes productos no tienen suficiente inventario: \n\n'+faltantes+'\n ¿Desea proceder?')) return;
	}*/

	
	//if(!confirm('¿Realmente deseea realizar la cotización?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCotizacion').html('<img src="'+ img_loader +'"/> Se esta realizando la cotización, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/registrarCotizacion',
		data: $('#frmCotizaciones').serialize()+'&'+$('#frmProcesarCotizacion').serialize(),
		/*{
			"productos":			productos,
			"cantidad":				cantidad,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			"servicios":			servicios,
			fechas:					fechas,
			nombres:				nombres,
			descuentos:				descuentos,
			"ivaPorcentaje":		$("#selectIva").val(),
			"iva":					$("#txtIvaTotal").val(),
			"subTotal":				$("#txtSubTotal").val(),
			"descuento":			$("#txtDescuentoTotal").val(),
			"descuentoPorcentaje":	$("#txtDescuentoPorcentaje0").val(),
			
			"total":				$("#txtTotal").val(),
			"idCliente":			$("#txtIdCliente").val(),
			"idDivisa":				$("#selectDivisas").val(),
			"fechaCotizacion":		$("#txtFechaCotizacion").val(),
			"fechaEntrega":			$("#txtFechaEntrega").val(),
			"comentarios":			$("#txtComentarios").val(),
			"serie":				$("#txtSerie").val(),
			"diasCredito":			$("#txtDiasCredito").val(),
			"idContacto":			$("#selectContactosClienteCotizacion").val(),
			
			"asunto":				$("#txtAsunto").val(),
			"presentacion":			$("#txtPresentacion").val(),
			"agradecimientos":		$("#txtAgradecimientos").val(),
			"condiciones":			$("#txtCondicionesPago").val(),
			"terminos":				$("#txtTerminos").val(),
			"firma":				$("#txtFirma").val(),
			"idUsuario":			$("#selectUsuariosEnviar").val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCotizacion").html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					
					if($('#txtRecargar').val()=="0")
					{
						$('#ventanaCotizaciones').dialog('close');
						$('#ventanaProcesarCotizacion').dialog('close');
						
						notify('La cotización se ha registrado correctamente',500,5000,'',30,5);
						
						obtenerCotizaciones();
					}
					else
					{
						location.reload();
					}
					
					
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCotizacion").html('');
			notify('Error al realizar la cotización, por favor verifique la conexión a internet',500,5000,'error',30,5);
		}
	});		
}

function obtenerProductosVenta()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductosVenta').html('<img src="'+ img_loader +'"/> Obteniendo la lista de productos...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerProductosVenta',
		data:
		{
			criterio:	$('#txtBuscarProducto').val(),
			idCliente:	$('#txtIdCliente').val(),
			idLinea:	$('#selectLineas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductosVenta').html(data);
		},
		error:function(datos)
		{
			$('#obtenerProductosVenta').html('');
		}
	});		
}

function formularioCotizaciones(idCliente)
{
	$('#ventanaCotizaciones').dialog('open');
	fila=1;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCotizaciones').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cotizaciones...');
		},
		type:"POST",
		url:base_url+'clientes/formularioCotizaciones',
		data:
		{	
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCotizaciones').html(data);
			obtenerProductosVenta();
			$('#txtBuscarProducto').focus();
		},
		error:function(datos)
		{
			$('#formularioCotizaciones').html('');
		}
	});		
}

fila=1;

function comprobarDuplicidad(n)
{
	for(i=0;i<=fila;i++)
	{
		if(!isNaN($('#idProducto'+i).val()))
		{
			if($('#txtIdProducto'+n).val()==$('#idProducto'+i).val())
			{
				cantidad=parseFloat($('#cantidadProducto'+i).val())+parseFloat($('#txtCantidad'+n).val());
				total=parseFloat($('#selectPrecios'+n).val())*cantidad;
				total=redondeo2decimales(total);
				
				$('#cantidadProducto'+i).val(cantidad);
				
				calcularFilaProducto();
				$('#txtCantidad'+n).val('')
				$('#txtBusquedaProducto').val('');
				$('#txtBusquedaProducto').focus()
				
				/*$('#totalProducto'+i).val(total);
				
				$('#filaCantidad'+i).html(cantidad);
				$('#filaTotal'+i).html(total);
				
				calcularTotales();
				//calcularCambio();
				
				$('#txtCantidad'+n).val('')
				$('#txtBusquedaProducto').val('');
				$('#txtBusquedaProducto').focus();*/
	
				return 0;
			}
		}
	}
	
	return 1;
}

function quitarProductoKit(n)
{
	$('#filaProducto'+n).remove();
	calcularTotales();
	//calcularCambio();
}

function quitarProductoProducto(n)
{
	$('#filaProducto'+n).remove();

	calcularSubTotal();
}

function agregarProductoVenta(n,servicio,raton)//n es el numero de fila
{
	/*if(comprobarDuplicidad(n))
	{
		return;
	}*/
	
	precio	= redondear($('#selectPrecios'+n).val());
	
	if(precio==0)
	{
		/*notify('El precio es incorrecto',500,5000,'error',30,5);
		return;*/
	}
	
	total	= parseFloat($('#selectPrecios'+n).val())*parseFloat(1)
	total	= redondear(total);
	
	
	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td class="filaProducto" width="80%">';
	producto+='<label>'+$('#txtNombre'+n).val()+'</label><br />';
	producto+='<label class="informacionUnidad" style="margin-left:0px">';
	
	if($('#txtClaveDescuento').val()!='')
	{
		producto+='<img src="'+base_url+'img/descuento.png" onclick="accesoAsignarDescuento('+fila+')" style="cursor:pointer; display: none" title="Asignar descuento" />';
	}
	
	producto+='<input type="text" maxlength="8" id="txtCantidadProducto'+fila+'" name="txtCantidadProducto'+fila+'" class="cajasCantidades" value="1" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" /> '+$('#txtUnidad'+n).val()+'';
	
	producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+precio+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/></label>';
	
	producto+='<br /> <label id="lblDescuento'+fila+'" style="font-size:13px; margin-left:81px">Desc $0.00</label>';
	producto+='</td>';
	
	producto+='<td class="filaProducto" id="filaTotal'+fila+'" align="right">';
	producto+='$'+precio;
	producto+='</td>';
	producto+='<td class="filaProducto">';
	producto+='<img src="'+base_url+'img/borrar.png" onclick="quitarProductoProducto('+fila+')" />';
	producto+='</td>';
	
	producto+='<input type="hidden" id="txtNombreProducto'+fila+'" 		name="txtNombreProducto'+fila+'" 		value="'+$('#txtNombre'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalProducto'+fila+'" 		name="txtTotalProducto'+fila+'" 		value="'+total+'" />';
	producto+='<input type="hidden" id="txtIdProducto'+fila+'" 			name="txtIdProducto'+fila+'" 			value="'+$('#txtIDProducto'+n).val()+'" />';
	producto+='<input type="hidden" id="txtServicio'+fila+'" 			name="txtServicio'+fila+'" 				value="'+servicio+'" />';
	producto+='<input type="hidden" id="txtStockDisponible'+fila+'" 	name="txtStockDisponible'+fila+'" 		value="'+$('#txtCantidadTotal'+n).val()+'" />';
	
	producto+='<input type="hidden" id="txtDescuentoProducto'+fila+'" 	name="txtDescuentoProducto'+fila+'" 	value="0" />';
	producto+='<input type="hidden" class="descuentosProductos" 		id="txtDescuentoPorcentaje'+fila+'" name="txtDescuentoPorcentaje'+fila+'" 	value="0" />';
	
	producto+='<input type="hidden" id="txtImpuesto'+fila+'" 			name="txtImpuesto'+fila+'" 			value="'+$('#txtImpuestoNombre'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTasaImpuesto'+fila+'" 		name="txtTasaImpuesto'+fila+'" 		value="'+$('#txtImpuestoTasa'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTipoImpuesto'+fila+'" 		name="txtTipoImpuesto'+fila+'" 		value="'+$('#txtImpuestoTipo'+n).val()+'" />';
	producto+='<input type="hidden" id="txtIdImpuesto'+fila+'" 			name="txtIdImpuesto'+fila+'" 		value="'+$('#txtImpuestoId'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalImpuesto'+fila+'" 		name="txtTotalImpuesto'+fila+'" 	value="'+$('#txtImpuestoTotal'+n).val()+'" />';
	
	producto+='</tr>';
	
	$('#tablaVentas').append(producto); 
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  

	fila++;
	
	$('#txtNumeroProductos').val(fila); 

	$('#carritoVacio').removeClass('Error_validar');
	$('#carritoVacio').html('');
	
	calcularSubTotal();
	
	
	setTimeout(function() 
	{
		if(preciosActivo=='1')
		{
			calcularFilaProducto(fila-1);
		}
		
	}, 200);
}

function calcularFilaProducto(n)
{
	cantidad			= parseFloat($('#txtCantidadProducto'+n).val());
	tasa				= obtenerNumeros($('#txtTasaImpuesto'+n).val());
	precio				= parseFloat($('#txtPrecioProducto'+n).val());
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje'+n).val());

	//if(isNaN(precio) || precio==0 || !comprobarNumeros(precio))
	if(isNaN(precio) || !comprobarNumeros(precio))
	{
		precio =1;
		$('#txtPrecioProducto'+n).val(precio)
	}
	
	if(isNaN(cantidad) || cantidad==0 || !comprobarNumeros(cantidad))
	{
		cantidad =1;
		$('#txtCantidadProducto'+n).val(cantidad)
	}
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje'+n).val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	importe				= precio*cantidad;
	descuento			= importe*descuentoPorcentaje;
	importe				-=descuento;
	//impuestoProducto	= importe*(tasa/100);
	subTotal			= importe / (1+(tasa/100));
	impuestoProducto	= importe-subTotal;
	
	$('#filaTotal'+n).html('$'+redondear(importe))
	$('#txtTotalProducto'+n).val(redondear(importe))
	$('#txtTotalImpuesto'+n).val(redondear(impuestoProducto))
	
	$('#lblDescuento'+n).html('Desc $'+redondear(descuento))
	$('#txtDescuentoProducto'+n).val(redondear(descuento))
	
	calcularSubTotal();
}

subTotal			= 0;
totalesDescuentos	= 0;

function calcularSubTotal() //Calular el subtotal de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	vacio				= false; //El carrito esta vacio
	
	for(i=0;i<=fila;i++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+i).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+i).val());
		
		if(precio>0)
		{
			subTotal			+=precio;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
			
			vacio			= true;
		}
	}
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	//alert(descuentoPorcentaje);
	$('#txtDescuentoProducto0').val(descuento);
	$('#filaDescuento').html('DESC: $'+redondear(totalesDescuentos))
	
	$('#filaSubTotal').html('SUBTOTAL: $'+redondear(subTotal-totalImpuestos));
	$('#filaIva').html('IMPUESTOS: $'+redondear(totalImpuestos));
	$('#filaTotal').html('TOTAL: $'+redondear(subTotal));
	
	if(!vacio)
	{
		$('#carritoVacio').addClass('Error_validar');
		$('#carritoVacio').html('Carrito de ventas vacio');
	}
}


function calcularTotales() //Calular el total de la venta
{
	subTotal	= 0;
	totalImpuestos	= 0;
	
	for(i=0;i<=fila;i++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+i).val());
		
		if(precio>0)
		{
			subTotal		+=precio;
			totalImpuestos	+=impuestoProducto;
		}
	}
	
	$('#txtSubTotal').val(redondear(subTotal-totalImpuestos));
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	subTotal			-=descuento;
	$('#txtDescuentoTotal').val(redondear(descuento));
	//$('#filaDescuento').html('DESC: $'+redondear(descuento))
	
	/*iva			= parseFloat($('#selectIva').val())/100;
	total		= subTotal+(iva*subTotal)*/
	
	total		= subTotal

	$('#txtTotal').val(redondear(total))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	
	calcularCambio();
}



function productoCodigo() 
{
	$("#cantidad1").val(1);
	agregarProductoVenta(1);
	obtenerProductosTienda();
	//alert("Solo");
	return;
}

function calcularCambio()
{
	total	=parseFloat($('#txtTotal').val());
	pago	=parseFloat($('#txtPago').val());
	
	if(isNaN(pago))
	{
		pago=0;
	}
	
	cambio	=pago-total;
	cambio	=Math.round(cambio*100)/100;
	
	if(cambio<0)
	{
		cambio="0.00";
	}
	
	$('#txtCambio').val(cambio)
}

function obtenerCotizacion(idCotizacion)
{
	$('#ventanaEditarCotizacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCotizacion').html('<img src="'+ img_loader +'"/> Obteniendo detalles de cotización...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerCotizacion',
		data:
		{	
			idCotizacion:idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCotizacion').html(data);
			fila	= $('#txtIndiceCotizacion').val();
			fila++;
			obtenerProductosVenta();
			calcularSubTotal();
		},
		error:function(datos)
		{
			$('#obtenerCotizacion').html('');
		}
	});		
}

function editarCotizacion()
{
	mensaje			= "";
	/*productos		= new Array();
	cantidad		= new Array();
	totales			= new Array();
	precioProducto	= new Array();
	servicios		= new Array();
	fechas			= new Array();
	nombres			= new Array();
	descuentos		= new Array();*/
	
	m		=0;
	totalKit=0;
	
	if($("#txtSubTotal").val()=="0" || parseFloat($("#txtSubTotal").val())=="0")
	{
		mensaje+="No se han agregado productos para la cotización <br />";
	}

	if($('#txtIdCotizacion').val()=="0")
	{
		mensaje+="Error en el registro de la cotización <br />";
	}
	
	v=0;
	
	for(i=0;i<fila;i++)
	{
		precio=parseFloat($('#txtTotalProducto'+i).val())
		
		/*if(!isNaN(precio))
		{
			productos[v]		=$('#txtIdProducto'+i).val();
			nombres[v]			=$('#txtNombreProducto'+i).val();
			cantidad[v]			=$('#txtCantidadProducto'+i).val();
			totales[v]			=$('#txtTotalProducto'+i).val();
			precioProducto[v]	=$('#txtPrecioProducto'+i).val();
			servicios[v]		=$('#txtServicio'+i).val();
			descuentos[v]		= $('#txtDescuentoPorcentaje'+i).val()+'|'+$('#txtDescuentoProducto'+i).val();

			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}

			v++;
		}*/
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente deseea editar la cotización?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoCotizacion').html('<img src="'+ img_loader +'"/> Se esta editando la cotización, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/editarCotizacion',
		data: $('#frmCotizaciones').serialize()+'&'+$('#frmProcesarCotizacion').serialize(),
		/*{
			"productos":			productos,
			"cantidad":				cantidad,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			servicios:				servicios,
			fechas:					fechas,
			nombres:				nombres,
			descuentos:				descuentos,
			"diasCredito":			$("#txtDiasCredito").val(),
			
			
			"subTotal":				$("#txtSubTotal").val(),
			"descuento":			$("#txtDescuentoTotal").val(),
			"descuentoPorcentaje":	$("#txtDescuentoPorcentaje0").val(),
			"ivaPorcentaje":		$("#selectIva").val(),
			"iva":					$("#txtIvaTotal").val(),
			
			"idCotizacion":			$("#txtIdCotizacion").val(),
			"total":				$("#txtTotal").val(),
			"idDivisa":				$("#selectDivisas").val(),
			"fechaCotizacion":		$("#txtFechaCotizacion").val(),
			"fechaEntrega":			$("#txtFechaEntrega").val(),
			"comentarios":			$("#txtComentarios").val(),
			"idContacto":			$("#selectContactosClienteCotizacion").val(),
			
			"asunto":				$("#txtAsunto").val(),
			"presentacion":			$("#txtPresentacion").val(),
			"agradecimientos":		$("#txtAgradecimientos").val(),
			"condiciones":			$("#txtCondicionesPago").val(),
			"terminos":				$("#txtTerminos").val(),
			"firma":				$("#txtFirma").val(),
			"idUsuario":			$("#selectUsuariosEnviar").val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoCotizacion").html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
				if($('#txtRecargar').val()=="1")
				{
					location.reload();
				}
				else
				{
					notify(data[1],500,5000,'',30,3);
					obtenerCotizaciones();
					$('#ventanaEditarCotizacion').dialog('close');
					$('#ventanaFormularioEditarCotizacion').dialog('close');
				}
			}
		},
		error:function(datos)
		{
			$("#editandoCotizacion").html('');
			notify('Error al editar la cotización, por favor verifique la conexión a internet',500,5000,'error',30,3);
		}
	});		
}

//PARA ENVIAR EL CORREO POR CORREO
$(document).ready(function()
{
	$("#ventanaCorreo").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
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
				enviarCorreo();       
			},
		},
		close: function() 
		{
			$("#formularioCorreo").html('');
		}
	});
});

function formularioCorreo(idCotizacion)
{
	$('#ventanaCorreo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCorreo').html('<img src="'+ img_loader +'"/>Obteniendo el formulario de correo...');
		},
		type:"POST",
		url:base_url+'clientes/formularioCorreoCotizacion',
		data:
		{
		  	"idCotizacion":	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCorreo').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de correo',500,5000,'error',2,5);
			$("#formularioCorreo").html('');
		}
	});
}

function enviarCorreo()
{
	var mensaje="";

	if($("#asunto").val()=="")
	{
		mensaje+="Por favor escriba el asunto del correo <br />";										
	} 
	
	if($("#correo").val()=="")
	{
		mensaje+="El correo es requerido <br />";									
	}
	
	if($("#mensa").val()=="")
	{
		mensaje+="Escriba el mensaje <br />";												
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error");
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#enviandoCorreo').html('<img src="'+ img_loader +'"/> Se esta enviando el correo, por favor espere...');},
		type:"POST",
		url:base_url+"clientes/enviarCotizacion",
		data:
		{
			"asunto":		$("#asunto").val(),
			"correo":		$("#correo").val(),
			"mensaje":		$("#mensa").val(),
			"idCotizacion":	$('#txtIdCotizacion').val(),
			"idUsuario":	$('#selectUsuariosEnviar').val(),
			"firma":		$('#txtFirma').val(),
			"desglose":		$('#selectDegloseCorreo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#enviandoCorreo").html('');
				notify('Error al enviar el correo',500,4000,"error");
				break;
				case "1":
				$("#enviandoCorreo").html('');
				notify('El correo se ha enviado correctamente',500,4000,"");
				$('#ventanaCorreo').dialog('close');
				break;
			}//switch
		},
		error:function(datos)
		{
			$("#enviandoCorreo").html('');
			notify('Error al enviar el correo',500,4000,"error");
		}
	});
}

//PROCESAR LA COTIZACIÓN
$(document).ready(function()
{
	$("#ventanaProcesarCotizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
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
				registrarCotizacion()		  	  
			},
		},
		close: function() 
		{
			$("#formularioProcesarCotizacion").html('');
		}
	});
});

function formularioProcesarCotizacion()
{
	if(subTotal==0)
	{
		notify('El subtotal de la cotización es incorrecto',500,5000,'error',30,5);
		return;	
	}
	
	if($('#txtIdCliente').val()=="0")
	{
		notify('Seleccione un cliente',500,5000,'error',30,5);
		return;	
	}
	
	$('#ventanaProcesarCotizacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProcesarCotizacion').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cotización');
		},
		type:"POST",
		url:base_url+'clientes/formularioProcesarCotizacion',
		data:
		{
			idCliente:	$('#txtIdCliente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProcesarCotizacion').html(data);
			calcularTotales();
		},
		error:function(datos)
		{
			$('#formularioProcesarCotizacion').html('');
		}
	});		
}

//EDITAR EL REGISTRO DE LA COTIZACIÓN
$(document).ready(function()
{
	$("#ventanaFormularioEditarCotizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
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
				editarCotizacion()		  	  
			},
		},
		close: function() 
		{
			$("#formularioEditarCotizacion").html('');
		}
	});
});

function formularioEditarCotizacion()
{
	if(subTotal==0)
	{
		notify('El subtotal de la cotización es incorrecto',500,5000,'error',30,5);
		return;	
	}
	
	$('#ventanaFormularioEditarCotizacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEditarCotizacion').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cotización');
		},
		type:"POST",
		url:base_url+'clientes/formularioEditarCotizacion',
		data:
		{
			idCotizacion:	$('#txtIdCotizacion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEditarCotizacion').html(data);
			calcularTotales();
		},
		error:function(datos)
		{
			$('#formularioEditarCotizacion').html('');
		}
	});		
}


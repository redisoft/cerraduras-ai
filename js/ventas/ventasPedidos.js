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
	
	$("#ventanaVentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:570,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			/*Cancelar: function() 
			{
				$(this).dialog('close');				 
			},*/
			'Cobrar': function() 
			{
				formularioCobros()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioVentas").html('');
		}
	});
});

function opcionesFormasPagoVentas(opcionFormas)
{
	if(opcionFormas==1)
	{
		Formas		= new String($('#selectFormas').val());
		formas   	= Formas.split('|');
		forma	    = obtenerNumeros(formas[0]);
		
		if(forma==1)
		{
			$('#filaBanco').fadeOut();
			$('#filaCuenta').fadeOut();
		}
		
		if(forma!=1)
		{
			$('#filaBanco').fadeIn();
			$('#filaCuenta').fadeIn();
		}
	}
	else
	{
		if($('#selectFormas').val()=="1")
		{
			$('#filaBanco').fadeOut();
			$('#filaCuenta').fadeOut();
		}
		
		if($('#selectFormas').val()!="1")
		{
			$('#filaBanco').fadeIn();
			$('#filaCuenta').fadeIn();
		}
	}
}

tipoVenta		= 0;
cambioVentas	= 0;

function registrarVenta()
{
	m				= 0;
	mensaje			= "";
	cambioVentas	= 0;

	if($("#txtSubTotal").val()=="0")
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}
	
	if(sistemaActivo=='pinata')
	{
		if(!comprobarNumeros($("#txtPago").val()) || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
		{
			mensaje+="El pago es incorrecto <br />";
		}
	}

	if($('#selectFormas').val()=="2")
	{
		if($('#numeroCheque').val()=="")
		{
			//mensaje+="El numero de tarjeta es invalido <br />";
		}
	}

	if($('#selectFormas').val()=="3")
	{
		if($('#numeroTransferencia').val()=="")
		{
			//mensaje+="El numero de transferencia es invalido <br />";
		}
	}

	if($('#selectFormas').val()!="1" && $('#selectFormas').val()!="4") 
	{
		if($('#cuentasBanco').val()=="0")
		{
			mensaje+="Debe seleccionar una cuenta y un banco <br />";
		}
	}
	
	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	
	
	
	v	= 0;
	ban	= 0;
	
	
	
	for(i=0;i<=fila;i++)
	{
		precio	= parseFloat($('#txtTotalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			ban			=1;
			
			/*productos[v]		= $('#txtIdProducto'+i).val();
			nombres[v]			= $('#txtNombreProducto'+i).val();
			cantidad[v]			= $('#txtCantidadProducto'+i).val();
			totales[v]			= $('#txtTotalProducto'+i).val();
			precioProducto[v]	= $('#txtPrecioProducto'+i).val();
			servicios[v]		= $('#txtServicio'+i).val();
			descuentos[v]		= $('#txtDescuentoPorcentaje'+i).val()+'|'+$('#txtDescuentoProducto'+i).val();
			//fechas[v]			=$('#txtFechaInicio'+i).val();
			
			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}
			

			v++;*/
		}
	}
	
	if(ban==0)
	{
		mensaje+="Seleccione al menos un producto";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if($('#txtTipoUsuarioActivo').val()!="pinata")
	{
		faltantes	= comprobarFaltantesProductos();

		if(numeroFaltantes==0)
		{
			//if(!confirm('¿Realmente deseea realizar la venta?')) return;
		}
		
		if(numeroFaltantes>0)
		{
			formularioInventarioFaltante()
			return;
		}
	}
	
	cambioVentas	= obtenerNumeros($('#txtCambio').val());


	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/registrarVenta',
		data: $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&tipoVenta='+tipoVenta+'&'+$('#frmPedidos').serialize(),
		datatype:"html",

		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('La venta se ha realizado correctamente',500,5000,'',30,5);
					//window.open(base_url+'pdf/nuevaVenta/'+data[1]+'/1');
					//window.open(base_url+'clientes/imprimirTicket/'+data[1]+'/1');
					//window.open(base_url+'pdf/nuevaRemisionFormato/'+data[1]);
					//formularioVentas();
					
					
					
					
					setTimeout(function() 
					{
						if(sistemaActivo=='olyess')
						{
							
							if(obtenerNumeros($('#txtPedidoActivo').val())==1)
							{
								window.open(base_url+'reportes/pedidosReporte/'+data[1]);
							}
							else
							{
								window.open(base_url+'reportes/pedidoVenta/'+data[1]);
							}
						}
						else
						{
							obtenerTicket(data[1]);
						}
						
						//
					}, 100);
					
					
					setTimeout(function() 
					{
						cambioVenta()
						
						$("#formularioPedidos").html('');
						
					}, 300);
					
					
					
					
					
					subTotal	= 0;
					//$('#ventanaCobrosVenta').dialog('close');
					
					if($('#txtIdTienda').val()!="0")
					{
						//obtenerVentas();	
					}		
				
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCobroVenta").html('');
			notify('Error al realizar la venta, por favor verifique la conexión a internet',500,5000,'error',30,3);
		}
	});		
}

function comprobarProductosVentaPedido()
{
	ban=false;
	
	for(i=0;i<=fila;i++)
	{
		idProducto	= parseFloat($('#txtIdProducto'+i).val())
		
		if(!isNaN(idProducto))
		{
			ban=true;
			break;
		}
	}
	
	return ban;
}

function cambioVenta()
{
	$("#btnCancelar,#btnVistaPrevia,#btnFactura,#btnVistaPrevia").button("disable");
	$('#formularioCobros').html('<div style="text-align: center; margin-top:200px; font-size: 50px; height:200px; width:100%"><input readonly="readonly" id="txtCambioActualizar" type="text" class="cajasTransparentes" value="Cambio: $'+redondear(cambioVentas)+'" /><input type="hidden" id="txtCambioActivo" name="txtCambioActivo" value="1" /></div>');
	
	$("#txtCambioActualizar").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			//formularioVentas();
			$('#ventanaCobrosVenta').dialog('close');
		}
	});
	
	$('#txtCambioActualizar').focus();
}

function definirLinea(idLinea)
{
	$('#txtIdLinea').val(idLinea);
	
	obtenerProductosVenta()
}

function definirSubLinea(idSubLinea)
{
	$('#selectSubLineas').val(idSubLinea);
	
	obtenerProductosVenta()
}

//DELAY DEL BUSCADOR


function obtenerProductosVenta(retraso)
{
	if(retraso!='1')
	{
		if(ejecutar && ejecutar.readystate != 4)
		{
			ejecutar.abort();
		}
	}
	
	if(sistemaActivo=='olyess')
	{
		if(!camposVacios($('#txtBuscarProducto').val()) && obtenerNumeros($('#selectLineas').val())==0 && obtenerNumeros($('#selectSubLineas').val())==0)
		{
			obtenerLineasVentas();
			return;
		}
	}
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductosVenta').html('<label><img src="'+ img_loader +'"/> Obteniendo la lista de productos...</label>');
		},
		type:"POST",
		url:base_url+'ventas/obtenerProductosVenta',
		data:
		{
			criterio:	$('#txtBuscarProducto').val(),
			idCliente:	$('#txtIdCliente').val(),
			idLinea:	$('#selectLineas').val(),
			idSubLinea:	$('#selectSubLineas').val()
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

function formularioVentas()
{
	tipoVenta=0;
	
	$('#ventanaVentas').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioVentas').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'clientes/formularioVentas',
		data:
		{
			idCliente: $('#txtIdClientePunto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioVentas').html(data);
			
			if(sistemaActivo=='olyess')
			{
					
			}
			else
			{
				obtenerProductosVenta();
			}
			
			
			
			$('#txtBuscarCodigo').focus();
			fila	= 1;
			ma		= 0;
			
			$("#btnCancelar,#btnVistaPrevia,#btnFactura,#btnVistaPrevia").button("enable");
		},
		error:function(datos)
		{
			$('#formularioVentas').html('');
		}
	});		
}

fila	= 1;

function quitarProductoProducto(n)
{
	$('#filaProducto'+n).remove();
	Fila=0;
	calcularSubTotal();
}

function comprobarDuplicidad(idProducto)
{
	for(i=0;i<=fila;i++)
	{
		if(!isNaN($('#txtIdProducto'+i).val()))
		{
			if(parseInt(idProducto)==parseInt($('#txtIdProducto'+i).val()))
			{
				cantidad	= parseFloat($('#txtCantidadProducto'+i).val())+1;
				total		= parseFloat($('#txtPrecioProducto'+i).val())*cantidad;
				total		= redondear(total);
				
				$('#txtCantidadProducto'+i).val(cantidad);
				$('#txtTotalProducto'+i).val(total);
				$('#filaTotal'+i).html('$'+total);
				
				calcularFilaProducto(i)
				//calcularSubTotal();
				return true;
			}
		}
	}
	
	return false;
}

Fila=0;

function seleccionarFilaProducto(n)
{
	$('.filaProducto').removeClass('seleccionado')
	$('#filaProducto'+n).addClass('seleccionado');
	
	Fila=n;
}

function agregarProductoVenta(n,servicio,rebanada)//n es el numero de fila
{
	filaProducto	= fila;
	
	if(obtenerNumeros($('#txtIdLinea'+n).val())==15)
	{
		if(obtenerNumeros($('#txtIdProducto1').val())==0)
		{
			notify('Agregue primero un producto diferente a servicios',500,5000,'error',30,5);
			return;
		}
	}
	
	if(obtenerNumeros($('#txtIdLinea'+n).val())!=15)
	{
		if(obtenerNumeros($('#txtIdProducto1').val())>0)
		{
			notify('Solo se puede agregar un producto que no sea servicio',500,5000,'error',30,5);
			return;
		}
	}
	
	if(obtenerNumeros($('#txtIdProducto1').val())==0)
	{
		fila=1;
	}
	
	if(rebanada=='si')
	{
		precio			= redondear($('#selectPreciosRebanada'+n).val());
		
		impuestoTotal	= $('#txtImpuestoRebanadaTotal'+n).val();
	}
	else
	{
		precio	= redondear($('#selectPrecios'+n).val());
		
		impuestoTotal	= $('#txtImpuestoTotal'+n).val();
	}
	
	if(precio==0)
	{
		/*notify('El precio es incorrecto',500,5000,'error',30,5);
		return;*/
	}
	
	if(rebanada=='si')
	{
		total	= parseFloat($('#selectPreciosRebanada'+n).val())*1
	}
	else
	{
		total	= parseFloat($('#selectPrecios'+n).val())*1
	}

	total	= redondear(total);
	
	
	producto='<tr id="filaProducto'+fila+'" class="filaProducto" onclick="seleccionarFilaProducto('+fila+')">';
	producto+='<td class="filaProducto" width="80%">';
	producto+='<label id="lblNombreProducto'+fila+'">'+$('#txtNombre'+n).val()+(rebanada=='si'?' - Rebanada':'')+'</label><br />';
	producto+='<label class="informacionUnidad" style="margin-left:0px">';
	
	if($('#txtClaveDescuento').val()!='')
	{
		producto+='<img src="'+base_url+'img/descuento.png" onclick="accesoAsignarDescuento('+fila+')" style="cursor:pointer;" title="Asignar descuento" />';
	}
	
	producto+='<input type="text" maxlength="8" id="txtCantidadProducto'+fila+'" name="txtCantidadProducto'+fila+'" class="cajasCantidades" value="1" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" /> '+$('#txtUnidad'+n).val()+'';
	
	if(sistemaActivo=='olyess')
	{
		if(obtenerNumeros($('#txtIdLinea'+n).val())==15)
		{
			//producto+='<span id="lblPrecioProducto'+fila+'">'+total+'</span>';
			producto+='<input type="text" class="cajas" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
		}
		else
		{
			producto+='<span id="lblPrecioProducto'+fila+'">'+total+'</span>';
			producto+='<input type="hidden" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
		}
			
	}
	else
	{
		producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	}
	
	//producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+precio+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	
	producto+='<br /> <label id="lblDescuento'+fila+'" style="font-size:13px; margin-left:81px">Desc $0.00</label>';
	
	producto+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="lblPedido" style="font-size:14px; color: red"></label>';
	producto+='</td>';
	
	producto+='<td class="filaProducto" id="filaTotal'+fila+'" align="right">';
	producto+='$'+precio;
	producto+='</td>';
	producto+='<td class="filaProducto">';
	producto+='<img src="'+base_url+'img/borrar.png" onclick="quitarProductoProducto('+fila+')" />';
	producto+='</td>';
	

	producto+='<input type="hidden" id="txtNombreProducto'+fila+'" 		name="txtNombreProducto'+fila+'" 		value="'+$('#txtNombre'+n).val()+(rebanada=='si'?' - Rebanada':'')+'" />';
	producto+='<input type="hidden" id="txtNombreProductoOriginal'+fila+'" 										value="'+$('#txtNombre'+n).val()+'" />';
	producto+='<input type="hidden" id="txtUnidadProducto'+fila+'" 		name="txtUnidadProducto'+fila+'" 		value="'+$('#txtUnidad'+n).val()+'" />';
	producto+='<input type="hidden" id="txtCodigoInterno'+fila+'" 		name="txtCodigoInterno'+fila+'" 		value="'+$('#txtCodigoProducto'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalProducto'+fila+'" 		name="txtTotalProducto'+fila+'" 		value="'+total+'" />';
	producto+='<input type="hidden" id="txtIdProducto'+fila+'" 			name="txtIdProducto'+fila+'" 			value="'+$('#txtIDProducto'+n).val()+'" />';
	producto+='<input type="hidden" id="txtServicio'+fila+'" 			name="txtServicio'+fila+'" 				value="'+servicio+'" />';
	producto+='<input type="hidden" id="txtStockDisponible'+fila+'" 	name="txtStockDisponible'+fila+'"  		value="'+$('#txtCantidadTotal'+n).val()+'" />';
	producto+='<input type="hidden" id="txtDescuentoProducto'+fila+'" 	name="txtDescuentoProducto'+fila+'" 	value="0" />';
	producto+='<input type="hidden" id="txtDescuentoPorcentaje'+fila+'" name="txtDescuentoPorcentaje'+fila+'" 	value="0" class="descuentosProductos"/>';
	
	producto+='<input type="hidden" id="txtImpuesto'+fila+'" 			name="txtImpuesto'+fila+'" 				value="'+$('#txtImpuestoNombre'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTasaImpuesto'+fila+'" 		name="txtTasaImpuesto'+fila+'" 			value="'+$('#txtImpuestoTasa'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTipoImpuesto'+fila+'" 		name="txtTipoImpuesto'+fila+'" 			value="'+$('#txtImpuestoTipo'+n).val()+'" />';
	producto+='<input type="hidden" id="txtIdImpuesto'+fila+'" 			name="txtIdImpuesto'+fila+'" 			value="'+$('#txtImpuestoId'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalImpuesto'+fila+'" 		name="txtTotalImpuesto'+fila+'" 		value="'+impuestoTotal+'" />';
	
	producto+='<input type="hidden" id="txtPrecioOriginal'+fila+'" 		 value="'+total+'" />';
	
	
	producto+='<input type="hidden" id="txtRebanadas'+fila+'" 			name="txtRebanadas'+fila+'" 		value="'+rebanada+'" />';
	producto+='<input type="hidden" id="txtNumeroRebanadas'+fila+'" 		name="txtNumeroRebanadas'+fila+'" 		value="'+$('#txtRebanadasNumero'+n).val()+'" />';

	if(sistemaActivo=='olyess')
	{
		producto+='<input type="hidden" id="txtDomicilioProducto'+fila+'"  name="txtDomicilioProducto'+fila+'" 		 value="'+$('#txtDomicilio'+n).val()+'" />';
	}
	
	producto+='</tr>';
	
	//$('#tablaVentas').append(producto); 
	$("#tablaVentas tbody").prepend(producto);
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  
	
	fila	= filaProducto;

	fila++;
	
	$('#txtNumeroProductos').val(fila); 
	$('#carritoVacio').removeClass('Error_validar');
	$('#carritoVacio').html('');
	
	calcularSubTotal();
	
	setTimeout(function() 
	{
		if(sistemaActivo=='olyess')
		{
			if(obtenerNumeros($('#txtPedidoActivo').val())=='1')
			{
				$('.lblPedido').html('Pedido 1')
				
				
			}
		}
		
		$('#txtBuscarCodigo').val('');
		$('#txtBuscarCodigo').focus();
		
		
		
	}, 200);
}

function obtenerProductoCodigo()
{
	if(!camposVacios($('#txtBuscarCodigo').val()))
	{
		return;
	}
	
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoVenta').html('<label><img src="'+ img_loader +'"/> Obteniendo producto...</label>');
		},
		type:"POST",
		url:base_url+'ventas/obtenerProductoCodigo',
		data:
		{
			codigoBarras:	$('#txtBuscarCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoVenta').html(data);
			var datos	= $.parseJSON(data);
			
			if(datos.idProducto==0)
			{
				notify('El producto no se encuentra registrado',500,5000,'error',30,5);
			}
			else
			{
				agregarProductoCodigo(datos);
			}
			
		},
		error:function(datos)
		{
			$('#obtenerProductosVenta').html('');
		}
	});		
}


function agregarProductoCodigo(Producto)//n es el numero de fila
{
	if(comprobarDuplicidad(Producto.idProducto))
	{
		setTimeout(function() 
		{
			$('#txtBuscarCodigo').val('');
			$('#txtBuscarCodigo').focus();
		}, 200);
		
		return;
	}
	
	/*precio		= redondear(Producto.precioA);
	impuesto	= (Producto.tasa/100)*precio

	total		= parseFloat(Producto.precioA)+impuesto
	total		= redondear(total);*/
	
	precio		= redondear(Producto.precioImpuestos);
	impuesto	= Producto.precioImpuestos-Producto.precioA

	total		=precio
	total		= redondear(total);
	
	
	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td class="filaProducto" width="80%">';
	producto+='<label>'+Producto.nombre+'</label><br />';
	producto+='<label class="informacionUnidad" style="margin-left:0px">';
	
	if($('#txtClaveDescuento').val()!='')
	{
		producto+='<img src="'+base_url+'img/descuento.png" onclick="accesoAsignarDescuento('+fila+')" style="cursor:pointer" title="Asignar descuento" />';
	}
	
	producto+='<input type="text" maxlength="8" id="txtCantidadProducto'+fila+'" name="txtCantidadProducto'+fila+'" class="cajasCantidades" value="1" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" /> '+(Producto.unidad!=null?Producto.unidad:'')+'';
	
	if(sistemaActivo=='olyess')
	{
		producto+=total+'<input type="hidden" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';	
	}
	else
	{
		producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	}

	producto+='<br /> <label id="lblDescuento'+fila+'" style="font-size:13px; margin-left:81px">Desc $0.00</label>';
	producto+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="lblPedido" style="font-size:14px; color: red">'+'</label>';
	producto+='</td>';
	
	producto+='<td class="filaProducto" id="filaTotal'+fila+'" align="right">';
	producto+='$'+total;
	producto+='</td>';
	producto+='<td class="filaProducto">';
	producto+='<img src="'+base_url+'img/borrar.png" onclick="quitarProductoProducto('+fila+')" />';
	producto+='</td>';
	
	producto+='<input type="hidden" id="txtNombreProducto'+fila+'" 		name="txtNombreProducto'+fila+'" 		value="'+Producto.nombre+'" />';
	producto+='<input type="hidden" id="txtUnidadProducto'+fila+'" 		name="txtUnidadProducto'+fila+'" 		value="'+Producto.unidad+'" />';
	producto+='<input type="hidden" id="txtCodigoInterno'+fila+'" 		name="txtCodigoInterno'+fila+'" 		value="'+Producto.codigoInterno+'" />';
	producto+='<input type="hidden" id="txtTotalProducto'+fila+'" 		name="txtTotalProducto'+fila+'" 		value="'+total+'" />';
	producto+='<input type="hidden" id="txtIdProducto'+fila+'" 			name="txtIdProducto'+fila+'" 			value="'+Producto.idProducto+'" />';
	producto+='<input type="hidden" id="txtServicio'+fila+'" 			name="txtServicio'+fila+'" 				value="'+Producto.servicio+'" />';
	producto+='<input type="hidden" id="txtStockDisponible'+fila+'" 	name="txtStockDisponible'+fila+'"  		value="'+Producto.stock+'" />';
	producto+='<input type="hidden" id="txtDescuentoProducto'+fila+'" 	name="txtDescuentoProducto'+fila+'" 	value="0" />';
	producto+='<input type="hidden" id="txtDescuentoPorcentaje'+fila+'" name="txtDescuentoPorcentaje'+fila+'" 	value="0" class="descuentosProductos"/>';
	
	producto+='<input type="hidden" id="txtImpuesto'+fila+'" 			name="txtImpuesto'+fila+'" 				value="'+Producto.impuesto+'" />';
	producto+='<input type="hidden" id="txtTasaImpuesto'+fila+'" 		name="txtTasaImpuesto'+fila+'" 			value="'+Producto.tasa+'" />';
	producto+='<input type="hidden" id="txtTipoImpuesto'+fila+'" 		name="txtTipoImpuesto'+fila+'" 			value="'+Producto.tipoImpuesto+'" />';
	producto+='<input type="hidden" id="txtIdImpuesto'+fila+'" 			name="txtIdImpuesto'+fila+'" 			value="'+Producto.idImpuesto+'" />';
	producto+='<input type="hidden" id="txtTotalImpuesto'+fila+'" 		name="txtTotalImpuesto'+fila+'" 		value="'+impuesto+'" />';
	
	producto+='</tr>';
	
	//$('#tablaVentas').append(producto); 
	$("#tablaVentas tbody").prepend(producto);
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  

	fila++;
	
	$('#txtNumeroProductos').val(fila); 
	$('#carritoVacio').removeClass('Error_validar');
	$('#carritoVacio').html('');
	
	calcularSubTotal();
	
	setTimeout(function() 
	{
		if(sistemaActivo=='olyess')
		{
			if(obtenerNumeros($('#txtPedidoActivo').val())=='1')
			{
				$('.lblPedido').html('Pedido 1')
			}
		}
		
		$('#txtBuscarCodigo').val('');
		$('#txtBuscarCodigo').focus();
	}, 200);
}

function calcularFilaProducto(n)
{
	cantidad			= obtenerNumeros($('#txtCantidadProducto'+n).val());
	tasa				= obtenerNumeros($('#txtTasaImpuesto'+n).val());
	precio				= obtenerNumeros($('#txtPrecioProducto'+n).val());
	descuentoPorcentaje	= obtenerNumeros($('#txtDescuentoPorcentaje'+n).val());
	
	precio				= precio / (1+(tasa/100));

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
	impuestoProducto	= importe*(tasa/100);
	importe				+=impuestoProducto
	
	//subTotal			= importe / (1+(tasa/100));
	//impuestoProducto	= importe-subTotal;
	
	$('#filaTotal'+n).html('$'+redondear(importe))
	$('#txtTotalProducto'+n).val(redondear(importe))
	$('#txtTotalImpuesto'+n).val(redondear(impuestoProducto))
	
	$('#lblDescuento'+n).html('Desc $'+redondear(descuento))
	$('#txtDescuentoProducto'+n).val(redondear(descuento))
	
	calcularSubTotal();
}


/*function calcularFilaProducto(n)
{
	cantidad			= obtenerNumeros($('#txtCantidadProducto'+n).val());
	tasa				= obtenerNumeros($('#txtTasaImpuesto'+n).val());
	precio				= obtenerNumeros($('#txtPrecioProducto'+n).val());
	descuentoPorcentaje	= obtenerNumeros($('#txtDescuentoPorcentaje'+n).val());
	
	precioSinImpuesto	= precio / (1+(tasa/100));

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
}*/

subTotal	= 0; //El subtotal de la venta


function productoCodigo() 
{
	$("#cantidad1").val(1);
	agregarProductoVenta(1);
	obtenerProductosTienda();

	return;
}

subTotal	= 0;
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
			subTotal			+=precio+descuentoProducto;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
			
			vacio=true;
		}
	}
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	$('#txtDescuentoProducto0').val(descuento);
	$('#filaDescuento').html('DESC: $'+redondear(totalesDescuentos))
	
	$('#filaSubTotal').html('SUBTOTAL: $'+redondear(subTotal-totalImpuestos));
	$('#filaIva').html('IMPUESTOS: $'+redondear(totalImpuestos));
	//$('#filaTotal').html('TOTAL: $'+redondear(subTotal+totalImpuestos));
	$('#filaTotal').html('TOTAL: $'+redondear(subTotal-totalesDescuentos));
	$('#txtTotalPrevio').val(redondear(subTotal-totalesDescuentos))
	
	if(!vacio)
	{
		$('#carritoVacio').addClass('Error_validar');
		$('#carritoVacio').html('Carrito de ventas vacio');
	}
}

function calcularTotales() //Calular el total de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	
	for(i=0;i<=fila;i++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+i).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+i).val());
		
		if(precio>0)
		{
			subTotal			+=precio+descuentoProducto;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
		}
	}

	$('#txtSubTotal').val(redondear(subTotal-totalImpuestos));
	
	subTotal			-=descuento;
	$('#txtDescuentoTotal').val(redondear(totalesDescuentos));

	total		= subTotal

	$('#txtTotal').val(redondear(subTotal-totalesDescuentos))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	calcularCambio();
}


/*function calcularSubTotal() //Calular el subtotal de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	vacio				= false; //El carrito esta vacio
	
	for(i=0;i<fila;i++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+i).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+i).val());
		
		if(precio>0)
		{
			subTotal			+=precio;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
			
			vacio=true;
		}
	}
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	$('#txtDescuentoProducto0').val(descuento);
	$('#filaDescuento').html('DESC: $'+redondear(totalesDescuentos))
	
	$('#filaSubTotal').html('SUBTOTAL: $'+redondear(subTotal-totalImpuestos));
	$('#filaIva').html('IVA: $'+redondear(totalImpuestos));
	//$('#filaTotal').html('TOTAL: $'+redondear(subTotal+totalImpuestos));
	$('#filaTotal').html('TOTAL: $'+redondear(subTotal));
	
	if(!vacio)
	{
		$('#carritoVacio').addClass('Error_validar');
		$('#carritoVacio').html('Carrito de ventas vacio');
	}
}
*/
/*function calcularTotales() //Calular el total de la venta
{
	subTotal		= 0;
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
	
	//$('#txtSubTotal').val(redondear(subTotal));
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
	
	//iva			= parseFloat($('#selectIva').val())/100;
	//total		= subTotal+totalImpuestos
	total		= subTotal

	$('#txtTotal').val(redondear(total))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	calcularCambio();
}*/

function calcularCambio()
{
	total	=parseFloat($('#txtTotal').val());
	pago	=parseFloat($('#txtPago').val());
	
	if(isNaN(pago))
	{
		pago=0;
	}
	
	cambio	= pago-total;
	cambio	= redondear(cambio);
	
	if(cambio<0)
	{
		cambio="0.00";
	}
	
	$('#txtCambio').val(cambio)
	$('#lblCambioCliente').html('Cambio: $'+cambio)
}

$(document).ready(function()
{
	/*$('#ventanaCobrosVenta').keypress(function(e) 
	{
		if (e.keyCode == $.ui.keyCode.ENTER) 
		{
			registrarVenta()
		}
	});*/
	
	$("#ventanaCobrosVenta").dialog(
	{
		autoOpen:false,
		//show: { effect: "scale", duration: 600 },
		height:550,
		width:890,
		modal:true,
		resizable:false,
		buttons: 
		{
			"cancelar" : 
			{
				text: "Cancelar",
				id: "btnCancelar",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	},
			
			/*"vista": 
			{
				text: "Vista previa",
				id: "btnVistaPrevia",
				click: function()
				{
					vistaPreviaVentaFactura()
				}   
		  	},*/
			/*"Factura": 
			{
				text: "Factura",
				id: "btnFactura",
				click: function()
				{
					registrarVentaFactura()
				}   
		  	},*/
			"Aceptar": 
			{
				text: "Aceptar",
				id: "btnAceptar",
				click: function()
				{
					if($('#txtCambioActivo').val()=="0")
					{
						registrarVenta()
					}
					else
					{
						$('#ventanaCobrosVenta').dialog('close');
						//formularioVentas();
					}
				}   
		  	} 
		},
		close: function() 
		{
			//$('#ventanaCobrosVenta').dialog('option', 'title', 'La piñata');
			$('#ventanaCobrosVenta').dialog('option', 'title', 'Cobro de ventas');
			
			if($('#txtCambioActivo').val()=="0")
			{
				$("#formularioCobros").html('');
				
			}
			else
			{
				$('#ventanaCobrosVenta').dialog('close');
				formularioVentas();
			}
			
			
		}
	});
});

function formularioCobros()
{
	limiteVentas	= obtenerNumeros($('#txtLimiteVentas').val());
	ventasF4		= obtenerNumeros($('#txtVentasF4').val());
	totalPrevio		= obtenerNumeros($('#txtTotalPrevio').val());
	
	if(subTotal==0)
	{
		notify('El subtotal de la venta es incorrecto',500,5000,'error',30,5);
		return;	
	}
	
	if(obtenerNumeros($("#txtPedidoActivo").val())==1)
	{
		/*if($('#selectDirecciones').val()=="0")
		{
			notify('Seleccione la dirección de entrega',500,5000,'error',30,5);
			return;	
		}*/
	}
	
	if(limiteVentas<(ventasF4+totalPrevio))
	{
		notify('Esta superando el total de las ventas F4',500,5000,'error',30,5);
		return;	
	}
	
	$('#ventanaCobrosVenta').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioCobros').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cobros');
		},
		type:"POST",
		url:base_url+'clientes/formularioCobros',
		data:
		{
			diasCredito: $('#txtCreditoDias').val(),
			reutilizar: 0
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCobros').html(data);
			$('#txtPago').focus();
			calcularTotales();
			obtenerFolio();
			
			if(obtenerNumeros($("#txtPedidoActivo").val())==0)
			{
				$('#filaAcrilicoCobros').fadeOut(1);
			}
		},
		error:function(datos)
		{
			$('#formularioCobros').html('');
		}
	});		
}
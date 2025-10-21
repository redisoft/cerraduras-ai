//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//PARA LA FACTURA DIRECTAMENTE
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function registrarVentaFactura()
{
	mensaje			= "";

	m=0;
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(parseFloat($("#txtSubTotal").val())==0)
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}
		
	if(!comprobarNumeros($("#txtPago").val()) || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
	{
		mensaje+="El pago es incorrecto <br />";
	}

	if($('#TipoPago').val()=="2")
	{
		if($('#numeroCheque').val()=="")
		{
			//mensaje+="El numero de tarjeta es invalido <br />";
		}
	}

	if($('#TipoPago').val()=="3")
	{
		if($('#numeroTransferencia').val()=="")
		{
			//mensaje+="El numero de transferencia es invalido <br />";
		}
	}

	/*if($('#cuentasBanco').val()=="0")
	{
		mensaje+="Debe seleccionar una cuenta y un banco <br />";
	}*/
	
	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	

	v=0;
	
	for(i=0;i<fila;i++)
	{
		precio		= parseFloat($('#txtTotalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			//totalKit+=precio
			
			/*productos[v]		= $('#txtIdProducto'+i).val();
			nombres[v]			= $('#txtNombreProducto'+i).val();
			cantidad[v]			= $('#txtCantidadProducto'+i).val();
			totales[v]			= $('#txtTotalProducto'+i).val();
			precioProducto[v]	= $('#txtPrecioProducto'+i).val();
			servicios[v]		= $('#txtServicio'+i).val();
			descuentos[v]		= $('#txtDescuentoPorcentaje'+i).val()+'|'+$('#txtDescuentoProducto'+i).val();
			
			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}*/
			/*
			if($('#txtIdPeriodo'+i).val()!="8")
			{
				if($('#txtFechaInicio'+i).val()=="")
				{
					notify('Por favor configure la fecha de inicio correctamente de los servicios',500,5000,'error',30,0);
					$('#txtFechaInicio'+i).focus()
					return;
				}
			}*/

			v++;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	//if(!confirm('¿Realmente deseea realizar la venta y factura?')) return;
	
	faltantes	= comprobarFaltantesProductos();
	
	if(numeroFaltantes==0)
	{
		if(!confirm('¿Realmente deseea realizar la venta y factura?')) return;
	}
	
	if(numeroFaltantes>0)
	{
		formularioInventarioFaltante()
		return;
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta y factura, por favor espere ...');},
		type:"POST",
		url:base_url+'clientes/registrarVentaFactura',
		data: $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&metodoPagoTexto='+$("#selectMetodoPago option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			data	=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(''+data[1],500,5000,'error',30,3);
				break;

				case "1":
					window.open(base_url+'pdf/crearFactura/'+data[2]+'/2');
					notify('La factura se ha realizado correctamente',500,5000,'',30,3);
					formularioVentas();
					subTotal	= 0;
					$('#ventanaCobrosVenta').dialog('close');
					
					if($('#txtIdTienda').val()!="0")
					{
						obtenerVentas();	
					}					
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCobroVenta").html('');
			notify('Error al realizar la factura, por favor verifique la conexión a internet',500,5000,'error',30,3);
		}
	});		
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//PARA LA PREVIA DE LA FACTURA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

function realizarVentaPrevia()
{
	mensaje			= "";
	productos		= new Array();
	cantidad		= new Array();
	totales			= new Array();
	precioProducto	= new Array();
	servicios		= new Array();
	fechas			= new Array();
	nombres			= new Array();
	descuentos		= new Array();
	
	m=0;

	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(parseFloat($("#txtSubTotal").val())==0)
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}
		
	/*if(Solo_Numerico($("#txtPago").val())=="")// || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
	{
		mensaje+="El pago es incorrecto <br />";
	}

	if($('#TipoPago').val()=="2")
	{
		if($('#numeroCheque').val()=="")
		{
			mensaje+="El numero de tarjeta es invalido <br />";
		}
	}

	if($('#TipoPago').val()=="3")
	{
		if($('#numeroTransferencia').val()=="")
		{
			mensaje+="El numero de transferencia es invalido <br />";
		}
	}

	if($('#cuentasBanco').val()=="0")
	{
		mensaje+="Debe seleccionar una cuenta y un banco <br />";
	}*/
	
	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	
	v=0;
	
	for(i=0;i<fila;i++)
	{
		precio	= parseFloat($('#txtTotalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			//totalKit+=precio
			
			productos[v]		= $('#txtIdProducto'+i).val();
			nombres[v]			= $('#txtNombreProducto'+i).val();
			cantidad[v]			= $('#txtCantidadProducto'+i).val();
			totales[v]			= $('#txtTotalProducto'+i).val();
			precioProducto[v]	= $('#txtPrecioProducto'+i).val();
			servicios[v]		= $('#txtServicio'+i).val();
			descuentos[v]		= $('#txtDescuentoPorcentaje'+i).val()+'|'+$('#txtDescuentoProducto'+i).val();
			//servicios[v]		=$('#txtIdPeriodo'+i).val();
			//fechas[v]			=$('#txtFechaInicio'+i).val();
			
			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}
			
			/*if($('#txtIdPeriodo'+i).val()!="8")
			{
				if($('#txtFechaInicio'+i).val()=="")
				{
					notify('Por favor configure la fecha de inicio correctamente de los servicios',500,5000,'error',30,0);
					$('#txtFechaInicio'+i).focus()
					return;
				}
			}*/

			v++;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente deseea ver la previa?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> El sistema esta preparando una previa de la factura, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/realizarVentaPrevia',
		data:
		{
			"productos":			productos,
			"cantidad":				cantidad,
			"servicios":			servicios,
			fechas:					fechas,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			nombres:				nombres,
			descuentos:				descuentos,
			
			"ivaPorcentaje":		$("#selectIva").val(),
			"iva":					$("#txtIvaTotal").val(),
			"subTotal":				$("#txtSubTotal").val(),
			"total":				$("#txtTotal").val(),
			
			"descuento":			$("#txtDescuentoTotal").val(),
			"descuentoPorcentaje":	$("#txtDescuentoPorcentaje0").val(),
			
			
			"idCliente":			$("#txtIdCliente").val(),
			"idDivisa":				$("#selectDivisas").val(),
			"pago":					$("#txtPago").val(),
			"cambio":				$("#txtCambio").val(),
			
			"idCuenta":				$('#cuentasBanco').val(), //Si es venta se agregan esta información
			"numeroCheque":			$('#numeroCheque').val(),
			"numeroTransferencia":	$('#numeroTransferencia').val(),
			"nombreReceptor":		$('#txtNombreReceptor').val(),
			//"formaPago":			$('#TipoPago').val(),
			"idForma":				$('#selectFormas').val(),
			"banco":				$('#listaBancos').val(),
			"observaciones":		$('#txtObservacionesVenta').val(),
			
			"condiciones":			$('#txtCondicionesPago').val(),
			"formaDePago":			$('#txtFormaPago').val(),
			"idEmisor":				$('#selectEmisores').val(),
			
			"metodoPago":			$("#selectMetodoPago").val(),
			"metodoPagoTexto":		$("#selectMetodoPago option:selected").text(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			
			switch(parseInt(data))
			{
				case 0:
				notify('Error al realizar la previa de la factura',500,5000,'error',30,3);
				break;
				
				/*case "stock":
					notify('El sistema ha detectado que no hay suficientes productos para realizar la previa',500,5000,'error',30,3);
				break;*/
				
				case 2:
				
					notify('El cliente seleccionado no tiene los datos fiscales necesario para crear la factura',500,5000,'error',30,3);
				break;
				
				case 1:
				window.open(base_url+'pdf/vistaPrevia');
				notify('La previa se ha realizado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCobroVenta").html('');
			notify('Error al realizar la previa, por favor verifique la conexión a internet',500,5000,'error',30,3);
		}
	});		
}

//LA VISTA PREVIA DE LA VENTA Y FACTURA NO REALIZADA
function vistaPreviaVentaFactura()
{
	mensaje			= "";	
	m=0;
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(parseFloat($("#txtSubTotal").val())==0)
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}
		
	if(!comprobarNumeros($("#txtPago").val()))// || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
	{
		mensaje+="El pago es incorrecto <br />";
	}

	if($('#TipoPago').val()=="2")
	{
		if($('#numeroCheque').val()=="")
		{
			//mensaje+="El numero de tarjeta es invalido <br />";
		}
	}

	if($('#TipoPago').val()=="3")
	{
		if($('#numeroTransferencia').val()=="")
		{
			//mensaje+="El numero de transferencia es invalido <br />";
		}
	}

	/*if($('#cuentasBanco').val()=="0")
	{
		mensaje+="Debe seleccionar una cuenta y un banco <br />";
	}*/
	
	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}

	if(numeroFaltantes==0)
	{
		if(!confirm('¿Realmente deseea crear la vista previa?')) return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la vista previa');},
		type:"POST",
		url:base_url+'reportes/vistaPreviaVentaFactura',
		data: $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&metodoPagoTexto='+$("#selectMetodoPago option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			
			window.location.href=base_url+'reportes/descargarPdfPrevia/vistaPrevia/vistaPrevia';
			notify('La previa de la factura se ha realizado correctamente',500,5000,'',30,3);	
		},
		error:function(datos)
		{
			$("#registrandoCobroVenta").html('');
			notify('Error al realizar la previa',500,5000,'error',30,3);
		}
	});		
}

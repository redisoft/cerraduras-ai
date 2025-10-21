$(document).ready(function()
{
	//$('.ajax-pagProcesadas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagProcesadas > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerProcesadas";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarVenta').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo la lista de ventas');},
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
	
	$("#ventanaEditarVenta").dialog(
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
			'Cobrar': function() 
			{
				formularioCobrosEditar()	  	  
			},
		},
		close: function() 
		{
			$("#obtenerVentaEditar").html('');
		}
	});
	
	$("#txtBuscarVenta").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProcesadas();
		}, milisegundos);
	});
});

function obtenerVentaEditar(idCotizacion)
{
	$("#ventanaEditarVenta").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentaEditar').html('<img src="'+ img_loader +'"/> Obteniendo detalles de venta...');
		},
		type:"POST",
		url:base_url+'cotizaciones/obtenerVentaEditar',
		data:
		{
			idCotizacion:	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentaEditar').html(data);
			fila	= parseInt($('#txtNumeroProductos').val());
			fila++;
			obtenerProductosVenta();
			obtenerFolio();
			calcularTotales();
		},
		error:function(datos)
		{
			$('#obtenerVentaEditar').html('');
		}
	});		
}

function obtenerProcesadas()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProcesadas').html('<img src="'+ img_loader +'"/> Obteniendo la lista de ventas...');
		},
		type:"POST",
		url:base_url+'cotizaciones/obtenerProcesadas',
		data:
		{
			criterio:	$('#txtBuscarVenta').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProcesadas').html(data);
		},
		error:function(datos)
		{
			$('#obtenerProcesadas').html('');
		}
	});		
}

function formularioCobrosEditar()
{
	if(subTotal==0)
	{
		notify('El subtotal de la venta es incorrecto',500,5000,'error',30,5);
		return;	
	}
	
	$('#ventanaCobrosVentaEditar').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCobros').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cobros');
		},
		type:"POST",
		url:base_url+'clientes/formularioCobros',
		data:
		{
			diasCredito: $('#txtCreditoDias').val(),
			reutilizar: 1,
			idCotizacion: $('#txtIdCotizacionReutilizar').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCobros').html(data);
			$('#txtPago').focus();
			calcularTotales();
		},
		error:function(datos)
		{
			$('#formularioCobros').html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaCobrosVentaEditar").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:890,
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
				reutilizarVenta();	  	  
			},
		},
		close: function() 
		{
			$("#formularioCobrosEditar").html('');
		}
	});
});

function reutilizarVenta()
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

	if($("#txtSubTotal").val()=="0")
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}

	if(!comprobarNumeros($("#txtPago").val()))// || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
	{
		mensaje+="El pago es incorrecto <br />";
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
	
	v	=0;
	ban	=0;
	
	for(i=0;i<fila;i++)
	{
		precio	= parseFloat($('#txtTotalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			ban			=1;
			
			productos[v]		= $('#txtIdProducto'+i).val();
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
			

			v++;
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
	
	if(!confirm('¿Realmente deseea realizar la venta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/registrarVenta',
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
			"idForma":				$('#selectFormas').val(),
			"banco":				$('#listaBancos').val(),
			"condicionesPago":		$('#txtCondicionesPago').val(),
			"formaDePago":			$('#txtFormaPago').val(),
			"facturar":				document.getElementById('chkFacturar').checked?1:0,
			"diasCredito":			$('#txtDiasCredito').val(),
			"mostrador":			$('#selectMostrador').val(),
			"observaciones":		$('#txtObservacionesVenta').val(),
			"fechaVenta":			$('#txtFechaVenta').val(),
			"idTienda":				$('#txtIdTienda').val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					subTotal	= 0;
					$('#ventanaCobrosVentaEditar').dialog('close');
					$('#ventanaEditarVenta').dialog('close');
					
					obtenerProcesadas();	
				
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
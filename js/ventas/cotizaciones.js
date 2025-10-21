//PROCESAR LA COTIZACIÓN
$(document).ready(function()
{
	$("#ventanaProcesarCotizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Cancelar" : 
			{
				text: "Cancelar",
				id: "btnCancelarCotizacion",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	},
			"Aceptar" : 
			{
				text: "Aceptar",
				id: "btnRegistrarCotizacion",
				click: function()
				{
					registrarCotizacion()
				}   
		  	},
		},
		
		
		close: function() 
		{
			$("#formularioProcesarCotizacion").html('');
			activarBotonesCotizacion()
		}
	});
});
function desactivarBotonesCotizacion()
{
	$("#btnCancelarCotizacion,#btnRegistrarCotizacion").button("disable");
}

function activarBotonesCotizacion()
{
	$("#btnCancelarCotizacion,#btnRegistrarCotizacion").button("enable");
}

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
	
	desactivarBotonesCotizacion();
	
	if(ejecutarAccion && ejecutarAccion.readyState != 4)
	{
		notify('Ya se esta procesando el registro',500,5000,'error',30,5);
		return;
	}
	
	
	if($("#txtSubTotal").val()=="0" || parseFloat($("#txtSubTotal").val())=="0")
	{
		mensaje+="No se han agregado productos para la cotización <br />";
	}
	
	if($('#txtIdUsuarioVendedor').val()=="0")
	{
		mensaje+="Seleccione el vendedor <br />";
	}
	
	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}

	if ($('#selectMostrador').val() == "1" && $('#selectDirecciones').val() == "0")
	{
		mensaje+="Seleccione la dirección de envío <br />";
	}
	
	v=0;
	
	for(i=0;i<=fila;i++)
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
		activarBotonesCotizacion()
		return;
	}
	
	//faltantes	= comprobarFaltantesProductos();

	//if(faltantes.length==0)
	//{
		/*if(!confirm('¿Realmente deseea realizar la cotización?'))
		{
			$('#formularioProcesarCotizacion').html('');
			return;
		}*/
	//}
	
	/*if(faltantes.length>0)
	{
		if(!confirm('Alerta, los siguientes productos no tienen suficiente inventario: \n\n'+faltantes+'\n ¿Desea proceder?')) return;
	}*/

	
	//if(!confirm('¿Realmente deseea realizar la cotización?')) return;
	
	ejecutarAccion=$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoCotizacion').html('<img src="'+ img_loader +'"/> Se esta realizando la cotización, por favor tenga paciencia ...');},
		type:"POST",
		//url:base_url+'clientes/registrarCotizacion',
		url:base_url+'clientes/registrarVenta',
		//data: $('#frmVentasClientes').serialize()+'&'+$('#frmProcesarCotizacion').serialize(),
		data: $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&tipoVenta='+tipoVenta+'&cotizacion=1'+'&tipoEnvio=' + $("#selectMostrador option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCotizacion").html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					activarBotonesCotizacion()
				break;
				
				case "1":
					
					$('#ventanaProcesarCotizacion').dialog('close');
					formularioVentas()
					notify('La cotización se ha registrado correctamente',500,5000,'',30,5);
					//window.open(base_url+'pdf/cotizacionPdf/'+data[2]+'/1');
					
					obtenerTicket(data[1]);
					fila	= 1;
					ma		= 0;
					
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCotizacion").html('');
			notify('Error al realizar la cotización, por favor verifique la conexión a internet',500,5000,'error',30,5);
			activarBotonesCotizacion()
		}
	});		
}



/*fila=1;

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
	
	
	total		= subTotal

	$('#txtTotal').val(redondear(total))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	
	calcularCambio();
}
*/

function formularioProcesarCotizacion()
{
	$("#formularioCobros").html('');
	
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
	
	//return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioProcesarCotizacion').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cotización');
		},
		type:"POST",
		//url:base_url+'clientes/formularioProcesarCotizacion',
		url:base_url+'clientes/formularioCobrosCotizaciones',
		data:
		{
			diasCredito: 	$('#txtCreditoDias').val(),
			reutilizar: 	0,
			idUsuario: 		$('#txtIdUsuarioVendedorPunto').val(),
			usuario: 		$('#txtBuscarUsuarioPunto').val(),
			idCliente: 		$('#txtIdCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProcesarCotizacion').html(data);
			calcularTotales();
			
			//registrarCotizacion();
		},
		error:function(datos)
		{
			$('#formularioProcesarCotizacion').html('');
		}
	});		
}

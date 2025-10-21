//PARA LA FACTURACIÓN ELECTRONICA
//--------------------------------------------------------------------------------------------------------------//
function obtenerDatosFactura(idCotizacion)
{
	$('#ventanaFacturacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDatosFactura').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar crear la factura...');
		},
		type:"POST",
		url:base_url+"facturacion/obtenerDatosFactura",
		data:
		{
			"idCotizacion":idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDatosFactura').html(data);
			obtenerFolio();
		},
		error:function(datos)
		{
			$('#obtenerDatosFactura').html('');
		}
	});//Ajax	
}

function vistaPrevia()
{
	desactivarBotonesFactura();
	
	if(ejecutarAccion && ejecutarAccion.readyState != 4)
	{
		notify('Ya se esta procesando el registro',500,5000,'error',30,5);
		return;
	}
	
	if(parseInt($('#txtTotalParcialFactura').val())>0)
	{
		notify('Ya se han creado las facturas',500,5000,'error',30,5);
		activarBotonesFactura()
		return;
	}
	
	if(parseInt($('#txtParcial').val())>1)
	{
		notify('La vista previa debe ser parcial',500,5000,'error',30,5);
		activarBotonesFactura()
		return;
	}
	
	productos		=new Array();
	var mensaje		="";
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	if($('#selectDireccionesCfdi').val()=="0")
	{
		mensaje+="Seleccione la dirección <br />";
	}
	
	
	/*if($('#txtMetodoPago').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}*/
	
	/*if($('#txtCondiciones').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
	}*/
	
	for(i=1;i<=parseInt($('#txtNumeroProductosFactura').val());i++)
	{
		productos[i]	=$('#txtDescripcionProductoFactura'+i).val();
		
		if($('#txtDescripcionProductoFactura'+i).val()=="")	
		{
			mensaje	+="La descripción del producto es incorrecta";
			break;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		activarBotonesFactura()
		return;
	}
	
	if(!confirm('¿Realmente desea realizar la vista previa?'))
	{
		activarBotonesFactura()
		return;
	}

	ejecutarAccion=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturando').html('<img src="'+ img_loader +'"/> Se esta generando la vista previa, por favor espere...');
		},
		type:"POST",
		url:base_url+"reportes/vistaPreviaFactura",
		data: $('#frmFacturacion').serialize() + "&documento=FACTURA&tipoComprobante=ingreso&metodoPagoTexto="
			+ $("#txtMetodoPago option:selected").text() + "&formaPagoTexto=" + $("#txtFormaPago option:selected").text()
			+ "&usoCfdiTexto=" + $("#selectUsoCfdi option:selected").text(),
		/*data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"condiciones":		$('#txtCondiciones').val(),			
			"retencion":		$('#txtRetencion').val(),
			"tasa":				$('#txtTasa').val(),
			"nombre":			$('#txtNombreRetencion').val(),
			"parcial":			0,
			"idDivisa":			$('#selectDivisas').val(),
			"idEmisor":			$('#selectEmisores').val(),
			productos:			productos,
			"observaciones":	$('#txtObservaciones').val(),
			"subTotal":			$('#txtSubTotal').val(),
			"descuento":		$('#txtDescuento').val(),
			"ivaPorcentaje":	$('#txtIvaPorcentaje').val(),
			"iva":				$('#txtIvaCfdi').val(),
			"tasaIeps":			$('#txtIeps').val(),
			"totalIeps":		$('#txtIepsTotal').val(),
			"retencionIva":		$('#txtRetencionIvaTotal').val(),
			"tasaRetencionIva":	$('#txtRetencionIva').val(),
			"retencionIeps":	$('#txtRetencionIepsTotal').val(),
			"tasaRetencionIeps":$('#txtRetencionIeps').val(),
			"total":			$('#txtTotalCfdi').val(),
			"folioActual":		$('#txtFolioActual').val(),
			"formaPago":		$('#txtFormaPago').val(),
			"formaPagoTexto":	$("#txtFormaPago option:selected").text(),
			"cuentaPago":		$('#txtCuentaPago').val(),
			"metodoPago":		$("#txtMetodoPago").val(),
			"metodoPagoTexto":	$("#txtMetodoPago option:selected").text(),
			"usoCfdi":			$("#selectUsoCfdi").val(),
			"usoCfdiTexto":		$("#selectUsoCfdi option:selected").text(),
			"idDireccion":		$("#selectDireccionesCfdi").val(),
			"fechaFactura":		$('#txtFechaFactura').val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			data	=parseInt(data);
			
			$('#facturando').html('')
			
			window.location.href=base_url+'reportes/descargarPdfPrevia/vistaPrevia/vistaPrevia';
			notify('La previa de la factura se ha realizado correctamente',500,5000,'',30,3);
			
			activarBotonesFactura();
		},
		error:function(datos)
		{
			notify('Error al generar la vista previa',500,5000,'error',30,5);
			$('#facturando').html('')
			
			activarBotonesFactura()
		}
	});		
}

function calcularRetenciones()
{
	subTotal		= obtenerNumero($('#txtSubTotal').val());
	descuento		= obtenerNumero($('#txtDescuento').val());
	suma			= obtenerNumero($('#txtSuma').val());
	
	sumaSubTotal	= obtenerNumero($('#txtSuma').val());
	
	iva				= obtenerNumero($('#txtIvaPorcentaje').val());
	total			= obtenerNumero($('#txtTotal').val());
	
	ieps			= obtenerNumero($('#txtIeps').val());
	retencionIeps	= obtenerNumero($('#txtRetencionIeps').val());
	retencionIva	= obtenerNumero($('#txtRetencionIva').val());
	
	iva				= iva>0?iva/100:0;
	ieps			= ieps>0?ieps/100:0;
	retencionIeps	= retencionIeps>0?retencionIeps/100:0;
	retencionIva	= retencionIva>0?retencionIva/100:0;
	
	iepsTotal			= 0;
	retencionIespTotal	= 0;
	retencionIvaTotal	= 0;
	

	if(document.getElementById('chkIeps').checked)
	{
		iepsTotal		= ieps*suma;
		//suma			= ieps+suma;
	}
	
	ivaTotal		= iva*(suma+iepsTotal);
	
	if(document.getElementById('chkRetencionIeps').checked)
	{
		retencionIespTotal		= retencionIeps*suma;
	}
	
	if(document.getElementById('chkRetencionIva').checked)
	{
		retencionIvaTotal		= retencionIva*suma;
	}
	
	total	= suma+iepsTotal+ivaTotal-retencionIespTotal-retencionIvaTotal;
	
	$('#lblIeps').html('$'+redondear(iepsTotal));
	$('#lblRetencionIeps').html('$'+redondear(retencionIespTotal));
	$('#lblRetencionIva').html('$'+redondear(retencionIvaTotal));
	
	$('#lblIva').html('$'+redondear(ivaTotal));
	$('#lblTotal').html('$'+redondear(total));
	
	
	$('#txtIepsTotal').val(redondear(iepsTotal));
	$('#txtRetencionIepsTotal').val(redondear(retencionIespTotal));
	$('#txtRetencionIvaTotal').val(redondear(retencionIvaTotal));
	$('#txtIvaCfdi').val(redondear(ivaTotal));
	$('#txtTotalCfdi').val(redondear(total));

}

activarFacturacion	= true;

function crearCFDI()
{
	var mensaje		= "";
	productos		= new Array();
	
	desactivarBotonesFactura();
	
	if(ejecutarAccion && ejecutarAccion.readyState != 4)
	{
		notify('Ya se esta procesando el registro',500,5000,'error',30,5);
		return;
	}
	
	if(parseInt($('#txtDiferencia').val())==0)
	{
		notify('Ya se han realizado las facturas',500,5000,'error',30,5);
		activarBotonesFactura();
		
		return;
	}
	
	if(parseInt($('#txtTotalParcialFactura').val())>0)
	{
		//notify('La siguiente factura debe ser parcial',500,5000,'error',30,5);
		notify('Ya se han realizado las facturas',500,5000,'error',30,5);
		activarBotonesFactura();
		
		return;
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor<br />";
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	if($('#selectDireccionesCfdi').val()=="0")
	{
		mensaje+="Seleccione la dirección <br />";
	}
	
	/*if($('#txtMetodoPago').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}*/
	
	/*if($('#txtCondiciones').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
	}*/

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		activarBotonesFactura();
		
		return;
	}
	
	for(i=1;i<=parseInt($('#txtNumeroProductosFactura').val());i++)
	{
		productos[i]	=$('#txtDescripcionProductoFactura'+i).val();
		
		if($('#txtDescripcionProductoFactura'+i).val()=="")	
		{
			mensaje	+="La descripción del producto es incorrecta";
			break;
		}
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea realizar la factura?'))
	{
		activarBotonesFactura();
		
		return;
	}
	
	//REACTIVAR EL ACCESO A LA FACTURACIÓN
	activarFacturacion	= true;

	ejecutarAccion=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturando').html('<img src="'+ img_loader +'"/> Se esta generando la factura, por favor espere...');
		},
		type:"POST",
		url: base_url + "facturacion/crearCFDI",
			data: $('#frmFacturacion').serialize() + "&documento=FACTURA&tipoComprobante=ingreso&metodoPagoTexto="
				+ $("#txtMetodoPago option:selected").text() + "&formaPagoTexto=" + $("#txtFormaPago option:selected").text()
				+ "&usoCfdiTexto=" + $("#selectUsoCfdi option:selected").text(),
		/*data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"condiciones":		$('#txtCondiciones').val(),
			"idDivisa":			$('#selectDivisas').val(),
			"retencion":		$('#txtRetencion').val(),
			"tasa":				$('#txtTasa').val(),
			"nombre":			$('#txtNombreRetencion').val(),
			"parcial":			0,
			"idEmisor":			$('#selectEmisores').val(),
			productos:			productos,
			"observaciones":	$('#txtObservaciones').val(),
			"subTotal":			$('#txtSubTotal').val(),
			"descuento":		$('#txtDescuento').val(),
			"ivaPorcentaje":	$('#txtIvaPorcentaje').val(),
			"iva":				$('#txtIvaCfdi').val(),
			"tasaIeps":			$('#txtIeps').val(),
			"totalIeps":		$('#txtIepsTotal').val(),
			"retencionIva":		$('#txtRetencionIvaTotal').val(),
			"tasaRetencionIva":	$('#txtRetencionIva').val(),
			"retencionIeps":	$('#txtRetencionIepsTotal').val(),
			"tasaRetencionIeps":$('#txtRetencionIeps').val(),
			"total":			$('#txtTotalCfdi').val(),
			"formaPago":		$('#txtFormaPago').val(),
			"formaPagoTexto":	$("#txtFormaPago option:selected").text(),
			"cuentaPago":		$('#txtCuentaPago').val(),
			"metodoPago":		$("#txtMetodoPago").val(),
			"metodoPagoTexto":	$("#txtMetodoPago option:selected").text(),
			"usoCfdi":			$("#selectUsoCfdi").val(),
			"usoCfdiTexto":		$("#selectUsoCfdi option:selected").text(),
			"idFactura":		$("#txtIdUltimaFactura").val(),
			"pendiente":		$("#txtPendiente").val(),
			"idDireccion":		$("#selectDireccionesCfdi").val(),
			"fechaFactura":		$('#txtFechaFactura').val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#facturando').html('')
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					
					activarBotonesFactura()
				break;
				
				case "1":
					/*if($('#txtIdTienda').val()=="0")
					{
						window.location.href=base_url+"facturacion/facturasCliente/"+$('#txtIdClienteFactura').val();
					}
					else
					{*/
					
					activarFacturacion	= false;
					location.href=base_url+"pdf/crearFactura/"+data[2]+'/2';
					
					notify('La factura se ha creado correctamente '+(data[4]=='0'?' <br/>Favor de agregar un correo electrónico para enviar la factura':''),500,5000,'',30,5);

					$('#ventanaFacturacion').dialog('close');

					if(obtenerNumeros($('#txtModuloFactura').val())==1)
					{
						obtenerFacturas();
					}
					else
					{
						obtenerVentas();
					}
					//}
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al crear la factura',500,5000,'error',30,5);
			$('#facturando').html('')
			
			activarBotonesFactura();
		}
	});		
}

function desactivarBotonesFactura()
{
	$("#btnCancelarFactura,#btnVistaPrevia,#btnFacturar").button("disable");
}

function activarBotonesFactura()
{
	$("#btnCancelarFactura ,#btnVistaPrevia,#btnFacturar").button("enable");
}


$(document).ready(function()
{
	$("#ventanaFacturacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:640,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Cancelar": 
			{
				text: "Cancelar",
				id: "btnCancelarFactura",
				click: function()
				{
					$(this).dialog('close');	
				}   
		  	},
			"vistaPrevia": 
			{
				text: "Vista previa",
				id: "btnVistaPrevia",
				click: function()
				{
					vistaPrevia()
				}   
		  	},
			"Facturar": 
			{
				text: "Facturar",
				id: "btnFacturar",
				click: function()
				{
					crearCFDI()
				}   
		  	} 
		},
		
		close: function() 
		{
			$("#obtenerDatosFactura").html('');
			
			if(activarFacturacion)
			{
				actualizarAccesoFacturacion('0');
			}
			
			activarBotonesFactura()
		}
	});
	
	$("#ventanaFacturaParcial").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:960,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Vista previa': function() 
			{
				previaFacturaParcial();	  	  
			},
			'Aceptar': function() 
			{
				crearFacturaParcial();	  	  
			},
		},
		close: function() 
		{
			$("#facturaParcial").html('');
		}
	});
});

function crearFacturaParcial()
{
	var mensaje		= "";
	productos		= new Array();
	cantidad		= new Array();
	descuentos		= new Array();
	
	if($('#selectEmisoresParcial').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(parseFloat($('#txtRetencionParcial').val())>0)
	{
		if(Solo_Numerico($('#txtTasaParcial').val())=="")
		{
			mensaje+="La tasa es incorrecta <br />";
		}
		
		if($('#txtNombreRetencionParcial').val()=="")
		{
			mensaje+="El nombre de la retencion es incorrecta <br />";
		}
	}
	
	/*if($('#txtConcepto').val()=="")
	{
		mensaje+="El concepto es requerido <br />";
	}
	
	if($('#txtUnidad').val()=="")
	{
		mensaje+="La unidad es requerida <br />";
	}*/
	
	if($('#txtFormaPagoParcial').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	/*if($('#txtMetodoPagoParcial').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}*/
	
	if($('#txtCondicionesParcial').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
	}
	
	for(i=1;i<=parseInt($('#txtNumeroProductos').val());i++)
	{
		productos[i]	= $('#txtDescripcionProducto'+i).val();
		cantidad[i]		= $('#txtCantidadFacturar'+i).val();
		descuentos[i]	= $('#txtDescuentoProducto'+i).val();
		
		if($('#txtDescripcionProducto'+i).val()=="")	
		{
			mensaje	+="La descripción del producto es incorrecta";
			break;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(confirm('¿Realmente desea realizar la factura parcial?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturandoParcial').html('<img src="'+ img_loader +'"/> Se esta generando la factura, por favor tenga paciencia...');
		},
		type:"POST",
		url: base_url + "facturacion/crearCFDI",
		data: $('#frmFacturacion').serialize()+"&documento=FACTURA",
		/*data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"formaPago":		$('#txtFormaPagoParcial').val(),
			
			"metodoPago":			$("#selectMetodoPagoParcial").val(),
			"metodoPagoTexto":		$("#selectMetodoPagoParcial option:selected").text(),
			
			"condiciones":		$('#txtCondicionesParcial').val(),
			"retencion":		$('#txtRetencionParcial').val(),
			"tasa":				$('#txtTasaParcial').val(),
			"nombre":			$('#txtNombreRetencionParcial').val(),
			"parcial":			1,
			
			"subTotal":			$('#txtSubTotalParcial').val(),
			"descuento":		$('#txtDescuentoParcial').val(),
			"iva":				$('#txtIvaParcial').val(),
			"total":			$('#txtTotalParcial').val(),
			
			"concepto":			$('#txtConcepto').val(),
			"unidad":			$('#txtUnidad').val(),
			"idDivisa":			$('#selectDivisasParcial').val(),
			"idEmisor":			$('#selectEmisoresParcial').val(),
			"porcentaje":		$('#txtPorcentajeFacturar').val(),
			"observaciones":	$('#txtObservacionesParcial').val(),
			productos:			productos,
			cantidad:			cantidad,
			descuentos:			descuentos,
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#facturandoParcial').html('');
			
			data	=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
				
					location.href	= base_url+"pdf/crearFactura/"+data[2]+'/2';
					notify('La factura se ha creado correctamente',500,5000,'',30,5);
					obtenerVentas();
					$('#ventanaFacturacion').dialog('close');
					$('#ventanaFacturaParcial').dialog('close');
						
					//location.reload();
					
					/*if($('#txtIdTienda').val()=="0")
					{
						location.reload();
					}
					else
					{
						obtenerVentas();
					}*/
				break;
			}//switch
		},
		error:function(datos)
		{
			notify('Error al crear la factura parcial',500,5000,'error',30,5);
			$('#facturandoParcial').html('');
		}
	});		
}

function previaFacturaParcial()
{
	var mensaje		="";
	productos		= new Array();
	cantidad		= new Array();
	descuentos		= new Array();
	
	if($('#selectEmisoresParcial').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(parseFloat($('#txtRetencionParcial').val())>0)
	{
		if(Solo_Numerico($('#txtTasaParcial').val())=="")
		{
			mensaje+="La tasa es incorrecta <br />";
		}
		
		if($('#txtNombreRetencionParcial').val()=="")
		{
			mensaje+="El nombre de la retencion es incorrecta <br />";
		}
	}

	if($('#txtFormaPagoParcial').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	if($('#txtMetodoPagoParcial').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}
	
	if($('#txtCondicionesParcial').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
	}

	for(i=1;i<=parseInt($('#txtNumeroProductos').val());i++)
	{
		productos[i]	= $('#txtDescripcionProducto'+i).val();
		cantidad[i]		= $('#txtCantidadFacturar'+i).val();
		descuentos[i]	= $('#txtDescuentoProducto'+i).val();

		
		if($('#txtDescripcionProducto'+i).val()=="")	
		{
			mensaje	+="La descripción del producto es incorrecta";
			break;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(confirm('¿Realmente desea realizar la previa de la factura parcial?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturandoParcial').html('<img src="'+ img_loader +'"/> Se esta generando la factura, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"facturacion/vistaPrevia",
		data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"formaPago":		$('#txtFormaPagoParcial').val(),
			//"metodoPago":		$('#txtMetodoPagoParcial').val(),
			
			"metodoPago":			$("#selectMetodoPagoParcial").val(),
			"metodoPagoTexto":		$("#selectMetodoPagoParcial option:selected").text(),
			
			"condiciones":		$('#txtCondicionesParcial').val(),
			"retencion":		$('#txtRetencionParcial').val(),
			"tasa":				$('#txtTasaParcial').val(),
			"nombre":			$('#txtNombreRetencionParcial').val(),
			"parcial":			1,
			
			"subTotal":			$('#txtSubTotalParcial').val(),
			"descuento":		$('#txtDescuentoParcial').val(),
			"iva":				$('#txtIvaParcial').val(),
			"total":			$('#txtTotalParcial').val(),
			
			"concepto":			$('#txtConcepto').val(),
			"unidad":			$('#txtUnidad').val(),
			"idDivisa":			$('#selectDivisasParcial').val(),
			
			"porcentaje":		$('#txtPorcentajeFacturar').val(),
			"idEmisor":			$('#selectEmisoresParcial').val(),
			"observaciones":	$('#txtObservacionesParcial').val(),
			
			productos:			productos,
			cantidad:			cantidad,
			descuentos:			descuentos,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#facturandoParcial').html('');
			
			data	=parseInt(data);
			
			switch(data)
			{
				case 0:
				notify('Error al generar la factura',500,5000,'error',30,5);
				break;
				
				case 1:
					notify('La vista previa se ha generado correctamente',500,5000,'',30,5);
				 	window.open(base_url+'pdf/vistaPrevia');
				break;
				
				case 2:
				notify('El cliente seleccionado no tiene los datos fiscales necesarios para crear la factura',500,5000,'error',30,5);
				return;
				break;
				
				case 3:
				//alert('El cliente seleccionado no tiene los datos necesarios para crear la factura');
				notify('Error al conectarse al servidor de timbrado, verifique por favor su usuario y contraseña',500,5000,'error',30,5);
				return;
				break;
				
				case 4:
				notify('Los folios se han terminado, por favor compre mas folios',500,5000,'error',30,5);
				return;
				break;
				
				case 5:
				notify('El sistema ha detectado que no existen suficientes productos para poder crear la factura',500,5000,'error',30,5);
				return;
				break;
				
				default:
				notify(data,500,5000,'error',30,5);
				//window.location.href="http://"+base_url+"ventas/";
				break;
			}//switch
			
			//window.location.href="http://"+base_url+"ventas/";
		},
		error:function(datos)
		{
		}
	});		
}

function facturaParcial()
{
	if(parseFloat($('#txtDiferencia').val())<1)
	{
		notify('Ya se han creado todas las facturas parciales',500,5000,'',30,5);
		return;
	}
	
	$('#ventanaFacturaParcial').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturaParcial').html('<img src="'+ img_loader +'"/> Obteniendo los datos para crear la factura parcial...');
		},
		type:"POST",

		url:base_url+"facturacion/facturaParcial",
		data:
		{
			"idCotizacion":$('#txtIdCotizacion').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#facturaParcial').html(data);
			obtenerFolioParcial();
		},
		error:function(datos)
		{
			$('#facturaParcial').html('');
		}
	});//Ajax	
}

function mostrarParcial()
{
	if(!document.getElementById('chkParcial').checked)
	{
		$('#tablaParcial').fadeOut();
	}
	else
	{
		$('#tablaParcial').fadeIn();
	}
}

function calcularDatosParcial()
{
	subTotal		=parseFloat($("#txtSubTotalParcial").val());
	subTotalTotal	=parseFloat($("#txtSubTotalTotal").val());
	
	if(isNaN(subTotal) || subTotal>subTotalTotal)
	{
		//$('#lblTotalParcial').html(total.toFixed(2));
		subTotal=subTotalTotal
		$("#txtSubTotalParcial").val(subTotalTotal.toFixed(2))
		notify('Los importes son incorrectos',500,5000,'error',30,3);
	}
	
	descuento	=parseFloat($("#porcentajeDescuento").val())/100;
	iva			=parseFloat($("#porcentajeIva").val());
	
	descuento   =subTotal*descuento;
	suma		=subTotal-descuento;
	iva			=suma*iva;
	total		=suma+iva;
	
	//total		=parseFloat($("#txtTotal").val());
	//================================================================================================//
	retencion	=parseFloat($("#txtRetencionParcial").val());
	
	if(isNaN(retencion) || retencion>total)
	{
		$("#txtRetencionParcial").val(0)
		$('#lblTotalParcial').html(total.toFixed(2));
		notify('La retencion es incorrecta',500,5000,'error',30,3);
		return;
	}
	
	total=total-retencion;
	
	$('#lblTotalParcial').html(total.toFixed(2));
	$('#lblDescuento').html(descuento.toFixed(2));
	$('#lblIva').html(iva.toFixed(2));
	
	$('#txtTotalParcial').val(total.toFixed(2));
	$('#txtDescuentoParcial').val(descuento.toFixed(2));
	$('#txtIvaParcial').val(iva.toFixed(2));
}

function calcularRetencionParcial()
{
	total		=parseFloat($("#txtTotal").val());
	
	//================================================================================================//
	retencion	=parseFloat($("#txtRetencion").val());
	
	if(isNaN(retencion) || retencion>total)
	{
		$("#txtRetencion").val(0)
		$('#lblTotal').html(total.toFixed(2));
		notify('La retencion es incorrecta',500,5000,'error',30,3);
		return;
	}
	
	total=total-retencion;
	
	$('#lblTotal').html(total.toFixed(2));
}

function calcularRetencion()
{
	total			=parseFloat($("#txtTotal").val());
	subTotal		=parseFloat($("#txtSuma").val());
	
	//================================================================================================//
	retencion		=parseFloat($("#txtTasa").val());
	
	if(isNaN(retencion) || retencion>99)
	{
		$("#txtTasa").val(0)
		$('#lblTotal').html(total.toFixed(2));
		notify('La retencion es incorrecta',500,5000,'error',30,3);
		return;
	}
	
	retencion	=(retencion/100)*subTotal;

	total-=retencion;
	
	$('#lblTotal').html(total.toFixed(2));
	$('#txtRetencion').val(retencion.toFixed(2));
}

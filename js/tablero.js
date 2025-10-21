function detallesSeguimiento(idSeguimiento)
{
	detalleCita=true;
	
	$('#ventanaInformacionSeguimiento').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarSeguimiento').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimiento',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarSeguimiento').html(data)
		},
		error:function(datos)
		{
			$('#cargarSeguimiento').html('Error al obtener los detalles del seguimiento')
		}
	});		
}

pago		= 0;
ventaRecibo	= 0;

function reciboCorreo(idPago,idVenta)
{
	pago			= idPago
	ventaRecibo		= idVenta;
}

function obtenerVenta(idCotizacion)
{
	detalleCita=true;
	
	$('#ventanitaVentas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarVentita').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la venta...');},
		type:"POST",
		url:base_url+'tablero/obtenerVenta',
		data:
		{
			"idCotizacion":idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarVentita").html(data);
		},
		error:function(datos)
		{
			$("#cargarVentita").html('Error al obtener la venta');	
		}
	});//Ajax			
}

/*function buscarCuentas()
{
	div = document.getElementById('listaBancos');
	idBanco=div.value;
	
	$("#cargarCuenta").load(base_url+"ficha/obtenerCuentas/"+idBanco);
} */

function obtenerVentaEntrega(idct,fecha)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarVentita').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la venta...');},
		type:"POST",
		url:base_url+'ficha/obtenerVentaEntrega',
		data:
		{
			"idCotizacion":idct,
			"fecha":fecha
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarEntrega").html(data);
		},
		error:function(datos)
		{
			$("#cargarEntrega").html('Error al obtener la venta');	
		}
	});//Ajax			
}

//===============================================================================================================================//
//======================================================PARA LAS COMPRAS=========================================================//
//===============================================================================================================================//

$(document).ready(function()
{
	for(i=1;i<2000;i++)
	{
		$("#entrega"+i).click(function(e)
		{
			$('#ventanaEntregas').dialog('open');
		});
	}
	
	$("#ventanaEntregas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$("#cargarEntrega").html(''); 
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
			$("#cargarEntrega").html('');
		}
	});
});




//=====================================================================================================================//
//============================================OBTENER COBROS CLIENTES==================================================//
//=====================================================================================================================//

function obtenerCobrosClientesTablero(idCotizacion)
{
	detalleCita=true;
	$('#ventanaCobrosClientesTablero').dialog('open');
	
	ventita=idCotizacion;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarPagosClientesTablero').html('<img src="'+ img_loader +'"/> Obteniendo el detalle de los cobros...');
		},
		type:"POST",
		url:base_url+'tablero/obtenerCobrosClientes',
		data:
		{
			"idCotizacion":idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarPagosClientesTablero").html(data);
		},
		error:function(datos)
		{
			$("#cargarPagosClientesTablero").html('');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaCobrosClientesTablero").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:540,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			detalleCita=false;
			///$("#ErrorRemision").fadeOut();
		}
	});
});

//=====================================================================================================================//
//============================================OBTENER PAGOS PROVEEDOR==================================================//
//=====================================================================================================================//
function obtenerPagosComprasProveedor(idCompra)
{
	detalleCita		= true;
	comprita		= idCompra;
	
	$('#ventanaPagosProveedor').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarPagosProveedor').html('<img src="'+ img_loader +'"/> Se estan cargando los pagos de la compra...');
		},
		type:"POST",
		url:base_url+'tablero/obtenerPagosCompras',
		data:
		{
			"idCompra":idCompra
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarPagosProveedor").html(data);
		},
		error:function(datos)
		{
			$("#cargarPagosProveedor").html('Error al obtener los pagos de la compra');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaPagosProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			detalleCita		= false;
			///$("#ErrorRemision").fadeOut();
		}
	});
});

//=====================================================================================================================//
//============================================OBTENER  TODAS FACTURAS==================================================//
//=====================================================================================================================//
function obtenerDetallesFactura(idFactura)
{
	detalleCita		= true;
	$('#ventanaFacturasTablero').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarDetallesFactura').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la factura...');
		},
		type:"POST",
		url:base_url+'tablero/obtenerDetallesFactura',
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarDetallesFactura").html(data);
		},
		error:function(datos)
		{
			$("#cargarDetallesFactura").html('Error al obtener la factura');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaFacturasTablero").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				window.open(base_url+'pdf/'+$('#txtAcceso').val()+'/'+$('#txtIdFacturaAcceso').val());
			},
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			detalleCita		= false;
			///$("#ErrorRemision").fadeOut();
		}
	});
});

$(document).ready(function()
{
	$("#ventanaInformacionSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Borrar': function() 
			{
				borrarSeguimientoCrm();		 
			},
			'Editar': function() 
			{
				$(this).dialog('close');				 
			},
			
			/*'ERP': function() 
			{
				formularioErp();
				$('#ventanaFormularioErp').dialog('open');			 
			},
			'Páginas web': function() 
			{
				formularioPw();
				$('#ventanaFormularioPw').dialog('open');			 
			}*/
		},
		close: function()
		{
			$("#cargarSeguimiento").html('');
			detalleCita	= false;
		}
	});

	$("#ventanaFormularioErp").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:360,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				agregarErp()				 
			}
		},
		close: function()
		{
			$("#formularioErp").html('');
		}
	});
});

function formularioErp()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioErp').html('<img src="'+ img_loader +'"/> \
			Cargando el formulario de ERP...');
		},
		type:"POST",
		url:base_url+'clientes/formularioErp',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioErp').html(data)
		},
		error:function(datos)
		{
			$('#formularioErp').html('');
			notify('Error al obtener el formulario de ERP',500,5000,'error',0,0);
		}
	});		
}


function agregarErp()
{
	var mensaje="";

	if($('#txtFechaErp').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
		
	}
	
	if($('#txtClienteErp').val()=="")
	{
		mensaje+='El cliente es requerido <br />';
	}
	
	if($('#txtComentariosErp').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea agregar el seguimiento ERP?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoErp').html('<img src="'+ img_loader +'"/> \
			Se esta registrando el seguimiento ERP...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimientoErp",
		data:
		{
			"comentarios":		$("#txtComentariosErp").val(),
			"observaciones":	$("#txtObservacionesErp").val(),
			"fecha":			$('#txtFechaErp').val(),
			"cliente":			$('#txtClienteErp').val(),
			"idCliente":		$('#txtIdCliente').val(),
			"idStatus":			$('#selectStatusErp').val(),
			"idResponsable":	$('#selectResponsableErp').val(),
			"tipo":				1,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#agregandoErp').html('');
				notify('¡Error al registrar el seguimiento!',500,5000,'error',0,0);
				break;
				
				case "1":
				window.location.href=base_url+'principal/tableroControl/'+$('#txtFechaActual').val();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#agregandoErp').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});					  	  
}


function formularioPw()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPw').html('<img src="'+ img_loader +'"/> \
			Cargando el formulario de Páginas web...');
		},
		type:"POST",
		url:base_url+'clientes/formularioPw',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPw').html(data)
		},
		error:function(datos)
		{
			$('#formularioPw').html('');
			notify('Error al obtener el formulario de Páginas web',500,5000,'error',0,0);
		}
	});		
}


function agregarPw()
{
	var mensaje="";

	if($('#txtFechaErp').val()=="")
	{
		mensaje+='Debe seleccionar una fecha<br /> ';
		
	}
	
	if($('#txtClienteErp').val()=="")
	{
		mensaje+='El cliente es requerido <br />';
	}
	
	if($('#txtComentariosErp').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea agregar el seguimiento de Páginas web?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoPw').html('<img src="'+ img_loader +'"/> \
			Se esta registrando el seguimiento de Páginas web...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimientoErp",
		data:
		{
			"comentarios":	$("#txtComentariosErp").val(),
			"observaciones":$("#txtObservacionesErp").val(),
			"fecha":		$('#txtFechaErp').val(),
			"cliente":		$('#txtClienteErp').val(),
			"idCliente":	$('#txtIdCliente').val(),
			"idStatus":		$('#selectStatusErp').val(),
			"idResponsable":$('#selectResponsablePw').val(),
			"tipo":			2,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#agregandoPw').html('');
				notify('¡Error al registrar el seguimiento!',500,5000,'error',0,0);
				break;
				
				case "1":
				window.location.href=base_url+'principal/tableroControl/'+$('#txtFechaActual').val();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#agregandoPw').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});					  	  
}


$(document).ready(function()
{
	$("#ventanaFormularioPw").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:360,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				agregarPw()				 
			}
		},
		close: function()
		{
			$("#formularioPw").html('');
		}
	});
	
	$("#ventanaSeguimientoCrm").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:420,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				guardarSeguimiento()				 
			}
		},
		close: function()
		{
			$("#formularioPw").html('');
		}
	});
});

function guardarSeguimiento()
{
	var mensaje="";

	if($('#txtComentarios').val()=="")
	{
		mensaje+='Los comentarios son requeridos <br />';
	}
	
	if($('#txtFechaSeguimiento').val()=="")
	{
		mensaje+='Debe seleccionar una fecha <br />';
	}
	
	if(Solo_Numerico($('#txtMonto').val())=="")
	{
		mensaje+='El monto es incorrecto<br />';
	}
	
	/*if($('#txtFechaCierre').val()=="")
	{
		mensaje+='La fecha de cierre es incorrecta';
	}*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea agregar el seguimiento?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoSeguimiento').html('<img src="'+ img_loader +'"/> \Se esta registrando un seguimiento...');
		},
		type:"POST",
		url:base_url+"clientes/registrarSeguimiento",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"fecha":			$('#txtFechaSeguimiento').val()+' '+$('#txtHoraSeguimiento').val(),
			"idCliente":		$('#txtIdClienteSeguimiento').val(),
			"idStatus":			$('#selectStatus').val(),
			"idServicio":		$('#selectServicio').val(),
			"idResponsable":	$('#selectResponsable').val(),
			"monto":			$('#txtMonto').val(),
			"fechaCierre":		$('#txtFechaCierre').val(),
			"lugar":			$('#txtLugar').val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#cargandoSeguimiento').html('')
				$('#errorSeguimiento').html('Error  al agregar el seguimiento')
				break;
				
				case "1":
				window.location.href=base_url+'principal/tableroControl/'+$('#txtFechaActual').val();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoSeguimiento').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});		
}



$(document).ready(function()
{
	$("#ventanitaVentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				window.open(base_url+'pdf/nuevaVenta/'+$('#txtIdCotizacion').val()+'/1');
			},
			'Vista previa': function() 
			{
				vistaPrevia();	  	  
			},
			'Facturar': function() 
			{
				crearCFDI();	  	  
			},
			/*'Cerrar': function() 
			{
				$("#cargarVentita").html('');
				$(this).dialog('close');	 
			},*/
		},
		close: function() 
		{
			$("#cargarVentita").html('');
			detalleCita		= false;
		}
	});
});

function vistaPrevia()
{
	if($('#txtIdFactura').val()!="0")
	{
		notify('Ya se ha facturado la venta',500,5000,'error',30,5);
		return;	
	}
	
	if(parseInt($('#txtParcial').val())>1)
	{
		notify('La vista previa debe ser parcial',500,5000,'error',30,5);
		return;
	}
	
	if(parseInt($('#txtDatosFiscales').val())==0)
	{
		notify('El cliente no tiene los datos fiscales necesarios para crear la factura',500,5000,'error',30,5);
		return;
	}
	
	var mensaje		="";
	productos		=new Array();

	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	if($('#txtMetodoPago').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}
	
	if($('#txtCondiciones').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
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
		notify(mensaje,500,5000,'error',0,5);
		return;
	}
	
	if(confirm('¿Realmente desea realizar la vista previa?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturando').html('<img src="'+ img_loader +'"/> Se esta generando la vista previa, por favor espere...');
		},
		type:"POST",
		url:base_url+"facturacion/vistaPrevia",
		data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"formaPago":		$('#txtFormaPago').val(),
			"metodoPago":		$('#txtMetodoPago').val(),
			"condiciones":		$('#txtCondiciones').val(),
			
			"retencion":		$('#txtRetencion').val(),
			"tasa":				$('#txtTasa').val(),
			"nombre":			$('#txtNombreRetencion').val(),
			"parcial":			0,
			"idDivisa":			$('#selectDivisas').val(),
			
			"idEmisor":			$('#selectEmisores').val(),
			productos:			productos,
			"observaciones":	'',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(parseInt(data))
			{
				case 0:
				notify('Error al generar la vista previa',500,5000,'error',30,5);
				$('#facturando').html('')
				break;
				
				case 1:
				$('#facturando').html('')
				notify('La vista previa se ha generado correctamente',500,5000,'',30,5);
				 window.open(base_url+'pdf/vistaPrevia');
				//window.location.href=base_url+"clientes/ventas/";
				break;
				
				case 2:
				notify('El cliente seleccionado no tiene los datos fiscales necesarios para crear la vista previa',500,5000,'error',30,5);
				$('#facturando').html('')
				break;

				case 4:
				notify('Los folios se han terminado, por favor adquiera una nueva dotación',500,5000,'error',30,5);
				$('#facturando').html('')
				break;
				
				case 5:
				notify('El sistema ha detectado que no existen suficientes productos para poder crear la vista previa',500,5000,'error',30,5);
				$('#facturando').html('')
				break;
				
				default:
				$('#facturando').html('')
				notify(data,500,5000,'error',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al generar la vista previa',500,5000,'error',30,5);
			$('#facturando').html('')
		}
	});		
}

function crearCFDI()
{
	var mensaje		="";
	productos		=new Array();
	
	
	if($('#txtIdFactura').val()!="0")
	{
		notify('Ya se ha facturado la venta',500,5000,'error',30,5);
		return;	
	}
	
	if(parseInt($('#txtParcial').val())>0)
	{
		notify('La siguiente factura debe ser parcial',500,5000,'error',30,5);
		return;
	}
	
	if(parseInt($('#txtDatosFiscales').val())==0)
	{
		notify('El cliente no tiene los datos fiscales necesarios para crear la factura',500,5000,'error',30,5);
		return;
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+="Especifique la forma de pago <br />";
	}
	
	if($('#txtMetodoPago').val()=="")
	{
		mensaje+="Especifique el metodo de pago <br />";
	}
	
	if($('#txtCondiciones').val()=="")
	{
		mensaje+="Especifique las condiciones de pago <br />";
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
		notify(mensaje,500,5000,'error',0,5);
		return;
	}
	
	if(confirm('¿Realmente desea realizar la factura?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#facturando').html('<img src="'+ img_loader +'"/> Se esta generando la factura, por favor espere...');
		},
		type:"POST",
		url:base_url+"facturacion/crearCFDI",
		data:
		{
			"idCotizacion":		$('#txtIdCotizacion').val(),
			"documento":		'FACTURA',
			"tipoComprobante":	'ingreso',
			"formaPago":		$('#txtFormaPago').val(),
			"metodoPago":		$('#txtMetodoPago').val(),
			"condiciones":		$('#txtCondiciones').val(),
			"idDivisa":			$('#selectDivisas').val(),
			"retencion":		$('#txtRetencion').val(),
			"tasa":				$('#txtTasa').val(),
			"nombre":			$('#txtNombreRetencion').val(),
			"parcial":			0,
			"idEmisor":			$('#selectEmisores').val(),
			productos:			productos,
			"observaciones":	'',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#facturando').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					window.location.href=base_url+"facturacion/facturasCliente/"+$('#txtIdClienteFactura').val();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al crear la factura',500,5000,'error',30,5);
			$('#facturando').html('')
		}
	});		
}
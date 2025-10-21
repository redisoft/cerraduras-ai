$(document).ready(function()
{
	$("#ventanaPagos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:950,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				realizarPagoProveedor();
				
				//$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			$("#cargarPagos").html('');
		}
	});
});

function obtenerPagosComprasProveedor(idCompra)
{
	$("#ventanaPagos").dialog('open');
	
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
			$('#montoPagar').focus()
			//catalogos();
		},
		error:function(datos)
		{
			$("#cargarPagos").html('');	
		}
	});//Ajax	
}


function borrarPago(idPago,idCompra)
{
	if(confirm('¿Realmente desea borrar este pago?')==true)
	{
		$.ajax(
		{
			async   : true,
			beforeSend:function(objeto)
			{
				$('#cargandoPagos').html('<img src="'+ img_loader +'"/> Se esta borrando el pago, por favor espere...');
			},
			type    : "POST",
			url     : base_url+"compras/borrarPago",
			data	: 
			{
				"idPago":idPago,
			},
			datatype: "html",
			success	: function(data, textStatus)
			{
				switch(data)
				{
					case "0":
					$('#cargandoPagos').html('');
					notify('Error al borrar el pago',500,5000,'error',34,4);
					break;
							
					case "1":
					$('#cargandoPagos').html('');
					notify('El pago se ha borrado correctamente',500,5000,'',34,4);
					window.setTimeout("obtenerPagosComprasProveedor("+idCompra+")",300)
					break;
				}
			},
			error: function(datos)
			{
				$('#cargandoPagos').html('');
				notify('Error al borrar el cobro',500,5000,'error',34,4);
			}
		});
	}
}

function realizarPagoProveedor()
{
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaEgreso').val()=="")
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
	
	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#numeroCheque').val('');
		$('#numeroTransferencia').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#selectFormas').val()=="2")
	{
		$('#numeroTransferencia').val('');
		idNombre	=$('#selectNombres').val();
		
		if($('#numeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			//mensaje+="Seleccione el nombre <br />";
		}
	}

	if($('#selectFormas').val()=="3")
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
	
	if(!confirm('¿Realmente desea registrar el pago?')) return;
	
	var formData = new FormData($('#frmPagoCompra')[0]);
	
	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#cargandoPagos').html('<img src="'+ img_loader +'"/> Se esta realizando el pago, por favor espere...');
		},
		async   : false,
		type    : "POST",
		url     : base_url+"compras/realizarPago",
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		/*data	: 
		{
			"idCompras":			$('#idCompras').val(),
			"montoPagar":			$('#montoPagar').val(),
			"cuentasBanco":			$('#cuentasBanco').val(),
			"numeroCheque":			$('#numeroCheque').val(),
			"numeroTransferencia":	$('#numeroTransferencia').val(),
			"idForma":				$('#selectFormas').val(),
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
			idProveedor:			$('#txtIdProveedorCompra').val(),
			esRemision:				$('#selectFactura').val(),
		},*/
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#cargandoPagos').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
				
				notify(data[1],500,5000,'error',34,4);
				break;
						
				case "1":
					window.setTimeout("obtenerPagosComprasProveedor("+$('#idCompras').val()+")",300);
					notify(data[1],500,5000,'',34,4);
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

function opcionesFormasPago(opcionFormas)
{
	if(opcionFormas==1)
	{
		Formas		= new String($('#selectFormas').val());
		formas   	= Formas.split('|');
		forma	    = obtenerNumeros(formas[0]);
		
		if(forma!=2 && forma!=3)
		{
			$('#mostrarCheques').fadeOut();
			$('#filaNombre').fadeOut();
			$('#mostrarTransferencia').fadeOut();
			$('#contenedorNombres').fadeOut();
			
			$('#numeroCheque').val('');
			$('#numeroTransferencia').val('');
			$('#txtNombreReceptor').val('');
			$('#filaPeriodicidad').fadeOut();
			
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val('');
		}
		
		if(forma==4)
		{
			$('#filaPeriodicidad').fadeIn();
			$('#filaCheques').fadeOut();
			$('#filaTransferencia').fadeOut();
			$('#filaNombre').fadeOut();
			$('#contenedorNombres').fadeOut();
			
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val('');
		}
		
		if(forma==2)
		{
			//$('#mostrarCheques').fadeIn();
			$('#mostrarTransferencia').fadeOut();
			//$('#filaNombre').fadeIn();
			//$('#contenedorNombres').fadeIn();
			$('#numeroTransferencia').val('');
			$('#txtNombreReceptor').val($('#txtProveedorCompra').val());
			$('#filaPeriodicidad').fadeOut();
		}
		
		if(forma==3)
		{
			$('#mostrarCheques').fadeOut();
			//$('#mostrarTransferencia').fadeIn();
			
			
			
			//$('#filaNombre').fadeIn();
			//$('#contenedorNombres').fadeOut();
	
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val($('#txtProveedorCompra').val());
			$('#filaPeriodicidad').fadeOut();
		}
	}
	else
	{
		if($('#selectFormas').val()!="2" && $('#selectFormas').val()!="3")
		{
			$('#mostrarCheques').fadeOut();
			$('#filaNombre').fadeOut();
			$('#mostrarTransferencia').fadeOut();
			$('#contenedorNombres').fadeOut();
			
			$('#numeroCheque').val('');
			$('#numeroTransferencia').val('');
			$('#txtNombreReceptor').val('');
			$('#filaPeriodicidad').fadeOut();
			
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val('');
		}
		
		if($('#selectFormas').val()=="4")
		{
			$('#filaPeriodicidad').fadeIn();
			$('#filaCheques').fadeOut();
			$('#filaTransferencia').fadeOut();
			$('#filaNombre').fadeOut();
			$('#contenedorNombres').fadeOut();
			
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val('');
		}
		
		if($('#selectFormas').val()=="2")
		{
			$('#mostrarCheques').fadeIn();
			$('#mostrarTransferencia').fadeOut();
			//$('#filaNombre').fadeIn();
			//$('#contenedorNombres').fadeIn();
			$('#numeroTransferencia').val('');
			$('#txtNombreReceptor').val($('#txtProveedorCompra').val());
			$('#filaPeriodicidad').fadeOut();
		}
		
		if($('#selectFormas').val()=="3")
		{
			$('#mostrarCheques').fadeOut();
			$('#mostrarTransferencia').fadeIn();
			//$('#filaNombre').fadeIn();
			//$('#contenedorNombres').fadeOut();
	
			$('#txtNumeroCheque').val('');
			$('#txtNombreReceptor').val($('#txtProveedorCompra').val());
			$('#filaPeriodicidad').fadeOut();
		}
	}
}
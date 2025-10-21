function busquedaTiendas()
{
	window.location.href=base_url+"tiendas/prebusquedaTienda/"+$('#selectTiendas').val();
}

function busquedaFechasTienda()
{
	window.location.href=base_url+"tiendas/prebusquedaTiendaFecha/"+$('#FechaDia').val();
}

/*function buscarCuentas()
{
	div = document.getElementById('listaBancos');
	idBanco=div.value;
	
	$("#cargarCuenta").load(base_url+"ficha/obtenerCuentas/"+idBanco);
}*/

/*function mostrarDatos()
{
	if($('#TipoPago').val()=="1")
	{
		$('#mostrarCheques').fadeOut();
		$('#mostrarTransferencia').fadeOut();
	}
	
	if($('#TipoPago').val()=="2")
	{
		$('#mostrarCheques').fadeIn();
		$('#mostrarTransferencia').fadeOut();
	}
	
	if($('#TipoPago').val()=="3")
	{
		$('#mostrarCheques').fadeOut();
		$('#mostrarTransferencia').fadeIn();
	}
}*/

function obtenerProductosTienda()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarProductosVenta').html('<img src="'+ img_loader +'"/> Espere por favor ...');},
		type:"POST",
		url:base_url+'tiendas/obtenerProductosTienda',
		data:
		{
			"nombreProducto":$("#txtBusquedaProducto").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarProductosVenta").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de productos',500,5000,'error',30,1);
		}
	});				  	  
}

$(document).ready(function()
{
	$("#puntoVenta").click(function(e)
	{
		$('#ventanaPuntoVenta').dialog('open');
	});

	$("#ventanaPuntoVenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				
				
				var mensaje="";
				
				productos=new Array();
				cantidad=new Array();
				totales=new Array();
				precioProducto=new Array();

				if($("#kitTotal").val()=="0")
				{
					mensaje+="No se han agregado productos para la venta <br />";
				}
				
				if(isNaN($("#pagoVenta").val()))
				{
					mensaje+="El pago es incorrecto <br />";
				}
				
				if($("#pagoVenta").val()=="0")
				{
					mensaje+="El pago debe ser mayor a cero <br />";
				}
				
				if(parseFloat($("#pagoVenta").val())<parseFloat($("#totalVenta").val()))
				{
					mensaje+="El pago es incorrecto <br />";
				}
				
				if($('#TipoPago').val()=="2")
				{
					if($('#numeroCheque').val()=="")
					{
						mensaje+="El numero de cheque es invalido <br />";
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
				}
				
				if($('#selectClientes').val()=="0")
				{
					mensaje+="Debe seleccionar un cliente <br />";
				}
				
				v=0;
				
				for(i=0;i<fila;i++)
				{
					precio=parseFloat($('#totalProducto'+i).val())
					
					if(!isNaN(precio))
					{
						totalKit+=precio
						
						productos[v]=$('#idProducto'+i).val();
						cantidad[v]=$('#cantidadProducto'+i).val();
						totales[v]=$('#totalProducto'+i).val();
						precioProducto[v]=$('#precioProducto'+i).val();

						v++;
					}
				}
				
				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',30,0);
					return;
				}
				
				if(confirm('¿Realmente deseea realizar la venta?')==false) return;
				
				$("#realizandoVenta").fadeIn();
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#realizandoVenta').html('<img src="'+ img_loader +'"/> Espere por favor ...');},
					type:"POST",
					url:base_url+'tiendas/realizarVenta',
					data:
					{
						"nombreKit":$("#nombreKit").val(),
						"productos":productos,
						"cantidad":cantidad,
						"kitTotal":$("#kitTotal").val(), //Es el subtotal en la venta
						"preciosTotales":totales,
						"precioProducto":precioProducto,
						"iva":$("#ivaVenta").val(),
						"total":$("#totalVenta").val(),
						"idCliente":$("#selectClientes").val(),
						"pago":$("#pagoVenta").val(),
						"cambio":$("#cambioVenta").val(),
						
						"cuentasBanco":$('#cuentasBanco').val(), //Si es venta se agregan esta información
						"numeroCheque":$('#numeroCheque').val(),
						"numeroTransferencia":$('#numeroTransferencia').val(),
						"formaPago":$('#TipoPago').val(),
						"banco":$('#listaBancos').val() 
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$("#realizandoVenta").fadeOut();
							notify('Error al realizar la venta',500,5000,'error',30,3);
							break;
							case "1":
							window.location.href=base_url+"tiendas";
							break;
							
							case "0":
							$("#realizandoVenta").fadeOut();
							notify('No existen suficientes productos para realizar la venta',500,5000,'error',30,3);
							break;
						}
					},
					error:function(datos)
					{
						$("#realizandoVenta").fadeOut();
						notify('Error al realizar la venta',500,5000,'error',30,3);
					}
				});				  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#realizandoVenta").fadeOut();
		}
	});
});

//============================================================================================================================//
	
	fila=1;
	
	function comprobarDuplicidad(n)
	{
		for(i=0;i<=fila;i++)
		{
			if(!isNaN($('#idProducto'+i).val()))
			{
				if($('#agregar'+n).val()==$('#idProducto'+i).val())
				{
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
		calcularCambio();
	}
	
	function agregarProductoVenta(n)//n es el numero de fila
	{
		mensaje="";
		
		if(comprobarDuplicidad(n)==0)
		{
			notify('No puede poner 2 productos iguales',500,5000,'error',30,3);
			return;
		}
		
		if(isNaN($('#cantidad'+n).val()) || $('#cantidad'+n).val()=="0" || $('#cantidad'+n).val()=="" ||
		 	parseInt($('#cantidad'+n).val()) > parseInt($('#cantidadTotal'+n).val()))
		{
			notify('La cantidad es incorrecta',500,5000,'error',30,3);
			return;
		}
				
		total=parseFloat($('#precio'+n).val())*parseFloat($('#cantidad'+n).val())
		
		producto='<tr id="filaProducto'+fila+'">';
		producto+='<td align="center">';
		producto+='<img style="cursor:pointer" onclick="quitarProductoKit('+fila+')" src="http://'+base_url+
		'img/borrar.png" width="25" tittle="Quitar producto"  />';
		producto+='</td>';
		producto+='<td align="center">'+$('#descripcion'+n).val()+'</td>';
		producto+='<td align="center">$ '+$('#precio'+n).val()+'</td>';
		producto+='<td align="center">'+$('#cantidad'+n).val()+'</td>';
		producto+='<td align="right">$ '+total+'<input type="hidden" id="totalProducto'+fila+'" value="'+total+'" />';
		producto+='<input type="hidden" id="idProducto'+fila+'" value="'+$('#agregar'+n).val()+'" />';
		producto+='<input type="hidden" id="cantidadProducto'+fila+'" value="'+$('#cantidad'+n).val()+'" />';
		producto+='<input type="hidden" id="precioProducto'+fila+'" value="'+$('#precio'+n).val()+'" />';
		producto+='</td>';
		producto+='</tr>';
		
		$('#tablaVentas').append(producto); //Nombre de la tabla que contiene el kit
		
		//document.getElementById('agregar'+n).checked=false;
		
		fila++;
		
		calcularTotales();
		calcularCambio();
		
		$('#cantidad'+n).val('0')
		$('#pagoVenta').focus();
		//$('#buscarNombre').val('');
		//$('#buscarNombre').focus();
	}
	
	function calcularTotales() //Calular el total del kit de productos
	{
		totalKit=0;
		
		for(i=0;i<fila;i++)
		{
			precio=parseFloat($('#totalProducto'+i).val());
			
			if(!isNaN(precio))
			{
				totalKit+=precio;
			}
		}
		
		$('#kitTotal').val(totalKit);
		
		iva=parseFloat($('#ivaVenta').val())/100;
		totalVenta=totalKit+(iva*totalKit)
		$('#totalVenta').val(totalVenta)
	}
	
	function calcularCambio()
	{
		total=parseFloat($('#totalVenta').val());
		pago=parseFloat($('#pagoVenta').val());
		
		cambio=pago-total;
		
		$('#cambioVenta').val(cambio)
		
	}
	
	//=============================================================================================================//
	cotizacionFactura=0;
	
	function datosFactura(cliente,idCotizacion,subTotal,iva,descuento,total)
	{
		cotizacionFactura=idCotizacion;
		
		$("#cliente").html(cliente);
		$("#descuento").html(descuento);
		$("#iva").html(iva);
		$("#total").html(total);
		$("#subtotal").html(subTotal);
	}
	
	$(document).ready(function()
	{
		for(i=1;i<30;i++)
		{
			$("#facturar"+i).click(function(e)
			{
				$('#ventanaFacturacion').dialog('open');
			});
		}
		
		$("#ventanaFacturacion").dialog(
		{
			autoOpen:false,
			height:250,
			width:600,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Guardar': function() 
				{
					
					
					var Mensage="";
					
					
					var URL=base_url+"factura_ventas/facturaGlobal";

					
					if(confirm('¿Realmente desea realizar la factura?')==false)
					{
						return;
					}
					
					$('#facturando').fadeIn()
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#facturando').html('<img src="'+ img_loader +'"/> Se esta realizando la factura, tenga paciencia por favor...');
						},
						type:"POST",
						url:URL,
						data:
						{
							"idCotizacion":cotizacionFactura,
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								notify('Error al realizar la factura',500,5000,'error',5,5);
								$('#facturando').fadeOut()
								break;
								
								case "1":
								window.location.href=base_url+"produccion/tiendas/";
								break;
								
								case "2":
								notify('El cliente no tiene los datos necesarios para realizar la factura',500,5000,'error',5,5);
								$('#facturando').fadeOut()
								break;
							}//switch
						},
						error:function(datos)
						{
							notify('Error al realizar la factura',500,5000,'error',5,5);	
						}
					});					  	  
				},
				Cancelar: function() 
				{
					$('#facturando').fadeOut()
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$('#facturando').fadeOut()
			}
		});
	});
	
	//====================================================================================================//
	//==========================================AGREGAR CLIENTE P=========================================//
	//====================================================================================================//
	
	function formularioCliente()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#formularioCliente').html('<img src="'+ img_loader +'"/> Espere por favor ...');},
			type:"POST",
			url:base_url+'tiendas/formularioCliente',
			data:
			{
				//"nombreProducto":$("#txtBusquedaProducto").val(),
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#formularioCliente").html(data);
			},
			error:function(datos)
			{
				$("#formularioCliente").html('Error al obtener el formulario de los clientes');
			}
		});				  	  
	}
	
	function copiarDireccion()
	{
		if(document.getElementById('chkConfirmar').checked==true)
		{
			$('#direccionEnvio').val($('#direccion').val());
			$('#ciudadEnvio').val($('#localidad').val());
			$('#codigoPostalEnvio').val($('#codigoPostal').val());
			$('#estadoEnvio').val($('#estado').val());
		}
		else
		{
			$('#direccionEnvio').val('');
			$('#ciudadEnvio').val('');
			$('#codigoPostalEnvio').val('');
			$('#estadoEnvio').val('');
		}
	}

	$(document).ready(function()
	{
		$("#agregarCliente").click(function(e)
		{
   			$('#ventanaClientes').dialog('open');
		});
		
		$("#ventanaClientes").dialog(
		{
			autoOpen:false,
			height:630,
			width:750,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Guardar': function() 
				{
					var mensaje="";
					var URL=base_url+"tiendas/agregarCliente";

					if($('#empresa').val()=="")
					{
						mensaje+='El nombre de la empresa es incorrecto <br />';
					}
					
					if($('#direccion').val()=="")
					{
						mensaje+='La direccion es incorrecta <br />';
					}
					
					if($('#telefono').val()=="")
					{
						mensaje+='El telefono es incorrecto <br />';
					}
					
					if($('#zona').val()=="0")
					{
						mensaje+='Por favor seleccione '+$('#txtIdentificador').val()+' <br />';
					}
					
					if(mensaje.length>0)
					{
						notify(mensaje,500,5000,'error',5,5);
						return;
					}
					
					if(confirm('¿Realmente desea registrar el cliente?')==false)
					{
						return;
					}
					
					$('#cargandoClientes').fadeIn()
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#cargandoClientes').html('<img src="'+ img_loader +'"/> Se esta registrando el cliente...');
						},
						type:"POST",
						url:URL,
						data:
						{
							"empresa":$("#empresa").val(),
							"estado":$('#estado').val(),
							"localidad":$('#localidad').val(),
							"rfc":$('#rfc').val(),
							"direccion":$('#direccion').val(),
							"numero":$('#numero').val(),
							"colonia":$('#colonia').val(),
							"codigoPostal":$('#codigoPostal').val(),
							"telefono":$('#telefono').val(),
							"fax":$('#fax').val(),
							"email":$('#email').val(),
							"pagina":$('#pagina').val(),
							"direccionEnvio":$('#direccionEnvio').val(),
							"codigoPostalEnvio":$('#codigoPostalEnvio').val(),
							"estadoEnvio":$('#estadoEnvio').val(),
							"ciudadEnvio":$('#ciudadEnvio').val(),
							"proveedoraso":"no",
							"idZona":$('#zona').val(),
							"esCliente":0,
							"precio":$('#txtPrecioCliente').val(),
							
							"nombreVendedor":$('#nombreVendedor').val(),
							"limiteCredito":$('#limiteCredito').val(),
							"plazos":$('#plazos').val(),
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								$('#cargandoClientes').fadeOut()
								notify('Error al agregar al cliente',500,5000,'error',0,0);
								break;
								
								case "1":
								$('#cargandoClientes').fadeOut()
								notify('El cliente se ha registrado correctamente',500,5000,'',0,0);
								$('#ventanaClientes').dialog('close');
								//window.location.href=base_url+"clientes/";
								break;
								
							}//switch
						},
						error:function(datos)
						{
							$('#cargandoClientes').fadeOut()
							notify('Error al agregar al cliente',500,5000,'error',0,0);
						}
					});					  	  
				},
				Cancel: function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#cargandoClientes").html('');
			}
		});
	});
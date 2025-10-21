//REGISTRAR CFDI DE FACTURA
$(document).ready(function()
{
	$("#ventanaCfdiFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:380,
		width:780,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			"cfdiVenta" : 
			{
				text: "Aceptar",
				id: "btnRegistrarCfiVenta",
				click: function()
				{
					registrarFacturaVenta();
				}   
		  	},
		},
		
		close: function() 
		{
			$("#formularioCfdiVenta").html('');
			activarBotonesVenta();
		}
	});
});

function formularioCfdiVenta()
{
	Forma	= new String($("#selectFormas").val())
	
	forma	= Forma.split('|');
	
	mensaje="";
	
	if($("#txtIdUsuarioVendedor").val()=="0")
	{
		mensaje+="Seleccione el vendedor <br />";
	}
	
	if($("#selectMostrador").val()=="0")
	{
		if(obtenerNumeros(forma[0])!=7)
		{
			/*if(!comprobarNumeros($("#txtPago").val()) || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
			{
				mensaje+="El pago es incorrecto <br />";
			}*/
		}
	}
	
	if($("#selectMostrador").val()=="1")
	{
		if($('#selectDirecciones').val()=="0")
		{
			mensaje+="Seleccione la dirección de entrega <br />";
		}
	}

	if($('#selectFormas').val()!="1" && $('#selectFormas').val()!="4") 
	{
		if($('#cuentasBanco').val()=="0")
		{
			mensaje+="Debe seleccionar una cuenta y un banco <br />";
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}

	
	$("#ventanaCfdiFactura").dialog('open');
	
	desactivarBotonesVenta();
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCfdiVenta').html('<img src="'+ img_loader +'"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'ventas/formularioCfdiVenta',
		data:
		{
			idCliente: $('#txtIdCliente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCfdiVenta').html(data);
		},
		error:function(datos)
		{
			$('#formularioCfdiVenta').html('');
			notify('Error en el formulario',500,5000,'error',30,5);
		}
	});		
}

function registrarFacturaVenta()
{
	m				= 0;
	mensaje			= "";
	cambioVentas	= 0;
	
	Forma			= new String($("#selectFormas").val())
	forma			= Forma.split('|');
	
	$("#btnRegistrarCfiVenta").button("disable");
	
	if(ejecutarAccion && ejecutarAccion.readyState != 4)
	{
		notify('Ya se esta procesando el registro',500,5000,'error',30,5);
		return;
	}
	
	if($("#txtIdUsuarioVendedor").val()=="0")
	{
		mensaje+="Seleccione el vendedor <br />";
	}

	if($("#txtSubTotal").val()=="0")
	{
		mensaje+="No se han agregado productos para la venta <br />";
	}
	
	if($("#selectMostrador").val()=="0")
	{
		if(obtenerNumeros(forma[0])!=7)
		{
			if(!comprobarNumeros($("#txtPago").val()) || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
			{
				//mensaje+="El pago es incorrecto <br />";
			}
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
	
	if($('#selectDireccionesCfdi').val()=="0")
	{
		mensaje+="Seleccione la dirección <br />";
	}
	
	
	v	= 0;
	ban	= true;
	
	for(i=0;i<=fila;i++)
	{
		if(obtenerNumeros($('#txtIdProducto'+i).val())>0)
		{
			precio	= obtenerNumeros($('#txtTotalProducto'+i).val())
		
			if(precio==0)
			{
				ban			= false;
			}   
		}
	}
	
	if(!ban)
	{
		mensaje+="Por favor verifique los precios de los productos<br />";
	}
	
	if(obtenerNumeros($('#txtIdSucursal').val())>0 && $('#selectMostrador').val()=="1")
	{
		mensaje+="La venta incluye un traspaso, debe venderse en mostrador";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		
		$("#btnRegistrarCfiVenta").button("enable");
		return;
	}
	
	
	cambioVentas	= obtenerNumeros($('#txtCambio').val());
	
	
	if(!confirm('¿Realmente deseea realizar la venta y factura?'))
	{
		$("#btnRegistrarCfiVenta").button("enable");
		return;
	}

	ejecutarAccion=$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoCfdiVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta y la factura, por favor espere ...');},
		type:"POST",
		//url:base_url+'clientes/registrarVenta',
		
		url:base_url+'ventas/registrarVentaFactura',
		
		data: $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&tipoVenta='+tipoVenta+'&'+$('#frmCfdiVentas').serialize()
		+'&condiciones='+$('#txtCondicionesPagoVenta').val()
		+'&tipoEnvio=' + $("#selectMostrador option:selected").text()
		+'&metodoPago='+$("#selectMetodoPagoVenta").val()+'&metodoPagoTexto='+$("#selectMetodoPagoVenta option:selected").text()
		+'&formaPago='+$("#selectFormaPagoVenta").val()+'&formaPagoTexto='+$("#selectFormaPagoVenta option:selected").text()
		+'&usoCfdi='+$("#selectUsoCfdiVenta").val()+'&usoCfdiTexto='+$("#selectUsoCfdiVenta option:selected").text(),
		datatype:"html",

		success:function(data, textStatus)
		{
			$("#registrandoCfdiVenta").html('');
			$("#btnRegistrarCfiVenta").button("enable");
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('La venta y la factura se han realizado correctamente '+(data[4]=='0'?' <br/>Favor de agregar un correo electrónico para enviar la factura':''),500,5000,'',30,5);
					
					$("#formularioPedidos").html('');
					$("#ventanaCfdiFactura").dialog('close');
					
					setTimeout(function() 
					{
						obtenerTicket(data[1]);
						
						desactivarBotonesVenta();
						
					}, 100);
					
					if(obtenerNumeros($('#txtDiasCredito').val())==0)
					{
						setTimeout(function() 
						{
							obtenerTicket(data[1]);
						}, 1500);
					}

					setTimeout(function() 
					{
						cambioVenta()
					}, 2500);
					
					window.open(base_url+'pdf/crearFactura/'+data[2]);
					
					
					subTotal	= 0;
					
					
				
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCfdiVenta").html('');
			notify('Error al realizar la venta, por favor verifique la conexión a internet',500,5000,'error',30,3);
			$("#btnRegistrarCfiVenta").button("enable");
		}
	});		
}

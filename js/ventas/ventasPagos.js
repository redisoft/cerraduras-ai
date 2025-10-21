function buscarCuentasCliente()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarCuenta').html('<img src="'+ img_loader +'"/>Obteniendo la lista de cuentas');
		},
		type:"POST",
		url:base_url+'ficha/obtenerCuentas/'+$('#listaBancos').val()+"/"+$('#txtIdCliente').val(),
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarCuenta').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las cuentas',500,5000,'error',2,5);
			$("#cargarCuenta").html('');
		}
	});
}

function buscarCuentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarCuenta').html('<img src="'+ img_loader +'"/>Obteniendo la lista de cuentas');
		},
		type:"POST",
		url:base_url+'ficha/obtenerCuentas/'+$('#listaBancos').val(),
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarCuenta').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las cuentas',500,5000,'error',2,5);
			$("#cargarCuenta").html('');
		}
	});
}

//========================================================================================================//
	
ventita=0;
function obtenerPagosClientes(idCotizacion)
{
	$('#ventanaPagosClientes').dialog('open');
	
	ventita=idCotizacion;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarPagosClientes').html('<img src="'+ img_loader +'"/>Obteniendo detalles de cobros, por favor espere...');
		},
		type:"POST",
		url:base_url+'ficha/obtenerCobrosClientes',
		data:
		{
			"idCotizacion":idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarPagosClientes").html(data);
			//catalogos();
			$("#montoPagar").focus();
		},
		error:function(datos)
		{
			$("#cargarPagosClientes").html('');	
			notify('Error al obtener los cobros',500,4000,"");
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaPagosClientes").dialog(
	{
		//closeOnEscape: false,
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
				realizarCobro();
			},
		},
		close: function() 
		{
			$('#cargarPagosClientes').html('');
		}
	});
});

function realizarCobro()
{
	//$("#ventanaPagosClientes").button("Aceptar", "disabled", true);
	
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	if($('#txtConcepto').val()=="0")
	{
		//mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#txtImporte').val()=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}

	if($('#selectDepartamento').val()=="0")
	{
		mensaje+="Seleccione el departamento <br />";
	}
	
	/*if($('#selectNombres').val()=="0")
	{
		mensaje+="Seleccione el nombre <br />";
	}*/

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
		
		/*if($('#selectNombres').val()=="0") //COMENTADO PARA CAMBIOS 09 ABRIL 2016
		{
			mensaje+="Seleccione el nombre <br />";
		}*/
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
	
	if(!confirm('¿Realmente desea registrar el cobro?')) return;
	
	var formData = new FormData($('#frmCobroCliente')[0]);
	
	$.ajax(
	{
		beforeSend:function()
		{
			$('#cargandoPagosClientes').html('<img src="'+ img_loader +'"/> Se esta realizando el cobro, por favor espere...');
		},
		async   : true,
		type    : "POST",
		url     : base_url+"ficha/realizarPago",
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		/*data	: 
		{
			"idVenta":				$('#txtIdVenta').val(),
			"montoPagar":			$('#montoPagar').val(),
			"cuentasBanco":			$('#cuentasBanco').val(),
			"numeroCheque":			$('#numeroCheque').val(),
			"numeroTransferencia":	$('#numeroTransferencia').val(),
			"idForma":				$('#selectFormas').val(),
			"banco":				$('#listaBancos').val(),
			//incluyeIva:				document.getElementById('chkIva').checked==true?1:0,
			idNombre:				idNombre,
			idProducto:				$('#txtConcepto').val(),
			idGasto:				$('#selectTipoGasto').val(),
			iva:					$('#txtIvaPorcentaje').val(),
			idDepartamento:			$('#selectDepartamento').val(),
			concepto:      		 	$('#txtDescripcionProducto').val(),
			nombreReceptor:			$('#txtNombreReceptor').val(),
			fecha:					$('#txtFechaIngreso').val(),
			factura:				$('#txtFactura').val(),
			comentarios:			$('#txtComentarios').val(),
			idCliente:				$('#txtIdClienteCobro').val(),
			remision:				$('#selectFacturaRemision').val(),
		},*/
		
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#cargandoPagosClientes').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',32,5);
				break;
						
				case "1":
					notify(data[1],500,5000,'',32,5);
					obtenerPagosClientes($('#txtIdVenta').val());
					
					if($('#txtModuloVentas').val()=="1")
					{
						obtenerVentas();
					}
					
					if($('#txtModuloActivo').val()=="ventaServicios")
					{
						obtenerVentaServicios();
					}
				break;
			}
		},
		error: function(datos)
		{
			notify('Error al realizar el cobro',500,5000,'error',32,5);
			$('#cargandoPagosClientes').html('');
		}
	});
}

function borrarCobro(idIngreso)
{
	if(confirm('¿Realmente desea borrar este cobro?'))
	{
		$.ajax(
		{
			async   : true,
			beforeSend:function(objeto)
			{
				$('#cargandoPagosClientes').html('<img src="'+ img_loader +'"/> Se esta borrando el cobro, por favor espere...');
			},
			type    : "POST",
			url     : base_url+"ficha/borrarCobro",
			data	: 
			{
				"idIngreso":idIngreso,
			},
			datatype: "html",
			success	: function(data, textStatus)
			{
				switch(data)
				{
					case "0":
						$('#cargandoPagosClientes').html('');
						notify('Error al borrar el cobro',500,5000,'error',34,4);
					break;
							
					case "1":
						$('#cargandoPagosClientes').html('');
						notify('El cobro se ha borrado correctamente',500,5000,'',34,4);
						obtenerPagosClientes($('#txtIdVenta').val());
						
						if($('#txtModuloActivo').val()=="ventaServicios")
						{
							obtenerVentaServicios();
						}
						
					break;
				}
			},
			error: function(datos)
			{
				$('#cargandoPagosClientes').html('');
				notify('Error al borrar el cobro',500,5000,'error',34,4);
			}
		});
	}
}


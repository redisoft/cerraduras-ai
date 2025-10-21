$(document).ready(function()
{
	$('#txtBuscarTicket').focus();
	
	$('#txtBuscarTicket').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerVentaFolio();
		}
	});
	
	$('#txtImporteCaja').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			registrarPagoCaja();
		}
	});
	
	$("#ventanaCobrarVenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		[
		 	{
				id: "btnCancelar",
                text: "Cancelar",
                click: function() 
				{
                    $( this ).dialog( "close" );
                }
            },
            {
				id: "btnPagar",
                text: "Registrar",
                click: $.noop,
                type: "submit",
				form: "frmCobroCaja",
				
            },
        ],
		close: function() 
		{
			$('#txtBuscarTicket').val('')
			$('#txtBuscarTicket').focus();
			
			$("#btnPagar, #btnCancelar").button("enable");
			
			
			$('#txtIdCotizacion').val(0);
			$('#txtIdCliente').val(0);
			$('#txtConcepto').val('');
			
			$('#txtIdForma').val(0);
			$('#txtNumeroFormas').val(1);
			$('#lblForma').html('');
			

			$('#txtImporteCaja').val('');
			$('#lblCambio').html('$0.00')
			$('#selectFormaCobroCaja').val(0);
			
			$('#frmCobroCaja')[0].reset();
		}
	});
});

function obtenerVentaFolio()
{
	if(ejecutar && ejecutar.readyState != 4)
	{
		notify('Se esta buscando el registro',500,5000,'error',30,5);
		return;
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#buscandoTicket').html('<img src="'+ img_loader +'"/>Buscando el registro...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerVentaFolio',
		data:
		{
			folio: 			$('#txtBuscarTicket').val(),
			prefactura: 	document.getElementById('chkPrefactura').checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			var datos	= $.parseJSON(data);
			
			$("#buscandoTicket").html('');
			
			switch(datos.idCotizacion)
			{
				case 0:
					notify('El folio no existe',500,5000,'error',30,5);
					break;
				
				default:
					
					total	= datos.total-datos.pagado;
					total	= redondear(total);
					
					$('#ventanaCobrarVenta').dialog('open');
					
					$('#lblFolio').html(datos.folio);
					$('#lblTotal').html('$'+total);
					$('#txtSaldoCaja').val(total);

					$('#txtIdCotizacion').val(datos.idCotizacion);
					$('#txtIdCliente').val(datos.idCliente);
					$('#txtConcepto').val('VEN-'+datos.folioVenta);

					$('#txtIdForma').val(datos.idForma);
					$('#selectFormasPago0').val(datos.idForma);
					//$('#lblForma').html(datos.forma);

					$('#txtImporteCaja').focus();
					
					if(total>0)
					{
						$('#txtImporteCaja0').prop('disabled',false);
						$('#lblEstatus').html('Pendiente');
						$("#btnCargarForma").fadeIn()
					}
					else
					{
						$('#txtImporteCaja0').prop('disabled',true);
						$('#lblEstatus').html('Pagado');
						$("#btnCargarForma").fadeOut()
					}
					
					/*if(total>0)
					{
						$('#ventanaCobrarVenta').dialog('open');
					
						$('#lblFolio').html(datos.folio);
						$('#lblTotal').html('$'+total);
						$('#txtSaldoCaja').val(total);
						
						$('#txtIdCotizacion').val(datos.idCotizacion);
						$('#txtIdCliente').val(datos.idCliente);
						$('#txtConcepto').val('VEN-'+datos.folioVenta);
						
						$('#txtIdForma').val(datos.idForma);
						$('#lblForma').html(datos.forma);
						
						$('#txtImporteCaja').focus();
					}
					else
					{
						notify('El ticket está pagado',500,5000,'',30,5);
					}*/
					
					break;
			}
		},
		error:function(datos)
		{
			notify('Error al buscar el registro',500,5000,'error',30,5);
			$("#buscandoTicket").html('');
		}
	});
}

function cargarFormaPago()
{
	f = obtenerNumeros($('#txtNumeroFormas').val());

	$("#lblForma").append('<div id="filaPago' + f + '"></div>');
	
	$('#selectFormasPago0').clone().attr('id', 'selectFormasPago' + f).attr('name', 'selectFormasPago' + f).appendTo($('#filaPago'+f));

	$("#filaPago" + f).append(' <input type="number" id="txtImporteCaja' + f + '" name="txtImporteCaja' + f + '" class="cajas" min="0.1" max="99999999" step="any"  maxlength="8" placeholder="$ Importe " required="true" onchange="sumarFormasPagoCaja()"/>  <input type="hidden" id="txtNumeroRegistro' + f + '" name="txtNumeroRegistro' + f + '" value="'+f+'"/> <img src="'+base_url+'img/borrar.png" width="22" onclick="borrarFormaPago('+f+')"/>');

	f++;

	$('#txtNumeroFormas').val(f);

	revisarFormasPagoCaja()
}

function borrarFormaPago(i)
{
	$("#filaPago" + i).remove();

	sumarFormasPagoCaja()

	revisarFormasPagoCaja();

	calcularCambioCaja()
}

function sumarFormasPagoCaja()
{
	f		= obtenerNumeros($('#txtNumeroFormas').val());

	total	= 0;

	for (i = 0; i < f; i++)
	{
		total += obtenerNumeros($('#txtImporteCaja'+i).val());
	}

	$("#lblTotalFormas").html('Total: $'+redondear(total));

	return total;
}


function revisarFormasPagoCaja()
{
	f		= obtenerNumeros($('#txtNumeroFormas').val());
	r		= 1;

	for (i = 1; i < f; i++)
	{
		if (obtenerNumeros($('#txtNumeroRegistro' + i).val())>0)
		{
			r++;
		}
		
	}

	if (r == 1)
	{
		$('#filaCambio').fadeIn()
		$('#lblTotalFormas').fadeOut()
		$('#txtNumeroPagos').val(1)
		return true;
	}
	else
	{
		$('#filaCambio').fadeOut()
		$('#lblTotalFormas').fadeIn()
		$('#txtNumeroPagos').val(r)

		return false;
	}
}



function calcularCambioCaja()
{
	saldo		= obtenerNumeros($('#txtSaldoCaja').val());
	importe		= obtenerNumeros($('#txtImporteCaja0').val());
	cambio		= saldo-importe;
	
	if(cambio<0)
	{
		$('#lblCambio').html('$'+redondear(cambio*-1))
	}
	else
	{
		$('#lblCambio').html('$0.00')
	}
}

function registrarPagoCaja()
{
	saldo		= obtenerNumeros($('#txtSaldoCaja').val());
	//importe		= obtenerNumeros($('#txtImporteCaja').val());
	importe		= sumarFormasPagoCaja();
	cambio		= saldo-importe;

	
	
	//$("#btnPagar, #btnCancelar").button("disable");
	
	//|| importe<saldo

	if (revisarFormasPagoCaja())
	{
		if(importe==0 )
		{
			notify('El pago es incorrecto',500,5000,'error',30,5);
		
			$("#btnPagar, #btnCancelar").button("enable");
			return;
		}
	}
	else
	{
		if (importe == 0 || importe > saldo)
		{
			notify('El pago es incorrecto',500,5000,'error',30,5);
		
			$("#btnPagar, #btnCancelar").button("enable");
			return;
		}
	}
	
	
	if(ejecutar && ejecutar.readyState != 4)
	{
		notify('Se esta registrando el pago',500,5000,'error',30,5);
		
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el pago?'))
	{
		$("#btnPagar, #btnCancelar").button("enable");
		return;
	}
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPagoCaja').html('<img src="'+ img_loader +'"/>Registrando el pago...');
		},
		type:"POST",
		url:base_url+'ventas/registrarPagoCaja',
		data:$('#frmCobroCaja').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			 data	= eval(data);
			
			$("#registrandoPagoCaja").html('');
			
			switch(data[0])
			{
				case "0":
					notify('Error al registrar el pago',500,5000,'error',30,5);
					break;
				
				case "1":
					
					$('#ventanaCobrarVenta').dialog('close');
					
					notify('El pago ha sido exitoso',500,5000,'',30,5);
					
					break;
			}
		},
		error:function(datos)
		{
			notify('Error en el pago',500,5000,'error',30,5);
			$("#registrandoPagoCaja").html('');
			
			$("#btnPagar, #btnCancelar").button("enable");
		}
	});
}

$(document).ready(function()
{
	$("#ventanaValesRetiro").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:270,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Cancelar": 
			{
				text: "Cancelar",
				id: "btnCancelarVales",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	},
			"Aceptar": 
			{
				text: "Registrar",
				id: "btnRegistrarVales",
				click: function()
				{
					registrarValesRetiros();	
				}   
		  	} 
		},
		close: function() 
		{
			$('#formularioValesRetiros').val('')
		}
	});
});

function formularioValesRetiros(tipoRegistro)
{
	$("#ventanaValesRetiro").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioValesRetiros').html('<img src="'+ img_loader +'"/>Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'ventas/formularioValesRetiros',
		data:
		{
			tipoRegistro: 		tipoRegistro,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioValesRetiros").html(data);
		},
		error:function(datos)
		{
			notify('Error al preparar el formulario',500,5000,'error',30,5);
			$("#formularioValesRetiros").html('');
		}
	});
}

function registrarValesRetiros()
{
	if(obtenerNumeros($('#txtImporteValeRetiro').val())==0)
	{
		notify('El importe es incorrecto',500,5000,'error',30,5);
		
		$("#btnRegistrarVales, #btnCancelarVales").button("enable");
		return;
	}
	
	if(ejecutar && ejecutar.readyState != 4)
	{
		notify('Se esta registrando el movimiento',500,5000,'error',30,5);
		
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el movimiento?'))
	{
		$("#btnRegistrarVales, #btnCancelarVales").button("enable");
		return;
	}
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoValesRetiro').html('<img src="'+ img_loader +'"/>Registrando el movimiento...');
		},
		type:"POST",
		url:base_url+'ventas/registrarValesRetiros',
		data:$('#frmValesRetiros').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			 data	= eval(data);
			
			$("#procesandoValesRetiro").html('');
			
			switch(data[0])
			{
				case "0":
					notify('Error al registrar el movimiento',500,5000,'error',30,5);
					break;
				
				case "1":
					
					$('#ventanaValesRetiro').dialog('close');
					
					notify('El movimiento ha sido exitoso', 500, 5000, '', 30, 5);

					window.open(base_url + 'reportes/imprimirTicketVales/' + data[2]);
					
					break;
			}
		},
		error:function(datos)
		{
			notify('Error en el movimiento',500,5000,'error',30,5);
			$("#procesandoValesRetiro").html('');
			
			$("#btnRegistrarVales, #btnCancelarVales").button("enable");
		}
	});
}

//CORTE DE CAJA
$(document).ready(function()
{
	$("#ventanaCorteCaja").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Aceptar": 
			{
				text: "Imprimir",
				id: "btnImprimir",
				click: function()
				{
					imprimirCorte();
				}   
		  	} 
		},
		close: function() 
		{
			$('#formularioCorte').val('')
		}
	});
});

function formularioCorte()
{
	$("#ventanaCorteCaja").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCorte').html('<img src="'+ img_loader +'"/>Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'ventas/formularioCorte',
		data:
		{
			idEstacion: 	$('#selectEstaciones').val(),
			fecha: 			$('#txtFechaCorte').val(),
			idUsuario: 		$('#selectCajeros').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioCorte").html(data);
		},
		error:function(datos)
		{
			notify('Error al preparar el formulario',500,5000,'error',30,5);
			$("#formularioCorte").html('');
		}
	});
}

function opcionesCortes() 
{
	switch($("#selectCajeros").val())
	{
		case "0": 
		break;
			
		default: 
			accesoOpcionCorte(1);
		break;
	}
}

function imprimirCorte()
{
	document.forms['frmCorte'].submit();
}

//REPORTE DE RETIROS
$(document).ready(function()
{
	$('#txtInicio,#txtFin').datepicker();
	
	$("#ventanaReporteRetiros").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Aceptar": 
			{
				text: "Aceptar",
				id: "btnCerrarReoprte",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	} 
		},
		close: function() 
		{
			$('#obtenerReporteRetiros').html('')
		}
	});
});

function obtenerReporteRetiros()
{
	$("#ventanaReporteRetiros").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporteRetiros').html('<img src="'+ img_loader +'"/>Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerReporteRetiros',
		data:
		{
			idEstacion: $('#selectEstacionesReporte').val(),
			inicio: 	$('#txtInicio').val(),
			fin: 		$('#txtFin').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerReporteRetiros").html(data);
		},
		error:function(datos)
		{
			notify('Error al preparar el formulario',500,5000,'error',30,5);
			$("#obtenerReporteRetiros").html('');
		}
	});
}

//SALDO INICIAL
$(document).ready(function()
{
	$("#ventanaSaldoInicial").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:220,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Aceptar": 
			{
				text: "Aceptar",
				id: "btnSaldoInicial",
				click: function()
				{
					registrarSaldoInicial();
				}   
		  	} 
		},
		close: function() 
		{
			$('#formularioSaldoInicial').html('')
		}
	});
});

function formularioSaldoInicial()
{
	$("#ventanaSaldoInicial").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSaldoInicial').html('<img src="'+ img_loader +'"/>Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'ventas/formularioSaldoInicial',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioSaldoInicial").html(data);
		},
		error:function(datos)
		{
			notify('Error al preparar el formulario',500,5000,'error',30,5);
			$("#formularioSaldoInicial").html('');
		}
	});
}

function registrarSaldoInicial()
{
	mensaje="";
	
	if(obtenerNumeros($('#txtImporte').val())==0)
	{
		mensaje+='El importe es incorrecto <br />';
	}
	
	if(!camposVacios($('#txtConceptoSaldo').val()))
	{
		mensaje+='La descripción es incorrecta ';
	}
	
	if(mensaje.length)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el movimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoSaldoInicial').html('<img src="'+ img_loader +'"/>Registrando el movimiento...');
		},
		type:"POST",
		url:base_url+'ventas/registrarSaldoInicial',
		data:$('#frmSaldoInicial').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			data	= eval(data);
			
			$("#procesandoSaldoInicial").html('');
			
			switch(data[0])
			{
				case "0":
						notify('Error al registrar el movimiento',500,5000,'error',30,5);
					break;
				
				case "1":
					
					$('#ventanaSaldoInicial').dialog('close');
					
					notify('El movimiento ha sido exitoso',500,5000,'',30,5);
					
					break;
			}
		},
		error:function(datos)
		{
			notify('Error en el movimiento',500,5000,'error',30,5);
			$("#procesandoSaldoInicial").html('');
		}
	});
}


//REPORTE DE VENTAS EFECTIVO
$(document).ready(function()
{
	$('#txtInicioEfectivo,#txtFinEfectivo,#txtFechaCorte').datepicker({maxDate:0});
	
	$("#ventanaVentasEfectivo").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Aceptar": 
			{
				text: "Aceptar",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	} 
		},
		close: function() 
		{
			$('#obtenerVentasEfectivo').html('')
		}
	});
	
	$(document).on("click", ".ajax-pagVentasEfectivo > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentasEfectivo";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idEstacion: $('#selectEstacionesEfectivo').val(),
				inicio: 	$('#txtInicioEfectivo').val(),
				fin: 		$('#txtFinEfectivo').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentasEfectivo').html('<img src="'+ img_loader +'"/>Obteniendo registros');
			},
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
});

function obtenerVentasEfectivo()
{
	$("#ventanaVentasEfectivo").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentasEfectivo').html('<img src="'+ img_loader +'"/>Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerVentasEfectivo',
		data:
		{
			idEstacion: $('#selectEstacionesEfectivo').val(),
			inicio: 	$('#txtInicioEfectivo').val(),
			fin: 		$('#txtFinEfectivo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerVentasEfectivo").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte',500,5000,'error',30,5);
			$("#obtenerVentasEfectivo").html('');
		}
	});
}

//DETALLES DE PAGOS
$(document).ready(function()
{
	$("#ventanaDetallesPago").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			"Aceptar": 
			{
				text: "Aceptar",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	} 
		},
		close: function() 
		{
			$('#obtenerDetallesPagos').html('')
		}
	});
});

function obtenerDetallesPagos(idForma)
{
	$("#ventanaDetallesPago").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesPagos').html('<img src="'+ img_loader +'"/>Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerDetallesPagos',
		data:
		{
			idEstacion: $('#selectEstaciones').val(),
			fecha: 		$('#txtFechaCorte').val(),
			idForma: 	idForma
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDetallesPagos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte',500,5000,'error',30,5);
			$("#obtenerDetallesPagos").html('');
		}
	});
}

function obtenerDetallesPendiente()
{
	$("#ventanaDetallesPago").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesPagos').html('<img src="'+ img_loader +'"/>Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerDetallesPendiente',
		data:
		{
			idEstacion: $('#selectEstaciones').val(),
			fecha: 		$('#txtFechaCorte').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDetallesPagos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte',500,5000,'error',30,5);
			$("#obtenerDetallesPagos").html('');
		}
	});
}



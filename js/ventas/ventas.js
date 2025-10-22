var filtroProductosFirma = "";

function cacheProductosDesdeTabla()
{
	if(!window.posCache || typeof window.posCache.saveProductos !== 'function')
	{
		return;
	}

	var filas = document.querySelectorAll('#example tbody tr');
	if(!filas.length)
	{
		return;
	}

	var productosCache = [];

	filas.forEach(function(fila)
	{
		var inputId = fila.querySelector('input[id^="txtIDProducto"]');
		if(!inputId)
		{
			return;
		}

		var sufijo = inputId.id.replace('txtIDProducto', '');
		var obtenerValor = function(prefijo)
		{
			var campo = document.getElementById(prefijo + sufijo);
			return campo ? campo.value : '';
		};

		var idProducto = parseInt(inputId.value, 10);
		if(isNaN(idProducto))
		{
			return;
		}

		var codigoInterno = obtenerValor('txtCodigoProducto');
		var nombreProducto = obtenerValor('txtNombre');
		var esServicio = parseInt(fila.getAttribute('data-servicio') || '0', 10) === 1;

		var selectPrecios = document.getElementById('selectPrecios' + sufijo);
		var preciosLista = [];
		var precioCliente = 1;
		if(selectPrecios)
		{
			for(var idx = 0; idx < selectPrecios.options.length; idx++)
			{
				var valor = parseFloat(selectPrecios.options[idx].value) || 0;
				preciosLista.push(valor);
			}
			precioCliente = selectPrecios.selectedIndex + 1;
		}

		var impuestoNombre = obtenerValor('txtImpuestoNombre');
		var impuestoTasa = parseFloat(obtenerValor('txtImpuestoTasa')) || 0;
		var impuestoTipo = obtenerValor('txtImpuestoTipo');
		var impuestoTotal = parseFloat(obtenerValor('txtImpuestoTotal')) || 0;
		var impuestoId = obtenerValor('txtImpuestoId');

		var precioA = parseFloat(obtenerValor('txtActualPrecio')) || 0;
		var cantidadMayoreo = parseFloat(obtenerValor('txtMayoreoCantidad')) || 0;
		var stock = parseFloat(obtenerValor('txtCantidadTotal')) || 0;

		productosCache.push({
			idProducto: idProducto,
			nombre: nombreProducto,
			codigoInterno: codigoInterno,
			unidad: obtenerValor('txtUnidad'),
			servicio: esServicio ? 1 : 0,
			precioA: precioA,
			precioB: preciosLista[1] || 0,
			precioC: parseFloat(obtenerValor('txtPrecio3')) || 0,
			precioD: preciosLista[3] || 0,
			precioE: preciosLista[4] || 0,
			preciosLista: preciosLista,
			precioCliente: precioCliente,
			precioTarjeta: precioA ? precioA * 1.025 : 0,
			cantidadMayoreo: cantidadMayoreo,
			stock: stock,
			impuestos:
			{
				nombre: impuestoNombre,
				tasa: impuestoTasa,
				tipo: impuestoTipo,
				total: impuestoTotal,
				id: impuestoId
			},
			ultimaActualizacion: Date.now()
		});
	});

	if(productosCache.length)
	{
	window.posCache.saveProductos(productosCache)
		.then(function()
		{
			if(typeof window.posCache.setMetadata === 'function')
			{
				window.posCache.setMetadata('lastSyncProductos', Date.now()).catch(function(){});
			}
		})
		.catch(function(error)
		{
			console.warn('No se pudo almacenar productos localmente', error);
		});
	}
}

function formatCurrency(valor)
{
	return new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(valor || 0);
}

function formatNumber(valor)
{
	return new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(valor || 0);
}

function escapeHtml(valor)
{
	return String(valor || '').replace(/[&<>"']/g, function(caracter)
	{
		switch(caracter)
		{
			case '&': return '&amp;';
			case '<': return '&lt;';
			case '>': return '&gt;';
			case '"': return '&quot;';
			case "'": return '&#39;';
		}
		return caracter;
	});
}

function renderProductosDesdeCache(lista)
{
	var contenedor = $('#obtenerProductosVenta');
	if(!contenedor.length)
	{
		return;
	}

	if(!Array.isArray(lista) || !lista.length)
	{
		contenedor.html('<div class="Error_validar">Sin registro de productos</div>');
		return;
	}

	var precio1Visible = $('#txtPrecioCliente').val() !== '0';
	var html = '';

	html += '<input type="hidden" id="txtNumeroTotalProductos" value="'+lista.length+'"/>';
	html += '<div id="example-console"></div>';
	html += '<div style="width:90%; margin-top:0%;">';
	html += '<ul id="pagination-digg" class="ajax-pagVen"></ul>';
	html += '</div>';
	html += '<table class="admintable display" cellspacing="0" width="100%" id="example">';
	html += '<thead><tr>';
	html += '<th>#</th>';
	html += '<th width="12%">Código</th>';
	html += '<th width="20%">Nombre</th>';
	html += '<th>Unidad</th>';
	html += '<th>Stock</th>';
	html += '<th>Precio</th>';
	html += '<th>Precio tarjeta</th>';
	html += '<th '+(precio1Visible?'':'style="display:none"')+'>Precio 1</th>';
	html += '<th>Mayoreo</th>';
	html += '</tr></thead><tbody>';

	lista.forEach(function(producto, index)
	{
		var fila = index + 1;
		var clase = fila % 2 ? 'sinSombra' : 'sombreado';
		var preciosLista = producto.preciosLista || [];
		var precioA = producto.precioA || 0;
		var precioB = preciosLista[1] || 0;
		var precioC = producto.precioC || 0;
		var precioD = preciosLista[3] || 0;
		var precioE = preciosLista[4] || 0;
		var cantidadMayoreo = producto.cantidadMayoreo || 0;
		var stock = producto.stock || 0;
		var servicio = producto.servicio ? 1 : 0;
		var precioTarjeta = producto.precioTarjeta || (precioA * 1.025);
		var impuesto = producto.impuestos || {};

		html += '<tr class="'+clase+'" id="tab'+fila+'" data-servicio="'+servicio+'">';
		html += '<td style="font-size:11px">'+fila+'</td>';
		html += '<td style="font-size:11px">';
		html += '<input type="hidden" id="txtNombre'+fila+'" value="'+escapeHtml(producto.nombre)+'" />';
		html += '<input type="hidden" id="txtCodigoProducto'+fila+'" value="'+escapeHtml(producto.codigoInterno)+'" />';
		html += escapeHtml(producto.codigoInterno);
		html += '</td>';
		html += '<td style="font-size:11px">'+escapeHtml(producto.nombre)+'</td>';
		html += '<td style="font-size:11px">'+escapeHtml(producto.unidad)+'</td>';
		html += '<td style="font-size:11px" align="center" '+(stock===0?'style="color:red"':'')+'>'+formatNumber(stock)+'<br><label style="cursor:pointer" onclick="obtenerStockSucursales('+producto.idProducto+')">Sucursales</label></td>';

		html += '<td style="font-size:11px" align="center">$'+formatCurrency(precioA);
		html += '<select id="selectPrecios'+fila+'" class="cajasPrecios" style="height: 23px; width:100px; display:none">';
		[precioA, precioB, precioC, precioD, precioE].forEach(function(valor, idx)
		{
			html += '<option '+(producto.precioCliente === (idx+1) ? 'selected="selected"' : '')+' value="'+valor+'">$'+formatCurrency(valor)+'</option>';
		});
		html += '</select>';
		html += '<input type="hidden" id="txtActualPrecio'+fila+'" value="'+precioA+'" />';
		html += '<input type="hidden" id="txtCantidadTotal'+fila+'" value="'+(servicio ? 100000 : stock)+'" />';
		html += '<input type="hidden" id="txtIDProducto'+fila+'" value="'+producto.idProducto+'" />';
		html += '<input type="hidden" id="txtUnidad'+fila+'" value="'+escapeHtml(producto.unidad)+'" />';
		html += '<input type="hidden" id="txtMayoreoCantidad'+fila+'" value="'+cantidadMayoreo+'" />';
		html += '<input type="hidden" id="txtImpuestoNombre'+fila+'" value="'+escapeHtml(impuesto.nombre)+'" />';
		html += '<input type="hidden" id="txtImpuestoTasa'+fila+'" value="'+(impuesto.tasa || 0)+'" />';
		html += '<input type="hidden" id="txtImpuestoTipo'+fila+'" value="'+escapeHtml(impuesto.tipo)+'" />';
		html += '<input type="hidden" id="txtImpuestoTotal'+fila+'" value="'+(impuesto.total || 0)+'" />';
		html += '<input type="hidden" id="txtImpuestoId'+fila+'" value="'+(impuesto.id || 0)+'" />';
		html += '<input type="hidden" id="txtPrecio3'+fila+'" value="'+precioC+'" />';
		html += '</td>';

		html += '<td style="font-size:11px" align="center">$'+formatCurrency(precioTarjeta)+'</td>';
		html += '<td style="font-size:11px" '+(precio1Visible?'':'style="display:none"')+' align="center">$'+formatCurrency(precioC)+'</td>';
		html += '<td style="font-size:11px" align="center">'+formatNumber(cantidadMayoreo)+'</td>';
		html += '</tr>';
	});

html += '</tbody></table>';

contenedor.html(html);

	if(typeof window.inicializarTablaProductos === 'function')
	{
		window.inicializarTablaProductos(lista.length);
	}
}

function finalizarVentaOffline()
{
	$("#registrandoCobroVenta").html('');
	if($('#ventanaCobrosVenta').data('ui-dialog'))
	{
		$('#ventanaCobrosVenta').dialog('close');
	}
	formularioVentas();
	activarBotonesVenta();
	if(typeof window.actualizarEstadoConexion === 'function')
	{
		window.actualizarEstadoConexion();
	}
}

function guardarVentaOffline(url, payload, resumen)
{
	if(!window.posCache || typeof window.posCache.addVentaPendiente !== 'function')
	{
		notify('No se pudo guardar la venta sin conexión, intente nuevamente.',500,5000,'error',30,5);
		activarBotonesVenta();
		return;
	}

	var registro = {
		url: url,
		payload: payload,
		clienteId: resumen && resumen.clienteId ? resumen.clienteId : '',
		clienteNombre: resumen && resumen.clienteNombre ? resumen.clienteNombre : '',
		total: resumen && resumen.total ? resumen.total : '',
		fecha: new Date().toISOString()
	};

	window.posCache.addVentaPendiente(registro).then(function()
	{
		notify('Venta guardada sin conexión. Se enviará automáticamente al reconectar.',500,5000,'',30,5);
		finalizarVentaOffline();
	}).catch(function(error)
	{
		console.error('Error guardando venta offline', error);
		notify('No se pudo guardar la venta sin conexión.',500,5000,'error',30,5);
		activarBotonesVenta();
	});
}

function enviarVentaPendiente(venta)
{
	return new Promise(function(resolve)
	{
		$.ajax(
		{
			type: 'POST',
			url: venta.url || base_url+'ventas/registrarVenta',
			data: venta.payload,
			dataType: 'html'
		}).done(function(respuesta)
		{
			var ok = false;
			try
			{
				var data = eval(respuesta);
				ok = data && data[0] === "1";
			}
			catch(e)
			{
				ok = false;
			}
			if(ok && window.posCache && typeof window.posCache.removeVentaPendiente === 'function')
			{
				window.posCache.removeVentaPendiente(venta.id).finally(function()
				{
					resolve(true);
				});
			}
			else
			{
				resolve(false);
			}
		}).fail(function()
		{
			resolve(false);
		});
	});
}

window.syncVentasPendientes = function()
{
	if(!navigator.onLine || !window.posCache || typeof window.posCache.getVentasPendientes !== 'function')
	{
		return Promise.resolve();
	}

	return window.posCache.getVentasPendientes().then(function(lista)
	{
		if(!lista || !lista.length)
		{
			if(typeof window.actualizarEstadoConexion === 'function')
			{
				window.actualizarEstadoConexion();
			}
			return;
		}
		var exitos = 0;
		return lista.reduce(function(promise, venta)
		{
			return promise.then(function()
			{
				return enviarVentaPendiente(venta).then(function(ok)
				{
					if(ok)
					{
						exitos++;
					}
				});
			});
		}, Promise.resolve()).then(function()
		{
			if(exitos>0)
			{
				notify(exitos+' ventas sincronizadas correctamente.',500,5000,'',30,5);
			}
		}).finally(function()
		{
			if(typeof window.actualizarEstadoConexion === 'function')
			{
				window.actualizarEstadoConexion();
			}
		});
	});
};
window.inicializarTablaProductos = function(totalProductos)
{
	if(!$('#example').length || !$.fn.DataTable)
	{
		return;
	}

	if($.fn.DataTable.isDataTable('#example'))
	{
		$('#example').DataTable().destroy();
	}

	var table = $('#example').DataTable({
		keys:
		{
			keys: [13, 38, 40]
		},
		tabIndex: 3,
		pageLength: 8,
		deferRender: true
	});

	$('#example').off('key-focus.dt').on('key-focus.dt', function(e, datatable, cell)
	{
		$(table.row(cell.index().row).node()).addClass('selected');
	});

	$('#example').off('key-blur.dt').on('key-blur.dt', function(e, datatable, cell)
	{
		$(table.row(cell.index().row).node()).removeClass('selected');
	});

	$('#example').off('dblclick').on('dblclick', 'tbody td', function(e)
	{
		e.stopPropagation();
		var rowIdx = table.cell(this).index().row;
		var rowNode = table.row(rowIdx).node();
		var servicio = $(rowNode).data('servicio') || 0;
		agregarProductoVenta(rowIdx+1,servicio,'si','0');
	});

	$('#example').off('key.dt').on('key.dt', function(e, datatable, key, cell)
	{
		if(key === 13)
		{
			var data = table.row(cell.index().row).data();
			var rowNode = table.row(cell.index().row).node();
			var servicio = $(rowNode).data('servicio') || 0;
			setTimeout(function()
			{
				if(agregarProductoVenta(data[0],servicio,'si','0'))
				{
					table.cell.blur();
				}
			}, 100);
		}
	});

	$('#example_info, #example_paginate, #example_length, #example_filter').hide();

	if(totalProductos === 1)
	{
		agregarProductoVenta(1,0,'si','0');
	}

	window.tableProductosPOS = table;
};


$(document).ready(function()
{
	PRECIO1	= false;

	if(typeof window.syncVentasPendientes === 'function' && navigator.onLine)
	{
		window.syncVentasPendientes();
	}

	//$('.ajax-pagVen > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagVen > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerProductosVenta";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarProducto').val(),
				idCliente:	$('#txtIdCliente').val(),
				idLinea:	$('#selectLineas').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
		success:function(html,textStatus)
		{
			setTimeout(function()
			{
				$(element).html(html);
				cacheProductosDesdeTabla();
			},300);
			},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
	
	$("#ventanaVentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:570,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			/*Cancelar: function() 
			{
				$(this).dialog('close');				 
			},*/
			'Cobrar': function() 
			{
				formularioCobros()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioVentas").html('');
		}
	});
});

/*function opcionesFormasPagoVentas()
{
	if($('#selectFormas').val()=="1")
	{
		$('#filaBanco').fadeOut();
		$('#filaCuenta').fadeOut();
	}
	
	if($('#selectFormas').val()!="1")
	{
		$('#filaBanco').fadeIn();
		$('#filaCuenta').fadeIn();
	}
}*/

function interesesVentasTotales()
{
	calcularFilasIntereses(1);
	
	setTimeout(function() 
	{
		calcularTotales();
	}, 1000);
}

function opcionesFormasPagoVentas(opcionFormas)
{
	if(opcionFormas==1)
	{
		Formas		= new String($('#selectFormas').val());
		formas   	= Formas.split('|');
		forma	    = obtenerNumeros(formas[0]);
		
		if(forma==1)
		{
			$('#filaBanco').fadeOut();
			$('#filaCuenta').fadeOut();
		}
		
		if(forma!=1)
		{
			$('#filaBanco').fadeIn();
			$('#filaCuenta').fadeIn();
		}
		
		calcularFilasIntereses(1);
		
		setTimeout(function() 
		{
			calcularTotales();
		}, 1000);
		
	}
	else
	{
		if($('#selectFormas').val()=="1")
		{
			$('#filaBanco').fadeOut();
			$('#filaCuenta').fadeOut();
		}
		
		if($('#selectFormas').val()!="1")
		{
			$('#filaBanco').fadeIn();
			$('#filaCuenta').fadeIn();
		}
	}
}

tipoVenta		= 0;
cambioVentas	= 0;

function desactivarBotonesVenta()
{
	$("#btnCancelar,#btnClientes,#btnFactura,#btnAceptar").button("disable");
}

function activarBotonesVenta()
{
	$("#btnCancelar,#btnClientes,#btnFactura,#btnAceptar").button("enable");
}

function registrarVenta()
{
	m				= 0;
	mensaje			= "";
	cambioVentas	= 0;
	
	Forma	= new String($("#selectFormas").val())
	
	forma	= Forma.split('|');
	
	desactivarBotonesVenta();
	
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
		if(obtenerNumeros($('#txtIdSucursal').val())==0)
		{
			if(obtenerNumeros(forma[0])!=7)
			{
				/*if(!comprobarNumeros($("#txtPago").val()) || $("#txtPago").val()=="0" || parseFloat($("#txtPago").val())<parseFloat($("#txtTotal").val()))
				{
					mensaje+="El pago es incorrecto <br />";
				}*/
			}
		}
	}

	if($("#selectMostrador").val()=="1")
	{
		if($('#selectDirecciones').val()=="0")
		{
			mensaje+="Seleccione la dirección de entrega <br />";
		}
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
	
	
	
	v	= 0;
	ban	= true;
	
	for(d=0;d<=fila;d++)
	{
		if(obtenerNumeros($('#txtIdProducto'+d).val())>0)
		{
			precio	= obtenerNumeros($('#txtTotalProducto'+d).val())
		
			if(precio==0)
			{
				ban			= false;
			}   
		}
	}
	
	if(obtenerNumeros($('#txtIdSucursal').val())>0 && $('#selectMostrador').val()=="1")
	{
		mensaje+="La venta incluye un traspaso, debe venderse en mostrador<br />";
	}
	
	if(!ban)
	{
		mensaje+="Por favor verifique los precios de los productos<br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		
		activarBotonesVenta();
		
		return;
	}
	
	if($('#txtTipoUsuarioActivo').val()!="pinata")
	{
		faltantes	= comprobarFaltantesProductos();

		if(numeroFaltantes==0)
		{
			//if(!confirm('¿Realmente deseea realizar la venta?')) return;
		}
		
		if(numeroFaltantes>0)
		{
			formularioInventarioFaltante();
			
			activarBotonesVenta();
			
			return;
		}
	}
	
	cambioVentas	= obtenerNumeros($('#txtCambio').val());
	
	MetodoPago		= new String($("#selectMetodoPago option:selected").text());
	FormaPago		= new String($("#selectFormaPagoSat option:selected").text());
	UsoCfdi			= new String($("#selectUsoCfdi option:selected").text());
	
	metodoPago		= MetodoPago.split(',');
	formaPago		= FormaPago.split(',');
	usoCfdi			= UsoCfdi.split(',');
	
	url				= base_url+'clientes/registrarVenta';
	factura			= false;
	
	if(document.getElementById('chkFacturar').checked)
	{
		url				= base_url+'ventas/registrarVenta';
		factura			= true;
		
		if(!confirm('¿Realmente deseea realizar la venta y prefactura?'))
		{
			activarBotonesVenta();
			return;
		}
	}

	var ventaPayload = $('#frmVentasClientes').serialize()+'&'+$('#frmCobros').serialize()+'&tipoVenta='+tipoVenta+'&cotizacion=0'
	+'&condiciones='+$('#txtCondicionesPago').val()
	+'&tipoEnvio=' + $("#selectMostrador option:selected").text()
	+'&metodoPago='+metodoPago[0]+'&metodoPagoTexto='+MetodoPago
	+'&formaPago='+formaPago[0]+'&formaPagoTexto='+FormaPago
	+'&usoCfdi='+usoCfdi[0]+'&usoCfdiTexto='+UsoCfdi;

	var resumenVenta = {
		clienteId: $('#txtIdCliente').val(),
		clienteNombre: $('#txtBuscarCliente').val(),
		total: $('#txtTotal').val()
	};

	if(!navigator.onLine)
	{
		guardarVentaOffline(url, ventaPayload, resumenVenta);
		return;
	}

	
		ejecutarAccion=$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoCobroVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta, por favor tenga paciencia ...');},
		type:"POST",
		//url:base_url+'clientes/registrarVenta',
		
		url:url,
		
		data: ventaPayload,
		datatype:"html",

		success:function(data, textStatus)
		{
			$("#registrandoCobroVenta").html('');
			data	= eval(data);

			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
					
					activarBotonesVenta();
				break;
				
				case "1":
					
					PRECIO1	= false;
					
					notify('La venta se ha realizado correctamente',500,5000,'',30,5);
					//window.open(base_url+'pdf/nuevaVenta/'+data[1]+'/1');
					//window.open(base_url+'clientes/imprimirTicket/'+data[1]+'/1');
					//window.open(base_url+'pdf/nuevaRemisionFormato/'+data[1]);
					//formularioVentas();
					
					
					$("#formularioPedidos").html('');
					
					setTimeout(function() 
					{
						obtenerTicket(data[1]);
						
						/*if(impresoraLocal=="0")
						{
							obtenerTicket(data[1]);
						}
						else
						{
							invocarImpresora(data[1]);
						}*/
						
						
					}, 100);
					
					
					if(obtenerNumeros($('#txtDiasCredito').val())==0)
					{
						//SOLO UN TICKET
						/*setTimeout(function() 
						{
							obtenerTicket(data[1]);
						}, 1500);*/
					}
					
					if(obtenerNumeros($('#txtPago').val())>0)
					{
						setTimeout(function() 
						{
							cambioVenta()
						}, 1500);
					}
					else
					{
						$('#ventanaCobrosVenta').dialog('close');
						formularioVentas();
					}
					
					//CREAR EL CFDI
					if(factura)
					{
						//window.open(base_url+'pdf/crearFactura/'+data[2]);
					}
				
					
					subTotal	= 0;
					//$('#ventanaCobrosVenta').dialog('close');
					
					if($('#txtIdTienda').val()!="0")
					{
						//obtenerVentas();	
					}	
					
					fila	= 1;
					ma		= 0;
					
					
				
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoCobroVenta").html('');
			if(!navigator.onLine || datos.status === 0)
			{
				guardarVentaOffline(url, ventaPayload, resumenVenta);
			}
			else
			{
				notify('Error al realizar la venta, por favor verifique la conexión a internet',500,5000,'error',30,3);
				activarBotonesVenta();
			}
		}
	});	
}

function invocarImpresora(idCotizacion)
{
	try
	{
		window.location.assign("impresion:"+idCotizacion);

	}
	catch(Exception)
	{
	}
}


function comprobarProductosVentaPedido()
{
	ban=false;
	
	for(i=0;i<=fila;i++)
	{
		idProducto	= parseFloat($('#txtIdProducto'+i).val())
		
		if(!isNaN(idProducto))
		{
			ban=true;
			break;
		}
	}
	
	return ban;
}

function cambioVenta()
{
	//$("#btnCancelar,#btnVistaPrevia,#btnFactura,#btnVistaPrevia").button("disable");
	$('#formularioCobros').html('<div style="text-align: center; margin-top:200px; font-size: 50px; height:200px; width:100%"><input readonly="readonly" id="txtCambioActualizar" type="text" class="cajasTransparentes" value="Cambio: $'+redondear(cambioVentas)+'" /><input type="hidden" id="txtCambioActivo" name="txtCambioActivo" value="1" /></div>');
	
	$("#txtCambioActualizar").keypress(function(e)
	 {
		if(e.which == 13) 
		{
			//formularioVentas();
			$('#ventanaCobrosVenta').dialog('close');
		}
	});
	
	$('#txtCambioActualizar').focus();
}

function definirLinea(idLinea)
{
	$('#txtIdLinea').val(idLinea);
	
	obtenerProductosVenta()
}

function definirSubLinea(idSubLinea)
{
	$('#selectSubLineas').val(idSubLinea);
	
	obtenerProductosVenta()
}

//DELAY DEL BUSCADOR


function obtenerProductosVenta(retraso)
{
	var firmaActual = [
		$('#txtBuscarProducto').val(),
		$('#txtBuscarProductoCodigo').val(),
		$('#selectLineas').val(),
		$('#selectSubLineas').val(),
		$('#txtBuscarProveedor').val(),
		$('#txtIdCliente').val(),
		PRECIO1 ? '1' : '0'
	].join('|');

	if(firmaActual === filtroProductosFirma && retraso === 'debounce')
	{
		return;
	}

	filtroProductosFirma = firmaActual;

	var ejecutarConsultaServidor = function()
	{
		if(retraso!='1')
		{
			if(ejecutar && ejecutar.readystate != 4)
			{
				ejecutar.abort();
			}
		}
	
		if(sistemaActivo=='olyess')
		{
			if(!camposVacios($('#txtBuscarProducto').val()) && obtenerNumeros($('#selectLineas').val())==0 && obtenerNumeros($('#selectSubLineas').val())==0)
			{
				obtenerLineasVentas();
				filtroProductosFirma = '';
				return;
			}
		}
	
		ejecutar=$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerProductosVenta').html('<label><img src="'+ img_loader +'"/> Obteniendo la lista de productos...</label>');
			},
			type:"POST",
			url:base_url+'ventas/obtenerProductosVenta',
			data:
			{
				criterio:	$('#txtBuscarProducto').val(),
				idCliente:	$('#txtIdCliente').val(),
				idLinea:	$('#selectLineas').val(),
				idSubLinea:	$('#selectSubLineas').val(),
				codigoInterno:	$('#txtBuscarProductoCodigo').val(),
				proveedor:	$('#txtBuscarProveedor').val(),
				precio1:	!PRECIO1?'0':'1'
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#obtenerProductosVenta').html(data);
				cacheProductosDesdeTabla();
			},
			error:function(datos)
			{
				$('#obtenerProductosVenta').html('');
			}
		});
	};

	var filtroLinea = parseInt($('#selectLineas').val(), 10) || 0;
	var filtroSubLinea = parseInt($('#selectSubLineas').val(), 10) || 0;

	if(window.posCache && typeof window.posCache.searchProductos === 'function' && filtroLinea === 0 && filtroSubLinea === 0)
	{
		window.posCache.searchProductos({
			texto: $('#txtBuscarProducto').val(),
			codigo: $('#txtBuscarProductoCodigo').val(),
			limite: 40
		}).then(function(resultado)
		{
			if(resultado && resultado.length)
			{
				renderProductosDesdeCache(resultado);
				return;
			}
			ejecutarConsultaServidor();
		}).catch(function()
		{
			ejecutarConsultaServidor();
		});
		return;
	}

	ejecutarConsultaServidor();
}

function recargarPaginaVenta()
{
	location.reload(true)
}

function formularioVentas()
{
	tipoVenta	= 0;
	PRECIO1		= false;
	
	$('#ventanaVentas').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioVentas').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'clientes/formularioVentas',
		data:
		{
			idCliente: $('#txtIdClientePunto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioVentas').html(data);
			
			if(sistemaActivo=='olyess')
			{
					
			}
			else
			{
				obtenerProductosVenta();
			}
			
			
			
			$('#txtBuscarCodigo').focus();
			fila	= 0;
			ma		= 0;
			Fila	= -1;
			
			//$("#btnCancelar,#btnVistaPrevia,#btnFactura,#btnVistaPrevia").button("enable");
		},
		error:function(datos)
		{
			$('#formularioVentas').html('');
		}
	});		
}

fila	= 0;

function quitarProductoProducto(n)
{
	$('#filaProducto'+n).remove();
	Fila=-1;
	calcularSubTotal();
}

function comprobarDuplicidad(idProducto)
{
	for(x=0;x<=fila;x++)
	{
		if(!isNaN($('#txtIdProducto'+x).val()))
		{
			if(parseInt(idProducto)==parseInt($('#txtIdProducto'+x).val()))
			{
				cantidad	= parseFloat($('#txtCantidadProducto'+x).val())+1;
				total		= parseFloat($('#txtPrecioProducto'+x).val())*cantidad;
				total		= redondear(total);
				
				$('#txtCantidadProducto'+x).val(cantidad);
				$('#txtTotalProducto'+x).val(total);
				$('#filaTotal'+x).html('$'+total);
				
				calcularFilaProducto(i)
				//calcularSubTotal();
				return true;
			}
		}
	}
	
	return false;
}

Fila=-1;

function seleccionarFilaProducto(n)
{
	$('.filaProducto').removeClass('seleccionado')
	$('#filaProducto'+n).addClass('seleccionado');
	
	Fila=n;
}

function agregarProductoVenta(n,servicio,raton)//n es el numero de fila
{
	fila	= obtenerNumeros($('#txtNumeroProductos').val()); 
	/*if(comprobarDuplicidad($('#txtIDProducto'+n).val()))
	{
		setTimeout(function() 
		{
			$('#txtBuscarCodigo').val('');
			$('#txtBuscarCodigo').focus();
		}, 200);
		
		return;
	}*/
	
	precio=0;
	
	if(obtenerNumeros($('#txtCantidadTotal'+n).val())==0)
	{
		notify('No hay producto',500,1000,'error',30,5);
		return false;
	}
	
	if(!PRECIO1)
	{
		if(obtenerNumeros($('#selectPrecios'+n).val())==0)
		{
			notify('El precio es incorrecto',500,1000,'error',30,5);
			return false;
		}
		
		precio	= obtenerNumeros($('#selectPrecios'+n).val());
	}
	
	if(PRECIO1)
	{
		if(obtenerNumeros($('#txtPrecio3'+n).val())==0)
		{
			notify('El precio es incorrecto',500,1000,'error',30,5);
			return false;
		}
		
		precio	= obtenerNumeros($('#txtPrecio3'+n).val());
	}
	

	
	
	if(precio==0)
	{
		/*notify('El precio es incorrecto',500,5000,'error',30,5);
		return;*/
	}
	
	total	= parseFloat(precio)*parseFloat(1)
	total	= redondear(total);
	
	
	producto='<tr id="filaProducto'+fila+'" class="filaProducto" onclick="seleccionarFilaProducto('+fila+')">';
	producto+='<td class="filaProducto" width="80%">';
	producto+='<label id="lblNombreProducto'+fila+'">'+$('#txtNombre'+n).val()+'<strong>('+$('#txtCodigoProducto'+n).val()+')</strong></label><br />';
	producto+='<label class="informacionUnidad" style="margin-left:0px">';
	
	if($('#txtClaveDescuento').val()!='')
	{
		//producto+='<img src="'+base_url+'img/descuento.png" onclick="accesoAsignarDescuento('+fila+')" style="cursor:pointer;" title="Asignar descuento" />';
	}
	
	producto+='<input type="text" maxlength="8" id="txtCantidadProducto'+fila+'" name="txtCantidadProducto'+fila+'" class="cajasCantidades" value="1" onchange="calcularFilaProducto('+fila+',0,0,1,1)" onkeypress="return soloDecimales(event)" /> '+$('#txtUnidad'+n).val()+'';
	
	if(sistemaActivo=='olyess' || sistemaActivo=='cerraduras')
	{
		producto+='&nbsp;$<span id="lblPrecioProducto'+fila+'">'+total+'</span>\<input type="hidden" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';	
	}
	else
	{
		producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+',1)" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	}
	
	//producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+precio+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	
	producto+='<br /> <label id="lblDescuento'+fila+'" style="font-size:13px; margin-left:81px">Desc $0.00</label>';
	
	producto+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="lblPedido" style="font-size:14px; color: red"></label>';
	producto+='</td>';
	
	producto+='<td class="filaProducto" id="filaTotal'+fila+'" align="right">';
	producto+='$'+precio;
	producto+='</td>';
	producto+='<td class="filaProducto">';
	producto+='<img src="'+base_url+'img/borrar.png" onclick="quitarProductoProducto('+fila+')" />';
	producto+='</td>';
	

	producto+="<input type='hidden' id='txtNombreProducto"+fila+"' 		name='txtNombreProducto"+fila+"' 		value='"+$('#txtNombre'+n).val()+"' />";
	producto+="<input type='hidden' id='txtNombreProductoOriginal"+fila+"' 										value='"+$('#txtNombre'+n).val()+"' />";
	producto+='<input type="hidden" id="txtUnidadProducto'+fila+'" 		name="txtUnidadProducto'+fila+'" 		value="'+$('#txtUnidad'+n).val()+'" />';
	producto+='<input type="hidden" id="txtCodigoInterno'+fila+'" 		name="txtCodigoInterno'+fila+'" 		value="'+$('#txtCodigoProducto'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalProducto'+fila+'" 		name="txtTotalProducto'+fila+'" 		value="'+total+'" />';
	producto+='<input type="hidden" id="txtIdProducto'+fila+'" 			name="txtIdProducto'+fila+'" 			value="'+$('#txtIDProducto'+n).val()+'" />';
	producto+='<input type="hidden" id="txtServicio'+fila+'" 			name="txtServicio'+fila+'" 				value="'+servicio+'" />';
	producto+='<input type="hidden" id="txtStockDisponible'+fila+'" 	name="txtStockDisponible'+fila+'"  		value="'+$('#txtCantidadTotal'+n).val()+'" />';
	producto+='<input type="hidden" id="txtDescuentoProducto'+fila+'" 	name="txtDescuentoProducto'+fila+'" 	value="0" />';
	producto+='<input type="hidden" id="txtDescuentoPorcentaje'+fila+'" name="txtDescuentoPorcentaje'+fila+'" 	value="0" class="descuentosProductos"/>';
	
	producto+='<input type="hidden" id="txtImpuesto'+fila+'" 			name="txtImpuesto'+fila+'" 				value="'+$('#txtImpuestoNombre'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTasaImpuesto'+fila+'" 		name="txtTasaImpuesto'+fila+'" 			value="'+$('#txtImpuestoTasa'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTipoImpuesto'+fila+'" 		name="txtTipoImpuesto'+fila+'" 			value="'+$('#txtImpuestoTipo'+n).val()+'" />';
	producto+='<input type="hidden" id="txtIdImpuesto'+fila+'" 			name="txtIdImpuesto'+fila+'" 			value="'+$('#txtImpuestoId'+n).val()+'" />';
	producto+='<input type="hidden" id="txtTotalImpuesto'+fila+'" 		name="txtTotalImpuesto'+fila+'" 		value="'+$('#txtImpuestoTotal'+n).val()+'" />';
	
	producto+='<input type="hidden" id="txtPrecioOriginal'+fila+'" 		 value="'+total+'" />';
	producto+='<input type="hidden" id="txtPrecioC'+fila+'" 		 	value="'+$('#txtPrecio3'+n).val()+'" />';
	producto += '<input type="hidden" id="txtPrecio1' + fila + '" 		name="txtPrecio1' + fila +'"	 	value="0" />';
	
	if(sistemaActivo=='cerraduras')
	{
		producto+='<input type="hidden" id="txtCantidadMayoreo'+fila+'" 		 value="'+$('#txtMayoreoCantidad'+n).val()+'" />';
	}
	
	producto+='</tr>';
	
	//$('#tablaVentas').append(producto); 
	$("#tablaVentas tbody").prepend(producto);
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  

	fila++;
	
	$('#txtNumeroProductos').val(fila); 
	$('#carritoVacio').removeClass('Error_validar');
	$('#carritoVacio').html('');
	
	calcularSubTotal();
	
	FilaProducto=fila-1;
	
	setTimeout(function() 
	{
		
		$('#txtBuscarCodigo').val('');
		$('#txtBuscarCodigo').focus();
		
		if(preciosActivo=='1')
		{
			//calcularFilaProducto((fila-1),0,0,0,0);
			calcularFilaProducto(FilaProducto,0,0,0,0);
		}
		
		if(sistemaActivo=='cerraduras')
		{
			$('#txtBuscarProducto').val('')
			$('#txtBuscarProveedor').val('')
			$('#txtBuscarProductoCodigo').val('');
			
			if(!camposVacios($('#txtBuscarProducto').val()) || !camposVacios($('#txtBuscarProveedor').val()) || camposVacios($('#txtBuscarProductoCodigo').val()))
			{
				obtenerProductosVenta();
			}
			
			/*$('#txtCantidadProducto'+(fila-1)).focus();
			$('#txtCantidadProducto'+(fila-1)).select();*/
			
			$('#txtCantidadProducto'+FilaProducto).focus();
			$('#txtCantidadProducto'+FilaProducto).select();

			
			//$('#txtCantidadProducto'+(fila-1)).keydown(function(e)
			$('#txtCantidadProducto'+FilaProducto).keydown(function(e)
			 {
				if(e.which == 13 || e.which == 09) 
				{
					//if(calcularFilaProducto((fila-1),0,0,1,1))
					if(calcularFilaProducto(FilaProducto,0,0,1,1))
					{
						setTimeout(function() 
						{
							$("#txtBuscarProductoCodigo").focus();
						}, 400);
					}
					
					e.preventDefault();
				}
				
				
			});
		}
		
	}, 200);
	
	return true;
}

function accesoPrecioPermiso()
{
	if(Fila==-1)
	{
		notify('Seleccione un producto',500,5000,'error',30,5);
		return;	
	}
	
	accesoPrecio1(1)
}

function obtenerProductoCodigo()
{
	if(!camposVacios($('#txtBuscarCodigo').val()))
	{
		return;
	}
	
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoVenta').html('<label><img src="'+ img_loader +'"/> Obteniendo producto...</label>');
		},
		type:"POST",
		url:base_url+'ventas/obtenerProductoCodigo',
		data:
		{
			codigoBarras:	$('#txtBuscarCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoVenta').html(data);
			var datos	= $.parseJSON(data);
			
			if(datos.idProducto==0)
			{
				notify('El producto no se encuentra registrado',500,5000,'error',30,5);
			}
			else
			{
				agregarProductoCodigo(datos);
			}
			
		},
		error:function(datos)
		{
			$('#obtenerProductosVenta').html('');
		}
	});		
}

function asignarPrecio1()
{
	precio=redondear($('#txtPrecioC'+Fila).val())
	$('#txtPrecioProducto'+Fila).val(precio);
	$('#txtPrecioOriginal'+Fila).val(precio);
	$('#lblPrecioProducto'+Fila).html(precio);
	
	$('#txtPrecio1'+Fila).val(1);
	
	calcularFilaProducto(Fila)
}

function asignarPrecios1()
{
	for(f=0;f<=fila;f++)
	{
		idProducto	= obtenerNumeros($('#txtIdProducto'+f).val())
		
		if(idProducto>0)
		{
			precio	= redondear($('#txtPrecioC'+f).val())
			
			$('#txtPrecioProducto'+f).val(precio);
			$('#txtPrecioOriginal'+f).val(precio);
			$('#lblPrecioProducto'+f).html(precio);

			$('#txtPrecio1'+f).val(1);

			//calcularFilaProducto(i)
		}
	}
	
	for(l=0;l<=fila;l++)
	{
		idProducto	= obtenerNumeros($('#txtIdProducto'+l).val())
		
		if(idProducto>0)
		{
			calcularFilaProducto(l)
		}
	}
}

function obtenerProductoId(N,criterioPrecio)
{
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoVenta').html('<label><img src="'+ img_loader +'"/> Revisando precio mayoreo...</label>');
		},
		type:"POST",
		url:base_url+'ventas/obtenerProductoId',
		data:
		{
			idProducto:	$('#txtIdProducto'+N).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoVenta').html(data);
			var Producto	= $.parseJSON(data);
			
			if(Producto.idProducto==0)
			{
				notify('El producto no se encuentra registrado',500,5000,'error',30,5);
			}
			else
			{
				precio			= redondear(Producto.precioA);
				
				if(criterioPrecio=='mayoreo')
				{
					precio			= redondear(Producto.precioB);
				}
				
				$('#txtPrecioProducto'+N).val(precio);
				$('#txtPrecioOriginal'+N).val(precio);
				$('#lblPrecioProducto'+N).html(precio);

				/*impuesto		= precio - precio / (1 + (Producto.tasa/100))
				total			= precio*/
				
				calcularFilaProducto(N)
			}
			
		},
		error:function(datos)
		{
			$('#obtenerProductosVenta').html('');
		}
	});		
}

function calcularFilasIntereses(calcular)
{
	if(calcular==1)
	{
		Intereses	= new String($('#selectFormas').val());
		intereses   = Intereses.split('|');
		Interes	    = obtenerNumeros(intereses[1]);
	}
	else
	{
		Interes=0;
	}
	
	
	for(z=0;z<=fila;z++)
	{
		calcularFilaProducto(z,0,Interes);
	}
}

function revisarInventarioProducto(idProducto,inventario)
{
	cantidadP=0;
	
	for(j=0;j<=fila;j++)
	{
		if(obtenerNumeros($('#txtIdProducto'+j).val())==obtenerNumeros(idProducto))
		{
			cantidadP+= 	obtenerNumeros($('#txtCantidadProducto'+j).val());
		}
	}

	if(inventario<cantidadP)
	{
		return false;
	}
	
	return true;
}

function calcularFilaProducto(n,editar,interes,mayoreo,cursor)
{
	cantidad			= obtenerNumeros($('#txtCantidadProducto'+n).val());
	disponible			= obtenerNumeros($('#txtStockDisponible'+n).val());
	idProducto			= obtenerNumeros($('#txtIdProducto'+n).val());
	precio1				= obtenerNumeros($('#txtPrecio1'+n).val());
	
	if(sistemaActivo=='cerraduras')
	{
		/*if(disponible<cantidad)
		{
			$('#txtCantidadProducto'+n).val(disponible)
			cantidad	= disponible;
			
			notify('Solo existen '+disponible+' unidades en el inventario',500,5000,'error',30,5);
		}*/
		
		if(!revisarInventarioProducto(idProducto,disponible))
		{
			/*$('#txtCantidadProducto'+n).val(disponible)
			cantidad	= disponible;*/
			
			notify('Solo existen '+disponible+' unidades en el inventario',500,5000,'error',30,5);
			return false;
		}
		
		if(mayoreo==1)
		{
			cantidadMayoreo		= obtenerNumeros($('#txtCantidadMayoreo'+n).val());
			
			if(cantidadMayoreo>0)
			{
				if(cantidad>=cantidadMayoreo)
				{
					obtenerProductoId(n,'mayoreo')
					return true;
				}
				else
				{
					if(precio1==0)
					{
						obtenerProductoId(n,'menudeo')
						return true;
					}
				}
			}
		}
	}
	
	/*if(cursor==1)
	{
		setTimeout(function() 
		{
			$("#txtBuscarProductoCodigo").focus();
		}, 400);
	}*/
	
	
	tasa				= obtenerNumeros($('#txtTasaImpuesto'+n).val());
	precio				= obtenerNumeros($('#txtPrecioProducto'+n).val());
	descuentoPorcentaje	= obtenerNumeros($('#txtDescuentoPorcentaje'+n).val());
	
	//interes	= 10;
	
	//alert(interes);
	
	if(editar==1)
	{
		$('#txtPrecioOriginal'+n).val(precio);
	}
	
	if(interes>0)
	{
		precio			= obtenerNumeros($('#txtPrecioOriginal'+n).val());
		
		precio			= precio + (precio * (interes/100))
		
		precio			= obtenerNumeros(precio);
		
		$('#txtPrecioProducto'+n).val(redondear(precio))
	}
	else
	{
		precio				= obtenerNumeros($('#txtPrecioOriginal'+n).val());
		$('#txtPrecioProducto'+n).val(precio)
	}

	if(isNaN(precio) || !comprobarNumeros(precio))
	{
		precio =1;
		$('#txtPrecioProducto'+n).val(precio)
	}
	
	precio				= precio / (1+(tasa/100));
	
	
	
	if(isNaN(cantidad) || cantidad==0 || !comprobarNumeros(cantidad))
	{
		cantidad =1;
		$('#txtCantidadProducto'+n).val(cantidad)
	}
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje'+n).val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	importe				= precio*cantidad;
	descuento			= importe*descuentoPorcentaje;
	importe				-=descuento;
	impuestoProducto	= importe*(tasa/100);
	importe				+=impuestoProducto
	
	
	//alert(cantidad);
	
	//subTotal			= importe / (1+(tasa/100));
	//impuestoProducto	= importe-subTotal;
	
	$('#filaTotal'+n).html('$'+redondear(importe))
	$('#txtTotalProducto'+n).val(redondear(importe))
	$('#txtTotalImpuesto'+n).val(redondear(impuestoProducto))
	
	$('#lblDescuento'+n).html('Desc $'+redondear(descuento))
	$('#txtDescuentoProducto'+n).val(redondear(descuento))
	
	calcularSubTotal();
	
	return true;
	
}

subTotal	= 0; //El subtotal de la venta


function productoCodigo() 
{
	$("#cantidad1").val(1);
	agregarProductoVenta(1);
	obtenerProductosTienda();

	return;
}

subTotal	= 0;
totalesDescuentos	= 0;

function calcularSubTotal() //Calular el subtotal de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	vacio				= false; //El carrito esta vacio
	
	for(h=0;h<=fila;h++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+h).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+h).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+h).val());
		
		if(precio>0)
		{
			subTotal			+=precio+descuentoProducto;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
			
			vacio=true;
		}
	}
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	$('#txtDescuentoProducto0').val(descuento);
	$('#filaDescuento').html('DESC: $'+redondear(totalesDescuentos))
	
	$('#filaSubTotal').html('SUBTOTAL: $'+redondear(subTotal-totalImpuestos));
	$('#filaIva').html('IMPUESTOS: $'+redondear(totalImpuestos));
	//$('#filaTotal').html('TOTAL: $'+redondear(subTotal+totalImpuestos));
	$('#filaTotal').html('TOTAL: $'+redondear(subTotal-totalesDescuentos));
	$('#txtTotalPrevio').val(redondear(subTotal-totalesDescuentos))
	
	if(!vacio)
	{
		/*$('#carritoVacio').addClass('Error_validar');
		$('#carritoVacio').html('Carrito de ventas vacio');*/
	}
}

function calcularTotales() //Calular el total de la venta CONSIDERANDO LOS INTERES!!!!!!!!!!!!!!!!!
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	
	for(g=0;g<=fila;g++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+g).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+g).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+g).val());
		
		if(precio>0)
		{
			subTotal			+=precio+descuentoProducto;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
		}
	}

	$('#txtSubTotal').val(redondear(subTotal-totalImpuestos));
	
	subTotal			-=descuento;
	$('#txtDescuentoTotal').val(redondear(totalesDescuentos));

	total		= subTotal

	$('#txtTotal').val(redondear(subTotal-totalesDescuentos))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	$('#lblSubTotalVenta').html('$'+redondear(subTotal-totalImpuestos))
	$('#lblDescuentoVenta').html('$'+redondear(totalesDescuentos))
	$('#lblTotalVenta').html('$'+redondear(subTotal-totalesDescuentos))
	
	calcularCambio();
}

function calcularTotalesSinInteres() //Calular el total de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	
	for(v=0;v<=fila;v++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+v).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+v).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+v).val());
		
		if(precio>0)
		{
			subTotal			+=precio+descuentoProducto;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
		}
	}

	$('#txtSubTotal').val(redondear(subTotal-totalImpuestos));
	
	subTotal			-=descuento;
	$('#txtDescuentoTotal').val(redondear(totalesDescuentos));

	total		= subTotal

	$('#txtTotal').val(redondear(subTotal-totalesDescuentos))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	calcularCambio();
}


/*function calcularSubTotal() //Calular el subtotal de la venta
{
	subTotal			= 0;
	totalImpuestos		= 0;
	totalesDescuentos	= 0;
	vacio				= false; //El carrito esta vacio
	
	for(i=0;i<fila;i++)
	{
		precio				= obtenerNumeros($('#txtTotalProducto'+i).val());
		impuestoProducto	= obtenerNumeros($('#txtTotalImpuesto'+i).val());
		descuentoProducto	= obtenerNumeros($('#txtDescuentoProducto'+i).val());
		
		if(precio>0)
		{
			subTotal			+=precio;
			totalImpuestos		+=impuestoProducto;
			totalesDescuentos	+=descuentoProducto;
			
			vacio=true;
		}
	}
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje0').val());
	
	if(descuentoPorcentaje>99 || descuentoPorcentaje==0 || !comprobarNumeros(descuentoPorcentaje))
	{
		$('#txtDescuentoPorcentaje0').val(0)
	}
	
	descuentoPorcentaje	= descuentoPorcentaje>0?descuentoPorcentaje/100:0;
	descuento			= subTotal*descuentoPorcentaje;
	
	$('#txtDescuentoProducto0').val(descuento);
	$('#filaDescuento').html('DESC: $'+redondear(totalesDescuentos))
	
	$('#filaSubTotal').html('SUBTOTAL: $'+redondear(subTotal-totalImpuestos));
	$('#filaIva').html('IVA: $'+redondear(totalImpuestos));
	//$('#filaTotal').html('TOTAL: $'+redondear(subTotal+totalImpuestos));
	$('#filaTotal').html('TOTAL: $'+redondear(subTotal));
	
	if(!vacio)
	{
		$('#carritoVacio').addClass('Error_validar');
		$('#carritoVacio').html('Carrito de ventas vacio');
	}
}
*/
/*function calcularTotales() //Calular el total de la venta
{
	subTotal		= 0;
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
	
	//$('#txtSubTotal').val(redondear(subTotal));
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
	
	//iva			= parseFloat($('#selectIva').val())/100;
	//total		= subTotal+totalImpuestos
	total		= subTotal

	$('#txtTotal').val(redondear(total))
	$('#txtIvaTotal').val(redondear(totalImpuestos))
	$('#lblImporteIva').html('$'+redondear(totalImpuestos))
	
	calcularCambio();
}*/

function calcularCambio()
{
	total	=parseFloat($('#txtTotal').val());
	pago	=parseFloat($('#txtPago').val());
	
	if(isNaN(pago))
	{
		pago=0;
	}
	
	cambio	= pago-total;
	cambio	= redondear(cambio);
	
	if(cambio<0)
	{
		cambio="0.00";
	}
	
	$('#txtCambio').val(cambio)
	$('#lblCambioCliente').html('Cambio: $'+cambio)
}

$(document).ready(function()
{
	/*$('#ventanaCobrosVenta').keypress(function(e) 
	{
		if (e.keyCode == $.ui.keyCode.ENTER) 
		{
			registrarVenta()
		}
	});*/
	
	$("#ventanaCobrosVenta").dialog(
	{
		autoOpen:false,
		//show: { effect: "scale", duration: 600 },
		height:590,
		width:890,
		modal:true,
		resizable:false,
		buttons: 
		{
			"cancelar" : 
			{
				text: "Cancelar",
				id: "btnCancelar",
				click: function()
				{
					$(this).dialog('close');
				}   
		  	},
			
			/*"vista": 
			{
				text: "Vista previa",
				id: "btnVistaPrevia",
				click: function()
				{
					vistaPreviaVentaFactura()
				}   
		  	},*/
			/*,*/
			
			"Clientes": 
			{
				text: "Clientes",
				id: "btnClientes",
				click: function()
				{
					$('#ventanaBuscarClientes').dialog('open');
					obtenerClientesBusqueda();
				}   
		  	},
			"Factura": 
			{
				text: "Factura",
				id: "btnFactura",
				click: function()
				{
					/*if(tiendaLocal=='1')
					{
						notify('La facturación no esta disponible de forma local',500,5000,'error',30,5);
						return;	
					}*/
					
					formularioCfdiVenta()
				}   
		  	},
			"Aceptar": 
			{
				text: "Aceptar",
				id: "btnAceptar",
				click: function()
				{
					if($('#txtCambioActivo').val()=="0")
					{
						registrarVenta()
					}
					else
					{
						$('#ventanaCobrosVenta').dialog('close');
						//formularioVentas();
					}
				}   
		  	} 
		},
		close: function() 
		{
			//$('#ventanaCobrosVenta').dialog('option', 'title', 'La piñata');
			$('#ventanaCobrosVenta').dialog('option', 'title', 'Cobro de ventas');
			
			activarBotonesVenta();
			
			if($('#txtCambioActivo').val()=="0")
			{
				$("#formularioCobros").html('');
				
			}
			else
			{
				$('#ventanaCobrosVenta').dialog('close');
				formularioVentas();
			}
			
			calcularFilasIntereses(0);
		}
	});
});

function formularioCobros()
{
	limiteVentas	= obtenerNumeros($('#txtLimiteVentas').val());
	ventasF4		= obtenerNumeros($('#txtVentasF4').val());
	totalPrevio		= obtenerNumeros($('#txtTotalPrevio').val());
	
	if(subTotal==0)
	{
		notify('El subtotal de la venta es incorrecto',500,5000,'error',30,5);
		return;	
	}
	
	if(obtenerNumeros($("#txtPedidoActivo").val())==1)
	{
		/*if($('#selectDirecciones').val()=="0")
		{
			notify('Seleccione la dirección de entrega',500,5000,'error',30,5);
			return;	
		}*/
	}
	
	for(f=0;f<=fila;f++)
	{
		disponible			= obtenerNumeros($('#txtStockDisponible'+f).val());
		idProducto			= obtenerNumeros($('#txtIdProducto'+f).val());
		
		if(!revisarInventarioProducto(idProducto,disponible))
		{
			notify('Revise que las cantidades no superen el inventario disponible',500,5000,'error',30,5);
			return;
		}
	}
	
	
	
	/*if(limiteVentas<(ventasF4+totalPrevio))
	{
		notify('Esta superando el total de las ventas F4',500,5000,'error',30,5);
		return;	
	}*/
	
	$('#ventanaCobrosVenta').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioCobros').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cobros');
		},
		type:"POST",
		url:base_url+'clientes/formularioCobros',
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
			$('#formularioCobros').html(data);
			$('#selectFormas').focus();
			calcularTotales();
			obtenerFolio();
		},
		error:function(datos)
		{
			$('#formularioCobros').html('');
		}
	});		
}

function agregarProductoCodigo(Producto)//n es el numero de fila
{
	if(comprobarDuplicidad(Producto.idProducto))
	{
		setTimeout(function() 
		{
			$('#txtBuscarCodigo').val('');
			$('#txtBuscarCodigo').focus();
		}, 200);
		
		return;
	}
	
	/**/
	
	if(preciosActivo=='0' )
	{
		precio		= redondear(Producto.precioImpuestos);
		impuesto	= Producto.precioImpuestos-Producto.precioA
	
		total		=precio
		total		= redondear(total);
	}
	else
	{
		/*precio		= redondear(Producto.precioA);
		impuesto	= (Producto.tasa/100)*precio
	
		total		= parseFloat(Producto.precioA)+impuesto
		total		= redondear(total);*/
		
		precioCliente	= obtenerNumeros($('#txtPrecioCliente').val());
		precio			= redondear(Producto.precioA);

		switch(precioCliente)
		{
			case 2: precio			= redondear(Producto.precioB); break;
			case 3: precio			= redondear(Producto.precioC); break;
			case 4: precio			= redondear(Producto.precioD); break;
			case 5: precio			= redondear(Producto.precioE); break;
		}
		
		impuesto		= precio - precio / (1 + (Producto.tasa/100))
		total			= precio
	}

	producto='<tr id="filaProducto'+fila+'">';
	producto+='<td class="filaProducto" width="80%">';
	producto+='<label>'+Producto.nombre+'</label><br />';
	producto+='<label class="informacionUnidad" style="margin-left:0px">';
	
	if($('#txtClaveDescuento').val()!='')
	{
		producto+='<img src="'+base_url+'img/descuento.png" onclick="accesoAsignarDescuento('+fila+')" style="cursor:pointer" title="Asignar descuento" />';
	}
	
	producto+='<input type="text" maxlength="8" id="txtCantidadProducto'+fila+'" name="txtCantidadProducto'+fila+'" class="cajasCantidades" value="1" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" /> '+(Producto.unidad!=null?Producto.unidad:'')+'';
	
	if(sistemaActivo=='olyess')
	{
		producto+=total+'<input type="hidden" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+',1)" onkeypress="return soloDecimales(event)" maxlength="15"/>';	
	}
	else
	{
		producto+=''+'<input type="text" class="cajas" style="width:80px" id="txtPrecioProducto'+fila+'" 	name="txtPrecioProducto'+fila+'" 	value="'+total+'" placeholder="Precio" onchange="calcularFilaProducto('+fila+')" onkeypress="return soloDecimales(event)" maxlength="15"/>';
	}

	producto+='<br /> <label id="lblDescuento'+fila+'" style="font-size:13px; margin-left:81px">Desc $0.00</label>';
	producto+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="lblPedido" style="font-size:14px; color: red">'+'</label>';
	producto+='</td>';
	
	producto+='<td class="filaProducto" id="filaTotal'+fila+'" align="right">';
	producto+='$'+total;
	producto+='</td>';
	producto+='<td class="filaProducto">';
	producto+='<img src="'+base_url+'img/borrar.png" onclick="quitarProductoProducto('+fila+')" />';
	producto+='</td>';
	
	producto+='<input type="hidden" id="txtNombreProducto'+fila+'" 		name="txtNombreProducto'+fila+'" 		value="'+Producto.nombre+'" />';
	producto+='<input type="hidden" id="txtUnidadProducto'+fila+'" 		name="txtUnidadProducto'+fila+'" 		value="'+Producto.unidad+'" />';
	producto+='<input type="hidden" id="txtCodigoInterno'+fila+'" 		name="txtCodigoInterno'+fila+'" 		value="'+Producto.codigoInterno+'" />';
	producto+='<input type="hidden" id="txtTotalProducto'+fila+'" 		name="txtTotalProducto'+fila+'" 		value="'+total+'" />';
	producto+='<input type="hidden" id="txtIdProducto'+fila+'" 			name="txtIdProducto'+fila+'" 			value="'+Producto.idProducto+'" />';
	producto+='<input type="hidden" id="txtServicio'+fila+'" 			name="txtServicio'+fila+'" 				value="'+Producto.servicio+'" />';
	producto+='<input type="hidden" id="txtStockDisponible'+fila+'" 	name="txtStockDisponible'+fila+'"  		value="'+Producto.stock+'" />';
	producto+='<input type="hidden" id="txtDescuentoProducto'+fila+'" 	name="txtDescuentoProducto'+fila+'" 	value="0" />';
	producto+='<input type="hidden" id="txtDescuentoPorcentaje'+fila+'" name="txtDescuentoPorcentaje'+fila+'" 	value="0" class="descuentosProductos"/>';
	
	producto+='<input type="hidden" id="txtImpuesto'+fila+'" 			name="txtImpuesto'+fila+'" 				value="'+Producto.impuesto+'" />';
	producto+='<input type="hidden" id="txtTasaImpuesto'+fila+'" 		name="txtTasaImpuesto'+fila+'" 			value="'+Producto.tasa+'" />';
	producto+='<input type="hidden" id="txtTipoImpuesto'+fila+'" 		name="txtTipoImpuesto'+fila+'" 			value="'+Producto.tipoImpuesto+'" />';
	producto+='<input type="hidden" id="txtIdImpuesto'+fila+'" 			name="txtIdImpuesto'+fila+'" 			value="'+Producto.idImpuesto+'" />';
	producto+='<input type="hidden" id="txtTotalImpuesto'+fila+'" 		name="txtTotalImpuesto'+fila+'" 		value="'+impuesto+'" />';
	
	producto+='<input type="hidden" id="txtPrecioOriginal'+fila+'" 		 value="'+total+'" />';
	
	producto+='</tr>';
	
	//$('#tablaVentas').append(producto); 
	$("#tablaVentas tbody").prepend(producto);
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  

	fila++;
	
	$('#txtNumeroProductos').val(fila); 
	$('#carritoVacio').removeClass('Error_validar');
	$('#carritoVacio').html('');
	
	calcularSubTotal();
	
	setTimeout(function() 
	{
		if(sistemaActivo=='olyess')
		{
			if(obtenerNumeros($('#txtPedidoActivo').val())=='1')
			{
				$('.lblPedido').html('Pedido 1')
			}
		}
		
		$('#txtBuscarCodigo').val('');
		$('#txtBuscarCodigo').focus();
	}, 200);
}

function opcionesRutaEntrega() 
{
	mostrarRutas() 
	
	switch($("#selectMostrador option:selected").text())
	{
		case "Traspaso": 
			accesoRutaEntrega(1);
		break;
	}
}

function mostrarRutas() 
{
	$('#txtObservacionesEnvio').val('');
	
	$('#selectRutas').val('0');
	$('#selectDirecciones').val('0');
	
	if($('#selectMostrador').val()=="1")
	{
		$('#filaRutas').fadeIn();
	}
	else
	{
		$('#filaRutas').fadeOut();
	}
}

function opcionesVendedores() 
{
	switch($("#txtIdUsuarioVendedor").val())
	{
		case "0": 
		break;
			
		default: 
			accesoOpcionVendedor(1);
		break;
	}
}

function convertirPreFactura(idCotizacion)
{
	if(!confirm('¿Realmente desea convertir la prefactura a remisión?')) return;
	
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#procesandoVentas').html('<img src="'+ img_loader +'"/> Procesando el registro');
		},
		type:"POST",
		url:base_url+'ventas/convertirPreFactura',
		data:
		{
			idCotizacion: idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#procesandoVentas').html('');
			
			switch(data[0])
			{
				case "0":
						notify('Error en el registro',500,5000,'error',30,5);
					break;
				
				case "1":
						notify(data[1],500,5000,'',30,5);
						obtenerVentas()
					break;

			}
			
		},
		error:function(datos)
		{
			$('#procesandoVentas').html('');
			notify('Error en el registro',500,5000,'',30,5);
		}
	});		
}

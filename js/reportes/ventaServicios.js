//OBTENER VENTA POR SERVICIOS
$(document).ready(function ()
{
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerVentaServicios();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagVentaServicios > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentaServicios";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#FechaDia').val(),
				fin:			$('#FechaDia2').val(),
				criterio:		$('#txtCriterio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de venta de servicios...</label>');
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

function obtenerVentaServicios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentaServicios').html('<img src="'+ img_loader +'"/>Obteniendo detalles de venta de servicios...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerVentaServicios',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentaServicios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los venta de servicios',500,5000,'error',30,5);
			$("#obtenerVentaServicios").html('');
		}
	});
}

function excelVentaServicios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelVentaServicios',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/ventaServicios'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteVentaServicios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reporteVentaServicios',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ventaServicios'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function cancelarVentaServicios(idCotizacionPadre,idCotizacion,idProduct)
{
	if(!confirm('¿Realmente desea cancelar las ventas de servicios?')) return;	
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#cancelandoVentaServicios').html('<img src="'+ img_loader +'"/> Se esta cancelando la venta, por favor espere...');
		},
		type    : "POST",
		url     : base_url+"ventas/cancelarVentaServicios",
		data	: 
		{
			"idCotizacionPadre":	idCotizacionPadre,
			"idCotizacion":			idCotizacion,
			idProduct:				idProduct
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#cancelandoVentaServicios').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al cancelar las ventas',500,5000,'error',34,4);
				break;
						
				case "1":
					notify('Las ventas se han cancelado correctamente',500,5000,'',34,4);
					obtenerVentaServicios()
				break;
			}
		},
		error: function(datos)
		{
			$('#cancelandoVentaServicios').html('');
			notify('Error al cancelar la venta',500,5000,'error',34,4);
		}
	});
}

//EDITAR VENTA DE SERVICIOS
//OBTENER VENTA POR SERVICIOS
$(document).ready(function ()
{
	$("#ventanaEditarVentasServicios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			/*Cancelar: function() 
			{
				$(this).dialog('close');				 
			},*/
			'Aceptar': function() 
			{
				editarVentaServicios()		  	  
			},
			
		},
		close: function() 
		{
			$("#obtenerVentaServicioEditar").html('');
		}
	});
});

function editarVentaServicios()
{
	if(obtenerNumero($('#txtPrecioProducto').val())==0)
	{
		notify('El precio es incorrecto',500,5000,'error',34,4);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el precio del servicio?')) return;	
	
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#editandoVentaServicio').html('<img src="'+ img_loader +'"/> Se esta editando la venta, por favor espere...');
		},
		type    : "POST",
		url     : base_url+"ventas/editarVentaServicios",
		data	: $('#frmEditarVentaServicio').serialize(),
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#editandoVentaServicio').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al editar la venta',500,5000,'error',34,4);
				break;
						
				case "1":
					notify('El precio se ha editado correctamente',500,5000,'',34,4);
					obtenerVentaServicios()
					$("#ventanaEditarVentasServicios").dialog('close');
				break;
			}
		},
		error: function(datos)
		{
			$('#editandoVentaServicio').html('');
			notify('Error al cancelar la venta',500,5000,'error',34,4);
		}
	});
}

function obtenerVentaServicioEditar(idCotizacion)
{
	$("#ventanaEditarVentasServicios").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentaServicioEditar').html('<img src="'+ img_loader +'"/>Obteniendo detalles de venta de servicios...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerVentaServicioEditar',
		data:
		{
			idCotizacion:			idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentaServicioEditar').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los venta de servicios',500,5000,'error',30,5);
			$("#obtenerVentaServicioEditar").html('');
		}
	});
}


function obtenerImportesVentaServicio()
{
	//PRECIO DEL SERVICIO
	cantidad			= obtenerNumero($('#txtCantidad').val());
	precio				= obtenerNumero($('#txtPrecioProducto').val());
	
	importe				= cantidad*precio;
	
	descuentoPorcentaje	= obtenerNumero($('#txtDescuentoPorcentaje').val())/100;
	descuento			= importe*descuentoPorcentaje;
	
	$('#txtDescuento').val(redondear(descuento));
	$('#txtImporte').val(redondear(importe-descuento));
	
	$('#lblDescuentoProducto').html('$'+redondear(descuento));
	$('#lblImporte').html('$'+redondear(importe-descuento));
	
	
	
	//PRECIO DE LA VENTA
	subTotal			= importe-descuento;
	descuentoPorcentaje	= obtenerNumero($('#txtDescuentoPorcentajeVenta').val())/100;
	descuento			= precio*descuentoPorcentaje;
	diferencia			= subTotal-descuento;
	
	ivaPorcentaje		= obtenerNumero($('#txtIvaPorcentaje').val())/100;
	iva					= subTotal*ivaPorcentaje;
	
	$('#txtPrecioProducto').val(redondear(precio));
	$('#txtSubTotalVenta').val(redondear(subTotal));
	$('#txtDescuentoVenta').val(redondear(descuento));
	$('#txtIva').val(redondear(iva));
	$('#txtTotalVenta').val(redondear(diferencia+iva));
	
	$('#lblSubTotalVenta').html('$'+redondear(subTotal));
	$('#lblDescuentoVenta').html('$'+redondear(descuento));
	$('#lblIvaVenta').html('$'+redondear(iva));
	$('#lblTotalVenta').html('$'+redondear(diferencia+iva));
}

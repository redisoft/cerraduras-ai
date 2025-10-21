$(document).ready(function ()
{
	obtenerVentas()
	
	$("#txtBuscarCliente").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerVentas()
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagVentas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":		$('#FechaDia').val(),
				"fin":			$('#FechaDia2').val(),
				"criterio":		$('#txtBuscarCliente').val(),
				"idZona":		$('#selectZonas').val(),
				"idUsuario":	$('#selectAgentes').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentas').html('<img src="'+ img_loader +'"/>Obteniendo las ventas...');
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

function obtenerVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerVentas').html('<img src="'+ img_loader +'"/> Obteniendo ventas...');},
		type:"POST",
		url:base_url+'reportes/obtenerVentasReporte',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			"tipoVenta":	$('#selectTipoVenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentas').html(data);
		},
		error:function(datos)
		{
			$("#obtenerVentas").html('');
		}
	});//Ajax		
}


function excelVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentasReporte',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			"tipoVenta":	$('#selectTipoVenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarExcel/'+data;
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{

			$("#generandoExcel").html('');
		}
	});//Ajax		
}

function reporteVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentasReporte',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			"tipoVenta":	$('#selectTipoVenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReporteVentas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function imprimirTicketReporteVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/imprimirTicketReporteVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			"tipoVenta":	$('#selectTipoVenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			$('#ticketVentas').html(data);
			
			etiquetas 			= document.getElementById('ticketVentas');
			ventanaImprimir 	= window.open(' ', 'popimpr');
			
			ventanaImprimir.document.write( etiquetas.innerHTML );
			ventanaImprimir.document.close();
			ventanaImprimir.print( );
			ventanaImprimir.close();
			$('#ticketVentas').html('');
			
			//window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReporteVentas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function imprimirTicket()
{
	etiquetas 			= document.getElementById('ticketVentas');
	ventanaImprimir 	= window.open(' ', 'popimpr');
	
	ventanaImprimir.document.write( etiquetas.innerHTML );
	ventanaImprimir.document.close();
	ventanaImprimir.print( );
	ventanaImprimir.close();
	$('#ticketVentas').html('');
}

function obtenerTicket(idCotizacion,tipoVenta)
{
	url	= tipoVenta=='f3'?base_url+"clientes/imprimirTicket/"+idCotizacion:base_url+"clientes/imprimirTicketVenta/"+idCotizacion;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			//$('#procesandoRecepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> El sistema esta procesando las etiquetas');
		},
		type:"POST",
		url:url,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#procesandoRecepciones').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al imprimir el ticket',500,5000,'error',30,5);
				break;
				
				default:
				$('#ticketVentas').html(data);
				imprimirTicket();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al imprimir el tiqueta ',500,5000,'error',30,10);
		}
	});	
}


function actualizarLimiteVentas()
{
	$.ajax(
	{
		async:false,
		//beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'configuracion/actualizarLimiteVentas',
		data:
		{
			"limiteVentas":		$('#txtLimiteVentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('El registro se ha guardado correctamente',500,5000,'error',2,5);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al guardar el límite',500,5000,'error',2,5);
		}
	});		
}
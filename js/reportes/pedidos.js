$(document).ready(function ()
{
	obtenerPedidos()
	$('#txtFechaInicial,#txtFechaFinal').datepicker();
	
	$("#txtBuscarCliente").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerPedidos()
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagPedidos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPedidos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":		$('#txtFechaInicial').val(),
				"fin":			$('#txtFechaFinal').val(),
				"criterio":		$('#txtBuscarCliente').val(),
				"idZona":		$('#selectZonas').val(),
				"idUsuario":	$('#selectAgentes').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPedidos').html('<img src="'+ img_loader +'"/>Obteniendo los pedidos...');
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

function obtenerPedidos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerPedidos').html('<img src="'+ img_loader +'"/> Obteniendo pedidos...');},
		type:"POST",
		url:base_url+'reportes/obtenerPedidos',
		data:
		{
			"inicio":		$('#txtFechaInicial').val(),
			"fin":			$('#txtFechaFinal').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPedidos').html(data);
		},
		error:function(datos)
		{
			$("#obtenerPedidos").html('');
		}
	});//Ajax		
}


function excelPedidos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val()
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

function reportePedidos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val()
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


function cambiarEstado(idPedido,idEstado)
{
	if(!confirm('¿Realmente desea cambiar el estado del pedido?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Cambiando estado de pedido...');},
		type:"POST",
		url:base_url+'reportes/cambiarEstado',
		data:
		{
			"idPedido":		idPedido,
			"idEstado":		idEstado,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#generandoExcel").html('');
			obtenerPedidos();
			notify('El estado se ha cambiado correctamente',500,4000,"error",30,5);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
		}
	});//Ajax		
}

//REPARTIDORES
$(document).ready(function ()
{
	$("#ventanaRepartidores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				editarRepartidor()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioVentas").html('');
		}
	});
	
});

function formularioRepartidores(idCotizacion)
{
	$("#ventanaRepartidores").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioRepartidores').html('<img src="'+ img_loader +'"/> Obteniendo detalles de formulario...');},
		type:"POST",
		url:base_url+'reportes/formularioRepartidores',
		data:
		{
			"idCotizacion":		idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRepartidores').html(data);
		},
		error:function(datos)
		{
			$("#formularioRepartidores").html('');
		}
	});//Ajax		
}

function editarRepartidor()
{
	if(!confirm('¿Realmente desea cambiar el repartidor?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoRepartidor').html('<img src="'+ img_loader +'"/> Cambiando estado de pedido...');},
		type:"POST",
		url:base_url+'reportes/editarRepartidor',
		data:
		{
			"idPedido":		$('#txtIdPedido').val(),
			"idPersonal":	$('#selectRepartidores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoRepartidor").html('');
			obtenerPedidos();
			notify('El repartidor se ha cambiado correctamente',500,4000,"error",30,5);
			$("#ventanaRepartidores").dialog('close');
		},
		error:function(datos)
		{
			$("#editandoRepartidor").html('');
		}
	});//Ajax		
}
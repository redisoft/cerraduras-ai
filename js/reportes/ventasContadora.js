$(document).ready(function ()
{
	obtenerVentasContadora()
	
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
		var element 	= "#obtenerVentasContadora";
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

function obtenerVentasContadora()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerVentasContadora').html('<img src="'+ img_loader +'"/> Obteniendo ventas...');},
		type:"POST",
		url:base_url+'reportes/obtenerVentasContadora',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentasContadora').html(data);
		},
		error:function(datos)
		{
			$("#obtenerVentasContadora").html('');
		}
	});	
}

function excelVentasContadora()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentasContadora',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/VentasContadora';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{

			$("#generandoExcel").html('');
		}
	});	
}

function reporteVentasContadora()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentasContadora',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/VentasContadora'
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

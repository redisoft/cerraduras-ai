//PARA EL REPORTE DE INGRESOS
$(document).ready(function()
{
	obtenerReporte();
	
	$('#txtBuscarCliente').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
	$(document).on("click", ".ajax-pagReporte > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerIngresos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#FechaDia').val(),
				fin:			$('#FechaDia2').val(),
				criterio:		$('#txtBuscarCliente').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerIngresos').html('<img src="'+ img_loader +'"/>Obteniendo los ingresos, por favor tenga paciencia...');
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

function obtenerReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporte').html('<img src="'+ img_loader +'"/>Obteniendo los registros, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerPagosCredito',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporte').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',2,5);
			$("#obtenerReporte").html('');
		}
	});
}

function reportePdf()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reportePagosCredito',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/PagosCreditos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteExcel()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelPagosCredito',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			criterio:		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/PagosCreditos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

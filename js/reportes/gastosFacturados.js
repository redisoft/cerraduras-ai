//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE RelacionProveedores
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagGastosFacturados > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerGastosFacturados";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:			$('#txtMes').val(),
				idEmisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerGastosFacturados').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de gastos...');
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

function obtenerGastosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGastosFacturados').html('<img src="'+ img_loader +'"/>Obteniendo detalles de gastos...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerGastosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGastosFacturados').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los gastos',500,5000,'error',2,5);
			$("#obtenerGastosFacturados").html('');
		}
	});
}

function reporteGastosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteGastosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/GastosFacturados'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelGastosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelGastosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/GastosFacturados'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE IngresosFacturados
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagIngresosFacturados > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerIngresosFacturados";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:			$('#txtMes').val(),
				idCuenta:		$('#selectCuentas').val(),
				idEmisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerIngresosFacturados').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de retiros...');
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

function obtenerIngresosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngresosFacturados').html('<img src="'+ img_loader +'"/>Obteniendo detalles de ingresos facturados...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerIngresosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerIngresosFacturados').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los retiros',500,5000,'error',2,5);
			$("#obtenerIngresosFacturados").html('');
		}
	});
}

function reporteIngresosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteIngresosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/IngresosFacturados'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelIngresosFacturados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelIngresosFacturados',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/IngresosFacturados'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
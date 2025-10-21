//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE Retiros
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagRetiros > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRetiros";
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
				$('#obtenerRetiros').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de retiros...');
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

function obtenerRetiros()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRetiros').html('<img src="'+ img_loader +'"/>Obteniendo detalles de retiros...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerRetiros',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRetiros').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los retiros',500,5000,'error',2,5);
			$("#obtenerRetiros").html('');
		}
	});
}

function reporteRetiros()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteRetiros',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Retiros'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelRetiros()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelRetiros',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Retiros'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
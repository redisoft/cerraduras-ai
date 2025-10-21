//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE IngresosFacturados
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagRelacionClientes > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRelacionClientes";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				anio:			$('#selectAnio').val(),
				idEmisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerIngresosFacturados').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de relación de clientes...');
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

function obtenerRelacionClientes()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRelacionClientes').html('<img src="'+ img_loader +'"/>Obteniendo detalles de relación de clientes...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerRelacionClientes',
		data:
		{
			anio:			$('#selectAnio').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRelacionClientes').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la relación de clientes',500,5000,'error',2,5);
			$("#obtenerRelacionClientes").html('');
		}
	});
}

function reporteRelacionClientes()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteRelacionClientes',
		data:
		{
			anio:			$('#selectAnio').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/RelacionClientes'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelRelacionClientes()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelRelacionClientes',
		data:
		{
			anio:			$('#selectAnio').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/RelacionClientes'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
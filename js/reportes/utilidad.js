//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE UTILIDAD
//--------------------------------------------------------------------------------------

function obtenerUtilidad()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerUtilidad').html('<img src="'+ img_loader +'"/>Obteniendo detalles de utilidad...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerUtilidad',
		data:
		{
			fecha:			$('#txtFecha').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerUtilidad').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la relación',500,5000,'error',2,5);
			$("#obtenerUtilidad").html('');
		}
	});
}

function reporteUtilidad()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteUtilidad',
		data:
		{
			fecha:			$('#txtFecha').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/utilidad'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelUtilidad()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelUtilidad',
		data:
		{
			fecha:			$('#txtFecha').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Utilidad'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
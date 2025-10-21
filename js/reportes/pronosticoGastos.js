//PARA EL REPORTE DE GASTOS
function obtenerPronosticoGastos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPronosticoGastos').html('<img src="'+ img_loader +'"/>Obteniendo el pronóstico, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerPronosticoGastos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPronosticoGastos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el pronóstico',500,5000,'error',2,5);
			$("#obtenerPronosticoGastos").html('');
		}
	});
}

function excelPronosticoGastos(inicio,fin,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelPronosticoGastos/'+inicio+'/'+fin+'/'+idProveedor,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/pronosticoGastos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
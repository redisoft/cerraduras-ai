//PARA EL REPORTE DE PRONOSTICO DE COBROS
function obtenerPronosticoIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPronosticoIngresos').html('<img src="'+ img_loader +'"/>Obteniendo el pronóstico, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerPronosticoIngresos',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			idCuenta:	$('#selectCuentas').val(),
			idCliente:	$('#txtIdCliente').val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPronosticoIngresos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el pronóstico',500,5000,'error',2,5);
			$("#obtenerPronosticoIngresos").html('');
		}
	});
}

function excelPronosticoIngresos(inicio,fin,idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelPronosticoIngresos/'+inicio+'/'+fin+'/'+idCliente,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/pronosticoIngresos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
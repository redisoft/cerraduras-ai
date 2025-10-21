function excelVentasLineas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentasLineas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			idEstacion:  	$('#selectEstaciones').val(),
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
	});
}

function reporteVentasLineas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentasLineas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			idEstacion:  	$('#selectEstaciones').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/VentasLinea'
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}
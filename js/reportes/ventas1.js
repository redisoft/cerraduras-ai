
function generarExcelVentas(inicio,fin,idCliente,idZona,idUsuario)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentas/'+inicio+'/'+fin+'/'+idCliente+'/'+idZona+'/'+idUsuario,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarExcel/'+data;
			notify('El excel se ha creado correctamente',500,4000,"error");
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{

			$("#generandoExcel").html('');
		}
	});//Ajax		
}

function reporteVentas(inicio,fin,idCliente,idZona,idUsuario)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentas/'+inicio+'/'+fin+'/'+idCliente+'/'+idZona+'/'+idUsuario,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReporteVentas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}
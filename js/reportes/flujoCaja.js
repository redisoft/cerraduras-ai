//REPORTE FLUJO DE CAJA CHICA
function obtenerFlujoCaja()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFlujoCaja').html('<img src="'+ img_loader +'"/> Obteniendo el reporte de flujo de caja, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerFlujoCaja',
		data:
		{
			fecha:	$('#txtMesFlujo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFlujoCaja').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de flujo de caja',500,5000,'error',2,5);
			$('#obtenerFlujoCaja').html('');
		}
	});					  	  
}

//EXCEL FLUJO CAJA
function excelFlujoCaja(mes,anio)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelFlujoCaja/'+mes+'/'+anio,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteFlujoCaja'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

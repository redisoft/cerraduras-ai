//REPORTE FLUJO DE EFECTIVO
function obtenerFlujoEfectivo()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFlujoEfectivo').html('<img src="'+ img_loader +'"/> Obteniendo el reporte de flujo de efectivo, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerFlujoEfectivo',
		data:
		{
			fecha:	$('#txtMesFlujo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFlujoEfectivo').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de flujo de efectivo',500,5000,'error',2,5);
			$('#obtenerFlujoEfectivo').html('');
		}
	});					  	  
}

function excelFlujoEfectivo(mes,anio)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelFlujoEfectivo/'+mes+'/'+anio,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteFlujoEfectivo'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}
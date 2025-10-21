//OBTENER FACTURAS
function obtenerFacturas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFacturas').html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerFacturas',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCliente:		$('#txtBuscarCliente').val(),
			idFactura:		$('#txtBuscarFactura').val(),
			idEmisor:		$('#selectEmisoresBusqueda').val(),
			tipo:			$('#selectTipo').val(),
			canceladas:		$('#selectCanceladas').val(),
			idEstacion:  	$('#selectEstaciones').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFacturas').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las facturas',500,5000,'error',2,5);
			$("#obtenerFacturas").html('');
		}
	});
}

function excelFacturacion(mes,anio,idEmisor,tipo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelFacturacion/'+mes+'/'+anio+'/'+idEmisor+'/'+tipo,
		data:
		{
			canceladas:		$('#selectCanceladas').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idCliente:		$('#txtBuscarCliente').val(),
			idFactura:		$('#txtBuscarFactura').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteFacturacion'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function reporteFacturacion(mes,anio,idEmisor,tipo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteFacturacion/'+mes+'/'+anio+'/'+idEmisor+'/'+tipo+'/1',
		data:
		{
			canceladas:		$('#selectCanceladas').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idCliente:		$('#txtBuscarCliente').val(),
			idFactura:		$('#txtBuscarFactura').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReporteFacturacion'
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}
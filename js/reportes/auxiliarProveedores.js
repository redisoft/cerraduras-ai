//REPORTE AXULIAR PROVEEDORES
function obtenerAuxiliarProveedores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerAuxiliarProveedores').html('<img src="'+ img_loader +'"/> Obteniendo detalles de auxiliar, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerAuxiliarProveedores',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idProveedor:	$('#selectProveedores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerAuxiliarProveedores').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de auxiliar',500,5000,'error',20,5);
			$('#obtenerAuxiliarProveedores').html('');
		}
	});					  	  
}

function excelAuxiliarProveedores(inicio,fin,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelAuxiliarProveedores/'+inicio+'/'+fin+'/'+idProveedor,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteAuxiliarProveedores'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}
//OBTENER INVENTARIOS
$(document).ready(function ()
{
	$('#txtBuscarProducto').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerInventarios();
		}
	});
	
    //$('.ajax-pagInventarios > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagInventarios > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerInventarios";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarProducto').val(),
				idLinea:	$('#selectLineas').val(),
				idUnidad:	$('#selectUnidades').val(),
				idTienda:	$('#selectTiendas').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de inventario, por favor tenga paciencia...</label>');
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

function obtenerInventarios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerInventarios').html('<img src="'+ img_loader +'"/>Obteniendo detalles de inventario, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerInventarios',
		data:
		{
			criterio:	$('#txtBuscarProducto').val(),
			idLinea:	$('#selectLineas').val(),
			idUnidad:	$('#selectUnidades').val(),
			idTienda:	$('#selectTiendas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerInventarios').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del inventario',500,5000,'error',2,5);
			$("#obtenerInventarios").html('');
		}
	});
}

function excelInventarios(idProducto,idLinea)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelInventarios/'+idProducto+'/'+idLinea,
		data:
		{
			idTienda:	$('#selectTiendas').val(),
			idUnidad:	$('#selectUnidades').val(),
			criterio:	$('#txtBuscarProducto').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteInventarios'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function reporteInventarios(idProducto,idLinea)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteInventarios/'+idProducto+'/'+idLinea,
		data:
		{
			idTienda:	$('#selectTiendas').val(),
			idUnidad:	$('#selectUnidades').val(),
			criterio:	$('#txtBuscarProducto').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/reporteInventarios'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function reporteSalidasEntradas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporteSalidasEntradas').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteSalidasEntradas',
		data:
		{
			idProducto:		$('#txtIdProductoInventario').val(),
			idTienda:		$('#selectTiendas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/SalidasEntradas'
			$('#generandoReporteSalidasEntradas').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',30,5);
			$("#generandoReporteSalidasEntradas").html('');
		}
	});		
}

function excelSalidasEntradas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporteSalidasEntradas').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelSalidasEntradas',
		data:
		{
			idProducto:		$('#txtIdProductoInventario').val(),
			idTienda:		$('#selectTiendas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/SalidasEntradas'
			$('#generandoReporteSalidasEntradas').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporteSalidasEntradas").html('');
		}
	});		
}
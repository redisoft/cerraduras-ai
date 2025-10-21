$(document).ready(function()
{
	$(document).on("click", ".ajax-pagVentasProducto > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentasProducto";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio: 		$('#txtInicio').val(),
				fin: 			$('#txtFin').val(),
				idCliente:  	$('#selectClientesBusqueda').val(),
				idCotizacion:  	$('#selectVentasBusqueda').val(),
				idProducto:  	$('#selectProductosBusqueda').val(),
				ordenVentas:  	$('#txtOrdenVentas').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentasProducto').html('<img src="'+ img_loader +'"/>Obteniendo detalles de ventas');
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



function definirOrdenVentas(orden)
{
	$('#txtOrdenVentas').val(orden);
	obtenerVentasProducto();
}

function obtenerVentasProducto()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentasProducto').html('<img src="'+ img_loader +'"/>Obteniendo la lista de ventas...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerVentasProducto',
		data:
		{
			inicio: 		$('#txtInicio').val(),
			fin: 			$('#txtFin').val(),
			idCliente:  	$('#selectClientesBusqueda').val(),
			idCotizacion:  	$('#selectVentasBusqueda').val(),
			idProducto:  	$('#selectProductosBusqueda').val(),
			ordenVentas:  	$('#txtOrdenVentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentasProducto').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las ventas',500,5000,'error',30,5);
			$("#obtenerVentasProducto").html('');
		}
	});
}

function reporteVentasProducto()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteVentasProducto',
		data:
		{
			inicio: 		$('#txtInicio').val(),
			fin: 			$('#txtFin').val(),
			idCliente:  	$('#selectClientesBusqueda').val(),
			idCotizacion:  	$('#selectVentasBusqueda').val(),
			idProducto:  	$('#selectProductosBusqueda').val(),
			ordenVentas:  	$('#txtOrdenVentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/VentasProducto'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelVentasProducto()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelVentasProducto',
		data:
		{
			inicio: 		$('#txtInicio').val(),
			fin: 			$('#txtFin').val(),
			idCliente:  	$('#selectClientesBusqueda').val(),
			idCotizacion:  	$('#selectVentasBusqueda').val(),
			idProducto:  	$('#selectProductosBusqueda').val(),
			ordenVentas:  	$('#txtOrdenVentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/VentasProducto'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
//PARA EL REPORTE DE GASTOS
$(document).ready(function()
{
	$(document).on("click", ".ajax-pagGastos > li a", function(eve)	
	{
		eve.preventDefault();
		var element 	= "#obtenerGastos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#FechaDia').val(),
				fin:			$('#FechaDia2').val(),
				idCuenta:		$('#selectCuentas').val(),
				idDepartamento:	$('#selectDepartamentos').val(),
				idProducto:		$('#selectProductos').val(),
				idGasto:		$('#selectGastos').val(),
				idProveedor:	$('#txtIdProveedor').val(),
				criterio:		$('#selectCriterio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerGastos').html('<img src="'+ img_loader +'"/>Obteniendo los gastos, por favor tenga paciencia...');
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

function obtenerGastos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGastos').html('<img src="'+ img_loader +'"/>Obteniendo los gastos, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerGastos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idCuenta:		$('#selectCuentas').val(),
			idDepartamento:	$('#selectDepartamentos').val(),
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectGastos').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			criterio:		$('#selectCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGastos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los gastos',500,5000,'error',2,5);
			$("#obtenerGastos").html('');
		}
	});
}

function excelGastos(inicio,fin,idCuenta,idDepartamento,idProducto,idGasto,idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelGastos/'+inicio+'/'+fin+'/'+idCuenta+'/'+idDepartamento+'/'+idProducto+'/'+idGasto+'/'+idCliente,
		data:
		{
			criterio:		$('#selectCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteGastos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
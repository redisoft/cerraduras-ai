//PARA EL REPORTE DE INGRESOS
$(document).ready(function()
{
	$('#txtBuscarCliente').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerIngresos();
		}
	});
	
	$(document).on("click", ".ajax-pagIngresos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerIngresos";
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
				cliente:		$('#txtBuscarCliente').val(),
				idIngreso:		$('#txtIdIngreso').val(),
				criterio:		$('#selectCriterio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerIngresos').html('<img src="'+ img_loader +'"/>Obteniendo los ingresos, por favor tenga paciencia...');
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

function obtenerIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngresos').html('<img src="'+ img_loader +'"/>Obteniendo los ingresos, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerIngresos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idCuenta:		$('#selectCuentas').val(),
			idDepartamento:	$('#selectDepartamentos').val(),
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectGastos').val(),
			cliente:		$('#txtBuscarCliente').val(),
			idIngreso:		$('#txtIdIngreso').val(),
			criterio:		$('#selectCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerIngresos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los ingresos',500,5000,'error',2,5);
			$("#obtenerIngresos").html('');
		}
	});
}

function reporteIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reporteIngresos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idCuenta:		$('#selectCuentas').val(),
			idDepartamento:	$('#selectDepartamentos').val(),
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectGastos').val(),
			cliente:		$('#txtBuscarCliente').val(),
			idIngreso:		$('#txtIdIngreso').val(),
			criterio:		$('#selectCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Ingresos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelIngresos(inicio,fin,idCuenta,idDepartamento,idProducto,idGasto,idIngreso)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelIngresos/'+inicio+'/'+fin+'/'+idCuenta+'/'+idDepartamento+'/'+idProducto+'/'+idGasto+'/'+idIngreso,
		data:
		{
			criterio:		$('#selectCriterio').val(),
			cliente:		$('#txtBuscarCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteIngresos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

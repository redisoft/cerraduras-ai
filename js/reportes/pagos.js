//PARA EL REPORTE DE PAGOS

$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagPagos > li a", function(eve)	
	{
		eve.preventDefault();
		var element 	= "#obtenerPagos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#FechaDia').val(),
				fin:			$('#FechaDia2').val(),
				idProveedor:	$('#txtIdProveedor').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPagos').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de pagos, por favor tenga paciencia...');
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

function obtenerPagos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPagos').html('<img src="'+ img_loader +'"/>Obteniendo los pagos, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerPagos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idProveedor:	$('#txtIdProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPagos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los pagos',500,5000,'error',2,5);
			$("#obtenerPagos").html('');
		}
	});
}

function excelPagos(inicio,fin,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelPagos/'+inicio+'/'+fin+'/'+idProveedor,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reportePagos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
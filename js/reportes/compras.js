//PARA EL REPORTE DE COMPRAS
$(document).ready(function ()
{
    //$('.ajax-pagCompras > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagCompras > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerCompras";
		var link = $(this).attr('href');
		
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
				$('#obtenerCompras').html('<img src="'+ img_loader +'"/>Obteniendo las compras, por favor tenga paciencia...');
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

function obtenerCompras()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCompras').html('<img src="'+ img_loader +'"/>Obteniendo las compras, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerCompras',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idProveedor:	$('#txtIdProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCompras').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las compras',500,5000,'error',2,5);
			$("#obtenerCompras").html('');
		}
	});
}

function excelCompras(inicio,fin,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelCompras/'+inicio+'/'+fin+'/'+idProveedor,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteCompras'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteCompras(inicio,fin,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteCompras/'+inicio+'/'+fin+'/'+idProveedor,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Compras'
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}
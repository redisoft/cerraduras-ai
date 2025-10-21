//OBTENER AUTORIZACIONES
$(document).ready(function ()
{
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerChecador();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagChecador > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerChecador";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:		$('#FechaDia').val(),
				fin:		$('#FechaDia2').val(),
				criterio:	$('#txtCriterio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de checador...</label>');
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

function obtenerChecador()
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
			$('#obtenerChecador').html('<img src="'+ img_loader +'"/>Obteniendo detalles de checador...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerChecador',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerChecador').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles',500,5000,'error',30,5);
			$("#obtenerChecador").html('');
		}
	});
}

function excelChecador()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelChecador',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Checador'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteChecador()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reporteChecador',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Checador'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

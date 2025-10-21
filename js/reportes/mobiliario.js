//OBTENER MOBILIARIO

$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagMobiliario > li a", function(eve)	
	{
		eve.preventDefault();
		var element 		= "#obtenerMobiliario";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idInventario:	$('#txtIdInventario').val(),
				idProveedor:	$('#txtIdProveedor').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de mobiliario, por favor tenga paciencia...</label>');
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

function obtenerMobiliario()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerMobiliario').html('<img src="'+ img_loader +'"/>Obteniendo detalles de mobiliario, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerMobiliario',
		data:
		{
			idInventario:	$('#txtIdInventario').val(),
			idProveedor:	$('#txtIdProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMobiliario').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del mobiliario',500,5000,'error',2,5);
			$("#obtenerMobiliario").html('');
		}
	});
}

function excelMobiliario(idInventario,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelMobiliario/'+idInventario+'/'+idProveedor,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteMobiliario'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function reporteMobiliario(idInventario,idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteMobiliario/'+idInventario+'/'+idProveedor,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/reporteMobiliario'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}
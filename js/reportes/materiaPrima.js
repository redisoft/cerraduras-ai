//OBTENER INVENTARIOS DE MATERIA PRIMA
$(document).ready(function ()
{
	obtenerMateriaPrima()
	
	$('#txtIdProducto').val(0);
	$('#txtBuscarProducto').val('');
	
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerMateriaPrima();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagMateriaPrima > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerMateriaPrima";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtCriterio').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de materia prima, por favor tenga paciencia...</label>');
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

function obtenerMateriaPrima()
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
			$('#obtenerMateriaPrima').html('<img src="'+ img_loader +'"/>Obteniendo detalles de inventario, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerMateriaPrima',
		data:
		{
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMateriaPrima').html(data);
		},
		error:function(datos)
		{
			//notify('Error al obtener los detalles del inventario',500,5000,'error',2,5);
			$("#obtenerMateriaPrima").html('');
		}
	});
}

function excelMateriaPrima()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelMateriaPrima',
		data:
		{
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/inventarioMateriaPrima'
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function reporteMateriaPrima()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteMateriaPrima',
		data:
		{
			criterio:		$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/inventarioMateriaPrima'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

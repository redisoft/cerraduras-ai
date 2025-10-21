//OBTENER FACTURAS
$(document).ready(function ()
{
    //$('.ajax-pagFacturacionSat > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagFacturacionSat > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerFacturas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:		$('#txtMes').val(),
				criterio:	$('#txtCriterio').val(),
				recibida:	$('#selectRecibidas').val(),
				emisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
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
	
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerFacturas();
		}, milisegundos);
	});
});

function obtenerFacturas()
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
			$('#obtenerFacturas').html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerFacturasSat',
		data:
		{
			fecha:		$('#txtMes').val(),
			criterio:	$('#txtCriterio').val(),
			recibida:	$('#selectRecibidas').val(),
			emisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFacturas').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las facturas',500,5000,'error',2,5);
			$("#obtenerFacturas").html('');
		}
	});
}

function excelFacturacion(mes,anio,idCliente,idEmisor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelFacturacion/'+mes+'/'+anio+'/'+idCliente+'/'+idEmisor,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteFacturacion'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function zipearFacturasSat()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> El sistema esta zipeando las facturas...');},
		type:"POST",
		url:base_url+'reportes/zipearFacturasSat/',
		data:
		{
			fecha:		$('#txtMes').val(),
			criterio:	$('#txtCriterio').val(),
			recibida:	$('#selectRecibidas').val(),
			emisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargaZipSat/'+data
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al zipear las facturas',500,5000,'error',2,5);
		}
	});//Ajax		
}

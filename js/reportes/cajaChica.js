//CAJA CHICA
function obtenerCajaChica()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCajaChica').html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo detalles de caja chica...</label>');
		},
		type:"POST",
		url:base_url+'reportes/obtenerCajaChica',
		data:
		{
			'fecha': 	$('#txtMes').val(),
			'criterio': $('#txtBusquedaCaja').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCajaChica').html(data);
		},
		error:function(datos)
		{
			$('#obtenerCajaChica').html('<div class="erroresGeneral">Error al obtener los detalles de caja chica, verifique su conexión a internet</div>')
		}
	});
}

function excelCajaChica(mes,anio)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelCajaChica/'+mes+'/'+anio,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteCajaChica'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}



$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagCaja > li a", function(eve)	
	{
		eve.preventDefault();
		var element = "#obtenerCajaChica";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				'fecha': 	$('#txtMes').val(),
				'criterio': $('#txtBusquedaCaja').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo detalles de caja chica, por favor tenga paciencia...</label>');
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
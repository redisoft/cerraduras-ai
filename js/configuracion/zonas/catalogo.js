//CATÁLOGO DE ZONAS
$(document).ready(function()
{
	$("#ventanaCatalogoZonas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:840,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			}
		},
		close: function()
		{
			$("#obtenerCatalogoZonas").html('');
			obtenerRegistrosZona()
		}
	});
})

function obtenerCatalogoZonas()
{
	$('#ventanaCatalogoZonas').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoZonas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoZonas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoZonas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoZonas').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerRegistrosZona()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRegistrosZona').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerRegistrosZona',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRegistrosZona').html(data)
		},
		error:function(datos)
		{
			$('#obtenerRegistrosZona').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}


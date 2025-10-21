//CATÁLOGO DE ZONAS
$(document).ready(function()
{
	$("#ventanaCatalogoMotivos").dialog(
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
			$("#obtenerCatalogoMotivos").html('');
			obtenerRegistrosMotivos()
		}
	});
})

function obtenerCatalogoMotivos()
{
	$('#ventanaCatalogoMotivos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoMotivos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoMotivos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoMotivos').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerRegistrosMotivos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRegistrosMotivos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerRegistrosMotivos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRegistrosMotivos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerRegistrosMotivos').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}


//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoPeriodos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:900,
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
			$("#catalogoPeriodos").html('');
		}
	});
})

function obtenerCatalogoPeriodos()
{
	$('#ventanaCatalogoPeriodos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoPeriodos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoPeriodos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#obtenerCatalogoPeriodos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoPeriodos').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerCatalogosRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCampanasRegistro').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCampanasRegistro',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#obtenerCampanasRegistro').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCampanasRegistro').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

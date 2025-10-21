//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoCampanas").dialog(
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
			if($('#txtCampanasEditado').val()=='1')
			{
				//obtenerCampanasRegistro()
			}
			
			$("#obtenerCatalogoCampanas").html('');
		}
	});
})

function obtenerCatalogoCampanas()
{
	$('#ventanaCatalogoCampanas').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoCampanas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoCampanas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#obtenerCatalogoCampanas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoCampanas').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerCampanasRegistro()
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

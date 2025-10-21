//CATÁLOGO DE MATERIALES
$(document).ready(function()
{
	$("#ventanaCatalogoMateriales").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1100,
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
			$("#obtenerCatalogoMateriales").html('');
		}
	});
})

function obtenerCatalogoMateriales()
{
	$('#ventanaCatalogoMateriales').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoMateriales').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'materiales/obtenerCatalogoMateriales',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoMateriales').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoMateriales').html('');
			notify('Error al obtener el catálogo de materiales',500,5000,'error',30,5);
		}
	});
}

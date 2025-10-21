//CATÁLOGO DE MATERIALES
$(document).ready(function()
{
	$("#ventanaCatalogoProductos").dialog(
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
			$("#obtenerCatalogoProductos").html('');
		}
	});
})

function obtenerCatalogoProductos()
{
	$('#ventanaCatalogoProductos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoProductos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/obtenerCatalogoProductos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoProductos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoProductos').html('');
			notify('Error al obtener el catálogo de productos',500,5000,'error',30,5);
		}
	});
}

//CATÁLOGO DE MATERIALES
$(document).ready(function()
{
	$("#ventanaCatalogoInventarios").dialog(
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
			$("#obtenerCatalogoInventarios").html('');
		}
	});
})

function obtenerCatalogoInventarios()
{
	$('#ventanaCatalogoInventarios').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoInventarios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/obtenerCatalogoInventarios',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoInventarios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoInventarios').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

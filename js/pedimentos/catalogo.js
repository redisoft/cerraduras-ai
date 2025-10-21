//CATÁLOGO DE PEDIMENTOS
$(document).ready(function()
{
	$("#ventanaCatalogoPedimentos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:950,
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
			$("#obtenerCatalogoPedimentos").html('');
		}
	});
})

function obtenerCatalogoPedimentos()
{
	$('#ventanaCatalogoPedimentos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoPedimentos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'pedimentos/obtenerCatalogoPedimentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#obtenerCatalogoPedimentos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoPedimentos').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}


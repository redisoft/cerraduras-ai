//CATÁLOGO DE MATERIALES
$(document).ready(function()
{
	$("#ventanaCatalogoServicios").dialog(
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
			$("#obtenerCatalogoServicios").html('');
		}
	});
})

function obtenerCatalogoServicios()
{
	$('#ventanaCatalogoServicios').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoServicios').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'servicios/obtenerCatalogoServicios',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoServicios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoServicios').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

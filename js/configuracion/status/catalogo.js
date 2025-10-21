//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoStatus").dialog(
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
			$("#obtenerCatalogoStatus").html('');
			obtenerStatusCrm()
		}
	});
})

function obtenerCatalogoStatus()
{
	$('#ventanaCatalogoStatus').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoStatus').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoStatus',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoStatus').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoStatus').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerStatusCrm()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerStatusCrm').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerStatusCrm',
		data:
		{
			cliente:	$('#txtTipoStatusCrm').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerStatusCrm').html(data)
		},
		error:function(datos)
		{
			$('#obtenerServiciosCrm').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

//CATÁLOGO DE SERVICIOS
$(document).ready(function()
{
	$("#ventanaCatalogoServicios").dialog(
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
			$("#obtenerCatalogoServicios").html('');
			obtenerServiciosCrm()
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
		url:base_url+'configuracion/obtenerCatalogoServicios',
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

function obtenerServiciosCrm()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServiciosCrm').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerServiciosCrm',
		data:
		{
			cliente:	$('#txtTipoServicioCrm').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerServiciosCrm').html(data);
			opcionesServicios()
		},
		error:function(datos)
		{
			$('#obtenerServiciosCrm').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

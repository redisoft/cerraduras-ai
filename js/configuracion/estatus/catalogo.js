//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoEstatus").dialog(
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
			$("#obtenerCatalogoEstatus").html('');
			obtenerEstatusCrm()
		}
	});
})

function obtenerCatalogoEstatus()
{
	$('#ventanaCatalogoEstatus').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoEstatus').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoEstatus',
		data:
		{
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$('#obtenerCatalogoEstatus').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoEstatus').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerEstatusCrm()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEstatusCrm').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerEstatusCrm',
		data:
		{
			tipo: $('#txtTipoRegistro').val()=='prospectos'?'1':'0'
		},
		datatype:"html",
		success:function(data, textEstatus)
		{
			$('#obtenerEstatusCrm').html(data)
		},
		error:function(datos)
		{
			$('#obtenerServiciosCrm').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

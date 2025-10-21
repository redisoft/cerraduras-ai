//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoPromotores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:620,
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
			/*if($('#txtPromotoresEditado').val()=='1')
			{
				obtenerPromotoresRegistro()
			}*/
			
			$("#obtenerCatalogoPromotores").html('');
		}
	});
})

function obtenerCatalogoPromotores()
{
	$('#ventanaCatalogoPromotores').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoPromotores').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoPromotores',
		data:
		{
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$('#obtenerCatalogoPromotores').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoPromotores').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerPromotoresRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPromotoresRegistro').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPromotoresRegistro',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textPromotores)
		{
			$('#obtenerPromotoresRegistro').html(data)
		},
		error:function(datos)
		{
			$('#obtenerPromotoresRegistro').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

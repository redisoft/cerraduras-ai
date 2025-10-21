//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoComisiones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1200,
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
			if($('#txtComisionesEditado').val()=='1')
			{
				obtenerComisionesRegistro()
			}
			
			$("#obtenerCatalogoComisiones").html('');
		}
	});
})

function obtenerCatalogoComisiones()
{
	$('#ventanaCatalogoComisiones').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoComisiones').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoComisiones',
		data:
		{
		},
		datatype:"html",
		success:function(data, textComisiones)
		{
			$('#obtenerCatalogoComisiones').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoComisiones').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerComisionesRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComisionesRegistro').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerComisionesRegistro',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textComisiones)
		{
			$('#obtenerComisionesRegistro').html(data)
		},
		error:function(datos)
		{
			$('#obtenerComisionesRegistro').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

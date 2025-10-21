//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoCausas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:850,
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
			/*if($('#txtCausasEditado').val()=='1')
			{
				
			}*/
			
			obtenerCausasRegistro()
			$("#obtenerCatalogoCausas").html('');
		}
	});
})

function obtenerCatalogoCausas()
{
	$('#ventanaCatalogoCausas').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoCausas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoCausas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#obtenerCatalogoCausas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoCausas').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerCausasRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCausasRegistro').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCausasRegistro',
		data:
		{
			"tipo":			$("#txtTipoBajas").val()
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#obtenerCausasRegistro').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCausasRegistro').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

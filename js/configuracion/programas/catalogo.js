//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoProgramas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1000,
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
			if($('#txtProgramasEditado').val()=='1')
			{
				obtenerProgramasRegistro()
			}
			
			$("#obtenerCatalogoProgramas").html('');
		}
	});
})

function obtenerCatalogoProgramas()
{
	$('#ventanaCatalogoProgramas').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoProgramas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCatalogoProgramas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#obtenerCatalogoProgramas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoProgramas').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

function obtenerProgramasRegistro()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProgramasRegistro').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProgramasRegistro',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#obtenerProgramasRegistro').html(data)
		},
		error:function(datos)
		{
			$('#obtenerProgramasRegistro').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

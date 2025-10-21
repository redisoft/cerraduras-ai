//CATÁLOGO DE STATUS
$(document).ready(function()
{
	$("#ventanaCatalogoDirecciones").dialog(
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
			$("#obtenerCatalogoDirecciones").html('');
		}
	});
})

function obtenerCatalogoDirecciones(idCliente)
{
	$('#ventanaCatalogoDirecciones').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoDirecciones').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerCatalogoDirecciones',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$('#obtenerCatalogoDirecciones').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoDirecciones').html('');
			notify('Error al obtener el catálogo',500,5000,'error',30,5);
		}
	});
}

/*function agregarDireccionProducto(idDireccion,nombre,color)
{
	$('#lblDireccion'+$('#txtIDirecciones').val()).html(nombre);
	$('#txtIdDireccion'+$('#txtIDirecciones').val()).val(idDireccion);

	document.getElementById('colorDireccion'+$('#txtIDirecciones').val()).style.background=color;
	
	$('#ventanaCatalogoDirecciones').dialog('close');
	
}*/

$(document).ready(function()
{
	$("#ventanaImportarProductos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#formularioImportarProductos").html('');
		}
	});
});

function formularioImportarProductos()
{
	$('#ventanaImportarProductos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarProductos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarProductos",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarProductos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContacto").html('');	
		}
	});
}

function exportarProductos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'importar/exportarProductos',
		data:
		{
			criterio:		$('#txtBuscarProductoInventario').val(),
			orden:			$('#txtOrdenProductos').val(),
			minimo:			$('#selectStockBusqueda').val(),
			codigoInterno:	$('#txtBuscarProductoCodigo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Productos'
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

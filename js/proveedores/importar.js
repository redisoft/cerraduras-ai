$(document).ready(function()
{
	$("#ventanaImportarProveedores").dialog(
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
			$("#formularioImportarProveedores").html('');
		}
	});
});

function formularioImportarProveedores()
{
	$('#ventanaImportarProveedores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarProveedores').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarProveedores",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarProveedores').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContacto").html('');	
		}
	});
}

function exportarProveedores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'importar/exportarProveedores',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Proveedores'
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function excelEgresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoEgresos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'administracion/excelEgresos',
		data:
		{
			criterio:	$('#txtBuscarEgreso').val(),
			inicio:		$('#txtInicioEgresoFecha').val(),
			fin:		$('#txtFinEgresoFecha').val(),
			idCuenta:	$('#selectCuentaEgresos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoEgresos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Egresos'
		},
		error:function(datos)
		{
			$("#cargandoEgresos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',30,5);
		}
	});//Ajax		
}

$(document).ready(function()
{
	$("#ventanaImportarEgresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cerrar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#formularioImportarEgresos").html('');
		}
	});
});



function formularioImportarEgresos()
{
	$('#ventanaImportarEgresos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioImportarEgresos').html('<img src="'+ img_loader +'"/> Preparando el formulario...');},
		type:"POST",
		url:base_url+'importar/formularioImportarEgresos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarEgresos').html(data);
		},
		error:function(datos)
		{
			$("#formularioImportarEgresos").html('');
			notify('Error al preparar el formulario',500,5000,'error',30,5);
		}
	});//Ajax		
}


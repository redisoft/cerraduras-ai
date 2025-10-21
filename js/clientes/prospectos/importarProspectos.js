$(document).ready(function()
{
	$("#ventanaImportarProspectos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
			$("#formularioImportarProspectos").html('');
		}
	});
});

function formularioImportarProspectos()
{
	$('#ventanaImportarProspectos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarProspectos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarProspectosRegistro",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarProspectos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#formularioImportarProspectos").html('');	
		}
	});
}


$(document).ready(function()
{
	$("#ventanaImportarChecador").dialog(
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
			$("#formularioImportarClientes").html('');
		}
	});
});

function formularioImportarChecador()
{
	$('#ventanaImportarChecador').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarChecador').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarChecador",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarChecador').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#formularioImportarChecador").html('');	
		}
	});
}

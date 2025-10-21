$(document).ready(function()
{
	$("#ventanaImportarComparar").dialog(
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
			$("#formularioImportarComparar").html('');
		}
	});
});

function formularioImportarComparar()
{
	$('#ventanaImportarComparar').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarComparar').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarComparar",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarComparar').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#formularioImportarComparar").html('');	
		}
	});
}


$(document).ready(function()
{
	$("#ventanaClientesComparar").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1100,
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
			$("#obtenerComparados").html('');
		}
	});
});

function obtenerComparados()
{
	$('#ventanaClientesComparar').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComparados').html('<img src="'+ img_loader +'"/> Obteniendo detalles de prospectos...');
		},
		type:"POST",
		url:base_url+"importar/obtenerComparados",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComparados').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#obtenerComparados").html('');	
		}
	});
}

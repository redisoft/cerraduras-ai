
$(document).ready(function()
{
	$("#ventanaImportarFacebook").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:320,
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
			$("#formularioImportarFacebook").html('');
		}
	});
});

function formularioImportarFacebook()
{
	$('#ventanaImportarFacebook').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarFacebook').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarFacebook",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarFacebook').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#formularioImportarFacebook").html('');	
		}
	});
}

function importarArchivosFacebook(Archivos,indi)
{
	/*mensaje	= '';
	for(i=0;i<indi;i++)
	{
		mensaje	+= Archivos[i]+'\n';
	}
	
	alert(mensaje);*/
	
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#importandoFacebook').html('<img src="'+ img_loader +'"/> Procesando los archivos cargados...');
		},
		type:"POST",
		url:base_url+"importar/importarArchivosFacebook",
		data:
		{
			archivos:		Archivos,
			indice:			indi,
			idCampana:		$('#selectCampana').val(),
			fecha:			$('#txtFechaPrimerSeguimiento').val(),
			hora:			$('#txtHoraPrimerSeguimiento').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#importandoFacebook').html('');
			notify('El proceso fue existoso',500,5000,'',30,5);
			
			archivos	= new Array();
			indi		= 0;
			
			comprobarRepetidos()
			
			$('#ventanaImportarFacebook').dialog('close');
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información',500,5000,'error',5,5);
			$("#formularioImportarFacebook").html('');	
		}
	});
}


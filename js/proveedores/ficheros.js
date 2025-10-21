//==================================================================================================//
//===================================         FICHEROS         ====================================//
//==================================================================================================//

$(document).ready(function()
{
	$("#ventanaFicheros").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function()
		{
			$("#obtenerFicheros").html('');
		}
	});
});

function obtenerFicheros(idProveedor)
{
	$('#ventanaFicheros').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFicheros').html('<img src="'+ img_loader +'"/> Obteniendo archivos, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerFicheros',
		data:
		{
			"idProveedor":idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFicheros').html(data);
		},
		error:function(datos)
		{
			$('#obtenerFicheros').html('');
			notify('Error al obtener los ficheros',500,5000,'error',0,0);
		}
	});		
}

function borrarFichero(idFichero)
{
	if(!confirm('¿Realmente desea borrar el archivo?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoFicheros').html('<img src="'+ img_loader +'"/> Borrando archivo, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/borrarFichero',
		data:
		{
			"idFichero":idFichero,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#registrandoFicheros').html('')
				notify('¡Error al borrar el archivo!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El archivo se ha borrado correctamente!',500,5000,'',0,0);
				$('#registrandoFicheros').html('');
				obtenerFicheros($('#txtIdProveedor').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoFicheros').html('');
			notify('¡Error al borrar el fichero!',500,5000,'error',0,0);
		}
	});		
}
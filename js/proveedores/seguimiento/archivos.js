//FICHEROS DE SEGUIMIENTO PROVEEDORES
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
$(document).ready(function()
{
	$("#ventanaArchivosSeguimiento").dialog(
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
			$("#obtenerArchivosSeguimiento").html('');
		}
	});
});

function obtenerArchivosSeguimiento(idSeguimiento)
{
	$('#ventanaArchivosSeguimiento').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerArchivosSeguimiento').html('<img src="'+ img_loader +'"/> Obteniendo archivos, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerArchivosSeguimiento',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerArchivosSeguimiento').html(data);
		},
		error:function(datos)
		{
			$('#obtenerArchivosSeguimiento').html('');
			notify('Error al obtener los archivos',500,5000,'error',0,0);
		}
	});		
}

function borrarArchivoSeguimiento(idArchivo)
{
	if(!confirm('¿Realmente desea borrar el archivo?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoArchivosSeguimiento').html('<img src="'+ img_loader +'"/> Borrando archivo, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/borrarArchivoSeguimiento',
		data:
		{
			"idArchivo":idArchivo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoArchivosSeguimiento').html('')
			
			switch(data)
			{
				case "0":
				notify('¡Error al borrar el archivo!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El archivo se ha borrado correctamente!',500,5000,'',0,0);
				obtenerArchivosSeguimiento($('#txtIdSeguimiento').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoArchivosSeguimiento').html('');
			notify('¡Error al borrar el fichero!',500,5000,'error',0,0);
		}
	});		
}

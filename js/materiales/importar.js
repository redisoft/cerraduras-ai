$(document).ready(function()
{
	$("#ventanaImportarMateriales").dialog(
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
			$("#formularioImportarMateriales").html('');
		}
	});
});

function formularioImportarMateriales()
{
	$('#ventanaImportarMateriales').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioImportarMateriales').html('<img src="'+ img_loader +'"/> Obteniendo el formulario...');
		},
		type:"POST",
		url:base_url+"importar/formularioImportarMateriales",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioImportarMateriales').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContacto").html('');	
		}
	});
}

function subirArchivoMateriales()
{
	if(document.getElementById('txtImportarFichero').value=='')
	{
		notify('Seleccione el archivo',500,5000,'error',30,5);
		return;
	}
	
	file 	= document.querySelectorAll('#txtImportarFichero')[0];

	file	= file.files[0];
	
	var limit = 1048576*4,xhr;
	console.log( limit  )

	if( file )
	{
		if( file.size < limit )
		{
			if(!confirm('¿Realmente desea importar los materiales?'))return
			
			$('#importandoMateriales').html('<img src="'+ img_loader +'"/> Cargando el archivo, por favor espere...');
			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e)
			{
			}, false);
			
			xhr.onreadystatechange = function()
			{
				if(xhr.readyState == 4 && xhr.status == 200)
				{
					$('#importandoMateriales').html('');
					
					switch(xhr.responseText)
					{
						case "0":
							notify('Error al subir el archivo',500,5000,'error',30,5);
						break;
						
						case "1":
							$('#ventanaImportarMateriales').dialog('close');
							obtenerMateriales();
							notify('La materia prima se ha importado correctamente',500,5000,'',30,5);
						break;
						
						case "2":
							notify('El archivo es incorrecto',500,5000,'error',30,5);
						break;
					}
				}
			}

			xhr.upload.addEventListener('error',function(e)
			{
				notify('Error al subir el archivo',500,5000,'error',30,5);
				$('#importandoMateriales').html('');
			}, false);

			xhr.open('POST',base_url+'importar/subirArchivoMateriales');

            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);
            xhr.send(file);
			
			
		}
		else
		{
			notify('El archivo es demasiado grande',500,5000,'error',30,5);
		}
	}
}

function exportarMateriales()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se estan exportando los datos...');},
		type:"POST",
		url:base_url+'importar/exportarMateriales',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			window.location.href=base_url+'importar/descargarExportar/Materiales'
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

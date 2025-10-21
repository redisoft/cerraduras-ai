archivos		= new Array();
ArchivosGlobal	= new Array();
indiceGlobal	= 0;
indi			= 0;

$(document).ready(function()
{ 
	$('#txtImportarFichero').fileupload(
	{
		url: base_url+'crm/subirArchivosSeguimiento',
		dataType: 'json',
		acceptFileTypes: /(\.|\/)(gif|jpg|jpeg|bmp|tif|png)$/i,
		maxFileSize: 1073741824,
		done: function (e, data) 
		{
			$.each(data.result.files, function (index, file) 
			{
				$('<p/>').text(file.name).appendTo('#files');
				archivos[indi]	= file.name;
				indi++;
				
				ArchivosGlobal[indiceGlobal]	= file.name;
				indiceGlobal++;
			});
		},
		progressall: function (e, data) 
		{
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .progress-bar').css(
				'width',
				progress + '%'
			);
			
			if(progress==100)
			{
				setTimeout(function() 
				{
					renombrarArchivos(archivos,indi);
				}, 2000);
			}
			
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
		
		
		}).on('fileuploaddone', function (e, data) {
	$.each(data.result.files, function (index, file) 
	{
		
		
	});
		
		 }).on('fileuploadfail', function (e, data) 
		 {
		$.each(data.files, function (index) 
		{
			var error = $('<span class="text-danger"/>').text('Archivos no permitidos.');
			$(data.context.children()[index])
				.append('<br>')
				.append(error);
	});
});

function renombrarArchivos(Archivos,Indi)
{
	//ArchivosGlobal	= Archivos;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#enviandoBitacora').html('<img src="'+ img_loader +'"/> Procesando los archivos cargados...');
		},
		type:"POST",
		url:base_url+"crm/renombrarArchivos",
		data:
		{
			archivos:		ArchivosGlobal,
			indice:			indiceGlobal,
			id:				$('#txtIdImagenes').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#enviandoBitacora').html('');
			//notify('El proceso fue existoso',500,5000,'',30,5);
			
			archivos	= new Array();
			indi		= 0;
		},
		error:function(datos)
		{
			notify('Error al procesar archivos',500,5000,'error',5,5);
			$("#enviandoBitacora").html('');	
		}
	});
}
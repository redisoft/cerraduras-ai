function uploadFile(file)
{
	var limit = 1048576*4,xhr;
	console.log( limit  )

	if( file )
	{
		if( file.size < limit )
		{
			if(!confirm('¿Realmente desea subir el archivo?'))return
			
			$('#registrandoArchivosSeguimiento').html('<img src="'+ img_loader +'"/> Cargando el archivo, por favor espere...');
			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e)
			{
				notify('El archivo se ha cargado correctamente',500,5000,'',0,0);
				window.setTimeout("obtenerArchivosSeguimiento("+$('#txtIdSeguimiento').val()+")",1000)
				$('#registrandoArchivosSeguimiento').html('');
			}, false);

			xhr.upload.addEventListener('error',function(e)
			{
				notify('Error al subir el archivo',500,5000,'error',0,0);
			}, false);

			//xhr.open('POST','upload.php');
			xhr.open('POST',base_url+'clientes/subirArchivosSeguimiento/'+$('#txtIdSeguimiento').val());

            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);
            xhr.send(file);
			
			
		}
		else
		{
			notify('El fichero es demasiado grande',500,5000,'error',0,0);
		}
	}
}

var upload_input = document.querySelectorAll('#archivoSeguimiento')[0];

upload_input.onchange = function()
{
	uploadFile( this.files[0] );
};
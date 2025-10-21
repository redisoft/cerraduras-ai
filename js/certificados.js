$(document).ready(function()
{ 
	$("#fileCertificado").pekeUpload(
	{
		theme:				'bootstrap',
		btnText:			'Seleccione',
		allowedExtensions:	"cer",
		url:				base_url+'configuracion/subirCertificado',
		maxSize:			25,
		sizeError:			'El archivo es demasiado grande',
		invalidExtError:	'No se permite ese tipo de archivos',
		onFileSuccess:function(file,data)
		{
			//data	= eval(data);
			procesarCertificado(file.name);
		}
	});
});

/*function uploadFile(file)
{
	var limit = 1048576*2,xhr;
	console.log(limit)

	if(file)
	{
		if(file.size<limit )
		{
			$('#obteniendoCertificado').html('<img src="'+base_url+'img/loader.gif"/> Procesando el certificado'+esperar);
			
			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e)
			{
				notify('El certificado se ha cargado correctamente',500,5000,'',0,0);
				$('#obteniendoCertificado').html('');
				//window.setTimeout("procesarCertificado("+file.name+")",2000);
				procesarCertificado(file.name);
				
				//window.setTimeout('procesarCertificado('+file.name+')',2000);
				//window.setTimeout("procesarCertificado(file.name)",2000);
			}, false);

			xhr.upload.addEventListener('error',function(e)
			{
				notify('Error al subir el fichero',500,5000,'error',0,0);
			}, false);

			xhr.open('POST',base_url+'configuracion/subirCertificado');

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

var upload_input = document.querySelectorAll('#fileCertificado')[0];

upload_input.onchange = function()
{
	if(comprobarCertificado())
	{
		uploadFile( this.files[0] );
	}
};*/

function procesarCertificado(archivo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obteniendoCertificado').html('<img src="'+base_url+'img/loader.gif"/> Se esta procesando el certificado'+esperar);},
		type:"POST",
		url:base_url+'configuracion/procesarCertificado',
		data:
		{
			archivo:archivo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data	=eval(data);
			
			$('#txtNumeroCertificado').val(data[0]);
			$('#txtFechaInicio').val(data[1]);
			$('#txtFechaCaducidad').val(data[2]);
			
			$('#obteniendoCertificado').html('');
		},
		error:function(datos)
		{
			notify('Error al procesar el certificado',500,5000,'error',30,3);
			$("#obteniendoCertificado").html('');
		}
	}); 
}
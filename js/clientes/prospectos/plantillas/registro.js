$(document).ready(function()
{ 
	$("#txtPlantillas").pekeUpload(
	{
		theme:				'bootstrap',
		btnText:			'Seleccione',
		allowedExtensions:	"txt|html|htm|xhtml|zip",
		url:				base_url+'crm/subirPlantilla',
		maxSize:			25,
		sizeError:			'El archivo es demasiado grande',
		invalidExtError:	'No se permite ese tipo de archivos',
		onFileSuccess:function(file,data)
		{
			//data	= eval(data);
			
			switch(data)
			{
				case "0":
					//alert(data[1])
				break;	
				
				case "1":
					obtenerPlantillas()
					notify('La plantilla se ha cargado correctamente',500,5000,'',30,5);
				break;	
			}
		}
	});
});
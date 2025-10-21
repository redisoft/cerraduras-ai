function seleccionarFichero()
{
	var upload_input = document.querySelectorAll('#txtXml')[0];

	upload_input.onchange = function()
	{
		if(!comprobarFichero()) return;
		
		subirFichero(this.files[0]);
	};
}

function comprobarFichero()
{
	cadena		=$('#txtXml').val();
	b			=0;
	extension	="";

	for(i=0;i<cadena.length;i++)
	{
		if(b==1)
		{
			extension+=cadena[i];
		}

		if(cadena[i]==".")
		{
			b=1;
		}
	}
	
	if(extension!='xml')
	{
		notify('El archivo debe estar en formato xml',500,4000,'error',30,5);
		return false;
	}
	
	return true;
}

function subirFichero(file)
{
	var limit = 1048576*2,xhr;
	console.log( limit  )

	if( file )
	{
		if( file.size < limit )
		{
			if(!confirm('¿Realmente desea subir el comprobante?'))return
			
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> El sistema esta procesando el comprobante</label>');
			xhr = new XMLHttpRequest();

			xhr.upload.addEventListener('load',function(e)
			{
				$('#registrandoInformacion').html('');
				
			}, false);
			
			xhr.onreadystatechange = function()
			{
				if(xhr.readyState == 4 && xhr.status == 200)
				{
					data	= eval(xhr.responseText);
					$('#txtXml').val('')
					
					switch(data[0])
					{
						case "0":
							notify('Error al procesar el comprobante',500,4000,'error',30,5);
							
						break;
						
						case "1":
							notify('El comprobante se ha cargado correctamente',500,4000,'error',30,5);
							
							//Póliza
							$('#txtConcepto').val(data[11][0]+data[12][0] + ' | ' + data[15][0] + ' | ' + data[2][0]);
							
							//Transacción
							$('#txtConceptoTransaccion').val('Factura: '+data[11][0]+data[12][0] + ', Cliente: ' + data[25][0]);
							$('#txtHaber').val(data[4][0]);
							
							//Cheque
							
							if(data[10]=='cheque')
							{
								$('#txtMonto').val(data[4][0]);
								$('#txtBeneficiario').val(data[16][0]);
								$('#txtRfc').val(data[15][0]);
							}
							
							//Transferencia
							
							if(data[10]=='transferencia')
							{
								$('#txtMontoTransferencia').val(data[4][0]);
								$('#txtBeneficiarioTransferencia').val(data[16][0]);
								$('#txtRfcTransferencia').val(data[15][0]);
							}
							
							//Comprobante
							$('#txtUuid').val(data[39][0]);
							$('#txtMontoComprobante').val(data[4][0]);
							$('#txtRfcComprobante').val(data[15][0]);
						break;
					}
				}
			}

			xhr.upload.addEventListener('error',function(e)
			{
				$('#registrandoInformacion').html('');
			}, false);


			xhr.open('POST',base_url+'contabilidad/subirXml',true);

            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);
            xhr.send(file);
		}
		else
		{
			notify('El archivo es demasiado grande',500,4000,'error',30,5);
		}
	}
}
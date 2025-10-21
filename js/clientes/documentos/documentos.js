function borrarDocumentoTemporal(idDocumento,temporal)
{
	if(!confirm('¿Realmente desea borrar el documento?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#cargandoClientes').html('<label><img class="ajax-loader" src="'+base_url+'img/ajax-loader.gif"/>Borrando el documento</label>');
		},
		type:"POST",
		url:base_url+'clientes/borrarDocumentoTemporal/'+temporal,
		data:
		{
			idDocumento:idDocumento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoClientes').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar el documento',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El documento se ha borrado correctamente',500,5000,'error',30,5);
					$("#documento"+idDocumento).remove()
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el documento',500,5000,'error',30,5);
			$('#cargandoClientes').html('');
		}
	});	
}

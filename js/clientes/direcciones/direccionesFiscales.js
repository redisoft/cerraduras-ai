function obtenerDireccionesCfdi(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDireccionesCfdi').html('<img src="'+ img_loader +'"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDireccionesCfdi',
		data:
		{
			idCliente:idCliente,
			tipo:3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDireccionesCfdi').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDireccionesCfdi').html('');
			notify('Error en la  busqueda',500,5000,'error',30,5);
		}
	});		
}
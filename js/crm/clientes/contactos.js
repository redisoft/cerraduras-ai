function obtenerContactosCliente(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContactosCliente').html('<img src="'+ img_loader +'"/>Cargando lista de contactos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerContactosCliente',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactosCliente').html(data)
		},
		error:function(datos)
		{
			$('#obtenerContactosCliente').html('');
			notify('Error al obtener los contactos del cliente',500,5000,'error',30,5);
		}
	});		
}

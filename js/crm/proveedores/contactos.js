
function obtenerContactosProveedor(idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContactosProveedor').html('<img src="'+ img_loader +'"/>Cargando lista de contactos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerContactosProveedor',
		data:
		{
			idProveedor:idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactosProveedor').html(data)
		},
		error:function(datos)
		{
			$('#obtenerContactosProveedor').html('');
			notify('Error al obtener los contactos del proveedor',500,5000,'error',30,5);
		}
	});		
}
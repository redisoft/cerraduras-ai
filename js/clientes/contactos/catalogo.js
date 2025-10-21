
function obtenerCatalogoContactos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoContactos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de catálogo...');
		},
		type:"POST",
		url:base_url+'ficha/obtenerCatalogoContactos',
		data:
		{
			idCliente:	$('#txtClienteId').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoContactos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoContactos').html('');
			notify('Error al obtener el catálogo de contactos',500,5000,'error',30,5);
		}
	});
}

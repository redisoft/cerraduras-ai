function obtenerContactosClienteCotizacion(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerContactosClienteCotizacion').html('<img src="'+ img_loader +'"/>Obteniendo la lista de contactos');},
		type:"POST",
		url:base_url+'cotizaciones/obtenerContactosClienteCotizacion',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactosClienteCotizacion').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de contactos',500,4000,"error"); 
			$('#obtenerContactosClienteCotizacion').html('')
		}
	}); 	  
}
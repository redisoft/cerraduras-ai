
function obtenerDetallesEmbudo(idEmbudo,detalles,contar)
{
	if(contar==1) 
	{
		contarDetalleEmbudo(idEmbudo)
		return;	
	}

	if(detalles==0) 
	{
		$('#obtenerDetallesEmbudo').html('')
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/obtenerDetallesEmbudo',
		data:
		{
			"idEmbudo":		idEmbudo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesEmbudo').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}

function contarDetalleEmbudo(idEmbudo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/contarDetalleEmbudo',
		data:
		{
			"idEmbudo":		idEmbudo,
			"idCliente":	$('#txtClienteId').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesEmbudo').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}
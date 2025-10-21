function obtenerConceptosArea()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConceptosArea').html('<img src="'+ img_loader +'"/>Obteniendo registros...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerConceptosArea',
		data:
		{
			idArea:	$('#selectAreas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConceptosArea').html(data)
		},
		error:function(datos)
		{
			$('#obtenerConceptosArea').html('');
			notify('Error en el procesao',500,5000,'error',30,5);
		}
	});		
}


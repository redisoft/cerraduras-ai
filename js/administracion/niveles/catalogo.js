function obtenerNiveles2Busqueda()
{
	$('#obtenerNiveles2Busqueda').html('<select class="cajas" id="selectNiveles2Busqueda" name="selectNiveles2Busqueda" style="width:105px"><option value="0">Nivel 2</option></select>')
	$('#obtenerNiveles3Busqueda').html('<select class="cajas" id="selectNiveles2Busqueda" name="selectNiveles2Busqueda" style="width:105px"><option value="0">Nivel 3</option></select>')
	
	if($('#selectNiveles1Busqueda').val()=="0") return;
	
	setTimeout(function()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerNiveles2Busqueda').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
			},
			type:"POST",
			url:base_url+'catalogos/obtenerNiveles2Busqueda',
			data:
			{
				idNivel1:$('#selectNiveles1Busqueda').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#obtenerNiveles2Busqueda").html(data);
			},
			error:function(datos)
			{
				notify('Error al obtener la lista de niveles',500,5000,'error',30,3);
				$("#obtenerNiveles2Busqueda").html('');
			}
		});	
	},400);
}

function obtenerNiveles3Busqueda()
{
	$('#obtenerNiveles3Busqueda').html('<select class="cajas" id="selectNiveles3Busqueda" name="selectNiveles3Busqueda" style="width:105px"><option value="0">Nivel 3</option></select>')
	
	if($('#selectNiveles1Busqueda').val()=="0") return;
	
	setTimeout(function()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerNiveles3Busqueda').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
			},
			type:"POST",
			url:base_url+'catalogos/obtenerNiveles3Busqueda',
			data:
			{
				idNivel2:$('#selectNiveles2Busqueda').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#obtenerNiveles3Busqueda").html(data);
			},
			error:function(datos)
			{
				notify('Error al obtener la lista de niveles',500,5000,'error',30,3);
				$("#obtenerNiveles3Busqueda").html('');
			}
		});	
	},400);
}





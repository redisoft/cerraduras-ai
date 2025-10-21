function obtenerSeguimientoAtrasos1(idSeguimiento)
{
	$('.sinSombra').removeClass('fuenteNaranja');
	$('#filaSeguimiento'+idSeguimiento).addClass('fuenteNaranja');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoAtrasos').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimientoAtrasos',
		data:
		{
			"idSeguimiento":	idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoAtrasos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoAtrasos').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}


function obtenerSeguimientosAtrasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientosDiarios').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'crm/obtenerSeguimientosAtrasos',
		data:
		{
			"idPromotor":	$('#selectPromotorSeguimientos').val(),
			"nuevos":		$('#selectNuevosAtrasos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientosDiarios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientosDiarios').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}
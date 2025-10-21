//OBTENER COTIZACIONES
$(document).ready(function ()
{
	//
	//$('.ajax-pagCotizaciones > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagCotizaciones > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerCotizaciones";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBusquedaCotizacion').val(),
				inicio:		$('#txtInicio').val(),
				fin:		$('#txtFin').val(),
				orden:		$('#txtOrden').val(),
				idEstacion: $('#selectEstaciones').val(),
				desglose:	$('#selectDeglose').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de cotizaciones, por favor tenga paciencia...</label>');
			},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
	
	
});

function ordenCotizaciones(orden)
{
	$('#txtOrden').val(orden);
	
	obtenerCotizaciones()
}

function obtenerCotizaciones()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCotizaciones').html('<img src="'+ img_loader +'"/>Obteniendo detalles de cotizaciones, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'cotizaciones/obtenerCotizaciones',
		data:
		{
			criterio:	$('#txtBusquedaCotizacion').val(),
			inicio:		$('#txtInicio').val(),
			fin:		$('#txtFin').val(),
			orden:		$('#txtOrden').val(),
			idEstacion: $('#selectEstaciones').val(),
			desglose:	$('#selectDeglose').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCotizaciones').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de cotizaciones',500,5000,'error',2,5);
			$("#obtenerCotizaciones").html('');
		}
	});
}

function confirmarBorrarCotizacion(idCotizacion)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Se esta borrando la cotización, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+"cotizaciones/borrarCotizacion",
		data:
		{
			"idCotizacion":	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html("");
			
			switch(data)
			{
				case "0":
				notify('Error al borrar la cotización',500,5000,'error',30,3);
				break;
				case "1":
				notify('La cotización se ha borrado correctamente',500,5000,'',30,3);
				obtenerCotizaciones();
				break;
				
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html("");
			notify('Error al borrar la cotización',500,5000,'error',30,3);
		}
	});//Ajax	
}

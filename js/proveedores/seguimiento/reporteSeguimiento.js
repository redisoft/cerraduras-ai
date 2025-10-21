//REPORTE DE SEGUIMIENTO PROVEEDORES
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$(document).ready(function()
{
	$("#txtBuscarLlamada").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerSeguimientos();
		}, milisegundos);
	});
	
	//$('.ajax-pagSeguimientos > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagSeguimientos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerSeguimientos";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarLlamada').val(),
				inicio:		$('#FechaDia').val(),
				fin:		$('#FechaDia2').val(),
				idStatus:	$('#selectStatusBusqueda').val(),
				idServicio:	$('#selectServiciosBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo detalles de seguimiento...');},
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

function obtenerSeguimientos()
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
			$('#obtenerSeguimientos').html('<img src="'+ img_loader +'"/>Obteniendo detalles de seguimiento...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerSeguimientos',
		data:
		{
		  	criterio:	$('#txtBuscarLlamada').val(),
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			idStatus:	$('#selectStatusBusqueda').val(),
			idServicio:	$('#selectServiciosBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las seguimiento',500,4000,"error",30,5);
			$("#obtenerSeguimientos").html('');
		}
	});
}



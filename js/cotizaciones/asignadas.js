//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//CATALOGOS COLORES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaCotizacionAsignada").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Guardar': function() 
			{
				registrarCotizacionAsignada()
			},
		},
		close: function() 
		{
			$("#obtenerCotizacionAsignada").html('');
		}
	});
});

function obtenerCotizacionAsignada(idCotizacion)
{
	$("#ventanaCotizacionAsignada").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCotizacionAsignada').html('<img src="'+ img_loader +'"/>Obteniendo detalles de cotización');},
		type:"POST",
		url:base_url+'cotizaciones/obtenerCotizacionAsignada',
		data:
		{
			idCotizacion:idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCotizacionAsignada').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de la cotización',500,4000,"error"); 
			$('#obtenerCotizacionAsignada').html('')
		}
	}); 	  
}

function registrarCotizacionAsignada()
{
	if($('#selectMotivos').val()=="0")
	{
		notify('Seleccion el motivo',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#desasignandoCotizacion').html('<img src="'+ img_loader +'"/>Se esta realizando el registro');},
		type:"POST",
		url:base_url+'cotizaciones/registrarCotizacionAsignada',
		data:$('#frmAsignada').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#desasignandoCotizacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro se ha guardado correctamente',500,5000,'',30,5);
					$('#filaCotizacion'+$('#txtIdCotizacion').val()).remove();
					$("#ventanaCotizacionAsignada").dialog('close');
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#desasignandoCotizacion').html('');
		}
	}); 	  
}


$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagCotizaciones > li a", function(eve)
	//$('.ajax-pagCotizaciones > li a').live('click',function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerCotizacionesAsignadas";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarCotizacion').val(),
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
	
	$("#txtBuscarCotizacion").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerCotizacionesAsignadas();
		}, milisegundos);
	});
});

function obtenerCotizacionesAsignadas()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCotizacionesAsignadas').html('<img src="'+ img_loader +'"/>Obteniendo detalles de cotización');},
		type:"POST",
		url:base_url+'cotizaciones/obtenerCotizacionesAsignadas',
		data:
		{
			criterio:	$('#txtBuscarCotizacion').val(),
			idMotivo:	$('#selectMotivos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCotizacionesAsignadas').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de la cotización',500,4000,"error"); 
			$('#obtenerCotizacionesAsignadas').html('')
		}
	}); 	  
}
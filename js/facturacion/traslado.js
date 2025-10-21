tipo ="";

$(document).ready(function()
{
	$("#ventanaTraslado").dialog(
	{
		autoOpen:false,
		height:650,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				registrarTrasldo()		 
			},
		},
		close: function() 
		{
			$("#formularioPagosCfdi").html('');
		}
	});
});


function formularioTraslado(idCotizacion)
{
	$('#ventanaTraslado').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioTraslado').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de factura...</label>');
		},
		type:"POST",
		url:base_url+'facturacion/formularioTraslado',
		data:
		{
			idCotizacion:idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioTraslado').html(data);
			obtenerFolio();
		},
		error:function(datos)
		{
			$('#formularioTraslado').html('');
			notify("Error al obtener los datos para registrar la factura",500,4000,"error"); 
		}
	});	
}

function registrarTrasldo()
{
	mensaje="";
	
	if($('#selectDireccionesCfdi').val()=="0")
	{
		mensaje+="Seleccione la dirección";
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoTraslado').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Procesando el registro...</label>');
		},
		type:"POST",
		url:base_url+'facturacion/registrarTrasldo',
		data:
		$('#frmTraslado').serialize()+'&usoCfdiTexto='+$('#selectUsoCfdi option:selected').text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoTraslado').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "1":
					notify(data[1],500,7000,'',30,5);
					
					
					
					formularioTraslado($('#txtIdCotizacion').val())
					/*if($('#txtModuloTraslado').val()=="envios")
					{
						obtenerReporte();
					}
					if($('#txtModuloTraslado').val()=="ventas")
					{
						obtenerVentas();
					}*/
					
				break;
				
				case "0":
					notify(data[1],500,7000,'error',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoTraslado').html('');
			notify("Error en el registro",500,5000,"error",30,5); 
		}
	});	
}

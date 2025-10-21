function imprimirTicket()
{
	/*var mode = 'popup'; // popup
	var close = mode == "popup";
	var options = { mode : mode, popClose : close};
	$("#imprimirTicket").printArea( options );*/
	
	
	etiquetas 			= document.getElementById('imprimirTicket');
	ventanaImprimir 	= window.open(' ', 'popimpr');
	
	ventanaImprimir.document.write( etiquetas.innerHTML );
	ventanaImprimir.document.close();
	ventanaImprimir.print( );
	ventanaImprimir.close();
	$('#imprimirTicket').html('');
}

function obtenerTicket(idCotizacion)
{
	url	= tipoVenta==0?base_url+"clientes/imprimirTicket/"+idCotizacion:base_url+"clientes/imprimirTicketVenta/"+idCotizacion;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			//$('#procesandoRecepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> El sistema esta procesando las etiquetas');
		},
		type:"POST",
		url:url,
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#procesandoRecepciones').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al imprimir el ticket',500,5000,'error',30,5);
				break;
				
				default:
				$('#imprimirTicket').html(data);
				imprimirTicket();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al imprimir el tiqueta ',500,5000,'error',30,10);
		}
	});	
}

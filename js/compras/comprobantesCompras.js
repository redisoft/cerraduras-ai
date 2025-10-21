//Para los ficheros

$(document).ready(function()
{
	$("#ventanaComprobantesCompras").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cerrar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerComprobantesCompras").html('');
		}
	});
});

function obtenerComprobantesCompras(idCompra,idRecibido)
{
	$('#ventanaComprobantesCompras').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprobantesCompras').html('<img src="'+ img_loader +'"/> Obteniendo la lista comprobantes, por favor espere...');
		},
		type:"POST",
		url:base_url+'compras/obtenerComprobantesCompras',
		data:
		{
			idCompra:	idCompra,
			idRecibido:	idRecibido
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprobantesCompras').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista comprobantes',500,5000,'error',2,5);
			$('#obtenerComprobantesCompras').html('');
		}
	});					  	  
}

function borrarComprobanteCompra(idComprobante,idCompra,idRecibido)
{
	if(!confirm('¿Realmente desea borrar el comprobante?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoComprobanteCompra').html('<img src="'+ img_loader +'"/> Borrando el comprobante, por favor espere...');
		},
		type:"POST",
		url:base_url+'compras/borrarComprobanteCompra',
		data:
		{
			"idComprobante":idComprobante,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoComprobanteCompra').html('')
			
			switch(data)
			{
				case "0":
				notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El comprobante se ha borrado correctamente!',500,5000,'',0,0);
					obtenerComprobantesCompras(idCompra,idRecibido);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoComprobanteCompra').html('');
			notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
		}
	});		
}

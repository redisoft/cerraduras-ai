$(document).ready(function()
{
	$("#ventanaComprobantesEgresos").dialog(
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
			$("#obtenerComprobantesEgresos").html('');
		}
	});
});

function obtenerComprobantesEgresos(idEgreso)
{
	$('#ventanaComprobantesEgresos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprobantesEgresos').html('<img src="'+ img_loader +'"/> Obteniendo la lista comprobantes, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerComprobantesEgresos',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprobantesEgresos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista comprobantes',500,5000,'error',2,5);
			$('#obtenerComprobantesEgresos').html('');
		}
	});					  	  
}

function borrarComprobanteEgreso(idComprobante,idEgreso)
{
	if(!confirm('¿Realmente desea borrar el comprobante?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoComprobante').html('<img src="'+ img_loader +'"/> Borrando el comprobante, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/borrarComprobanteEgreso',
		data:
		{
			"idComprobante":idComprobante,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoComprobanteEgreso').html('')
			
			switch(data)
			{
				case "0":
				notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El comprobante se ha borrado correctamente!',500,5000,'',0,0);
				obtenerComprobantesEgresos(idEgreso);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoComprobanteEgreso').html('');
			notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
		}
	});		
}

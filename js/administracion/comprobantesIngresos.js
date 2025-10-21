//OTROS INGRESOS
//-------------------------------------------------------------------------------------------------------------------

$(document).ready(function()
{
	$("#ventanaComprobantes").dialog(
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
			$("#obtenerComprobantes").html('');
		}
	});
});

function obtenerComprobantes(idIngreso)
{
	$('#ventanaComprobantes').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprobantes').html('<img src="'+ img_loader +'"/> Obteniendo la lista comprobantes, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerComprobantes',
		data:
		{
			idIngreso:idIngreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprobantes').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista comprobantes',500,5000,'error',2,5);
			$('#obtenerComprobantes').html('');
		}
	});					  	  
}

function borrarComprobante(idComprobante,idIngreso)
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
		url:base_url+'administracion/borrarComprobante',
		data:
		{
			"idComprobante":idComprobante,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoComprobante').html('')
			
			switch(data)
			{
				case "0":
				notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El comprobante se ha borrado correctamente!',500,5000,'',0,0);
				obtenerComprobantes(idIngreso);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoComprobante').html('');
			notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
		}
	});		
}
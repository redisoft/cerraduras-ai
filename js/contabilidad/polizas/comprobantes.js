//FICHEROS
$(document).ready(function()
{
	$("#ventanaComprobantesConcepto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function()
		{
			$("#obtenerComprobantesConcepto").html('');
		}
	});
});

function obtenerComprobantesConcepto(idConcepto)
{
	$('#ventanaComprobantesConcepto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprobantesConcepto').html('<img src="'+ img_loader +'"/> Obteniendo comprobantes, por favor espere...');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerComprobantesConcepto',
		data:
		{
			"idConcepto":idConcepto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprobantesConcepto').html(data);
		},
		error:function(datos)
		{
			$('#obtenerComprobantesConcepto').html('');
			notify('Error al obtener los comprobantes',500,5000,'error',0,0);
		}
	});		
}

function borrarComprobanteConcepto(idComprobante)
{
	if(!confirm('¿Realmente desea borrar el archivo?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoComprobantesConcepto').html('<img src="'+ img_loader +'"/> Borrando comprobante, por favor espere...');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarComprobanteConcepto',
		data:
		{
			"idComprobante":idComprobante,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoComprobantesConcepto').html('')
			
			switch(data)
			{
				case "0":
				notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('¡El comprobante se ha borrado correctamente!',500,5000,'',0,0);
				obtenerComprobantesConcepto($('#txtIdConcepto').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#procesandoComprobantesConcepto').html('');
			notify('¡Error al borrar el comprobante!',500,5000,'error',0,0);
		}
	});		
}
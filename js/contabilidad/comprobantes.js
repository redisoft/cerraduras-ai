//COMPROBANTES

fila	= 1; //Para configurar el número de comprobantes

$(document).ready(function()
{
	$("#ventanaComprobantes").dialog(
	{
		autoOpen:false,     
		show: { effect: "scale", duration: 600 },                         
		height:550,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			/*'Agregar comprobante': function() 
			{
				cargarComprobante();
			},*/
			'Registrar': function() 
			{
				registrarComprobantes();
			},
		},
		close: function() 
		{
			$('#obtenerComprobantes').html('');
		}
	});
});

function obtenerComprobantes(idTransaccion)
{
	$('#ventanaComprobantes').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerComprobantes').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de comprobantes...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerComprobantes',
		data:
		{
			idTransaccion:idTransaccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprobantes').html(data);
			fila	= parseInt($('#txtNumeroComprobantes').val());
			fila++;
		},
		error:function(datos)
		{
			$('#obtenerComprobantes').html('');
			notify("Error al obtener los detalles de los comprobantes",500,4000,"error"); 
		}
	});	
}

function borrarComprobanteNuevo(i)
{
	$('#filaComprobante'+i).remove();
}

function cargarComprobante()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoComprobantes').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando comprobante...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarComprobante',
		data:
		{
			i:fila
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoComprobantes').html('');
			
			fila++;
			$('#tablaComprobantes').append(data);
			$('#txtNumeroComprobantes').val(fila);
		},
		error:function(datos)
		{
			$('#procesandoComprobantes').html('');
			notify("Error al cargar el comprobante",500,4000,"error"); 
		}
	});	
}


function registrarComprobantes()
{
	alerta	= "";
	cheque	= false;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdComprobante'+i).val()))
		{
			cheque=true;
			
			if(!camposVacios($('#txtUuid'+i).val()) || !camposVacios($('#txtMonto'+i).val()) ||  !camposVacios($('#txtRfc'+i).val()))
			{
				alerta+='Configure correctamente todos los campos para todos los registros<br />';
				break;
			}
		}
	}
	
	if(!cheque)
	{
		alerta+='Agregar por lo menos un comprobante<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea guardar los registros de comprobantes?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoComprobantes').html('<label><img src="'+base_url+'img/loader.gif"/> Se estan registrando los comprobantes...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarComprobantes',
		data:
		$('#frmComprobantes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoComprobantes').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar los registros de los comprobantes',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('Los registros se han guardado correctamente',500,4000,'',30,5);
				obtenerComprobantes($('#txtIdTransaccion').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoComprobantes').html('');
			notify('Error al registrar los comprobantes',500,4000,'error',30,5);
		}
	});	
}

function borrarComprobante(idComprobante)
{
	if(!confirm('¿Realmente desea borrar el comprobante?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoComprobantes').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando comprobante '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarComprobante',
		data:
		{
			idComprobante:idComprobante
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoComprobantes').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar el comprobante',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaComprobante'+idComprobante).remove();
				notify('El comprobante se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoComprobantes').html('');
			notify("Error al borrar el comprobante",500,4000,"error"); 
		}
	});	
}
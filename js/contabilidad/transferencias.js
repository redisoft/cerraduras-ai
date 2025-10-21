//CHEQUES

fila	= 1; //Para configurar el número de cuentas de las transacciones

$(document).ready(function()
{
	$("#ventanaTransferencias").dialog(
	{
		autoOpen:false, 
		show: { effect: "scale", duration: 600 },                             
		height:550,
		width:1170,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			/*'Agregar transferencia': function() 
			{
				cargarTransferencia();
			},*/
			'Registrar': function() 
			{
				registrarTransferencias();
			},
		},
		close: function() 
		{
			$('#obtenerTransferencias').html('');
		}
	});
});

function obtenerTransferencias(idTransaccion)
{
	$('#ventanaTransferencias').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTransferencias').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de transferencias...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerTransferencias',
		data:
		{
			idTransaccion:idTransaccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTransferencias').html(data);
			fila	= parseInt($('#txtNumeroTransferencias').val());
			fila++;
		},
		error:function(datos)
		{
			$('#obtenerTransferencias').html('');
			notify("Error al obtener los detalles de las transferencias",500,4000,"error"); 
		}
	});	
}

function borrarTransferenciaNueva(i)
{
	$('#filaTransferencia'+i).remove();
}

function cargarTransferencia()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransferencias').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando transferencia...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarTransferencia',
		data:
		{
			i:fila
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransferencias').html('');
			
			fila++;
			$('#tablaTransferencias').append(data);
			$('#txtNumeroTransferencias').val(fila);
		},
		error:function(datos)
		{
			$('#procesandoTransferencias').html('');
			notify("Error al cargar la transferencia",500,4000,"error"); 
		}
	});	
}


function registrarTransferencias()
{
	alerta	= "";
	cheque	= false;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdTransferencia'+i).val()))
		{
			cheque=true;
			
			if(!camposVacios($('#txtCuentaOrigen'+i).val())
			|| !camposVacios($('#txtCuentaDestino'+i).val()) || !camposVacios($('#txtMonto'+i).val()) 
			|| !camposVacios($('#txtBeneficiario'+i).val()) || !camposVacios($('#txtRfc'+i).val()))
			{
				alerta+='Configure correctamente todos los campos para todas las transferencias<br />';
				break;
			}
		}
	}
	
	if(!cheque)
	{
		alerta+='Agregar por lo menos una transferencia<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea guardar los registros de transferencias?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransferencias').html('<label><img src="'+base_url+'img/loader.gif"/> Se estan registrando las transferencias...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarTransferencias',
		data:
		$('#frmTransferencias').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransferencias').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar los registros de los transferencias',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('Los registros se han guardado correctamente',500,4000,'',30,5);
				obtenerTransferencias($('#txtIdTransaccion').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoTransferencias').html('');
			notify('Error al registrar las transferencias',500,4000,'error',30,5);
		}
	});	
}

function borrarTransferencia(idTransferencia)
{
	if(!confirm('¿Realmente desea borrar la transferencia?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransferencias').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando transferencia '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarTransferencia',
		data:
		{
			idTransferencia:idTransferencia
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransferencias').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la transferencia',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaTransferencia'+idTransferencia).remove();
				notify('La transferencia se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoTransferencias').html('');
			notify("Error al borrar la transferencia",500,4000,"error"); 
		}
	});	
}
//VENTANA CONCEPTOS POLIZA

fila	= 1; //Para configurar el número de cuentas de las transacciones

$(document).ready(function()
{
	$("#ventanaTransacciones").dialog(
	{
		autoOpen:false,     
		show: { effect: "scale", duration: 600 },                         
		height:600,
		width:1180,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			/*'Agregar transacción': function() 
			{
				cargarCuentaTransaccion();
			},*/
			'Registrar': function() 
			{
				registrarTransacciones();
			},
		},
		close: function() 
		{
			$('#obtenerTransacciones').html('');
		}
	});
	
	/*$("#ventanaFormularioConceptos").dialog(
	{
		autoOpen:false,                              
		height:400,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			'Registrar': function() 
			{
				registrarConcepto();
			},
		},
		close: function() 
		{
			$('#formularioConceptos').html('');
		}
	});
	
	$("#ventanaEditarConcepto").dialog(
	{
		autoOpen:false,                              
		height:400,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			'Editar': function() 
			{
				editarConcepto();
			},
		},
		close: function() 
		{
			$('#obtenerConcepto').html('');
		}
	});*/
});

function obtenerTransacciones(idConcepto)
{
	$('#ventanaTransacciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTransacciones').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de transacciones...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerTransacciones',
		data:
		{
			idConcepto:idConcepto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTransacciones').html(data);
			fila	= parseInt($('#txtNumeroCuentas').val());
			fila++;
		},
		error:function(datos)
		{
			$('#obtenerTransacciones').html('');
			notify("Error al obtener los detalles de las transacciones",500,4000,"error"); 
		}
	});	
}

function borrarCuentaNueva(i)
{
	$('#filaTransaccion'+i).remove();
}

function cargarCuentaTransaccion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransacciones').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarCuentaTransaccion',
		data:
		{
			i:		fila,
			fecha:	$('#txtFechaPolizaTransaccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransacciones').html('');
			
			fila++;
			$('#tablaTransacciones').append(data);
			$('#txtNumeroCuentas').val(fila);
		},
		error:function(datos)
		{
			$('#procesandoTransacciones').html('');
			notify("Error al preparar el formulario para las cuentas",500,4000,"error"); 
		}
	});	
}


function registrarTransacciones()
{
	alerta	= "";
	cuenta	= false;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdTransaccion'+i).val()))
		{
			cuenta=true;
			
			if($('#selectCuentasTransaccion'+i).val()=="0" || !camposVacios($('#txtConcepto'+i).val()))
			{
				alerta+='Configure correctamente el número de cuenta y concepto para todos los registros<br />';
				break;
			}
		}
	}
	
	if(!cuenta)
	{
		alerta+='Agregar por lo menos una cuenta<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea guardar los registros de transacciones?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransacciones').html('<label><img src="'+base_url+'img/loader.gif"/> Se estan registrando las transacciones...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarTransacciones',
		data:
		$('#frmTransacciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransacciones').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar los registros de las transacciones',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('Los registros se han guardado correctamente',500,4000,'',30,5);
				obtenerTransacciones($('#txtIdConcepto').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoTransacciones').html('');
			notify('Error al registrar las transacciones',500,4000,'error',30,5);
		}
	});	
}

function borrarTransaccion(idTransaccion)
{
	if(!confirm('Borrar la transacción borrara sus comprobantes, cheques y transferencias ¿Realmente desea continuar?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTransacciones').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando transacciones '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarTransaccion',
		data:
		{
			idTransaccion:idTransaccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTransacciones').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la transacción',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaTransaccion'+idTransaccion).remove();
				notify('La transacción se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoTransacciones').html('');
			notify("Error al borrar la transacción",500,4000,"error"); 
		}
	});	
}

//VENTANA CONCEPTOS POLIZA
$(document).ready(function()
{
	$("#ventanaConceptosTransaccion").dialog(
	{
		autoOpen:false,      
		show: { effect: "scale", duration: 600 },                        
		height:400,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#obtenerConceptosTransaccion').html('');
		}
	});
});

function obtenerConceptosTransaccion(idTransaccion)
{
	$('#ventanaConceptosTransaccion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConceptosTransaccion').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo la lista de conceptos...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerConceptosTransaccion',
		data:
		{
			idTransaccion:	idTransaccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConceptosTransaccion').html(data);
		},
		error:function(datos)
		{
			$('#obtenerConceptosTransaccion').html('');
			notify("Error al obtener el obtener los  conceptos",500,4000,"error"); 
		}
	});	
}

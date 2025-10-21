//CHEQUES

fila	= 1; //Para configurar el número de cheques

$(document).ready(function()
{
	$("#ventanaCheques").dialog(
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
			/*'Agregar cheque': function() 
			{
				cargarCheque();
			},*/
			'Registrar': function() 
			{
				registrarCheques();
			},
		},
		close: function() 
		{
			$('#obtenerCheques').html('');
		}
	});
});

function obtenerCheques(idTransaccion)
{
	$('#ventanaCheques').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCheques').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cheques...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCheques',
		data:
		{
			idTransaccion:idTransaccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCheques').html(data);
			fila	= parseInt($('#txtNumeroCheques').val());
			fila++;
		},
		error:function(datos)
		{
			$('#obtenerCheques').html('');
			notify("Error al obtener los detalles de los cheques",500,4000,"error"); 
		}
	});	
}

function borrarChequeNuevo(i)
{
	$('#filaCheque'+i).remove();
}

function cargarCheque()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCheques').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando cheque...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarCheque',
		data:
		{
			i:		fila,
			fecha:	$('#txtFechaPolizaTransaccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCheques').html('');
			
			fila++;
			$('#tablaCheques').append(data);
			$('#txtNumeroCheques').val(fila);
		},
		error:function(datos)
		{
			$('#procesandoCheques').html('');
			notify("Error al cargar el cheque",500,4000,"error"); 
		}
	});	
}


function registrarCheques()
{
	alerta	= "";
	cheque	= false;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdCheque'+i).val()))
		{
			cheque=true;
			
			if(!camposVacios($('#txtNumeroCheque'+i).val()) 
			|| !camposVacios($('#txtCuentaOrigen'+i).val()) || !camposVacios($('#txtMonto'+i).val()) 
			|| !camposVacios($('#txtBeneficiario'+i).val()) || !camposVacios($('#txtRfc'+i).val()))
			{
				alerta+='Configure correctamente todos los campos para todos los registros<br />';
				break;
			}
		}
	}
	
	if(!cheque)
	{
		alerta+='Agregar por lo menos un cheque<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea guardar los registros de cheques?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCheques').html('<label><img src="'+base_url+'img/loader.gif"/> Se estan registrando los cheques...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarCheques',
		data:
		$('#frmCheques').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCheques').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar los registros de los cheques',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('Los registros se han guardado correctamente',500,4000,'',30,5);
				obtenerCheques($('#txtIdTransaccion').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoCheques').html('');
			notify('Error al registrar los cheques',500,4000,'error',30,5);
		}
	});	
}

function borrarCheque(idCheque)
{
	if(!confirm('¿Realmente desea borrar el cheque?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCheques').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando cheque '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarCheque',
		data:
		{
			idCheque:idCheque
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCheques').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar el cheque',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaCheque'+idCheque).remove();
				notify('El cheque se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoCheques').html('');
			notify("Error al borrar el cheque",500,4000,"error"); 
		}
	});	
}
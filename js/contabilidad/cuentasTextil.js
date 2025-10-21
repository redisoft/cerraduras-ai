$(document).ready(function()
{
	$("#ventanaAsociarCuenta").dialog(
	{
		autoOpen:false,    
		show: { effect: "scale", duration: 600 },                          
		height:400,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				asociarCuentaGasto();
			},
		},
		close: function() 
		{
			$('#obtenerNiveles').html('');
		}
	});
	
});

function formularioAsociarCuenta(idGasto)
{
	$('#ventanaAsociarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAsociarCuenta').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles del gasto...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/formularioAsociarCuenta',
		data:
		{
			idGasto:idGasto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAsociarCuenta').html(data)
		},
		error:function(datos)
		{
			$('#formularioAsociarCuenta').html('');
			notify("Error al obtener detalles de gasto",500,4000,"error"); 
		}
	});	
}

function asociarCuentaGasto()
{
	alerta	= "";

	if($('#selectCuentaCatalogo').val()=="0")
	{
		alerta+='Seleccione la cuenta<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar asociar la cuenta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asociandoCuenta').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta asociando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/asociarCuentaGasto',
		data:
		{
			idCuentaCatalogo:	$('#selectCuentaCatalogo').val(),
			idGasto:			$('#txtIdGasto').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asociandoCuenta').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar el registro',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha asociado correctamente',500,4000,'',30,5);
				formularioAsociarCuenta($('#txtIdGasto').val());
				
				break;
			}
		},
		error:function(datos)
		{
			$('#asociandoCuenta').html('');
			notify('Error al asociar la cuenta',500,4000,'error',30,5);
		}
	});	
}

function borrarCuentaGasto(idRelacion)
{
	if(!confirm('¿Realmente desea borrar la cuenta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asociandoCuenta').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'cuentas/borrarCuentaGasto',
		data:
		{
			idRelacion:	idRelacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asociandoCuenta').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar el registro',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',500,4000,'',30,5);
				formularioAsociarCuenta($('#txtIdGasto').val());
				
				break;
			}
		},
		error:function(datos)
		{
			$('#asociandoCuenta').html('');
			notify('Error al borrar la cuenta',500,4000,'error',30,5);
		}
	});	
}
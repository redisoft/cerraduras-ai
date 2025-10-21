//--------------------------------------------------------------------------------------------//
//PARA LAS FORMAS DE PAGO
//--------------------------------------------------------------------------------------------//

function formularioFormas()
{
	$('#ventanaFormas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioFormas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la forma de pago..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioFormas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFormas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la forma de pago',500,5000,'error',30,3);
			$("#formularioFormas").html('');
		}
	});
}

function registrarForma()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="La forma de pago es incorrecta <br />";
	}
	
	if(Solo_Numerico($('#txtPorcentaje').val())=="")
	{
		mensaje+="El porcentaje es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoForma').html('<img src="'+ img_loader +'"/> Registrando la forma de pago, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarForma',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			fecha: 			$('#txtFecha').val(),
			porcentaje: 	$('#txtPorcentaje').val(),
			idCuenta: 		$('#selectCuentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoForma').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
				notify(data[1],500,5000,'error',30,5);
				
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/formasPago';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la forma de pago',500,5000,'error',30,3);
			$("#registrandoForma").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaFormas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:260,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarForma();
			},
		},
		close: function() 
		{
			$("#formularioFormas").html(''); 
		}
	});

	$("#ventanaEditarForma").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:260,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');
			},
			'Aceptar': function() 
			{
				editarForma();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerForma').html('');
		}
	});
});

function obtenerForma(idForma)
{
	$('#ventanaEditarForma').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerForma').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar la forma de pago..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerForma',
		data:
		{
			idForma:idForma
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerForma").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar la forma de pago',500,5000,'error',30,3);
			$("#obtenerForma").html('');
		}
	});
}

function editarForma()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="La forma de pago es incorrecta <br />";
	}
	
	if(Solo_Numerico($('#txtPorcentaje').val())=="")
	{
		mensaje+="El porcentaje es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro de la forma de pago?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoForma').html('<img src="'+ img_loader +'"/> Editando la forma de pago, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarForma',
		data:
		{
			nombre: 		$('#txtNombre').val(),
			fecha: 			$('#txtFecha').val(),
			porcentaje: 	$('#txtPorcentaje').val(),
			idForma: 		$('#txtIdForma').val(),
			idCuenta: 		$('#selectCuentas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar la forma de pago o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoForma').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/formasPago';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la forma de pago o no hubo cambios en el registro',500,5000,'error',30,3);
			$("#editandoForma").html('');
		}
	});		
}

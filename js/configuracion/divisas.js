//DIVISAS
function formularioDivisas()
{
	$('#ventanaDivisas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDivisas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la divisa..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioDivisas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioDivisas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la divisa',500,5000,'error',30,3);
			$("#formularioDivisas").html('');
		}
	});
}

function registrarDivisa()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre de la divisa es incorrecto <br />";
	}
	
	if($('#txtClave').val()=="")
	{
		mensaje+="La clave de la divisa es incorrecta <br />";
	}
	
	if($('#txtTipoCambio').val()=="0" || isNaN($('#txtTipoCambio').val()))
	{
		mensaje+="El tipo de cambio de la divisa es incorrecto";
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
			$('#registrandoDivisa').html('<img src="'+ img_loader +'"/> Registrandos la divisa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarDivisa',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			clave: 		$('#txtClave').val(),
			tipoCambio: $('#txtTipoCambio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoDivisa').html('');
			data	= eval(data)
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/divisas';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la divisa',500,5000,'error',30,5);
			$("#registrandoDivisa").html('');
		}
	});		
}

$(document).ready(function()
{

	$("#ventanaDivisas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				registrarDivisa();
			},
		},
		close: function() 
		{
			$("#formularioDivisas").html(''); 
		}
	});
	
	$("#ventanaEditarDivisa").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				editarDivisa();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerDivisa').html('');
		}
	});
});

function obtenerDivisa(idDivisa)
{
	$('#ventanaEditarDivisa').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDivisa').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar la divisa..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerDivisa',
		data:
		{
			idDivisa:idDivisa
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDivisa").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar la divisa',500,5000,'error',30,3);
			$("#obtenerDivisa").html('');
		}
	});
}

function editarDivisa()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre de la divisa es incorrecto <br />";
	}
	
	if($('#txtClave').val()=="")
	{
		mensaje+="La clave de la divisa es incorrecta <br />";
	}
	
	if($('#txtTipoCambio').val()=="0" || isNaN($('#txtTipoCambio').val()))
	{
		mensaje+="El tipo de cambio de la divisa es incorrecto";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro de la divisa?')==false)
	{
		return;
	}
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDivisa').html('<img src="'+ img_loader +'"/> Editando la divisa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarDivisa',
		data:
		{
			nombre: 	$('#txtNombre').val(),
			clave: 		$('#txtClave').val(),
			tipoCambio: $('#txtTipoCambio').val(),
			idDivisa: 	$('#txtIdDivisa').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar la divisa o no hubo cambios en el registro',500,5000,'error',30,3);
				$('#editandoDivisa').html('');
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/divisas';
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la divisa',500,5000,'error',30,3);
			$("#editandoDivisa").html('');
		}
	});		
}
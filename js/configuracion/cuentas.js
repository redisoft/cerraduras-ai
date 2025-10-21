//CUENTAS
//---------------------------------------------------------------------------------------------
$(document).ready(function()
{
	$("#ventanaCuentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
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
				registrarCuenta()
			},
			
		},
		close: function() 
		{
			$("#formularioCuentas").html('');
		}
	});
	
	$("#ventanaEditarCuenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
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
				editarCuenta()
			},
			
		},
		close: function() 
		{
			$("#formularioCuentas").html('');
		}
	});
});

function obtenerCuenta(idCuenta)
{
	$('#ventanaEditarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuenta').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar la cuenta...');
		},
		type:"POST",
		url:base_url+'bancos/obtenerCuenta',
		data:
		{
			idCuenta:idCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCuenta").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar la cuenta',500,5000,'error',30,3);
			$("#obtenerCuenta").html('');
		}
	});
}

function editarCuenta()
{
	var mensaje="";
	
	/*if($("#txtCuenta").val()=="")
	{
		notify('El número de cuenta es incorrecto',500,5000,'error',30,15);
		return;										
	}*/
	
	if(!camposVacios($("#txtCuenta").val()) && !camposVacios($("#txtTarjetaCredito").val()) && !camposVacios($("#txtClabe").val()))
	{
		notify('Por favor ingrese al menos uno de los siguientes campos: No cuenta, Clabe, Tarjeta de crédito',500,5000,'error',30,5);
		return;										
	}
	
	/*if(!camposVacios($("#txtClabe").val()) || !longitudCadena($("#txtClabe").val(),18))
	{
		notify('La clabe debe contener 18 dígitos',500,5000,'error',30,15);
		return;										
	}*/
	
	/*if(camposVacios($("#txtTarjetaCredito").val()))
	{
		if(!longitudCadena($("#txtTarjetaCredito").val(),16))
		{
			notify('La clabe debe contener 16 dígitos',500,5000,'error',30,5);
			return;										
		}
	}*/
	
	if(!confirm('¿Realmente desea editar el registro de la cuenta?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCuenta').html('<img src="'+ img_loader +'"/> Editando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"bancos/editarCuenta",
		data:
		{
			"idCuenta":				$("#txtIdCuenta").val(),
			"idBanco":				$("#selectBancos").val(),
			"cuenta":				$("#txtCuenta").val(),
			"clabe":				$("#txtClabe").val(),
			"idEmisor":				$("#selectEmisores").val(),
			"reportes":				document.getElementById('chkReportes').checked?'1':'0',
			"idCuentaCatalogo":		$("#txtIdCuentaCatalogo").val(),
			"saldoInicial":			$("#txtSaldoInicial").val(),
			"tarjetaCredito":		$("#txtTarjetaCredito").val(),
			"dashboard":			document.getElementById('chkDefault').checked?'1':'0',
			"noDisponible":			document.getElementById('chkNodisponible').checked?'1':'0',
			"sie":					document.getElementById('chkSie').checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCuenta').html('');
			
			switch(data)
			{
				case "0":
					notify('Los registros de la cuenta no se han modificado',500,5000,'error',30,3);
				break;
				case "1":
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la cuenta',500,5000,'error',30,3);	
			$('#editandoCuenta').html('');
		}
	});
}

function formularioCuentas()
{
	$('#ventanaCuentas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCuentas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la cuenta...');
		},
		type:"POST",
		url:base_url+'bancos/formularioCuentas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioCuentas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la cuenta',500,5000,'error',30,3);
			$("#formularioCuentas").html('');
		}
	});
}

function registrarCuenta()
{
	var mensaje="";
	
	if(!camposVacios($("#txtCuenta").val()) && !camposVacios($("#txtTarjetaCredito").val()) && !camposVacios($("#txtClabe").val()))
	{
		notify('Por favor ingrese al menos uno de los siguientes campos: No cuenta, Clabe, Tarjeta de crédito',500,5000,'error',30,5);
		return;										
	}
	
	/*if(!camposVacios($("#txtClabe").val()) || !longitudCadena($("#txtClabe").val(),18))
	{
		notify('La clabe debe contener 18 dígitos',500,5000,'error',30,15);
		return;										
	}*/
	
	/*if(camposVacios($("#txtTarjetaCredito").val()))
	{
		if(!longitudCadena($("#txtTarjetaCredito").val(),16))
		{
			notify('La clabe debe contener 16 dígitos',500,5000,'error',30,5);
			return;										
		}
	}*/
	
	if(!confirm('¿Realmente desea registrar la cuenta?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoCuenta').html('<img src="'+ img_loader +'"/> Registrando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"bancos/registrarCuenta",
		data:
		{
			"idBanco":				$("#selectBancos").val(),
			"cuenta":				$("#txtCuenta").val(),
			"clabe":				$("#txtClabe").val(),
			"idEmisor":				$("#selectEmisores").val(),
			"idCliente":			$("#txtIdCliente").val(),
			"reportes":				$("#chkReportes").attr('checked','true')?'1':'0',
			"idCuentaCatalogo":		$("#txtIdCuentaCatalogo").val(),
			"saldoInicial":			$("#txtSaldoInicial").val(),
			"tarjetaCredito":		$("#txtTarjetaCredito").val(),
			"dashboard":			document.getElementById('chkDefault').checked?'1':'0',
			"noDisponible":			document.getElementById('chkNodisponible').checked?'1':'0',
			"sie":					document.getElementById('chkSie').checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCuenta').html('');
			data=eval(data);

			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la cuenta',500,5000,'error',30,5);	
			$('#registrandoCuenta').html('');
		}
	});
}
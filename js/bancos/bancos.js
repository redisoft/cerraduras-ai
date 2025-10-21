
$(document).ready(function()
{
	$("#ventanaBancos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:750,
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
				registrarBanco();
			},
		},
		close: function() 
		{
			$("#formularioBancos").html(''); 
		}
	});
	
	$("#ventanaEditarBanco").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:160,
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
				editarBanco();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerBanco').html('');
		}
	});
});

function editarBanco()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del banco es requerido',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro del banco?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoBanco').html('<img src="'+ img_loader +'"/> Editando el banco, por favor espere...');
		},
		type:"POST",
		url:base_url+'bancos/editarBanco',
		data:
		{
			nombre:		$('#txtNombre').val(),
			idBanco:	$('#txtIdBanco').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('El registro del banco no tiene ningun cambio',500,5000,'error',30,3);
				$('#editandoBanco').html('');
				break;
				
				case "1":
					location.reload(true);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el banco',500,5000,'error',30,3);
			$("#editandoBanco").html('');
		}
	});
}

function obtenerBanco(idBanco)
{
	$('#ventanaEditarBanco').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerBanco').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el banco...');
		},
		type:"POST",
		url:base_url+'bancos/obtenerBanco',
		data:
		{
			idBanco:idBanco
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerBanco").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el banco',500,5000,'error',30,3);
			$("#obtenerBanco").html('');
		}
	});
}

function registrarBanco()
{
	if($('#txtNombreBanco').val()=="")
	{
		notify('El nombre del banco es requerido',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoBanco').html('<img src="'+ img_loader +'"/> Registrando el banco, por favor espere...');
		},
		type:"POST",
		url:base_url+'bancos/registrarBanco',
		data:$('#frmBancos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoBanco').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El banco se ha registrado correctamente',500,5000,'',30,5);
					$('#ventanaBancos').dialog('close');
					//obtenerCliente($('#txtClienteId').val())
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el banco',500,5000,'error',30,5);
			$("#registrandoBanco").html('');
		}
	});
}

function formularioBancos()
{
	$('#ventanaBancos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioBancos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el banco...');
		},
		type:"POST",
		url:base_url+'clientes/formularioBancos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioBancos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el banco',500,5000,'error',30,3);
			$("#formularioBancos").html('');
		}
	});
}

//CUENTAS
//---------------------------------------------------------------------------------------------
$(document).ready(function()
{
	$("#ventanaCuentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:750,
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
		height:250,
		width:750,
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
			$("#obtenerCuenta").html('');
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
		url:base_url+'clientes/obtenerCuentaCliente',
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
	
	if($("#txtCuenta").val()=="")
	{
		notify('El número de cuenta es incorrecto',500,5000,'error',30,15);
		return;										
	}
	
	if(!camposVacios($("#txtClabe").val()) || !longitudCadena($("#txtClabe").val(),18))
	{
		notify('La clabe debe contener 18 dígitos',500,5000,'error',30,15);
		return;										
	}
	
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
			"idCuenta":	$("#txtIdCuenta").val(),
			"idBanco":	$("#selectBancos").val(),
			"cuenta":	$("#txtCuenta").val(),
			"clabe":	$("#txtClabe").val(),
			"idEmisor": $("#selectEmisores").val(),
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
				notify('La cuenta se ha editado correctamente',500,5000,'',30,3);
				$('#ventanaEditarCuenta').dialog('close');
				obtenerCliente($('#txtClienteId').val(),'1')
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
		url:base_url+'clientes/formularioCuentasCliente',
		data:
		{
			idCliente:$('#txtClienteId').val()
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
	
	if(!camposVacios($("#txtCuenta").val()))
	{
		notify('El número de cuenta es incorrecto',500,5000,'error',30,15);
		return;										
	}
	
	if(!camposVacios($("#txtClabe").val()) || !longitudCadena($("#txtClabe").val(),18))
	{
		notify('La clabe debe contener 18 dígitos',500,5000,'error',30,15);
		return;										
	}

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
			"idBanco":	$("#selectBancos").val(),
			"cuenta":	$("#txtCuenta").val(),
			"clabe":	$("#txtClabe").val(),
			"idCliente":$("#txtClienteId").val(),
			"idEmisor":$("#selectEmisores").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCuenta').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
				
					notify(data[1],500,5000,'error',30,3);
				break;
				case "1":
					notify('La cuenta se ha registrado correctamente',500,5000,'',30,3);
					$('#ventanaCuentas').dialog('close');
					obtenerCliente($('#txtClienteId').val(),'1')
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la cuenta',500,5000,'error',30,3);	
			$('#registrandoCuenta').html('');
		}
	});
}


function borrarCuentaCliente(idCuenta)
{
	if(!confirm('¿Realmente desea borrar el registro de la cuenta?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoEditarClientes').html('<img src="'+ img_loader +'"/> Borrando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"bancos/borrarCuentaCliente",
		data:
		{
			"idCuenta":	idCuenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoEditarClientes').html('');
			
			switch(data)
			{
				case "0":
				
				notify('Error al borra la cuenta esta asociada a ventas  y/o compras',500,5000,'error',30,3);
				break;
				case "1":
				notify('La cuenta se ha borrado correctamente',500,5000,'',30,3);
				obtenerCliente($('#txtClienteId').val(),'1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la cuenta',500,5000,'error',30,3);	
			$('#cargandoEditarClientes').html('');
		}
	});
}

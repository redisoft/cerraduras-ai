function obtenerCuentaContable(idCuenta)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarCuentaContable').html('<img src="'+ img_loader +'"/>Obteniendo datos de la cuenta contable, por favor espere...');},
		type:"POST",
		url:base_url+'configuracion/obtenerCuentaContable',
		data:
		{
			"idCuenta": idCuenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarCuentaContable").html(data);
		},
		error:function(datos)
		{
			$("#cargarCuentaContable").html('');
			notify('Error al obtener la cuenta contable',500,5000,'error',30,3);
		}
	});				  	  
}

$(document).ready(function()
{
	for(i=1;i<100;i++)
	{
		$("#btnCuentaContable"+i).click(function(e)
		{
			$('#ventanaEditarCuentasContables').dialog('open');
		});
	}

	$("#ventanaEditarCuentasContables").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:580,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				var mensaje="";
		
				var URL=base_url+"configuracion/editarCuentaContable";
				
				if($("#txtNivel11").val()=="")
				{
					mensaje+="El nivel 1 es incorrecto <br />";										
				}
					
				if($("#txtClave11").val()=="")
				{
					mensaje+="La clave 1 es incorrecta <br />";										
				}

				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',30,12);
					return;
				}
				
				if(confirm('¿Realmente desea editar la cuenta contable?')==false)
				{
					return;
				}
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#editandoCuentas').html('<img src="'+ img_loader +'"/>Se esta editando la cuenta contable, por favor espere...');},
					type:"POST",
					url:URL,
					data:
					{
						"nivel1":   $("#txtNivel11").val(),
						"clave1":   $("#txtClave11").val(),
						"nivel2":   $("#txtNivel21").val(),
						"clave2":   $("#txtClave21").val(),
						"nivel3":   $("#txtNivel31").val(),
						"clave3":   $("#txtClave31").val(),
						"nivel4":   $("#txtNivel41").val(),
						"clave4":   $("#txtClave41").val(),
						"nombre":   $("#txtCuentaEditar").val(),
						"idCuenta": $("#txtIdCuenta").val(),
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$("#editandoCuentas").html('');
							notify('Error al editar la cuenta contable',500,5000,'error',30,3);
							break;
							case "1":
							window.location.href=base_url+"configuracion/cuentasContables";
							break;
						}
					},
					error:function(datos)
					{
						$("#editandoCuentas").html('');
						notify('Error al editar la cuenta contable',500,5000,'error',30,3);
					}
				});				  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
		}
	});
	
	$("#agregarCuentaContable").click(function(e)
	{
		$('#ventanaCuentasContables').dialog('open');
	});

	$("#ventanaCuentasContables").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				var mensaje="";
		
				var URL=base_url+"configuracion/agregarCuentaContable";
				
				if($("#txtNivel1").val()=="")
				{
					mensaje+="El nivel 1 es incorrecto <br />";										
				}
					
				if($("#txtClave1").val()=="")
				{
					mensaje+="La clave 1 es incorrecta <br />";										
				}

				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',30,12);
					return;
				}
				
				if(confirm('¿Realmente desea registrar la cuenta contable?')==false)
				{
					return;
				}
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#registrandoCuentas').html('<img src="'+ img_loader +'"/>Se esta registrando la cuenta contable, por favor espere...');},
					type:"POST",
					url:URL,
					data:
					{
						"nivel1":  $("#txtNivel1").val(),
						"clave1":  $("#txtClave1").val(),
						"nivel2":  $("#txtNivel2").val(),
						"clave2":  $("#txtClave2").val(),
						"nivel3":  $("#txtNivel3").val(),
						"clave3":  $("#txtClave3").val(),
						"nivel4":  $("#txtNivel4").val(),
						"clave4":  $("#txtClave4").val(),
						"nombre":  $("#txtCuenta").val(),
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$("#registrandoCuentas").html('');
							notify('Error al registrar la cuenta contable',500,5000,'error',30,3);
							break;
							case "1":
							window.location.href=base_url+"configuracion/cuentasContables";
							break;
						}
					},
					error:function(datos)
					{
						$("#registrandoCuentas").html('');
						notify('Error al registrar la cuenta contable',500,5000,'error',30,3);
					}
				});				  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
		}
	});
	
	$("#agregarTienda").click(function(e)
	{
		$('#ventanaAgregarTienda').dialog('open');
	});

	$("#ventanaAgregarTienda").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:680,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				
				
				var mensaje="";
		
				var URL=base_url+"configuracion/agregarTienda";
				
				if($("#txtNombreTienda").val()=="")
				{
					mensaje+="El nombre de la tienda es incorrecto <br />";										
				}
				
				if($("#txtTelefono").val()=="")
				{
					mensaje+="El telefono es incorrecto <br />";										
				}
				
				if($("#txtNombre").val()=="")
				{
					mensaje+="El nombre del encargado es incorrecto <br />";										
				}
				
				if($("#txtUsuario").val()=="")
				{
					mensaje+="El usuario es incorrecto <br />";										
				}
				
				if($("#txtPassword").val()=="")
				{
					mensaje+="El password es incorrecto <br />";										
				}
				
				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',30,0);
					return;
				}
				
				$("#registrandoTienda").fadeIn();
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#registrandoTienda').html('<img src="'+ img_loader +'"/> Espere por favor ...');},
					type:"POST",
					url:URL,
					data:
					{
						"nombreTienda":$("#txtNombreTienda").val(),
						"direccion":$("#txtDireccion").val(),
						"numero":$("#txtNumero").val(),
						"colonia":$("#txtColonia").val(),
						"codigoPostal":$("#txtCodigoPostal").val(),
						"ciudad":$("#txtCiudad").val(),
						"telefono":$("#txtTelefono").val(),
						
						"nombre":$("#txtNombre").val(),
						"paterno":$("#txtPaterno").val(),
						"materno":$("#txtMaterno").val(),
						"usuario":$("#txtUsuario").val(),
						"password":$("#txtPassword").val(),
						"email":$("#txtEmail").val(),
						
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$("#registrandoTienda").fadeOut();
							notify('Error al registrar la tienda',500,5000,'error',30,3);
							break;
							case "1":
							window.location.href=base_url+"configuracion/tiendas";
							break;
						}
					},
					error:function(datos)
					{
						$("#registrandoTienda").fadeOut();
						notify('Error al registrar la tienda',500,5000,'error',30,3);
					}
				});				  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#registrandoTienda").fadeOut();
		}
	});
});	

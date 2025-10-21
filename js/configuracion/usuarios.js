//USUARIOS
//------------------------------------------------------------------------------------------------//
$(document).ready(function()
{
	obtenerUsuarios();

	$('#txtCriterio').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerUsuarios();
		}
	});
	
	$(document).on("click", ".ajax-pagUsuarios > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerUsuarios";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio: 	$('#txtCriterio').val(),
				idRol: 		$('#selectRolBusqueda').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
	
});

function obtenerUsuarios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerUsuarios').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerUsuarios',
		data:
		{
			criterio: 	$('#txtCriterio').val(),
			idRol: 		$('#selectRolBusqueda').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerUsuarios").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerUsuarios").html('');
		}
	});//Ajax		
}


function formularioUsuarios()
{
	$('#ventanaRegistrarUsuario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioUsuario').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para registrar al usuario...');
		},
		type:"POST",
		url:base_url+'configuracion/formularioUsuarios',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioUsuario").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para registrar al usuario',500,5000,'error',30,3);
			$("#formularioUsuario").html('');
		}
	});//Ajax		
}

function registrarUsuario()
{
	var mensaje="";
				
	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre del usuario es incorrecto <br />";										
	}
	
	if(!camposVacios($("#txtUsuario").val()))
	{
		mensaje+="El usuario es incorrecto <br />";										
	}
	
	//pass=document.getElementById('txtPassword').value.length;
	
	if(document.getElementById('txtPassword').value.length<6)
	{
		mensaje+="El password debe tener al menos 6 caracteres <br />";										
	}
	
	if(!camposVacios($("#txtPassword").val()) || $("#txtPassword").val()!=$("#txtRepetirPassword").val())
	{
		mensaje+="Las contraseñas no coinciden <br />";										
	}
	
	if(!validarEmail($("#txtCorreo").val()))
	{
		mensaje+="El correo es incorrecto <br />";										
	}
	
	if($("#selectRol").val()=="0")
	{
		mensaje+="Seleccione el rol de usuario<br />";										
	}
	
	if(sistemaActivo!='cerraduras')
	{
		if($("#selectRol").val()=="2")
		{
			if($("#selectTiendas").val()=="0")
			{
				mensaje+="Seleccione la tienda<br />";	
			}
		}
	}

	if(!camposVacios($("#txtClaveCancelacion").val()) || document.getElementById('txtClaveCancelacion').value.length<6)
	{
		mensaje+="La clave de usuario debe tener al menos 6 caracteres <br />";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	promotor='0';
	
	if(sistemaActivo=='IEXE')
	{
		promotor	= document.getElementById('chkPromotor').checked?'1':'0'
	}
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoUsuario').html('<img src="'+ img_loader +'"/> Se esta registrando el usuario, por favor espere.');
		},
		type:"POST",
		url:base_url+"configuracion/registrarUsuario",
		data: $('#frmRegistrarUsuario').serialize()
		/*{
			"nombre":			$("#txtNombre").val(),
			"apellidoPaterno":	$("#txtPaterno").val(),
			"apellidoMaterno":	$("#txtMaterno").val(),
			"usuario":			$("#txtUsuario").val(),
			"password":			$("#txtPassword").val(),
			"correo":			$("#txtCorreo").val(),
			"idRol":			$("#selectRol").val(),
			"idTienda":			$("#selectRol").val()=="2"?$('#selectTiendas').val():0,
			"firma":			$("#txtFirma").val(),
			"claveDescuento":	$("#txtClaveDescuento").val(),
			"claveCancelacion":	$("#txtClaveCancelacion").val(),
			"promotor":			promotor,
			"vendedor":			$("#txtVendedor").val(),
		}*/,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoUsuario').html('');
			data	= eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					
				break;
				
				case "1":
					$('#ventanaRegistrarUsuario').dialog('close');
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					obtenerUsuarios();
				break;
				
				case "2":
					notify('El correo no es válido',500,5000,'error',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al usuario',500,5000,'error',30,5);
			$('#registrandoUsuario').html('');
		}
	});				  	  
}

$(document).ready(function()
{
	$("#ventanaRegistrarUsuario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:850,
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
				registrarUsuario();
			},
		},
		close: function() 
		{
			$("#formularioUsuario").html(''); 
		}
	});

	$("#ventanaEditarUsuario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:850,
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
				editarUsuario();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerUsuario').html('');
		}
	});
});

function criterioRol()
{
	if($('#selectRol').val()=="2")
	{
		$('#filaTienda').fadeIn();
	}
	else
	{
		$('#filaTienda').fadeOut();
	}
}

function obtenerUsuario(idUsuario)
{
	$('#ventanaEditarUsuario').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerUsuario').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar al usuario...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerUsuario',
		data:
		{
			idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerUsuario").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar al usuario',500,5000,'error',30,3);
			$("#obtenerUsuario").html('');
		}
	});//Ajax		
}

function editarUsuario()
{
	var mensaje="";
				
	if($("#txtNombre").val()=="")
	{
		mensaje+="El nombre del usuario no es correcto <br />";										
	}
	
	if($("#txtUsuario").val()=="")
	{
		mensaje+="El usuario es incorrecto <br />";										
	}
	
	if($("#txtPassword").val()!="" || $("#txtRepetirPassword").val()!="")
	{
		if($("#txtPassword").val()!=$("#txtRepetirPassword").val())
		{
			mensaje+="Las contraseñas no coinciden <br />";										
		}
	}
	
	if($("#txtCorreo").val()=="")
	{
		mensaje+="El correo es incorrecto <br />";										
	}
	
	if($("#selectRol").val()=="0")
	{
		mensaje+="Seleccione el rol de usuario<br />";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del usuario?')==false)
	{
		return;
	}
	
	promotor='0';
	
	if(sistemaActivo=='IEXE')
	{
		promotor	= document.getElementById('chkPromotor').checked?'1':'0'
	}
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoUsuario').html('<img src="'+ img_loader +'"/> Se esta editando el usuario, por favor espere.');
		},
		type:"POST",
		url:base_url+"configuracion/editarUsuario",
		data: $('#frmEditarUsuario').serialize(),
		/*data:
		{
			"nombre":			$("#txtNombre").val(),
			"apellidoPaterno":	$("#txtPaterno").val(),
			"apellidoMaterno":	$("#txtMaterno").val(),
			"usuario":			$("#txtUsuario").val(),
			"password":			$("#txtPassword").val(),
			"correo":			$("#txtCorreo").val(),
			"idRol":			$("#selectRol").val(),
			"idUsuario":		$("#txtIdUsuario").val(),
			"firma":			$("#txtFirma").val(),
			"claveDescuento":	$("#txtClaveDescuento").val(),
			"claveCancelacion":	$("#txtClaveCancelacion").val(),
			promotor:			promotor,
			"vendedor":			$("#txtVendedor").val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoUsuario').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar al usuario o no hubo cambios',500,5000,'error',30,3);
				break;
				
				case "1":
					$('#ventanaEditarUsuario').dialog('close');
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					obtenerUsuarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al usuario',500,5000,'error',30,3);
			$('#editandoUsuario').html('');
		}
	});				  	  
}

function borrarUsuario(idUsuario)
{
	var mensaje="";

	if(!confirm('¿Realmente desea desactivar el registro del usuario?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoUsuarios').html('<img src="'+ img_loader +'"/> Se esta desactivando el usuario, por favor espere.');
		},
		type:"POST",
		url:base_url+"configuracion/borrarUsuario",
		data:
		{
			idUsuario:			idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoUsuarios').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al desactivar al usuario o no hubo cambios',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El usuario se ha desactivado',500,5000,'',30,5);
					obtenerUsuarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al desactivar al usuario',500,5000,'error',30,3);
			$('#procesandoUsuarios').html('');
		}
	});				  	  
}

function reactivarUsuario(idUsuario)
{
	var mensaje="";

	if(!confirm('¿Realmente desea reactivar el registro del usuario?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoUsuarios').html('<img src="'+ img_loader +'"/> Se esta desactivando el usuario, por favor espere.');
		},
		type:"POST",
		url:base_url+"configuracion/reactivarUsuario",
		data:
		{
			idUsuario:			idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoUsuarios').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al reactivar al usuario o no hubo cambios',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El usuario se ha reactivado',500,5000,'',30,5);
					obtenerUsuarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al reactivar al usuario',500,5000,'error',30,3);
			$('#procesandoUsuarios').html('');
		}
	});				  	  
}

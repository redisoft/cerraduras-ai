rolUsuario	=0;
function obtenerRol(idRol)
{
	$('#ventanaEditarRoles').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRol').html('<img src="'+ img_loader +'"/> Se estan cargando los detalles del rol...');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerRol',
		data:
		{
			"idRol":idRol
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerRol").html(data);
		},
		error:function(datos)
		{
			$("#obtenerRol").html('');	
		}
	});
	
	rolUsuario	=idRol;
}

$(document).ready(function()
{
	$("#ventanaEditarRoles").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		width:1030,
		height:650,
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
				editarRol()
			},
			
		},
		close: function() 
		{
			$("#obtenerRol").html('');	
		}
	});
});

function editarRol()
{
	mensaje		= "";
	ban			= false;

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+='La descripción del rol es incorrecta <br />';
	}
	
	for(i=1;i<parseInt($('#txtIndice').val());i++)
	{
		if(document.getElementById('chkBoton'+i).checked) 
		{
			ban=true;
		
			break;
		}
	}
	
	if(!ban)
	{
		mensaje+='Seleccione al menos un permiso<br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el rol?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoRol').html('<img src="'+ img_loader +'"/> Editando el rol...');},
		type:"POST",
		url:base_url+"configuracion/editarRol",
		data:$('#frmRoles').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoRol').html('');
			
			window.location.href=base_url+"configuracion/roles";
			
			switch(data)
			{
				case "0":
					notify('Error al editar el rol',500,6000,"error",30,5);
					
				break;
				case "1":
					window.location.href=base_url+"configuracion/roles";
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el rol',500,6000,"error",30,5);
			$('#editandoRol').html('');
		}
	});
}

function formularioRoles()
{
	$('#ventanaAgregarRoles').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRoles').html('<img src="'+ img_loader +'"/> Obteniendo formulario para roles...');
		},
		type:"POST",
		url:base_url+'configuracion/formularioRoles',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioRoles").html(data);
		},
		error:function(datos)
		{
			$("#formularioRoles").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarRoles").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		width:1030,
		height:650,
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
				registrarRol()
			},
		},
		close: function() 
		{
			$('#agregandoRoles').html('');
		}
	});
});

function registrarRol()
{
	mensaje		= "";
	ban			= false;

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+='La descripción del rol es incorrecta <br />';
	}
	
	for(i=1;i<parseInt($('#txtIndice').val());i++)
	{
		if(document.getElementById('chkBoton'+i).checked) 
		{
			ban=true;
			break;
		}
	}
	
	if(!ban)
	{
		mensaje+='Seleccione al menos un permiso<br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el rol?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoRol').html('<img src="'+ img_loader +'"/> Registrando el rol...');},
		type:"POST",
		url:base_url+"configuracion/registrarRol",
		data:$('#frmRoles').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoRol').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,6000,"error",30,5);
					
				break;
				case "1":
				window.location.href=base_url+"configuracion/roles";
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el rol',500,6000,"error",30,5);
			$('#registrandoRol').html('');
		}
	});
}

function seleccionarTodosArchivos()
{
	b	= document.getElementById('chkTodo').checked?1:0;
	
	if(b==0)
	{
		$(".check").attr("checked", false);
		return;
	}
	
	if(b==1)
	{
		$(".check").attr("checked", true);
		return;
	}
	
	return;
}
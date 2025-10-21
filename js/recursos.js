

function catalogos()
{
	obtenerDepartamentos();
	obtenerPuestos();
}

//DEPARTAMENTOS
//-------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function()
{
	$("#ventanaDepartamentos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:180,
		width:440,
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
				agregarDepartamento();
			},
		},
		close: function() 
		{
			$("#formularioDepartamentos").html('');
		}
	});
});

function formularioDepartamentos()
{
	$("#ventanaDepartamentos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar departamentos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/formularioDepartamentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioDepartamentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para departamentos',500,5000,'error',2,5);
			$('#formularioDepartamentos').html('');
		}
	});					  	  
}

function agregarDepartamento()
{
	if($('#txtNombreDepartamento').val()=="")
	{
		notify('El nombre del departamento es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoDepartamento').html('<img src="'+ img_loader +'"/> Registrando el departamento, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/agregarDepartamento',
		data:
		{
			'nombre':$('#txtNombreDepartamento').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoDepartamento').html('');
			$('#txtNombreDepartamento').val('');
			notify('¡Registro correcto!',500,5000,'',30,10);
			obtenerDepartamentos();
		},
		error:function(datos)
		{
			notify('Error al registrar el departamento',500,5000,'error',0,0);
			$('#agregandoDepartamento').html('');
		}
	});					  	  
}

function obtenerDepartamentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo los departamentos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerDepartamentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDepartamentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los departamentos',500,5000,'error',2,5);
			$('#obtenerDepartamentos').html('');
		}
	});					  	  
}

//PUESTOS
//-------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function()
{
	$("#ventanaPuestos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:180,
		width:440,
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
				agregarPuesto();
			},
		},
		close: function() 
		{
			$("#formularioPuestos").html('');
		}
	});
});

function formularioPuestos()
{
	$("#ventanaPuestos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPuestos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario registrar puestos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/formularioPuestos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPuestos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para puestos',500,5000,'error',2,5);
			$('#formularioNombres').html('');
		}
	});					  	  
}

function agregarPuesto()
{
	if($('#txtNombrePuesto').val()=="")
	{
		notify('El nombre es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoPuesto').html('<img src="'+ img_loader +'"/> Registrando el puesto, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/agregarPuesto',
		data:
		{
			'nombre':$('#txtNombrePuesto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoPuesto').html('');
			$('#txtNombrePuesto').val('');
			notify('¡Registro correcto!',500,5000,'',30,10);
			obtenerPuestos();
		},
		error:function(datos)
		{
			notify('Error al registrar el puesto',500,5000,'error',0,0);
			$('#agregandoPuesto').html('');
		}
	});					  	  
}

function obtenerPuestos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPuestos').html('<img src="'+ img_loader +'"/> Obteniendo los puestos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerPuestos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPuestos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los puestos',500,5000,'error',2,5);
			$('#obtenerPuestos').html('');
		}
	});					  	  
}

//FORMULARIO PERSONAL
function formularioPersonal()
{
	$("#ventanaPersonal").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPersonal').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para personal, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/formularioPersonal',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPersonal').html(data);
			catalogos();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para personal',500,5000,'error',2,5);
			$('#formularioPersonal').html('');
		}
	});					  	  
}

$(document).ready(function()
{
	$("#txtBuscarPersonal").autocomplete(
	{
		source:base_url+'configuracion/obtenerPersonal',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'administracion/recursosHumanos/'+ui.item.idPersonal;
		}
	});


	$("#ventanaPersonal").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:950,
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
				agregarPersonal();
			},
		},
		close: function() 
		{
			$("#formularioPersonal").html('');
		}
	});
	
	$("#ventanaEditarPersonal").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:950,
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
				editarPersonal();
			},
		},
		close: function() 
		{
			$("#obtenerPersonal").html('');
		}
	});
});

function obtenerPersonal(idPersonal)
{
	$("#ventanaEditarPersonal").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPersonal').html('<img src="'+ img_loader +'"/> Obteniendo detalles del personal, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerPersonal',
		data:
		{
			idPersonal:idPersonal
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPersonal').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles del personal',500,5000,'error',2,5);
			$('#obtenerPersonal').html('');
		}
	});					  	  
}

//AGREGAR EL PERSONAL
function editarPersonal()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre es incorrecto <br />";
	}
	
	if(!comprobarNumeros($('#txtNumeroAcceso').val()) || parseInt($('#txtNumeroAcceso').val())<100000)
	{
		mensaje+="El número de acceso es incorrecto <br />";
	}
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}

	if($('#selectDepartamento').val()=="0")
	{
		mensaje+="Seleccione el departamento <br />";
	}
	
	if($('#selectPuestos').val()=="0")
	{
		mensaje+="Seleccione el puesto <br />";
	}
	
	if(Solo_Numerico($('#txtSalario').val())=="")
	{
		mensaje+="El salario por dia es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(confirm("¿Realmente desea editar el registro personal?"))
	{
		document.forms['frmPersonal'].submit();
	}
}

//AGREGAR EL PERSONAL
function agregarPersonal()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre es incorrecto <br />";
	}
	
	if(!comprobarNumeros($('#txtNumeroAcceso').val()) || parseInt($('#txtNumeroAcceso').val())<100000)
	{
		mensaje+="El número de acceso es incorrecto <br />";
	}
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}

	if($('#selectDepartamento').val()=="0")
	{
		mensaje+="Seleccione el departamento <br />";
	}
	
	if($('#selectPuestos').val()=="0")
	{
		mensaje+="Seleccione el puesto <br />";
	}
	
	if(Solo_Numerico($('#txtSalario').val())=="")
	{
		mensaje+="El salario por dia es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(confirm("¿Realmente desea registrar el personal?"))
	{
		document.forms['frmPersonal'].submit();
	}
}



//ESTATUS
//-------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function()
{
	$("#ventanaEstatus").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:180,
		width:440,
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
				registrarEstatus();
			},
		},
		close: function() 
		{
			$("#formularioEstatus").html('');
		}
	});
});

function formularioEstatus()
{
	$("#ventanaEstatus").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEstatus').html('<img src="'+ img_loader +'"/> Obteniendo el formulario, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/formularioEstatus',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEstatus').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',2,5);
			$('#formularioEstatus').html('');
		}
	});					  	  
}

function obtenerEstatus()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEstatus').html('<img src="'+ img_loader +'"/> Obteniendo los estatus, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerEstatus',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEstatus').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los estatus',500,5000,'error',2,5);
			$('#obtenerEstatus').html('');
		}
	});					  	  
}

function registrarEstatus()
{
	if($('#txtEstatus').val()=="")
	{
		notify('El nombre del estatus es incorrecto',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoEstatus').html('<img src="'+ img_loader +'"/> Registrando el estatus, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/registrarEstatus',
		data:
		{
			'nombre':$('#txtEstatus').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoEstatus').html('');
			$('#txtEstatus').val('');
			notify('¡Registro correcto!',500,5000,'',30,10);
			obtenerEstatus();
		},
		error:function(datos)
		{
			notify('Error al registrar el estatus',500,5000,'error',0,0);
			$('#registrandoEstatus').html('');
		}
	});					  	  
}

function borrarPersonal(idPersonal)
{
	if(!confirm('¿Realmente desea borrar el personal?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoPersonal').html('<img src="'+ img_loader +'"/> Borrando el personal');},
		type:"POST",
		url:base_url+"administracion/borrarPersonal",
		data:
		{
			idPersonal:idPersonal
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPersonal').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					window.location.href=base_url+'administracion/recursosHumanos'
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la requisición',500,4000,"error",30,5);
			$("#procesandoPersonal").html('');	
		}
	});				
}

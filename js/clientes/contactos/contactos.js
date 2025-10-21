$(document).ready(function()
{
	$("#ventanaAgregarContacto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				registrarContactoCliente()		  	  
			},
		},
		close: function() 
		{
			$("#formularioContacto").html('');
		}
	});
	
	$("#ventanaEditarContacto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				editarContactoCliente()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerContacto").html('');
		}
	});
});

function formularioContacto()
{
	$('#ventanaAgregarContacto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioContacto').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de contactos...');
		},
		type:"POST",
		url:base_url+"ficha/formularioContacto",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioContacto').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de contactos',500,5000,'error',5,5);
			$("#formularioContacto").html('');	
		}
	});
}

function obtenerContactos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContactos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de contactos...');
		},
		type:"POST",
		url:base_url+"ficha/obtenerContactos",
		data:
		{
			idCliente: $('#txtClienteId').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de contactos',500,5000,'error',5,5);
			$("#obtenerContactos").html('');	
		}
	});
}

function registrarContactoCliente()
{
	var mensaje="";

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre es incorrecto<br />"
	}
	
	if (!camposVacios($("#txtTelefonoContacto").val())) 
	{
		mensaje+="El teléfono es incorrecto<br />";										
	}
	
	if(!camposVacios($("#txtEmail").val()))
	{
		mensaje+="El email es incorrecto<br />";
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
			$('#agregandoContacto').html('<img src="'+ img_loader +'"/> Se esta registrando el contacto...');
		},
		type:"POST",
		url:base_url+"clientes/registrarContactoCliente",
		data:
		{
			"nombre":		$("#txtNombre").val(),
			"telefono":		$("#txtTelefonoContacto").val(),
			"email":		$("#txtEmail").val(),
			"direccion":	$("#txtDepartamento").val(),
			"extension":	$("#extension").val(),
			"idCliente":	$("#txtClienteId").val(),
			"puesto":		$("#txtPuesto").val(),
			
			"lada":			$("#txtLadaTelefonoContacto").val(),
			"ladaMovil1":	$("#txtLadaMovil").val(),
			"movil1":		$("#txtMovil").val(),
			"ladaMovil2":	$("#txtLadaMovil2").val(),
			"movil2":		$("#txtMovil2").val(),
			"ladaNextel":	$("#txtLadaNextel").val(),
			"nextel":		$("#txtNextel").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#agregandoContacto").html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerContactos();
					$('#ventanaAgregarContacto').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al contacto',500,5000,'error',30,5);
			$("#agregandoContacto").html('');
		}
	});		
}

function editarContactoCliente()
{
	var mensaje	= "";

	if(!camposVacios($("#txtNombre").val()))
	{
		mensaje+="El nombre es incorrecto<br />"
	}
	
	if (!camposVacios($("#txtTelefonoEditar").val())) 
	{
		mensaje+="Error en el número telefonico<br />";										
	}
	
	if(!camposVacios($("#txtEmail").val()))
	{
		mensaje+="El email es incorrecto<br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar la información del contacto?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{	
			$('#editandoContacto').html('<img src="'+ img_loader +'"/> Se esta editando el contacto...');
		},
		type:"POST",
		url:base_url+"ficha/editarContacto",
		data:
		{
			"nombre":		$("#txtNombre").val(),
			"telefono":		$("#txtTelefonoEditar").val(),
			"email":		$("#txtEmail").val(),
			"direccion":	$("#txtDepartamento").val(),
			"extension":	$("#txtExtension").val(),
			"idContacto":	$("#txtIdContacto").val(),
			"puesto":		$("#txtPuestoEditar").val(),
			"lada":			$("#txtLadaTelefonoContactoEditar").val(),
			"ladaMovil1":	$("#txtLadaMovilEditar").val(),
			"movil1":		$("#txtMovilEditar").val(),
			"ladaMovil2":	$("#txtLadaMovil2Editar").val(),
			"movil2":		$("#txtMovil2Editar").val(),
			"ladaNextel":	$("#txtLadaNextelEditar").val(),
			"nextel":		$("#txtNextelEditar").val(),
			"idCliente":	$("#txtClienteId").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoContacto").html('');
			
			switch(data)
			{
				case "0":
					notify('El registro del contacto no se ha editado',500,5000,'error',30,5);
				break;
				
				case "1":
					
					//$('#txtEmailContactoSeguimiento').val($("#txtEmail").val());
					
					notify('El registro del contacto se ha editado correctamente',500,5000,'',30,5);
					obtenerContactos();
					$('#ventanaEditarContacto').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al contacto',500,5000,'error',5,5);
			$("#editandoContacto").html('');
		}
	});		
}

function obtenerContacto(idContacto)
{
	$('#ventanaEditarContacto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContacto').html('<img src="'+ img_loader +'"/> Obteniendo información del contacto...');
		},
		type:"POST",
		url:base_url+"ficha/obtenerContacto",
		data:
		{
			"idContacto":idContacto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContacto').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContacto").html('');	
		}
	});	
}

function borrarContactoCliente(idContacto)
{
	if(!confirm('¿Realmente desea editar la información del contacto?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{	
			$('#procesandoContactos').html('<img src="'+ img_loader +'"/> Borrando registro del contacto...');
		},
		type:"POST",
		url:base_url+"ficha/borrarContacto",
		data:
		{
			"idContacto":		idContacto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#procesandoContactos").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro del contacto',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro del contacto se ha borrado correctamente',500,5000,'',30,5);
					$("#filaContacto"+idContacto).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al contacto',500,5000,'error',5,5);
			$("#procesandoContactos").html('');
		}
	});		
}

//EDITAR EL CONTACTO DEL CLIENTE
$(document).ready(function()
{
	$("#ventanaEditarContactoCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				editarClienteContacto()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerContactoCliente").html('');
		}
	});
});

function obtenerContactoCliente(idCliente)
{
	$('#ventanaEditarContactoCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContacto').html('<img src="'+ img_loader +'"/> Obteniendo información del contacto...');
		},
		type:"POST",
		url:base_url+"ficha/obtenerContactoCliente",
		data:
		{
			"idCliente":idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactoCliente').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContactoCliente").html('');	
		}
	});	
}

function editarClienteContacto()
{
	var mensaje	= "";

	if(!confirm('¿Realmente desea editar la información del cliente?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{	
			$('#editandoContactoCliente').html('<img src="'+ img_loader +'"/> Se esta editando el contacto...');
		},
		type:"POST",
		url:base_url+"ficha/editarContactoCliente",
		data:
		{
			"telefono":		$("#txtTelefonoEditar").val(),
			"email":		$("#txtEmail").val(),
			"lada":			$("#txtLadaTelefonoContactoEditar").val(),
			"ladaMovil":	$("#txtLadaMovilEditar").val(),
			"idCliente":	$("#txtIdClienteContacto").val(),
			"movil":		$("#txtMovilEditar").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoContactoCliente").html('');
			
			switch(data)
			{
				case "0":
					notify('El registro del contacto no se ha editado',500,5000,'error',30,5);
				break;
				
				case "1":

					notify('El registro del contacto se ha editado correctamente',500,5000,'',30,5);
					
					
					
					telefono=$("#txtTelefonoEditar").val()+' '+$("#txtMovilEditar").val();
					
					$('#txtEmailContactoSeguimiento').val($("#txtEmail").val());
					$('#txtTelefonoContactoSeguimiento').val(telefono.trim());
					
					$('#ventanaEditarContactoCliente').dialog('close');
					
					if(obtenerNumeros($('#txtIdSeguimiento').val())>0)
					{
						//obtenerSeguimientoDiario($('#txtIdSeguimiento').val());
						obtenerContactos();
					}
					else
					{
						obtenerContactos();
					}
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al contacto',500,5000,'error',5,5);
			$("#editandoContactoCliente").html('');
		}
	});		
}
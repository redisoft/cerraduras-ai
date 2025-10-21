//PARA LAS PERCEPCIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaRegistrarEmpleado").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:640,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				registrarEmpleado();
			},
		},
		close: function() 
		{
			$('#formularioEmpleados').html('');
		}
	});
	
	$("#ventanaEditarEmpleado").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:640,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Editar': function() 
			{
				editarEmpleado();
			},
		},
		close: function() 
		{
			$('#obtenerEmpleado').html('');
		}
	});
	
	//$('.ajax-pagEmpleado > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagEmpleado > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerEmpleado";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarEmpleado').val(),
				agregar:	$('#txtAgregarEmpleados').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo la lista de empleados'+esperar);},
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

function obtenerEmpleados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEmpleados').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de empleados'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerEmpleados',
		data:
		{
			criterio:	$('#txtBuscarEmpleado').val(),
			agregar:	$('#txtAgregarEmpleados').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerEmpleados").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de empleados'+conexion,500,5000,'error',30,3);
			$("#obtenerEmpleados").html('');
		}
	});		
}

function formularioEmpleados()
{
	$("#ventanaRegistrarEmpleado").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEmpleados').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para empleados'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioEmpleados',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEmpleados").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para empleados'+conexion,500,5000,'error',30,3);
			$("#formularioEmpleados").html('');
		}
	});		
}

function obtenerEmpleado(idEmpleado)
{
	$("#ventanaEditarEmpleado").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEmpleado').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo al empleado'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerEmpleado',
		data:
		{
			idEmpleado:idEmpleado
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerEmpleado").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener al empleado'+conexion,500,5000,'error',30,3);
			$("#obtenerEmpleado").html('');
		}
	});		
}

function editarEmpleado()
{
	mensaje		=	"";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+='La clave es incorrecta <br />';
	}
	
	if($('#txtNumeroEmpleado').val()=="")
	{
		mensaje+='El número de empleado es incorrecto <br />';
	}
	
	if($('#txtCurp').val()=="")
	{
		mensaje+='La curp es incorrecta <br />';
	}
	
	if($('#txtRfc').val()=="")
	{
		mensaje+='El rfc es incorrecto <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro del empleado?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEmpleado').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando empleado'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/editarEmpleado',
		data:$('#frmEmpleados').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEmpleado').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar al empleado o no hubo cambios en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
				notify('El empleado se ha editado correctamente',500,5000,'',30,3);
				obtenerEmpleados();
				$("#ventanaEditarEmpleado").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al empleado',500,5000,'error',30,3);
			$("#editandoEmpleado").html('');
		}
	});		
}

function registrarEmpleado()
{
	mensaje		=	"";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+='La clave es incorrecta <br />';
	}
	
	if($('#txtNumeroEmpleado').val()=="")
	{
		mensaje+='El número de empleado es incorrecto <br />';
	}
	
	if($('#txtCurp').val()=="")
	{
		mensaje+='La curp es incorrecta <br />';
	}
	
	if($('#txtRfc').val()=="")
	{
		mensaje+='El rfc es incorrecto <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoEmpleado').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando empleado'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/registrarEmpleado',
		data:$('#frmEmpleados').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoEmpleado').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
				notify('El empleado se ha registrado correctamente',500,5000,'',30,3);
				obtenerEmpleados();
				$("#ventanaRegistrarEmpleado").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al empleado',500,5000,'error',30,3);
			$("#registrandoEmpleado").html('');
		}
	});		
}

function borrarEmpleado(idEmpleado)
{
	if(!confirm('¿Realmente desea borrar el registro del empleado?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando al empleado'+esperar);
		},
		type:"POST",
		url:base_url+"nomina/borrarEmpleado",
		data:
		{
			"idEmpleado":		idEmpleado,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar al empleado',500,5000,'error',30,3);
				break;
				
				case "1":
				obtenerEmpleados();
				notify('El empleado se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al empleado',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
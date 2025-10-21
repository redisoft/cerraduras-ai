//PARA LOS PUESTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaRegistrarDepartamento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:500,
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
				registrarDepartamento();
			},
		},
		close: function() 
		{
			$('#formularioDepartamentos').html('');
		}
	});
	
	$("#ventanaEditarDepartamento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:500,
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
				editarDepartamento();
			},
		},
		close: function() 
		{
			$('#obtenerDepartamento').html('');
		}
	});
});

function obtenerDepartamentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepartamentos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de departamentos'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerDepartamentos',
		data:
		{
			criterio:	$('#txtBuscarDepartamento').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDepartamentos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de departamentos'+conexion,500,5000,'error',30,3);
			$("#obtenerDepartamentos").html('');
		}
	});		
}

function formularioDepartamentos()
{
	$("#ventanaRegistrarDepartamento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDepartamentos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para departamentos'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioDepartamentos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioDepartamentos").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para departamentos'+conexion,500,5000,'error',30,3);
			$("#formularioDepartamentos").html('');
		}
	});		
}

function obtenerDepartamento(idDepartamento)
{
	$("#ventanaEditarDepartamento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepartamento').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el departamento'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerDepartamento',
		data:
		{
			idDepartamento:idDepartamento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDepartamento").html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el departamento'+conexion,500,5000,'error',30,3);
			$("#obtenerDepartamento").html('');
		}
	});		
}

function editarDepartamento()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del departamento es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el departamento?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDepartamento').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando el departamento'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/editarDepartamento',
		data:
		{
			nombre:		$('#txtNombre').val(),
			idDepartamento:	$('#txtIdDepartamento').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoDepartamento').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar el departamento o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
				notify('El departamento se ha editado correctamente',500,5000,'',30,3);
				obtenerDepartamentos();
				$("#ventanaEditarDepartamento").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el departamento'+conexion,500,5000,'error',30,3);
			$("#editandoDepartamento").html('');
		}
	});		
}

function registrarDepartamento()
{
	if($('#txtNombre').val()=="")
	{
		notify('El nombre del departamento es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoDepartamento').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando el departamento'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/registrarDepartamento',
		data:
		{
			nombre:	$('#txtNombre').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoDepartamento').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
				notify('El departamento se ha registrado correctamente',500,5000,'',30,3);
				obtenerDepartamentos();
				$("#ventanaRegistrarDepartamento").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el departamento',500,5000,'error',30,3);
			$("#registrandoDepartamento").html('');
		}
	});		
}

function borrarDepartamento(idDepartamento)
{
	if(!confirm('¿Realmente desea borrar el registro del departamento?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando el departamento'+esperar);
		},
		type:"POST",
		url:base_url+"nomina/borrarDepartamento",
		data:
		{
			"idDepartamento":		idDepartamento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al borrar el departamento, esta asociado a empleados',500,5000,'error',30,3);
				$('#procesandoInformacion').html('');
				break;
				
				case "1":
				$('#procesandoInformacion').html('');
				obtenerDepartamentos();
				notify('El departamento se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al departamento',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
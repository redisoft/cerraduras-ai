//SERVICIOS
//=========================================================================================================================================//
function obtenerProgramas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProgramas').html('<img src="'+ img_loader +'"/> Obteniendo la lista de Programas.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProgramas',
		data:
		{
			criterio: $('#txtBuscarPrograma').val()
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$("#obtenerProgramas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Programas',500,5000,'error',30,3);
			$("#obtenerProgramas").html('');
		}
	});
}

function formularioProgramas()
{
	$('#ventanaProgramas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProgramas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar el programa..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioProgramas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$("#formularioProgramas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar el programa',500,5000,'error',30,3);
			$("#formularioProgramas").html('');
		}
	});
}

function registrarProgramas()
{
	mensaje="";
	
	if(!camposVacios($('#txtPrograma').val()))
	{
		mensaje+="El nombre del programa es incorrecto <br />";
	}
	
	diaPago	= obtenerNumeros($('#txtDiaPago').val());
	
	if(diaPago==0 || diaPago>31)
	{
		mensaje+="El día de pago es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProgramas').html('<img src="'+ img_loader +'"/> Registrandos el programa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarProgramas',
		data:
		{
			nombre: 					$('#txtPrograma').val(),
			cantidadInscripcion: 		$('#txtPeriodicidadInscripcion').val(),
			cantidadColegiatura: 		$('#txtPeriodicidadColegiatura').val(),
			cantidadReinscripcion: 		$('#txtPeriodicidadReinscripcion').val(),
			
			diaPago: 					$('#txtDiaPago').val(),
			idPeriodo: 					$('#selectPeriodo').val(),
			idGrado: 					$('#selectGrados').val(),
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#registrandoProgramas').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					obtenerProgramas();
					$('#ventanaProgramas').dialog('close');
					notify(data[1],500,3000,'',30,5);
					$('#txtProgramasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el programa',500,5000,'error',30,3);
			$("#registrandoProgramas").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaProgramas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
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
				registrarProgramas();
			},
		},
		close: function() 
		{
			$("#formularioProgramas").html(''); 
			$('#The_colorPicker').fadeOut();
		}
	});

	$("#ventanaEditarProgramas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
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
				editarProgramas();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerProgramasEditar').html('');
			$('#The_colorPicker').fadeOut();
		}
	});
	
	$(document).on("click", ".ajax-pagProgramas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerProgramas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarPrograma').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerProgramas').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
			},
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

function obtenerProgramasEditar(idPrograma)
{
	$('#ventanaEditarProgramas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el programa..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerProgramasEditar',
		data:
		{
			idPrograma:idPrograma
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$("#obtenerProgramasEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el programa',500,5000,'error',30,3);
			$("#obtenerProgramasEditar").html('');
		}
	});
}

function editarProgramas()
{
	mensaje="";
	
	if(!camposVacios($('#txtPrograma').val()))
	{
		mensaje+="El nombre del programa es incorrecto <br />";
	}
	
	diaPago	= obtenerNumeros($('#txtDiaPago').val());
	
	if(diaPago==0 || diaPago>31)
	{
		mensaje+="El día de pago es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del programa?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoProgramas').html('<img src="'+ img_loader +'"/> Editando el programa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarProgramas',
		data:
		{
			nombre: 					$('#txtPrograma').val(),
			idPrograma: 				$('#txtIdPrograma').val(),
			cantidadInscripcion: 		$('#txtPeriodicidadInscripcion').val(),
			cantidadColegiatura: 		$('#txtPeriodicidadColegiatura').val(),
			cantidadReinscripcion: 		$('#txtPeriodicidadReinscripcion').val(),
			editarAlumnos: 				document.getElementById('chkProgramaAlumnos').checked?'1':'0',
			
			diaPago: 					$('#txtDiaPago').val(),
			idPeriodo: 					$('#selectPeriodo').val(),
			idGrado: 					$('#selectGrados').val(),
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#editandoProgramas').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarProgramas').dialog('close');
					notify('El Programa se ha editado correctamente',500,3000,'',30,5);
					obtenerProgramas();
					$('#txtProgramasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el programa',500,5000,'error',30,3);
			$("#editandoProgramas").html('');
		}
	});		
}

function borrarProgramas(idPrograma)
{
	if(!confirm('¿Realmente desea borrar el registro del programa?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoProgramas').html('<img src="'+ img_loader +'"/> Borrando el programa..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarProgramas',
		data:
		{
			idPrograma: 	idPrograma,
		},
		datatype:"html",
		success:function(data, textProgramas)
		{
			$('#procesandoProgramas').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el programa',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El Programa se ha borrado correctamente',500,5000,'',30,5);
					$('#filaProgramas'+idPrograma).remove();
					$('#txtProgramasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el programa',500,5000,'error',30,3);
			$("#procesandoProgramas").html('');
		}
	});		
}
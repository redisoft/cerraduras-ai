//PERIODOS
//=========================================================================================================================================//
function obtenerPeriodos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPeriodos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de registros.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPeriodos',
		data:
		{
			criterio: $('#txtBuscarPeriodo').val()
		},
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$("#obtenerPeriodos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerPeriodos").html('');
		}
	});
}

function formularioPeriodos()
{
	$('#ventanaPeriodos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPeriodos').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar ..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioPeriodos',
		data:
		{

		},
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$("#formularioPeriodos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para el registro',500,5000,'error',30,3);
			$("#formularioPeriodos").html('');
		}
	});
}

function comprobarProgramasRegistro()
{
	numero	= obtenerNumeros($('#txtNumeroProgramas').val());
	
	for(i=1;i<=numero;i++) 
	{
		if(document.getElementById('chkPrograma'+i).checked) return true;
	}
	
	return false;
}

function registrarPeriodos()
{
	mensaje="";
	
	if(!camposVacios($('#txtPeriodo').val()))
	{
		mensaje+="El nombre es incorrecto <br />";
	}
	
	if($('#txtFechaInicialPeriodo').val()>$('#txtFechaFinalPeriodo').val())
	{
		mensaje+="Las fechas son incorrectas <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPeriodos').html('<img src="'+ img_loader +'"/> Realizando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarPeriodos',
		data:$('#frmPeriodo').serialize(),
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$('#registrandoPeriodos').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					obtenerPeriodos();
					$('#ventanaPeriodos').dialog('close');
					notify(data[1],500,3000,'',30,5);
					$('#txtPeriodosEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el periodo',500,5000,'error',30,3);
			$("#registrandoPeriodos").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaPeriodos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				registrarPeriodos();
			},
		},
		close: function() 
		{
			$("#formularioPeriodos").html(''); 
			$('#The_colorPicker').fadeOut();
		}
	});

	$("#ventanaEditarPeriodos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
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
				editarPeriodos();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerPeriodosEditar').html('');
			$('#The_colorPicker').fadeOut();
		}
	});
	
	$(document).on("click", ".ajax-pagPeriodos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPeriodos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarPeriodo').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPeriodos').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function obtenerPeriodosEditar(idPeriodo)
{
	$('#ventanaEditarPeriodos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPeriodosEditar').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerPeriodosEditar',
		data:
		{
			idPeriodo:idPeriodo
		},
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$("#obtenerPeriodosEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar',500,5000,'error',30,3);
			$("#obtenerPeriodosEditar").html('');
		}
	});
}

function editarPeriodos()
{
	mensaje="";
	
	if(!camposVacios($('#txtPeriodo').val()))
	{
		mensaje+="El nombre  es incorrecto <br />";
	}
	
	if($('#txtFechaInicialPeriodo').val()>$('#txtFechaFinalPeriodo').val())
	{
		mensaje+="Las fechas son incorrectas <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPeriodos').html('<img src="'+ img_loader +'"/> Editando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/editarPeriodos',
		data:$('#frmEditarPeriodo').serialize(),
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$('#editandoPeriodos').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarPeriodos').dialog('close');
					notify('El registro se ha editado correctamente',500,3000,'',30,5);
					obtenerPeriodos();
					$('#txtPeriodosEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoPeriodos").html('');
		}
	});		
}

function borrarPeriodos(idPeriodo)
{
	if(!confirm('¿Realmente desea borrar el registro ?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPeriodos').html('<img src="'+ img_loader +'"/> Borrando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarPeriodos',
		data:
		{
			idPeriodo: 	idPeriodo,
		},
		datatype:"html",
		success:function(data, textPeriodos)
		{
			$('#procesandoPeriodos').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaPeriodos'+idPeriodo).remove();
					$('#txtPeriodosEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#procesandoPeriodos").html('');
		}
	});		
}
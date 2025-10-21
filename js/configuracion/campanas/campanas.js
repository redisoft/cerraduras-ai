//SERVICIOS
//=========================================================================================================================================//
function obtenerCampanas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCampanas').html('<img src="'+ img_loader +'"/> Obteniendo la lista de campañas.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCampanas',
		data:
		{
			criterio: $('#txtBuscarCampana').val()
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$("#obtenerCampanas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerCampanas").html('');
		}
	});
}

function formularioCampanas()
{
	$('#ventanaCampanas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCampanas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la campaña..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioCampanas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$("#formularioCampanas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para el registro',500,5000,'error',30,3);
			$("#formularioCampanas").html('');
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

function registrarCampanas()
{
	mensaje="";
	
	if(!camposVacios($('#txtCampana').val()))
	{
		mensaje+="El nombre es incorrecto <br />";
	}
	
	if($('#txtFechaInicialCampana').val()>$('#txtFechaFinalCampana').val())
	{
		mensaje+="Las fechas son incorrectas <br />";
	}
	
	if(!comprobarProgramasRegistro())
	{
		mensaje+="Seleccione al menos un programa <br />";
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
			$('#registrandoCampanas').html('<img src="'+ img_loader +'"/> Realizando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarCampanas',
		data:$('#frmCampana').serialize(),
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#registrandoCampanas').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					obtenerCampanas();
					$('#ventanaCampanas').dialog('close');
					notify(data[1],500,3000,'',30,5);
					$('#txtCampanasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el campana',500,5000,'error',30,3);
			$("#registrandoCampanas").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaCampanas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
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
				registrarCampanas();
			},
		},
		close: function() 
		{
			$("#formularioCampanas").html(''); 
			$('#The_colorPicker').fadeOut();
		}
	});

	$("#ventanaEditarCampanas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
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
				editarCampanas();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerCampanasEditar').html('');
			$('#The_colorPicker').fadeOut();
		}
	});
	
	$(document).on("click", ".ajax-pagCampanas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerCampanas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarCampana').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerCampanas').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function obtenerCampanasEditar(idCampana)
{
	$('#ventanaEditarCampanas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCampanasEditar').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCampanasEditar',
		data:
		{
			idCampana:idCampana
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$("#obtenerCampanasEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar',500,5000,'error',30,3);
			$("#obtenerCampanasEditar").html('');
		}
	});
}

function editarCampanas()
{
	mensaje="";
	
	if(!camposVacios($('#txtCampana').val()))
	{
		mensaje+="El nombre  es incorrecto <br />";
	}
	
	if($('#txtFechaInicialCampana').val()>$('#txtFechaFinalCampana').val())
	{
		mensaje+="Las fechas son incorrectas <br />";
	}
	
	if(!comprobarProgramasRegistro())
	{
		mensaje+="Seleccione al menos un programa <br />";
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
			$('#editandoCampanas').html('<img src="'+ img_loader +'"/> Editando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/editarCampanas',
		data:$('#frmEditarCampana').serialize(),
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#editandoCampanas').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarCampanas').dialog('close');
					notify('El registro se ha editado correctamente',500,3000,'',30,5);
					obtenerCampanas();
					$('#txtCampanasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoCampanas").html('');
		}
	});		
}

function borrarCampanas(idCampana)
{
	if(!confirm('¿Realmente desea borrar el registro ?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCampanas').html('<img src="'+ img_loader +'"/> Borrando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarCampanas',
		data:
		{
			idCampana: 	idCampana,
		},
		datatype:"html",
		success:function(data, textCampanas)
		{
			$('#procesandoCampanas').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaCampanas'+idCampana).remove();
					$('#txtCampanasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#procesandoCampanas").html('');
		}
	});		
}
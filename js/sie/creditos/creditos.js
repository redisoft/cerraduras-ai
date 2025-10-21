$(document).ready(function()
{
	$("#ventanaCreditos").dialog(
	{
		autoOpen:false,
		height:380,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Agregar: function() 
			{
				registrarCredito(0)			 
			},
			Guardar: function() 
			{
				registrarCredito(1)		 
			},
		},
		close: function() 
		{
			$("#formularioCreditos").html('');
		}
	});
	
	$("#ventanaEditarCreditos").dialog(
	{
		autoOpen:false,
		height:380,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				editarCredito()			 
			},
		},
		close: function() 
		{
			$("#obtenerCredito").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagCreditos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerCreditos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				
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

function obtenerCreditos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCreditos').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+'creditos/obtenerCreditos',
		data:
		{
			//fecha:			$('#txtFecha').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCreditos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerCreditos').html('');
		}
	});		
}

function formularioCreditos()
{
	$("#ventanaCreditos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCreditos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'creditos/formularioCreditos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioCreditos").html(data);
			$('#txtFuente').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioCreditos").html('');
		}
	});		
}

function registrarCredito(cerrar)
{
	mensaje="";
	
	if(!camposVacios($('#txtFuente').val()))
	{
		mensaje+="La fuente es requerida <br />";
	}
	
	if(obtenerNumeros($('#txtMonto').val())==0)
	{
		mensaje+="El monto es incorrecto <br />";
	}
	
	if(obtenerNumeros($('#txtInteresAnual').val())>99)
	{
		mensaje+="El interes anual es incorrecto <br />";
	}
	
	if(obtenerNumeros($('#txtPago').val())==0)
	{
		mensaje+="El pago es incorrecto";
	}
	
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<img src="'+ img_loader +'"/> Registrando...');
		},
		type:"POST",
		url:base_url+'creditos/registrarCredito',
		data:$('#frmRegistro').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡Error en el registro!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro ha sido exitoso!',500,5000,'',0,0);
					obtenerCreditos();
					
					if(cerrar==1)
					{
						$('#ventanaCreditos').dialog('close');	
					}
					else
					{
						$('#frmRegistro')[0].reset();
						$('#txtFuente').focus();
					}
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}

function obtenerCredito(idCredito)
{
	$('#ventanaEditarCreditos').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCredito').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'creditos/obtenerCredito',
		data:
		{
			idCredito:idCredito
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCredito").html(data);
			$('#txtConcepto').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerCredito").html('');
		}
	});		
}

function editarCredito(cerrar)
{
	mensaje="";
	
	if(!camposVacios($('#txtFuente').val()))
	{
		mensaje+="La fuente es requerida <br />";
	}
	
	if(obtenerNumeros($('#txtMonto').val())==0)
	{
		mensaje+="El monto es incorrecto <br />";
	}
	
	if(obtenerNumeros($('#txtInteresAnual').val())>99)
	{
		mensaje+="El interes anual es incorrecto <br />";
	}
	
	if(obtenerNumeros($('#txtPago').val())==0)
	{
		mensaje+="El pago es incorrecto";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<img src="'+ img_loader +'"/> Editando...');
		},
		type:"POST",
		url:base_url+'creditos/editarCredito',
		data:$('#frmRegistro').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro ha sido exitoso!',500,5000,'',0,0);
					obtenerCreditos();
					
					$('#ventanaEditarCreditos').dialog('close');	
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}

function borrarCredito(idCredito)
{
	if(!confirm('¿Realmente desea borrar registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Editando...');
		},
		type:"POST",
		url:base_url+'creditos/borrarCredito',
		data:
		{
			idCredito:idCredito
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro se ha borrado correctamente!',500,5000,'',0,0);
					obtenerCreditos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}



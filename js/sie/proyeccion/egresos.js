$(document).ready(function()
{
	$('#txtBuscarEgreso').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerEgresos();
		}
	});
	
	$("#ventanaEgresos").dialog(
	{
		autoOpen:false,
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Agregar: function() 
			{
				registrarEgreso(0)			 
			},
			Guardar: function() 
			{
				registrarEgreso(1)		 
			},
		},
		close: function() 
		{
			$("#formularioEgresos").html('');
		}
	});
	
	$("#ventanaEditarEgresos").dialog(
	{
		autoOpen:false,
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				editarEgreso()			 
			},
		},
		close: function() 
		{
			$("#obtenerEgreso").html('');
		}
	});
	
	$("#ventanaEgresoPagado").dialog(
	{
		autoOpen:false,
		height:500,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				definirEgresoPagado()			 
			},
		},
		close: function() 
		{
			$("#obtenerEgresoPagado").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagEgresos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerEgresos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#txtInicioEgreso').val(),
				fin:			$('#txtFinEgreso').val(),
				tipoFecha:		$('#selectTipoFecha').val(),
				criterio:		$('#txtBuscarEgreso').val(),
				pagado:			$('#txtPagado').val(),
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

function filtroEgresos(pagado,escenario)
{
	$('#txtPagado').val(pagado)
	$('#txtEscenario').val(escenario)
	
	obtenerEgresos()
}

function obtenerEgresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEgresos').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerEgresos',
		data:
		{
			inicio:			$('#txtInicioEgreso').val(),
			fin:			$('#txtFinEgreso').val(),
			tipoFecha:		$('#selectTipoFecha').val(),
			criterio:		$('#txtBuscarEgreso').val(),
			pagado:			$('#txtPagado').val(),
			idEscenario:	$('#txtEscenario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEgresos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerEgresos').html('');
		}
	});		
}

function formularioEgresos()
{
	$("#ventanaEgresos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEgresos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/formularioEgresos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEgresos").html(data);
			$('#txtConcepto').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioEgresos").html('');
		}
	});		
}

function registrarEgreso(cerrar)
{
	mensaje="";
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+="El concepto es requerido <br />";
	}
	
	if(obtenerNumeros($('#txtImporte').val())==0)
	{
		mensaje+="El importe es incorrecto";
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
		url:base_url+'proyeccion/registrarEgreso',
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
					obtenerEgresos();
					
					if(cerrar==1)
					{
						$('#ventanaEgresos').dialog('close');	
					}
					else
					{
						$('#frmRegistro')[0].reset();
						$('#txtConcepto').focus();
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

function obtenerEgreso(idEgreso)
{
	$('#ventanaEditarEgresos').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEgreso').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerEgreso',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerEgreso").html(data);
			$('#txtConcepto').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerEgreso").html('');
		}
	});		
}

function editarEgreso(cerrar)
{
	mensaje="";
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+="El concepto es requerido";
	}
	
	if(obtenerNumeros($('#txtImporte').val())==0)
	{
		mensaje+="El importe es incorrecto";
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
		url:base_url+'proyeccion/editarEgreso',
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
					obtenerEgresos();
					
					$('#ventanaEditarEgresos').dialog('close');	
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

function borrarEgreso(idEgreso)
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
		url:base_url+'proyeccion/borrarEgreso',
		data:
		{
			idEgreso:idEgreso
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
					obtenerEgresos();
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


function obtenerEgresoPagado(idEgreso)
{
	$('#ventanaEgresoPagado').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEgresoPagado').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerEgresoPagado',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerEgresoPagado").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerEgresoPagado").html('');
		}
	});		
}


function definirEgresoPagado()
{
	total				= obtenerNumeros($('#txtImporteEgreso').val());
	efectivo			= obtenerNumeros($('#txtEfectivo').val());
	cuentas				= obtenerNumeros($('#txtCuentas').val());
	paypal				= obtenerNumeros($('#txtPaypal').val());
	
	numeroCuentas		= obtenerNumeros($('#txtNumeroCuentas').val());
	totalCuentas		= 0;
	
	for(i=0;i<numeroCuentas;i++)
	{
		totalCuentas		+= obtenerNumeros($('#txtCuentas'+i).val());
	}
	
	totalCuentas+=efectivo;
	
	$('#txtTotalPagadoEgreso').val(totalCuentas)
	
	/*if(total!=(efectivo+cuentas+paypal))
	{
		notify('¡Revise que los importes sean igual al total!',500,5000,'error',30,5);
		return;
	}*/
	
	/*if((efectivo+cuentas+paypal)==0)
	{
		notify('¡Revise que los importes sean correctos!',500,5000,'error',30,5);
		return;
	}*/
	
	if(totalCuentas==0)
	{
		notify('¡Revise que los importes sean correctos!',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea confirmar que ha sido pagado el concepto?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEgresoPagado').html('<img src="'+ img_loader +'"/> Registrando...');
		},
		type:"POST",
		url:base_url+'proyeccion/definirEgresoPagado',
		data:$('#frmRegistro').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEgresoPagado').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro ha sido correcto!',500,5000,'',0,0);
					$('#ventanaEgresoPagado').dialog('close');	
					obtenerEgresos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoEgresoPagado').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}

function editarFechaPago(idEgreso)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Registrando...');
		},
		type:"POST",
		url:base_url+'proyeccion/editarFechaPago',
		data:
		{
			idEgreso:	idEgreso,
			fechaPago:	$('#txtFechaPago'+idEgreso).val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					//notify('¡El registro no tuvo cambios!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro ha sido correcto!',500,5000,'',0,0);
					obtenerEgresos();
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



$(document).ready(function()
{
	$('#txtBuscarIngreso').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerIngresos();
		}
	});
	
	$("#ventanaIngresos").dialog(
	{
		autoOpen:false,
		height:270,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Agregar: function() 
			{
				registrarIngreso(0)			 
			},
			Guardar: function() 
			{
				registrarIngreso(1)		 
			},
		},
		close: function() 
		{
			$("#formularioIngresos").html('');
		}
	});
	
	$("#ventanaEditarIngresos").dialog(
	{
		autoOpen:false,
		height:270,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				editarIngreso()			 
			},
		},
		close: function() 
		{
			$("#obtenerIngreso").html('');
		}
	});
	
	$("#ventanaIngresoCobrado").dialog(
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
				definirIngresoCobrado()			 
			},
		},
		close: function() 
		{
			$("#obtenerIngresoCobrado").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagIngresos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerIngresos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#txtInicioIngreso').val(),
				fin:			$('#txtFinIngreso').val(),
				criterio:		$('#txtBuscarIngreso').val(),
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

function filtroIngresos(pagado,escenario)
{
	$('#txtCobrado').val(pagado)
	$('#txtEscenarioIngreso').val(escenario)
	
	obtenerIngresos()
}

function obtenerIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngresos').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerIngresos',
		data:
		{
			inicio:			$('#txtInicioIngreso').val(),
			fin:			$('#txtFinIngreso').val(),
			criterio:		$('#txtBuscarIngreso').val(),
			
			cobrado:		$('#txtCobrado').val(),
			idEscenario:	$('#txtEscenarioIngreso').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerIngresos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerIngresos').html('');
		}
	});		
}

function formularioIngresos()
{
	$("#ventanaIngresos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioIngresos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/formularioIngresos',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioIngresos").html(data);
			$('#txtConcepto').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioIngresos").html('');
		}
	});		
}

function registrarIngreso(cerrar)
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
		url:base_url+'proyeccion/registrarIngreso',
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
					obtenerIngresos();
					
					if(cerrar==1)
					{
						$('#ventanaIngresos').dialog('close');	
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

function obtenerIngreso(idIngreso)
{
	$('#ventanaEditarIngresos').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngreso').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerIngreso',
		data:
		{
			idIngreso:idIngreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerIngreso").html(data);
			$('#txtConcepto').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerIngreso").html('');
		}
	});		
}

function editarIngreso(cerrar)
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
		url:base_url+'proyeccion/editarIngreso',
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
					obtenerIngresos();
					
					$('#ventanaEditarIngresos').dialog('close');	
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

function borrarIngreso(idIngreso)
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
		url:base_url+'proyeccion/borrarIngreso',
		data:
		{
			idIngreso:idIngreso
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
					obtenerIngresos();
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



function obtenerIngresoCobrado(idIngreso)
{
	$('#ventanaIngresoCobrado').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngresoCobrado').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'proyeccion/obtenerIngresoCobrado',
		data:
		{
			idIngreso:idIngreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerIngresoCobrado").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#obtenerIngresoCobrado").html('');
		}
	});		
}


function definirIngresoCobrado()
{
	total		= obtenerNumeros($('#txtImporteIngreso').val());
	efectivo	= obtenerNumeros($('#txtEfectivo').val());
	cuentas		= obtenerNumeros($('#txtCuentas').val());
	paypal		= obtenerNumeros($('#txtPaypal').val());
	
	numeroCuentas		= obtenerNumeros($('#txtNumeroCuentas').val());
	totalCuentas		= 0;
	
	for(i=0;i<numeroCuentas;i++)
	{
		totalCuentas		+= obtenerNumeros($('#txtCuentas'+i).val());
	}
	
	
	totalCuentas+=efectivo;
	
	if(total>totalCuentas)
	{
		notify('¡Revise que los importes sean correctos!',500,5000,'error',30,5);
		return;
	}
	
	$('#txtTotalCobradoIngreso').val(totalCuentas)
	
	
	/*if(total>(efectivo+cuentas+paypal))
	{
		notify('¡Revise que los importes sean igual o mayor al total!',500,5000,'error',30,5);
		return;
	}*/
	
	if(!confirm('¿Realmente desea confirmar que ha sido cobrado el concepto?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoIngresoCobrado').html('<img src="'+ img_loader +'"/> Registrando...');
		},
		type:"POST",
		url:base_url+'proyeccion/definirIngresoCobrado',
		data:$('#frmRegistro').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoIngresoCobrado').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('¡El registro no tuvo cambios!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro ha sido correcto!',500,5000,'',0,0);
					$('#ventanaIngresoCobrado').dialog('close');	
					obtenerIngresos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoIngresoCobrado').html('');
			notify('¡Error en el registro!',500,5000,'error',0,0);
		}
	});		
}
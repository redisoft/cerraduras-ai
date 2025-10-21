$(document).ready(function()
{
	$("#ventanaFacturaIngreso").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Datos fiscales': function() 
			{
				obtenerDatosFiscales();	 	  
			},
			'Previa': function() 
			{
				obtenerPreviaIngresos('0');	 	  
			},
			'Aceptar': function() 
			{
				registrarIngresoFactura();	 	  
			},
		},
		close: function() 
		{
			$("#formularioFacturaIngreso").html('');
		}
	});
});

function formularioFacturaIngreso(idIngreso)
{
	$("#ventanaFacturaIngreso").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioFacturaIngreso').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'produccion/formularioFacturaIngreso',
		data:
		{
			idIngreso:	idIngreso,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioFacturaIngreso').html(data);
			obtenerFolio();
		},
		error:function(datos)
		{
			$('#formularioFacturaIngreso').html('');
			notify('Error al obtener el formulario',500,5000,'error',30,5);
		}
	});		
}


function registrarIngresoFactura()
{
	mensaje			= "";

	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar el cliente <br />";
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Debe seleccionar el emisor <br />";
	}
	
	if(!camposVacios($('#txtFormaPago').val()))
	{
		mensaje+="La forma de pago es incorrecta <br />";
	}
	
	/*if(!camposVacios($('#txtCondiciones').val()))
	{
		mensaje+="Las condiciones de pago son incorrectas <br />";
	}*/
	

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente deseea registrar la factura?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#facturandoIngreso').html('<img src="'+ img_loader +'"/> Registrando la factura ...');},
		type:"POST",
		url:base_url+'facturacion/registrarIngresoFactura',
		data:$('#frmFacturaIngreso').serialize()+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#facturandoIngreso").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(''+data[1],500,5000,'error',30,5);
				break;

				case "1":
					$("#ventanaFacturaIngreso").dialog('close');
					
					if($("#txtModuloCfdi").val()=='administracion')
					{
						obtenerOtrosIngresos();
					}
					else
					{
						obtenerIngresos();
					}
					
				break;
			}
		},
		error:function(datos)
		{
			$("#facturandoIngreso").html('');
			notify('Error en el registro',500,5000,'error',30,5);
		}
	});		
}

function obtenerPreviaIngresos(global)
{
	mensaje			= "";

	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar el cliente <br />";
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Debe seleccionar el emisor <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente deseea ver la previa?')) return;
	
	formulario	= $('#frmFacturaIngreso').serialize()+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text()
	loader		= $('#facturandoIngreso').html('<img src="'+ img_loader +'"/> Generando la previa ...');
	
	if(global=='1')
	{
		formulario	= $('#frmGlobalIngresos').serialize()
		+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='
		+$("#selectUsoCfdi option:selected").text()
		+'&'+$('#frmCriterios').serialize();
		
		loader		= $('#facturandoGlobal').html('<img src="'+ img_loader +'"/>Generando la previa ...');
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){loader},
		type:"POST",
		url:base_url+'facturacion/obtenerPreviaIngresos',
		data:formulario,
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#facturandoIngreso").html('');
			$("#facturandoGlobal").html('');
			data	= eval(data);
			
			location.href=base_url+'reportes/descargarPdfPrevia/previa/Folio'+data[0];
		},
		error:function(datos)
		{
			$("#facturandoIngreso").html('');
			$("#facturandoGlobal").html('');
			notify('Error en el registro',500,5000,'error',30,5);
		}
	});		
}



//FACTURA GLOBAL

$(document).ready(function()
{
	$("#ventanaGlobalIngreso").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Datos fiscales': function() 
			{
				obtenerDatosFiscales();	 	  
			},
			'Previa': function() 
			{
				obtenerPreviaIngresos('1');	 	  
			},
			'Aceptar': function() 
			{
				registrarGlobalIngresos();	 	  
			},
		},
		close: function() 
		{
			$("#formularioGlobalIngresos").html('');
		}
	});
});

function formularioGlobalIngresos()
{
	$("#ventanaGlobalIngreso").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioGlobalIngresos').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'facturacion/formularioGlobalIngresos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idCuenta:		$('#selectCuentas').val(),
			idDepartamento:	$('#selectDepartamentos').val(),
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectGastos').val(),
			idCliente:		$('#txtIdClienteBusqueda').val(),
			idIngreso:		$('#txtIdIngreso').val(),
			criterio:		$('#selectCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioGlobalIngresos').html(data);
			obtenerFolio();
		},
		error:function(datos)
		{
			$('#formularioGlobalIngresos').html('');
			notify('Error al obtener el formulario',500,5000,'error',30,5);
		}
	});		
}


function registrarGlobalIngresos()
{
	mensaje			= "";

	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar el cliente <br />";
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Debe seleccionar el emisor <br />";
	}
	
	/*if(!camposVacios($('#txtFormaPago').val()))
	{
		mensaje+="La forma de pago es incorrecta <br />";
	}*/
	
	iva16	= obtenerNumeros($('#txtIva16').val());
	iva0	= obtenerNumeros($('#txtIva0').val());
	
	/*if(!camposVacios($('#txtCondiciones').val()))
	{
		mensaje+="Las condiciones de pago son incorrectas <br />";
	}*/
	
	if(iva16>0 && iva0>0)
	{
		mensaje+="Los ingresos deben tener la misma tasa de iva <br />";
	}
	
	if(obtenerNumeros($('#txtTotal').val())==0)
	{
		mensaje+="El total de la factura es incorrecto";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente deseea registrar la factura global?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#facturandoGlobal').html('<img src="'+ img_loader +'"/> Registrando la factura ...');},
		type:"POST",
		url:base_url+'facturacion/registrarGlobalIngresos',
		data:$('#frmGlobalIngresos').serialize()
		+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='
		+$("#selectUsoCfdi option:selected").text()
		+'&'+$('#frmCriterios').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#facturandoGlobal").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(''+data[1],500,5000,'error',30,5);
				break;

				case "1":
					$("#ventanaGlobalIngreso").dialog('close');
					obtenerIngresos();
					
				break;
			}
		},
		error:function(datos)
		{
			$("#facturandoGlobal").html('');
			notify('Error en el registro',500,5000,'error',30,5);
		}
	});		
}


//DATOS FISCALES

$(document).ready(function()
{
	$("#ventanaDatosFiscales").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				editarDatosFiscales();	 	  
			},
		},
		close: function() 
		{
			$("#obtenerDatosFiscales").html('');
		}
	});
});
function obtenerDatosFiscales()
{
	if($('#txtIdCliente').val()=="0")
	{
		notify('El ingreso no tiene cliente asignado',500,5000,'error',30,5);
		return;
	}
	
	$("#ventanaDatosFiscales").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDatosFiscales').html('<img src="'+ img_loader +'"/> Preparando el formulario de datos fiscales...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDatosFiscales',
		data:
		{
			idCliente:	$('#txtIdCliente').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDatosFiscales').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDatosFiscales').html('');
			notify('Error al obtener el formulario',500,5000,'error',30,5);
		}
	});		
}

function editarDatosFiscales()
{
	mensaje			= "";

	if(!camposVacios($('#txtRazonSocial').val()))
	{
		mensaje+="La razón social es incorrecta<br />";
	}
	
	if(!camposVacios($('#txtRfc').val()))
	{
		mensaje+="El RFC es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente deseea editar los datos fiscales?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#editandoFiscales').html('<img src="'+ img_loader +'"/> Editando datos fiscales ...');},
		type:"POST",
		url:base_url+'clientes/editarDatosFiscales',
		data:$('#frmDatosFiscales').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoFiscales").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,5);
				break;

				case "1":
					notify('El registro se ha editado correctamente',500,5000,'error',30,5);
					$('#txtBuscarClienteGlobal').val($('#txtRazonSocial').val())
					$('#lblRazonSocial').html($('#txtRazonSocial').val())
					
					$("#ventanaDatosFiscales").dialog('close');

				break;
			}
		},
		error:function(datos)
		{
			$("#editandoFiscales").html('');
			notify('Error al registrar los datos',500,5000,'error',30,5);
		}
	});		
}

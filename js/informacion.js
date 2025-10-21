function obtenerVentaInformacion(idCotizacion)
{
	detalleCita=true;
	$('#ventanaVentasInformacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerVentaInformacion').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la venta...');},
		type:"POST",
		url:base_url+'informacion/obtenerVentaInformacion',
		data:
		{
			"idCotizacion":idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerVentaInformacion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerVentaInformacion").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaVentasInformacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerVentaInformacion").html('');
			detalleCita=false;
		}
	});
});

//INFORMACIÓN DE LA  COMPRA
function obtenerCompraInformacion(idCompras)
{
	$('#ventanaComprasInformacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCompraInformacion').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la compra...');},
		type:"POST",
		url:base_url+'informacion/obtenerCompraInformacion',
		data:
		{
			"idCompras":idCompras
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCompraInformacion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerCompraInformacion").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaComprasInformacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerCompraInformacion").html('');
		}
	});
});


//INFORMACIÓN DEL GASTO
function obtenerGastoInformacion(idEgreso)
{
	$('#ventanaGastosInformacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerGastoInformacion').html('<img src="'+ img_loader +'"/> Se estan cargando los datos del gasto...');},
		type:"POST",
		url:base_url+'informacion/obtenerGastoInformacion',
		data:
		{
			"idEgreso":idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerGastoInformacion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerGastoInformacion").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaGastosInformacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerGastoInformacion").html('');
		}
	});
});

//PARA LA FICHA TÉCNICA DEL CLIENTE
function fichaTecnicaCliente(idCliente)
{
	$('#ventanaFichaCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFichaCliente').html('<img src="'+ img_loader +'"/>Obteniendo ficha tecnica del cliente...');
		},
		type:"POST",
		url:base_url+'clientes/buscarCliente/'+idCliente,
		data:
		{
			//"idSeguimiento":	$("#idSeguimiento"+i).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFichaCliente').html(data)
		},
		error:function(datos)
		{
			$('#obtenerFichaCliente').html('')
		}
	});		
}
	
$(document).ready(function()
{
	$("#ventanaFichaCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Imprimir': function()
			{
				window.location.href=base_url+'clientes/fichaPdf/'+$('#txtIdClienteFicha').val();	 
			},
			'Enviar': function()
			{
				formularioCorreoFichaCliente();				 
			},
			'Aceptar': function() 
			{
				$(this).dialog('close');				 
			},
			
		},
		close: function()
		{
			$("#obtenerFichaCliente").html('');
		}
	});
	
	$("#ventanaEnviarFichaCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:470,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Enviar': function()
			{
				enviarFichaCliente();			 
			},
		},
		close: function()
		{
			$('#formularioCorreoFichaCliente').html('');
		}
	});
})

function enviarFichaCliente()
{
	var mensaje="";

	if($("#txtAsuntoFichaCliente").val()=="")
	{
		mensaje+="Por favor escriba el asunto del correo <br />";										
	} 
	
	if($("#txtCorreoFichaCliente").val()=="")
	{
		mensaje+="El correo es requerido <br />";									
	}
	
	if($("#txtMensajeCorreo").val()=="")
	{
		mensaje+="Escriba el mensaje <br />";												
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#enviandoFichaCliente').html('<img src="'+ img_loader +'"/> Se esta enviando el correo, por favor espere...');},
		type:"POST",
		url:base_url+"clientes/enviarFichaCliente",
		data:
		{
			"asunto":		$("#txtAsuntoFichaCliente").val(),
			"correo":		$("#txtCorreoFichaCliente").val(),
			"mensaje":		$("#txtMensajeCorreo").val(),
			"idCliente":	$('#txtIdClienteFicha').val(),
			"idUsuario":	$('#selectUsuariosEnviar').val(),
			"firma":		$('#txtFirma').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#enviandoFichaCliente").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al enviar el correo',500,4000,"error",30,5);
				break;
				case "1":
					notify('El correo se ha enviado correctamente',500,4000,"",30,5);
					$('#ventanaEnviarFichaCliente').dialog('close');
				break;
			}//switch
		},
		error:function(datos)
		{
			$("#enviandoFichaCliente").html('');
			notify('Error al enviar el correo',500,4000,"error",30,5);
		}
	});
}

function formularioCorreoFichaCliente()
{
	$('#ventanaEnviarFichaCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioCorreoFicha').html('<img src="'+ img_loader +'"/> Cargando detalles de formulario...');},
		type:"POST",
		url:base_url+"clientes/formularioCorreoFicha",
		data:
		{
			idCliente:$('#txtIdClienteFicha').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCorreoFichaCliente').html(data);
		},
		error:function(datos)
		{
			$('#formularioCorreoFichaCliente').html('');
		}
	});	
}

function obtenerCotizacionInformacion(idCotizacion)
{
	detalleCita=true;
	$('#ventanaCotizacionesInformacion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCotizacionInformacion').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la cotización...');},
		type:"POST",
		url:base_url+'informacion/obtenerCotizacionInformacion',
		data:
		{
			"idCotizacion":idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCotizacionInformacion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerCotizacionInformacion").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaCotizacionesInformacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				window.open(base_url+'pdf/cotizacionPdf/'+$('#txtIdCotizacionInformacion').val());
			},
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerCotizacionInformacion").html('');
			detalleCita=false;
		}
	});
});



//INFORMACIÓN DE LA TIENDA
function obtenerInformacionTienda(idTienda)
{
	$('#ventanaInformacionTienda').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerInformacionTienda').html('<img src="'+ img_loader +'"/> Se estan cargando los datos de la tienda...');},
		type:"POST",
		url:base_url+'informacion/obtenerInformacionTienda',
		data:
		{
			"idTienda":idTienda
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerInformacionTienda").html(data);
		},
		error:function(datos)
		{
			$("#obtenerInformacionTienda").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaInformacionTienda").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerInformacionTienda").html('');
		}
	});
});


//INFORMACIÓN DE LA MATERIA PRIMA
function obtenerInformacionMaterial(idMaterial,idProveedor)
{
	$('#ventanaInformacionMaterial').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerInformacionMaterial').html('<img src="'+ img_loader +'"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerInformacionMaterial',
		data:
		{
			"idMaterial":	idMaterial,
			"idProveedor":	idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerInformacionMaterial").html(data);
		},
		error:function(datos)
		{
			$("#obtenerInformacionMaterial").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaInformacionMaterial").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerInformacionMaterial").html('');
		}
	});
});


//INFORMACIÓN DEL INVENTARIO PARA SALIDAS Y ENTRADAS
function obtenerInformacionCompras(idProducto)
{
	$('#ventanaInformacionCompras').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerInformacionCompras').html('<img src="'+ img_loader +'"/> Se estan cargando los datos ...');},
		type:"POST",
		url:base_url+'informacion/obtenerInformacionCompras',
		data:
		{
			"idProducto":	idProducto,
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			"idTienda":		$('#selectTiendas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerInformacionCompras").html(data);

			obtenerComprasInformacion();
			obtenerEntradasTraspasos();
			obtenerVentasInformacion();
			obtenerEnviosInformacion();
			obtenerMovimientosInformacion();
			obtenerDiarioInformacion();
			obtenerEntradasEntregas()
		},
		error:function(datos)
		{
			$("#obtenerInformacionCompras").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaInformacionCompras").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerInformacionCompras").html('');
		}
	});
});

$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagComprasInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerComprasInformacion";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function(){$('#obtenerComprasInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
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

function obtenerComprasInformacion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerComprasInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerCompras',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerComprasInformacion').html(data);
		},
		error:function(datos)
		{
			$("#obtenerComprasInformacion").html('');
		}
	});		
}

//ENTRADAS POR TRASPASOS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagEntradasTraspasos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerEntradasTraspasos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerEntradasTraspasos').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerEntradasTraspasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerEntradasTraspasos').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerEntradasTraspasos',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEntradasTraspasos').html(data);
		},
		error:function(datos)
		{
			$("#obtenerEntradasTraspasos").html('');
		}
	});		
}

//VENTAS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagVentasInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentasInformacion";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentasInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerVentasInformacion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerVentasInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerVentas',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentasInformacion').html(data);
		},
		error:function(datos)
		{
			$("#obtenerVentasInformacion").html('');
		}
	});		
}

//ENVÍOS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagEnviosInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerEnviosInformacion";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerEnviosInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerEnviosInformacion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerEnviosInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerEnvios',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEnviosInformacion').html(data);
		},
		error:function(datos)
		{
			$("#obtenerEnviosInformacion").html('');
		}
	});		
}

//MOVIMIENTOS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagMovimientosInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerMovimientosInformacion";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerMovimientosInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerMovimientosInformacion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMovimientosInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerMovimientos',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMovimientosInformacion').html(data);
		},
		error:function(datos)
		{
			$("#obtenerMovimientosInformacion").html('');
		}
	});		
}

//DIARIO
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagDiarioInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerDiarioInformacion";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerDiarioInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerDiarioInformacion()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerDiarioInformacion').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerDiario',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDiarioInformacion').html(data);
		},
		error:function(datos)
		{
			$("#obtenerDiarioInformacion").html('');
		}
	});		
}

//ENTRADAS ENTREGAS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagEntradasEntregasInformacion > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerEntradasEntregas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idProducto": $('#txtIdProductoInventario').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerEntradasEntregas').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');
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

function obtenerEntradasEntregas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerEntradasEntregas').html('<img src="' + img_loader + '"/> Se estan cargando los datos...');},
		type:"POST",
		url:base_url+'informacion/obtenerEntradasEntregas',
		data:
		{
			"idProducto": $('#txtIdProductoInventario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEntradasEntregas').html(data);
		},
		error:function(datos)
		{
			$("#obtenerEntradasEntregas").html('');
		}
	});		
}

$(document).ready(function()
{
	$('#txtBusquedaVentas').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerVentas();
		}
	});
	
	$("#ventanaCorreo").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:750,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				enviarCorreo();       
			},
		},
		close: function() 
		{
			$("#formularioCorreo").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagVentas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio: 		$('#txtBusquedaVentas').val(),
				inicio: 		$('#txtFechaInicioVentas').val(),
				fin: 			$('#txtFechaFinVentas').val(),
				idCliente:  	$('#txtClienteId').val(),
				idCotizacion:  	$('#selectVentasBusqueda').val(),
				idFactura:  	$('#selectFacturasBusqueda').val(),
				ordenVentas:  	$('#txtOrdenVentas').val(),
				seccion:  		$('#txtSeccion').val(),
				idEstacion:  	$('#selectEstaciones').val(),
				traspasos:  	$('#selectVentasTraspasos').val(),
				saldo:  		document.getElementById('chkSaldo').checked?'1':'0',
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentas').html('<img src="'+ img_loader +'"/>Obteniendo detalles de ventas');
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

function formularioCorreo(serie,correo,idCotizacion)
{
	$('#ventanaCorreo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCorreo').html('<img src="'+ img_loader +'"/>Obteniendo el formulario de correo...');
		},
		type:"POST",
		url:base_url+'clientes/formularioCorreo',
		data:
		{
			"serie":		serie,
			"idCotizacion":	idCotizacion,
			"correo":		correo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCorreo').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de correo',500,5000,'error',2,5);
			$("#formularioCorreo").html('');
		}
	});
}

function enviarCorreo()
{
	var mensaje="";

	if($("#asunto").val()=="")
	{
		mensaje+="Por favor escriba el asunto del correo <br />";										
	} 
	
	if($("#correo").val()=="")
	{
		mensaje+="El correo es requerido <br />";									
	}
	
	if($("#mensa").val()=="")
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
		beforeSend:function(objeto){$('#enviandoCorreo').html('<img src="'+ img_loader +'"/> Se esta enviando el correo, por favor espere...');},
		type:"POST",
		url:base_url+"clientes/enviar",
		data:
		{
			"asunto":		$("#asunto").val(),
			"correo":		$("#correo").val(),
			"mensaje":		$("#mensa").val(),
			"idCotizacion":	$('#txtIdCotizacion').val(),
			"idUsuario":	$('#selectUsuariosEnviar').val(),
			"firma":		$('#txtFirma').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#enviandoCorreo").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al enviar el correo',500,4000,"error",30,5);
				break;
				case "1":
					notify('El correo se ha enviado correctamente',500,4000,"",30,5);
					$('#ventanaCorreo').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$("#enviandoCorreo").html('');
			notify('Error al enviar el correo',500,4000,"error");
		}
	});
}



function definirOrdenVentas(orden)
{
	$('#txtOrdenVentas').val(orden);
	obtenerVentas();
}

function definirIdCliente()
{
	$('#txtClienteId').val($('#selectClientesBusqueda').val());
	obtenerVentas();
}

function obtenerVentas()
{
	if(ejecutar && ejecutar.readyState != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentas').html('<img src="'+ img_loader +'"/>Obteniendo la lista de ventas...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerVentas',
		data:
		{
			criterio: 		$('#txtBusquedaVentas').val(),
			inicio: 		$('#txtFechaInicioVentas').val(),
			fin: 			$('#txtFechaFinVentas').val(),
			idCliente:  	$('#txtClienteId').val(),
			idCotizacion:  	$('#selectVentasBusqueda').val(),
			idFactura:  	$('#selectFacturasBusqueda').val(),
			ordenVentas:  	$('#txtOrdenVentas').val(),
			seccion:  		$('#txtSeccion').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			traspasos:  	$('#selectVentasTraspasos').val(),
			saldo:  		document.getElementById('chkSaldo').checked?'1':'0',
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentas').html(data);
		},
		error:function(datos)
		{
			//notify('Error al obtener las ventas',500,5000,'error',30,5);
			$("#obtenerVentas").html('');
		}
	});
}

//BORRAR VENTA

function cancelarVenta(idCotizacion)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoVentas').html('<img src="'+ img_loader +'"/> Cancelando la venta...');
		},
		type:"POST",
		url:base_url+"ficha/cancelarVenta",
		data:
		{
			"idCotizacion":idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data	= eval(data);
			$('#procesandoVentas').html('');
			
			switch(data[0])
			{
				case '0':
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case '1':
					notify(data[1],500,5000,'',30,5);
					obtenerVentas();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoVentas').html('');
			notify('Error al cancelar la venta',500,5000,'error',30,5);
		}
	});
}

function borrarVenta(idCotizacion)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoVentas').html('<img src="'+ img_loader +'"/> Borrando la venta...');
		},
		type:"POST",
		url:base_url+"ficha/borrarVenta",
		data:
		{
			"idCotizacion":idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data	= eval(data);
			$('#procesandoVentas').html('');
			
			switch(data[0])
			{
				case '0':
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case '1':
					notify(data[1],500,5000,'',30,5);
					$('#filaVenta'+idCotizacion).remove();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoVentas').html('');
			notify('Error al borrar la venta',500,5000,'error',30,5);
		}
	});
}

function ticketReporteVentas()
{
	document.forms['frmReporteTicket'].submit();
}


$(document).ready(function()
{
	$("#ventanaCorteDiario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1100,
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
			$("#corteCatalogo").html('');
		}
	});
});


function corteCatalogo()
{
	$("#ventanaCorteDiario").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#corteCatalogo').html('<img src="'+ img_loader +'"/>Preparando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/corteCatalogo',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#corteCatalogo').html(data);
		},
		error:function(datos)
		{
			//notify('Error al obtener las ventas',500,5000,'error',30,5);
			$("#corteCatalogo").html('');
		}
	});
}
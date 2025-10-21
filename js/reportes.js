
//==============================================================================================//
//============================================EXCEL=============================================//
//==============================================================================================//


$(document).ready(function()
{
	$("#txtBusquedaVentas").autocomplete(
	{
		source:base_url+'configuracion/obtenerVentas',
		
		select:function( event, ui)
		{
			window.location.href=base_url+"clientes/prebusquedaVentas/"+ui.item.idCotizacion;
		}
	});
		
	$("#ventanaNomina").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:800,
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
				pagarNomina();       
			},
		},
		close: function() 
		{
			$("#formularioNomina").html('');
		}
	});
});

function formularioNomina(idPersonal)
{
	$('#ventanaNomina').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNomina').html('<img src="'+ img_loader +'"/>Obteniendo detalles para pago de nómina...');
		},
		type:"POST",
		url:base_url+'reportes/formularioNomina',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			dias:		$('#txtDias').val(),
			idPersonal:	idPersonal
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioNomina').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles para pago de la nómina',500,5000,'error',2,5);
			$("#formularioNomina").html('');
		}
	});
}

function pagarNomina()
{
	mensaje		="";
	idNombre 	=0
	
	if($('#txtFechaEngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	if($('#selectProductos').val()=="0")
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#txtImporte').val()=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}

	if($('#selectDepartamento').val()=="0")
	{
		mensaje+="Seleccione el departamento <br />";
	}
	
	
	if($('#selectTipoGasto').val()=="0")
	{
		mensaje+="Seleccione el tipo <br />";
	}
	
	
	
	/*if($('#selectProductos').val()=="0")
	{
		mensaje+="Seleccione el producto <br />";
	}*/
	
	if($('#selectTipoPago').val()=="Efectivo")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#selectTipoPago').val()=="Cheque")
	{
		$('#txtNumeroTransferencia').val('');
		idNombre	=$('#selectNombres').val();
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione a quien se le pagara el documento <br />";
		}
	}

	if($('#selectTipoPago').val()=="Transferencia")
	{
		$('#txtNumeroCheque').val('');
		idNombre	=0;
		
		if($('#txtNumeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
	}
	
	if($('#selectBancos').val()=="0")
	{
		mensaje+="Por favor seleccione el banco <br />";
	}
	
	if($('#selectCuentas').val()=="0")
	{
		mensaje+="Por favor seleccione la cuenta <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',0,0);
		return;
	}
	
	if(confirm("¿Realmente desea registrar el pago?")==false)
	{
		return;
	}
	
	//cajaChica=document.getElementById('chkCajaChica').checked;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#pagandoNomina').html('<img src="'+ img_loader +'"/> Registrando el pago, por favor espere...');
		},
		type:"POST",
		url:base_url+'reportes/pagarNomina',
		data:
		{
			producto:       $('#txtDescripcionProducto').val(),
			pago:			$('#txtImporte').val(),
			iva:			$('#txtIva').val(),
			idDepartamento:	$('#selectDepartamento').val(),
			idNombre:		idNombre,
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectTipoGasto').val(),
			formaPago:		$('#selectTipoPago').val(),
			cheque:			$('#txtNumeroCheque').val(),
			transferencia:	$('#txtNumeroTransferencia').val(),
			idCuenta:		$('#selectCuentas').val(),
			incluyeIva:		document.getElementById('chkIva').checked==true?1:0,
			cajaChica:		0,
			nombreReceptor:	$('#txtNombreReceptor').val(),
			fecha:			$('#txtFechaEngreso').val(),
			idPersonal:		$('#txtIdPersonal').val(),
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			dias:			$('#txtDias').val(),
			comentarios:	$('#txtComentarios').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/nomina';
		},
		error:function(datos)
		{
			notify('Error al registrar el pago',500,5000,'error',0,0);
			$('#pagandoNomina').html('');
		}
	});					  	  
}

function obtenerNomina()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNomina').html('<img src="'+ img_loader +'"/>Obteniendo detalles de nómina...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerNomina',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			idPersonal:	$('#txtPersonal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNomina').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de la nómina',500,5000,'error',2,5);
			$("#obtenerNomina").html('');
		}
	});
}

function formularioCorreoss(serie,correo,idCotizacion)
{
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


//PRONOSTICO DE PAGOS
function obtenerPronostico()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPronostico').html('<img src="'+ img_loader +'"/> Obteniendo el pronostico, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerPronostico',
		data:
		{
			fechaInicio:	$('#FechaDia').val(),
			fechaFin:		$('#FechaDia2').val(),
			idProveedor:	$('#selectProveedores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPronostico').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el pronostico',500,5000,'error',20,5);
			$('#obtenerPronostico').html('');
		}
	});					  	  
}


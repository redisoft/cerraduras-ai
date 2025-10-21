function formularioFacturaGlobal()
{
	$('#ventanaFacturaGlobal').dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#formularioFacturaGlobal').html('<img src="'+ img_loader +'"/>Obteniendo detalles para la factura, por favor espere...');},
		type:"POST",
		url:base_url+'facturacion/formularioFacturaGlobal',
		data:
		{
			//"idCuenta": idCuenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFacturaGlobal").html(data);
		},
		error:function(datos)
		{
			$("#formularioFacturaGlobal").html('');
			notify('Error al obtener los datos para la factura',500,5000,'error',30,3);
		}
	});				  	  
}

$(document).ready(function()
{
	$("#ventanaFacturaGlobal").dialog(
	{
		autoOpen:false,
		height:650,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Previa': function() 
			{
				previaGlobal();
			},
			'Aceptar': function() 
			{
				registrarFacturaGlobal();
			},
		},
		close: function() 
		{
			$("#formularioFacturaGlobal").html('');
			
			if(activarFacturacion)
			{
				actualizarAccesoFacturacion('0');
			}
		}
	});
});

function rangoDatos()
{
	if($('#selectTipoRango').val()=="Fechas")
	{
		$('#filaRangoFechas').fadeIn();
		$('#filaRangoNotas').fadeOut();
	}
	
	if($('#selectTipoRango').val()=="Folios")
	{
		$('#filaRangoNotas').fadeIn();
		$('#filaRangoFechas').fadeOut();
		
		$('#txtFolioInicial').focus();
	}
	
	setTimeout(function() 
	{
		obtenerTotalesFactura()
	}, 500);
	
	
}

function obtenerTotalesFactura()
{
	if($('#selectTipoRango').val()=="Folios")
	{
		inicio	= obtenerNumeros($('#txtFolioInicial').val());
		fin		= obtenerNumeros($('#txtFolioFinal').val());
		
		if(inicio>fin)
		{
			notify('Configure correctamente el folio de inicio y el folio final',500,1000,'error',30,3);
			
			$("#txtTotalesFacturaGlobal").val(0);
			$("#lblSubTotal,#lblIva,#lblTotal").html('$0.00')
			
			return;
		}
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#obtenerTotalesFactura').html('<img src="'+ img_loader +'"/>Obteniendo detalles para la factura, por favor espere...');},
		type:"POST",
		url:base_url+'facturacion/obtenerTotalesFactura',
		data:
		{
			"inicio": 	$('#selectTipoRango').val()=="Fechas"?$('#txtInicio').val():$('#txtFolioInicial').val(),
			"fin": 		$('#selectTipoRango').val()=="Fechas"?$('#txtFin').val():$('#txtFolioFinal').val(),
			"tipo":		$('#selectTipoDocumento').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerTotalesFactura").html(data);
		},
		error:function(datos)
		{
			$("#obtenerTotalesFactura").html('');
			notify('Error al obtener los datos para la factura',500,5000,'error',30,3);
		}
	});				  	  
}

function calcularTotalesFactura() //Calcular los importes por conceptos
{
	subTotal	= 0;
	iva			= 0;
	total		= 0;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdConcepto'+i).val()))
		{
			cantidad	=obtenerNumero($('#txtCantidadFactura'+i).val());
			precio		=obtenerNumero($('#txtPrecioFactura'+i).val());

			i/*f(isNaN(cantidad) || cantidad==0 || Solo_Numerico(cantidad)=="")
			{
				cantidad=0;
			}
			
			if(isNaN(precio) || precio==0 || Solo_Numerico(precio)=="")
			{
				precio=0;
			}*/
			
			subTotal+=precio*cantidad;
			
			$('#txtImporteFactura'+i).val(redondear(precio*cantidad))
		}
	}

	iva		= obtenerNumero($('#selectIva').val())/100;
	iva		= iva*subTotal;
	total	= subTotal+iva;
	
	$('#txtSubTotal').val(redondear(subTotal))
	$('#txtIva').val(redondear(iva))
	$('#txtTotal').val(redondear(total))
	
	$('#lblSubTotal').html('$'+redondear(subTotal))
	$('#lblIva').html('$'+redondear(iva))
	$('#lblTotal').html('$'+redondear(total))
	
}

function previaGlobal()
{
	var mensaje="";
	
	if($("#selectEmisoresGlobal").val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";										
	}
	
	if($("#txtIdClienteGlobal").val()=="0")
	{
		mensaje+="Seleccione el cliente <br />";										
	}
	
	if(parseFloat($("#txtTotalesFacturaGlobal").val())=="0")
	{
		mensaje+="El importe de la factura es incorrecto <br />";										
	}
	
	if($('#selectDirecciones').val()=="0")
	{
		mensaje+="Seleccione la dirección <br />";
	}

	b=1;
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente desea ver la previa de la factura?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoFacturaGlobal').html('<img src="'+ img_loader +'"/>Se esta creando la previa, por favor espere...');},
		type:"POST",
		url:base_url+"reportes/vistaPreviaFacturaGlobal",
		data:
		//$('#frmFacturacion').serialize(),
		$('#frmFacturacion').serialize()+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+
		'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text()+'&mes='+$("#selectMeses option:selected").text()+'&periodicidad='+$("#selectPeriodicidad option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoFacturaGlobal").html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error',500,5000,'error',30,5);
				break;
				case "1":
					window.location.href=base_url+'reportes/descargarPdfPrevia/vistaPrevia/vistaPrevia';
					notify('La previa de la factura se ha realizado correctamente',500,5000,'',30,3);
				break;
				
			}
		},
		error:function(datos)
		{
			$("#registrandoFacturaGlobal").html('');
			notify('Error al realizar la factura',500,5000,'error',30,3);
		}
	});		
}

activarFacturacion	= true;

function registrarFacturaGlobal()
{
	/*if($('#txtFoliosActivos').val()=="0")
	{
		notify('Los folios se han terminado, por favor consulte con el administrador',500,5000,'error',30,5);
		return;
	}*/
	
	var mensaje="";
	
	if($("#selectEmisoresGlobal").val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";										
	}
	
	if($("#txtIdClienteGlobal").val()=="0")
	{
		mensaje+="Seleccione el cliente <br />";										
	}
	
	if(parseFloat($("#txtTotalesFacturaGlobal").val())=="0")
	{
		mensaje+="El importe de la factura es incorrecto <br />";										
	}
	
	if($('#selectDirecciones').val()=="0")
	{
		mensaje+="Seleccione la dirección <br />";
	}
	
	/*if(!camposVacios($("#txtConceptoGlobal").val()))
	{
		mensaje+="El concepto es incorrecto <br />";										
	}*/
	
	b=1;
	
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la factura?')) return;
	
	activarFacturacion	= true;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoFacturaGlobal').html('<img src="'+ img_loader +'"/>Se esta creando la factura, por favor espere...');},
		type:"POST",
		url:base_url+"facturacion/registrarFacturaGlobal",
		data:
		//$('#frmFacturacion').serialize(),
		$('#frmFacturacion').serialize()+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+
		'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text()+'&mes='+$("#selectMeses option:selected").text()+'&periodicidad='+$("#selectPeriodicidad option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoFacturaGlobal").html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					activarFacturacion	= false;
					
					$('#ventanaFacturaGlobal').dialog('close');
					notify('La factura se ha registrado corractamente',500,5000,'error',30,5);
					obtenerFacturas();
				break;
				
			}
		},
		error:function(datos)
		{
			$("#registrandoFacturaGlobal").html('');
			notify('Error al realizar la factura',500,5000,'error',30,3);
		}
	});		
}

function obtenerDireccionesCliente(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDireccionesCliente').html('<img src="'+ img_loader +'"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDireccionesFiscalesCliente',
		data:
		{
			idCliente:idCliente,
			tipo:3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDireccionesCliente').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDireccionesCliente').html('');
			notify('Error en la  busqueda',500,5000,'error',30,5);
		}
	});		
}

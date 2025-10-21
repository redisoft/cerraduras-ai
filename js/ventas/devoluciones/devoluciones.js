$(document).ready(function()
{
	$("#ventanaDevoluciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:620,
		width:970,
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
				registrarDevolucion()	  	  
			},
		},
		close: function() 
		{
			$("#obtenerDevoluciones").html('');
		}
	});
});

function obtenerDevoluciones(idCotizacion)
{
	$("#ventanaDevoluciones").dialog('open');
	
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDevoluciones').html('<img src="'+ img_loader +'"/> Obteniendo detalles de venta...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerDevoluciones',
		data:
		{
			idCotizacion:	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDevoluciones').html(data);
			importeTotal	= 0;
		},
		error:function(datos)
		{
			$('#obtenerDevoluciones').html('');
			notify('Error al obtener las devoluciones',500,5000,'error',30,5);
		}
	});		
}

importeTotal	= 0;

function calcularImporteDevolucionFila(i)
{
	cantidad	= parseFloat($('#txtCantidadDevolver'+i).val());
	disponible	= parseFloat($('#txtCantidadDisponible'+i).val());
	precio		= parseFloat($('#txtPrecioProducto'+i).val());
	importe		= parseFloat($('#txtImporteProducto'+i).val());
	importeTotal-=importe;
	
	
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje'+i).val());
	descuento			= descuentoPorcentaje>0?descuentoPorcentaje/100*precio:0;
	precio				-=descuento;
	

	if(!comprobarNumeros(cantidad) || cantidad==0 || cantidad>disponible)
	{
		$('#txtCantidadDevolver'+i).val('')
		cantidad=0;
	}
	
	importe			= cantidad*precio;
	importeTotal	+= importe;
	
	$('#txtImporteProducto'+i).val(redondear(importe))
	$('#lblImporteProducto'+i).html('$'+redondear(importe));
	
	$('#txtImporteTotal').val(redondear(importeTotal))
	$('#lblImporteTotal').html('$'+redondear(importeTotal));
	
	$('#txtDescuentoProducto'+i).val(redondear(descuento*cantidad))
	
	calcularImportesNota();
	calcularImportesDinero()
}

function configurarTipoNota()
{
	switch($('#selectTipoDevolucion').val())
	{
		case "2":
			$('#filaNotaCredito').fadeOut();
			$('#filaPago').fadeIn();
		break;
		
		case "3":
			$('#filaNotaCredito').fadeIn();
			$('#filaPago').fadeOut();
		break;
		
		default:
			$('#filaNotaCredito').fadeOut();
			$('#filaPago').fadeOut();
		break;
	}
}

function comprobarProductosNota()
{
	b=false;
	for(i=1;i<=parseInt($('#txtNumeroProductos').val());i++)
	{
		cantidad		= parseFloat($('#txtCantidadDevolver'+i).val())
		
		if(comprobarNumeros(cantidad) || cantidad>0)
		{
			b=true;
		}
	}
	
	if(!b)
	{
		return "Ingrese el menos un producto para la devolución <br />";
	}
	
	return '';
}

function registrarDevolucion()
{
	mensaje			= "";

	if($('#selectMotivos').val()=="0")
	{
		mensaje+="Seleccion el motivo de la devolución <br />";
	}
	
	if(!compararCantidades($('#txtDisponibleDevolucion').val(),$('#txtImporteTotal').val()))
	{
		mensaje+="El importe de la devolución supera al que se ha pagado <br />";
	}
	
	mensaje+=comprobarProductosNota();
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if($('#selectTipoDevolucion').val()=="2")
	{
		if($('#txtDineroAbierto').val()!='1')
		{
			notify('Configure la devolución del dinero',500,5000,'error',30,5);
			return;
		}
		
		if(!configurarDinero()) return;
	}
	
	if($('#selectTipoDevolucion').val()=="3")
	{
		if($('#txtNotaAbierto').val()!='1')
		{
			notify('Configure la nota de crédito',500,5000,'error',30,5);
			return;
		}
		
		if(!configurarNota()) return;
	}

	if(!confirm('¿Realmente deseea registrar la devolución?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoDevoluciones').html('<img src="'+ img_loader +'"/> Registrando la devolución ...');},
		type:"POST",
		url:base_url+'ventas/registrarDevolucion',
		data:$('#frmDevoluciones').serialize()+'&'+$('#frmNotaCredito').serialize()+'&'+$('#frmDinero').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#procesandoDevoluciones").html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(''+data[1],500,5000,'error',30,5);
				break;

				case "1":
					$("#obtenerDatosNota").html('');
					$("#obtenerFormularioDinero").html('');
					notify('La devolución se ha registrado correctamente',500,5000,'',30,5);
					obtenerDevoluciones($('#txtIdCotizacion').val());
	
				break;
			}
		},
		error:function(datos)
		{
			$("#procesandoDevoluciones").html('');
			notify('Error al registrar la devolución',500,5000,'error',30,5);
		}
	});		
}


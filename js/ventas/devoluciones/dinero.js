$(document).ready(function()
{
	$("#ventanaDineroDevolucion").dialog(
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
				configurarDinero();	  
			},
		},
		close: function() 
		{
			//$("#obtenerDatosNota").html('');
		}
	});
});

function obtenerFormularioDinero()
{
	mensaje			= "";
	
	mensaje+=comprobarProductosNota();
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$("#ventanaDineroDevolucion").dialog('open');
	
	if($('#txtDineroAbierto').val()=='1') return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFormularioDinero').html('<img src="'+ img_loader +'"/> Obteniendo detalles de formulario...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerFormularioDinero',
		data:
		{
			idCotizacion:	$('#txtIdCotizacion').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFormularioDinero').html(data);
			calcularImportesDinero()
		},
		error:function(datos)
		{
			$('#obtenerFormularioDinero').html('');
			notify('Error al obtener los detalles del formulario',500,5000,'error',30,5);
		}
	});		
}

function configurarDinero()
{
	mensaje		= "";
	idNombre	= 0
	
	if($('#txtFechaEgreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}

	if(!comprobarNumeros($('#txtImporteDinero').val()) || parseFloat($('#txtImporteDinero').val()) == 0)
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	if(!camposVacios($('#txtDescripcionProducto').val()))
	{
		mensaje+="La descripción del producto es incorrecta <br />";
	}
	
	if(Solo_Numerico($('#txtCantidad').val())=="" || $('#txtCantidad').val()=="0")
	{
		mensaje+="La cantidad es incorrecta <br />";
	}

	if($('#cuentasBanco').val()=="0")
	{
		mensaje+="Por favor seleccione la cuenta <br />";
	}
	
	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
	}

	if($('#selectFormas').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		
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

	if($('#selectFormas').val()=="3")
	{
		$('#txtNumeroCheque').val('');
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return false;
	}
	
	$("#ventanaDineroDevolucion").dialog('close');
	return true;
}

function calcularImportesDinero()
{
	//AGREGAR EL DESCUENTO
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentajeDinero').val());
	descuento			= descuentoPorcentaje>0?descuentoPorcentaje/100*importeTotal:0;
	importeTotal		-=descuento;
	
	iva					= parseFloat($('#txtIvaPorcentajeDinero').val());
	iva					= iva*importeTotal;
	totalNota			= iva+importeTotal;
	
	$('#lblSubTotalDinero').html('$'+redondear(importeTotal+descuento))
	$('#lblIvaDinero').html('$'+redondear(iva))
	$('#lblTotalDinero').html('$'+redondear(totalNota))
	
	$('#txtSubTotalDinero').val(redondear(importeTotal))
	$('#txtIvaDinero').val(redondear(iva))
	$('#txtImporteDinero').val(redondear(totalNota))
	
	//AGREGAR EL DESCUENTO
	$('#lblDescuentoDinero').html('$'+redondear(descuento))
	
	importeTotal+=descuento;
}


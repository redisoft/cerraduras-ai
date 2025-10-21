$(document).ready(function()
{
	$("#ventanaDatosNota").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				configurarNota();	 	  
			},
		},
		close: function() 
		{
			//$("#obtenerDatosNota").html('');
		}
	});
});

function configurarNota()
{
	mensaje		= "";
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if(!camposVacios($('#txtMetodoPago').val()))
	{
		mensaje+="El método de pago es incorrecto <br />";
	}
	
	if(!camposVacios($('#txtFormaPago').val()))
	{
		mensaje+="La forma de pago es incorrecta <br />";
	}

	if(!camposVacios($('#txtCondiciones').val()))
	{
		mensaje+="Las condiciones de pago son incorrectas <br />";
	}


	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return false;
	}
	
	$("#ventanaDatosNota").dialog('close');
	return true;
}

function obtenerDatosNota(idCotizacion)
{
	mensaje			= "";
	
	mensaje+=comprobarProductosNota();
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$("#ventanaDatosNota").dialog('open');
	if($('#txtNotaAbierto').val()=='1') return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDatosNota').html('<img src="'+ img_loader +'"/> Obteniendo detalles de nota de crédito...');
		},
		type:"POST",
		url:base_url+'facturacion/obtenerDatosNota',
		data:
		{
			idCotizacion:	$('#txtIdCotizacion').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDatosNota').html(data);
			obtenerFolio();
			calcularImportesNota();
		},
		error:function(datos)
		{
			$('#obtenerDatosNota').html('');
			notify('Error al obtener los detalles de nota de crédito',500,5000,'error',30,5);
		}
	});		
}

function calcularImportesNota()
{
	//AGREGAR EL DESCUENTO
	descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje').val());
	descuento			= descuentoPorcentaje>0?descuentoPorcentaje/100*importeTotal:0;
	importeTotal		-=descuento;
	//-------------------------------------------------------------------------------------------
	
	iva					= parseFloat($('#txtIvaPorcentaje').val());
	iva					= iva>0?iva/100*importeTotal:0;
	totalNota			= iva+importeTotal;
	
	$('#lblSubTotal').html('$'+redondear(importeTotal+descuento))
	$('#lblIva').html('$'+redondear(iva))
	$('#lblTotalNota').html('$'+redondear(totalNota))
	
	$('#txtSubTotal').val(redondear(importeTotal+descuento))
	$('#txtIva').val(redondear(iva))
	$('#txtTotalNota').val(redondear(totalNota))
	
	//AGREGAR EL DESCUENTO
	$('#lblDescuentoNota').html('$'+redondear(descuento))
	$('#txtDescuentoNota').val(redondear(descuento))
	
	importeTotal+=descuento;
}

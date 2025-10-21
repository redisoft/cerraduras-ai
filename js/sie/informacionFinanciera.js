$(document).ready(function()
{
	$('#txtFecha').daterangepicker(
	{
		singleDatePicker: true,
		locale: 
		{
		  format: 'YYYY-MM-DD'
		}
	});

	/*$('#txtFecha').datepicker({changeYear:true});*/
	
	/*
	$('#txtFechaSaldos').monthpicker({changeYear:true});*/
	
	obtenerInformacionFinanciera()
	//obtenerSaldosDia()
	
	/*$("#ventanaLlamadasProspectos").dialog(
	{
		autoOpen:false,
		height:500,
		width:1050,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerLlamadasProspectos").html('');
		}
	});*/
});

function obtenerInformacionFinanciera()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerInformacionFinanciera').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerInformacionFinanciera',
		data:
		{
			fecha:			$('#txtFecha').val(),
			idEscenario:	$('#txtIdEscenario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerInformacionFinanciera').html(data);
		},
		error:function(datos)
		{
			$('#obtenerInformacionFinanciera').html('');
		}
	});		
}


$(document).ready(function()
{
	/*$("#ventanaGraficaSaldosFecha").dialog(
	{
		autoOpen:false,
		height:650,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerGraficaSaldosFecha").html('');
		}
	});*/
});

//GRÁFICA DE SALDOS
function obtenerGraficaSaldosFecha(fecha,importe)
{
	$("#ventanaGraficaSaldosFecha").modal('show');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGraficaSaldosFecha').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerGraficaSaldosFecha',
		data:
		{
			fecha:			fecha,
			importe:		importe,
			idEscenario:	$('#txtIdEscenario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGraficaSaldosFecha').html(data);
		},
		error:function(datos)
		{
			$('#obtenerGraficaSaldosFecha').html('');
		}
	});		
}

//DETALLE DE SALDOS
$(document).ready(function()
{
	/*$("#ventanaDetallesSaldosFecha").dialog(
	{
		autoOpen:false,
		height:650,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerDetalleSaldoFecha").html('');
		}
	});*/
});

function obtenerDetalleSaldoFecha(numero)
{
	$("#ventanaGraficaSaldosFecha").modal('hide');
	$("#ventanaDetallesSaldosFecha").modal('show');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetalleSaldoFecha').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerDetalleSaldoFecha',
		data:
		{
			fecha:			$('#txtFechaSaldos').val(),
			numero:			numero,
			idEscenario:	$('#txtIdEscenario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetalleSaldoFecha').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDetalleSaldoFecha').html('');
		}
	});		
}


$(document).ready(function()
{
	$('#txtMes').daterangepicker(
	{
		singleDatePicker: true,
		locale: 
		{
		  format: 'YYYY-MM-DD'
		}
	});
});

function obtenerDetallesCalendario(fecha)
{
	$("#ventanaDetallesCalendario").modal('show');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesCalendario').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerDetallesCalendario',
		data:
		{
			fecha:fecha
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesCalendario').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDetallesCalendario').html('');
		}
	});		
}

//GRÁFICA DE CRÉDITOS
function obtenerCalendarioPagos(Anio,Mes)
{
	if(Anio.length==0)
	{
		fecha	= $('#txtMes').val();
		fecha	= fecha.split('-');
		
		mes	 =	fecha[1];
		anio =	fecha[0];
	}
	else
	{
		mes	 =	Mes;
		anio =	Anio;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCalendarioPagos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerCalendarioPagos',
		data:
		{
			mes:		mes,
			anio:		anio,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCalendarioPagos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerCalendarioPagos').html('');
		}
	});		
}

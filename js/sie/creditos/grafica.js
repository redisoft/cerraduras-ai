$(document).ready(function()
{

});

function obtenerCreditosDetalles()
{
	$("#ventanaCreditosDetalles").modal('show');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCreditosDetalles').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerCreditosDetalles',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCreditosDetalles').html(data);
		},
		error:function(datos)
		{
			$('#obtenerCreditosDetalles').html('');
		}
	});		
}

//GRÁFICA DE CRÉDITOS
function obtenerGraficaCreditos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGraficaCreditos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerGraficaCreditos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGraficaCreditos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerGraficaCreditos').html('');
		}
	});		
}

$(document).ready(function()
{
	$(document).on("click", ".pagination > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerDetallesEgresosConceptos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				'inicio': 	$('#txtInicio').val(),
				'fin': 		$('#txtFin').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
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

function detallesEgresos()
{
	$("#ventanaDetallesEgresos").modal('show');
	
	obtenerDetallesEgresosConceptos()

}

function obtenerDetallesEgresosConceptos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesEgresosConceptos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerDetallesEgresosConceptos',
		data:
		{
			'inicio': 	$('#txtInicio').val(),
			'fin': 		$('#txtFin').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesEgresosConceptos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDetallesEgresosConceptos').html('');
		}
	});		
}

//GRÁFICA DE CRÉDITOS
function obtenerGraficaEgresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerGraficaEgresos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'sie/obtenerGraficaEgresos',
		data:
		{
			inicio: $('#txtInicio').val(),
			fin: 	$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGraficaEgresos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerGraficaEgresos').html('');
		}
	});		
}

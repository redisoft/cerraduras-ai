function obtenerDashboard()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerDashboard').html('<img src="'+ img_loader +'"/>El sistema esta graficando los datos...');
		},
		type:"POST",
		url:base_url+'dashboard/obtenerDashboard',
		data:
		{
			'inicio': 			$('#txtInicio').val(),
			'fin': 				$('#txtFin').val(),
			'idCuenta': 		$('#selectCuentaIngresos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDashboard').html(data);
			
			//obtenerGraficaClientes();
			
			
		},
		error:function(datos)
		{
			$('#obtenerDashboard').html('')
		}
	});		
}

function obtenerGraficaClientes()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerGraficaClientes').html('<img src="'+ img_loader +'"/>El sistema esta graficando los datos...');
		},
		type:"POST",
		url:base_url+'dashboard/obtenerGraficaClientes',
		data:
		{
			'inicio': 	$('#txtInicio').val(),
			'fin': 		$('#txtFin').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerGraficaClientes').html(data)
		},
		error:function(datos)
		{
			$('#obtenerGraficaClientes').html('')
		}
	});		
}
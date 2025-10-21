$(document).ready(function ()
{
	obtenerVentas()
	
	$('#txtBuscarCliente').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerVentas();
		}
	});
	
	$(document).on("click", ".ajax-pagVentas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":		$('#FechaDia').val(),
				"fin":			$('#FechaDia2').val(),
				"criterio":		$('#txtBuscarCliente').val(),
				"idZona":		$('#selectZonas').val(),
				"idUsuario":	$('#selectAgentes').val(),
				idEstacion:  	$('#selectEstaciones').val(),
				idForma:  		$('#selectFormas').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerVentas').html('<img src="'+ img_loader +'"/>Obteniendo las ventas...');
			},
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

function obtenerVentas()
{
	if(ejecutar && ejecutar.readyState != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerVentas').html('<img src="'+ img_loader +'"/> Obteniendo ventas...');},
		type:"POST",
		url:base_url+'reportes/obtenerVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idForma:  		$('#selectFormas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentas').html(data);
		},
		error:function(datos)
		{
			$("#obtenerVentas").html('');
		}
	});//Ajax		
}


function excelVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idForma:  		$('#selectFormas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarExcel/'+data;
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{

			$("#generandoExcel").html('');
		}
	});//Ajax		
}

function reporteVentas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoExcel').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteVentas',
		data:
		{
			"inicio":		$('#FechaDia').val(),
			"fin":			$('#FechaDia2').val(),
			"criterio":		$('#txtBuscarCliente').val(),
			"idZona":		$('#selectZonas').val(),
			"idUsuario":	$('#selectAgentes').val(),
			idEstacion:  	$('#selectEstaciones').val(),
			idForma:  		$('#selectFormas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoExcel').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReporteVentas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoExcel").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function busquedaCliente()
{
	cliente=document.getElementById('selectClientes').value;
	direccion="http://"+base_url+"reportes/busquedaClienteVentas/"+cliente;
	window.location.href=direccion;
}

function busquedaFechaVenta()
{
	if($('#FechaDia').val()=="" || $('#FechaDia2').val()=="")
	{
		notify('Seleccione las fechas correctamente',500,4000,"error");
		return;
	}
	
	location.href=base_url+"reportes/index/"+$('#FechaDia').val()+"/"+$('#FechaDia2').val()+"/";
}

$(document).ready(function()
{
	
	$("#txtBuscarZona").autocomplete(
	{
		source:base_url+'configuracion/obtenerZonas',
		
		select:function( event, ui)
		{
			location.href=base_url+"reportes/index/fecha/fecha/0/"+ui.item.idZona;
		}
	});
	
	$("#txtProductos").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario',
		
		select:function( event, ui)
		{
			location.href=base_url+"reportes/busquedaProductosVentas/"+ui.item.idProducto;
		}
	});
});


function buscarVentaZona()
{
	location.href=base_url+"reportes/index/fecha/fecha/0/"+$('#selectZonas').val();
}

function buscarVentaUsuario()
{
	location.href=base_url+"reportes/index/fecha/fecha/0/0/"+$('#selectAgentes').val();
}
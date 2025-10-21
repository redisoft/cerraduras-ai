//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE DEPOSITOS
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
    //$('.ajax-pagDepositos > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagDepositos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerDepositos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:			$('#txtMes').val(),
				idCuenta:		$('#selectCuentas').val(),
				idEmisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerDepositos').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de depósitos...');
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

function obtenerDepositos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDepositos').html('<img src="'+ img_loader +'"/>Obteniendo detalles de depósitos...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerDepositos',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDepositos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los depósitos',500,5000,'error',2,5);
			$("#obtenerDepositos").html('');
		}
	});
}

function reporteDepositos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteDepositos',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Depositos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelDepositos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelDepositos',
		data:
		{
			fecha:			$('#txtMes').val(),
			idCuenta:		$('#selectCuentas').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Depositos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
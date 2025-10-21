//OBTENER FACTURAS
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagHistorialMovimientos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerHistorialMovimientos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#FechaDia').val(),
				fin:			$('#FechaDia2').val(),
				idLicencia:		$('#selectLicencias').val(),
				usuario:		$('#selectUsuario').val(),
				modulo:			$('#selectModulo').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo detalles de movimientos...</label>');
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

function obtenerHistorialMovimientos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerHistorialMovimientos').html('<img src="'+ img_loader +'"/>Obteniendo detalles de movimientos...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerHistorialMovimientos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idLicencia:		$('#selectLicencias').val(),
			usuario:		$('#selectUsuario').val(),
			modulo:			$('#selectModulo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerHistorialMovimientos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los movimientos',500,5000,'error',30,5);
			$("#obtenerHistorialMovimientos").html('');
		}
	});
}

function excelHistorialMovimientos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelHistorialMovimientos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idLicencia:		$('#selectLicencias').val(),
			usuario:		$('#selectUsuario').val(),
			modulo:			$('#selectModulo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/historialMovimientos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteHistorialMovimientos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reporteHistorialMovimientos',
		data:
		{
			inicio:			$('#FechaDia').val(),
			fin:			$('#FechaDia2').val(),
			idLicencia:		$('#selectLicencias').val(),
			usuario:		$('#selectUsuario').val(),
			modulo:			$('#selectModulo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/historialMovimientos'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

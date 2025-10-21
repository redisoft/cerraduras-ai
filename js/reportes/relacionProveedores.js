//--------------------------------------------------------------------------------------
//PARA EL REPORTE DE RelacionProveedores
//--------------------------------------------------------------------------------------
$(document).ready(function ()
{
	$(document).on("click", ".ajax-pagRelacionProveedores > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRelacionProveedores";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				anio:			$('#selectAnio').val(),
				idProveedor:	$('#txtIdProveedor').val(),
				idEmisor:		$('#selectEmisores').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerRelacionProveedores').html('<img src="'+ img_loader +'"/>Obteniendo los detalles de relación...');
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

function obtenerRelacionProveedores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRelacionProveedores').html('<img src="'+ img_loader +'"/>Obteniendo detalles de relación...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerRelacionProveedores',
		data:
		{
			anio:			$('#selectAnio').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRelacionProveedores').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la relación',500,5000,'error',2,5);
			$("#obtenerRelacionProveedores").html('');
		}
	});
}

function reporteRelacionProveedores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'reportes/reporteRelacionProveedores',
		data:
		{
			anio:			$('#selectAnio').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/RelacionProveedores'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function excelRelacionProveedores()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelRelacionProveedores',
		data:
		{
			anio:			$('#selectAnio').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			idEmisor:		$('#selectEmisores').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/RelacionProveedores'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}
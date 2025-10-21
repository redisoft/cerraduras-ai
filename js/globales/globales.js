//OBTENER AUTORIZACIONES
$(document).ready(function ()
{
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerVentasGlobal();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagGlobal > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentasGlobal";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:		$('#FechaDia').val(),
				fin:		$('#FechaDia2').val(),
				criterio:	$('#txtCriterio').val(),
				idLicencia:	$('#selectLicencias').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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

function obtenerVentasGlobal()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}
	
	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentasGlobal').html('<img src="'+ img_loader +'"/>Obteniendo detalles de checador...');
		},
		type:"POST",
		url:base_url+'globales/obtenerVentasGlobal',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
			idLicencia:	$('#selectLicencias').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentasGlobal').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles',500,5000,'error',30,5);
			$("#obtenerVentasGlobal").html('');
		}
	});
}

function excelChecador()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');
		},
		type:"POST",
		url:base_url+'reportes/excelChecador',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Checador'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

function reporteChecador()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');
		},
		type:"POST",
		url:base_url+'reportes/reporteChecador',
		data:
		{
			inicio:		$('#FechaDia').val(),
			fin:		$('#FechaDia2').val(),
			criterio:	$('#txtCriterio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Checador'
			$('#generandoReporte').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte ',500,5000,'error',2,5);
			$("#generandoReporte").html('');
		}
	});		
}

//STOCK DE SUCURSALES
$(document).ready(function()
{
	$("#ventanaSucursales").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:430,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			$("#formularioSucursales").html('');
		}
	});
});

function formularioSucursales()
{
	$('#ventanaSucursales').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSucursales').html('<img src="'+ img_loader +'"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'globales/formularioSucursales',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSucursales').html(data);
		},
		error:function(datos)
		{
			$('#formularioSucursales').html('');
			notify('Error al obtener el formulario',500,5000,'error',30,5);
		}
	});		
}

function editarNumeroVentas(idLicencia)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#formularioSucursales').html('<img src="'+ img_loader +'"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'globales/editarNumeroVentas',
		data:
		{
			idLicencia: 	idLicencia,
			numeroVentas: 	obtenerNumeros($('#txtNumeroVentas'+idLicencia).val()),
			importeDinero: 	obtenerNumeros($('#txtImporteDinero'+idLicencia).val())
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#formularioSucursales').html(data);
			notify('Registro corrrecto',500,5000,'',30,5);
		},
		error:function(datos)
		{
			//$('#formularioSucursales').html('');
			notify('Error en el proceso',500,5000,'error',30,5);
		}
	});		
}


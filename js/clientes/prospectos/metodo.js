$(document).ready(function()
{
	$('#txtBuscarProspecto').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
	$('#txtInicio,#txtFin').datepicker();
	
	obtenerReporte()
	
	$(document).on("click", ".ajax-pagReporte > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerReporte";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idFuente:			$('#selectFuentesBusqueda').val(),
				idPrograma: 		$('#selectProgramaBusqueda').val(),
				idCampana: 			$('#selectCampanasBusqueda').val(),
				idPromotor: 		$('#selectPromotorBusqueda').val(),
				criterio: 			$('#txtBuscarProspecto').val(),
				idMetodo: 			$('#selectMetodoBusqueda').val(),
				inicio: 			$('#txtInicio').val(),
				fin: 				$('#txtFin').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerReporte').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
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

function obtenerReporte()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerMetodo',
		data:
		{
			idFuente:			$('#selectFuentesBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idPromotor: 		$('#selectPromotorBusqueda').val(),
			criterio: 			$('#txtBuscarProspecto').val(),
			idMetodo: 			$('#selectMetodoBusqueda').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporte').html(data);
		},
		error:function(datos)
		{
			$('#obtenerReporte').html('');
		}
	});		
}

function excelReporte()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#exportandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelMetodo',
		data:
		{
			idFuente:			$('#selectFuentesBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idPromotor: 		$('#selectPromotorBusqueda').val(),
			criterio: 			$('#txtBuscarProspecto').val(),
			idMetodo: 			$('#selectMetodoBusqueda').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Metodo';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}

function desactivarTodo()
{
	$('#selectCualificado').fadeOut();
	$('#selectEstatusCualificado').fadeOut();
	$('#selectInteresado').fadeOut();
	$('#selectDetallesCualificado').fadeOut();
	
	$('#selectCualificado').val('');
	$('#selectEstatusCualificado').val('0');
	$('#selectInteresado').val('');
	$('#selectDetallesCualificado').val('0');
}

function sugerirSelectorCualificado()
{
	desactivarTodo()
	
	switch($('#selectContactado').val())
	{
		case "1":
			$('#selectCualificado').fadeIn();
		break;

		default:
			$('#selectCualificado').fadeOut();
		break;
	}
}

function sugerirOpcionesCualificado()
{
	$('#selectEstatusCualificado').val('0');
	$('#selectDetallesCualificado').fadeOut();
	$('#selectDetallesCualificado').val('0');
	$('#selectInteresado').val('')
	
	switch($('#selectCualificado').val())
	{
		case "0":
			$('#selectEstatusCualificado').fadeIn();
			$('#selectInteresado').fadeOut();
		break;
		
		case "1":
			$('#selectEstatusCualificado').fadeOut();
			$('#selectInteresado').fadeIn();
		break;
		
		default:
			$('#selectEstatusCualificado').fadeOut();
			$('#selectInteresado').fadeOut();
			
		break;
	}
}

function sugerirOpcionesInteresado()
{
	$('#selectDetallesCualificado').val('0');

	
	switch($('#selectInteresado').val())
	{
		case "0":
			$('#selectDetallesCualificado').fadeIn();
		break;
		
		case "1":
			$('#selectDetallesCualificado').fadeOut();
		break;
		
		default:
			$('#selectDetallesCualificado').fadeOut();
		break;
	}
}

//PARA LA BUSQUEDA DE LOS PROSPECTOS
function sugerirBusquedaProspectos()
{
	$('#selectProspectosBusqueda').val('0');
	
	if(document.getElementById('chkFiltroProspectos').checked)
	{
		$('#selectContactado').fadeOut();
		$('#selectProspectosBusqueda').fadeIn();
		$('#selectDetallesProspecto').fadeOut();
		desactivarTodo()
	}
	else
	{
		$('#selectContactado').fadeIn();
		$('#selectProspectosBusqueda').fadeOut();
		$('#selectDetallesProspecto').fadeOut();
		desactivarTodo()
	}
}

function sugerirOpcionesProspecto()
{
	$('#selectDetallesProspecto').val('0');

	switch($('#selectProspectosBusqueda').val())
	{
		case "5":
			$('#selectDetallesProspecto').fadeIn();
		break;
		
		default:
			$('#selectDetallesProspecto').fadeOut();
		break;
	}
}

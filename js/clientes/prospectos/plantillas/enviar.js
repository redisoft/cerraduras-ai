//PLANTILLAS
$(document).ready(function()
{
	$("#ventanaEnviarPlantilla").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				confirmarEnviarPlantilla();				 
			},
		},
		close: function()
		{
			$("#obtenerPlantillaEnviar").html('');
			$('#enviandoPlantilla').html('')
		}
	});
});

porcion		= 0;
porcentaje	= 0;
total1		= 0;

function obtenerPlantillaEnviar()
{
	if($('#txtNumeroTotalProspectos').val()==0)
	{
		notify('Sin registros para enviar la plantilla',500,5000,'error',30,5);
		return;
	}
	
	$('#ventanaEnviarPlantilla').dialog('open');
	
	if($('#txtTipoPlantilla').val()=="0")
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerPlantillaEnviar').html('<img src="'+ img_loader +'"/> Obteniendo plantilla, por favor espere...');
			},
			type:"POST",
			url:base_url+'crm/obtenerPlantillaEnviar',
			data:
			{
				criterio:			$('#txtBusquedas').val(),
				idResponsable:		$('#selectResponsableBusqueda').val(),
				idStatus:			$('#selectStatusBusqueda').val(),
				idZona:				$('#selectZonasBuscar').val(),
				idServicio:			$('#selectServicioBusqueda').val(),
				fecha:				$('#FechaDia2').val()==""?"fecha":$('#FechaDia2').val(),
				mes:				$('#txtFechaMes').val()==""?"mes":$('#txtFechaMes').val(),
				idTipo:				$('#selectBusquedaTipo').val(),
				
				
				idPromotor:			$('#selectPromotorBusqueda').val(),
				idEstatus:			$('#selectEstatusBuscar').val(),
					
				tipoRegistro: 		$('#txtTipoRegistro').val(),
				criterioSeccion:	$('#txtCriterioSeccion').val(),
				
				fechaFin:			$('#txtFechaFin').val()==""?"fecha":$('#txtFechaFin').val(),
				
				numeroSeguimientos:	$('#selectNumeroSeguimientos').val(),
				idCampana:			$('#selectCampanasBusqueda').val(),
				idPrograma:			$('#selectProgramaBusqueda').val(),
				diaPago:			$('#selectDiaPago').val(),
				
				idFuente:			$('#selectFuentesBusqueda').val(),
				
				tipoFecha:			$('#selectTipoFecha').val(),
				inicial:			$('#txtFechaProspectosInicio').val(),
				final:				$('#txtFechaProspectosFin').val(),
				
				tipoPlantilla:		$('#txtTipoPlantilla').val(),
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#obtenerPlantillaEnviar').html(data);
				
				total		= obtenerNumeros($('#txtNumeroCorreos').val());
				porcion		= 100/total;
				porcentaje	= 0;
			},
			error:function(datos)
			{
				$('#obtenerPlantillaEnviar').html('');
				notify('Error al obtener los registros',500,5000,'error',30,5);
			}
		});	
	}
	
	if($('#txtTipoPlantilla').val()=="1")
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#obtenerPlantillaEnviar').html('<img src="'+ img_loader +'"/> Obteniendo plantilla, por favor espere...');
			},
			type:"POST",
			url:base_url+'crm/obtenerPlantillaEnviar',
			data:
			{
				criterio:			$('#txtBuscarLlamada').val(),
				inicio:				$('#FechaDia').val(),
				fin:				$('#FechaDia2').val(),
				idStatus:			$('#selectStatusBusqueda').val(),
				idServicio:			$('#selectServiciosBusqueda').val(),
				
				idUsuarioRegistro:	$('#selectUsuarios').val(),
				idResponsable:		$('#selectResponsables').val(),
				idEstatus:			$('#selectEstatusBuscar').val(),
				
				idPrograma:			$('#selectProgramasBuscar').val(),
				
				tipoPlantilla:		$('#txtTipoPlantilla').val(),
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#obtenerPlantillaEnviar').html(data);
				
				total		= obtenerNumeros($('#txtNumeroCorreos').val());
				porcion		= 100/total;
				porcentaje	= 0;
			},
			error:function(datos)
			{
				$('#obtenerPlantillaEnviar').html('');
				notify('Error al obtener los registros',500,5000,'error',30,5);
			}
		});	
	}
	
		
}

function confirmarEnviarPlantilla(avance)
{
	if($('#selectPlantillas').val()==0)
	{
		notify('Seleccione la plantilla',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea enviar la plantilla?'))return;
	
	enviarPlantilla(0);
}

function enviarPlantilla(n)
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#enviandoPlantilla').html('<img src="'+ img_loader +'"/> Enviando plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/enviarPlantilla',
		data:
		{
			prospecto:			$('#txtNombreProspecto'+n).val(),
			email:				$('#txtEmailProspecto'+n).val(),
			promotor:			$('#txtNombrePromotor'+n).val(),
			emailPromotor:		$('#txtEmailPromotor'+n).val(),
			idCliente:			$('#txtIdCliente'+n).val(),
			idPlantilla:		$('#selectPlantillas').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#enviandoPlantilla').html('')
			
			n++;
			
			porcentaje	+= porcion;
			porcentaje	= redondear(porcentaje);
			porcentaje	= obtenerNumeros(porcentaje);
			
			if(porcentaje>100) porcentaje=100;
			
			progresoPlantilla(porcentaje);
			
			if(n<total)
			{
				window.setTimeout(function() 
				{
					enviarPlantilla(n)
				}, 250);
			}
			else
			{
				
				window.setTimeout(function() 
				{
					notify('La plantilla se ha enviado correctamente',500,5000,'',30,5);
					$('#ventanaEnviarPlantilla').dialog('close');
				}, 1500);
			}
			
		},
		error:function(datos)
		{
			$('#registrandoPlantillas').html('');
			$('#enviandoPlantilla').html('')
			notify('¡Error al enviar la plantilla!',500,5000,'error',30,5);
		}
	});		
}

function progresoPlantilla(pro)
{
	var $pb = $('.progress .progress-bar');
	 
	 $pb.attr('data-transitiongoal', obtenerNumeros(pro)).progressbar({display_text: 'center'});
}

function progresoPlantillas()
{
	var $pb = $('.progress .progress-bar');
 
	$pb.attr('data-transitiongoal', obtenerNumeros($('#txtProgreso').val())).progressbar({display_text: 'center'});
}

//PLANTILLAS ENVIADS

//PLANTILLAS
$(document).ready(function()
{
	$("#ventanaPlantillasEnviadas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close')
			},
		},
		close: function()
		{
			$("#obtenerPlantillasEnviadas").html('');
		}
	});
});

function obtenerPlantillasEnviadas(idCliente)
{
	$('#ventanaPlantillasEnviadas').dialog('open')
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerPlantillasEnviadas').html('<img src="'+ img_loader +'"/> Obteniendo envíos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerPlantillasEnviadas',
		data:
		{
			idCliente:			idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPlantillasEnviadas').html(data);
		},
		error:function(datos)
		{
			$('#registrandoPlantillas').html('');
		}
	});		
}

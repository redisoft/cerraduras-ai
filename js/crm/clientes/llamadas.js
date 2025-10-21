$(document).ready(function()
{
	$("#txtBuscarLlamada").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerLlamadas();
		}, milisegundos);
	});
	
	//$('.ajax-pagLlamadas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagLlamadas > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerLlamadas";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
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
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo detalles de llamadas...');},
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

function obtenerLlamadas()
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
			$('#obtenerLlamadas').html('<img src="'+ img_loader +'"/>Obteniendo detalles de llamadas...');
		},
		type:"POST",
		url:base_url+'cotizaciones/obtenerLlamadas',
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
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerLlamadas').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las llamadas',500,4000,"error",30,5);
			$("#obtenerLlamadas").html('');
		}
	});
}

function excelReporteLlamadas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'cotizaciones/excelReporteLlamadas',
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
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Quejas';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#procesandoInformacion").html('');
		}
	});//Ajax		
}

$(document).ready(function()
{	
	$("#ventanaEditarEstatusSeguimiento").dialog(
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
				editarEstatusSeguimientoDetalle()
			},
		},
		close: function() 
		{
			$("#obtenerEstatusSeguimientoEditar").html('');
		}
	});
});

function mostrarFechasEstatus()
{
	if($('#selectEstatusEditar').val()=="3")
	{
		$('#filaFechasEstatus').fadeIn()
	}
	else
	{
		$('#filaFechasEstatus').fadeOut()
	}
}

function obtenerEstatusSeguimientoEditar(idSeguimiento)
{
	$("#ventanaEditarEstatusSeguimiento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEstatusSeguimientoEditar').html('<img src="'+ img_loader +'"/>Obteniendo detalles del formulario...');
		},
		type:"POST",
		url:base_url+'crm/obtenerEstatusSeguimientoEditar',
		data:
		{
			idSeguimiento:idSeguimiento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEstatusSeguimientoEditar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerEstatusSeguimientoEditar').html('');
			notify('Error en el procesao',500,5000,'error',30,5);
		}
	});		
}


function editarEstatusSeguimientoDetalle()
{
	if(!confirm('¿Realmente desea editar el seguimiento?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#editandoEstatusSeguimiento').html('<img src="'+ img_loader +'"/>Se esta editando el seguimiento CRM...');
		},
		type:"POST",
		url:base_url+"crm/editarEstatusSeguimientoDetalle",
		data:
		{
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			"idEstatus":		$("#selectEstatusEditar").val(),
			"fechaResuelta":	$("#txtFechaEstatus").val(),
			"horaResuelta":		$("#txtHoraEstatus").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEstatusSeguimiento').html('');
			
			switch(data)
			{
				case "0":
					notify('¡El seguimiento no tuvo cambios!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡Seguimiento editado!',500,5000,'',30,5);
					obtenerLlamadas()
					$("#ventanaEditarEstatusSeguimiento").dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoEstatusSeguimiento').html('')
			notify('Error al editar el seguimiento',500,5000,'error',30,5);
		}
	});					  	  
}

function borrarSeguimientoCrm(idSeguimiento)
{
	if(!confirm('¿Realmente desea borrar el seguimiento?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+ img_loader +'"/>Se esta borrando el seguimiento de CRM...');
		},
		type:"POST",
		url:base_url+'clientes/borrarSeguimientoErp',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('¡Seguimiento borrado!',500,5000,'',0,0);
			$('#procesandoInformacion').html('');
			obtenerLlamadas();
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify('Error al borrar el seguimiento de CRM',500,5000,'error',0,0);
		}
	});		
}

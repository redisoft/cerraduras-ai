$(document).ready(function()
{
	$('#txtBusquedasNocuali').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerNocuali();
		}
	});

	$(document).on("click", ".ajax-pagNoCuali > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerNocuali";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtBusquedasNocuali').val(),
				idPrograma:		$('#selectProgramasBuscar').val(),
				idCausa:		$('#selectCausaBuscar').val(),
				idCampana:		$('#selectCampanaBuscar').val(),
				idPromotor:		$('#selectPromotorBusqueda').val(),
				inicio:			$('#txtFechaInicio').val(),
				fin:			$('#txtFechaFin').val(),
				idFuente:		$('#selectFuentesBuscar').val(),
				idDetalle:		$('#selectDetalleBuscar').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerNocuali').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function obtenerNocuali()
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
			$('#obtenerNocuali').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"clientes/obtenerNocuali",
		data:
		{
			criterio:		$('#txtBusquedasNocuali').val(),
			idPrograma:		$('#selectProgramasBuscar').val(),
			idCausa:		$('#selectCausaBuscar').val(),
			idCampana:		$('#selectCampanaBuscar').val(),
			idPromotor:		$('#selectPromotorBusqueda').val(),
			inicio:			$('#txtFechaInicio').val(),
			fin:			$('#txtFechaFin').val(),
			idFuente:		$('#selectFuentesBuscar').val(),
			idDetalle:		$('#selectDetalleBuscar').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNocuali').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#obtenerNocuali").html('');	
		}
	});				
}

function excelNocuali()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'clientes/excelNocuali',
		data:
		{
			criterio:		$('#txtBusquedasNocuali').val(),
			idPrograma:		$('#selectProgramasBuscar').val(),
			idCausa:		$('#selectCausaBuscar').val(),
			idCampana:		$('#selectCampanaBuscar').val(),
			idPromotor:		$('#selectPromotorBusqueda').val(),
			inicio:			$('#txtFechaInicio').val(),
			fin:			$('#txtFechaFin').val(),
			idFuente:		$('#selectFuentesBuscar').val(),
			idDetalle:		$('#selectDetalleBuscar').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Nocualificado';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
		}
	});//Ajax		
}



function reactivarProspecto(idCliente)
{
	if(!confirm('¿Realmente desea reactivar al prospecto?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"crm/reactivarProspecto",
		data:
		{
			idCliente:		idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			notify('El prospecto se ha reactivado',500,4000,"",30,5);
			obtenerNocuali();
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#exportandoDatos").html('');	
		}
	});				
}

function reactivarProspectoSeguimiento(idCliente)
{
	if(!confirm('¿Realmente desea reactivar al prospecto?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"crm/reactivarProspecto",
		data:
		{
			idCliente:		idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			notify('El prospecto se ha reactivado',500,4000,"",30,5);
			obtenerClientes();
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#exportandoDatos").html('');	
		}
	});				
}



$(document).ready(function()
{
	$("#ventanaHistorialSeguimiento").dialog(
	{
		autoOpen:false,
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerHistorialSeguimiento").html('');
		}
	});
});

function obtenerHistorialSeguimiento(idCliente)
{
	$("#ventanaHistorialSeguimiento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerHistorialSeguimiento').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"crm/obtenerHistorialSeguimiento",
		data:
		{
			idCliente:		idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerHistorialSeguimiento').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#obtenerHistorialSeguimiento").html('');	
		}
	});				
}





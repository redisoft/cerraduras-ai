$(document).ready(function()
{
	$('#txtBusquedasBajas').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerBajas();
		}
	});

	$(document).on("click", ".ajax-pagBajas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerBajas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtBusquedasBajas').val(),
				idPrograma:		$('#selectProgramasBuscar').val(),
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
				$('#obtenerBajas').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function obtenerBajas()
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
			$('#obtenerBajas').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"clientes/obtenerNoDisponible",
		data:
		{
			criterio:		$('#txtBusquedasBajas').val(),
			idPrograma:		$('#selectProgramasBuscar').val(),
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
			$('#obtenerBajas').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#obtenerBajas").html('');	
		}
	});				
}

function excelBajas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'clientes/excelNoDisponible',
		data:
		{
			criterio:		$('#txtBusquedasBajas').val(),
			idPrograma:		$('#selectProgramasBuscar').val(),
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
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/NoDisponible';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoDatos").html('');
		}
	});//Ajax		
}

/*cliente=0;

function obtenerCliente(idCliente,banco)
{
	$('#ventanaEditarClientes').dialog('open');
	
	cliente=idCliente;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarClientes').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerCliente',
		data:
		{
			"idCliente":	idCliente,
			tipoRegistro: 	$('#txtTipoRegistro').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarClientes').html(data);
			
			if(banco=='1')
			{
				configurarTabsCliente('cuentasBanco');
			}
		},
		error:function(datos)
		{
			$('#cargarClientes').html('Error al obtener el cliente');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaEditarClientes").dialog(
	{
		autoOpen:false,
		height:650,
		width:1010,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				editarCliente();	  	  
			},
		},
		close: function() 
		{
			$("#cargarClientes").html('');
		}
	});
});
*/
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
			obtenerBajas();
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





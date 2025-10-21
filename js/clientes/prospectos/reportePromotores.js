$(document).ready(function()
{
	$('#txtFechaInicial,#txtFechaFinal').datepicker();
	
	$('#txtBuscarProspecto').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
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
			  	idPromotor:			$('#selectPromotorBusqueda').val(),
				idPrograma: 		$('#selectProgramaBusqueda').val(),
				idCampana: 			$('#selectCampanasBusqueda').val(),
				inicio: 			$('#txtFechaInicial').val(),
				fin: 				$('#txtFechaFinal').val(),
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
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerReportePromotores',
		data:
		{
			idPromotor:			$('#selectPromotorBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			inicio: 			$('#txtFechaInicial').val(),
			fin: 				$('#txtFechaFinal').val(),
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
		async:true,
		beforeSend:function(objeto){$('#exportandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelReportePromotores',
		data:
		{
			idPromotor:			$('#selectPromotorBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			inicio: 			$('#txtFechaInicial').val(),
			fin: 				$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Promotores';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}


$(document).ready(function()
{
	$("#ventanaDetalleInscritos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:1100,
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
			$("#obtenerDetalleInscritos").html('');
		}
	});
});

function obtenerDetalleInscritos(idPromotor,idCampana)
{
	$("#ventanaDetalleInscritos").dialog('open');
	$('#ventanaDetalleInscritos').dialog('option', 'title', 'Inscritos');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetalleInscritos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerDetalleInscritos',
		data:
		{
			idPromotor:			idPromotor,
			idCampana: 			idCampana,
			inicio: 			$('#txtFechaInicial').val(),
			fin: 				$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetalleInscritos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDetalleInscritos').html('');
		}
	});		
}

function obtenerDetallePreinscritos(idPromotor,idCampana)
{
	$("#ventanaDetalleInscritos").dialog('open');
	$('#ventanaDetalleInscritos').dialog('option', 'title', 'Pre-inscritos');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetalleInscritos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerDetallePreinscritos',
		data:
		{
			idPromotor:			idPromotor,
			idCampana: 			idCampana,
			inicio: 			$('#txtFechaInicial').val(),
			fin: 				$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetalleInscritos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDetalleInscritos').html('');
		}
	});		
}


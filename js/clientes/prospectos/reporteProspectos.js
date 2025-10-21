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
				idPrograma: 		$('#selectProgramasBusqueda').val(),
				idCampana: 			$('#selectCampanasBusqueda').val(),
				idFuente: 			$('#selectFuentesBusqueda').val(),
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
		url:base_url+'crm/obtenerReporteProspectos',
		data:
		{
			idPromotor:			$('#selectPromotorBusqueda').val(),
			idPrograma: 		$('#selectProgramasBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idCampanaOriginal: 	$('#selectCampanasOriginal').val(),
			idFuente: 			$('#selectFuentesBusqueda').val(),
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
		url:base_url+'crm/excelReporteProspectos',
		data:
		{
			idPromotor:			$('#selectPromotorBusqueda').val(),
			idPrograma: 		$('#selectProgramasBusqueda').val(),
			idCampanaOriginal: 	$('#selectCampanasOriginal').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idFuente: 			$('#selectFuentesBusqueda').val(),
			inicio: 			$('#txtFechaInicial').val(),
			fin: 				$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Prospectos';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}


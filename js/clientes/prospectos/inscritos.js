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
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'crm/obtenerInscritos',
		data:
		{
			idFuente:			$('#selectFuentesBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idPromotor: 		$('#selectPromotorBusqueda').val(),
			criterio: 			$('#txtBuscarProspecto').val(),
			
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
		async:true,
		beforeSend:function(objeto){$('#exportandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelInscritos',
		data:
		{
			idFuente:			$('#selectFuentesBusqueda').val(),
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			idPromotor: 		$('#selectPromotorBusqueda').val(),
			criterio: 			$('#txtBuscarProspecto').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Inscritos';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}


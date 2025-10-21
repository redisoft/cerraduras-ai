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
		url:base_url+'crm/obtenerReporteEmbudo',
		data:
		{
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
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
		url:base_url+'crm/excelReporteEmbudo',
		data:
		{
			idPrograma: 		$('#selectProgramaBusqueda').val(),
			idCampana: 			$('#selectCampanasBusqueda').val(),
			inicio: 			$('#txtInicio').val(),
			fin: 				$('#txtFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Embudo';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoReporte").html('');
		}
	});//Ajax		
}


$(document).ready(function()
{
	$('#txtBusquedaAtrasos').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerAtrasos();
		}
	});
	
	$('#txtInicioAtrasos,#txtFinAtrasos').datepicker();
	
	$("#ventanaAtrasos").dialog(
	{
		autoOpen:false,
		height:650,
		width:1150,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			//$("#obtenerAtrasos").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagAtrasos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerAtrasos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#txtFechaInicialAtrasos').val(),
				fin: 			$('#txtFechaFinalAtrasos').val(),
				idUsuario: 		$('#selectPromotoresAtrasos').val(),
				registros: 		$('#selectRegistrosAtrasos').val(),
				
				criterio: 		$('#txtBusquedaAtrasos').val(),
				editar: 		$('#txtCriterioAtrasosEditar').val(),
				inicio: 		$('#txtInicioAtrasos').val(),
				fin: 			$('#txtFinAtrasos').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerAtrasos').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
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

function obtenerAtrasos()
{
	$('#ventanaAtrasos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerAtrasos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerAtrasos',
		data:
		{
			inicio:			$('#txtFechaInicialAtrasos').val(),
			fin: 			$('#txtFechaFinalAtrasos').val(),
			idUsuario: 		$('#selectPromotoresAtrasos').val(),
			registros: 		$('#selectRegistrosAtrasos').val(),
			criterio: 		$('#txtBusquedaAtrasos').val(),
			editar: 		$('#txtCriterioAtrasosEditar').val(),
			
			
			inicio: 		$('#txtInicioAtrasos').val(),
			fin: 			$('#txtFinAtrasos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerAtrasos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerAtrasos').html('');
		}
	});		
}

function excelAtrasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoAtrasos').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelAtrasos',
		data:
		{
			inicio:			$('#txtFechaInicialAtrasos').val(),
			fin: 			$('#txtFechaFinalAtrasos').val(),
			idUsuario: 		$('#selectPromotoresAtrasos').val(),
			registros: 		$('#selectRegistrosAtrasos').val(),
			criterio: 		$('#txtBusquedaAtrasos').val(),
			inicio: 		$('#txtInicioAtrasos').val(),
			fin: 			$('#txtFinAtrasos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoAtrasos').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Atrasos';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#procesandoAtrasos").html('');
		}
	});//Ajax		
}



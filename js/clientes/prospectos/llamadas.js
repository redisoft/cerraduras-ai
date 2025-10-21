$(document).ready(function()
{
	$("#ventanaLlamadasProspectos").dialog(
	{
		autoOpen:false,
		height:500,
		width:1050,
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
			$("#obtenerLlamadasProspectos").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagLlamadasProspecto > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerLlamadasProspectos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:			$('#txtFechaInicialLlamadas').val(),
				fin: 			$('#txtFechaFinalLlamadas').val(),
				src: 			$('#selectPromotoresProspecto').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerLlamadasProspectos').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
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

function obtenerLlamadasProspectos()
{
	$('#ventanaLlamadasProspectos').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerLlamadasProspectos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerLlamadasProspectos',
		data:
		{
			inicio:			$('#txtFechaInicialLlamadas').val(),
			fin: 			$('#txtFechaFinalLlamadas').val(),
			src: 			$('#selectPromotoresProspecto').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerLlamadasProspectos').html(data);
		},
		error:function(datos)
		{
			$('#obtenerLlamadasProspectos').html('');
		}
	});		
}


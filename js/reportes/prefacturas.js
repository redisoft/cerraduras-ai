//==============================================================================================//
//=====================================EXCEL COBRANZA==========================================//
//==============================================================================================//
//OBTENER INVENTARIOS DE MATERIA PRIMA
$(document).ready(function ()
{
	obtenerReporte()
	
	$('#txtCriterioBusqueda').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
	$(document).on("click", ".ajax-pagReporte > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerReporte";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio: 	$('#FechaDia').val(),
				fin: 		$('#FechaDia2').val(),
				criterio: 	$('#txtCriterioBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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
		beforeSend:function(objeto){$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'reportes/obtenerPrefacturas',
		data:
		{
			inicio: 	$('#FechaDia').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporte').html(data);
		},
		error:function(datos)
		{
			$("#obtenerReporte").html('');
			notify('Error al obtener los registros',500,5000,'error',2,5);
		}
	});	
}


function excelReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelPrefacturas',
		data:
		{
			inicio: 	$('#FechaDia').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reportePrefacturas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});	
}

function pdfReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reportePrefacturas',
		data:
		{
			inicio: 	$('#FechaDia').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/reportePrefacturas'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function ticketReporte()
{
	document.forms['frmEnvios'].submit();
}

/*function ticketReporte()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
		},
		type:"POST",
		url:base_url+"reportes/ticketEnvios",
		data:
		{
			inicio: 	$('#FechaDia').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),
			idRuta: 	$('#selectRutas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#procesandoRecepciones').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al imprimir el ticket',500,5000,'error',30,5);
				break;
				
				default:
					$('#ticketReporte').html(data);
				
					etiquetas 			= document.getElementById('ticketReporte');
					ventanaImprimir 	= window.open(' ', 'popimpr');

					ventanaImprimir.document.write( etiquetas.innerHTML );
					ventanaImprimir.document.close();
					ventanaImprimir.print( );
					ventanaImprimir.close();
					$('#ticketReporte').html('');
					
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al imprimir el ticket ',500,5000,'error',30,10);
		}
	});	
}*/

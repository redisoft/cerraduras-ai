//==============================================================================================//
//=====================================EXCEL COBRANZA==========================================//
//==============================================================================================//
//OBTENER INVENTARIOS DE MATERIA PRIMA
$(document).ready(function ()
{
	$('#FechaCorteDiario').datepicker();
	obtenerReporte()
});

function obtenerReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'reportes/obtenerCorteDiario',
		data:
		{
			fecha: 	$('#FechaCorteDiario').val(),
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
		url:base_url+'reportes/excelEnvios',
		data:
		{
			inicio: 	$('#FechaCorteDiario').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),
			idRuta: 	$('#selectRutas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteEnvios'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});	
}

function pdfReporteCorte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteCorteDiario',
		data:
		{
			fecha: 	$('#FechaCorteDiario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/CorteDiario'
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

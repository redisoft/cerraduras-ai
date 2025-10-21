//==============================================================================================//
//=====================================     ENVÍOS    ==========================================//
//==============================================================================================//
$(document).ready(function ()
{
	$("#txtFechaInicioInventario,#txtFechaFinInventario").datepicker();

	$('#txtBuscarProducto,#txtBuscarOrden,#txtBuscarFolioTicket').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporteInventario();
		}
	});

	$("#ventanaReporteInventario").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaReporteInventario').dialog('close')
			},			
		},
		close: function() 
		{

		}
	});
	
	$(document).on("click", ".ajax-pagReporteInventario > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerReporteInventario";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio: 		$('#txtFechaInicioInventario').val(),
				fin: 			$('#txtFechaFinInventario').val(),
				criterio: 		$('#txtBuscarProducto').val(),
				folio: 			$('#txtBuscarOrden').val(),
				folioTicket: 	$('#txtBuscarFolioTicket').val(),
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
					$(element).html(html);
					$("#tablaInventario tr:even").addClass("sombreado");
					$("#tablaInventario tr:odd").addClass("sinSombra");  
					},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
});

function obtenerReporteInventario()
{
	$('#ventanaReporteInventario').dialog('open')

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerReporteInventario').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'reportes/obtenerReporteInventario',
		data:
		{
			inicio: 		$('#txtFechaInicioInventario').val(),
			fin: 			$('#txtFechaFinInventario').val(),
			criterio: 		$('#txtBuscarProducto').val(),
			folio: 			$('#txtBuscarOrden').val(),
			folioTicket: 	$('#txtBuscarFolioTicket').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporteInventario').html(data);

			$("#tablaInventario tr:even").addClass("sombreado");
			$("#tablaInventario tr:odd").addClass("sinSombra");  
		},
		error:function(datos)
		{
			$("#obtenerReporteInventario").html('');
			notify('Error al obtener los registros',500,5000,'error',2,5);
		}
	});	
}

function pdfInventario()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoReporteInventario').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/pdfInventario',
		data:
		{
			inicio: 		$('#txtFechaInicioInventario').val(),
			fin: 			$('#txtFechaFinInventario').val(),
			criterio: 		$('#txtBuscarProducto').val(),
			folio: 			$('#txtBuscarOrden').val(),
			folioTicket: 	$('#txtBuscarFolioTicket').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoReporteInventario').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/inventarioEntregas'
		},
		error:function(datos)
		{
			$("#procesandoReporteInventario").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function excelInventario()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoReporteInventario').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelInventario',
		data:
		{
			inicio: 		$('#txtFechaInicioInventario').val(),
			fin: 			$('#txtFechaFinInventario').val(),
			criterio: 		$('#txtBuscarProducto').val(),
			folio: 			$('#txtBuscarOrden').val(),
			folioTicket: 	$('#txtBuscarFolioTicket').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoReporteInventario').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/inventarioEntregas'
		},
		error:function(datos)
		{
			$("#procesandoReporteInventario").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});	
}

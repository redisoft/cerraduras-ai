//OBTENER FACTURAS
function obtenerFacturasCliente()
{
	$("#ventanaFacturasCliente").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFacturasCliente').html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'reportes/obtenerFacturasCliente',
		data:
		{
			fecha:		$('#txtMes').val(),
			idCliente:	$('#txtIdCliente').val(),
			idFactura:	0,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFacturasCliente').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las facturas',500,5000,'error',2,5);
			$("#obtenerFacturasCliente").html('');
		}
	});
}


function excelFacturacion(mes,anio,idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelFacturacion/'+mes+'/'+anio+'/'+idCliente,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteFacturacion'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});//Ajax		
}

function zipearFacturas(mes,anio,idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> El sistema esta zipeando las facturas...');},
		type:"POST",
		url:base_url+'reportes/zipearFacturas/'+mes+'/'+anio+'/'+idCliente,
		data:
		{
			//"idBodega":idBodega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargaZip/'+data
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al zipear las facturas',500,5000,'error',2,5);
		}
	});//Ajax		
}

$(document).ready(function()
{
	$("#ventanaFacturasCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:900,
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
			$("#obtenerFacturasCliente").html('');
		}
	});
	
	//$('.ajax-pagFactu > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagFactu > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerFacturasCliente";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:		$('#txtMes').val(),
				idCliente:	$('#txtIdCliente').val(),
				idFactura:	0,
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerFacturasCliente').html('<img src="'+ img_loader +'"/>Obteniendo las facturas, por favor tenga paciencia...');
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

$(document).ready(function()
{
	$("#txtMes").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
});

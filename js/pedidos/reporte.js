
function formularioReporte(idPedido)
{
	$('#ventanaFormularioReporte').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioReporte').html('<img src="'+ img_loader +'"/> Obteniendo formulario de reporte...');
		},
		type:"POST",
		url:base_url+"pedidos/formularioReporte",
		data:
		{
			"idPedido":idPedido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioReporte').html(data);
			
			calcularImportesReporte()
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de reporte',500,5000,'error',30,3)
			$("#formularioReporte").html('');	
		}
	});
}

$(document).ready(function()
{	
	$('#txtInicioPanaderos,#txtFinPanaderos').datepicker();
	
	$("#ventanaFormularioReporte").dialog(
	{
		autoOpen:false,
		height:400,
		width:750,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarReporte()				  	  
			},
		},
		close: function() 
		{
			$("#formularioReporte").html('');
		}
	});
});

function registrarReporte()
{
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoReporte').html('<img src="'+ img_loader +'"/> Registrando reporte, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/registrarReporte",
		data:$('#frmReporte').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoReporte').html('');
			data	= eval(data);
			
			notify(data[1],500,5000,'',30,5);
			location.href=base_url+'reportes/pedidoReporte/'+$('#txtIdPedidoReporte').val();
			$('#ventanaFormularioReporte').dialog('close');
			
			/*switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					location.href=base_url+'reportes/pedidoReporte/'+$('#txtIdReporte').val();
					$('#ventanaFormularioReporte').dialog('close');
				break;
			}*/
		},
		error:function(datos)
		{
			$('#registrandoReporte').html('');
			notify('Error al registrar el reporte',500,5000,'error',30,3);	
		}
	});
}

function calcularImportesReporte()
{
	mano			= obtenerNumero($('#txtManoObra').val());
	cuotaSindical	= obtenerNumero($('#txtCuotaSindical').val());
	primaDominical	= obtenerNumero($('#txtPrimaDominical').val());
	total			= obtenerNumero($('#txtTotalReporte').val());
	
	if(mano>99)
	{
		mano=0;
		('#txtManoObra').val(0)
	}
	
	if(cuotaSindical>99)
	{
		cuotaSindical=0;
		('#txtCuotaSindical').val(0)
	}
	
	if(primaDominical>99)
	{
		primaDominical=0;
		('#txtPrimaDominical').val(0)
	}
	
	mano		= mano>0?mano/100:0;
	manoTotal	= mano*total;
	
	/*cuotaSindical	= cuotaSindical>0?cuotaSindical/100:0; //MOVERLA ABAJO
	cuotaTotal		= cuotaSindical*manoTotal;*/
	
	primaDominical	= primaDominical>0?primaDominical/100:0;
	primaTotal		= primaDominical*manoTotal;
	
	//AQUI SE MOVIO
	cuotaSindical	= cuotaSindical>0?cuotaSindical/100:0;
	cuotaTotal		= cuotaSindical*(manoTotal+primaTotal); //ANEXAR LA PRIMA DOMINICAL SI EXISTE
	
	pagoTotal		= manoTotal+primaTotal-cuotaTotal;
	
	$('#lblManoObra').html('$'+redondear(manoTotal))
	$('#lblCuotaSindical').html('$'+redondear(cuotaTotal))
	$('#lblPrimaDominical').html('$'+redondear(primaTotal))
	$('#lblPagoTotal').html('$'+redondear(pagoTotal))
	
	$('#txtManoTotal').val(redondear(manoTotal))
	$('#txtCuotaTotal').val(redondear(cuotaTotal))
	$('#txtPrimaTotal').val(redondear(primaTotal))
	
	maestro		= obtenerNumero($('#txtMaestro').val());
	
	if(maestro>pagoTotal) maestro=0;
	
	saldo	= pagoTotal-maestro;
	
	$('#lblSaldo').html('$'+redondear(saldo))
	$('#txtMaestro').val(redondear(maestro))
}

function calcularImportesMaestro()
{
	mano	= obtenerNumero($('#txtManoTotal').val());
}


//REPORTE PANADEROS

$(document).ready(function()
{	
	$("#ventanaReportePanaderos").dialog(
	{
		autoOpen:false,
		height:630,
		width:1100,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			$("#formularioReporte").html('');
		}
	});
});

$(document).on("click", ".ajax-pagPanaderos > li a", function(eve)
{
	eve.preventDefault();
	var element 	= "#obtenerReportePanaderos";
	var link 		= $(this).attr('href');
	
	$.ajax(
	{
		url:link,
		type:"POST",
		data:
		{
			"idLinea":	$('#selectLineasPanaderos').val(),
			"inicio":	$('#txtInicioPanaderos').val(),
			"fin":		$('#txtFinPanaderos').val(),
			"orden":	$('#txtOrdenReporte').val(),
		},
		dataType:"html",
		beforeSend:function()
		{
			$('#obtenerReportePanaderos').html('<img src="'+ img_loader +'"/>Obteniendo reporte..');
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

function ordenReporte(orden)
{
	$('#txtOrdenReporte').val(orden)
	
	obtenerReportePanaderos();
}

function obtenerReportePanaderos()
{
	$('#ventanaReportePanaderos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReportePanaderos').html('<img src="'+ img_loader +'"/> Obteniendo reporte...');
		},
		type:"POST",
		url:base_url+"reportes/obtenerReportePanaderos",
		data:
		{
			"idLinea":	$('#selectLineasPanaderos').val(),
			"inicio":	$('#txtInicioPanaderos').val(),
			"fin":		$('#txtFinPanaderos').val(),
			"orden":	$('#txtOrdenReporte').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReportePanaderos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte',500,5000,'error',30,3)
			$("#obtenerReportePanaderos").html('');	
		}
	});
}

function reportePanaderos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reportePanaderos',
		data:
		{
			"idLinea":	$('#selectLineasPanaderos').val(),
			"inicio":	$('#txtInicioPanaderos').val(),
			"fin":		$('#txtFinPanaderos').val(),
			"orden":	$('#txtOrdenReporte').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ReportePanaderos'
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}


function excelPanaderos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelPanaderos',
		data:
		{
			"idLinea":	$('#selectLineasPanaderos').val(),
			"inicio":	$('#txtInicioPanaderos').val(),
			"fin":		$('#txtFinPanaderos').val(),
			"orden":	$('#txtOrdenReporte').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Panaderos'
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});
}


function calcularPagosKilos()
{
	peso			= obtenerNumero($('#txtTotalPeso').val());
	costoKilo		= obtenerNumero($('#txtCostoKg').val());
	pagoKilo		= obtenerNumero($('#txtPagoKg').val());
	
	total			= peso*costoKilo;
	maestro			= peso*pagoKilo;
	
	
	$('#lblMaestro').html('$'+redondear(maestro))
	$('#lblTotal').html('$'+redondear(total))
	
	
	$('#txtTotalReporte').val(redondear(total))
	$('#txtPagoMaestro').val(redondear(maestro))
}

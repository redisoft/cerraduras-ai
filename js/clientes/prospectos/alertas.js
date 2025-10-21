$(document).ready(function()
{
	$("#ventanaAlertasPasado").dialog(
	{
		autoOpen:false,
		height:600,
		width:1200,
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
			$("#obtenerSeguimientoAlertaPasado").html('');
			//$('#alertasPasado').fadeOut();
		}
	});
});


function obtenerSeguimientoAlertaPasado()
{
	$('#ventanaAlertasPasado').dialog('open');
	
	var audio = document.getElementById("audio");
	audio.pause();
	audio.currentTime = 0;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoAlertaPasado').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerSeguimientoAlertaPasado',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoAlertaPasado').html(data);
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoAlertaPasado').html('');
		}
	});		
}

$(document).ready(function()
{
	//REVISAR LAS ALERTAS POR EL CONSUMO DE RECURSOS
	obtenerSeguimientoAlerta();
	
	setInterval("obtenerSeguimientoAlerta()", 60000);
	
	$("#ventanaAlertas").dialog(
	{
		autoOpen:false,
		height:350,
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
			$("#obtenerSeguimientoAlerta").html('');
		}
	});
});


function obtenerSeguimientoAlerta()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#obtenerSeguimientoAlerta').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerSeguimientoAlerta',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			if(data.length>0)
			{
				$('#obtenerSeguimientoAlerta').html(data);
				$('#ventanaAlertas').dialog('open');
				
				reproducirAlertaSeguimiento();
			}
		},
		error:function(datos)
		{
			//$('#obtenerSeguimientoAlerta').html('');
		}
	});		
}

function reproducirAlerta(numero)
{
	$('#alertasPasado').html(numero)
	$('#alertasPasado').fadeIn()
	
	if(alertaActiva=='1')
	{
		var audio = document.getElementById("audio");
	
		//audio.play();
	}
}

function reproducirAlertaSinAudio(numero)
{
	$('#alertasPasado').html(numero)
	$('#alertasPasado').fadeIn()
}


function reproducirAlertaSeguimiento()
{
	var audio = document.getElementById("audioAlertas");

	//audio.play();
}


function comprobarSeguimientoAlertasPasado()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#obtenerSeguimientoAlerta').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'crm/comprobarSeguimientoAlertasPasado',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=parseInt(data);
			
			if(data>0)
			{
				reproducirAlertaSinAudio(data);
			}
		},
		error:function(datos)
		{
			//$('#obtenerSeguimientoAlerta').html('');
		}
	});		
}

function excelAlertas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#exportandoAlertas').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'crm/excelAlertas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoAlertas').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Alertas';
			notify('El excel se ha creado correctamente',500,4000,"error");
		},
		error:function(datos)
		{
			$("#exportandoAlertas").html('');
		}
	});//Ajax		
}




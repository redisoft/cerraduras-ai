
//PARA ADMINISTRAR EL MARGEN DE LA COTIZACIÓN
$(document).ready(function()
{
	$("#ventanaMargenCotización").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				cotizacionMargen();       
			},
		},
		close: function() 
		{
			$("#formularioMargenCotizacion").html('');
		}
	});
});


function formularioMargenCotizacion(idCotizacion)
{
	$('#ventanaMargenCotización').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioMargenCotizacion').html('<img src="'+ img_loader +'"/>Obteniendo detalles de configuración del margen...');
		},
		type:"POST",
		url:base_url+'clientes/formularioMargenCotizacion',
		data:
		{
		  	"idCotizacion":	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioMargenCotizacion').html(data);
		},
		error:function(datos)
		{
			$("#formularioMargenCotizacion").html('');
		}
	});
}

function cotizacionMargen()
{
	if(Solo_Numerico($('#txtMargen').val())=="")
	{
		notify('El tamaño del margen no es correcto',500,4000,"error");
		return;
	}
	
	window.open(base_url+'pdf/formatoCotizacion/'+$('#txtIdCotizacionMargen').val()+'/1/'+$('#txtMargen').val())
}

$(document).ready(function()
{
	$("#ventanaConvertirVenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				convertirOrdenVenta()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerDetallesCotizacion").html('');
		}
	});
});

function obtenerDetallesCotizacion(idCotizacion)
{
	$('#ventanaConvertirVenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesCotizacion').html('<img src="'+ img_loader +'"/>Obteniendo detalles de cotización');
		},
		type:"POST",
		url:base_url+'cotizaciones/obtenerDetallesCotizacion',
		data:
		{
			"idCotizacion":	idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesCotizacion').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de la cotización',500,5000,'error',2,5);
			$("#obtenerDetallesCotizacion").html('');
		}
	});
}

function convertirOrdenVenta()
{
	var mensaje="";

	if($("#txtOrdenVenta").val()=="")
	{
		mensaje+="El número de orden es incorrecto <br />";										
	} 

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea convertir la cotización en venta?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#conviertiendoVenta').html('<img src="'+ img_loader +'"/> Se esta convirtiendo la cotización en venta, por favor espere...');},
		type:"POST",
		url:base_url+"cotizaciones/convertirOrdenVenta",
		data:
		{
			"orden":			$("#txtOrdenVenta").val(),
			"idCotizacion":		$("#txtIdCotizacion").val(),
			"prefactura":		document.getElementById('chkPrefactura').checked?'1':'0',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#conviertiendoVenta").html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				case "1":
					window.location.href=base_url+'clientes/ventas/'+$('#txtIdClienteCotizacion').val()
				break;
			}//switch
		},
		error:function(datos)
		{
			$("#conviertiendoVenta").html('');
			notify('Error al convertir la cotización a venta',500,4000,"error");
		}
	});
}

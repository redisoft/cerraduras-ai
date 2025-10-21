tipo ="";

$(document).ready(function()
{
	$("#ventanaPagosCfdi").dialog(
	{
		autoOpen:false,
		height:550,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				registrarPagoCfdi()		 
			},
		},
		close: function() 
		{
			$("#formularioPagosCfdi").html('');
		}
	});
});


function formularioPagosCfdi(idFactura)
{
	$('#ventanaPagosCfdi').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPagosCfdi').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de factura...</label>');
		},
		type:"POST",
		url:base_url+'facturacion/formularioPagos',
		data:
		{
			idFactura:idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPagosCfdi').html(data);
		},
		error:function(datos)
		{
			$('#formularioPagosCfdi').html('');
			notify("Error al obtener los datos para registrar la factura",500,4000,"error"); 
		}
	});	
}

function registrarPagoCfdi()
{
	pago	= obtenerNumeros($('#txtImportePagar').val());
	saldo	= obtenerNumeros($('#txtSaldoFactura').val());
	
	if(pago>saldo || pago==0)
	{
		notify("El pago es incorrecto",500,4000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el pago?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPagoCfdi').html('<label><img src="'+base_url+'img/ajax-loader.gif"/> Se esta registrando el pago...</label>');
		},
		type:"POST",
		url:base_url+'facturacion/registrarPago',
		data:
		$('#frmRegistrarPago').serialize()+'&formaPago='+$('#selectFormaPago option:selected').text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPagoCfdi').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "1":
					notify(data[1],500,7000,'',30,5);
					obtenerFacturas();
					$("#ventanaPagosCfdi").dialog('close');
				break;
				
				case "0":
					notify(data[1],500,7000,'error',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoPagoCfdi').html('');
			notify("Error al registrar el pago",500,5000,"error",30,5); 
		}
	});	
}

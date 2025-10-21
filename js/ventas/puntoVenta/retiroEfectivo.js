function formularioRetiroEfectivo()
{
	$('#ventanaRetiroEfectivo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRetiroEfectivo').html('<img src="'+ img_loader +'"/>Obteniendo detalles de retiro, por favor espere...');
		},
		type:"POST",
		url:base_url+'ventas/formularioRetiroEfectivo',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioRetiroEfectivo").html(data);
			$("#txtRetiroEfectivo").focus();
		},
		error:function(datos)
		{
			$("#formularioRetiroEfectivo").html('');	
			notify('Error al obtener el retiro',500,4000,"");
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaRetiroEfectivo").dialog(
	{
		//closeOnEscape: false,
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:550,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				imprimirRetiroEfectivo();
			},
			'Aceptar': function() 
			{
				registrarRetiroEfectivo();
			},
		},
		close: function() 
		{
			$('#formularioRetiroEfectivo').html('');
		}
	});
});

function registrarRetiroEfectivo()
{
	mensaje="";
	
	if(obtenerNumeros($('#txtRetiroEfectivo').val())==0)
	{
		mensaje+="El importe del retiro es incorrecto <br />";
	}
	
	if( obtenerNumeros($('#txtEfectivoDisponible').val()) < obtenerNumeros($('#txtRetiroEfectivo').val()) )
	{
		mensaje+="El importe del retiro supera el efectivo disponible <br />";
	}
	
	if(!camposVacios($('#txtMotivoRetiro').val()))
	{
		mensaje+="Escriba los motivos del retiro <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',32,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el retiro?')) return;
	
	$.ajax(
	{
		beforeSend:function()
		{
			$('#registrandoRetiroEfectivo').html('<img src="'+ img_loader +'"/> Se esta registrando el retiro, por favor espere...');
		},
		async   : false,
		type    : "POST",
		url     : base_url+"ventas/registrarRetiroEfectivo",
		data	: $('#frmRetiroEfectivo').serialize(),
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#registrandoRetiroEfectivo').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',32,5);
				break;
						
				case "1":
					$('#txtRetiroEfectivo,#txtMotivoRetiro').val('')
					notify(data[1],500,5000,'',32,5);
					$('#txtIdEgreso').val(data[2]);
				break;
			}
		},
		error: function(datos)
		{
			notify('Error al realizar el cobro',500,5000,'error',32,5);
			$('#registrandoRetiroEfectivo').html('');
		}
	});
}

function imprimirRetiroEfectivo()
{
	if(obtenerNumeros($('#txtIdEgreso').val())==0)
	{
		notify('Registre el retiro',500,5000,'error',32,5);
		return;
	}
	
	window.open(base_url+'ventas/ticketRetiro/'+$('#txtIdEgreso').val());
	
}

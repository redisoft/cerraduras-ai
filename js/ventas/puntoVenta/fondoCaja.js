function formularioFondoCaja()
{
	$('#ventanaFondoCaja').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioFondoCaja').html('<img src="'+ img_loader +'"/>Obteniendo detalles de fondo de caja, por favor espere...');
		},
		type:"POST",
		url:base_url+'ventas/formularioFondoCaja',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFondoCaja").html(data);
			$("#txtFondoCaja").focus();
		},
		error:function(datos)
		{
			$("#formularioFondoCaja").html('');	
			notify('Error al obtener el fondo de caja',500,4000,"");
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaFondoCaja").dialog(
	{
		//closeOnEscape: false,
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:550,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				imprimirFondoCaja();
			},
			'Aceptar': function() 
			{
				registrarFondoCaja();
			},
		},
		close: function() 
		{
			$('#formularioFondoCaja').html('');
		}
	});
});

function registrarFondoCaja()
{
	mensaje="";
	
	if(obtenerNumeros($('#txtFondoCaja').val())==0)
	{
		mensaje+="El importe del fondo de caja es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',32,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el fondo de caja?')) return;
	
	$.ajax(
	{
		beforeSend:function()
		{
			$('#registrandoFondoCaja').html('<img src="'+ img_loader +'"/> Se esta registrando el fondo de caja, por favor espere...');
		},
		async   : false,
		type    : "POST",
		url     : base_url+"ventas/registrarFondoCaja",
		data	: $('#frmFondoCaja').serialize(),
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#registrandoFondoCaja').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',32,5);
				break;
						
				case "1":
					$('#txtFondoCaja').val('')
					notify(data[1],500,5000,'',32,5);
					$('#txtIdIngreso').val(data[2]);
				break;
			}
		},
		error: function(datos)
		{
			notify('Error al realizar el cobro',500,5000,'error',32,5);
			$('#registrandoFondoCaja').html('');
		}
	});
}

function imprimirFondoCaja()
{
	if(obtenerNumeros($('#txtIdIngreso').val())==0)
	{
		notify('Registre el fondo de caja',500,5000,'error',32,5);
		return;
	}
	
	window.open(base_url+'ventas/ticketFondo/'+$('#txtIdIngreso').val());
}

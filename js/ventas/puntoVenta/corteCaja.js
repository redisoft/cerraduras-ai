function obtenerCorteCaja()
{
	$('#ventanaCorteCaja').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCorteCaja').html('<img src="'+ img_loader +'"/>Obteniendo detalles de corte de caja, por favor espere...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerCorteCaja',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCorteCaja").html(data);
		},
		error:function(datos)
		{
			$("#obtenerCorteCaja").html('');	
			notify('Error al obtener el corte de caja',500,4000,"");
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaCorteCaja").dialog(
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
				imprimirCorteCaja();
			},
		},
		close: function() 
		{
			$('#formularioFondoCaja').html('');
		}
	});
});

function imprimirCorteCaja()
{
	window.open(base_url+'ventas/ticketCorte');
}

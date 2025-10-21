$(document).ready(function()
{
	$("#ventanaEstadoCuenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
			Imprimir: function() 
			{
				window.location.href=base_url+'clientes/reporteEstadoCuenta/'+$('#txtIdClienteEstado').val()			 
			},
		},
		close: function() 
		{
			$("#obtenerEstadoCuenta").html('');
		}
	});
});

function obtenerEstadoCuenta(idCliente)
{	
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	$('#ventanaEstadoCuenta').dialog('open');
	
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerEstadoCuenta').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para clientes...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerEstadoCuenta',
		data:
		{
			idCliente:idCliente
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEstadoCuenta').html(data);
		},
		error:function(datos)
		{
			$('#obtenerEstadoCuenta').html('');
		}
	});		
}

function sugerirCantidadesAcademico(nuevo)
{
	Programa	= new String($('#selectProgramas').val())
	programa	= Programa.split('|');
	
	if(nuevo==0)
	{
		if(!confirm('¿Desea establecer la periodicidad del programa seleccionado?')) return
	}

	if(Programa=="0") 
	{
		$('#txtCantidadInscripcion').val('')
		$('#txtCantidadColegiatura').val('')
		$('#txtCantidadReinscripcion').val('')
		
		calcularTotalesAcademicos()
		
		return;
	}
	
	$('#txtCantidadInscripcion').val(programa[1])
	$('#txtCantidadColegiatura').val(programa[2])
	$('#txtCantidadReinscripcion').val(programa[3])
	
	calcularTotalesAcademicos()
}
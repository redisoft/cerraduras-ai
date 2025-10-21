d=0;

$(document).ready(function()
{
	$("#ventanaArqueo").dialog(
	{
		//closeOnEscape: false,
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Imprimir': function() 
			{
				imprimirArqueo();
			},
			'Aceptar': function() 
			{
				registrarDenominacion();
			},
		},
		close: function() 
		{
			$('#obtenerArqueo').html('');
		}
	});
});

function obtenerArqueo()
{
	$("#ventanaArqueo").dialog('open');
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#obtenerArqueo').html('<img src="'+base_url+'img/loader.gif" width="30"/> Obteniendo detalles de arqueo');},
		type:"POST",
		url:base_url+'ventas/obtenerArqueo',
		datatype:"html",
		data:
		{
			idArqueo: $('#txtIdArqueo').val()
		},
		success:function(data, textStatus)
		{
			$('#obtenerArqueo').html(data);
		},
		error:function(datos)
		{
			$("#obtenerArqueo").html('');
			notify('Error al obtener el arqueo',500,5000,'error',30,3);
		}
	});		
}

function obtenerArqueoDetalles()
{
	$.ajax(
	{
		async:false,
		type:"POST",
		url:base_url+'ventas/obtenerArqueoDetalles',
		datatype:"html",
		data:
		{
			idArqueo: $('#txtIdArqueo').val()
		},
		success:function(data, textStatus)
		{
			$('#obtenerArqueoDetalles').html(data);
		},
		error:function(datos)
		{
			$("#obtenerArqueoDetalles").html('');
			notify('Error al obtener el arqueo',500,5000,'error',30,3);
		}
	});		
}

function registrarDenominacion(i)
{
	if(!comprobarNumeros($('#txtCantidadArqueo'+i).val()))
	{
		notify('La cantidad es incorrecta',500,5000,'error',30,5);
		
		$('#txtCantidadArqueo'+i).val($('#txtCantidadDenominacion'+i).val())
		return;
	}
	
	/*if(!confirm('¿Realmente desea registrar la cantidad?'))
	{
		$('#txtCantidadArqueo'+i).val($('#txtCantidadDenominacion'+i).val())
		
		return;
	}*/
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoArqueo').html('<label style="color:#FFF"><img src="'+base_url+'img/loader.gif" width="30"/> Registrando cantidad...</label>');},
		type:"POST",
		url:base_url+'ventas/registrarDenominacion',
		data:
		{
			idRelacion:		$('#txtIdRelacion'+i).val(),
			cantidad: 		$('#txtCantidadArqueo'+i).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoArqueo").html('');
			
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro ha sido exitoso',500,1000,'error',30,5);
					obtenerArqueo();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoArqueo").html('');
			notify('Error en el registro',500,5000,'error',30,3);
		}
	});		
}

function imprimirArqueo()
{
	window.open(base_url+'ventas/ticketArqueo/'+$('#txtIdArqueo').val());
}

function registrarArqueo()
{
	//if(!confirm('¿Realmente desea registrar el arqueo?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#registrandoVenta').html('<label style="color:#FFF"><img src="'+base_url+'img/loader.gif" width="30"/> Registrando arqueo...</label>');},
		type:"POST",
		url:base_url+'puntoVenta/registrarArqueo',
		data:
		{
			idArqueo:			$('#txtIdArqueo').val(),
			fondoInicial: 		$('#txtFondoInicialArqueo').val(),
			efectivo: 			$('#txtEfectivoArqueo').val(),
			retiros: 			$('#txtRetirosArqueo').val(),
			totalEfectivo: 		$('#txtTotalEfectivoArqueo').val(),
			efectivoReportado: 	$('#txtEfectivoReportadoArqueo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoVenta").html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					window.open(base_url+'puntoVenta/imprimirTicketArqueo/'+$('#txtIdArqueo').val());
					//notify('El registro ha sido exitoso',500,5000,'error',30,5);
					formularioVentas();
					d=0;
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoVenta").html('');
			notify('Error en el registro',500,5000,'error',30,3);
		}
	});		
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//REQUISICIONES COMPRAS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$(document).ready(function()
{
	$('#txtInicioRequisicion,#txtFinRequisicion,#txtInicioRequisicionProcesada,#txtFinRequisicionProcesada').datepicker({changeMonth: true, changeYear: true});
	$('#txtFechaOrden').datetimepicker({changeMonth: true, changeYear: true});
	
	$("#txtBusquedaRequisicion").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerRequisiciones();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagAbiertas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRequisiciones";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":	$('#txtInicioRequisicion').val(),
				"fin":		$('#txtFinRequisicion').val(),
				"criterio":	$('#txtBusquedaRequisicion').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerRequisiciones').html('<img src="'+ img_loader +'"/>Obteniendo requisiciones..');
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

function obtenerRequisiciones()
{
	$('#ventanaRequisiciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRequisiciones').html('<img src="'+ img_loader +'"/> Obteniendo detalles de requisiciones...');},
		type:"POST",
		url:base_url+"requisiciones/obtenerRequisicionesCompras",
		data:
		{
			"inicio":	$('#txtInicioRequisicion').val(),
			"fin":		$('#txtFinRequisicion').val(),
			"criterio":	$('#txtBusquedaRequisicion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerRequisiciones").html(data);
			$('#txtBusquedaRequisicion').focus()
		},
		error:function(datos)
		{
			$("#obtenerRequisiciones").html('');	
		}
	});	
}
	

$(document).ready(function()
{
	$("#ventanaRequisiciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1150,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarComprasRequisiones()
			},
		},
		close: function() 
		{
			$("#obtenerRequisiciones").html('');
		}
	});
});

function comprobarRequisiciones()
{
	re	= obtenerNumero($('#txtNumeroRequisiciones').val())
	
	b=false;
	
	for(i=0;i<re;i++)
	{
		if(document.getElementById('chkAutorizar'+i).checked)
		{
			b=true;
			
			if(obtenerNumero($('#txtCostoProducto'+i).val())==0 || obtenerNumero($('#txtCantidadProducto'+i).val())==0)
			{
				b=false;
				break;
			}
		}
	}
	
	return b;
}

function registrarComprasRequisiones()
{
	if(!comprobarRequisiciones())
	{
		//notify('Configure correctamente las requisiciones para las compras',500,6000,"error",30,5);
		alert('Favor de colocar precio y cantidad a los productos');
		return;
	}
	
	if(!confirm('¿Realmente desea registrar las compras?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoRequisiciones').html('<img src="'+ img_loader +'"/> Registrando las compras');},
		type:"POST",
		url:base_url+"requisiciones/registrarComprasRequisiones",
		data:$('#frmComprasRequisicion').serialize()+'&fechaCompra='+$('#txtFechaOrden').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoRequisiciones').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('Registro exitoso',500,6000,"",30,5);
					//setTimeout(function(){location.reload(true);},2000);
					obtenerComprasMateriales();
					$("#ventanaRequisiciones").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar las compras',500,4000,"error",30,5);
			$("#procesandoRequisiciones").html('');	
		}
	});				
}

function sugerirProveedorRequisicion(i)
{
	Proveedor	= $('#selectProveedores'+i).val();
	proveedor	= Proveedor.split('-');	
	
	$('#txtIdProveedor'+i).val(proveedor[0])
	$('#txtCostoProducto'+i).val(redondear(proveedor[1]))
}

//REQUISICIONES PROCESADAS
function obtenerRequisicionesProcesadas()
{
	$('#ventanaRequisicionesProcesadas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRequisicionesProcesadas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de requisiciones...');},
		type:"POST",
		url:base_url+"requisiciones/obtenerRequisicionesProcesadas",
		data:
		{
			"inicio":	$('#txtInicioRequisicionProcesada').val(),
			"fin":		$('#txtFinRequisicionProcesada').val(),
			"criterio":	$('#txtBusquedaRequisicionProcesada').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerRequisicionesProcesadas").html(data);
		},
		error:function(datos)
		{
			$("#obtenerRequisicionesProcesadas").html('');	
		}
	});	
}

$(document).ready(function()
{
	$("#txtBusquedaRequisicionProcesada").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerRequisicionesProcesadas();
		}, 700);
	});
	
	$("#ventanaRequisicionesProcesadas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1150,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaRequisicionesProcesadas').dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerRequisicionesProcesadas").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagProcesadas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRequisicionesProcesadas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":	$('#txtInicioRequisicionProcesada').val(),
				"fin":		$('#txtFinRequisicionProcesada').val(),
				"criterio":	$('#txtBusquedaRequisicionProcesada').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerRequisicionesProcesadas').html('<img src="'+ img_loader +'"/>Obteniendo requisiciones..');
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


$(document).ready(function()
{
	$('#txtBuscarClienteVenta').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerClientesBusqueda();
		}
	});

	$("#ventanaBuscarClientes").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:1000,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		  	  
			},
		},
		close: function() 
		{
			//$("#obtenerClientesBusqueda").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagClientesBusqueda > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerClientesBusqueda";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio: $('#txtBuscarClienteVenta').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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

function obtenerClientesBusqueda()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerClientesBusqueda').html('<img src="'+ img_loader +'"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'clientes/obtenerClientesBusqueda',
		data:
		{
			criterio: $('#txtBuscarClienteVenta').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerClientesBusqueda').html(data);
		},
		error:function(datos)
		{
			$('#obtenerClientesBusqueda').html('');
			notify('Error al obtener los registros',500,5000,'error',30,5);
		}
	});		
}

function agregarClienteVenta(idCliente)
{
	$('#txtIdCliente').val(idCliente)
	$('#txtBuscarCliente').val($('#txtEmpresaCliente'+idCliente).val())
	$('#txtCreditoDias').val($('#txtCreditoCliente'+idCliente).val())
	$('#txtDiasCredito').val($('#txtCreditoCliente'+idCliente).val())
	$('#txtIdSucursal').val($('#txtIdSucursal'+idCliente).val())
	
	$('#lblClienteVenta').html($('#txtEmpresaCliente'+idCliente).val())
	$('#lblDireccionVenta').html($('#txtDireccionCliente'+idCliente).val())
	
	obtenerDireccionesCliente(idCliente);
	
	$("#ventanaBuscarClientes").dialog('close');
}

function obtenerDireccionesCliente(idCliente)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDireccionesCliente').html('<img src="'+ img_loader +'"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDireccionesCliente',
		data:
		{
			idCliente:idCliente,
			tipo:3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDireccionesCliente').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDireccionesCliente').html('');
			notify('Error en la  busqueda',500,5000,'error',30,5);
		}
	});		
}

function editarClienteVenta(idCliente)
{
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'clientes/editarClienteVenta',
		data:
		{
			email:		$('#txtEmailCliente'+idCliente).val(),
			telefono:	$('#txtTelefonoCliente'+idCliente).val(),
			idCliente:	idCliente
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					break;

			}
			
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'',30,5);
		}
	});		
}

//REGISTRAR CLIENTE
$(document).ready(function()
{
	$("#ventanaRegistrarCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarClienteVenta()	  	  
			},
		},
		close: function() 
		{
			$("#formularioClientesVentas").html('');
		}
	});
});

function formularioClientesVentas()
{
	$("#ventanaRegistrarCliente").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioClientesVentas').html('<img src="'+ img_loader +'"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'clientes/formularioClientesVentas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioClientesVentas').html(data);
		},
		error:function(datos)
		{
			$('#formularioClientesVentas').html('');
			notify('Error en el formulario',500,5000,'error',30,5);
		}
	});		
}

function registrarClienteVenta()
{
	if(!camposVacios($('#txtEmpresa').val()))
	{
		notify('La empresa es requerida',500,5000,'error',30,5);
		return;
	}
	
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoCliente').html('<img src="'+ img_loader +'"/> Registrando el cliente');
		},
		type:"POST",
		url:base_url+'clientes/registrarClienteVenta',
		data: $('#frmClientes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			$('#registrandoCliente').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					
					$('#txtIdCliente').val(data[2])
					$('#txtBuscarCliente').val(data[3])
					$('#txtCreditoDias').val(0)
					
					$("#ventanaRegistrarCliente").dialog('close');
					break;

			}
			
		},
		error:function(datos)
		{
			$('#registrandoCliente').html('');
			notify('Error en el registro',500,5000,'',30,5);
		}
	});		
}

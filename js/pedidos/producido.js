
//PRODUCIDO PEDIDO
$(document).ready(function()
{
	$("#ventanaProducidoPedido").dialog(
	{
		autoOpen:false,
		height:500,
		width:850,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarProducido()				  	  
			},
		},
		close: function() 
		{
			$("#obtenerProducidoPedido").html('');
		}
	});
});

function obtenerProducidoPedido(idPedido)
{
	$('#ventanaProducidoPedido').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProducidoPedido').html('<img src="'+ img_loader +'"/> Obteniendo producido...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerProducidoPedido",
		data:
		{
			"idPedido":idPedido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProducidoPedido').html(data);
			re	= obtenerNumero($('#txtNumeroProductos').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener lo producido',500,5000,'error',30,3)
			$("#obtenerProducidoPedido").html('');	
		}
	});
}

function comprobarProducir()
{
	for(i=0;i<re;i++)
	{
		if($('#lineaPedido').val()=="4")
		{
			if(obtenerNumero($('#txtCantidadProducir'+i).val())>0 && obtenerNumero($('#txtPesoProducir'+i).val())>0)
			{
				return true;
			}
		}
		else
		{
			if(obtenerNumero($('#txtCantidadProducir'+i).val())>0)
			{
				return true;
			}
		}
	}
	
	return false;
}

function registrarProducido()
{
	if(!comprobarProducir())
	{
		notify('Configure al menos una cantidad',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProducido').html('<img src="'+ img_loader +'"/> Registrando producido, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/registrarProducido",
		data:$('#frmPedidos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoProducido').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					obtenerProducidoPedido($('#txtIdPedido').val())
					obtenerPedidos()
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoProducido').html('');
			notify('Error en el registro',500,5000,'error',30,3);	
		}
	});
}

//ADMINISTRAR PRODUCIDO

$(document).ready(function()
{
	$("#ventanaProducidosProducto").dialog(
	{
		autoOpen:false,
		height:450,
		width:800,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');				  	  
			},
		},
		close: function() 
		{
			$("#obtenerProducidosProducto").html('');
		}
	});
});

function obtenerProducidosProducto(idDetalle)
{
	$('#ventanaProducidosProducto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProducidosProducto').html('<img src="'+ img_loader +'"/> Obteniendo producido...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerProducidosProducto",
		data:
		{
			"idDetalle":idDetalle,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProducidosProducto').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener lo producido',500,5000,'error',30,3)
			$("#obtenerProducidosProducto").html('');	
		}
	});
}

function editarProducidoProducto(idProducido)
{
	if(obtenerNumero($('#txtCantidadProducido'+idProducido).val())==0)
	{
		notify('La cantidad es incorrecta',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoProducido').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/editarProducidoProducto",
		data:
		{
			"idProducido":		idProducido,
			"cantidad":			$('#txtCantidadProducido'+idProducido).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoProducido').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify('El registro no ha sido editado',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerProducidoPedido($('#txtIdPedido').val())
					obtenerProducidosProducto($('#txtIdDetalle').val())
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoProducido').html('');
			notify('Error al borrar los pedidos',500,5000,'error',30,3);	
		}
	});
}

function borrarProducidoProducto(idProducido)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoProducido').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/borrarProducidoProducto",
		data:
		{
			"idProducido":		idProducido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoProducido').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerProducidoPedido($('#txtIdPedido').val())
					obtenerProducidosProducto($('#txtIdDetalle').val())
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoProducido').html('');
			notify('Error al borrar los pedidos',500,5000,'error',30,3);	
		}
	});
}

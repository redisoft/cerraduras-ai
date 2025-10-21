//=====================================================================================================//
//===================================PEDIDOS========================================//
//=====================================================================================================//

function obtenerPedidos()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPedidos').html('<img src="'+ img_loader +'"/> Obteniendo pedidos, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerPedidos",
		data:
		{
			"criterio":	$('#txtBuscarPedido').val(),
			"inicio":	$('#txtInicioPedido').val(),
			"fin":		$('#txtFinPedido').val(),
			"orden":	$('#txtOrdenPedidos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPedidos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los pedidos',500,5000,'error',30,5)
			$("#obtenerPedidos").html('');	
		}
	});
}

function formularioPedidos()
{
	$('#ventanaPedidos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPedido').html('<img src="'+ img_loader +'"/> Obteniendo formulario de pedidos...');
		},
		type:"POST",
		url:base_url+"pedidos/formularioPedidos",
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPedidos').html(data);
			$('#txtComentarios').focus();
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de pedidos',500,5000,'error',30,3)
			$("#formularioPedidos").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#txtInicioPedido,#txtFinPedido").datepicker({});
	
	obtenerPedidos()
	
	$("#ventanaPedidos").dialog(
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
				registrarPedido()				  	  
			},
		},
		close: function() 
		{
			$("#formularioPedidos").html('');
		}
	});
	
	$("#txtBuscarPedido").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerPedidos();
		}, 700);
	});
	
	//$('.ajax-pagMateriales > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagPedidos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPedidos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarPedido').val(),
				"inicio":	$('#txtInicioPedido').val(),
				"fin":		$('#txtFinPedido').val(),
				"orden":	$('#txtOrdenPedidos').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerPedidos').html('<img src="'+ img_loader +'"/>Obteniendo pedidos..');
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

function ordenPedidos(orden)
{
	$('#txtOrdenPedidos').val(orden)
	
	obtenerPedidos();
}

function quitarProductoPedido(i)
{
	$('#filaPedido'+i).remove()
}

function comprobarProductoPedido(idMaterial)
{
	for(i=0;i<re;i++)
	{
		if(obtenerNumero(idMaterial)==obtenerNumero($('#txtIdProducto'+i).val())) return false;
	}
	
	return true;
}

re=0;
function cargarProductoPedido(producto)
{
	if(!comprobarProductoPedido(producto.idProducto))
	{
		setTimeout(function(){$('#txtBuscarProducto').val(''); $('#txtBuscarProductoFrances').val('');$('#txtBuscarProductoLinea').val('')},300);
		notify('Ya se ha cargado el producto',500,6000,"error",30,5);
		
		return;
	}
	
	data='<tr id="filaPedido'+re+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarProductoPedido('+re+')"/></td>';
	data+='<td>'+producto.codigoInterno+'</td>';
	data+='<td>'+producto.nombre+'</td>';
	data+='<td align="center"> <input type="text"  	name="txtCantidadPedido'+re+'" id="txtCantidadPedido'+re+'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>';
	
	if($('#selectLineas').val()=="4")
	{
		data+='<td align="center"> <input type="text"  	name="txtPesoPedido'+re+'" id="txtPesoPedido'+re+'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>';
	}
	
	data+='<input type="hidden"  name="txtIdProducto'+re+'" id="txtIdProducto'+re+'" value="'+producto.idProducto+'" />';
	data+='</tr>';
	
	//$('#tablaPedidos').append(data);
	
	$("#tablaPedidos tbody").prepend(data);
	
	re++;
	
	/*$("#tablaPedidos tr:even").addClass("sombreado");
	$("#tablaPedidos tr:odd").addClass("sinSombra"); */
	$('#txtNumeroProductos').val(re); 
	
	setTimeout(function(){$('#txtBuscarProducto').val(''); $('#txtBuscarProductoFrances').val('');$('#txtBuscarProductoLinea').val('')},300);
}

function comprobarPedidos()
{
	b=false;
	
	for(i=0;i<re;i++)
	{
		if(obtenerNumero($('#txtIdProducto'+i).val())>0)
		{
			b=true;
			
			if(obtenerNumero($('#txtCantidadPedido'+i).val())==0)
			{
				b=false;
			}
			
			if($('#selectLineas').val()=="4")
			{
				if(obtenerNumero($('#txtPesoPedido'+i).val())==0)
				{
					b=false;
				}
			}
		}
	}
	
	return b;
}

function registrarPedido()
{
	if(!comprobarPedidos())
	{
		notify('Configure correctamente los productos para las pedidos',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPedido').html('<img src="'+ img_loader +'"/> Registrando pedido, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/registrarPedido",
		data:$('#frmPedidos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPedido').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaPedidos').dialog('close');
					obtenerPedidos()
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoPedido').html('');
			notify('Error al registrar el pedido',500,5000,'error',30,3);	
		}
	});
}

function borrarPedido(idPedido)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPedidos').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/borrarPedido",
		data:
		{
			"idPedido":		idPedido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPedidos').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerPedidos();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoPedidos').html('');
			notify('Error al borrar los pedidos',500,5000,'error',30,3);	
		}
	});
}

function cancelarPedido(idPedido)
{
	if(!confirm('¿Realmente desea cancelar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPedidos').html('<img src="'+ img_loader +'"/> Cancelando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/cancelarPedido",
		data:
		{
			"idPedido":		idPedido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPedidos').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha cancelado correctamente',500,5000,'',30,5);
					obtenerPedidos();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoPedidos').html('');
			notify('Error al cancelar los pedidos',500,5000,'error',30,3);	
		}
	});
}

//EDITAR PEDIDO
$(document).ready(function()
{
	$("#ventanaEditarPedido").dialog(
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
				editarPedido()				  	  
			},
		},
		close: function() 
		{
			$("#obtenerPedido").html('');
		}
	});
});
function obtenerPedido(idPedido)
{
	$('#ventanaEditarPedido').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPedido').html('<img src="'+ img_loader +'"/> Obteniendo formulario de pedidos...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerPedido",
		data:
		{
			"idPedido":idPedido,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPedido').html(data);
			$('#txtComentarios').focus();
			re	= obtenerNumero($('#txtNumeroProductos').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de pedidos',500,5000,'error',30,3)
			$("#obtenerPedido").html('');	
		}
	});
}

function editarPedido()
{
	if(!comprobarPedidos())
	{
		notify('Configure correctamente los productos para las pedidos',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPedido').html('<img src="'+ img_loader +'"/> Editando pedido, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/editarPedido",
		data:$('#frmPedidos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoPedido').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaEditarPedido').dialog('close');
					obtenerPedidos()
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoPedido').html('');
			notify('Error al editar el pedido',500,5000,'error',30,3);	
		}
	});
}

function tipoPedido()
{
	idLinea=obtenerNumero($('#selectLineas').val());
	
	if(idLinea==3)
	{
		$('#spnPedido').html($('#txtBizcocho').val());
		$('#txtBuscarProducto').fadeIn(1);
		$('#txtBuscarProductoFrances').fadeOut(1);
		$('#txtBuscarProductoLinea').fadeOut(1);
	}
	
	if(idLinea==2)
	{
		$('#spnPedido').html($('#txtFrances').val());
		$('#txtBuscarProducto').fadeOut(1);
		$('#txtBuscarProductoFrances').fadeIn(1);
		$('#txtBuscarProductoLinea').fadeOut(1);
	}
	
	if(idLinea!=2 && idLinea!=3)
	{
		$('#spnPedido').html($('#txtLinea').val());
		$('#txtBuscarProducto').fadeOut(1);
		$('#txtBuscarProductoFrances').fadeOut(1);
		//$('#txtBuscarProductoLinea').fadeIn(1);
		
		
		$.ajax(
		{
			async:false,
			beforeSend:function(objeto)
			{
				//$('#procesandoPedidos').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
			},
			type:"POST",
			url:base_url+"pedidos/obtenerBuscadorLinea",
			data:
			{
				"idLinea":		idLinea,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#spnProductoLinea').html(data);
			},
			error:function(datos)
			{
				//$('#procesandoPedidos').html('');
				notify('Error',500,5000,'error',30,3);	
			}
		});
		
	}
	
	reiniciarPedido()
}

function reiniciarPedido()
{
	if($('#selectLineas').val()!="4")
	{
		$('#tablaPedidos').html('<thead><tr><th width="3%">-</th><th width="20%">Código</th><th width="60%">Producto</th><th>Cantidad</th></tr> </thead> <tbody></tbody>');
	}
	else
	{
		$('#tablaPedidos').html('<thead><tr><th width="3%">-</th><th width="20%">Código</th><th width="50%">Producto</th><th>Cantidad</th><th>Peso en kg</th></tr> </thead> <tbody></tbody>');
	}
	
}
//=====================================================================================================//
//===================================PEDIDOS========================================//
//=====================================================================================================//

function obtenerConteos()
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
			$('#obtenerConteos').html('<img src="'+ img_loader +'"/> Obteniendo conteo...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerConteos",
		data:
		{
			"criterio":	$('#txtBuscarConteo').val(),
			"inicio":	$('#txtInicioConteo').val(),
			"fin":		$('#txtFinConteo').val(),
			"orden":	$('#txtOrdenConteos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConteos').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener los conteos',500,5000,'error',30,5)
			$("#obtenerConteos").html('');	
		}
	});
}

function formularioConteos()
{
	$('#ventanaConteos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioConteo').html('<img src="'+ img_loader +'"/> Obteniendo formulario de conteos...');
		},
		type:"POST",
		url:base_url+"pedidos/formularioConteos",
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioConteos').html(data);
			$('#txtComentarios').focus();
			re=obtenerNumero($('#txtNumeroProductos').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de conteos',500,5000,'error',30,3)
			$("#formularioConteos").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#txtInicioConteo,#txtFinConteo").datepicker({});
	
	obtenerConteos()
	
	$("#ventanaConteos").dialog(
	{
		autoOpen:false,
		height:600,
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
				registrarConteo()				  	  
			},
		},
		close: function() 
		{
			$("#formularioConteos").html('');
		}
	});
	
	$("#txtBuscarConteo").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerConteos();
		}, 700);
	});
	
	//$('.ajax-pagMateriales > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagConteos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerConteos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarConteo').val(),
				"inicio":	$('#txtInicioConteo').val(),
				"fin":		$('#txtFinConteo').val(),
				"orden":	$('#txtOrdenConteos').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerConteos').html('<img src="'+ img_loader +'"/>Obteniendo conteos..');
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

function ordenConteos(orden)
{
	$('#txtOrdenConteos').val(orden)
	
	obtenerConteos();
}

function quitarProductoConteo(i)
{
	$('#filaConteo'+i).remove()
}

function comprobarProductoConteo(idMaterial)
{
	for(i=0;i<re;i++)
	{
		if(obtenerNumero(idMaterial)==obtenerNumero($('#txtIdProducto'+i).val())) return false;
	}
	
	return true;
}

re=0;
function cargarProductoConteo(producto)
{
	if(!comprobarProductoConteo(producto.idProducto))
	{
		setTimeout(function(){$('#txtBuscarProducto').val('')},300);
		notify('Ya se ha cargado el producto',500,6000,"error",30,5);
		
		return;
	}
	
	data='<tr id="filaConteo'+re+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarProductoConteo('+re+')"/></td>';
	data+='<td>'+producto.codigoInterno+'</td>';
	data+='<td>'+producto.nombre+'</td>';
	data+='<td align="center"> <input type="text"  	name="txtCantidadConteo'+re+'" id="txtCantidadConteo'+re+'" class="cajas" maxlength="8" style="width:80px;" onkeypress="return soloDecimales(event)"/></td>';

	data+='<input type="hidden"  name="txtIdProducto'+re+'" id="txtIdProducto'+re+'" value="'+producto.idProducto+'" />';
	data+='</tr>';
	
	//$('#tablaConteos').append(data);
	
	$("#tablaConteos tbody").prepend(data);
	
	re++;
	
	/*$("#tablaConteos tr:even").addClass("sombreado");
	$("#tablaConteos tr:odd").addClass("sinSombra"); */
	$('#txtNumeroProductos').val(re); 
	
	setTimeout(function(){$('#txtBuscarProducto').val(''); $('#txtBuscarProductoFrances').val('');$('#txtBuscarProductoLinea').val('')},300);
}

function comprobarConteos()
{
	b=false;
	
	for(i=0;i<re;i++)
	{
		if(obtenerNumero($('#txtIdProducto'+i).val())>0)
		{
			if(obtenerNumero($('#txtCantidadConteo'+i).val())>0)
			{
				b=true;
			}
		}
	}
	
	return b;
}

function registrarConteo()
{
	if(!comprobarConteos())
	{
		notify('Configure correctamente los productos para las conteos',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoConteo').html('<img src="'+ img_loader +'"/> Registrando conteo, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/registrarConteo",
		data:$('#frmConteos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoConteo').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaConteos').dialog('close');
					obtenerConteos()
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoConteo').html('');
			notify('Error al registrar el conteo',500,5000,'error',30,3);	
		}
	});
}

function borrarConteo(idConteo)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoConteo').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/borrarConteo",
		data:
		{
			"idConteo":		idConteo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoConteo').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerConteos();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoConteo').html('');
			notify('Error al borrar los conteos',500,5000,'error',30,3);	
		}
	});
}

//EDITAR PEDIDO
$(document).ready(function()
{
	$("#ventanaEditarConteo").dialog(
	{
		autoOpen:false,
		height:600,
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
				editarConteo()				  	  
			},
		},
		close: function() 
		{
			$("#obtenerConteo").html('');
		}
	});
});
function obtenerConteo(idConteo)
{
	$('#ventanaEditarConteo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConteo').html('<img src="'+ img_loader +'"/> Obteniendo formulario de conteos...');
		},
		type:"POST",
		url:base_url+"pedidos/obtenerConteo",
		data:
		{
			"idConteo":idConteo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConteo').html(data);
			$('#txtComentarios').focus();
			re	= obtenerNumero($('#txtNumeroProductos').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de conteos',500,5000,'error',30,3)
			$("#obtenerConteo").html('');	
		}
	});
}

function editarConteo()
{
	if(!comprobarConteos())
	{
		notify('Configure correctamente los productos para las conteos',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoConteo').html('<img src="'+ img_loader +'"/> Editando conteo, por favor espere...');
		},
		type:"POST",
		url:base_url+"pedidos/editarConteo",
		data:$('#frmConteos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoConteo').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaEditarConteo').dialog('close');
					obtenerConteos()
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoConteo').html('');
			notify('Error al editar el conteo',500,5000,'error',30,3);	
		}
	});
}

function tipoConteo()
{
	idLinea=obtenerNumero($('#selectLineas').val());
	
	if(idLinea==3)
	{
		$('#spnConteo').html($('#txtBizcocho').val());
		$('#txtBuscarProducto').fadeIn(1);
		$('#txtBuscarProductoFrances').fadeOut(1);
		$('#txtBuscarProductoLinea').fadeOut(1);
	}
	
	if(idLinea==2)
	{
		$('#spnConteo').html($('#txtFrances').val());
		$('#txtBuscarProducto').fadeOut(1);
		$('#txtBuscarProductoFrances').fadeIn(1);
		$('#txtBuscarProductoLinea').fadeOut(1);
	}
	
	if(idLinea!=2 && idLinea!=3)
	{
		$('#spnConteo').html($('#txtLinea').val());
		$('#txtBuscarProducto').fadeOut(1);
		$('#txtBuscarProductoFrances').fadeOut(1);
		//$('#txtBuscarProductoLinea').fadeIn(1);
		
		
		$.ajax(
		{
			async:false,
			beforeSend:function(objeto)
			{
				//$('#procesandoConteos').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
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
				//$('#procesandoConteos').html('');
				notify('Error',500,5000,'error',30,3);	
			}
		});
		
	}
	
	reiniciarConteo()
}

function reiniciarConteo()
{
	if($('#selectLineas').val()!="4")
	{
		$('#tablaConteos').html('<thead><tr><th width="3%">-</th><th width="20%">Código</th><th width="60%">Producto</th><th>Cantidad</th></tr> </thead> <tbody></tbody>');
	}
	else
	{
		$('#tablaConteos').html('<thead><tr><th width="3%">-</th><th width="20%">Código</th><th width="50%">Producto</th><th>Cantidad</th><th>Peso en kg</th></tr> </thead> <tbody></tbody>');
	}
}


//DETALLES CONTEO

function detallesConteo(idConteo)
{
	$('#ventanaDetallesConteo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#detallesConteo').html('<img src="'+ img_loader +'"/> Obteniendo detalles de conteos...');
		},
		type:"POST",
		url:base_url+"pedidos/detallesConteo",
		data:
		{
			"idConteo":idConteo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#detallesConteo').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de conteos',500,5000,'error',30,3)
			$("#detallesConteo").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaDetallesConteo").dialog(
	{
		autoOpen:false,
		height:500,
		width:850,
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
			$("#formularioConteos").html('');
		}
	});
});

function reporteConteos(idConteo)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoConteo').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteConteos',
		data:
		{
			"idConteo":	idConteo,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoConteo').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Conteos'
		},
		error:function(datos)
		{
			$("#procesandoConteo").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}



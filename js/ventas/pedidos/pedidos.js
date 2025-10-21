$(document).ready(function()
{
	$("#ventanaPedidos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1050,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('.lblPedido').html('Pedido 1')
				$(this).dialog('close');  	  
			},
			
		},
		close: function() 
		{
			//$("#formularioPedidos").html('');
		}
	});
});

function formularioPedidos()
{
	if(!comprobarProductosVentaPedido())
	{
		notify('Agregue al menos un producto',500,5000,'error',30,5);
		return;
	}
	
	$('#ventanaPedidos').dialog('open');
	
	if(obtenerNumeros($('#txtPedidoActivo').val())=='1')
	{
		return;
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#formularioPedidos').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'ventas/formularioPedidos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPedidos').html(data);
		},
		error:function(datos)
		{
			$('#formularioPedidos').html('');
		}
	});		
}

function obtenerDireccionesEntrega()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerDireccionesEntrega').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerDireccionesEntrega',
		data:
		{
			'idCliente':$('#txtIdCliente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDireccionesEntrega').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDireccionesEntrega').html('');
		}
	});		
}


$(document).ready(function()
{
	$("#ventanaDireccionesCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:430,
		width:1050,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				editarDireccionesCliente()
			},
			
		},
		close: function() 
		{
			$("#obtenerDireccionesCliente").html('');
		}
	});
});

function obtenerDireccionesCliente()
{
	$('#ventanaDireccionesCliente').dialog('open');

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerDireccionesCliente').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDireccionesCliente',
		data:
		{
			idCliente: $('#txtIdCliente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDireccionesCliente').html(data);
		},
		error:function(datos)
		{
			$('#obtenerDireccionesCliente').html('');
		}
	});		
}

function editarDireccionesCliente()
{
	if(!confirm('¿Realmente desea editar los registros?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDirecciones').html('<img src="'+ img_loader +'"/> Editando direcciones...');
		},
		type:"POST",
		url:base_url+"clientes/editarDireccionesCliente",
		data: $('#frmDireccionesCliente').serialize()+'&idCliente='+$('#txtIdCliente').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoDirecciones').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al editar las direcciones',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Los registros se han editado correctamente',500,5000,'',30,5);
					$('#ventanaDireccionesCliente').dialog('close');
					
					obtenerDireccionesEntrega()
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al cliente',500,5000,'error',30,5);
			$('#cargandoClientes').html('')
		}
	});	
}

ma=0;

function comprobarMaterialPedido(idMaterial)
{
	for(i=0;i<ma;i++)
	{
		if(obtenerNumeros($('#txtIdMaterial'+i).val())>0)
		{
			if(obtenerNumeros($('#txtIdMaterial'+i).val())==obtenerNumeros(idMaterial))
			{
				return false;
			}
		}
	}

	return true;
}

totalMateriales	= 0;

function calcularTotalesMateriales()
{
	totalMateriales	= 0;
	
	for(i=0;i<ma;i++)
	{
		if(obtenerNumeros($('#txtIdMaterial'+i).val())>0)
		{
			cantidad		= obtenerNumeros($('#txtCantidadMaterial'+i).val());
			precio			= obtenerNumeros($('#txtPrecioMaterial'+i).val());
			importe			= precio*cantidad;
			
			totalMateriales	+=importe;
			
			$('#lblImporte'+i).html(redondear(importe))
			$('#txtImporteMaterial'+i).val(importe)
		}
	}
	
	//precio				= obtenerNumeros($('#txtPrecioProducto1').val())
	
	precio				= obtenerNumeros($('#txtPrecioOriginal1').val())
	peso				= obtenerNumeros($('#txtPesoKg').val()) //OBNTENER EL PESO DE LOS PASTELES
	peso				= peso==0?1:peso;
	
	
	importeProducto		= precio+totalMateriales;
	importeProducto		= importeProducto*peso;
	
	$('#txtPrecioProducto1').val(importeProducto)
	$('#lblPrecioProducto1').html(redondear(importeProducto))
	//calcularSubTotal();
	
	calcularFilaProducto(1);
	
	return true;
}

function quitarMateriaPedido(MA)
{
	$('#filaMaterialPedido'+MA).remove()
	calcularTotalesMateriales()
}

function cargarMateriaPrimaPedido(material)
{
	if(!comprobarMaterialPedido(material.idMaterial)==1)
	{
		notify('Ya se ha agregado el material',500,4000,"error",30,5);
		return;
	}
	
	precio	= obtenerNumeros(material.precioImpuestos);

	data='<tr id="filaMaterialPedido'+ma+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarMateriaPedido('+ma+')"></td>';
	data+='<td>'+material.codigoInterno+'</td>';
	data+='<td>'+material.nombre+'</td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtCantidadMaterial'+ma+'" name="txtCantidadMaterial'+ma+'" value="1" style="width:50px" onkeypres="return soloDecimales(event)" onchange="calcularTotalesMateriales(); valorMinimo(this.value,1); " /></td>';
	data+='<td>'+material.unidad+'</td>';
	data+='<td align="right">$'+redondear(precio)+'</td>';
	data+='<td id="lblImporte'+ma+'" align="right">$'+redondear(precio)+'</td>';
	data+='<input type="hidden" id="txtIdMaterial'+ma+'" 		name="txtIdMaterial'+ma+'" 		value="'+material.idMaterial+'" />';
	data+='<input type="hidden" id="txtPrecioMaterial'+ma+'" 	name="txtPrecioMaterial'+ma+'" 	value="'+precio+'" />';
	data+='<input type="hidden" id="txtImporteMaterial'+ma+'" 	name="txtImporteMaterial'+ma+'" value="'+precio+'" />';
	data+='<input type="hidden" id="txtCostoMaterial'+ma+'" 	name="txtCostoMaterial'+ma+'" 	value="'+material.costo+'" />';
	data+='</tr>';

	ma++;
	
	$('#tablaMateriales').append(data);
	$("#tablaMateriales tr:even").addClass("sombreado");
	$("#tablaMateriales tr:odd").addClass("sinSombra"); 
	$('#txtNumeroMateriales').val(ma);

	setTimeout(function() 
	{
		$('#txtBuscarMateriaPrima').val('');
		$('#txtBuscarMateriaPrima').focus();
	}, 100);
	
	calcularTotalesMateriales()
}

function pastelEspecial()
{
	if(document.getElementById('chkEspecial').checked)
	{
		$('#txtEspecial').attr('disabled',false);
		$('#txtEspecial').focus();
	}
	else
	{
		$('#txtEspecial').attr('disabled',true);
	}
	
	etiquetaProductoEspecial()
}

function etiquetaProductoEspecial()
{
	if(document.getElementById('chkEspecial').checked)
	{
		$('#txtNombreProducto1').val($('#txtEspecial').val())
		$('#lblNombreProducto1').html($('#txtEspecial').val())
	}
	else
	{
		$('#txtNombreProducto1').val($('#txtNombreProductoOriginal1').val())
		$('#lblNombreProducto1').html($('#txtNombreProductoOriginal1').val())
	}
	
	
}




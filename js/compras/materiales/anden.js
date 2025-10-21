
$(document).ready(function()
{
	$("#ventanaAnden").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:1000,
		modal:true,
		resizable:false,
		buttons:
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				recibirMaterialesAnden()		  	  
			},
		},
		close: function() 
		{
			$("#obtenerMaterialesCompra").html('');
		}
	})
});

function recepcionesAnden(idCompras)
{
	$('#ventanaAnden').dialog('open');	

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#recepcionesAnden').html('<img src="'+ img_loader +'"/>Obteniendo detalles de compra...');},
		type:"POST",
		url:base_url+"compras/recepcionesAnden",
		data:
		{
			"idCompras":	idCompras,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#recepcionesAnden').html(data);					   
			$('#txtBuscarProductoAnden').focus();
			an=0;
		},
		error:function(datos)
		{
			$('#recepcionesAnden').html('');	
			notify('Error al obtener los detalles de compra',500,4000,"error",30,5);	
		}
	});//Ajax
}

function comprobarProductoAnden(idDetalle)
{
	for(i=0;i<an;i++)
	{
		if(obtenerNumero($('#txtIdDetalle'+i).val())>0)
		{
			if(obtenerNumero(idDetalle)==obtenerNumero($('#txtIdDetalle'+i).val()))
			{
				return false;
			}
		}
	}

	return true;
}

an=0;

function calcularDiferenciasAnden()
{
	for(i=0;i<an;i++)
	{
		if(obtenerNumero($('#txtIdDetalle'+i).val())>0)
		{
			cantidad	= obtenerNumero($('#txtCantidadRecibir'+i).val())
			producto	= obtenerNumero($('#txtCantidadProducto'+i).val())
			
			diferencia	= cantidad-producto;
			
			$('#filaDiferencia'+i).html(redondear(diferencia))
		}
	}
}

function agregarProductoAnden(producto)//n es el numero de fila
{
	if(!comprobarProductoAnden(producto.idDetalle))
	{
		setTimeout(function(){$('#txtBuscarProductoAnden').val('');},200);
		
		notify('Ya se ha agregado el producto',500,4000,"error",30,5);	
		return;
	}
	
	cantidadAnden	= obtenerNumero($('#txtCantidadAnden').val());
	
	data='<tr id="filaAnden'+an+'">';
	data+='<td align="center">';
	data+='<img style="cursor:pointer" onclick="quitarProductoAnden('+an+')" src="'+base_url+'img/borrar.png" width="18" tittle="Quitar producto"  />';
	data+='</td>';
	data+='<td align="center">'+producto.codigoInterno+'</td>';
	data+='<td align="center">'+producto.nombre+'</td>';
	data+='<td align="center"><input type="text" id="txtCantidadRecibir'+an+'" name="txtCantidadRecibir'+producto.idDetalle+'" style="width:80px" class="cajas" value="'+cantidadAnden+'" onkeypress="return soloDecimales(event)" onchange="calcularDiferenciasAnden()" /></td>';
	data+='<td align="center" >'+obtenerNumero(producto.cantidad)+'</td>';
	data+='<td align="center" id="filaDiferencia'+an+'"></td>';
	data+='<input type="hidden" id="txtCantidadProducto'+an+'" name="txtCantidadProducto'+producto.idDetalle+'" value="'+producto.cantidad+'" />';
	data+='<input type="hidden" id="txtIdDetalle'+an+'"  name="txtIdDetalle'+producto.idDetalle+'" value="'+producto.idDetalle+'" />';
	data+='</tr>';
	
	$('#tablaAnden').append(data); 
	
	an++;
	
	$('#txtCantidadAnden').val('1')
	$('#txtBuscarProductoAnden').focus();
	
	$("#tablaAnden tr:even").addClass("sombreado");
	$("#tablaAnden tr:odd").addClass("sinSombra");  
	
	calcularDiferenciasAnden()
	
	setTimeout(function(){$('#txtBuscarProductoAnden').val('');},200);
}

function quitarProductoAnden(i)
{
	$('#filaAnden'+i).remove();
	
	calcularDiferenciasAnden()
}

function comprobarProductosAnden()
{
	for(i=0;i<an;i++)
	{
		if(obtenerNumero($('#txtIdDetalle'+i).val())>0)
		{
			if(obtenerNumero($('#txtCantidadRecibir'+i).val())>0)
			{
				return true;
			}
		}
	}

	return false;
}


function recibirMaterialesAnden()
{
	if(!comprobarProductosAnden())
	{
		notify('Agregue al menos una cantidad',500,4000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea recibir los productos?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#recibiendoAnden').html('<img src="'+ img_loader +'"/> Recibiendo las compras');},
		type:"POST",
		url:base_url+"compras/recibirMaterialesAnden",
		data:$('#frmAnden').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#recibiendoAnden').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('Registro exitoso',500,6000,"",30,5);
					//setTimeout(function(){location.reload(true);},2000);
					obtenerComprasMateriales();
					$("#ventanaAnden").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar las compras',500,4000,"error",30,5);
			$("#recibiendoAnden").html('');	
		}
	});				
}
fila=1;

function formularioFacturaManual()
{
	$('#ventanaFacturaManual').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioFacturaManual').html('<img src="'+ img_loader +'"/>Obteniendo detalles para la factura, por favor espere...');},
		type:"POST",
		url:base_url+'facturacion/formularioFacturaManual',
		data:
		{
			"idCliente": $('#clienteFactura').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioFacturaManual").html(data);
			fila=1;
		},
		error:function(datos)
		{
			$("#formularioFacturaManual").html('');
			notify('Error al obtener los datos para la factura',500,5000,'error',30,3);
		}
	});				  	  
}

$(document).ready(function()
{
	$("#ventanaFacturaManual").dialog(
	{
		autoOpen:false,
		height:650,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarFacturaManual();
			},
		},
		close: function() 
		{
			$("#formularioFacturaManual").html('');
		}
	});
});

function quitarConceptoFactura(i)
{
	$('#filaFactura'+i).remove();
}

function cargarConceptoFactura()
{
	data='<tr id="filaFactura'+fila+'">';
	data+='<td><input type="hidden" id="txtIdConcepto'+fila+'" name="txtIdConcepto'+fila+'" value="'+fila+'" />';
	data+='<img src="'+base_url+'img/borrar.png" onclick="quitarConceptoFactura('+fila+')" width="22" /></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtConceptoFactura'+fila+'" name="txtConceptoFactura'+fila+'" style="width:320px"></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtBuscarUnidad'+fila+'" name="txtBuscarUnidad'+fila+'" style="width:100px" value="H87, Pieza">\
	<input type="hidden" class="cajas" id="txtClaveUnidad'+fila+'" name="txtClaveUnidad'+fila+'" style="width:100px" value="H87">\
	<input type="hidden" class="cajas" id="txtUnidadFactura'+fila+'" name="txtUnidadFactura'+fila+'" style="width:100px" value="Pieza"></td>';
	
	data+='<td align="center"><input type="text" class="cajas" id="txtBuscarClave'+fila+'" name="txtBuscarClave'+fila+'" style="width:100px" value="01010101, No existe en el catálogo">\
	<input type="hidden" id="txtClaveProductoFactura'+fila+'" name="txtClaveProductoFactura'+fila+'" value="01010101">\
	<input type="hidden" id="txtClaveProductoDescripcion'+fila+'" name="txtClaveProductoDescripcion'+fila+'" value="No existe en el catálogo"></td>';
	
	data+='<td align="center"><input type="text" class="cajas" id="txtCantidadFactura'+fila+'" name="txtCantidadFactura'+fila+'" style="width:50px" onchange="calcularTotalesFactura()" ></td>';
	data+='<td align="center"><input type="text" class="cajas" id="txtPrecioFactura'+fila+'" name="txtPrecioFactura'+fila+'" style="width:80px" onchange="calcularTotalesFactura()" ></td>';
	data+='<td align="center"><input type="text" readonly="readonly" class="cajas" id="txtImporteFactura'+fila+'" name="txtImporteFactura'+fila+'" style="width:80px" ></td>';
	
	data+='<input type="hidden" id="txtIvaProducto'+fila+'" name="txtIvaProducto'+fila+'" value="0">';
	data+='</tr>';
	
	fila++;
	$('#txtNumeroProductos').val(fila);
	$('#tablaFacturacion').append(data);
	
	asignarDatos(fila-1)
}

function asignarDatos(Fila)
{
	$("#txtBuscarClave"+Fila).autocomplete(
	{
		source:base_url+"configuracion/autoCompletadoProductoServicios",
		select: function(event,ui)
		{
			$("#txtClaveProductoFactura"+Fila).val(ui.item.clave);
			$("#txtClaveProductoDescripcion"+Fila).val(ui.item.nombre);
		}
	});
	
	$("#txtBuscarUnidad"+Fila).autocomplete(
	{
		source:base_url+"configuracion/autoCompletadoUnidades",
		select: function(event,ui)
		{
			$("#txtClaveUnidad"+Fila).val(ui.item.clave);
			$("#txtUnidadFactura"+Fila).val(ui.item.nombre);
		}
	});
}

function calcularTotalesFactura() //Calcular los importes por conceptos
{
	subTotal			= 0;
	iva					= 0;
	total				= 0;
	
	ivaPorcentaje		= obtenerNumeros($('#txtIvaPorcentaje').val());
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdConcepto'+i).val()))
		{
			cantidad	= obtenerNumeros($('#txtCantidadFactura'+i).val());
			precio		= obtenerNumeros($('#txtPrecioFactura'+i).val());
			
			$('#txtPrecioFactura'+i).val(precio)

			/*if(isNaN(cantidad) || cantidad==0 || Solo_Numerico(cantidad)=="")
			{
				cantidad=0;
			}
			
			if(isNaN(precio) || precio==0 || Solo_Numerico(precio)=="")
			{
				precio=0;
			}*/
			
			importe			= precio*cantidad;
			importe			= obtenerNumeros(importe);
			subTotal		+=importe;
			
			ivaProducto		= ivaPorcentaje*importe;
			ivaProducto		= obtenerNumeros(ivaProducto);
			
			iva				+=ivaProducto
			
			$('#txtImporteFactura'+i).val(importe)
			$('#txtIvaProducto'+i).val(ivaProducto)
		}
	}

	/*iva		= parseFloat($('#txtIvaPorcentaje').val());
	iva		= iva*subTotal;*/
	iva		= obtenerNumeros(iva);
	total	= subTotal+iva;
	total	= obtenerNumeros(total)
	
	$('#txtSubTotal').val(subTotal)
	$('#txtIva').val(iva)
	$('#txtTotal').val(total)
	
	$('#lblSubTotal').html(redondear(subTotal))
	$('#lblIva').html(redondear(iva))
	$('#lblTotal').html(redondear(total))
	
}

function registrarFacturaManual()
{
	if($('#txtFoliosActivos').val()=="0")
	{
		notify('Los folios se han terminado, por favor consulte con el administrador',500,5000,'error',30,5);
		return;
	}
	
	var mensaje="";
	
	if($("#selectEmisores").val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";										
	}
	
	if($("#txtIdClienteGlobal").val()=="0")
	{
		mensaje+="Seleccione el cliente <br />";										
	}
	
	if($("#txtSubTotal").val()=="0")
	{
		mensaje+="El importe de la factura es incorrecto <br />";										
	}
	
	b=1;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdConcepto'+i).val()))
		{
			cantidad	=parseFloat($('#txtCantidadFactura'+i).val());
			precio		=parseFloat($('#txtPrecioFactura'+i).val());

			if(isNaN(cantidad) || cantidad==0 || Solo_Numerico(cantidad)=="")
			{
				b=0;
				break;
			}
			
			if(isNaN(precio) || precio==0 || Solo_Numerico(precio)=="")
			{
				b=0;
				break;
			}
			
			if($('#txtConceptoFactura'+i).val()=="")
			{
				b=0;
				break;
			}
		}
	}
	
	if(b==0)
	{
		mensaje+="Configure correctamente los conceptos de la factura <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(confirm('¿Realmente desea registrar la factura?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoFacturaManual').html('<img src="'+ img_loader +'"/>Se esta creando la factura, por favor espere...');},
		type:"POST",
		url:base_url+"facturacion/registrarFacturaManual",
		data:
		$('#frmFacturacion').serialize()+'&metodoPagoTexto='+$("#txtMetodoPago option:selected").text()+'&formaPagoTexto='+$("#txtFormaPago option:selected").text()+'&usoCfdiTexto='+$("#selectUsoCfdi option:selected").text(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoFacturaManual").html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				case "1":
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoFacturaManual").html('');
			notify('Error al realizar la factura',500,5000,'error',30,3);
		}
	});		
}



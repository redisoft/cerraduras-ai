//REGISTRAR CFDI DE FACTURA
$(document).ready(function()
{
	$("#ventanaEditarVenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:900,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			"cfdiVenta" : 
			{
				text: "Aceptar",
				id: "btnEditarVenta",
				click: function()
				{
					editarVenta();
				}   
		  	},
		},
		
		close: function() 
		{
			$("#obtenerVentaEditar").html('');
		}
	});
});

function obtenerVentaEditar(idCotizacion)
{
	$("#ventanaEditarVenta").dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentaEditar').html('<img src="'+ img_loader +'"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'ventas/obtenerVentaEditar',
		data:
		{
			idCotizacion: idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerVentaEditar').html(data);
		},
		error:function(datos)
		{
			$('#obtenerVentaEditar').html('');
			notify('Error en el formulario',500,5000,'error',30,5);
		}
	});		
}

function editarVenta()
{
	mensaje			= "";
	
	if(obtenerNumeros($('#txtTotal').val())==0)
	{
		notify('El total de la venta no puede ser 0',500,5000,'error',30,5);
		return;
	}
	
	if(obtenerNumeros($('#txtTotalOriginal').val())==obtenerNumeros($('#txtTotal').val()))
	{
		notify('La venta no se ha modificado',500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente desea editar la ventas?')) return;

	$.ajax(
	{
		async:false,
		beforeSend:function(objeto){$('#editandoVenta').html('<img src="'+ img_loader +'"/> Se esta realizando la venta y la factura, por favor espere ...');},
		type:"POST",
		url:base_url+'ventas/editarVenta',		
		data: $('#frmEditarVenta').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#editandoVenta").html('');

			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,5);
					
					obtenerVentas();
					
					$("#ventanaEditarVenta").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$("#editandoVenta").html('');
			notify('Error al editar el registro',500,5000,'error',30,3);
		}
	});		
}

function calcularEditarVenta()
{
	numeroProductos	= obtenerNumeros($('#txtNumeroProductos').val());
	
	subTotal	= 0;
	b			= true;
	for(i=0;i<numeroProductos;i++)
	{
		cantidad		= obtenerNumeros(redondear($('#txtCantidad'+i).val()));
		cantidadTotal	= obtenerNumeros($('#txtCantidadTotal'+i).val());
		precio			= obtenerNumeros($('#txtPrecio'+i).val());
		
		if(cantidad>cantidadTotal)
		{
			cantidad	= cantidadTotal;
			
			b=false;
		}
		
		importe			= cantidad*precio;
		importe			= obtenerNumeros(importe)
		
		$('#txtCantidad'+i).val(cantidad);
		$('#txtImporte'+i).val(redondearDecimales(importe,4));
		
		$('#lblImporte'+i).html('$'+redondearDecimales(importe,4));
		
		subTotal+=importe;
	}
	
	subTotal		= obtenerNumeros(redondear(subTotal));
	
	ivaPorcentaje	= obtenerNumeros($('#txtIvaPorcentaje').val());
	ivaPorcentaje	= ivaPorcentaje/100;
	
	iva				= subTotal * ivaPorcentaje;
	iva				= obtenerNumeros(redondear(iva));
	
	total			= subTotal + iva;
	
	$('#txtSubTotal').val(redondear(subTotal));
	$('#txtIvaTotal').val(redondear(iva));
	$('#txtTotal').val(redondear(total));
	
	$('#lblSubTotal').html('$'+redondear(subTotal));
	$('#lblIva').html('$'+redondear(iva));
	$('#lblTotal').html('$'+redondear(total));
	
	if(!b)
	{
		notify('La cantidad no puede ser mayor a la actual',500,5000,'error',30,3);
	}
}
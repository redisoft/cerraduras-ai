$(document).ready(function()
{
	$("#ventanaInventarioFaltante").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			/*Cancelar: function() 
			{
				$(this).dialog('close');				 
			},*/
			'Registrar traspaso': function() 
			{
				registrarTraspasosVenta()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioInventarioFaltante").html('');
		}
	});
});

function formularioInventarioFaltante()
{
	$('#ventanaInventarioFaltante').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioInventarioFaltante').html('<img src="'+ img_loader +'"/> Obteniendo el detalle de productos');
		},
		type:"POST",
		url:base_url+'tiendas/formularioInventarioFaltante',
		data:$('#frmVentasClientes').serialize()+'&numeroProductos='+fila,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioInventarioFaltante').html(data);
		},
		error:function(datos)
		{
			$('#formularioInventarioFaltante').html('');
			notify('Error al procesar el formulario',500,5000,'error',30,5);
		}
	});		
}

function obtenerInventarioProductoTraspaso(i)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cantidadDisponible'+i).html('<img src="'+ img_loader +'"/> Obteniendo el detalle de inventario');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerStockTienda',
		data:
		{
			idTienda: 	$('#selectTiendaOrigen'+i).val(),
			idProducto: $('#txtIdProductoTraspaso'+i).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cantidadDisponible'+i).html(redondear(data));
			$('#txtCantidadDisponibleTraspaso'+i).val(data);
		},
		error:function(datos)
		{
			$('#cantidadDisponible'+i).html('');
			notify('Error al obtener los detalles del inventario',500,5000,'error',30,5);
		}
	});		
}

function configurarFaltantes()
{
	for(i=0;i<=parseInt($('#txtNumeroProductosTraspaso').val());i++)
	{
		if(!isNaN(parseFloat($('#txtIdProductoTraspaso'+i).val())))
		{
			$('#txtStockDisponible'+$('#txtFilaProducto'+i).val()).val($('#txtCantidadProducto'+$('#txtFilaProducto'+i).val()).val())
			
			//alert($('#txtStockDisponible'+$('#txtFilaProducto'+i).val()));
		}
	}
	
	$('#ventanaInventarioFaltante').dialog('close');
}

function registrarTraspasosVenta()
{
	for(i=0;i<=parseInt($('#txtNumeroProductosTraspaso').val());i++)
	{
		if(!isNaN(parseFloat($('#txtIdProductoTraspaso'+i).val())))
		{
			if(parseFloat($('#txtCantidadTraspaso'+i).val())>parseFloat($('#txtCantidadDisponibleTraspaso'+i).val()))
			{
				notify('Las cantidades son incorrectas',500,5000,'error',30,5);
				return;
			}
		}
	}
	
	if(!confirm('¿Realmente desea registrar los traspasos?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoTraspaso').html('<img src="'+ img_loader +'"/> Registrando traspaso');
		},
		type:"POST",
		url:base_url+'tiendas/registrarTraspasosVenta',
		data:$('#frmTraspasos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoTraspaso').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El traspaso se ha registrado correctamente',500,5000,'',30,5);
					configurarFaltantes();
				break;
			}
			
		},
		error:function(datos)
		{
			$('#registrandoTraspaso').html('');
			notify('Error al procesar los traspasos',500,5000,'error',30,5);
		}
	});		
}
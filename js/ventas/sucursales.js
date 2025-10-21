//STOCK DE SUCURSALES
$(document).ready(function()
{
	$("#ventanaStockSucursales").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:380,
		width:700,
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
			$("#obtenerStockSucursales").html('');
		}
	});
});

function obtenerStockSucursales(idProducto)
{
	$('#ventanaStockSucursales').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerStockSucursales').html('<img src="'+ img_loader +'"/> Obteniendo el detalles de stock');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerStockSucursales',
		data:
		{
			idProducto: idProducto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerStockSucursales').html(data);
		},
		error:function(datos)
		{
			$('#obtenerStockSucursales').html('');
			notify('Error al obtener el stock',500,5000,'error',30,5);
		}
	});		
}

numeroFaltantes		= 0;
fal					= 1;
function comprobarFaltantesProductos()
{
	numeroFaltantes	= 0;
	faltantes	= '\
	<table class="admintable" width="100%" id="tablaFaltantes">\
		<tr>\
			<th>#</th>\
			<th>Producto</th>\
			<th>Cantidad requerida</th>\
			<th>Cantidad disponible</th>\
			<th>Cantidad faltante</th>\
		</tr>';
	
	/*for(i=0;i<=fila;i++)
	{
		if(!isNaN($('#txtIdProducto'+i).val()))
		{
			if(parseFloat($('#txtCantidadProducto'+i).val())>parseFloat($('#txtStockDisponible'+i).val()))
			{
				faltantes+=$('#txtNombreProducto'+i).val()+', Cantidad: ' +redondear($('#txtCantidadProducto'+i).val())+', Inventario:'+$('#txtStockDisponible'+i).val()+'\n';
			}
		}
	}*/
	
	for(i=0;i<=fila;i++)
	{
		if(!isNaN($('#txtIdProducto'+i).val()))
		{
			if(parseFloat($('#txtCantidadProducto'+i).val())>parseFloat($('#txtStockDisponible'+i).val()))
			{
				numeroFaltantes++;
				
				//faltantes+=$('#txtNombreProducto'+i).val()+', Cantidad: ' +redondear($('#txtCantidadProducto'+i).val())+', Inventario:'+$('#txtStockDisponible'+i).val()+'\n';
				
				faltantes+='\
				<tr>\
					<td>'+numeroFaltantes+'</td>\
					<td>'+$('#txtNombreProducto'+i).val()+'</td>\
					<td align="center">'+redondear($('#txtCantidadProducto'+i).val())+'</td>\
					<td align="center">'+redondear($('#txtStockDisponible'+i).val())+'</td>\
					<td align="center">'+redondear(parseFloat($('#txtCantidadProducto'+i).val())-parseFloat($('#txtStockDisponible'+i).val()))+'</td>\
				</tr>';
				
				fal++;
			}
		}
	}
	
	faltantes+= '</table> ';
	
	return faltantes;
}
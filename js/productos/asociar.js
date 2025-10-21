function obtenerProveedoresCompra(idProducto)
{
	$('#ventanaAgregarProveedoresCompra').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProveedoresCompra').html('<img src="'+ img_loader +'"/> Cargando lista de proveedores...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/obtenerTodosProveedores',
		data:
		{
			"idProducto":	idProducto,
			"editar":		0,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProveedoresCompra').html(data)
		},
		error:function(datos)
		{
			$('#obtenerProveedoresCompra').html('')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarProveedoresCompra").dialog(
	{
		autoOpen:false,
		height:300,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asociarProveedorProductoCompra()			 
			}
		},
		close: function()
		{
			$("#obtenerProveedoresCompra").html('');
		}
	});
})

function asociarProveedorProductoCompra()
{
	mensaje="";
				
	if($("#proveedoresProductos").val()=="0")
	{
		mensaje+='Debe seleccionar un proveedor <br />';
	}
	
	if(isNaN($("#txtCostoProducto").val()) || parseFloat($("#txtCostoProducto").val())<0 || $("#txtCostoProducto").val()=="")
	{
		mensaje+='El costo del producto es incorrecto';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea asociar este proveedor con el producto?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asociandoProveedorProducto').html('<img src="'+ img_loader +'"/>Se esta asociando el proveedor con el producto, por favor espere...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/asociarProveedorProducto',
		data:
		{
			"idProveedor":	$("#proveedoresProductos").val(),
			"idProducto": 	$("#txtIdProducto").val(),
			"precio": 		$("#txtCostoProducto").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asociandoProveedorProducto').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$('#ventanaAgregarProveedoresCompra').dialog('close');
					obtenerProductosReventa();
					notify('El proveedor se ha asociado correctamente ',500,4000,"",30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#asociandoProveedorProducto').html('');
			notify('Error al asociar el proveedor con el producto',500,4000,"error");;
		}
	});	
}
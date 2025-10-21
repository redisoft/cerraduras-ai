//ASOCIAR PROVEEDOR CON INVENTARIO

$(document).ready(function()
{
	$("#ventanaAsociarProveedorCompra").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:600,
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
				asociarProveedorInventarioCompra();
			},
		},
		close: function() 
		{
			$("#formularioAgregarProveedorCompra").html('');
		}
	});
});

function formularioAgregarProveedorCompra(idInventario)
{
	$('#ventanaAsociarProveedorCompra').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarProveedorCompra').html('<img src="'+ img_loader +'"/> Obteniendo los datos para asociar el producto al proveedor...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/formularioAgregarProveedor",
		data:
		{
			"idInventario":	idInventario,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarProveedorCompra').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para asociar al proveedor',500,4000,"error");
			$("#formularioAgregarProveedorCompra").html("");	
		}
	});
}

function asociarProveedorInventarioCompra()
{
	var mensaje="";

	if($("#selectAsociarProveedor").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}

	if(!comprobarNumeros($("#txtCostoProveedor").val()) || parseFloat($("#txtCostoProveedor").val())==0)
	{
		mensaje+="El costo es incorrecto<br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea registrar el proveedor?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoProveedorCompra').html('<img src="'+ img_loader +'"/> Registran el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"inventarioProductos/asociarProveedorInventario",
		data:
		{
			"idInventario":	$('#txtIdInventarioAsociar').val(),
			"idProveedor":	$('#selectAsociarProveedor').val(),
			"costo":		$('#txtCostoProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#agregandoProveedorCompra").html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$("#ventanaAsociarProveedorCompra").dialog('close');
					notify('El proveedor se ha agregado correctamente',500,4000,"",30,5);
					obtenerProductosInventarios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proveedor al inventario',500,4000,"error",30,5);
			$("#agregandoProveedorCompra").html("");	
		}
	});
}

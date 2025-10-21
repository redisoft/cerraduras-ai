//=======================================================================================================//
//==============================AGREGAR UN NUEVO PROVEEDOR MATERIAS======================================//
//=======================================================================================================//

function obtenerProveedoresCompraAsociar(idMaterial)
{
	$('#ventanaAgregarProveedorCompra').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProveedoresCompraAsociar').html('<img src="'+ img_loader +'"/> Obteniendo lista de proveedores...');
		},
		type:"POST",
		url:base_url+'materiales/obtenerTodosProveedores',
		data:
		{
			"idMaterial":	idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProveedoresCompraAsociar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerProveedoresCompraAsociar').html('')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarProveedorCompra").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asociarMaterialProveedorCompra()
			}
		},
		close: function()
		{
			$("#obtenerProveedoresCompraAsociar").html('');
		}
	});
})

function asociarMaterialProveedorCompra()
{
	mensaje	= "";
	
	if($("#proveedoresMateriales").val()=="0")
	{
		mensaje+='Debe seleccionar un proveedor </br>';
	}
	
	if(!comprobarNumeros($("#txtCostoMaterial").val()) || parseFloat($("#txtCostoMaterial").val())<0)
	{
		mensaje+='El costo es incorrecto';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea agregar este proveedor?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asociandoProveedorCompra').html('<img src="'+ img_loader +'"/> Agregando el proveedor al material...');
		},
		type:"POST",
		url:base_url+'materiales/agregarProveedorMaterial',
		data:
		{
			"idProveedor":	$("#proveedoresMateriales").val(),
			"idMaterial": 	$("#txtIdMaterialAsociar").val(),
			"costo": 		$("#txtCostoMaterial").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asociandoProveedorCompra').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El proveedor se ha asociado correctamente',500,5000,'',30,5);
					$("#ventanaAgregarProveedorCompra").dialog('close');
					obtenerMaterialesCompra();
					obtenerRequisiciones();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al asociar el proveedor',500,5000,'error',30,5);
			$('#asociandoProveedorCompra').html('');
		}
	});	
}
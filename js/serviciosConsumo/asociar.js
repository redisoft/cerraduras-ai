//ASOCIAR PROVEEDOR CON INVENTARIO

$(document).ready(function()
{
	$("#ventanaAsociarProveedorCompra").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:750,
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
				asociarProveedorServicioCompra();
			},
		},
		close: function() 
		{
			$("#formularioAgregarProveedorCompra").html('');
		}
	});
});

function formularioAgregarProveedorCompra(idServicio)
{
	$('#ventanaAsociarProveedorCompra').dialog('open');	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarProveedor').html('<img src="'+ img_loader +'"/> Obteniendo los datos para asociar el servicio al proveedor...');
		},
		type:"POST",
		url:base_url+"servicios/formularioAgregarProveedor",
		data:
		{
			"idServicio":	idServicio,
			"opciones":		'0',
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

function asociarProveedorServicioCompra()
{
	var mensaje="";

	if($("#txtIdProveedorServicio").val()=="0")
	{
		mensaje+="Por favor seleccione el proveedor<br />";										
	}

	if(!comprobarNumeros($("#txtCostoServicio").val()) || parseFloat($("#txtCostoServicio").val())==0)
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
			$('#asociandoProveedorCompra').html('<img src="'+ img_loader +'"/> Registran el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"servicios/asociarProveedorServicio",
		data:
		{
			"idServicio":	$('#txtIdServicio').val(),
			"idProveedor":	$('#txtIdProveedorServicio').val(),
			"costo":		$('#txtCostoServicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#asociandoProveedorCompra").html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El proveedor se ha agregado correctamente',500,4000,"",30,5);
					$('#ventanaAsociarProveedorCompra').dialog('close');	
					obtenerServiciosCompra();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proveedor al servicio',500,4000,"error",30,5);
			$("#asociandoProveedorCompra").html("");	
		}
	});
}

function editarCostoProveedorServicioCompra(idServicio,idProveedor,i)
{
	mensaje="";
	
	if(!comprobarNumeros($("#precio"+i).val()) || parseFloat($("#precio"+i).val())==0 )
	{
		mensaje+='El costo del producto es incorrecto';
	}
	
	/*if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error");
		return;	
	}
	
	if(!confirm('¿Realmente desea editar el costo del servicio?')) return;
*/
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#procesandoCompraServicios').html('<img src="'+ img_loader +'"/> Editando el costo del servicio...');
		},
		type:"POST",
		url:base_url+'servicios/editarCostoProveedorServicio',
		data:
		{
			"costo":		$('#precio'+i).val(),
			"idServicio":	idServicio,
			"idProveedor":	idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCompraServicios').html('');
			
			switch(data)
			{
				case "0":
					//notify('El costo del servicio no ha sido modificado',500,4000,"error");
				break;
				
				case "1":
					//formularioAgregarProveedor(idProveedor);
					//notify('El costo se ha editado correctamente ',500,4000,"");
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('El precio del producto no ha sido modificado',500,4000,"error");
			$('#procesandoCompraServicios').html('')
		}
	});
}



$(document).ready(function()
{	
	$("#ventanaFormularioCrmCliente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarCrmProveedor();
			},
		},
		close: function() 
		{
			$("#formularioCrmClientes").html('');
		}
	});
});

function formularioCrmProveedores(fecha,hora1)
{
	$('#ventanaFormularioCrmCliente').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCrmClientes').html('<img src="'+ img_loader +'"/>Obteniendo el formulario de seguimiento...');
		},
		type:"POST",
		url:base_url+'crm/formularioCrmProveedores',
		data:
		{
			fecha:fecha,
			"hora1":hora1+':00',
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCrmClientes').html(data)
		},
		error:function(datos)
		{
			$('#formularioCrmClientes').html('');
			notify('Error al obtener el formulario de seguimiento',500,5000,'error',30,5);
		}
	});		
}

function obtenerContactosProveedor(idProveedor)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContactosProveedor').html('<img src="'+ img_loader +'"/>Cargando lista de contactos...');
		},
		type:"POST",
		url:base_url+'crm/obtenerContactosProveedor',
		data:
		{
			idProveedor:idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContactosProveedor').html(data)
		},
		error:function(datos)
		{
			$('#obtenerContactosProveedor').html('');
			notify('Error al obtener los contactos del proveedor',500,5000,'error',30,5);
		}
	});		
}

function registrarCrmProveedor()
{
	var mensaje	= "";
	status		= $('#selectStatus').val();
	estatus		= status.split('|');

	if(estatus[1]!="3")
	{
		if($('#txtComentarios').val()=="")
		{
			mensaje+='Los comentarios son requeridos <br />';
		}
	}
	
	if(estatus[1]=="3")
	{
		if($('#txtBitacora').val()=="")
		{
			mensaje+='La bitácora es requerida<br />';
		}
	}
	
	if($('#txtIdProveedor').val()=="0")
	{
		mensaje+='Seleccione el proveedor<br />';
	}
	
	if($('#txtIdProveedorBusquedaCrm').val()!="0")
	{
		if($('#txtIdProveedorBusquedaCrm').val()!=$('#txtIdProveedor').val())
		{
			mensaje+='El proveedor no coincide con la compra<br />';
		}
	}
	
	if($('#selectContactos').val()=="0")
	{
		mensaje+='Seleccione el contacto<br />';
	}
	
	if($('#txtFechaSeguimiento').val()=="")
	{
		mensaje+='Debe seleccionar una fecha <br />';
	}

	responsables	= new String($('#selectResponsable').val());
	responsable		= responsables.split("|");
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea agregar el seguimiento?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoCrmCliente').html('<img src="'+ img_loader +'"/>Se esta registrando el seguimiento...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarSeguimiento",
		data:
		{
			"comentarios":		$("#txtComentarios").val(),
			"bitacora":			$("#txtBitacora").val(),
			"fecha":			$('#txtFechaSeguimiento').val() + ' ' + $('#txtHoraSeguimiento').val(),
			"idProveedor":		$('#txtIdProveedor').val(),
			//"idStatus":			$('#selectStatus').val(),
			
			"idStatus":			estatus[0],
			"idStatusIgual":	estatus[1],
			
			"idServicio":		$('#selectServicio').val(),
			"idResponsable":	responsable[0],
			"fechaCierre":		$('#txtFechaCierre').val() + ' ' + $('#txtHoraCierre').val(),
			"lugar":			$('#txtLugar').val(),
			"email":			$("#txtEmailSeguimiento").val(),
			"idTiempo":			$("#selectTiempo").val(),
			"idContacto":		$("#selectContactos").val(),
			
			"idCompra":			$("#txtIdCompraCrm").val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCrmCliente').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
					$('#ventanaFormularioCrmCliente').dialog('close');
					obtenerSeguimientos();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoCrmCliente').html('')
			notify('Error al registrar el seguimiento',500,5000,'error',0,0);
		}
	});				
}

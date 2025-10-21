//ASIGNAR PORCENTAJE A LOS PRODUCTOS

function formularioPorcentaje(idProveedor)
{
	$('#ventanaPorcentaje').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPorcentaje').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'proveedores/formularioPorcentaje',
		data:
		{
			idProveedor:idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPorcentaje').html(data)
		},
		error:function(datos)
		{
			$('#formularioPorcentaje').html('')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaPorcentaje").dialog(
	{
		autoOpen:false,
		height:400,
		width:850,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asignarPorcentajes()			 
			}
		},
		close: function()
		{
			$("#formularioAsignarProveedor").html('');
		}
	});
})

function asignarPorcentajes()
{
	mensaje="";
				
	if(obtenerNumeros($("#txtPorcentaje1").val())==0 && obtenerNumeros($("#txtPorcentaje2").val())==0 && obtenerNumeros($("#txtPorcentaje3").val())==0 )
	{
		mensaje+='Asigne al menos un porcentaje <br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPorcentaje').html('<img src="'+ img_loader +'"/>Se esta asignando el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+'proveedores/asignarPorcentajes',
		data:$('#frmAsignarPorcentajes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPorcentaje').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro ha sido exitoso ',500,4000,"",30,5);
					formularioPorcentaje($('#txtIdProveedorAsignar').val())
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoPorcentaje').html('');
			notify('Error al asignar el proveedor',500,4000,"error");;
		}
	});	
}

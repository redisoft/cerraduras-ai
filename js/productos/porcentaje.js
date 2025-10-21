//ASIGNAR PORCENTAJE A LOS PRODUCTOS

function formularioPorcentaje(idProducto)
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
		url:base_url+'inventarioProductos/formularioPorcentaje',
		data:
		{
			idProducto:idProducto
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
		height:650,
		width:900,
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

function configurarPorcentajes()
{
	porcentaje1		= obtenerNumeros($("#txtPorcentaje1").val()) / 100;
	porcentaje2		= obtenerNumeros($("#txtPorcentaje2").val()) / 100;
	porcentaje3		= obtenerNumeros($("#txtPorcentaje3").val()) / 100;
	
	precioA			= obtenerNumeros($("#txtPrecioA").val())
	precioB			= obtenerNumeros($("#txtPrecioB").val())
	precioC			= obtenerNumeros($("#txtPrecioC").val())
	
	precioTotalA 	= precioA+(precioA*porcentaje1);
	precioTotalB 	= precioB+(precioB*porcentaje2);
	precioTotalC 	= precioC+(precioC*porcentaje3);
	
	$("#lblTotalPrecioA").html('$'+redondear(precioTotalA))
	$("#lblTotalPrecioB").html('$'+redondear(precioTotalB))
	$("#lblTotalPrecioC").html('$'+redondear(precioTotalC))
	
	$("#lblIncrementoA").html('$'+redondear(precioTotalA-precioA))
	$("#lblIncrementoB").html('$'+redondear(precioTotalB-precioB))
	$("#lblIncrementoC").html('$'+redondear(precioTotalC-precioC))
}

function asignarPorcentajes()
{
	mensaje="";
	
	url	=	base_url+'inventarioProductos/asignarPorcentajes'
	
	if(obtenerNumeros($("#txtPorcentaje1").val())==0 && obtenerNumeros($("#txtPorcentaje2").val())==0 && obtenerNumeros($("#txtPorcentaje3").val())==0 )
	{
		mensaje+='Asigne al menos un porcentaje <br />';
	}
	
	
	if($('#txtRegistroSucursales').val()=="1")
	{
		ban	= false;
		url	= base_url+'inventarioProductos/asignarPorcentajesSucursales'
		
		
		for(i=0;i<=obtenerNumeros($('#txtNumeroSucursales').val());i++)
		{
			if( $('#chkSucursal'+i).prop('checked') ) 
			{
				ban	= true;
				break;
			}
		}
		
		if(!ban)
		{
			mensaje+="Seleccione al menos una sucursal"
		}
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
			$('#registrandoPorcentaje').html('<img src="'+ img_loader +'"/>Se esta asignando el porcentaje, por favor espere...');
		},
		type:"POST",
		url:url,
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
					formularioPorcentaje($('#txtIdProducto').val())
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

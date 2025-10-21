//PARA LOS ENVÍOS DE LOS PRODUCTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	obtenerVentas();
	
	/*$("#ventanaVentas").dialog(
	{
		autoOpen:false,
		height:620,
		width:1024,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				registrarVenta();
			},
		},
		close: function() 
		{
			$('#formularioVentas').html('');
		}
	});*/
	
	//('.ajax-pagVentas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagVen > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerVentas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idTienda:	0,
				criterio:	$('#txtCriterio').val()
			},
			dataType:"html",
			beforeSend:function(){$('#obtenerEnvios').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de ventas');;},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
	
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerVentas();
		}, milisegundos);
	});
	
});

function obtenerVentas()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerVentas').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de ventas');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerVentas',
		data:
		{
			idTienda:	0,
			criterio:	$('#txtCriterio').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerVentas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de ventas',500,5000,'error',30,3);
			$("#obtenerVentas").html('');
		}
	});		
}


/*function formularioVentas()
{
	$("#ventanaVentas").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioVentas').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo los datos para registrar la venta');
		},
		type:"POST",
		url:base_url+'clientes/formularioVentas',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioVentas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la venta',500,5000,'error',30,3);
			$("#formularioVentas").html('');
		}
	});		
}*/




function registrarEnvio(idProducto)
{
	mensaje="";
	
	if($('#txtCantidadEnviar'+idProducto).val()=="" || Solo_Numerico($('#txtCantidadEnviar'+idProducto).val())=="" 
	|| $('#txtCantidadEnviar'+idProducto).val()=="0" || parseFloat($('#txtCantidadEnviar'+idProducto).val()) > parseFloat($('#txtStock'+idProducto).val()))
	{
		mensaje+=" La cantidad es incorrecta <br />";
	}
	
	if($('#selectTiendasEnvio').val()=="0")
	{
		mensaje+=" Seleccione la tienda";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el envío del producto?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoEnvio').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando el envío');
		},
		type:"POST",
		url:base_url+'tiendas/registrarEnvio',
		data:
		{
			idProducto:	idProducto,
			idTienda:	$('#selectTiendasEnvio').val(),
			cantidad:	$('#txtCantidadEnviar'+idProducto).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoEnvio').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
	
				case "1":
					notify('El envío se ha registrado correctamente',500,5000,'',30,5);
					obtenerProductosEnvio();
					obtenerEnvios();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el envío',500,5000,'error',30,5);
			$("#registrandoEnvio").html('');
		}
	});		
}


//PARA LAS LÍNEAS
$(document).ready(function()
{
	$("#ventanaLineas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
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
				agregarLinea();
			},
		},
		close: function() 
		{
			$("#formularioLineas").html('');
		}
	});
});

function formularioLineas()
{
	$("#ventanaLineas").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioLineas').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para líneas');
		},
		type:"POST",
		url:base_url+'produccion/formularioLineas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioLineas').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para líneas',500,5000,'error',2,5);
			$('#formularioLineas').html('')
		}
	});					  	  
}

function obtenerLineas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerLineas').html('<img src="'+ img_loader +'"/> Obteniendo la lista líneas');
		},
		type:"POST",
		url:base_url+'produccion/obtenerLineas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerLineas').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de líneas',500,5000,'error',2,5);
			$('#obtenerLineas').html('')
		}
	});					  	  
}

function agregarLinea()
{
	if($('#txtLinea').val()=="")
	{
		notify('El nombre de la línea es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#agregandoLinea').html('<img src="'+ img_loader +'"/> Agregando la línea...');
		},
		type:"POST",
		url:base_url+'produccion/agregarLinea',
		data:
		{
			nombre:$('#txtLinea').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoLinea').html('');
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					$("#ventanaLineas").dialog('close');
					notify(data[1],500,5000,'',30,5);
					obtenerLineas();
				break;
				
			}
		},
		error:function(datos)
		{
			notify('Error al agregar la línea',500,5000,'error',2,5);
			$('#agregandoLinea').html('')
		}
	});					  	  
}

function obtenerSubLineasCatalogo()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerSubLineas').html('<img src="'+ img_loader +'"/> Obteniendo la lista sublineas');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerSubLineasCatalogo',
		data:
		{
			idLinea:	$('#selectLineas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSubLineas').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de sublineas',500,5000,'error',2,5);
			$('#obtenerSubLineas').html('')
		}
	});					  	  
}

function obtenerLineasVentas()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerProductosVenta').html('<img src="'+ img_loader +'"/> Obteniendo la lista sublineas');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerLineasVentas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductosVenta').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de sublineas',500,5000,'error',2,5);
			$('#obtenerProductosVenta').html('')
		}
	});					  	  
}

function obtenerSubLineasVentas(idLinea)
{
	$('#selectLineas').val(idLinea)
	
	if(idLinea==0)
	{
		obtenerLineasVentas()
		obtenerSubLineasCatalogo()
		return;
	}
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#obtenerProductosVenta').html('<img src="'+ img_loader +'"/> Obteniendo la lista sublineas');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerSubLineasVentas',
		data:
		{
			idLinea:	idLinea
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductosVenta').html(data);
			obtenerSubLineasCatalogo()
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de sublineas',500,5000,'error',2,5);
			$('#obtenerProductosVenta').html('')
		}
	});					  	  
}
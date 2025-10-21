$(document).ready(function()
{
	$("#ventanaConversiones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:850,
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
				registrarConversion();
			},
		},
		close: function() 
		{
			$('#cargandoConversiones').html('');
		}
	});
});

function registrarConversion()
{
	var mensaje="";
	
	if($("#txtNombre").val()=="")
	{
		mensaje+="El nombre de la unidad no es correcto <br />";										
	}
	
	if($("#txtReferencia").val()=="")
	{
		mensaje+="La referencia de la conversión no es correcta <br />";										
	}
	
	if($("#txtValor").val()=="" || parseFloat($("#txtValor").val())<0 || isNaN($("#txtValor").val()))
	{
		mensaje+="El valor de la unidad no es correcto<br />";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoConversiones').html('<img src="'+ img_loader +'"/> Se esta registrando la conversión, por favor espere.');},
		type:"POST",
		url:base_url+"configuracion/registrarConversion",
		data:
		{
			"nombre":		$("#txtNombre").val(),
			"valor":		$("#txtValor").val(),
			"referencia":	$("#txtReferencia").val(),
			"idUnidad": 	$("#txtIdUnidad").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoConversiones').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				case "1":
					notify('La conversión se registro de manera correcta',500,5000,'',30,3);
					obtenerConversiones($("#txtIdUnidad").val());
				break;
			}
		},
		error:function(datos)
		{
			notify(mensaje,500,5000,'error',30,3);
			$('#cargandoConversiones').html('');	
		}
	});				  	  
}

function obtenerConversiones(idUnidad)
{
	$('#ventanaConversiones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarConversiones').html('<img src="'+ img_loader +'"/> Obteniendo las conversiones...');},
		type:"POST",
		url:base_url+'configuracion/obtenerConversiones',
		data:
		{
			"idUnidad":idUnidad
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarConversiones").html(data);
		},
		error:function(datos)
		{
			$("#cargarConversiones").html('Error al obtener las conversiones');
		}
	});//Ajax		
}

$(document).ready(function()
{
	$("#ventanaEditarConversion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:220,
		width:470,
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
				editarConversion();
			},
		},
		close: function() 
		{
			$('#obtenerConversion').html('');
		}
	});
});

function obtenerConversion(idConversion)
{
	$("#ventanaEditarConversion").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerConversion').html('<img src="'+ img_loader +'"/> Obteniendo detalles de conversión...');},
		type:"POST",
		url:base_url+'configuracion/obtenerConversion',
		data:
		{
			"idConversion":idConversion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerConversion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerConversion").html('');
			notify('Error al obtener los detalles de conversion',500,5000,'error',30,3);
		}
	});
}

function editarConversion()
{
	var mensaje="";
	
	if($("#txtConversionEditar").val()=="")
	{
		mensaje+="El nombre de la conversión no es correcta <br />";										
	}
	
	if($("#txtReferenciaEditar").val()=="")
	{
		mensaje+="La referencia de la conversión no es correcta <br />";										
	}
	
	if($("#txtValorEditar").val()=="" || parseFloat($("#txtValorEditar").val())<0 || isNaN($("#txtValorEditar").val()))
	{
		mensaje+="El valor de la unidad no es correcto<br />";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar la conversión?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoConversion').html('<img src="'+ img_loader +'"/> Se esta registrando la conversión, por favor tenga paciencia.');},
		type:"POST",
		url:base_url+"configuracion/editarConversion",
		data:
		{
			"nombre":		$("#txtConversionEditar").val(),
			"referencia":	$("#txtReferenciaEditar").val(),
			"valor":		$("#txtValorEditar").val(),
			"idConversion":	$("#txtIdConversion").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al editar la conversión',500,5000,'error',30,3);
				$('#editandoConversion').html('');
				break;
				case "1":
				notify('La conversión se edito de manera correcta',500,5000,'',30,3);
				$('#editandoConversion').html('');
				obtenerConversiones($("#txtIdUnidad").val());
				$("#ventanaEditarConversion").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la conversión',500,5000,'error',30,3);
			$('#editandoConversion').html('');	
		}
	});		
}

function borrarConversion(idConversion)
{
	
	if(!confirm('¿Realmente desea borrar la conversión?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoConversiones').html('<img src="'+ img_loader +'"/> Se esta borrando la conversión, por favor tenga paciencia.');},
		type:"POST",
		url:base_url+"configuracion/borrarConversion",
		data:
		{
			"idConversion":	idConversion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al borrar la conversión, esta asociada a productos o materiales',500,5000,'error',30,3);
				$('#cargandoConversiones').html('');
				break;
				case "1":
				notify('La conversión se borrado de manera correcta',500,5000,'',30,3);
				$('#cargandoConversiones').html('');
				obtenerConversiones($("#txtIdUnidad").val());
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la conversión',500,5000,'error',30,3);
			$('#cargandoConversiones').html('');	
		}
	});		
}

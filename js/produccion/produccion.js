var tabla=1;
$(document).ready(function()
{
	$("#txtBuscarProduccion").autocomplete(
	{
		source:base_url+'configuracion/obtenerInventarioProduccion',
		
		select:function( event, ui)
		{
			location.href=base_url+"produccion/index/"+ui.item.idProducto
		}
	});
});

function mostrar(img,num)
{
	if(img.src==base_url+"img/ocultar.png")
	{
		img.src=base_url+"img/mostrar.png"
		img.title="Mostrar detalles";
		return;
	}
	
	if(img.src==base_url+"img/mostrar.png")
	{
		img.src=base_url+"img/ocultar.png"
		img.title="Ocultar detalles";
		return;
	}
}

function busquedaFecha()
{
	if($('#FechaDia').val()=='')
	{
		notify('Por favor seleccione una fecha',500,4000,"error"); //Sistema de notificaciones

		return;
	}
			
	direccion="<?php echo base_url()?>produccion/busquedaCostoAdministrativo/"+$('#FechaDia').val();
	window.location.href=direccion;
}

function confirmarGasto()
{
	if(confirm('El cambio afectara sustancialmente los precios en lo productos ¿Realmente desea continuar?')==true)
	{
		direccion="<?php echo base_url()?>produccion/cambiarCostoGlobal";
		window.location.href=direccion;
	}
}


function mostrarCamposProducto()
{
	if(document.getElementById('chkMateriaPrima').checked==true)
	{
		$('#mostrarUnidades').fadeIn();
	}
	else
	{
		$('#mostrarUnidades').fadeOut();
	}
}

$(document).ready(function()
{
	$("#ventanaAgregarProduccion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:780,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				registrarProductoProduccion()
			},
			
		},
		close: function() 
		{
			$("#formularioProduccion").html('');
		}
	});
});

function formularioProduccion()
{
	$('#ventanaAgregarProduccion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProduccion').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de producción...');
		},
		type:"POST",
		url:base_url+'produccion/formularioProduccion',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProduccion').html(data);
			$('#txtNombreProducto').focus();
			obtenerLineas();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de producción',500,5000,'error',2,5);
			$('#formularioProduccion').html('')
		}
	});					  	  
}

function registrarProductoProduccion()
{
	var   mensaje="";
	var   materiaPrima="0";

	if($("#txtNombreProducto").val()=="")
	{
		mensaje+="Error en el nombre producto<br />"
	}
	
	if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}
	
	if($("#selectLineas").val()=="0")
	{
		mensaje+="Por favor seleccione la línea<br />"
	}
	
	if(document.getElementById('chkMateriaPrima').checked==true)
	{
		materiaPrima="1";
		
		if($("#selectUnidades").val()=="0")
		{
			mensaje+="Por favor seleccione una unidad<br />"
		}
	}
	else
	{
		materiaPrima="0";
		
		if(obtenerNumeros($('#txtPrecioA').val())==0)
		{
			mensaje+="El "+precioVentaA+" es incorrecto<br />"
		}
		
		/*if($("#utilidadB").val()=="" || isNaN($("#utilidadB").val()) || parseFloat($("#utilidadB").val())<1)
		{
			mensaje+="El "+precioVentaB+" es incorrecto<br />"
		}
		
		if($("#utilidadC").val()=="" || isNaN($("#utilidadC").val()) || parseFloat($("#utilidadC").val())<1)
		{
			mensaje+="El "+precioVentaC+" es incorrecto<br />"
		}
		
		if($("#utilidadD").val()=="" || isNaN($("#utilidadD").val()) || parseFloat($("#utilidadD").val())<1)
		{
			mensaje+="El "+precioVentaD+" es incorrecto<br />"
		}
		
		if($("#utilidadE").val()=="" || isNaN($("#utilidadE").val()) || parseFloat($("#utilidadE").val())<1)
		{
			mensaje+="El "+precioVentaE+" es incorrecto<br />"
		}*/
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5); //Sistema de notificaciones
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el producto?')) return
	
	var formData = new FormData($('#frmNuevoProducto')[0]);

	$.ajax(
	{
		beforeSend:function(objeto){$('#agregandoProductoProduccion').html('<img src="'+ img_loader +'"/> Registrando  el producto...');},
		url:base_url+'produccion/registrarProduccion',
		data:formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoProductoProduccion').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					location.reload(true);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,4000,"error",30,5);
			$('#agregandoProductoProduccion').html('')
		}
	});

	/*$('#agregandoProductoProduccion').html('<img src="'+ img_loader +'"/> Se esta registrando el producto, por favor espere...');
	document.forms['frmNuevoProducto'].submit();*/
}

function obtenerProducto(idProducto)
{
	$('#ventanaEditarProducto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerProducto').html('<img src="'+ img_loader +'"/> Obteniendo los detalles del producto...');},
		type:"POST",
		url:base_url+'produccion/obtenerProducto',
		data:
		{
			"idProducto":idProducto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProducto").html(data);
		},
		error:function(datos)
		{
			$("#obtenerProducto").html('Error');
			notify('Error al obtener el producto',500,5000,'error',30,1);
		}
	});				
}

$(document).ready(function()
{
	$("#ventanaEditarProducto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:780,
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
				editarProductoProduccion();
			},
			
		},
		close: function() 
		{
			$("#obtenerProducto").html('');
		}
	});
});

function editarProductoProduccion()
{
	var mensaje="";
	
	if(!camposVacios($("#txtNombreEditar").val()))
	{
		mensaje+="Error en el nombre producto<br />"
	}
	
	/*if($("#selectUnidades").val()=="0")
	{
		mensaje+="La unidad es incorrecta<br />";										
	}*/
	
	if($('#txtMateriaPrimaEditar').val()=="0")
	{
		if(obtenerNumeros($('#txtPrecioA').val())==0)
		{
			mensaje+="El "+precioVentaA+" es incorrecto<br />"
		}
		
		/*if($("#utilidadAEditar").val()=="" || isNaN($("#utilidadAEditar").val()) || parseFloat($("#utilidadAEditar").val())<1)
		{
			mensaje+="El "+precioVentaA+" es incorrecto<br />"
		}
		
		if($("#utilidadBEditar").val()=="" || isNaN($("#utilidadBEditar").val()) || parseFloat($("#utilidadBEditar").val())<1)
		{
			mensaje+="El "+precioVentaB+" es incorrecto<br />"
		}
		
		if($("#utilidadCEditar").val()=="" || isNaN($("#utilidadCEditar").val()) || parseFloat($("#utilidadCEditar").val())<1)
		{
			mensaje+="El "+precioVentaC+" es incorrecto<br />"
		}
		
		if($("#utilidadDEditar").val()=="" || isNaN($("#utilidadDEditar").val()) || parseFloat($("#utilidadDEditar").val())<1)
		{
			mensaje+="El "+precioVentaD+" es incorrecto<br />"
		}
		
		if($("#utilidadEEditar").val()=="" || isNaN($("#utilidadEEditar").val()) || parseFloat($("#utilidadEEditar").val())<1)
		{
			mensaje+="El "+precioVentaE+" es incorrecto<br />"
		}*/
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5); //Sistema de notificaciones
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del producto?')==false)
	{
		return;
	}
	
	$('#editandoProductoProduccion').html('<img src="'+ img_loader +'"/> Se esta editando el registro del producto, por favor espere...');
	document.forms['frmEditarProducto'].submit();
}






//PARA LAS LÍNEAS
/*$(document).ready(function()
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
		notify('El nombre de la línea es incorrecto',500,5000,'error',2,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
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
			$("#ventanaLineas").dialog('close');
			notify('La línea se ha registrado correctamente',500,5000,'',25,5);
			obtenerLineas();
		},
		error:function(datos)
		{
			notify('Error al agregar la línea',500,5000,'error',2,5);
			$('#agregandoLinea').html('')
		}
	});					  	  
}*/

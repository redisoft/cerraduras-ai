//HORARIOS
$(document).ready(function()
{
	$("#ventanaEfectivo").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:'auto',
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				if($('#txtModuloSie').val()=="efectivo")
				{
					editarEfectivo()
				}
				
				if($('#txtModuloSie').val()=="cuentas")
				{
					editarCuentas()
				}
				
				if($('#txtModuloSie').val()=="noDisponible")
				{
					editarNoDisponible()
				}
				
			}
		},
		close: function() 
		{
			$('#formularioEfectivo').html('');
		}
	});
});

function formularioEfectivo()
{
	$('#ventanaEfectivo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEfectivo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'administracion/formularioEfectivo',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEfectivo").html(data);
		},
		error:function(datos)
		{
			notify('Error',500,5000,'error',30,3);
			$("#formularioEfectivo").html('');
		}
	});		
}

function editarEfectivo()
{
	if(obtenerNumeros($('#txtEfectivo').val())<0)
	{
		notify('El efectivo es incorrecto',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEfectivo').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/editarEfectivo',
		data:
		{
			"efectivo":$('#txtEfectivo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEfectivo').html('')
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro se ha editado correctamente!',500,5000,'',0,0);
					$('#ventanaEfectivo').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoEfectivo').html('');
			notify('¡Error al editar el registro!',500,5000,'error',0,0);
		}
	});		
}

//EDITAR LAS CUENTAS
function formularioCuentas()
{
	$('#ventanaEfectivo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEfectivo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'administracion/formularioCuentas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEfectivo").html(data);
		},
		error:function(datos)
		{
			notify('Error',500,5000,'error',30,3);
			$("#formularioEfectivo").html('');
		}
	});		
}

function editarCuentas()
{
	/*if(obtenerNumeros($('#txtCuentas').val())<0)
	{
		notify('La cantidad es incorrecta',500,5000,'error',30,5);
		return;
	}*/
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEfectivo').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/editarCuentas',
		data:$('#frmCuentas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEfectivo').html('')
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro se ha editado correctamente!',500,5000,'',0,0);
					$('#ventanaEfectivo').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoEfectivo').html('');
			notify('¡Error al editar el registro!',500,5000,'error',0,0);
		}
	});		
}

//EDITAR NO DISPONIBLE
function formularioNoDisponible()
{
	$('#ventanaEfectivo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEfectivo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'administracion/formularioNoDisponible',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEfectivo").html(data);
		},
		error:function(datos)
		{
			notify('Error',500,5000,'error',30,3);
			$("#formularioEfectivo").html('');
		}
	});		
}

function editarNoDisponible()
{
	if(obtenerNumeros($('#txtPayu').val())<0)
	{
		notify('La cantidad para payu es incorrecta',500,5000,'error',30,5);
		return;
	}
	
	if(obtenerNumeros($('#txtPaypal').val())<0)
	{
		notify('La cantidad para paypal es incorrecta',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEfectivo').html('<img src="'+ img_loader +'"/> Editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/editarNoDisponible',
		data:
		{
			"payu":		$('#txtPayu').val(),
			"paypal":	$('#txtPaypal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEfectivo').html('')
			
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El registro se ha editado correctamente!',500,5000,'',0,0);
					$('#ventanaEfectivo').dialog('close');
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoEfectivo').html('');
			notify('¡Error al editar el registro!',500,5000,'error',0,0);
		}
	});		
}

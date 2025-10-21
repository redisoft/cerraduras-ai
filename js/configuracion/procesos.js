function formularioProcesos()
{
	$('#ventanaAgregarProceso').dialog('open');
}

$(document).ready(function()
{
	$("#ventanaAgregarProceso").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:580,
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
				registrarProceso();	  	  
			},
			
		},
		close: function() 
		{
			$("#registrandoProceso").html('');
		}
	});
	
	$("#ventanaEditarProceso").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:580,
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
				editarProceso();	  	  
			},
		},
		close: function() 
		{
			$("#obtenerProceso").html('');
		}
	});
	
});

function editarProceso()
{
	var mensaje="";

	if($("#txtNombreProcesoEditar").val()=="")
	{
		mensaje+="El nombre del proceso es incorrecto <br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar el proceso de producción?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoProceso').html('<img src="'+ img_loader +'"/>Se esta editando el proceso de producción, por favor espere...');},
		type:"POST",
		url:base_url+"configuracion/editarProceso",
		data:
		{
			"nombre":	$("#txtNombreProcesoEditar").val(),
			"idProceso":$("#txtIdProceso").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#editandoProceso").html('');
				notify('Error al editar el proceso o no hubo cambios en el registro',500,5000,'error',30,3);
				break;
				case "1":
				window.location.href=base_url+"configuracion/procesos";
				break;
			}
		},
		error:function(datos)
		{
			$("#editandoProceso").html('');
			notify('Error al editar el proceso de producción',500,5000,'error',30,3);
		}
	});		
}

function obtenerProceso(idProceso)
{
	$('#ventanaEditarProceso').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerProceso').html('<img src="'+ img_loader +'"/>Obteniendo detalles del proceso...');},
		type:"POST",
		url:base_url+'configuracion/obtenerProceso',
		data:
		{
			"idProceso": idProceso,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProceso").html(data);
		},
		error:function(datos)
		{
			$("#obtenerProceso").html('');
			notify('Error al obtener los detalles del proceso',500,5000,'error',30,3);
		}
	});				  	  
}

function registrarProceso()
{
	var mensaje="";

	if($("#txtNombreProceso").val()=="")
	{
		mensaje+="El nombre del proceso es incorrecto <br />";										
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoProceso').html('<img src="'+ img_loader +'"/>Se esta registrando el proceso de producción, por favor espere...');},
		type:"POST",
		url:base_url+"configuracion/agregarProceso",
		data:
		{
			"nombre":$("#txtNombreProceso").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoProceso").html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					window.location.href=base_url+"configuracion/procesos";
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoProceso").html('');
			notify('Error al registrar el proceso de producción',500,5000,'error',30,3);
		}
	});		
}
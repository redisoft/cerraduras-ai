//DIRECCIONES
//=========================================================================================================================================//
function obtenerDirecciones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDirecciones').html('<img src="'+ img_loader +'"/> Obteniendo la lista de registros.');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDirecciones',
		data:
		{
			idCliente: $('#txtIClienteDirecciones').val()
		},
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$("#obtenerDirecciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de registros',500,5000,'error',30,3);
			$("#obtenerDirecciones").html('');
		}
	});
}

function formularioDirecciones()
{
	$('#ventanaDirecciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDirecciones').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar..');
		},
		type:"POST",
		url:base_url+'clientes/formularioDirecciones',
		data:
		{

		},
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$("#formularioDirecciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar',500,5000,'error',30,3);
			$("#formularioDirecciones").html('');
		}
	});
}

function registrarDirecciones()
{
	mensaje="";
	
	if(!camposVacios($('#txtEmpresa').val()))
	{
		mensaje+="La empresa es requerida<br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoDirecciones').html('<img src="'+ img_loader +'"/> Registrando, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'clientes/registrarDirecciones',
		data:$('#frmDireccion').serialize()+'&idCliente='+$('#txtIClienteDirecciones').val(),
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$('#registrandoDirecciones').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					obtenerDirecciones();
					$('#ventanaDirecciones').dialog('close');
					notify(data[1],500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar',500,5000,'error',30,3);
			$("#registrandoDirecciones").html('');
		}
	});		
}

$(document).ready(function()
{
	obtenerDirecciones()
	
	$("#ventanaDirecciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:700,
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
				registrarDirecciones();
			},
		},
		close: function() 
		{
			$("#formularioDirecciones").html(''); 
		}
	});

	$("#ventanaEditarDirecciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:580,
		width:700,
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
				editarDirecciones();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerDireccionesEditar').html('');
		}
	});
});

function obtenerDireccionesEditar(idDireccion)
{
	$('#ventanaEditarDirecciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el registro..');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDireccionesEditar',
		data:
		{
			idDireccion:idDireccion
		},
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$("#obtenerDireccionesEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el registro',500,5000,'error',30,3);
			$("#obtenerDireccionesEditar").html('');
		}
	});
}

function editarDirecciones()
{
	mensaje="";
	
	if(!camposVacios($('#txtEmpresa').val()))
	{
		mensaje+="La empresa es requerida<br />";
	}
	
	if(!confirm('¿Realmente desea editar el registro ?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDirecciones').html('<img src="'+ img_loader +'"/> Editando el registro, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'clientes/editarDirecciones',
		data:$('#frmDireccion').serialize(),
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$('#editandoDirecciones').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('El registro no tuvo cambios',500,5000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarDirecciones').dialog('close');
					notify('El registro se ha editado correctamente',500,5000,'',30,5);
					obtenerDirecciones();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoDirecciones").html('');
		}
	});		
}

function borrarDirecciones(idDireccion)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoDirecciones').html('<img src="'+ img_loader +'"/> Borrando el registro..');
		},
		type:"POST",
		url:base_url+'clientes/borrarDirecciones',
		data:
		{
			idDireccion: 	idDireccion,
		},
		datatype:"html",
		success:function(data, textDirecciones)
		{
			$('#procesandoDirecciones').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaDirecciones'+idDireccion).remove();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#procesandoDirecciones").html('');
		}
	});		
}
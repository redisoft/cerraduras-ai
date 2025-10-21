//PLANTILLAS
$(document).ready(function()
{
	$("#ventanaPlantillas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:1000,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function()
		{
			$("#obtenerPlantillas").html('');
		}
	});
});

function obtenerPlantillas()
{
	$('#ventanaPlantillas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPlantillas').html('<img src="'+ img_loader +'"/> Obteniendo plantillas, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerPlantillas',
		data:
		{
			tipoPlantilla:	$('#txtTipoPlantilla').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPlantillas').html(data);
		},
		error:function(datos)
		{
			$('#obtenerPlantillas').html('');
			notify('Error al obtener los registros',500,5000,'error',0,0);
		}
	});		
}

function borrarPlantilla(idPlantilla)
{
	if(!confirm('¿Realmente desea borrar la plantilla?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPlantillas').html('<img src="'+ img_loader +'"/> Borrando plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/borrarPlantilla',
		data:
		{
			"idPlantilla":idPlantilla,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPlantillas').html('')
			
			switch(data)
			{
				case "0":
				
					notify('¡Error al borrar la plantilla!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('¡El fichero se ha borrado correctamente!',500,5000,'',0,0);
					obtenerPlantillas();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoPlantillas').html('');
			notify('¡Error al borrar la plantilla!',500,5000,'error',0,0);
		}
	});		
}

//PLANTILLA
$(document).ready(function()
{
	$("#ventanaPlantilla").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:1100,
		modal:true,
		resizable:false,
		
		/*buttons: 
		{
			'Editar': function() 
			{
				obtenerPlantillaEditar();
			},
			'Aceptar': function() 
			{
				$(this).dialog('close');				 
			},
		},*/
		
		
		buttons: 
		[
			{
				id: "btnObtenerPlantilla",
				text: "Editar",
				click: function() 
				{
					obtenerPlantillaEditar();
				}
			},
			{
				id: "btnEditarPlantilla",
				text: "Aceptar",
				click: function() 
				{
					if(obtenerNumeros($('#txtGuardarPlantilla').val())==0)
					{
						$(this).dialog('close');				
					}
					else
					{
						
						editarPlantillaDetalle();
					}
						
					 
				}
			}
		],

		close: function()
		{
			$("#obtenerPlantilla").html('');
			
			$("#btnObtenerPlantilla").button("enable");
			$("#btnEditarPlantilla").html('<span class="ui-button-text">Aceptar</span>')
		}
	});
});

function editarPlantillaDetalle()
{
	if(!confirm('¿Realmente desea editar la plantilla?'))return;
	
	editorId.post();
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPlantilla').html('<img src="'+ img_loader +'"/> Editando plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/editarPlantilla',
		data:
		{
			idPlantilla:	$('#txtIdPlantilla').val(),
			html:			$('#input').val(),
			archivo:		$('#txtArchivoPlantilla').val(),
			extension:		$('#txtExtension').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoPlantilla').html('')
			
			switch(data)
			{
				case "0":
				
					notify('¡Error al editar la plantilla!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('¡La plantilla se ha editado correctamente!',500,5000,'',30,5);
					$("#btnObtenerPlantilla").button("enable");
					$("#btnEditarPlantilla").html('<span class="ui-button-text">Aceptar</span>')
					obtenerPlantilla($('#txtIdPlantilla').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoPlantilla').html('');
			notify('¡Error al editar la plantilla!',500,5000,'error',30,5);
		}
	});		
}

function obtenerPlantilla(idPlantilla)
{
	$('#ventanaPlantilla').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPlantilla').html('<img src="'+ img_loader +'"/> Obteniendo plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerPlantilla',
		data:
		{
			idPlantilla:idPlantilla
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPlantilla').html(data);
		},
		error:function(datos)
		{
			$('#obtenerPlantilla').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function obtenerPlantillaEditar()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPlantilla').html('<img src="'+ img_loader +'"/> Obteniendo plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/obtenerPlantillaEditar',
		data:
		{
			idPlantilla:$('#txtIdPlantilla').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPlantilla').html(data);
			$("#btnObtenerPlantilla").button("disable");
			$("#btnEditarPlantilla").html('<span class="ui-button-text">Guardar</span>')
		},
		error:function(datos)
		{
			$('#obtenerPlantilla').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function editarPromotorPlantilla(idPlantilla)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoPlantillas').html('<img src="'+ img_loader +'"/> Borrando plantilla, por favor espere...');
		},
		type:"POST",
		url:base_url+'crm/editarPromotorPlantilla',
		data:
		{
			"idPlantilla":		idPlantilla,
			"idUsuario":		$('#selectPromotorPlantilla'+idPlantilla).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPlantillas').html('')
			
			switch(data)
			{
				case "0":
					notify('Sin cambios en el registro',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoPlantillas').html('');
			notify('¡Error al editar la plantilla!',500,5000,'error',30,5);
		}
	});		
}
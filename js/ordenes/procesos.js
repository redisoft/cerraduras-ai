//--------------------------------------------------------------------------------------
//ADMINISTRACIÓN DE PROCESOS
//--------------------------------------------------------------------------------------
function formularioAgregarProceso(idOrden)
{
	$('#ventanaAgregarProceso').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarProceso').html('<img src="'+ img_loader +'"/>Obteniendo detalles de formulario...');
		},
		type:"POST",
		url:base_url+'ordenes/formularioAgregarProceso',
		data:
		{
			idOrden:		idOrden,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarProceso').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',2,5);
			$("#formularioAgregarProceso").html('');
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarProceso").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:800,
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
				agregarProcesoOrden();  	  
			},
			
		},
		close: function() 
		{
			$('#formularioAgregarProceso').html('');	
		}
	});
});

function agregarProcesoOrden()
{
	mensaje		="";

	if($("#selectProcesos").val()=="0" || !comprobarNumeros($("#selectProcesos").val()))
	{
		mensaje+="Por favor seleccione un proceso <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea agregar el proceso?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoProceso').html('<img src="'+ img_loader +'"/> Agregando el proceso...');},
		type:"POST",
		url:base_url+'ordenes/agregarProcesoOrden',
		data:
		{
			"idProceso":	$("#selectProcesos").val(),
			"idOrden":		$("#txtIdOrden").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoProceso').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);							
				break;

				case "1":
					$('#ventanaAgregarProceso').dialog('close');
					notify('El proceso se ha agregado correctamente',500,5000,'',30,5);	
					obtenerOrdenes();
				break; 
			}
		},
		error:function(datos)
		{
			notify('Error al agregar el proceso',500,5000,'error',0,0);							
			$('#agregandoProceso').html('');
		}
	});			
}

function borrarProductoTerminado(idDetalle)
{
	if(!confirm('¿Realmente desea borrar el producto terminado?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoProducido').html('<img src="'+ img_loader +'"/> Borrando producto terminado...');},
		type:"POST",
		url:base_url+'ordenes/borrarProductoTerminado',
		data:
		{
			"idDetalle":	idDetalle,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoProducido').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);							
				break;

				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					
					obtenerProducido($('#txtIdOrden').val(),$('#txtIdRelacion').val(),$('#txtIdDetalle').val());
					obtenerOrdenes();
				break; 
				
				case "2":
					notify('No puede borrarse el registro ya que el inventario es menor',500,5000,'error',30,5);	
				break; 
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el producto terminado',500,5000,'error',0,0);							
			$('#cargandoProducido').html('');
		}
	});			
}



//--------------------------------------------------------------------------------------
//EDITAR PRODUCTO TERMINADO
//--------------------------------------------------------------------------------------
function obtenerProductoTerminado(idDetalle)
{
	$('#ventanaEditarProducido').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProductoTerminado').html('<img src="'+ img_loader +'"/>Obteniendo detalles de producto...');
		},
		type:"POST",
		url:base_url+'ordenes/obtenerProductoTerminado',
		data:
		{
			idDetalle:		idDetalle,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductoTerminado').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el producto',500,5000,'error',2,5);
			$("#obtenerProductoTerminado").html('');
		}
	});
}

$(document).ready(function()
{
	$("#ventanaEditarProducido").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:280,
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
				editarProductoTerminado();  	  
			},
			
		},
		close: function() 
		{
			$('#obtenerProductoTerminado').html('');	
		}
	});
});

function editarProductoTerminado()
{
	var mensaje		= "";

	if(!camposVacios($("#txtSupervisoEditar").val()))
	{
		mensaje+="Error en la persona que superviso <br />";										
	}
	
	if (!comprobarNumeros($('#txtCantidadProducidoEditar').val()) || parseFloat($('#txtCantidadProducidoEditar').val())<1) 
	{
		mensaje+="La cantidad es incorrecta ";										
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
		beforeSend:function(objeto){$('#editandoProducido').html('<img src="'+ img_loader +'"/>Editando el producto terminado, por favor espere...');},
		type:"POST",
		url:base_url+"ordenes/editarProductoTerminado",
		data:
		{
			"idDetalleProductoTerminado":		$("#txtIdDetalleProductoTerminado").val(),
			
			"idOrden":		$("#txtIdOrden").val(),
			//"idDetalle":	$("#txtIdDetalle").val(),
			"idRelacion":	$("#txtIdRelacion").val(),
			"fecha":		$("#txtFechaProducidoEditar").val(),
			"idProducto":	$("#txtIdProducto").val(),
			"superviso":	$("#txtSupervisoEditar").val(),
			"materiaPrima":	$("#txtMateriaPrima").val(),
			"cantidad":		$("#txtCantidadProducidoEditar").val(),
			"idPersonal":	$("#selectPersonalEditar").val(),
			"fechaCaducidad":	$("#txtFechaCaducidadEditar").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoProducido').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,4000,"error",30,5); //Sistema de notificaciones
				break;

				case "1":
					notify('El registro ha sido exitoso',500,4000,"",30,5);
					obtenerProducido($("#txtIdOrden").val(),$("#txtIdRelacion").val(),$("#txtIdDetalle").val());
					obtenerOrdenes();
					$('#ventanaEditarProducido').dialog('close');
				break;
				
				case "2":
					notify('No puede producir mas producto del registrado, esta superando la cantidad del proceso',500,4000,"error",30,5);
				break;
				
				case "3":
					notify('El proceso anterior no cuenta con unidades para registrar el producto terminado',500,4000,"error",30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoProducido').html('');
			notify('Error al producir el producto ',500,4000,"error",30,5);
		}
	});					  	  
}


function borrarProcesoOrden(idRelacion)
{
	if(!confirm('¿Realmente desea borrar el proceso de la orden?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoOrden').html('<img src="'+ img_loader +'"/> Borrando el proceso...');},
		type:"POST",
		url:base_url+'ordenes/borrarProcesoOrden',
		data:
		{
			"idRelacion":	idRelacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoOrden').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el proceso, esta asociado a otros procesos o terminados',500,5000,'error',30,5);							
				break;
				case "1":
					//$('#filaProceso'+idRelacion).remove();
					obtenerOrdenes();
					notify('El proceso se ha borrado correctamente',500,5000,'',30,5);	
				break; 
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el proceso',500,5000,'error',30,5);							
			$('#procesandoOrden').html('');
		}
	});			
}
//SERVICIOS
//=========================================================================================================================================//
function obtenerCausas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCausas').html('<img src="'+ img_loader +'"/> Obteniendo los registros.');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCausas',
		data:
		{
			criterio: 	$('#txtBuscarCausa').val(),
			idUsuario: 	$('#selectCausasBusqueda').val(),
			idCampana: 	$('#selectCampanasBusqueda').val(),
			"tipo":			$("#txtTipoBajas").val()
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$("#obtenerCausas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de Causas',500,5000,'error',30,3);
			$("#obtenerCausas").html('');
		}
	});
}

function formularioCausas()
{
	$('#ventanaCausas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCausas').html('<img src="'+ img_loader +'"/> Obteniendo los datos el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioCausas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$("#formularioCausas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para el registro',500,5000,'error',30,3);
			$("#formularioCausas").html('');
		}
	});
}

function registrarCausas()
{
	mensaje="";
	
	if(!camposVacios($('#txtCausa').val()))
	{
		mensaje+="El nombre del causa es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoCausas').html('<img src="'+ img_loader +'"/> Registrando, por favor espere..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarCausas',
		data:
		{
			nombre: 		$('#txtCausa').val(),
			"tipo":			$("#txtTipoBajas").val()
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#registrandoCausas').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,3000,'error',30,5);
				break;
				
				case "1":
					obtenerCausas();
					$('#ventanaCausas').dialog('close');
					notify(data[1],500,3000,'',30,5);
					$('#txtCausasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el causa',500,5000,'error',30,3);
			$("#registrandoCausas").html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaCausas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:170,
		width:650,
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
				registrarCausas();
			},
		},
		close: function() 
		{
			$("#formularioCausas").html(''); 
		}
	});

	$("#ventanaEditarCausas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:170,
		width:650,
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
				editarCausas();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerCausasEditar').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagCausas > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerCausas";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":		$('#txtBuscarCausa').val(),
				"tipo":			$("#txtTipoBajas").val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerCausas').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
			},
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
});

function obtenerCausasEditar(idCausa)
{
	$('#ventanaEditarCausas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerServicio').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar el causa..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerCausasEditar',
		data:
		{
			idCausa:idCausa
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$("#obtenerCausasEditar").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar el causa',500,5000,'error',30,3);
			$("#obtenerCausasEditar").html('');
		}
	});
}

function editarCausas()
{
	mensaje="";
	
	if(!camposVacios($('#txtCausa').val()))
	{
		mensaje+="El nombre del causa es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,3000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registo?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCausas').html('<img src="'+ img_loader +'"/> Editando el causa, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarCausas',
		data:
		{
			nombre: 				$('#txtCausa').val(),
			idCausa: 				$('#txtIdCausa').val(),
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#editandoCausas').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no tuvo cambios',500,3000,'error',30,5);
				
				break;
				
				case "1":
					$('#ventanaEditarCausas').dialog('close');
					notify('El Causa se ha editado correctamente',500,3000,'',30,5);
					obtenerCausas();
					$('#txtCausasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el causa',500,5000,'error',30,3);
			$("#editandoCausas").html('');
		}
	});		
}

function borrarCausas(idCausa)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCausas').html('<img src="'+ img_loader +'"/> Borrando el registro..');
		},
		type:"POST",
		url:base_url+'configuracion/borrarCausas',
		data:
		{
			idCausa: 	idCausa,
		},
		datatype:"html",
		success:function(data, textCausas)
		{
			$('#procesandoCausas').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaCausas'+idCausa).remove();
					$('#txtCausasEditado').val('1')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#procesandoCausas").html('');
		}
	});		
}
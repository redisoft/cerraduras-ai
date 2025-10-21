//PARA LAS PERCEPCIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaRegistrarPercepcion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:700,
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
				registrarPercepcion();
			},
		},
		close: function() 
		{
			$('#formularioPercepciones').html('');
		}
	});
	
	$("#ventanaEditarPercepcion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Editar': function() 
			{
				editarPercepcion();
			},
		},
		close: function() 
		{
			$('#obtenerPercepcion').html('');
		}
	});
	
	//$('.ajax-pagPercepciones > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagPercepciones > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerPercepciones";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarPercepcion').val(),
				agregar:	$('#txtAgregarPercepciones').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo la lista de percepciones'+esperar);},
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

function obtenerPercepciones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPercepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de percepciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerPercepciones',
		data:
		{
			criterio:	$('#txtBuscarPercepcion').val(),
			agregar:	$('#txtAgregarPercepciones').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerPercepciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de percepciones'+conexion,500,5000,'error',30,3);
			$("#obtenerPercepciones").html('');
		}
	});		
}

function formularioPercepciones()
{
	$("#ventanaRegistrarPercepcion").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPercepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para percepciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioPercepciones',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioPercepciones").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para percepciones'+conexion,500,5000,'error',30,3);
			$("#formularioPercepciones").html('');
		}
	});		
}

function obtenerPercepcion(idCatalogoPercepcion)
{
	$("#ventanaEditarPercepcion").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPercepcion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la percepción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerPercepcion',
		data:
		{
			idCatalogoPercepcion:idCatalogoPercepcion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerPercepcion").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la percepción'+conexion,500,5000,'error',30,3);
			$("#obtenerPercepcion").html('');
		}
	});		
}

function editarPercepcion()
{
	mensaje		=	"";
	
	if(!camposVacios($('#txtClave').val()) || !longitudCadena($('#txtClave').val(),3))
	{
		mensaje+='La clave es incorrecta <br />';
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+='El concepto es incorrecto <br />';
	}
	
	if(!comprobarNumeros($('#txtImporteGravado').val()))
	{
		mensaje+='El importe gravado es incorrecto <br />';
	}
	
	if(!comprobarNumeros($('#txtImporteExento').val()))
	{
		mensaje+='El importe exento es incorrecto <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la percepción?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoPercepcion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando percepción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/editarPercepcion',
		data:$('#frmPercepciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoPercepcion').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar la percepción o no hubo cambios en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
				notify('La percepción se ha editado correctamente',500,5000,'',30,3);
				obtenerPercepciones();
				$("#ventanaEditarPercepcion").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la percepción',500,5000,'error',30,3);
			$("#editandoPercepcion").html('');
		}
	});		
}

function registrarPercepcion()
{
	mensaje		=	"";
	
	if(!camposVacios($('#txtClave').val()) || !longitudCadena($('#txtClave').val(),3))
	{
		mensaje+='La clave es incorrecta <br />';
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		mensaje+='El concepto es incorrecto <br />';
	}
	
	if(!comprobarNumeros($('#txtImporteGravado').val()))
	{
		mensaje+='El importe gravado es incorrecto <br />';
	}
	
	if(!comprobarNumeros($('#txtImporteExento').val()))
	{
		mensaje+='El importe exento es incorrecto <br />';
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
			$('#registrandoPercepcion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando percepción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/registrarPercepcion',
		data:$('#frmPercepciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPercepcion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
	
				case "1":
					notify('La percepción se ha registrado correctamente',500,5000,'',30,5);
					obtenerPercepciones();
					$("#ventanaRegistrarPercepcion").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la percepción',500,5000,'error',30,5);
			$("#registrandoPercepcion").html('');
		}
	});		
}

function borrarPercepcion(idCatalogoPercepcion)
{
	if(!confirm('¿Realmente desea borrar el registro de la percepción?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando la percepción'+esperar);
		},
		type:"POST",
		url:base_url+"nomina/borrarPercepcion",
		data:
		{
			"idCatalogoPercepcion":		idCatalogoPercepcion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar la percepción',500,5000,'error',30,3);
				break;
				
				case "1":
				obtenerPercepciones();
				notify('La percepción se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la percepción',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
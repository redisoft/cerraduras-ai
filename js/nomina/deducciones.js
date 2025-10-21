//PARA LAS PERCEPCIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#ventanaRegistrarDeduccion").dialog(
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
				registrarDeduccion();
			},
		},
		close: function() 
		{
			$('#formularioDeducciones').html('');
		}
	});
	
	$("#ventanaEditarDeduccion").dialog(
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
				editarDeduccion();
			},
		},
		close: function() 
		{
			$('#obtenerDeduccion').html('');
		}
	});
	
	//$('.ajax-pagDeducciones > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagDeducciones > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerDeducciones";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarDeduccion').val(),
				agregar:	$('#txtAgregarDeducciones').val()
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo la lista de deducciones'+esperar);},
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

function obtenerDeducciones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDeducciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la lista de deducciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerDeducciones',
		data:
		{
			criterio:	$('#txtBuscarDeduccion').val(),
			agregar:	$('#txtAgregarDeducciones').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDeducciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de deducciones'+conexion,500,5000,'error',30,3);
			$("#obtenerDeducciones").html('');
		}
	});		
}

function formularioDeducciones()
{
	$("#ventanaRegistrarDeduccion").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioDeducciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para deducciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioDeducciones',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioDeducciones").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para deducciones'+conexion,500,5000,'error',30,3);
			$("#formularioDeducciones").html('');
		}
	});		
}

function obtenerDeduccion(idCatalogoDeduccion)
{
	$("#ventanaEditarDeduccion").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDeduccion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la deducción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerDeduccion',
		data:
		{
			idCatalogoDeduccion:idCatalogoDeduccion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDeduccion").html(data);
			$('#txtClave').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la deducción'+conexion,500,5000,'error',30,3);
			$("#obtenerDeduccion").html('');
		}
	});		
}

function editarDeduccion()
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
	
	if(!confirm('¿Realmente desea editar el registro de la deducción?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoDeduccion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando deducción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/editarDeduccion',
		data:$('#frmDeducciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoDeduccion').html('');
			
			switch(data)
			{
				case "0":
				notify('Error al editar la deducción o no hubo cambios en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
				notify('La deducción se ha editado correctamente',500,5000,'',30,3);
				obtenerDeducciones();
				$("#ventanaEditarDeduccion").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la deducción',500,5000,'error',30,3);
			$("#editandoDeduccion").html('');
		}
	});		
}

function registrarDeduccion()
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
			$('#registrandoDeduccion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando deducción'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/registrarDeduccion',
		data:$('#frmDeducciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoDeduccion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
	
				case "1":
					notify('La deducción se ha registrado correctamente',500,5000,'',30,5);
					obtenerDeducciones();
					$("#ventanaRegistrarDeduccion").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la deducción',500,5000,'error',30,5);
			$("#registrandoDeduccion").html('');
		}
	});		
}

function borrarDeduccion(idCatalogoDeduccion)
{
	if(!confirm('¿Realmente desea borrar el registro de la deducción?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando la deducción'+esperar);
		},
		type:"POST",
		url:base_url+"nomina/borrarDeduccion",
		data:
		{
			"idCatalogoDeduccion":		idCatalogoDeduccion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar la deducción',500,5000,'error',30,3);
				break;
				
				case "1":
				obtenerDeducciones();
				notify('La deducción se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la deducción',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}
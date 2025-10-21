//PARA NIVEL 1
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtBuscarNivel2').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerNiveles2();
		}
	});
	
	$("#ventanaRegistrarNivel2").dialog(
	{
		autoOpen:false,
		height:200,
		width:500,
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
				registrarNivel2();
			},
		},
		close: function() 
		{
			$('#formularioNivel2s').html('');
		}
	});
	
	$("#ventanaEditarNivel2").dialog(
	{
		autoOpen:false,
		height:200,
		width:500,
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
				editarNivel2();
			},
		},
		close: function() 
		{
			$('#obtenerNivel2').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagNivel2 > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerNiveles2";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarNivel2').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
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

function obtenerNiveles2()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNiveles2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNiveles2',
		data:
		{
			criterio:	$('#txtBuscarNivel2').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNiveles2").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerNiveles2").html('');
		}
	});		
}

function formularioNiveles2()
{
	$("#ventanaRegistrarNivel2").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNiveles2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'catalogos/formularioNiveles2',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioNiveles2").html(data);
			$('#txtNivel2').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioNiveles2").html('');
		}
	});		
}


function registrarNivel2()
{
	if($('#txtNivel2').val()=="")
	{
		notify('El nombre es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if($('#selectNiveles1Registro').val()=="0")
	{
		notify('Seleccione el nivel 1',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoNivel2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'catalogos/registrarNivel2',
		data:
		{
			nombre:		$('#txtNivel2').val(),
			idNivel1:	$('#selectNiveles1Registro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoNivel2').html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerNiveles2();
					$("#ventanaRegistrarNivel2").dialog('close');
					$("#txtRegistrosAfectados2").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoNivel2").html('');
		}
	});		
}

function obtenerNivel2(idNivel2)
{
	$("#ventanaEditarNivel2").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la variable');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNivel2',
		data:
		{
			idNivel2:idNivel2
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNivel2").html(data);
			$('#txtNivel2').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la variable',500,5000,'error',30,3);
			$("#obtenerNivel2").html('');
		}
	});		
}

function editarNivel2()
{
	if($('#txtNivel2').val()=="")
	{
		notify('El nombre es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoNivel2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando la variable');
		},
		type:"POST",
		url:base_url+'catalogos/editarNivel2',
		data:
		{
			nombre:		$('#txtNivel2').val(),
			idNivel2:	$('#txtIdNivel2').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel2').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,3);
					obtenerNiveles2();
					$("#ventanaEditarNivel2").dialog('close');
					$("#txtRegistrosAfectados2").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoNivel2").html('');
		}
	});		
}

function borrarNivel2(idNivel2)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel2').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"catalogos/borrarNivel2",
		data:
		{
			"idNivel2":	idNivel2,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel2').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerNiveles2();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
					$("#txtRegistrosAfectados2").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoNivel2').html('');
		}
	});				  	  
}
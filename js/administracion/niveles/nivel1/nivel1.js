//PARA NIVEL 1
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtBuscarNivel1').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerNiveles1();
		}
	});
	
	$("#ventanaRegistrarNivel1").dialog(
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
				registrarNivel1();
			},
		},
		close: function() 
		{
			$('#formularioNivel1s').html('');
		}
	});
	
	$("#ventanaEditarNivel1").dialog(
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
				editarNivel1();
			},
		},
		close: function() 
		{
			$('#obtenerNivel1').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagNivel1 > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerNiveles1";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarNivel1').val(),
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

function obtenerNiveles1()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNiveles1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNiveles1',
		data:
		{
			criterio:	$('#txtBuscarNivel1').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNiveles1").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerNiveles1").html('');
		}
	});		
}

function formularioNiveles1()
{
	$("#ventanaRegistrarNivel1").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNiveles1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'catalogos/formularioNiveles1',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioNiveles1").html(data);
			$('#txtNivel1').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioNiveles1").html('');
		}
	});		
}


function registrarNivel1()
{
	if($('#txtNivel1').val()=="")
	{
		notify('El nombre es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoNivel1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'catalogos/registrarNivel1',
		data:
		{
			nombre:	$('#txtNivel1').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoNivel1').html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerNiveles1();
					$("#ventanaRegistrarNivel1").dialog('close');
					$("#txtRegistrosAfectados1").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoNivel1").html('');
		}
	});		
}

function obtenerNivel1(idNivel1)
{
	$("#ventanaEditarNivel1").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la variable');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNivel1',
		data:
		{
			idNivel1:idNivel1
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNivel1").html(data);
			$('#txtNivel1').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la variable',500,5000,'error',30,3);
			$("#obtenerNivel1").html('');
		}
	});		
}

function editarNivel1()
{
	if($('#txtNivel1').val()=="")
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
			$('#editandoNivel1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando la variable');
		},
		type:"POST",
		url:base_url+'catalogos/editarNivel1',
		data:
		{
			nombre:		$('#txtNivel1').val(),
			idNivel1:	$('#txtIdNivel1').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel1').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,3);
					obtenerNiveles1();
					$("#ventanaEditarNivel1").dialog('close');
					$("#txtRegistrosAfectados1").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoNivel1").html('');
		}
	});		
}

function borrarNivel1(idNivel1)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel1').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"catalogos/borrarNivel1",
		data:
		{
			"idNivel1":	idNivel1,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel1').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerNiveles1();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
					$("#txtRegistrosAfectados1").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoNivel1').html('');
		}
	});				  	  
}
//PARA NIVEL 1
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtBuscarNivel3').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerNiveles3();
		}
	});
	
	$("#ventanaRegistrarNivel3").dialog(
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
				registrarNivel3();
			},
		},
		close: function() 
		{
			$('#formularioNivel3s').html('');
		}
	});
	
	$("#ventanaEditarNivel3").dialog(
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
				editarNivel3();
			},
		},
		close: function() 
		{
			$('#obtenerNivel3').html('');
		}
	});
	
	$(document).on("click", ".ajax-pagNivel3 > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerNiveles3";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarNivel3').val(),
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

function obtenerNiveles3()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNiveles3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNiveles3',
		data:
		{
			criterio:	$('#txtBuscarNivel3').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNiveles3").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerNiveles3").html('');
		}
	});		
}

function formularioNiveles3()
{
	$("#ventanaRegistrarNivel3").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNiveles3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'catalogos/formularioNiveles3',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioNiveles3").html(data);
			$('#txtNivel3').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioNiveles3").html('');
		}
	});		
}


function registrarNivel3()
{
	if($('#txtNivel3').val()=="")
	{
		notify('El nombre es incorrecto',500,5000,'error',30,3);
		return;
	}
	
	if($('#selectNiveles2Registro').val()=="0")
	{
		notify('Seleccione el nivel 2',500,5000,'error',30,3);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoNivel3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'catalogos/registrarNivel3',
		data:
		{
			nombre:		$('#txtNivel3').val(),
			idNivel2:	$('#selectNiveles2Registro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoNivel3').html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerNiveles3();
					$("#ventanaRegistrarNivel3").dialog('close');
					$("#txtRegistrosAfectados3").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoNivel3").html('');
		}
	});		
}

function obtenerNivel3(idNivel3)
{
	$("#ventanaEditarNivel3").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNivel3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo la variable');
		},
		type:"POST",
		url:base_url+'catalogos/obtenerNivel3',
		data:
		{
			idNivel3:idNivel3
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerNivel3").html(data);
			$('#txtNivel3').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener la variable',500,5000,'error',30,3);
			$("#obtenerNivel3").html('');
		}
	});		
}

function editarNivel3()
{
	if($('#txtNivel3').val()=="")
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
			$('#editandoNivel3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Editando la variable');
		},
		type:"POST",
		url:base_url+'catalogos/editarNivel3',
		data:
		{
			nombre:		$('#txtNivel3').val(),
			idNivel3:	$('#txtIdNivel3').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoNivel3').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar o no hubo cambios en el registro',500,5000,'error',30,3);
				
				break;
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,3);
					obtenerNiveles3();
					$("#ventanaEditarNivel3").dialog('close');
					$("#txtRegistrosAfectados3").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar el registro',500,5000,'error',30,3);
			$("#editandoNivel3").html('');
		}
	});		
}

function borrarNivel3(idNivel3)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoNivel3').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"catalogos/borrarNivel3",
		data:
		{
			"idNivel3":	idNivel3,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoNivel3').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerNiveles3();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
					$("#txtRegistrosAfectados3").val(1);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoNivel3').html('');
		}
	});				  	  
}
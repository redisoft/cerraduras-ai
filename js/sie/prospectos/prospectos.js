//MATRICULA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	obtenerProspectosSie()
	
	$('#txtInicioSieBusqueda,#txtFinSieBusqueda').datepicker({});
	
	$("#ventanaRegistroProspectosSie").dialog(
	{
		autoOpen:false,
		height:250,
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
				registrarProspectosSie();
			},
		},
		close: function() 
		{
			$('#formularioProspectosSie').html('');
		}
	});
	
	$("#ventanaEditarProspectosSie").dialog(
	{
		autoOpen:false,
		height:250,
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
				editarProspectosSie();
			},
		},
		close: function() 
		{
			$('#formularioEditarProspectosSie').html('');
		}
	});

	$(document).on("click", ".ajax-pagProspectosSie > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerProspectosSie";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio:	$('#txtInicioSieBusqueda').val(),
				fin:	$('#txtFinSieBusqueda').val(),
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

function obtenerProspectosSie()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProspectosSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'prospectos/obtenerRegistros',
		data:
		{
			inicio:	$('#txtInicioSieBusqueda').val(),
			fin:	$('#txtFinSieBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProspectosSie").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerProspectosSie").html('');
		}
	});		
}

function formularioProspectosSie()
{
	$("#ventanaRegistroProspectosSie").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProspectosSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'prospectos/formularioRegistro',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioProspectosSie").html(data);
			$('#txtMetaSie').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioProspectosSie").html('');
		}
	});		
}


function registrarProspectosSie()
{
	if(obtenerNumeros($('#txtMetaSie').val())==0)
	{
		notify('La meta es incorrecta',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoProspectoSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'prospectos/registrarInformacion',
		data:$('#frmRegistroProspectoSie').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoProspectoSie').html('');
			
			switch(data)
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerProspectosSie();
					$("#ventanaRegistroProspectosSie").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoProspectoSie").html('');
		}
	});		
}

function formularioEditarProspectosSie(idMeta)
{
	$("#ventanaEditarProspectosSie").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEditarProspectosSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario');
		},
		type:"POST",
		url:base_url+'prospectos/formularioEditar',
		data:
		{
			idMeta:idMeta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEditarProspectosSie").html(data);
			$('#txtMetaSie').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioEditarProspectosSie").html('');
		}
	});		
}


function editarProspectosSie()
{
	if(obtenerNumeros($('#txtMetaSie').val())==0)
	{
		notify('La meta es incorrecta',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoProspectoSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando');
		},
		type:"POST",
		url:base_url+'prospectos/editarInformacion',
		data:$('#frmRegistroProspectoSie').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoProspectoSie').html('');
			
			switch(data)
			{
				case "0":
					notify('El registro no se ha modificado',500,5000,'error',30,3);
				break;
	
				case "1":
					notify('El registro ha sido correcto',500,5000,'',30,3);
					obtenerProspectosSie()
					$("#ventanaEditarProspectosSie").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',30,3);
			$("#registrandoProspectoSie").html('');
		}
	});		
}



function borrarProspectosSie(idMeta)
{
	if(!confirm('¿Realmente desea borrar el registro?'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoProspectosSie').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el registro');
		},
		type:"POST",
		url:base_url+"prospectos/borrarRegistro",
		data:
		{
			"idMeta":	idMeta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoProspectosSie').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					obtenerProspectosSie();
					notify('El registro se ha borrado correctamente',500,5000,'',30,3);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',30,3);
			$('#procesandoProspectosSie').html('');
		}
	});				  	  
}
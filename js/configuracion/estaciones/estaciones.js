//=====================================================================================================//
//===================================GRADOS========================================//
//=====================================================================================================//

$(document).ready(function()
{
	obtenerRegistros()
	
	$("#ventanaRegistro").dialog(
	{
		autoOpen:false,
		height:200,
		width:850,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		[
		 	{
                text: "Cancelar",
                click: function() 
				{
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "Registrar",
                click: $.noop,
                type: "submit",
                form: "frmRegistro",
				
            },
        ],
		close: function() 
		{
			$("#formularioRegistro").html('');
		}
	});
	
	$("#txtBuscarRegistro").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerRegistros();
		}, milisegundos);
	});
	
	$(document).on("click", ".ajax-pagRegistros > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRegistros";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarRegistro').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerRegistros').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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


function obtenerRegistros()
{
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRegistros').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+"estaciones/obtenerRegistros",
		data:
		{
			"criterio":	$('#txtBuscarRegistro').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRegistros').html(data);
			
		},
		error:function(datos)
		{
			//notify('Error al obtener los registros',500,5000,'error',30,5)
			$("#obtenerRegistros").html('');	
		}
	});
}

function formularioRegistro()
{
	$('#ventanaRegistro').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRegistro').html('<img src="'+ img_loader +'"/> Preparando el formulario...');
		},
		type:"POST",
		url:base_url+"estaciones/formularioRegistro",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRegistro').html(data);
			$('#txtNombre').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3)
			$("#formularioRegistro").html('');	
		}
	});
}


function registrarFormulario()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<img src="'+ img_loader +'"/> Registrando, por favor espere...');
		},
		type:"POST",
		url:base_url+"estaciones/registrarFormulario",
		data: $('#frmRegistro').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaRegistro').dialog('close');
					obtenerRegistros();
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar',500,5000,'error',30,3);	
		}
	});
}
/*--------------------------------EDITAR MATERIALES-------------------------------------*/

function obtenerRegistro(idEstacion)
{
	$('#ventanaEditarRegistro').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRegistro').html('<img src="'+ img_loader +'"/> Obteniendo el registro');
		},
		type:"POST",
		url:base_url+"estaciones/obtenerRegistro",
		data:
		{
			"idEstacion":idEstacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRegistro').html(data);
		},
		error:function(datos)
		{
			$("#obtenerRegistro").html('');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanaEditarRegistro").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		[
		 	{
                text: "Cancelar",
                click: function() 
				{
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "Editar",
                click: $.noop,
                type: "submit",
                form: "frmEditar",
				
            },
        ],
		close: function() 
		{
			$("#obtenerRegistro").html('');
		}
	});
});

function editarFormulario()
{
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#editandoInformacion').html('<img src="'+ img_loader +'"/> Se esta editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"estaciones/editarFormulario",
		data: $('#frmEditar').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5)
					$("#ventanaEditarRegistro").dialog('close');
					obtenerRegistros();
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoInformacion').html('');
			notify('Error al editar el registro',500,5000,'error',30,3);
		}
	});				
}

function borrarRegistro(idEstacion)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"estaciones/borrarRegistro",
		data:
		{
			"idEstacion":		idEstacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerRegistros();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify('Error al borrar el registro',500,5000,'error',30,3);	
		}
	});
}
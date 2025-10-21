//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//PEDIMENTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
    obtenerRegistrosPedimentos()
    
    $('#txtBuscarRegistroPedimentos').keypress(function(e)
	{
		if(e.which == 13) 
		{
			obtenerRegistrosPedimentos();
		}
	});
    
	$("#ventanaRegistroPedimentos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:320,
		width:600,
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
                text: "Registrar",
                click: $.noop,
                type: "submit",
                form: "frmRegistroPedimentos",
				
            },
        ],
		close: function() 
		{
			$("#formularioRegistroPedimentos").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagRegistrosPedimentos > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerRegistrosPedimentos";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
                criterio: $('#txtBuscarRegistro').val()
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

function formularioRegistroPedimentos()
{
	$("#ventanaRegistroPedimentos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioRegistroPedimentos').html('<img src="'+ img_loader +'"/>Preparando el formulario');},
		type:"POST",
		url:base_url+'pedimentos/formularioRegistro',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRegistroPedimentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de motivos',500,4000,"error"); 
			$('#formularioRegistroPedimentos').html('')
		}
	}); 	  
}

function registrarFormularioPedimentos()
{
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoPedimentos').html('<img src="'+ img_loader +'"/>Se esta registrando el valor');},
		type:"POST",
		url:base_url+'pedimentos/registrarFormulario',
		data:$('#frmRegistroPedimentos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPedimentos').html('');
			data=eval(data);
            
			switch(data[0])
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					obtenerRegistrosPedimentos();
                    $("#ventanaRegistroPedimentos").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#registrandoPedimentos').html('');
		}
	}); 	  
}

function obtenerRegistrosPedimentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRegistrosPedimentos').html('<img src="'+ img_loader +'"/>Obteniendo registros');},
		type:"POST",
		url:base_url+'pedimentos/obtenerRegistros',
		data:
		{
            criterio: $('#txtBuscarRegistroPedimentos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRegistrosPedimentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,4000,"error"); 
			$('#obtenerRegistrosPedimentos').html('')
		}
	}); 	  
}

function formularioEditarPedimentos(idPedimento)
{
	$("#ventanaRegistroPedimentos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioRegistroPedimentos').html('<img src="'+ img_loader +'"/>Preparando el formulario');},
		type:"POST",
		url:base_url+'pedimentos/formularioEditar',
		data:
		{
			idPedimento:idPedimento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRegistroPedimentos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el registro',500,4000,"error"); 
			$('#formularioRegistroPedimentos').html('')
		}
	}); 	  
}

function editarFormularioPedimentos()
{
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoPedimentos').html('<img src="'+ img_loader +'"/>Editando el registro');},
		type:"POST",
		url:base_url+'pedimentos/editarFormulario',
		data:$('#frmRegistroPedimentos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoPedimentos').html('');
			data=eval(data);
            
			switch(data[0])
			{
				case "0":
					notify('Error en el registro',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					obtenerRegistrosPedimentos();
                    $("#ventanaRegistroPedimentos").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#registrandoPedimentos').html('');
		}
	}); 	  
}

function borrarRegistroPedimentos(idPedimento)
{
	if(!confirm('¿Realmente desea editar el motivo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoInformacionPedimentos').html('<img src="'+ img_loader +'"/>Borrando el registro');},
		type:"POST",
		url:base_url+'pedimentos/borrarRegistro',
		data:
		{
			idPedimento: 	idPedimento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacionPedimentos').html('');
			
            data=eval(data);
			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerRegistrosPedimentos()
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,4000,"error"); 
			$('#procesandoInformacionPedimentos').html('');
		}
	}); 	  
}

function agregarPedimento(idPedimento)
{
	$('#txtIdPedimentoRegistro').val(idPedimento)

	$('#lblPedimento').html($('#txtNumeroPedimento' + idPedimento).val());

	$('#btnBorrarPedimento').fadeIn();

	$('#ventanaCatalogoPedimentos').dialog('close');
}

function borrarPedimentoProducto()
{
	$('#lblPedimento').html('Seleccione');
	$('#txtIdPedimentoRegistro').val(0);

	$('#btnBorrarPedimento').fadeOut();
}


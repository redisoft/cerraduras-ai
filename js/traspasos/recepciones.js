//PARA LAS RECEPCIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$(document).ready(function()
{
	$("#ventanaRecepciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
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
				id: "btnRecibirTraspaso",
                text: "Registrar",
                click: function() 
				{
                    registrarRecepcion();
                },
                type: "button",
            },
        ],
		close: function() 
		{
			$('#formularioRecepciones').html('');
		}
	});
});

function formularioRecepciones(idTraspaso)
{
	$("#ventanaRecepciones").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioRecepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'tiendas/formularioRecepciones',
		data:
		{
			idTraspaso:idTraspaso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioRecepciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioRecepciones").html('');
		}
	});		
}

function comprobarProductosRecepcion()
{
	for(i=0;i<=obtenerNumeros($('#txtNumeroProductos').val());i++)
	{
		if(obtenerNumeros($('#txtCantidadRecibir'+i).val())>0) return true;
	}
	
	return false;
}

function comprobarCantidadRecepcion()
{
	for(i=0;i<obtenerNumeros($('#txtNumeroProductos').val());i++)
	{
		pendiente	= obtenerNumeros($('#txtCantidadPendiente'+i).val());
		cantidad	= obtenerNumeros($('#txtCantidadRecibir'+i).val());
		
		if(!compararCantidades(pendiente,cantidad)) return false;
	}
	
	return true;
}

function registrarRecepcion()
{
	mensaje="";
	
	$("#btnRecibirTraspaso").button("disable");
	
	if(ejecutarAccion && ejecutarAccion.readyState != 4)
	{
		notify('Se esta registrando la recepción',500,5000,'error',30,5);
		return;
	}

	if(!comprobarProductosRecepcion())
	{
		notify('Agregue al menos un producto para la recepción',500,5000,'error',30,3);
		
		$("#btnRecibirTraspaso").button("enable");
		return;
	}
	
	if(!comprobarCantidadRecepcion())
	{
		notify('Verifique que las cantidades sean correctas',500,5000,'error',30,3);
		$("#btnRecibirTraspaso").button("enable");
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la recepción?')) return;
	
	ejecutarAccion=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoRecepcion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando la recepción');
		},
		type:"POST",
		url:base_url+'tiendas/registrarRecepcion',
		data:$('#frmRecepciones').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoRecepcion').html('');
			data	= eval(data);
			
			$("#btnRecibirTraspaso").button("enable");
		
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
					notify(data[1],500,5000,'',30,3);
					$("#ventanaRecepciones").dialog('close');
					obtenerTraspasos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la recepción',500,5000,'error',30,3);
			$("#registrandoRecepcion").html('');
			
			$("#btnRecibirTraspaso").button("enable");
		}
	});		
}


function obtenerListas()
{
	$("#ventanaListas").dialog('open');
	
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerListas').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+"listas/obtenerListas",
		data:
		{
			criterio:	$('#txtBuscarLista').val(),
			inicio:		$('#txtInicioBusqueda').val(),
			fin:		$('#txtFinalBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerListas').html(data);
			
			$('#txtBuscarLista').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,4000,"error");
			$("#obtenerListas").html('');	
		}
	});				
}

$(document).ready(function()
{
	$('#txtInicioBusqueda,#txtFinalBusqueda').datepicker();
	
	$("#txtBuscarLista").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerListas();
		}, milisegundos);
	});

	$("#ventanaListas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1050,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		
			},
		},
		close: function() 
		{
			$("#formularioListas").html('');
		}
	});
	
	$(document).on("click", ".ajax-pagListas > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerListas";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarLista').val(),
				inicio:		$('#txtInicioBusqueda').val(),
				fin:		$('#txtFinalBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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
	
	$(document).on("click", ".ajax-pagProductosLista > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerProductosLista";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarProductoLista').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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
	
	$("#ventanaFormularioListas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:630,
		width:1000,
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
                form: "frmListas",
				
            },
        ],
		close: function() 
		{
			$("#formularioListas").html('');
		}
	});
	
	$("#ventanaEditarLista").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:630,
		width:1000,
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
				editarLista()
			},
		},
		close: function() 
		{
			$("#obtenerLista").html('');
		}
	});
});

function formularioListas()
{
	$('#ventanaFormularioListas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioListas').html('<img src="'+ img_loader +'"/> Preparando el formulario, por favor espere...');
		},
		type:"POST",
		url:base_url+"listas/formularioListas",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioListas').html(data);
			obtenerProductosLista();
			
			l=0;
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,4000,"error");
			$("#formularioProductos").html('');	
		}
	});				
}

function obtenerLista(idLista)
{
	$('#ventanaEditarLista').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerLista').html('<img src="'+ img_loader +'"/> Preparando el formulario, por favor espere...');
		},
		type:"POST",
		url:base_url+"listas/obtenerLista",
		data:
		{
			idLista:idLista
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerLista').html(data);
			obtenerProductosLista();
			
			l=obtenerNumeros($('#txtNumeroProductosLista').val());
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,4000,"error");
			$("#obtenerLista").html('');	
		}
	});				
}

function obtenerProductosLista()
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
			$('#obtenerProductosLista').html('<img src="'+ img_loader +'"/> Obteniendo registros...');
		},
		type:"POST",
		url:base_url+"listas/obtenerProductosLista",
		data:
		{
			criterio:	$('#txtBuscarProductoLista').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProductosLista').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,4000,"error");
			$("#obtenerProductosLista").html('');	
		}
	});				
}

function configurarFechaFinal()
{
	if(document.getElementById('chkVigencia').checked)
	{
		$('#filaFechaFinal').fadeIn();
		return;
	}
	else
	{
		$('#filaFechaFinal').fadeOut();
		return;
	}
}

l=0;

function quitarProductoLista(p)
{
	$('#filaLista'+p).remove();
}

function comprobarProductoListaRepetido(idProducto)
{
	for(i=0;i<l;i++)
	{
		if(idProducto==$('#txtIdProducto'+i).val()) return false;
	}
	
	return true;
}

function cargarProductoLista(p)
{
	if(!comprobarProductoListaRepetido($('#txtProductoId'+p).val()))
	{
		notify('Ya se ha cargado el registro',500,4000,"error",30,5);
		return;
	}
	
	data	='<tr id="filaLista'+l+'">';
	data	+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarProductoLista('+l+')" /></td>';
	data	+='<td>'+$('#txtCodigoInterno'+p).val()+'</td>';
	data	+='<td>'+$('#txtNombre'+p).val()+'</td>';
	data	+='<td>'+$('#txtLinea'+p).val()+'</td>';
	data	+='<td align="right">$'+redondear($('#txtPrecio'+p).val())+'</td>';
	data	+='<td align="center" style="display: none"><input type="text" class="cajas" style="text-align: right; width:80px" id="txtPrecioNuevo'+l+'" name="txtPrecioNuevo'+l+'" value="'+$('#txtPrecio'+p).val()+'" /> </td>';
	data	+='<input type="hidden" id="txtPrecioProducto'+l+'" name="txtPrecioProducto'+l+'" value="'+$('#txtPrecio'+p).val()+'" />';
	data	+='<input type="hidden" id="txtIdProducto'+l+'" name="txtIdProducto'+l+'" value="'+$('#txtProductoId'+p).val()+'" />';
	data	+='</tr>';
	
	$('#tablaLista').append(data);
	$("#tablaLista tr:even").addClass("sombreado");
	$("#tablaLista tr:odd").addClass("sinSombra");  
	
	l++;
	$('#txtNumeroProductosLista').val(l);
	
}

function comprobarProductosLista()
{
	pro	= false;
	
	for(i=0;i<l;i++)
	{
		idProducto	= obtenerNumeros($('#txtIdProducto'+i).val());
		
		if(idProducto>0)
		{
			pro	= true;
			
			//if(obtenerNumeros($('#txtPrecioNuevo'+i).val())==0) return false;
		}
	}
	
	if(!pro) return false;
	
	return true;
}

function registrarLista()
{
	var mensaje="";

	if(!camposVacios($("#txtNombreLista").val()))
	{
		mensaje+="El nombre de la lista es incorrecto <br />";										
	}
	
	if(document.getElementById('chkVigencia').checked)
	{
		if($("#txtFechaInicialRegistro").val()>$("#txtFechaFinalRegistro").val())
		{
			mensaje+="Las fechas son incorrectas <br />";										
		}
	}
	
	if(!comprobarProductosLista())
	{
		mensaje+="Configure correctamente los productos";	
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#registrandoLista').html('<img src="'+ img_loader +'"/> Procesando el registro...');
		},
		url:base_url+'listas/registrarLista',
		data:$('#frmListas').serialize(),
		async: false,
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoLista').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify(data[1],500,4000,"");
					$('#ventanaFormularioListas').dialog('close');
					obtenerListas();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,4000,"error",30,5);
			$('#registrandoLista').html('')
		}
	});
}

function editarLista()
{
	var mensaje="";

	if(!camposVacios($("#txtNombreLista").val()))
	{
		mensaje+="El nombre de la lista es incorrecto <br />";										
	}
	
	if(document.getElementById('chkVigencia').checked)
	{
		if($("#txtFechaInicialRegistro").val()>$("#txtFechaFinalRegistro").val())
		{
			mensaje+="Las fechas son incorrectas <br />";										
		}
	}
	
	if(!comprobarProductosLista())
	{
		mensaje+="Configure correctamente los productos";	
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,4000,"error",30,5);
		return;	
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#editandoLista').html('<img src="'+ img_loader +'"/> Procesando el registro...');
		},
		url:base_url+'listas/editarLista',
		data:$('#frmListas').serialize(),
		async: false,
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoLista').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					notify(data[1],500,4000,"");
					$('#ventanaEditarLista').dialog('close');
					obtenerListas();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al registrar el producto',500,4000,"error",30,5);
			$('#editandoLista').html('')
		}
	});
}


function borrarLista(idLista)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoListas').html('<img src="'+ img_loader +'"/> Borrando el registro...');
		},
		type:"POST",
		url:base_url+'listas/borrarLista',
		data:
		{
			"idLista":	idLista,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoListas').html('');
			
			data	= eval(data);

			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,4000,"error",30,5);
				break;
				
				case "1":
					$('#filaListas'+idLista).remove();
					notify('El registro se ha borrado correctamente',500,4000,"",30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,4000,"error",30,5);
			$('#procesandoListas').html('')
		}
	});
}

function listasPdf(idLista)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoListas').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'listas/listasPdf',
		data:
		{
			idLista:idLista
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/ListaPrecios'
			$('#procesandoListas').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#procesandoListas").html('');
		}
	});		
}

function autorizarLista(idLista)
{
	if(!confirm('¿Realmente desea autorizar la lista?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoListas').html('<img src="'+ img_loader +'"/> Autorizando el registro...');
		},
		type:"POST",
		url:base_url+'listas/autorizarLista',
		data:
		{
			"idLista":	idLista,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoListas').html('');
			
			data	= eval(data);

			switch(data[0])
			{
				case "0":
					notify('Error al autorizar el registro',500,4000,"error",30,5);
				break;
				
				case "1":
					notify('El registro se ha autorizado correctamente',500,4000,"",30,5);
					obtenerListas();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al autorizar el registro',500,4000,"error",30,5);
			$('#procesandoListas').html('')
		}
	});
}


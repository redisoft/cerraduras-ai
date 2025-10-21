//PARA LOS ENVÍOS DE LOS PRODUCTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$("#txtBuscarTraspasos").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerTraspasos();
		}, 700);
	});
	
	$("#ventanaTraspasos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1104,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$('#obtenerTraspasos').html('');
		}
	});
	
	//$('.ajax-pagTraspasos > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagTraspasos > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerTraspasos";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idLicenciaOrigen:	0,
				idLicenciaDestino:	0,
				criterio:			$('#txtBuscarTraspasos').val(),
				inicio:				$('#txtFechaInicial').val(),
				fin:				$('#txtFechaFinal').val()
			},
			dataType:"html",
			beforeSend:function(){$('#obtenerTraspasos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de traspasos');;},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},3);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});
});

function obtenerTraspasos()
{
	$("#ventanaTraspasos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTraspasos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de traspasos');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerTraspasos',
		data:
		{
			idLicenciaOrigen:	0,
			idLicenciaDestino:	0,
			criterio:			$('#txtBuscarTraspasos').val(),
			inicio:				$('#txtFechaInicial').val(),
			fin:				$('#txtFechaFinal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerTraspasos").html(data);
			$('#txtBuscarTraspasos').focus()
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de traspasos',500,5000,'error',30,3);
			$("#obtenerTraspasos").html('');
		}
	});		
}


//PARA LOS PRODUCTOS DE ENVÍO
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$(document).ready(function()
{
	$("#ventanaFormularioTraspasos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:630,
		width:990,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				registrarTraspaso();				 
			},
			
		},
		close: function() 
		{
			$('#formularioTraspasos').html('');
		}
	});
	
	//$('.ajax-pagProductosEnvio > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagProductosTraspaso > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerProductosTraspaso";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarProducto').val()
			},
			dataType:"html",
			beforeSend:function(){$('#obtenerProductosTraspaso').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de productos');;},
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

function formularioTraspasos()
{
	$("#ventanaFormularioTraspasos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioTraspasos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Preparando el formulario');
		},
		type:"POST",
		url:base_url+'tiendas/formularioTraspasos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioTraspasos").html(data);
			obtenerProductosTraspaso();
			tra=0;
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3);
			$("#formularioTraspasos").html('');
		}
	});		
}


function obtenerProductosTraspaso()
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
			$('#obtenerProductosTraspaso').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de productos');
		},
		type:"POST",
		url:base_url+'tiendas/obtenerProductosTraspaso',
		data:
		{
			criterio:	$('#txtBuscarProductoTraspaso').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProductosTraspaso").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de productos',500,5000,'error',30,3);
			$("#obtenerProductosTraspaso").html('');
		}
	});		
}

function registrarTraspaso()
{
	mensaje="";

	if(!comprobarProductosAgregados())
	{
		notify('Agregue al menos un producto para el traspaso',500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el traspaso?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoTraspaso').html('<img src="'+base_url+'img/ajax-loader.gif"/> Registrando el traspaso');
		},
		type:"POST",
		url:base_url+'tiendas/registrarTraspaso',
		data:$('#frmTraspasos').serialize()+'&numeroProductos='+tra,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoTraspaso').html('');
			data	= eval(data);
		
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
					notify(data[1],500,5000,'',30,3);
					$("#ventanaFormularioTraspasos").dialog('close');
					obtenerTraspasos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el traspaso',500,5000,'error',30,3);
			$("#registrandoTraspaso").html('');
		}
	});		
}

tra	= 0;

function comprobarProductosAgregados()
{
	for(i=0;i<=tra;i++)
	{
		if(obtenerNumeros($('#txtIdInventario'+i).val())>0) return true;
	}
	
	return false;
}

function quitarTraspaso(p)
{
	$('#filTraspaso'+p).remove();
}

function comprobarTraspaso(idInventario)
{
	for(i=0;i<=tra;i++)
	{
		if(obtenerNumeros(idInventario)==obtenerNumeros($('#txtIdInventario'+i).val())) return false;
	}
	
	return true;
}

function cargarProductoTraspaso(p)
{
	if(!comprobarTraspaso($('#txtInventarioId'+p).val()))
	{
		notify('Ya se ha cargado el producto',500,5000,'error',30,5);
		$('#txtTraspasoCantidad'+p).val('')
		return;	
	}
	
	if(!compararCantidades($('#txtStock'+p).val(),$('#txtTraspasoCantidad'+p).val()))
	{
		notify('La cantidad supera el inventario',500,5000,'error',30,5);
		$('#txtTraspasoCantidad'+p).val('')
		return;	
	}
	
	data='<tr id="filTraspaso'+tra+'">';
	
	data+='<td ><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarTraspaso('+tra+')" /></td>';
	data+='<td>'+$('#txtCodigoInterno'+p).val()+'</td>';
	data+='<td>'+$('#txtNombre'+p).val()+'</td>';
	data+='<td>'+$('#txtLinea'+p).val()+'</td>';
	data+='<td align="center">'+$('#txtStock'+p).val()+'</td>';
	data+='<td align="center">'+$('#txtTraspasoCantidad'+p).val()+'</td>';
	
	data+='<input type="hidden" id="txtCantidadTraspaso'+tra+'" name="txtCantidadTraspaso'+tra+'" value="'+$('#txtTraspasoCantidad'+p).val()+'"  />';
	data+='<input type="hidden" id="txtIdProducto'+tra+'" name="txtIdProducto'+tra+'" value="'+$('#txtProductoId'+p).val()+'"  />';
	data+='<input type="hidden" id="txtIdInventario'+tra+'" name="txtIdInventario'+tra+'" value="'+$('#txtInventarioId'+p).val()+'"  />';
	
	data+='</tr>';
	
	$('#tablaTraspasos').append(data);
	
	$("#tablaTraspasos tr:even").addClass("sombreado");
	$("#tablaTraspasos tr:odd").addClass("sinSombra");  

	tra++;
	
	$('#txtTraspasoCantidad'+p).val('')
}

function borrarTraspaso(idTraspaso)
{
	if(!confirm('¿Realmente desea borrar el traspaso?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTraspasos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Borrando el traspaso');
		},
		type:"POST",
		url:base_url+'tiendas/borrarTraspaso',
		data:
		{
			idTraspaso:idTraspaso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoTraspasos').html('');
			data	= eval(data);
		
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
	
				case "1":
					notify(data[1],500,5000,'',30,3);
					obtenerTraspasos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la recepción',500,5000,'error',30,3);
			$("#procesandoTraspasos").html('');
		}
	});		
}



function reporteTraspasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoReportes').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'tiendas/reporteTraspasos',
		data:
		{
			idTienda:	$('#selectTiendas').val(),
			criterio:	$('#txtCriterio').val(),
			inicio:		$('#txtFechaInicial').val(),
			fin:		$('#txtFechaFinal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href=base_url+'reportes/descargarPdfReportes/reporteTraspasos/reporteTraspasos'
			$('#procesandoReportes').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte',500,5000,'error',2,5);
			$("#procesandoReportes").html('');
		}
	});		
}

function excelTraspasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoReportes').html('<img src="'+ img_loader +'"/> Se esta generando el reporte...');
		},
		type:"POST",
		url:base_url+'tiendas/excelTraspasos',
		data:
		{
			idTienda:	$('#selectTiendas').val(),
			criterio:	$('#txtCriterio').val(),
			inicio:		$('#txtFechaInicial').val(),
			fin:		$('#txtFechaFinal').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.location.href	= base_url+'reportes/descargarExcelReportes/'+data+'/Traspasos'
			$('#procesandoReportes').html('');
		},
		error:function(datos)
		{
			notify('Error al crear el reporte en excel',500,5000,'error',2,5);
			$("#procesandoReportes").html('');
		}
	});		
}
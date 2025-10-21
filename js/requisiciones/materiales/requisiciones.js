//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//REQUISICIONES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$(document).ready(function()
{
	$('#txtInicioRequisicion,#txtFinRequisicion').datepicker({changeMonth: true, changeYear: true});
	
	/*$("#txtBusquedaRequisicion").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerRequisiciones();
		}, 700);
	});*/
	
	$(document).on("click", ".ajax-pagRequisiciones > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerRequisiciones";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"inicio":	$('#txtInicioRequisicion').val(),
				"fin":		$('#txtFinRequisicion').val(),
				"criterio":	$('#txtBusquedaRequisicion').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerRequisiciones').html('<img src="'+ img_loader +'"/>Obteniendo requisiciones..');
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

function obtenerRequisiciones()
{
	$('#ventanaRequisiciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRequisiciones').html('<img src="'+ img_loader +'"/> Obteniendo detalles de requisición...');},
		type:"POST",
		url:base_url+"requisiciones/obtenerRequisiciones",
		data:
		{
			"inicio":	$('#txtInicioRequisicion').val(),
			"fin":		$('#txtFinRequisicion').val(),
			"criterio":	$('#txtBusquedaRequisicion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerRequisiciones").html(data);
		},
		error:function(datos)
		{
			$("#obtenerRequisiciones").html('');	
		}
	});//Ajax	
}
	

$(document).ready(function()
{
	$("#ventanaRequisiciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaRequisiciones').dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerRequisiciones").html('');
		}
	});
});

$(document).ready(function()
{
	$("#ventanaFormularioRequisiciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarRequisicion()
			},
		},
		close: function() 
		{
			$("#formularioRequisiciones").html('');
		}
	});
});

function formularioRequisiciones()
{
	$('#ventanaFormularioRequisiciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioRequisiciones').html('<img src="'+ img_loader +'"/> Preparando el formulario de requisición...');},
		type:"POST",
		url:base_url+"requisiciones/formularioRequisiciones",
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioRequisiciones").html(data);
			$('#txtComentariosRequisicion').focus();
		},
		error:function(datos)
		{
			$("#formularioRequisiciones").html('');	
		}
	});//Ajax	
}

function quitarMaterialRequisicion(i)
{
	$('#filaMaterialRequisicion'+i).remove()
}

function comprobarMateriaRequisicion(idMaterial)
{
	for(i=0;i<re;i++)
	{
		if(obtenerNumero(idMaterial)==obtenerNumero($('#txtIdMaterial'+i).val())) return false;
	}
	
	return true;
}

re=0;
function cargarMateriaRequisicion(material)
{
	if(!comprobarMateriaRequisicion(material.idMaterial))
	{
		setTimeout(function(){$('#txtBuscarMateriaRequisicion').val('')},300);
		notify('Ya se ha cargado el material',500,6000,"error",30,5);
		
		return;
	}
	
	data='<tr id="filaMaterialRequisicion'+re+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarMaterialRequisicion('+re+')"/></td>';
	data+='<td>'+material.nombre+'</td>';
	data+='<td>'+material.unidad+'</td>';
	data+='<td align="center"> <input type="text"  	name="txtCantidadRequisicion'+re+'" id="txtCantidadRequisicion'+re+'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>';
	data+='<input type="hidden"  name="txtIdMaterial'+re+'" id="txtIdMaterial'+re+'" value="'+material.idMaterial+'" />';
	data+='</tr>';
	
	$('#tablaRequisiciones').append(data);
	re++;
	
	$("#tablaRequisiciones tr:even").addClass("sombreado");
	$("#tablaRequisiciones tr:odd").addClass("sinSombra"); 
	$('#txtNumeroMateriales').val(re); 
	
	setTimeout(function(){$('#txtBuscarMateriaRequisicion').val('')},300);
}

function sugerirMaterialNuevo()
{
	/*if(!comprobarMateriaRequisicion(material.idMaterial))
	{
		setTimeout(function(){$('#txtBuscarMateriaRequisicion').val('')},300);
		notify('Ya se ha cargado el material',500,6000,"error",30,5);
		
		return;
	}*/
	
	data='<tr id="filaMaterialRequisicion'+re+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarMaterialRequisicion('+re+')"/></td>';
	data+='<td>'+$('#txtBuscarMateriaRequisicion').val()+'</td>';
	data+='<td>Pieza</td>';
	data+='<td align="center"> <input type="text"  	name="txtCantidadRequisicion'+re+'" id="txtCantidadRequisicion'+re+'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>';
	data+='<input type="hidden"  name="txtIdMaterial'+re+'" id="txtIdMaterial'+re+'" value="100000000" />';
	data+='<input type="hidden"  name="txtNombreMaterial'+re+'" id="txtNombreMaterial'+re+'" value="'+$('#txtBuscarMateriaRequisicion').val()+'" />';
	data+='</tr>';
	
	$('#tablaRequisiciones').append(data);
	re++;
	
	$("#tablaRequisiciones tr:even").addClass("sombreado");
	$("#tablaRequisiciones tr:odd").addClass("sinSombra"); 
	$('#txtNumeroMateriales').val(re); 
	
	setTimeout(function(){$('#txtBuscarMateriaRequisicion').val('')},300);
}

function comprobarRequisiciones()
{
	b=false;
	
	for(i=0;i<re;i++)
	{
		if(obtenerNumero($('#txtIdMaterial'+i).val())>0)
		{
			b=true;
			
			if(obtenerNumero($('#txtCantidadRequisicion'+i).val())==0)
			{
				b=false;
			}
		}
	}
	
	return b;
}

function registrarRequisicion()
{
	if(!comprobarRequisiciones())
	{
		notify('Configure correctamente los materiales para las requisiciones',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la requisición?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoRequisicion').html('<img src="'+ img_loader +'"/> Registrando la requisición');},
		type:"POST",
		url:base_url+"requisiciones/registrarRequisicion",
		data:$('#frmRequisicion').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoRequisicion').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					obtenerRequisiciones();
					$('#ventanaFormularioRequisiciones').dialog('close');
					notify('Registro exitoso',500,6000,"",30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la requisición',500,4000,"error",30,5);
			$("#registrandoRequisicion").html('');	
		}
	});				
}

function borrarRequisicion(idRequisicion)
{
	if(!confirm('¿Realmente desea borrar la requisición?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoRequisiciones').html('<img src="'+ img_loader +'"/> Borrando la requisición');},
		type:"POST",
		url:base_url+"requisiciones/borrarRequisicion",
		data:
		{
			idRequisicion:idRequisicion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoRequisiciones').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					obtenerRequisiciones();
					notify('El registro se ha borrado correctamente',500,6000,"",30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar la requisición',500,4000,"error",30,5);
			$("#procesandoRequisiciones").html('');	
		}
	});				
}

//EDITAR REQUISICIÓN
$(document).ready(function()
{
	$("#ventanaEditarRequisiciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				editarRequisicion()
			},
		},
		close: function() 
		{
			$("#obtenerRequisicion").html('');
		}
	});
});

function obtenerRequisicion(idRequisicion)
{
	$('#ventanaEditarRequisiciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerRequisicion').html('<img src="'+ img_loader +'"/> Preparando el formulario de requisición...');},
		type:"POST",
		url:base_url+"requisiciones/obtenerRequisicion",
		data:
		{
			idRequisicion:idRequisicion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerRequisicion").html(data);
			re=obtenerNumero($("#txtNumeroMateriales").val());
			$('#txtComentariosRequisicion').focus();
		},
		error:function(datos)
		{
			$("#obtenerRequisicion").html('');	
		}
	});//Ajax	
}

function editarRequisicion()
{
	if(!comprobarRequisiciones())
	{
		notify('Configure correctamente los materiales para las requisiciones',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar la requisición?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoRequisicion').html('<img src="'+ img_loader +'"/> Editando la requisición');},
		type:"POST",
		url:base_url+"requisiciones/editarRequisicion",
		data:$('#frmEditarRequisicion').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoRequisicion').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,4000,"error",30,5);
				break;
				
				case "1":
					obtenerRequisiciones();
					$('#ventanaEditarRequisiciones').dialog('close');
					notify('Registro exitoso',500,6000,"",30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la requisición',500,4000,"error",30,5);
			$("#editandoRequisicion").html('');	
		}
	});				
}


$(document).ready(function()
{
	$("#ventanaDetallesRequisiciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$('#ventanaDetallesRequisiciones').dialog('close');
			},
		},
		close: function() 
		{
			$("#obtenerDetallesRequisicion").html('');
		}
	});
});

function obtenerDetallesRequisicion(idRequisicion)
{
	$('#ventanaDetallesRequisiciones').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerDetallesRequisicion').html('<img src="'+ img_loader +'"/> Obteniendo detalles de requisición...');},
		type:"POST",
		url:base_url+"requisiciones/obtenerDetallesRequisicion",
		data:
		{
			idRequisicion:idRequisicion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDetallesRequisicion").html(data);
		},
		error:function(datos)
		{
			$("#obtenerDetallesRequisicion").html('');	
		}
	});//Ajax	
}
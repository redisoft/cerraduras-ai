//=====================================================================================================//
//===================================FUNCIONES DE MATERIA PRIMA========================================//
//=====================================================================================================//

function obtenerSalidasControl()
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
			$('#obtenerSalidasControl').html('<img src="'+ img_loader +'"/> Obteniendo salidas, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"materiales/obtenerSalidasControl",
		data:
		{
			"criterio":	$('#txtBuscarControl').val(),
			"inicio":	$('#txtInicioControl').val(),
			"fin":		$('#txtFinControl').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSalidasControl').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la materia prima',500,5000,'error',30,5)
			$("#obtenerSalidasControl").html('');	
		}
	});
}

function formularioSalidaControl()
{
	$('#ventanaSalidasControl').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSalidaControl').html('<img src="'+ img_loader +'"/> Obteniendo formulario de control...');
		},
		type:"POST",
		url:base_url+"materiales/formularioSalidaControl",
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioSalidaControl').html(data);
			$('#txtComentarios').focus();
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de control',500,5000,'error',30,3)
			$("#formularioSalidaControl").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#txtInicioControl,#txtFinControl").datepicker({});
	
	obtenerSalidasControl()
	
	$("#ventanaSalidasControl").dialog(
	{
		autoOpen:false,
		height:500,
		width:850,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarSalidaControl()				  	  
			},
		},
		close: function() 
		{
			$("#formularioSalidaControl").html('');
		}
	});
	
	$("#txtBuscarControl").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerSalidasControl();
		}, 700);
	});
	
	//$('.ajax-pagMateriales > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagSalidasControl > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerSalidasControl";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBuscarControl').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerSalidasControl').html('<img src="'+ img_loader +'"/>Obteniendo salidas..');
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

function quitarMaterialControl(i)
{
	$('#filaSalidaControl'+i).remove()
}

function comprobarMateriaControl(idMaterial)
{
	for(i=0;i<re;i++)
	{
		if(obtenerNumero(idMaterial)==obtenerNumero($('#txtIdMaterial'+i).val())) return false;
	}
	
	return true;
}

re=0;
function cargarMateriaControl(material)
{
	if(!comprobarMateriaControl(material.idMaterial))
	{
		setTimeout(function(){$('#txtBuscarMateriaSalida').val('')},300);
		notify('Ya se ha cargado el material',500,6000,"error",30,5);
		
		return;
	}
	
	data='<tr id="filaSalidaControl'+re+'">';
	data+='<td><img src="'+base_url+'img/borrar.png" width="18" onclick="quitarMaterialControl('+re+')"/></td>';
	data+='<td>'+material.codigoInterno+'</td>';
	data+='<td>'+material.nombre+'</td>';
	data+='<td>'+material.unidad+'</td>';
	data+='<td align="center"> <input type="text"  	name="txtCantidadControl'+re+'" id="txtCantidadControl'+re+'" class="cajas" style="width:100px;" onkeypress="return soloDecimales(event)"/></td>';
	data+='<input type="hidden"  name="txtIdMaterial'+re+'" id="txtIdMaterial'+re+'" value="'+material.idMaterial+'" />';
	data+='</tr>';
	
	$('#tablaSalidasControl').append(data);
	re++;
	
	$("#tablaSalidasControl tr:even").addClass("sombreado");
	$("#tablaSalidasControl tr:odd").addClass("sinSombra"); 
	$('#txtNumeroMateriales').val(re); 
	
	setTimeout(function(){$('#txtBuscarMateriaSalida').val('')},300);
}

function comprobarSalidas()
{
	b=false;
	
	for(i=0;i<re;i++)
	{
		if(obtenerNumero($('#txtIdMaterial'+i).val())>0)
		{
			b=true;
			
			if(obtenerNumero($('#txtCantidadControl'+i).val())==0)
			{
				b=false;
			}
		}
	}
	
	return b;
}

function registrarSalidaControl()
{
	if(!comprobarSalidas())
	{
		notify('Configure correctamente los materiales para las salidas',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoSalida').html('<img src="'+ img_loader +'"/> Registrando salida, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/registrarSalidaControl",
		data:$('#frmSalidaControl').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoSalida').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaSalidasControl').dialog('close');
					obtenerSalidasControl()
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoSalida').html('');
			notify('Error al registrar la la salida',500,5000,'error',30,3);	
		}
	});
}

function borrarSalidaControl(idSalida)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoControl').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/borrarSalidaControl",
		data:
		{
			"idSalida":		idSalida,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoControl').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerSalidasControl();
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoControl').html('');
			notify('Error al borrar la materia prima',500,5000,'error',30,3);	
		}
	});
}

//EDITAR SALIDA DE CONTROL
$(document).ready(function()
{
	$("#ventanaEditarSalidaControl").dialog(
	{
		autoOpen:false,
		height:500,
		width:850,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				editarSalidaControl()				  	  
			},
		},
		close: function() 
		{
			$("#obtenerSalidaControl").html('');
		}
	});
});
function obtenerSalidaControl(idSalida)
{
	$('#ventanaEditarSalidaControl').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSalidaControl').html('<img src="'+ img_loader +'"/> Obteniendo formulario de control...');
		},
		type:"POST",
		url:base_url+"materiales/obtenerSalidaControl",
		data:
		{
			"idSalida":idSalida,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSalidaControl').html(data);
			$('#txtComentarios').focus();
			re	= obtenerNumero($('#txtNumeroMateriales').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de control',500,5000,'error',30,3)
			$("#obtenerSalidaControl").html('');	
		}
	});
}

function editarSalidaControl()
{
	if(!comprobarSalidas())
	{
		notify('Configure correctamente los materiales para las salidas',500,6000,"error",30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoSalida').html('<img src="'+ img_loader +'"/> Editando salida, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/editarSalidaControl",
		data:$('#frmSalidaControl').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoSalida').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaEditarSalidaControl').dialog('close');
					obtenerSalidasControl()
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoSalida').html('');
			notify('Error al editar la la salida',500,5000,'error',30,3);	
		}
	});
}


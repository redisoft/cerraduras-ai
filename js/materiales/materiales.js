//=====================================================================================================//
//===================================FUNCIONES DE MATERIA PRIMA========================================//
//=====================================================================================================//

function obtenerMateriales()
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
			$('#obtenerMateriales').html('<img src="'+ img_loader +'"/> Obteniendo registro, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"materiales/obtenerMateriales",
		data:
		{
			"criterio":	$('#txtBusquedaMaterial').val(),
			"orden":	$('#txtOrdenMaterial').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMateriales').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener el registro',500,5000,'error',30,5)
			$("#obtenerMateriales").html('');	
		}
	});
}

function ordenMateriaPrima(orden)
{
	$('#txtOrdenMaterial').val(orden);
	obtenerMateriales();
}

function informacion(concepto,faltantes)
{
	notify('El registro '+concepto+' necesita una nueva compra de '+faltantes,500,5000,'error',40,20);
}

function formularioMateriaPrima()
{
	$('#ventanaMateriales').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioMateriaPrima').html('<img src="'+ img_loader +'"/> Obteniendo formulario, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"materiales/formularioMateriaPrima",
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioMateriaPrima').html(data);
			$('#txtBuscarCuentaContable').focus();
			
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario',500,5000,'error',30,3)
			$("#formularioMateriaPrima").html('');	
		}
	});
}

function obtenerConversiones()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConversiones').html('<img src="'+ img_loader +'"/> Obteniendo las conversiones, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"configuracion/obtenerConversionesProduccion",
		data:
		{
			"idUnidad":$('#txtUnidad').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConversiones').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener las conversiones',500,5000,'error',30,3)
			$("#obtenerConversiones").html('');	
		}
	});
}

$(document).ready(function()
{
	$("#ventanaMateriales").dialog(
	{
		autoOpen:false,
		height:550,
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
			'Guardar': function() 
			{
				registrarMateriaPrima()				  	  
			},
		},
		close: function() 
		{
			$("#formularioMateriaPrima").html('');
		}
	});
	
	$("#txtBusquedaMaterial").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerMateriales();
		}, milisegundos);
	});
	
	//$('.ajax-pagMateriales > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagMateriales > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerMateriales";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"criterio":	$('#txtBusquedaMaterial').val(),
				"orden":	$('#txtOrdenMaterial').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerMateriales').html('<img src="'+ img_loader +'"/>Obteniendo detalles..');
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

function registrarMateriaPrima()
{
	var mensaje	="";
	var T4		=$("#T4").val();
	
	if($("#txtMaterial").val()=="")
	{
		mensaje+="El nombre  es incorrecto <br />";										
	}
	
	if($("#txtUnidad").val()=="0")
	{
		mensaje+="Seleccione la unidad <br />";										
	}
	
	/*if($("#selectConversiones").val()=="0")
	{
		mensaje+="Seleccione la conversión <br />";										
	}*/
	
	if (!T4.match(RegExPatternX)) 
	{
		mensaje+="El costo  es incorrecto <br />";										
	}
	
	if($("#CMINIMA").val()=="")
	{
		mensaje+="La cantidad minima es incorrecta <br />";
	}
	
	if($("#txtIdProveedor").val()=="0")
	{
		mensaje+="Seleccione el proveedor <br />";
	}
	
	proveedor	= $("#txtIdProveedor").val();
	
	/*if($("#paginaActivada").val()=="compras")
	{
		proveedor	= $("#proveedores").val();
		
		if(proveedor==0)
		{
			mensaje+="Por favor seleccione un proveedor";
		}
	}
	else
	{
		if(proveedor==0)
		{
			mensaje+="Por favor seleccione un proveedor";
		}
	}*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	impuestos 	= new String($('#selectImpuestos').val());
	impuesto	= impuestos.split('|');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoMateriaPrima').html('<img src="'+ img_loader +'"/> Registrando, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/registrarMateriaPrima",
		data:
		{
			"T2":					$("#txtMaterial").val(),
			"idUnidad":				$("#txtUnidad").val(),
			"T4":					T4, 
			"T6":					proveedor,
			"stockMinimo":			$("#CMINIMA").val(),
			"codigoInterno":		$("#txtCodigoInterno").val(),
			"idConversion":			$("#selectConversiones").val(),
			"tipo":					"1",
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			"inventarioInicial":	$('#txtInventarioInicial').val(),
			"idSubCategoria":		$('#selectSubCategoria').val(),
			
			"idImpuesto":			impuesto[0],
			"precio":				$('#txtPrecioA').val(),
			"precioImpuestos":		$('#txtPrecioImpuestos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoMateriaPrima').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaMateriales').dialog('close');
					obtenerMateriales();
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoMateriaPrima').html('');
			notify('Error al registrar',500,5000,'error',30,3);	
		}
	});
}
/*--------------------------------EDITAR MATERIALES-------------------------------------*/

function obtenerMaterialEditar(idMaterial,idProveedor)
{
	$('#ventanEditarMaterial').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editaMaterial').html('<img src="'+ img_loader +'"/> Obteniendo los detalles del registro, tenga paciencia por favor...');
		},
		type:"POST",
		url:base_url+"materiales/editarMaterial/"+idMaterial+'/'+idProveedor,
		data:
		{
			//"idMaterial":idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editaMaterial').html(data);
			
		},
		error:function(datos)
		{
			$("#editaMaterial").html('Error al obtener el registro');	
		}
	});//Ajax	
}

$(document).ready(function()
{
	$("#ventanEditarMaterial").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				editarMaterial();			  	  
			},
		},
		close: function() 
		{
			$("#editaMaterial").html('');
		}
	});
});

function editarMaterial()
{
	var mensaje	="";

	if(!camposVacios($("#materiaPrima").val()))
	{
		mensaje+="El nombre  es incorrecto <br />";										
	}
	
	if ($("#costoMateria").val()=="0" || !camposVacios($("#costoMateria").val())) 
	{
		mensaje+="El costo  es incorrecto <br />";										
	}
	
	if(!comprobarNumeros($('#txtCantidadMinimaEditar').val())) 
	{
		mensaje+="La cantidad minima es incorrecta";										
	}
	
	if($("#txtUnidad").val()=="0")
	{
		mensaje+="Seleccione la unidad <br />";										
	}
	
	/*if($("#selectConversiones").val()=="0")
	{
		mensaje+="Seleccione la conversión <br />";										
	}*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;
	
	impuestos 	= new String($('#selectImpuestos').val());
	impuesto	= impuestos.split('|');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoMateriaPrima').html('<img src="'+ img_loader +'"/> Se esta editando el registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/confirmarEditar",
		data:
		{
			"nombre":				$("#materiaPrima").val(),
			"costo":				$("#costoMateria").val(), 
			"idMaterial":			$("#txtIdMaterial").val(),
			"codigoInterno":		$("#txtCodigoInternoEditar").val(),
			"stockMinimo":			$("#txtCantidadMinimaEditar").val(),
			"idUnidad":				$("#txtUnidad").val(),
			"idConversion":			$("#selectConversiones").val(),
			"idProveedor":			$("#txtIdProveedor").val(),
			"idProveedorPasado":	$("#txtIdProveedorPasado").val(),
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			"idSubCategoria":		$('#selectSubCategoria').val(),
			
			"idImpuesto":			impuesto[0],
			"precio":				$('#txtPrecioA').val(),
			"precioImpuestos":		$('#txtPrecioImpuestos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoMateriaPrima').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al editar el registro, el registro no tuvo cambios',500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro se ha editado correctamente',500,5000,'',30,5)
					$("#ventanEditarMaterial").dialog('close');
					obtenerMateriales();
				break;
			}//switch
		},
		error:function(datos)
		{
			$('#editandoMateriaPrima').html('');
			notify('Error al editar el registro',500,5000,'error',30,3);
		}
	});				
}

function borrarMaterial(idMaterial,idProveedor)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Borrando registro, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/borrarMaterial",
		data:
		{
			"idMaterial":		idMaterial,
			"idProveedor":		idProveedor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro porque esta asociado a compras, proveedores y/o productos',500,5000,'error',30,3);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					$('#filaMaterial'+idMaterial+'_'+idProveedor).remove();
				break;
			}
		},
		error:function(datos)
		{
			$('#exportandoDatos').html('');
			notify('Error al borrar el registro',500,5000,'error',30,3);	
		}
	});
}

function obtenerMermasMaterial(idMaterial,idProveedor)
{
	$('#ventanaMerma').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarMermas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de salidas, por favor espere...');
		},
		type:"POST",
		url:base_url+'materiales/obtenerMermas',
		data:
		{
			"idMaterial":	idMaterial,
			idProveedor:	idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarMermas').html(data)
		},
		error:function(datos)
		{
			$('#cargarMermas').html('')
			notify('Error al obtener las mermas',500,5000,'error',20,5);
		}
	});					  	  
}

$(document).ready(function()
{
	$("#ventanaMerma").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:850,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				registrarMerma();				  	  
			},
		},
		close: function() 
		{
			$("#cargarMermas").html('');
		}
	});
});

function registrarMerma()
{
	var mensaje="";

	if(!comprobarNumeros($('#txtCantidadMerma').val()) || $('#txtCantidadMerma').val()=="0" || parseFloat($('#txtCantidadMerma').val())<0 || parseFloat($('#txtCantidadMerma').val())>$('#txtTotalMaterial').val())
	{
		mensaje+='La cantidad de la salida es incorrecta <br />';
	}
	
	if($('#txtComentariosMerma').val()=="")
	{
		mensaje+='Por favor escriba los comentarios respecto a la salida';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',20,5);
		return;
	}
	
	if(confirm('¿Realmente desea registrar la salida?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoMermas').html('<img src="'+ img_loader +'"/> Se esta registrando la salida, espere por favor...');
		},
		type:"POST",
		url:base_url+"materiales/registrarMerma",
		data:
		{
			"cantidad":		$("#txtCantidadMerma").val(),
			"comentarios":	$('#txtComentariosMerma').val(),
			"idMaterial":	$('#txtIdMaterialMerma').val(),
			"idProveedor":	$('#txtIdProveedorMaterial').val(),
			"fecha":		$('#txtFechaMerma').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$('#agregandoMermas').html('');
				notify('Error al registrar la salida',500,5000,'error',20,5);
				break;
				
				case "1":
					location.reload();
				break;
				
				case "2":
				$('#agregandoMermas').html('');
				notify('El registro de la salida, supera la cantidad del material',500,5000,'error',20,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#agregandoMermas').html('');
			notify('Error al registrar la salida',500,5000,'error',20,5);
		}
	});	
}


//=======================================================================================================//
//==============================AGREGAR UN NUEVO PROVEEDOR MATERIAS======================================//
//=======================================================================================================//

function obtenerTodosProveedores(idMaterial)
{
	$('#ventanaAgregarProveedor').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTodosProveedores').html('<img src="'+ img_loader +'"/> Obteniendo lista de proveedores...');
		},
		type:"POST",
		url:base_url+'materiales/obtenerTodosProveedores',
		data:
		{
			"idMaterial":	idMaterial,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTodosProveedores').html(data)
		},
		error:function(datos)
		{
			$('#obtenerTodosProveedores').html('')
		}
	});
}

$(document).ready(function()
{
	$("#ventanaAgregarProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:700,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				asociarMaterialProveedor()
			}
		},
		close: function()
		{
			$("#obtenerTodosProveedores").html('');
		}
	});
})

function asociarMaterialProveedor()
{
	mensaje	= "";
	
	if($("#proveedoresMateriales").val()=="0")
	{
		mensaje+='Debe seleccionar un proveedor </br>';
	}
	
	if(!comprobarNumeros($("#txtCostoMaterial").val()) || parseFloat($("#txtCostoMaterial").val())<0)
	{
		mensaje+='El costo es incorrecto';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea agregar este proveedor?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#asociandoProveedor').html('<img src="'+ img_loader +'"/> Agregando el proveedor al material...');
		},
		type:"POST",
		url:base_url+'materiales/agregarProveedorMaterial',
		data:
		{
			"idProveedor":	$("#proveedoresMateriales").val(),
			"idMaterial": 	$("#txtIdMaterialAsociar").val(),
			"costo": 		$("#txtCostoMaterial").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#asociandoProveedor').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El proveedor se ha asociado correctamente',500,5000,'',30,5);
					$("#ventanaAgregarProveedor").dialog('close');
					obtenerMateriales();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			notify('Error al asociar el proveedor',500,5000,'error',30,5);
			$('#asociandoProveedor').html('');
		}
	});	
}
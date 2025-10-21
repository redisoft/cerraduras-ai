var RegExPatternX = new RegExp("[0123456789 -]");

function busquedaProveeedores(idProveedor)
{
	direccion=base_url+"proveedores/index/0/"+idProveedor;
	
	window.location.href=direccion;
}

function registrarContacto()
{
	var mensaje	= "";
	var T2		= $("#T2").val();

	if(!camposVacios($("#T1").val()))
	{
		mensaje+="El nombre es incorrecto<br />"
	}
	
	if (!camposVacios($("#T2").val())) 
	{
		mensaje+="El teléfono es incorrecto<br />";
	}
	
	if(!camposVacios($("#T3").val()))
	{
		mensaje+="El email es incorrecto<br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProveedor').html('<img src="'+ img_loader +'"/> Registrando el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarContactoProveedor",
		data:
		{
			"nombre":			$("#T1").val(),
			"telefono":			T2,
			"email":			$("#T3").val(),
			"departamento":		$("#T4").val(),
			"extension":		$("#extension").val(),
			"idProveedor":		$("#txtIdProveedor").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoProveedor").html("");
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					window.location.href=base_url+"proveedores/contactos/"+$("#txtIdProveedor").val();
				break;
			}
		},
		error:function(datos)
		{
			$("#registrandoProveedor").html('');
			notify('Error al registrar al contacto',500,5000,'error',30,5);
		}
	});
}

function formularioContactos()
{
	$('#ventanaRegistrarContacto').dialog('open');
}

$(document).ready(function()
{
	$("#txtBusquedaProveedor").autocomplete(
	{
		source:base_url+"configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{
			busquedaProveeedores(ui.item.idProveedor)
		}
	});

	$("#ventanaRegistrarContacto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$("#registrandoProveedor").html('');
				$(this).dialog('close');
			},
			'Guardar': function() 
			{
				registrarContacto();
			},
		},
		close: function() 
		{
			$("#registrandoProveedor").html('');
		}
	});

	$("#ventanaEditarContacto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$("#agregandoContacto").html(''); 
				$(this).dialog('close');				 
			},
			'Guardar': function() 
			{
				editarContacto();			  	  
			},
		},
		close: function() 
		{
			$("#obtenerContacto").html('');
		}
	});
});

function editarContacto()
{
	var mensaje	="";
	var T2		=$("#txtTelefono").val();

	if($("#txtNombre").val()=="")
	{
		mensaje+="Error en el nombre<br />"
	}
	
	if (!T2.match(RegExPatternX)) 
	{
		mensaje+="Error en el número telefonico<br />";										
	}
	
	if($("#txtEmail").val()=="")
	{
		mensaje+="Error en el E-mail<br />";
	}
	if($("#txtDepartamento").val()=="")
	{
		mensaje+="Error en departamento<br />";
	}					 					 
	if($("#txtExtension").val()=="")
	{
		mensaje+="Error en la extension";
	}					 					 
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea editar la información del contacto?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{	
			$('#editandoContacto').html('<img src="'+ img_loader +'"/> Se esta editando el contacto...');
		},
		type:"POST",
		url:base_url+"proveedores/editarContacto",
		data:
		{
			"nombre":		$("#txtNombre").val(),
			"telefono":		$("#txtTelefono").val(),
			"email":		$("#txtEmail").val(),
			"departamento":	$("#txtDepartamento").val(),
			"extension":	$("#txtExtension").val(),
			"idContacto":	$("#txtIdContacto").val(),
			"idProveedor":	$("#txtIdProveedor").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#editandoContacto").html('');
				notify('Error al editar al contacto',500,5000,'error',5,5);
				break;
				
				case "1":
				window.location.href=base_url+"proveedores/contactos/"+$("#txtIdProveedor").val();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar al contacto',500,5000,'error',5,5);
			$("#editandoContacto").html('');
		}
	});		
}

function obtenerContactoEditar(idContacto)
{
	$('#ventanaEditarContacto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerContacto').html('<img src="'+ img_loader +'"/> Obteniendo información del contacto...');
		},
		type:"POST",
		url:base_url+"proveedores/obtenerContacto",
		data:
		{
			"idContacto":idContacto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerContacto').html(data);
			
		},
		error:function(datos)
		{
			notify('Error al obtener la información del contacto',500,5000,'error',5,5);
			$("#obtenerContacto").html('');	
		}
	});//Ajax	
}

function imprimirMapa()
{
	mapa 				= document.getElementById('obtenerMapa');
	ventanaImprimir 	= window.open(' ', 'popimpr');
	
	ventanaImprimir.document.write( mapa.innerHTML );
	ventanaImprimir.document.close();
	ventanaImprimir.print( );
	ventanaImprimir.close();
}

proveedor=0;

function obtenerProveedor(idProveedor)
{
	$('#ventanaEditarProveedores').dialog('open');
	
	proveedor=idProveedor;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarProveedores').html('<img src="'+ img_loader +'"/> Obteniendo detalles del proveedor...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerProveedor',
		data:
		{
			"idProveedor":idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarProveedores').html(data);
		},
		error:function(datos)
		{
			$('#cargarProveedores').html('');
			notify('Error al obtener los detalles del proveedor',500,5000,'error',0,0);
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaEditarProveedores").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1010,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			
			'Recargar mapa': function() 
			{
				actualizarMapa();	  	  
			},
			'Guardar': function() 
			{
				editarProveedor()			  	  
			},
		},
		close: function() 
		{
			$("#cargarProveedores").html('');
		}
	});
	
	$("#ventanaMapaProveedor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1010,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				$(this).dialog('close');				 
			},
			
			Imprimir: function() 
			{
				imprimirMapa();				 
			},
		},
		close: function() 
		{
			$("#obtenerMapa").html('');
		}
	});
});

function obtenerMapa(idProveedor)
{
	$("#ventanaMapaProveedor").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMapa').html('<img src="'+ img_loader +'"/> Obteniendo el mapa del proveedor, por favor espere...');},
		type:"POST",
		url:base_url+"proveedores/obtenerMapa",
		data:
		{
			idProveedor:idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerMapa').html(data);
		},
		error:function(datos)
		{
			$('#obtenerMapa').html('');
		}
	});	
}

function editarProveedor()
{
	var mensaje="";

	if($('#empresa1').val()=="")
	{
		mensaje+='El nombre de la empresa es incorrecto <br />';
	}
	
	if($('#direccion1').val()=="")
	{
		mensaje+='La direccion es incorrecta <br />';
		
	}
	
	if($('#txtTelefono').val()=="")
	{
		mensaje+='El telefono es incorrecto <br />';
		
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!('¿Realmente desea editar al proveedor?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoEditarProveedores').html('<img src="'+ img_loader +'"/> Se esta editando al proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/editarProveedor",
		data:
		{
			"empresa":		$("#empresa1").val(),
			"pais":			$('#pais1').val(),
			"rfc":			$('#rfc1').val(),
			"domicilio":	$('#domicilio').val(),
			"numero":		$('#txtNumero').val(),
			"colonia":		$('#txtColonia').val(),
			"localidad":	$('#txtLocalidad').val(),
			"municipio":	$('#txtMunicipio').val(),
			"estado":		$('#estado').val(),
			"pais":			$('#pais').val(),
			"codigoPostal":	$('#txtCodigoPostal').val(),
			"email":		$('#email1').val(),
			"pagina":		$('#pagina1').val(),
			"idProveedor":	proveedor,
			"alias":		$('#txtAlias').val(),
			"vende":		$("#txtVende").val(),
			latitud:		$('#txtLatitud').val(),
			longitud:		$('#txtLongitud').val(),
			diasCredito:	$('#txtDiasCredito').val(),
			
			"telefono":		$('#txtTelefono').val(),
			"fax":			$('#txtFax').val(),
			"lada":			$('#txtLada').val(),
			"ladaFax":		$('#txtLadaFax').val(),
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			
			"saldoInicial":		$('#txtSaldoInicial').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
					$('#cargandoEditarProveedores').html('')
					notify('El registro del proveedor no tuvo cambio alguno',500,5000,'error',0,0);
				break;
				
				case "1":
					location.reload();
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoEditarProveedores').html('')
			notify('El registro del proveedor no tuvo cambio alguno',500,5000,'error',0,0);
		}
	});	
}

function imprimirFicha()
{
	mapa 				= document.getElementById('cargarFichaProveedor');
	ventanaImprimir 	= window.open(' ', 'popimpr');
	
	ventanaImprimir.document.write( mapa.innerHTML );
	ventanaImprimir.document.close();
	ventanaImprimir.print( );
	ventanaImprimir.close();
}

function formularioCuentas()
{
	$('#ventanaFormularioCuentas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCuentas').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cuentas...');
		},
		type:"POST",
		url:base_url+'proveedores/formularioCuentas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCuentas').html(data)
		},
		error:function(datos)
		{
			$('#formularioCuentas').html('');
			notify('Error al obtener el formulario de cuentas',500,5000,'error',0,0);
		}
	});		
}

function registrarCuenta()
{
	var mensaje="";

	if($('#selectBancos').val()=="0")
	{
		mensaje+='Seleccione el banco <br />';
	}
	
	if($('#txtCuenta').val()=="")
	{
		mensaje+='El número de cuenta es incorrecto <br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea registra la cuenta?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoCuenta').html('<img src="'+ img_loader +'"/> Se esta registrando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarCuenta",
		data:
		{
			"clabe":		$("#txtClabe").val(),
			"sucursal":		$('#txtSucursal').val(),
			"cuenta":		$('#txtCuenta').val(),
			"banco":		$('#txtBanco').val(),
			"idProveedor":	$('#txtIdProveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCuenta').html('')
			
			switch(data)
			{
				case "0":
				
				notify('Error al registra la cuenta',500,5000,'error',0,0);
				break;
				
				case "1":
				$('#ventanaFormularioCuentas').dialog('close');
				notify('La cuenta se ha registrado correctamente',500,5000,'',0,0);
				obtenerProveedor($('#txtIdProveedor').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#registrandoCuenta').html('')
			notify('Error al registra la cuenta',500,5000,'error',0,0);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioCuentas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:260,
		width:600,
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
				registrarCuenta()			  	  
			},
		},
		close: function() 
		{
			$("#formularioCuentas").html('');
		}
	});
});

function obtenerCuenta(idCuenta)
{
	$('#ventanaEditarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuenta').html('<img src="'+ img_loader +'"/> Obteniendo el detalle de cuenta...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerCuenta',
		data:
		{
			idCuenta:idCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuenta').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuenta').html('');
			notify('Error al obtener el detalle de la cuenta',500,5000,'error',0,0);
		}
	});		
}

function editarCuenta()
{
	var mensaje="";

	if($('#selectBancos').val()=="0")
	{
		mensaje+='Seleccione el banco <br />';
	}
	
	if($('#txtCuenta').val()=="")
	{
		mensaje+='El número de cuenta es incorrecto <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm('¿Realmente desea editar la cuenta?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCuenta').html('<img src="'+ img_loader +'"/> Se esta editando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/editarCuenta",
		data:
		{
			"clabe":		$("#txtClabe").val(),
			"sucursal":		$('#txtSucursal').val(),
			"cuenta":		$('#txtCuenta').val(),
			"banco":		$('#txtBanco').val(),
			"idCuenta":		$('#txtIdCuenta').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCuenta').html('')
			
			switch(data)
			{
				case "0":
				
				notify('Error al editar la cuenta',500,5000,'error',0,0);
				break;
				
				case "1":
				$('#ventanaEditarCuenta').dialog('close');
				notify('La cuenta se ha editado correctamente',500,5000,'',0,0);
				obtenerProveedor($('#txtIdProveedor').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCuenta').html('')
			notify('Error al editar la cuenta',500,5000,'error',0,0);
		}
	});	
}


function borrarCuenta(idCuenta)
{
	if(!confirm('¿Realmente desea borrar la cuenta?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoEditarProveedores').html('<img src="'+ img_loader +'"/> Se esta borrando la cuenta, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/borrarCuenta",
		data:
		{
			idCuenta:idCuenta
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoEditarProveedores').html('')
			
			switch(data)
			{
				case "0":
				
				notify('Error al borrar la cuenta',500,5000,'error',0,0);
				break;
				
				case "1":
				notify('La cuenta se ha borrado correctamente',500,5000,'',0,0);
				obtenerProveedor($('#txtIdProveedor').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoEditarProveedores').html('')
			notify('Error al borrar la cuenta',500,5000,'error',0,0);
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaEditarCuenta").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:260,
		width:600,
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
				editarCuenta()			  	  
			},
		},
		close: function() 
		{
			$("#formularioCuentas").html('');
		}
	});
});

function borrarInventarioProveedor(idProveedor)
{
	if(!confirm('¿Realmente desea borrar el inventario del proveedor?'))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Se esta borrando el inventario, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/borrarInventarioProveedor",
		data:
		{
			idProveedor:idProveedor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoDatos').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar los registros',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Los registros se han borrado correctamente',500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#exportandoDatos').html('')
			notify('Error al borrar los registros',500,5000,'error',30,5);
		}
	});	
}
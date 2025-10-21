
function checarIvaMateriaPrima()
{
	if($('#txtIdMaterial').val()!="0")
	{
		document.getElementById("chkIva").checked=true;
		document.getElementById("chkCajaChica").checked=false;
	}
	else
	{
		calcularTotalEgreso();
	}
}

function checarComprobarCaja()
{
	if($('#txtIdMaterial').val()!="0")
	{
		document.getElementById("chkCajaChica").checked=false;
		notify('El concepto de materia prima no puede ser caja chica',500,5000,'error',0,0);
	}
}

function checarIvaProducto()
{
	if($('#txtIdProducto').val()!="0")
	{
		document.getElementById("chkIva").checked=true;
	}
}

function sugerirPrecioMaterial(precio)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerImportes').html('<img src="'+ img_loader +'"/> Obteniendo el precio del material');
		},
		type:"POST",
		url:base_url+'administracion/sugerirPrecioMaterial',
		data:
		{
			precio	:precio,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerImportes').html(data);
			calcularTotalEgreso();
		},
		error:function(datos)
		{
			$('#obtenerImportes').html('');
		}
	});	
}

function sugerirPrecios(precioA,precioB,precioC,precioD,precioE)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerImportes').html('<img src="'+ img_loader +'"/> Obteniendo los precios');
		},
		type:"POST",
		url:base_url+'administracion/sugerirPrecios',
		data:
		{
			precioA	:precioA,
			precioB	:precioB,
			precioC	:precioC,
			precioD	:precioD,
			precioE	:precioE,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerImportes').html(data);
		},
		error:function(datos)
		{
			$('#obtenerImportes').html('');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaFormularioProveedores").dialog(
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
			'Guardar': function() 
			{
				registrarProveedor()		  	  
			},
			'Recargar mapa': function() 
			{
				actualizarMapaProveedor();	  	  
			},
		},
		close: function() 
		{
			$("#formularioProveedores").html('');
		}
	});
});

function actualizarMapaProveedor()
{
	$('#mapaProveedores').remove();  
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#recargarMapa').html('<img src="'+ img_loader +'"/> Actualizando el mapa...');
		},
		type:"POST",
		url:base_url+'proveedores/actualizarMapa',
		data:
		{
			calle:			$('#domicilio').val(),
			numero:			$('#txtNumero').val(),
			colonia:		$('#txtColonia').val(),
			localidad:		$('#txtLocalidad').val(),
			municipio:		$('#txtMunicipio').val(),
			estado:			$('#estado').val(),
			pais:			$('#pais').val(),
			codigoPostal:	$('#txtCodigoPostal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#recargarMapa').html(data);
		},
		error:function(datos)
		{
			$('#recargarMapa').html('');
		}
	});		
}

function registrarProveedor()
{
	var mensaje="";

	if($('#empresa').val()=="")
	{
		mensaje+='El nombre de la empresa es incorrecto <br />';
	}
	
	if($('#domicilio').val()=="")
	{
		mensaje+='El domicilio es incorrecto <br />';
	}
	
	if($('#telefono').val()=="")
	{
		mensaje+='El telefono es incorrecto <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(confirm('¿Realmente desea registrar al proveedor?')==false)
	{
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoProveedor').html('<img src="'+ img_loader +'"/> Se esta registrando el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/agregarProveedor",
		data:
		{
			"empresa":			$("#empresa").val(),
			"rfc":				$('#rfc').val(),
			
			"domicilio":		$('#domicilio').val(),
			"numero":			$('#txtNumero').val(),
			"colonia":			$('#txtColonia').val(),
			
			"localidad":		$('#txtLocalidad').val(),
			"municipio":		$('#txtMunicipio').val(),
			"estado":			$('#estado').val(),
			"pais":				$('#pais').val(),
			"codigoPostal":		$('#txtCodigoPostal').val(),
			
			
			"telefono":			$('#telefono').val(),
			"email":			$('#email').val(),
			"pagina":			$('#pagina').val(),
			
			"idBanco":			$('#selectBancos').val(),
			"cuenta":			$('#txtCuenta').val(),
			"clabe":			$('#txtClabe').val(),
			
			
			"fax":				$('#txtFax').val(),
			
			"alias":			$('#txtAlias').val(),
			"nombreContacto":	$('#txtNombreContacto').val(),
			"telefonoContacto":	$('#txtTelefonoContacto').val(),
			"emailContacto":	$('#txtEmailContacto').val(),


		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoProveedor').html('')
			
			switch(data)
			{
				case "0":
				notify('Error al registrar al proveedor',500,5000,'error',0,0);
				break;
				case "1":
				notify('El proveedor se ha registrado correctamente',500,5000,'error',0,0);
				$("#ventanaFormularioProveedores").dialog('close'); 
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoProveedor').html('')
			notify('Error al registrar al proveedor',500,5000,'error',0,0);
		}
	});		
}

function formularioProveedores()
{
	$("#ventanaFormularioProveedores").dialog('open'); 
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProveedores').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para proveedores...');
		},
		type:"POST",
		url:base_url+'proveedores/formularioProveedores',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProveedores').html(data);
		},
		error:function(datos)
		{
			$('#formularioProveedores').html('');
		}
	});		
}





$(document).ready(function()
{
	$("#ventanaFormularioClientes").dialog(
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
			'Aceptar': function() 
			{
				registrarCliente()		  	  
			},
			'Recargar mapa': function() 
			{
				actualizarMapa();
			},
		},
		close: function() 
		{
			$("#formularioClientes").html('');
		}
	});
});

function formularioClientes()
{
	$("#ventanaFormularioClientes").dialog('open'); 
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioClientes').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para clientes...');
		},
		type:"POST",
		url:base_url+'clientes/formularioClientes',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioClientes').html(data);
		},
		error:function(datos)
		{
			$('#formularioClientes').html('');
		}
	});		
}

function registrarCliente()
{
	var mensaje	="";
	prospecto	=1;
	
	if(document.getElementById('esCliente').checked==true)
	{
		prospecto=0;
	}
	
	if($('#empresa').val()=="")
	{
		mensaje+='El nombre de la empresa es incorrecto <br />';
	}
	
	if($('#direccion').val()=="")
	{
		mensaje+='La dirección es incorrecta <br />';
	}
	
	if($('#telefono').val()=="")
	{
		mensaje+='El teléfono es incorrecto <br />';
	}
	
	if($('#zona').val()=="0")
	{
		mensaje+='Por favor seleccione '+$('#txtIdentificador').val()+' <br />';
	}
	
	if(parseInt($('#limiteCredito').val())<0)
	{
		mensaje+='Los días de crédito son incorrectos <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',5,5);
		return;
	}
	
	if(confirm('¿Realmente desea registrar el cliente?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoCliente').html('<img src="'+ img_loader +'"/> Se esta registrando el cliente, por favor espere...');
		},
		type:"POST",
		url:base_url+"clientes/agregarCliente",
		data:
		{
			"empresa":			$("#empresa").val(),
			"estado":			$('#estado').val(),
			"localidad":		$('#localidad').val(),
			"rfc":				$('#rfc').val(),
			"direccion":		$('#direccion').val(),
			"numero":			$('#numero').val(),
			"colonia":			$('#colonia').val(),
			"codigoPostal":		$('#codigoPostal').val(),
			"pais":				$('#txtPais').val(),
			"telefono":			$('#telefono').val(),
			"fax":				$('#fax').val(),
			"email":			$('#email').val(),
			"pagina":			$('#pagina').val(),
			"pagina2":			$('#pagina2').val(),
			"pagina3":			$('#pagina3').val(),
			"direccionEnvio":	$('#direccionEnvio').val(),
			"codigoPostalEnvio":$('#codigoPostalEnvio').val(),
			"estadoEnvio":		$('#estadoEnvio').val(),
			"ciudadEnvio":		$('#ciudadEnvio').val(),
			"proveedoraso":		$('#proveedoraso').val(),
			"idZona":			$('#zona').val(),
			"esCliente":		prospecto,
			"precio":			$('#txtPrecioCliente').val(),
			"municipio":		$('#txtMunicipio').val(),
			
			"nombreVendedor":	$('#nombreVendedor').val(),
			"limiteCredito":	$('#limiteCredito').val(),
			"plazos":			$('#plazos').val(),
			
			"nombreContacto":	$('#txtNombreContacto').val(),
			"emailContacto":	$('#txtEmailContacto').val(),
			"grupo":			$('#txtGrupo').val(),
			"alias":			$('#txtAlias').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoCliente').html('')
			
			switch(data)
			{
				case "0":
				notify('Error al registrar al cliente',500,5000,'error',5,5);
				
				break;
				
				case "1":
				notify('El cliente se ha registrado correctamente',500,5000,'',5,5);
				$('#ventanaFormularioClientes').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al cliente',500,5000,'error',5,5);
			$('#registrandoCliente').html('')
		}
	});	
}

function copiarDireccion()
{
	if(document.getElementById('chkConfirmar').checked==true)
	{
		$('#direccionEnvio').val($('#direccion').val());
		$('#ciudadEnvio').val($('#localidad').val());
		$('#codigoPostalEnvio').val($('#codigoPostal').val());
		$('#estadoEnvio').val($('#estado').val());
	}
	else
	{
		$('#direccionEnvio').val('');
		$('#ciudadEnvio').val('');
		$('#codigoPostalEnvio').val('');
		$('#estadoEnvio').val('');
	}
}

function actualizarMapa()
{
	$('#mapaClientes').remove();  
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#recargarMapa').html('<img src="'+ img_loader +'"/> Actualizando el mapa...');
		},
		type:"POST",
		url:base_url+'clientes/actualizarMapa',
		data:
		{
			calle:			$('#direccion').val(),
			numero:			$('#numero').val(),
			
			colonia:		$('#colonia').val(),
			localidad:		$('#localidad').val(),
			municipio:		$('#txtMunicipio').val(),
			estado:			$('#estado').val(),
			pais:			$('#txtPais').val(),
			codigoPostal:	$('#codigoPostal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#recargarMapa').html(data);
		},
		error:function(datos)
		{
			$('#recargarMapa').html('');
		}
	});		
}



function obtenerOtrosIngresos()
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
			$('#obtenerOtrosIngresos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de otros ingresos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerOtrosIngresos',
		data:
		{
			criterio:		$('#txtBuscarIngreso').val(),
			inicio:			$('#txtInicioIngresoFecha').val(),
			fin:			$('#txtFinIngresoFecha').val(),
			idCuenta:		$('#selectCuentaIngresos').val(),
			
			idProducto:		$('#selectProductosBusqueda').val(),
			idDepartamento:	$('#selectDepartamentosBusqueda').val(),
			idGasto:		$('#selectGastosBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerOtrosIngresos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de otros ingresos',500,5000,'error',2,5);
			$('#obtenerOtrosIngresos').html('');
		}
	});					  	  
}


function formularioOtrosIngresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioIngresos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de otros ingresos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioOtrosIngresos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioIngresos').html(data);
			window.setTimeout("catalogos()",1000);
			$('#txtBuscarVenta').focus()
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de otros ingresos',500,5000,'error',2,5);
			$('#formularioIngresos').html('');
		}
	});					  	  
}



//REGISTRAR INGRESO
function registrarIngreso()
{
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	importe	= $('#txtImporte').val();
	importe	=importe.replace(',','');
	importe	=importe.replace(',','');
	
	if(importe=="0" || Solo_Numerico(importe)=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	if(Solo_Numerico($('#txtCantidad').val())=="" || $('#txtCantidad').val()=="0")
	{
		mensaje+="La cantidad es incorrecta <br />";
	}
	
	if($('#txtIdVenta').val()!="0")
	{
		if(obtenerNumeros($('#txtTotal').val()) > obtenerNumeros($('#txtSaldoVenta').val()))
		{
			mensaje+="El total del ingreso es mayor al saldo <br />";
		}
		
		if(obtenerNumeros($('#selectIva').val()) != obtenerNumeros($('#txtTasaImpuestoVenta').val()))
		{
			mensaje+="El impuesto del ingreso no es correcto <br />";
		}
	}
	
	if($('#selectFormas').val()!="4")
	{
		if($('#selectBancos').val()=="0")
		{
			mensaje+="Por favor seleccione el banco <br />";
		}
		
		if($('#selectCuentas').val()=="0")
		{
			mensaje+="Por favor seleccione la cuenta <br />";
		}
	}

	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#selectTipoPago').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		idNombre	=$('#selectNombres').val();
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		/*if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione a quien se la pagara el documento <br />";
		}*/
	}

	if($('#selectTipoPago').val()=="3")
	{
		$('#txtNumeroCheque').val('');
		idNombre	=0;
		
		/*if($('#txtNumeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}*/
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el ingreso?')) return;
	
	var formData = new FormData($('#frmIngresos')[0]);
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#agregandoIngresos').html('<img src="'+ img_loader +'"/> Registrando el ingreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarIngreso',
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		/*data:
		{
			idProducto:      $('#txtConcepto').val(),
			pago:			importe,
			iva:			$('#txtIva').val(),
			idDepartamento:	$('#selectDepartamento').val(),
			idNombre:		idNombre,
			producto:		$('#txtDescripcionProducto').val(),
			idGasto:		$('#selectTipoGasto').val(),
			idForma:		$('#selectFormas').val(),
			cheque:			$('#txtNumeroCheque').val(),
			transferencia:	$('#txtNumeroTransferencia').val(),
			idCuenta:		$('#cuentasBanco').val(),
			incluyeIva:		document.getElementById('chkIva').checked==true?1:0,
			nombreReceptor:	$('#txtNombreReceptor').val(),
			fecha:			$('#txtFechaIngreso').val(),
			comentarios:	$('#txtComentarios').val(),
			idCliente:		$('#txtIdCliente').val(),
			factura:		$('#txtFactura').val(),
			
			cantidad:			$('#txtCantidad').val(),
			nombreProducto:		$('#txtDescripcionProducto').val(),
			idProductoCatalogo:	$('#txtIdProducto').val(),
			idPeriodo:			document.getElementById('txtServicio').value=="0"?0:$('#txtIdPeriodo').val(),
			
			repetir:			$('#txtRepetir').val(),
			idPeriodoRepetir:	$('#selectFormas').val()=='4'?$('#selectPeriodos').val():0,
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoIngresos').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
			
					$('#ventanaFormularioIngresos').dialog('close');
					window.setTimeout("obtenerOtrosIngresos()",1000)
					reporteBancos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el ingreso',500,5000,'error',0,0);
			$('#agregandoIngresos').html('');
		}
	});					  	  
}

//EDITAR LOS OTROS INGRESOS
function obtenerIngresoEditar(idIngreso)
{
	$("#ventanaEditarIngresos").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerIngresoEditar').html('<img src="'+ img_loader +'"/> Obteniendo el registro de ingreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerIngresoEditar',
		data:
		{
			idIngreso:idIngreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerIngresoEditar').html(data);
			cu=parseInt($('#txtNumeroCuentas').val());
			$('#txtBuscarCliente').focus()
		},
		error:function(datos)
		{
			notify('Error al obtener el registro de ingreso',500,5000,'error',2,5);
			$('#obtenerIngresoEditar').html('');
		}
	});					  	  
}

function editarOtroIngreso()
{
	mensaje		="";
	idNombre	=0;
	
	if($('#txtFechaIngreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	importe	=$('#txtImporte').val();
	importe	=importe.replace(',','');
	importe	=importe.replace(',','');
	
	if(importe=="0" || Solo_Numerico(importe)=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	//$('#txtIdProducto').val()!="0" &&
	if( $('#selectFormas').val()!="4")
	{
		if($('#selectBancos').val()=="0")
		{
			mensaje+="Por favor seleccione el banco <br />";
		}
		
		if($('#selectCuentas').val()=="0")
		{
			mensaje+="Por favor seleccione la cuenta <br />";
		}
	}

	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		
		idNombre	=0;
	}
	
	if($('#selectFormas').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		/*if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione a quien se le pagara el documento <br />";
		}*/
		
		idNombre=$('#selectNombres').val();
	}

	if($('#selectFormas').val()=="3")
	{
		$('#txtNumeroCheque').val('');
		
		/*if($('#txtNumeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}*/
		
		idNombre	=0;
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',0,0);
		return;
	}
	
	if(confirm("¿Realmente desea editar el ingreso?")==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoIngresos').html('<img src="'+ img_loader +'"/> Editando el ingreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/editarIngreso',
		data:$('#frmIngresos').serialize(),
		/*data:
		{
			concepto:       $('#txtConcepto').val(),

			subTotal:		$('#txtImporte').val(),
			ivaTotal:		$('#txtTotalIva').val(),
			pago:			$('#txtTotal').val(),
			iva:			$('#selectIva').val(),
			incluyeIva:		parseFloat($('#selectIva').val())>0?'1':'0',
			
			idDepartamento:	$('#selectDepartamento').val(),
			idNombre:		idNombre,
			producto:		$('#selectProductos').val(),
			idGasto:		$('#selectTipoGasto').val(),
			idForma:		$('#selectFormas').val(),
			cheque:			$('#txtNumeroCheque').val(),
			transferencia:	$('#txtNumeroTransferencia').val(),
			idCuenta:		$('#cuentasBanco').val(),
			
			nombreReceptor:	$('#txtNombreReceptor').val(),
			idIngreso:		$('#txtIdIngreso').val(),
			fecha:			$('#txtFechaIngreso').val(),
			comentarios:	$('#txtComentarios').val(),
			idCliente:		$('#txtIdCliente').val(),
			factura:		$('#txtFactura').val(),
			
			cantidad:		$('#txtCantidad').val(),
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('Registro correcto',500,5000,'',0,0);
			$('#editandoIngresos').html('');
			$('#ventanaEditarIngresos').dialog('close');
			window.setTimeout("obtenerOtrosIngresos()",1000);
			reporteBancos();
		},
		error:function(datos)
		{
			notify('Error al editar el ingreso',500,5000,'error',0,0);
			$('#editandoIngresos').html('');
		}
	});					  	  
}

//BORRAR LOS OTROS INGRESOS
function borrarIngreso(idIngreso)
{
	if(confirm('¿Realmente desea borrar el ingreso?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoIngresos').html('<img src="'+ img_loader +'"/> Se esta borrando el ingreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/borrarIngreso',
		data:
		{
			idIngreso:idIngreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			window.setTimeout("obtenerOtrosIngresos()",1000);
			notify('Ingreso borrado',500,5000,'',0,0);
			$('#cargandoIngresos').html('')
		},
		error:function(datos)
		{
			notify('Error al obtener el registro de ingreso',500,5000,'error',2,5);
			$('#cargandoIngresos').html('');
		}
	});					  	  
}

function formularioListaIngresos()
{
	obtenerOtrosIngresos();
	$('#ventanaOtrosIngresos').dialog('open');
}

$(document).ready(function()
{
	//reporteBancos(); //Cargar automaticamente el reporte de bancos
	
	//$('.ajax-pagIng > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagIng > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerOtrosIngresos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtBuscarIngreso').val(),
				inicio:		$('#txtInicioIngresoFecha').val(),
				fin:		$('#txtFinIngresoFecha').val(),
				idCuenta:	$('#selectCuentaIngresos').val(),
				
				idProducto:		$('#selectProductosBusqueda').val(),
				idDepartamento:	$('#selectDepartamentosBusqueda').val(),
				idGasto:		$('#selectGastosBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo la lista de ingresos...');},
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
	
	//$('.ajax-pagEgr > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagEgr > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerOtrosEgresos";
		var link 			= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtBuscarEgreso').val(),
				inicio:			$('#txtInicioEgresoFecha').val(),
				fin:			$('#txtFinEgresoFecha').val(),
				idCuenta:		$('#selectCuentaEgresos').val(),
				
				idNivel1:		$('#selectNiveles1Busqueda').val(),
				idNivel2:		$('#selectNiveles2Busqueda').val(),
				idNivel3:		$('#selectNiveles3Busqueda').val(),
				idPersonal:		$('#selectPersonalBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo la lista de egresos...');},
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
	
	//$('.ajax-pagTra > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagTra > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerTraspasos";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{

			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Obteniendo la lista de traspasos...');},
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

	$("#ventanaOtrosIngresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:640,
		width:1270,
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
				if($('#txtPermisoRegistro').val()=="1")
				{
					formularioOtrosIngresos();
					$('#ventanaFormularioIngresos').dialog('open');
				}
				else
				{
					notify('Sin permisos para registrar',500,5000,'error',30,5);
				}
				
			},
		},
		close: function() 
		{
			$("#obtenerOtrosIngresos").html('');
		}
	});
	
	//FORMULARIO PARA AGREGAR LOS INGRESOS
	$("#ventanaFormularioIngresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:900,
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
				registrarIngreso();
			},
		},
		close: function() 
		{
			$("#formularioIngresos").html('');
		}
	});
	
	//FORMULARIO PARA EDITAR LOS INGRESOS
	$("#ventanaEditarIngresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:900,
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
				editarOtroIngreso();
			},
		},
		close: function() 
		{
			$("#obtenerIngresoEditar").html('');
		}
	});
	
	$("#ventanaFormularioDepartamentos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:650,
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
				registrarDepartamento();
			},
		},
		close: function() 
		{
			$("#formularioDepartamentos").html('');
		}
	});
	
	$("#ventanaFormularioNombres").dialog(
	{
		autoOpen:false,
		height:200,
		width:650,
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
				registrarNombre();
			},
		},
		close: function() 
		{
			$("#formularioNombres").html('');
		}
	});
	
	$("#ventanaFormularioProductos").dialog(
	{
		autoOpen:false,
		height:200,
		width:650,
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
				registrarProducto();
			},
		},
		close: function() 
		{
			$("#formularioProductos").html('');
		}
	});
	
	$("#ventanaFormularioGastos").dialog(
	{
		autoOpen:false,
		height:200,
		width:650,
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
				registrarTipoGasto();
			},
		},
		close: function() 
		{
			$("#formularioGastos").html('');
		}
	});
	
});

function opcionesCuenta()
{
	if($('#selectTipoPago').val()=="Efectivo" || $('#selectTipoPago').val()=="Tarjeta de crédito" || $('#selectTipoPago').val()=="Tarjeta débito")
	{
		$('#filaCheques').fadeOut();
		$('#filaTransferencia').fadeOut();
		$('#filaNombre').fadeOut();
		$('#contenedorNombres').fadeOut();
		$('#filaPeriodicidad').fadeOut();
	}
	
	if($('#selectTipoPago').val()=="Programado")
	{
		$('#filaPeriodicidad').fadeIn();
		$('#filaCheques').fadeOut();
		$('#filaTransferencia').fadeOut();
		$('#filaNombre').fadeOut();
		$('#contenedorNombres').fadeOut();
	}

	if($('#selectTipoPago').val()=="Cheque")
	{
		$('#filaCheques').fadeIn();
		$('#filaTransferencia').fadeOut();
		$('#filaNombre').fadeIn();
		$('#contenedorNombres').fadeIn();
		$('#filaPeriodicidad').fadeOut();
	}
	
	if($('#selectTipoPago').val()=="Transferencia")
	{
		$('#filaCheques').fadeOut();
		$('#filaTransferencia').fadeIn();
		$('#filaNombre').fadeIn();
		$('#contenedorNombres').fadeOut();
		$('#filaPeriodicidad').fadeOut();
	}
}

//OTROS EGRESOS
//-------------------------------------------------------------------------------------------------------------------

function obtenerOtrosEgresos()
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
			$('#obtenerOtrosEgresos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de otros ingresos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerOtrosEgresos',
		data:
		{
			criterio:		$('#txtBuscarEgreso').val(),
			inicio:			$('#txtInicioEgresoFecha').val(),
			fin:			$('#txtFinEgresoFecha').val(),
			idCuenta:		$('#selectCuentaEgresos').val(),
			
			idNivel1:		$('#selectNiveles1Busqueda').val(),
			idNivel2:		$('#selectNiveles2Busqueda').val(),
			idNivel3:		$('#selectNiveles3Busqueda').val(),
			idPersonal:		$('#selectPersonalBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerOtrosEgresos').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de otros egresos',500,5000,'error',2,5);
			$('#obtenerOtrosEgresos').html('');
		}
	});					  	  
}

function formularioOtrosEgresos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEgresos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de otros egresos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/formularioOtrosEgresos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEgresos').html(data);
			//window.setTimeout("catalogos()",1000);
			$('#txtBuscarCompra').focus();
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de otros egresos',500,5000,'error',2,5);
			$('#formularioEgresos').html('');
		}
	});					  	  
}

function calcularTotalEgreso()
{
	importe	=parseFloat($('#txtImporte').val());
	
	if(Solo_Numerico(importe)=="" || importe=="0")
	{
		document.getElementById('chkIva').checked=false;
		$('#txtImporte').val('');
		notify('El importe es incorrecto',500,6000,'error',0,0);
		return;
	}
	
	iva		= parseFloat($('#txtIvaPorcentaje').val())/100;
	
	if(document.getElementById('chkIva').checked)
	{
		iva		= importe*iva;
		$('#txtImporte').val(redondeo2decimales(importe+iva));
		return;
	}
	
	if(!document.getElementById('chkIva').checked)
	{
		$('#txtImporte').val(redondeo2decimales(importe/(1+iva)))
		return;
	}
}

function registrarEgreso()
{
	mensaje		="";
	idNombre	=0
	
	if($('#txtFechaEgreso').val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	importe	=$('#txtImporte').val();
	importe	=importe.replace(',','');
	importe	=importe.replace(',','');
	
	if(importe=="0" || Solo_Numerico(importe)=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	if(!camposVacios($('#txtDescripcionProducto').val()))
	{
		mensaje+="La descripción del producto es incorrecta <br />";
	}
	
	if(Solo_Numerico($('#txtCantidad').val())=="" || $('#txtCantidad').val()=="0")
	{
		mensaje+="La cantidad es incorrecta <br />";
	}
	
	if($('#txtIdCompra').val()!="0")
	{
		if(obtenerNumeros($('#txtTotal').val()) > obtenerNumeros($('#txtSaldoCompra').val()))
		{
			mensaje+="El total del ingreso es mayor al saldo <br />";
		}
		
		if(obtenerNumeros($('#selectIva').val()) != obtenerNumeros($('#txtTasaImpuestoCompra').val()))
		{
			mensaje+="El impuesto del egreso no es correcto <br />";
		}
	}
	
	
	if($('#txtIdMaterial').val()!="0" && $('#txtIdProveedor').val()=="0")
	{
		mensaje+="Debe seleccionar un proveedor <br />";
	}
	
	if($('#txtIdMaterial').val()!="0" && $('#selectFormas').val()!="4")
	{
		if($('#selectBancos').val()=="0")
		{
			mensaje+="Por favor seleccione el banco <br />";
		}
		
		if($('#selectCuentas').val()=="0")
		{
			mensaje+="Por favor seleccione la cuenta <br />";
		}
	}
	
	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}


	if($('#selectFormas').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		/*if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione a quien se le pagara el documento <br />";
		}*/
		
		idNombre	=$('#selectNombres').val();
	}

	if($('#selectFormas').val()=="3")
	{
		$('#txtNumeroCheque').val('');
		
		/*if($('#txtNumeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}*/
		
		idNombre=0;
	}

	/*dato= document.getElementById('archivoEgreso');
	alert(dato.value);*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}

	if(!confirm("¿Realmente desea registrar el egreso?")) return

	var formData = new FormData($('#frmEgresos')[0]);
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoEgresos').html('<img src="'+ img_loader +'"/> Registrando el egreso, por favor espere...');
		},
		url:base_url+'produccion/registrarEgreso',
		data: formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
		type:"POST",
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoEgresos').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
				
					notify(data[1],500,5000,'',30,5);
					
					$('#ventanaFormularioEgresos').dialog('close');
					window.setTimeout("obtenerOtrosEgresos()",1000);
					reporteBancos();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el egreso',500,5000,'error',0,0);
			$('#agregandoEgresos').html('');
		}
	});					  	  
}

function editarEgreso()
{
	mensaje		="";
	idNombre	=0;
	
	importe	=$('#txtImporte').val();
	importe	=importe.replace(',','');
	importe	=importe.replace(',','');
	
	if(importe=="0" || Solo_Numerico(importe)=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}
	
	if(Solo_Numerico($('#txtCantidad').val())=="" || $('#txtCantidad').val()=="0")
	{
		mensaje+="La cantidad es incorrecta <br />";
	}
	
	if($('#txtIdMaterial').val()!="0" && $('#selectFormas').val()!="4")
	{
		if($('#selectBancos').val()=="0")
		{
			mensaje+="Por favor seleccione el banco <br />";
		}
		
		if($('#selectCuentas').val()=="0")
		{
			mensaje+="Por favor seleccione la cuenta <br />";
		}
	}

	if($('#selectFormas').val()!="3" && $('#selectFormas').val()!="2")
	{
		$('#txtNumeroTransferencia').val('');
		$('#txtNumeroCheque').val('');
		$('#txtNombreReceptor').val('');
		idNombre	=0;
	}
	
	if($('#selectFormas').val()=="2")
	{
		$('#txtNumeroTransferencia').val('');
		
		if($('#txtNumeroCheque').val()=="")
		{
			mensaje+="Número de cheque invalido <br />";
		}
		
		/*if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}
		
		if($('#selectNombres').val()=="0")
		{
			mensaje+="Seleccione a quien se le pagara el documento <br />";
		}*/
		
		idNombre	=$('#selectNombres').val();
	}

	if($('#selectFormas').val()=="3")
	{
		$('#txtNumeroCheque').val('');
		
		/*if($('#txtNumeroTransferencia').val()=="")
		{
			mensaje+="Número de transferencia es invalido <br />";
		}
		
		if($('#txtNombreReceptor').val()=="")
		{
			mensaje+="El nombre del receptor es incorrecto <br />";
		}*/
		
		idNombre=0;
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',0,0);
		return;
	}
	
	if(confirm("¿Realmente desea editar el egreso?")==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoEgresos').html('<img src="'+ img_loader +'"/> Editando el egreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/editarEgreso',
		data:$('#frmEgresos').serialize()
		/*{
			concepto:       $('#txtConcepto').val(),
			//pago:			importe,
			
			subTotal:		$('#txtImporte').val(),
			ivaTotal:		$('#txtTotalIva').val(),
			pago:			$('#txtTotal').val(),
			iva:			$('#selectIva').val(),
			incluyeIva:		parseFloat($('#selectIva').val())>0?'1':'0',
			
			
			//iva:			$('#txtIva').val(),
			idDepartamento:	$('#selectDepartamento').val(),
			idNombre:		idNombre,
			idProducto:		$('#selectProductos').val(),
			idGasto:		$('#selectTipoGasto').val(),
			idForma:		$('#selectFormas').val(),
			cheque:			$('#txtNumeroCheque').val(),
			transferencia:	$('#txtNumeroTransferencia').val(),
			idCuenta:		$('#cuentasBanco').val(),
			//incluyeIva:		document.getElementById('chkIva').checked,
			nombreReceptor:	$('#txtNombreReceptor').val(),
			idEgreso:		$('#txtIdEgreso').val(),
			fecha:			$('#txtFechaEgreso').val(),
			comentarios:	$('#txtComentarios').val(),
			idProveedor:	$('#txtIdProveedor').val(),
			factura:		$('#txtFactura').val(),
			remision:		$('#txtRemision').val(),
			cantidad:		$('#txtCantidad').val(),
		}*/,
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('Registro correcto',500,5000,'',0,0);
			$('#editandoEgresos').html('');
			$('#ventanaEditarEgresos').dialog('close');
			window.setTimeout("obtenerOtrosEgresos()",1000)
			reporteBancos();
		},
		error:function(datos)
		{
			notify('Error al editar el egreso',500,5000,'error',0,0);
			$('#editandoEgresos').html('');
		}
	});					  	  
}

//EDITAR LOS OTROS INGRESOS
function obtenerEgresoEditar(idEgreso)
{
	$("#ventanaEditarEgresos").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEgresoEditar').html('<img src="'+ img_loader +'"/> Obteniendo el registro de egreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/obtenerEgresoEditar',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerEgresoEditar').html(data)
			cu=parseInt($('#txtNumeroCuentas').val());
		},
		error:function(datos)
		{
			notify('Error al obtener el registro de egreso',500,5000,'error',2,5);
			$('#obtenerEgresoEditar').html('');
		}
	});					  	  
}

//BORRAR LOS OTROS EGRESOS
function borrarEgreso(idEgreso)
{
	if(confirm('¿Realmente desea borrar el egreso?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoEgresos').html('<img src="'+ img_loader +'"/> Se esta borrando el egreso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/borrarEgreso',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{

			window.setTimeout("obtenerOtrosEgresos()",1000);
			notify('Egreso borrado',500,5000,'',0,0);
			$('#cargandoEgresos').html('')
		},
		error:function(datos)
		{
			notify('Error al borrar el registro del egreso',500,5000,'error',30,5);
			$('#cargandoEgresos').html('');
		}
	});					  	  
}

function formularioListaEgresos()
{
	obtenerOtrosEgresos();
	$('#ventanaOtrosEgresos').dialog('open');
}

$(document).ready(function()
{
	$("#ventanaOtrosEgresos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1270,
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
				if($('#txtPermisoRegistro').val()=="1")
				{
					formularioOtrosEgresos();
					$('#ventanaFormularioEgresos').dialog('open');
				}
				else
				{
					notify('Sin permisos para registrar',500,5000,'error',30,5);
				}
			},
		},
		close: function() 
		{
			$("#obtenerOtrosEgresos").html('');
		}
	});
	
	//FORMULARIO PARA EGRESOS
	$("#ventanaFormularioEgresos").dialog(
	{
		autoOpen:false,
		height:630,
		width:900,
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
				registrarEgreso();
			},
		},
		close: function() 
		{
			$("#formularioEgresos").html('');
		}
	});
	
	//FORMULARIO PARA EDITAR EGRESOS
	$("#ventanaEditarEgresos").dialog(
	{
		autoOpen:false,
		height:630,
		width:900,
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
				editarEgreso();
			},
		},
		close: function() 
		{
			$("#obtenerEgresoEditar").html('');
		}
	});
	//TRASPASOS

	
	$("#ventanaTraspasos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:950,
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
				if($('#txtPermisoRegistro').val()=="1")
				{
					formularioTraspasos();
					$('#ventanaFormularioTraspasos').dialog('open');
				}
				else
				{
					notify('Sin permisos para registrar',500,5000,'error',30,5);
				}
			},
		},
		close: function() 
		{
			$("#obtenerTraspasos").html('');
		}
	});
	
	$("#ventanaFormularioTraspasos").dialog(
	{
		autoOpen:false,
		height:290,
		width:700,
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
				registrarTraspaso();
			},
		},
		close: function() 
		{
			$("#formularioTraspasos").html('');
		}
	});
});

function obtenerListaTraspasos()
{
	obtenerTraspasos();
	$('#ventanaTraspasos').dialog('open');
}

function obtenerTraspasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerTraspasos').html('<img src="'+ img_loader +'"/> Obteniendo la lista de traspasos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerTraspasos',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerTraspasos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de traspasos',500,5000,'error',2,5);
			$('#obtenerTraspasos').html('');
		}
	});					  	  
}

function formularioTraspasos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioTraspasos').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de traspasos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioTraspasos',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioTraspasos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de traspasos',500,5000,'error',2,5);
			$('#formularioTraspasos').html('');
		}
	});					  	  
}

function registrarTraspaso()
{
	mensaje="";
	
	if($('#selectCuentaOrigen').val()=="0")
	{
		mensaje+="Seleccione la cuenta de origen <br />";
	}
	
	if($('#selectCuentaDestino').val()=="0")
	{
		mensaje+="Seleccione la cuenta de destino <br />";
	}

	if($('#txtSaldoOrigen').val()=="0" || parseFloat($('#txtSaldoOrigen').val())<parseFloat($('#txtMonto').val()) )
	{
		mensaje+="La cuenta de origen no tiene suficiente saldo para hacer el traspaso <br />";
	}
	
	if($('#txtMonto').val()=="0" || parseFloat($('#txtMonto').val())<0 || isNaN($('#txtMonto').val()) || $('#txtMonto').val()=="" )
	{
		mensaje+="El monto es incorrecto <br />";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(!confirm("¿Realmente desea registrar el traspaso?")) return;	
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoTrapasos').html('<img src="'+ img_loader +'"/> Registrando el traspaso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarTraspaso',
		data:
		{
			monto:				$('#txtMonto').val(),
			idCuentaOrigen:		$('#selectCuentaOrigen').val(),
			idCuentaDestino:	$('#selectCuentaDestino').val(),
			fecha:				$('#txtFechaTraspaso').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoTrapasos').html('');
			
			data	= eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5);
				
					$('#ventanaFormularioTraspasos').dialog('close');
					window.setTimeout("obtenerTraspasos()",1000)
				break;
			}
			
			
		},
		error:function(datos)
		{
			notify('Error al registrar el traspaso',500,5000,'error',30,5);
			$('#agregandoTrapasos').html('');
		}
	});					  	  
}


function borrarTraspaso(idTraspaso)
{
	if(confirm('¿Realmente desea borrar el traspaso?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoTraspasos').html('<img src="'+ img_loader +'"/> Se esta borrando el traspaso, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/borrarTraspaso',
		data:
		{
			idTraspaso:idTraspaso
		},
		datatype:"html",
		success:function(data, textStatus)
		{

			window.setTimeout("obtenerTraspasos()",1000)
			notify('Traspaso borrado',500,5000,'',0,0);
			$('#procesandoTraspasos').html('')
		},
		error:function(datos)
		{
			notify('Error al borrar el registro de traspaso',500,5000,'error',2,5);
			$('#agregandoTraspasos').html('');
		}
	});					  	  
}

function obtenerCuentasDestino()
{
	if($('#selectCuentaOrigen').val()=="0")
	{
		notify('Seleccione cuenta de origen',500,5000,'error',0,0);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#filaCuentaDestino').html('<img src="'+ img_loader +'"/> Obteniendo cuentas de destino, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerCuentasDestino',
		data:
		{
			idCuenta:$('#selectCuentaOrigen').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#filaCuentaDestino').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener las cuentas de destino',500,5000,'error',0,0);
			$('#filaCuentaDestino').html('');
		}
	});
}

function obtenerSaldoOrigen()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#saldoCuentaOrigen').html('<img src="'+ img_loader +'"/> Obteniendo saldo, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerSaldoOrigen',
		data:
		{
			idCuenta:$('#selectCuentaOrigen').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#saldoCuentaOrigen').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el saldo',500,5000,'error',0,0);
			$('#saldoCuentaOrigen').html('');
		}
	});
}

function obtenerSaldoDestino()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#saldoCuentaDestino').html('<img src="'+ img_loader +'"/> Obteniendo saldo, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerSaldoDestino',
		data:
		{
			idCuenta:$('#selectCuentaDestino').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#saldoCuentaDestino').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el saldo',500,5000,'error',0,0);
			$('#saldoCuentaDestino').html('');
		}
	});
}

function calcularIva()
{
	if(document.getElementById('chkIva').checked==true)
	{
		iva  =$('#txtIvaSesion').val()/100;
		pago =$('#txtImporte').val();
		
		//if(Solo_Numerico())
	}
	
	if(document.getElementById('chkIva').checked==false)
	{
		$('txtIva').val(0)
	}
}


function ordenReporteBancos(orden)
{
	$('#txtOrdenReporte').val(orden);
	reporteBancos();
}

function reporteBancos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporteBancos').html('<img src="'+ img_loader +'"/> Obteniendo reporte de bancos, por favor espere...');
		},
		type:"POST",
		url:base_url+'administracion/reporteBancos',
		data:
		{
			idCuenta:		$('#selectCuentaBancos').val(),
			fecha:			$('#txtMesReporte').val(),
			orden:		$('#txtOrdenReporte').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporteBancos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de bancos',500,5000,'error',0,0);
			$('#obtenerReporteBancos').html('');
		}
	});
}

function reporteBancosFechas()
{
	if($('#txtFechaInicio').val()=="" || $('#txtFechaFin').val()=="")
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerReporteBancos').html('<img src="'+ img_loader +'"/> Obteniendo reporte de bancos, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/reporteBancos',
		data:
		{
			idCuenta:$('#selectCuentaBancos').val(),
			fechaInicio:$('#txtFechaInicio').val(),
			fechaFin:$('#txtFechaFin').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporteBancos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener el reporte de bancos',500,5000,'error',0,0);
			$('#obtenerReporteBancos').html('');
		}
	});
}

$(document).ready(function()
{
	$("#txtMesReporte").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
	
	$("#btnReporteBancos").click(function(e)
	{
		reporteBancos();
		$('#ventanaReporteBancos').dialog('open');
	});
	
	$("#ventanaReporteBancos").dialog(
	{
		autoOpen:false,
		height:550,
		width:950,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cerrar: function() 
			{
				$(this).dialog('close');				 
			},
		},
		close: function() 
		{
			$("#obtenerReporteBancos").html('');
		}
	});
});

function obtenerCajaChica(idEgreso)
{
	$("#ventanaCajaChica").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCajaChica').html('<img src="'+ img_loader +'"/> Obteniendo detalles de caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerCajaChica',
		data:
		{
			idEgreso:idEgreso
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCajaChica').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de caja chica',500,5000,'error',0,0);
			$('#obtenerCajaChica').html('');
		}
	});
}

function formularioCajaChica()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCajaChica').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de  caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/formularioCajaChica',
		data:
		{
			idEgreso:$('#txtIdEgreso').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCajaChica').html(data);
			window.setTimeout("catalogos()",500);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario de caja chica',500,5000,'error',0,0);
			$('#formularioCajaChica').html('');
		}
	});
}

function registrarCajaChica()
{
	mensaje="";
	
	if(parseFloat($('#txtSaldoCaja').val())<=0 || parseFloat($('#txtSaldoCaja').val())<parseFloat($('#txtImporte').val()) )
	{
		notify('No existe suficiente saldo para caja chica',500,6000,'error',0,0);
		return;
	}
	
	if(Solo_Numerico($('#txtImporte').val())=="")
	{
		mensaje+="El importe de caja chica es incorrecto <br />";
	}
	
	if($('#txtConcepto').val()=="")
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#txtImporte').val()=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',30,5);
		return;
	}
	
	if(!confirm("¿Realmente desea continuar con el registro de caja chica?"))return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#agregandoCajaChica').html('<img src="'+ img_loader +'"/> Registrando caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/registrarCajaChica',
		data:
		{
			concepto:       $('#txtConcepto').val(),
			importe:		$('#txtImporte').val(),
			idEgreso:		$('#txtIdEgreso').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{

			data=eval(data);
			$('#agregandoCajaChica').html('');
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					$('#ventanaAgregarCajaChica').dialog('close');
					window.setTimeout("obtenerCajaChica("+$('#txtIdEgreso').val()+")",1000)
					notify(data[1],500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar caja chica',500,5000,'error',0,0);
			$('#agregandoCajaChica').html('');
		}
	});					  	  
}


function editarCajaChica()
{
	mensaje="";
	
	if(parseFloat($('#txtSaldoCaja').val())<=0 || parseFloat($('#txtSaldoCaja').val())<parseFloat($('#txtImporte').val()) )
	{
		notify('No existe suficiente saldo para caja chica',500,6000,'error',0,0);
		return;
	}
	
	if(Solo_Numerico($('#txtImporte').val())=="")
	{
		mensaje+="El importe de caja chica es incorrecto <br />";
	}
	
	if($('#txtConcepto').val()=="")
	{
		mensaje+="El concepto es incorrecto <br />";
	}
	
	if($('#txtImporte').val()=="")
	{
		mensaje+="El importe es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,6000,'error',0,0);
		return;
	}
	
	if(confirm("¿Realmente desea editar el registro de caja chica?")==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCajaChica').html('<img src="'+ img_loader +'"/> Editando caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/editarCajaChica',
		data:
		{
			concepto:       $('#txtConcepto').val(),
			importe:		$('#txtImporte').val(),
			idEgreso:		$('#txtIdEgreso').val(),
			idCaja:			$('#txtIdCaja').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			notify('Registro correcto',500,5000,'',0,0);
			$('#editandoCajaChica').html('');
			$('#ventanaEditarCajaChica').dialog('close');
			window.setTimeout("obtenerCajaChica("+$('#txtIdEgreso').val()+")",1000)
		},
		error:function(datos)
		{
			notify('Error al editar caja chica',500,5000,'error',0,0);
			$('#editandoCajaChica').html('');
		}
	});					  	  
}


//EDITAR LOS OTROS INGRESOS
function obtenerCajaChicaEditar(idCaja)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCajaChicaEditar').html('<img src="'+ img_loader +'"/> Obteniendo el registro de caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/obtenerCajaChicaEditar',
		data:
		{
			idEgreso:$('#txtIdEgreso').val(),
			idCaja:idCaja
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCajaChicaEditar').html(data)
		},
		error:function(datos)
		{
			notify('Error al obtener el registro de caja chica',500,5000,'error',2,5);
			$('#obtenerCajaChicaEditar').html('');
		}
	});					  	  
}

//BORRAR LOS OTROS EGRESOS
function borrarCajaChica(idCaja)
{
	if(confirm('¿Realmente desea borrar registro de caja chica?')==false)
	{
		return;	
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoCajaChica').html('<img src="'+ img_loader +'"/> Se esta borrando caja chica, por favor espere...');
		},
		type:"POST",
		url:base_url+'produccion/borrarCajaChica',
		data:
		{
			idCaja:idCaja
		},
		datatype:"html",
		success:function(data, textStatus)
		{

			window.setTimeout("obtenerCajaChica("+$('#txtIdEgreso').val()+")",1000)
			notify('Caja chica borrada',500,5000,'',0,0);
			$('#cargandoCajaChica').html('')
		},
		error:function(datos)
		{
			notify('Error al borrar el registro de caja chica',500,5000,'error',2,5);
			$('#cargandoCajaChica').html('');
		}
	});					  	  
}

$(document).ready(function()
{
	/*$("#btnCajaChica").click(function(e)
	{
		obtenerCajaChica();
		$('#ventanaCajaChica').dialog('open');
	});*/
	
	$("#ventanaCajaChica").dialog(
	{
		autoOpen:false,
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
			'Registrar': function() 
			{
				formularioCajaChica();
				$('#ventanaAgregarCajaChica').dialog('open');
			},
		},
		close: function() 
		{
			$("#obtenerCajaChica").html('');
		}
	});
	
	$("#ventanaAgregarCajaChica").dialog(
	{
		autoOpen:false,
		height:250,
		width:650,
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
				registrarCajaChica();
			},
		},
		close: function() 
		{
			$("#formularioCajaChica").html('');
		}
	});
	
	//FORMULARIO PARA EDITAR EGRESOS
	$("#ventanaEditarCajaChica").dialog(
	{
		autoOpen:false,
		height:250,
		width:650,
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
				editarCajaChica();
			},
		},
		close: function() 
		{
			$("#obtenerCajaChicaEditar").html('');
		}
	});
});

function obtenerDiasCredito()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procensandoCompra').html('<img src="'+ img_loader +'"/> Obteniendo días de crédito...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerDiasCredito',
		data:
		{
			idProveedor:$('#proveedores').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procensandoCompra').html('');
			$('#txtDiasCredito').val(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los días de crédito',500,5000,'error',30,3);
			$("#procensandoCompra").html('');
		}
	});
}



//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//CARGAR LAS CUENTAS CONTABLES
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
cu=0;

function comprobarCuentaContable(idCuentaCatalogo)
{
	for(i=0;i<=cu;i++)
	{
		if(!isNaN($('#txtIdCuentaCatalogo'+i).val()))
		{
			if(parseInt($('#txtIdCuentaCatalogo'+i).val())==parseInt(idCuentaCatalogo)) return false;
		}
	}
	
	return true;
}

function borrarCuentaContable(i)
{
	$('#filaCuentaContable'+i).remove();
}

function cargarCuentaContable(cuenta)
{
	if(!comprobarCuentaContable(cuenta.idCuentaCatalogo))
	{
		limpiarCampoCuenta();
		notify('Ya ha agregado la cuenta',500,5000,'error',30,5);
		return;
	}
	
	data='<div id="filaCuentaContable'+cu+'">\
	<img src="'+base_url+'img/borrar.png" title="Borrar" onclick="borrarCuentaContable('+cu+')" width="18" />\
	Referencia contable: '+cuenta.numeroCuenta+', Descripción: '+cuenta.descripcion+'\
	<input type="hidden" value="'+cuenta.idCuentaCatalogo+'" id="txtIdCuentaCatalogo'+cu+'" name="txtIdCuentaCatalogo'+cu+'" />\
	</div>';
	
	$('#listaCuentasContables').append(data);
	
	limpiarCampoCuenta()
	cu++;
	$('#txtNumeroCuentas').val(cu);
}

function limpiarCampoCuenta()
{
	window.setTimeout(function() 
	{
		$('#txtBuscarCuentaContable').val('');
	}, 200);
}

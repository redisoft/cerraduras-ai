tipo 	= "";
alto	= 550;
ancho	= 1010;

$(document).ready(function()
{
	$("#ventanaClientes").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:alto,
		width:ancho,
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
				registrarCliente()		  	  
			},
			/*'Recargar mapa': function() 
			{
				//loadScript()		
					  
				actualizarMapa();
			},*/
		},
		close: function() 
		{
			/*if(ejecutar && ejecutar.readystate != 4)
			{
				ejecutar.abort();
			}*/

			$("#formularioClientes").html('');
		}
	});
	
	$("#ventanaFuentesContacto").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			Aceptar: function() 
			{
				registrarFuenteContacto();				 
			},
		},
		close: function() 
		{
			$("#formularioFuentesContacto").html('');
		}
	});
});

function formularioClientes(Tipo)
{	
	if(ejecutar && ejecutar.readystate != 4)
	{
		ejecutar.abort();
	}
	
	this.tipo=Tipo;
	
	$('#ventanaClientes').dialog('open');
	
	ejecutar=$.ajax(
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
			tipoRegistro: $('#txtTipoRegistro').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioClientes').html(data);
			obtenerFuentesContacto();
			$('#txtBuscarCuentaContable').focus();
		},
		error:function(datos)
		{
			$('#formularioClientes').html('');
		}
	});		
}

function registrarCliente()
{
	var mensaje	= "";
	prospecto	= 1;
	
	/*if(document.getElementById('esCliente').checked==true)
	{
		prospecto=0;
	}*/
	
	if(sistemaActivo=='IEXE')
	{
		if(!camposVacios($('#txtNombreAlumno').val())  ) // || !camposVacios($('#txtApellidoPaterno').val()) || !camposVacios($('#txtApellidoMaterno').val())
		{
			mensaje+='Los datos del alumno son requeridos <br />';
		}
	}
	
	if(sistemaActivo!='IEXE')
	{
		if(!camposVacios($('#empresa').val()))
		{
			mensaje+='El nombre de la empresa es incorrecto <br />';
		}
	}
	
	/*if($('#direccion').val()=="")
	{
		mensaje+='La dirección es incorrecta <br />';
	}*/
	
	if(!camposVacios($('#telefono').val()))
	{
		mensaje+='El teléfono es incorrecto <br />';
	}
	
	if($('#selectZonas').val()=="0")
	{
		mensaje+='Por favor seleccione '+$('#txtIdentificador').val()+' <br />';
	}
	
	if(parseInt($('#limiteCredito').val())<0)
	{
		mensaje+='Los días de crédito son incorrectos <br />';
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	copiarDireccionCliente();
	
	if(confirm('¿Realmente desea registrar el cliente?')==false)
	{
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoClientes').html('<img src="'+ img_loader +'"/> Se esta registrando el cliente, por favor espere...');
		},
		type:"POST",
		url:base_url+"clientes/registrarCliente",
		data: $('#frmClientes').serialize(),
		/*{
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
			"lada":				$('#txtLada').val(),
			"fax":				$('#fax').val(),
			"ladaFax":			$('#txtLadaFax').val(),
			"email":			$('#email').val(),
			"email2":			$('#email2').val(),
			"email3":			$('#email3').val(),
			"email4":			$('#email4').val(),
			"email5":			$('#email5').val(),
			"pagina":			$('#pagina').val(),
			"pagina2":			$('#pagina2').val(),
			"pagina3":			$('#pagina3').val(),
			"direccionEnvio":	$('#direccionEnvio').val(),
			"codigoPostalEnvio":$('#codigoPostalEnvio').val(),
			"estadoEnvio":		$('#estadoEnvio').val(),
			"ciudadEnvio":		$('#ciudadEnvio').val(),
			"proveedoraso":		$('#proveedoraso').val(),
			"idZona":			$('#selectZonas').val(),
			"prospecto":		$('#selectRegistro').val(),
			"precio":			$('#txtPrecioCliente').val(),
			"municipio":		$('#txtMunicipio').val(),
			"nombreVendedor":	$('#nombreVendedor').val(),
			"limiteCredito":	$('#limiteCredito').val(),
			"plazos":			$('#plazos').val(),
			"nombreContacto":	$('#txtNombreContacto').val(),
			"emailContacto":	$('#txtEmailContacto').val(),
			"telefonoContacto":	$('#txtTelefonoContacto').val(),
			"departamento":		$('#txtDepartamento').val(),
			"extensionContacto":$('#txtExtension').val(),
			"grupo":			$('#txtGrupo').val(),
			"alias":			$('#txtAlias').val(),
			"competencia":			document.getElementById('chkCompetencia').checked==true?1:0,
			"serviciosProductos":	$('#txtServiciosProductos').val(),
			"idFuente":			$('#selectFuente').val(),
			"latitud":			$('#txtLatitud').val(),
			"longitud":			$('#txtLongitud').val(),
			
			"idBanco":			$('#txtIdBanco').val(),
			"idEmisor":			$('#selectEmisores').val(),
			"banco":			$('#txtBanco').val(),
			"cuenta":			$('#txtCuenta').val(),
			"clabe":			$('#txtClabe').val(),
			
			"comentarios":		$('#txtComentariosCliente').val(),
			
			"puesto":			$('#txtClabe').val(),
			"lada":				$('#txtLadaTelefonoContacto').val(),
			"ladaMovil1":		$('#txtLadaMovil').val(),
			"movil1":			$('#txtMovil').val(),
			"ladaMovil2":		$('#txtLadaMovil2').val(),
			"movil2":			$('#txtMovil2').val(),
			"ladaNextel":		$('#txtLadaNextel').val(),
			"nextel":			$('#txtNextel').val(),
			
			"ladaMovilCliente":	$('#txtLadaMovilCliente').val(),
			"movilCliente":		$('#txtMovilCliente').val(),
			"razonSocial":		$('#txtRazonSocial').val(),
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			
			"idMetodo":			$('#selectMetodoPagoCliente').val(),
			"formaPago":		$('#txtFormaPagoCliente').val(),
			
			"saldoInicial":		$('#txtSaldoInicial').val(),
			
			"nombre":			$('#txtNombreAlumno').val(),
			"paterno":			$('#txtApellidoPaterno').val(),
			"materno":			$('#txtApellidoMaterno').val(),
			"promotor":			$('#txtPromotor').val(),
			
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoClientes').html('')
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
				
				notify('El cliente se ha registrado correctamente',500,5000,'',30,5);
				
				if(tipo=="recargar")
				{
					$('#ventanaClientes').dialog('close');
					obtenerClientes();
				}
				
				if(tipo!='recargar')
				{
					$('#txtBuscarCliente').val($('#empresa').val());
					$('#txtIdCliente').val(data[1]);
					
					obtenerBancos();
					obtenerProductosVenta('1')
					
					if(obtenerNumeros($('#txtPedidoActivo').val())=='1')
					{
						$('#txtClientePedido').val($('#empresa').val());
						obtenerDireccionesEntrega()
					}
		
					$('#ventanaClientes').dialog('close');
				}
				
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar al cliente',500,5000,'error',30,5);
			$('#cargandoClientes').html('')
		}
	});	
}

function formularioFuentesContacto()
{	
	$('#ventanaFuentesContacto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioFuentesContacto').html('<img src="'+ img_loader +'"/> Obteniendo el formulario para contactos...');
		},
		type:"POST",
		url:base_url+'clientes/formularioFuentesContacto',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioFuentesContacto').html(data);
		},
		error:function(datos)
		{
			$('#formularioFuentesContacto').html('');
		}
	});		
}

function registrarFuenteContacto()
{	
	if($('#txtFuente').val()=="")
	{
		notify('El nombre es requerido',500,5000,'error',5,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoFuenteContacto').html('<img src="'+ img_loader +'"/> Obteniendo fuentes de contacto...');
		},
		type:"POST",
		url:base_url+'clientes/registrarFuenteContacto',
		data:
		{
			nombre :$('#txtFuente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoFuenteContacto').html('');
			$('#ventanaFuentesContacto').dialog('close');
			notify('Registro correcto',500,5000,'error',5,5);
			obtenerFuentesContacto();
		},
		error:function(datos)
		{
			$('#registrandoFuenteContacto').html('');
		}
	});		
}

function obtenerFuentesContacto()
{	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerFuentesContacto').html('<img src="'+ img_loader +'"/> Obteniendo fuentes de contacto...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerFuentesContacto',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerFuentesContacto').html(data);
		},
		error:function(datos)
		{
			$('#obtenerFuentesContacto').html('');
		}
	});		
}



function obtenerBancos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerBancos').html('<img src="'+ img_loader +'"/> Obteniendo lista de bancos...');
		},
		type:"POST",
		url:base_url+'ficha/obtenerBancosCliente/'+$('#txtIdCliente').val(),
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerBancos').html(data);
			
			buscarCuentas()
			obtenerDiasCredito();
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de bancos',500,5000,'error',30,3);
			$("#obtenerBancos").html('');
		}
	});
}

function obtenerDiasCredito()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#realizandoVenta').html('<img src="'+ img_loader +'"/> Obteniendo días de crédito...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerDiasCredito',
		data:
		{
			idCliente:$('#txtIdCliente').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#realizandoVenta').html('');
			$('#txtCreditoDias').val(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los días de crédito',500,5000,'error',30,3);
			$("#realizandoVenta").html('');
		}
	});
}

function calcularTotalesAcademicos()
{
	totalAcademico=0;
	
	inscripcion		= obtenerNumeros($('#txtInscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadInscripcion').val())	
	importe			= cantidad*inscripcion
	totalAcademico +=importe;
	$('#lblTotalInscripcion').html('$'+agregarComas(redondear(importe)));
	
	colegiatura		= obtenerNumeros($('#txtColegiatura').val())
	cantidad		= obtenerNumeros($('#txtCantidadColegiatura').val())	
	importe			= cantidad*colegiatura
	totalAcademico +=importe;
	$('#lblTotalColegiatura').html('$'+agregarComas(redondear(importe)));
	
	reinscripcion	= obtenerNumeros($('#txtReinscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadReinscripcion').val())	
	importe			= cantidad*reinscripcion
	totalAcademico 	+=importe;
	$('#lblTotalReinscripcion').html('$'+agregarComas(redondear(importe)));
	
	$('#lblTotalAcademicos').html('$'+(redondear(totalAcademico)))
	
}

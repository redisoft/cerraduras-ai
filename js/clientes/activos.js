$(document).ready(function()
{
	$('#txtBusquedas').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerClientes();
		}
	});

	$("#txtCotizaciones").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaCotizaciones',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'clientes/cotizaciones/'+ui.item.idCliente+'/0/'+ui.item.idCotizacion;
		}
	});
	
	$("#txtBuscarVenta").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaVentas',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'clientes/ventas/'+ui.item.idCliente+'/'+ui.item.idCotizacion;
		}
	});
	
	$("#txtBuscarFactura").autocomplete(
	{
		source:base_url+'configuracion/obtenerFacturas',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'facturacion/facturasCliente/'+ui.item.idCliente+'/0/'+ui.item.idFactura;
		}
	});

	$("#txtFechaMes").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
		
		onSelect: function(date) {obtenerClientes()}
	});

	$("#agregarCliente").click(function(e)
	{
		
	});

	
	
	$("#ventanaMapaClientes").dialog(
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
	
	
	
	$(document).on("click", ".ajax-pagClientes > li a", function(eve)
	{
		eve.preventDefault();
		var element 	= "#obtenerClientes";
		var link 		= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:		$('#txtBusquedas').val(),
				idResponsable:	$('#selectResponsableBusqueda').val(),
				idStatus:		$('#selectStatusBusqueda').val(),
				idZona:			$('#selectZonasBuscar').val(),
				idServicio:		$('#selectServicioBusqueda').val(),
				fecha:			$('#FechaDia2').val()==""?"fecha":$('#FechaDia2').val(),
				mes:			$('#txtFechaMes').val()==""?"mes":$('#txtFechaMes').val(),
				idTipo:			$('#selectBusquedaTipo').val(),
				
				
				idPromotor:		$('#selectPromotorBusqueda').val(),
				idEstatus:		$('#selectEstatusBuscar').val(),
				
				tipoRegistro: 	$('#txtTipoRegistro').val(),
				
				criterioSeccion:$('#txtCriterioSeccion').val(),
			
				fechaFin:			$('#txtFechaFin').val()==""?"fecha":$('#txtFechaFin').val(),
				
				numeroSeguimientos:	$('#selectNumeroSeguimientos').val(),
				idCampana:			$('#selectCampanasBusqueda').val(),
				idPrograma:			$('#selectProgramaBusqueda').val(),
				diaPago:			$('#selectDiaPago').val(),
				idFuente:			$('#selectFuentesBusqueda').val(),
				
				tipoFecha:			$('#selectTipoFecha').val(),
				inicial:			$('#txtFechaProspectosInicio').val(),
				final:				$('#txtFechaProspectosFin').val(),
				
				matricula:			$('#selectMatricula').val(),
				orden:				$('#txtOrden').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$('#obtenerClientes').html('<img src="'+ img_loader +'"/>Obteniendo registros..');
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

function ordenClientes(orden)
{
	$('#txtOrden').val(orden);
	obtenerClientes();
}

function obtenerClientes()
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
			$('#obtenerClientes').html('<img src="'+ img_loader +'"/> Obteniendo los registros...');
		},
		type:"POST",
		url:base_url+"clientes/obtenerActivos",
		data:
		{
			criterio:		$('#txtBusquedas').val(),
			idResponsable:	$('#selectResponsableBusqueda').val(),
			idStatus:		$('#selectStatusBusqueda').val(),
			idZona:			$('#selectZonasBuscar').val(),
			idServicio:		$('#selectServicioBusqueda').val(),
			fecha:			$('#FechaDia2').val()==""?"fecha":$('#FechaDia2').val(),
			mes:			$('#txtFechaMes').val()==""?"mes":$('#txtFechaMes').val(),
			idTipo:			$('#selectBusquedaTipo').val(),
			
			
			idPromotor:		$('#selectPromotorBusqueda').val(),
			idEstatus:		$('#selectEstatusBuscar').val(),
				
			tipoRegistro: 		$('#txtTipoRegistro').val(),
			criterioSeccion:	$('#txtCriterioSeccion').val(),
			
			fechaFin:			$('#txtFechaFin').val()==""?"fecha":$('#txtFechaFin').val(),
			
			numeroSeguimientos:	$('#selectNumeroSeguimientos').val(),
			idCampana:			$('#selectCampanasBusqueda').val(),
			idPrograma:			$('#selectProgramaBusqueda').val(),
			diaPago:			$('#selectDiaPago').val(),
			idFuente:			$('#selectFuentesBusqueda').val(),
			
			tipoFecha:			$('#selectTipoFecha').val(),
			inicial:			$('#txtFechaProspectosInicio').val(),
			final:				$('#txtFechaProspectosFin').val(),
			
			matricula:			$('#selectMatricula').val(),
			
			orden:				$('#txtOrden').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerClientes').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros ',500,4000,"error");
			$("#obtenerClientes").html('');	
		}
	});				
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


function actualizarMapa()
{
	if($('#txtLongitud').val()=="" || $('#txtLongitud').val()=="")
	{
		notify('La latitud y longitud son requeridas',500,5000,'error',5,5);
		return;
	}
	
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
			longitud:		$('#txtLongitud').val(),
			latitud:		$('#txtLatitud').val(),
			
			/*calle:			$('#direccion').val(),
			numero:			$('#numero').val(),
			
			colonia:		$('#colonia').val(),
			localidad:		$('#localidad').val(),
			municipio:		$('#txtMunicipio').val(),
			estado:			$('#estado').val(),
			pais:			$('#txtPais').val(),
			codigoPostal:	$('#codigoPostal').val(),*/
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
	
	//$('#recargarMapa').load(base_url+'clientes/actualizarMapa');
}

function obtenerMapa(idCliente)
{
	$("#ventanaMapaClientes").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMapa').html('<img src="'+ img_loader +'"/> Obteniendo el mapa del cliente, por favor espere...');},
		type:"POST",
		url:base_url+"clientes/obtenerMapa",
		data:
		{
			idCliente:idCliente
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

//===========================================================================================================//
//PARA LOS MAPAS DE JAVASCRITP
/*function initialize() 
{
	var mapOptions = 
	{
		zoom: 8,
		center: new google.maps.LatLng(-34.397, 150.644),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	var map = new google.maps.Map(document.getElementById('map-canvas'),
	mapOptions);
}

function loadScript() 
{
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
	'callback=initialize';
	document.body.appendChild(script);
}*/
//===========================================================================================================//



cliente=0;

function obtenerCliente(idCliente,banco)
{
	$('#ventanaEditarClientes').dialog('open');
	
	cliente=idCliente;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarClientes').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerCliente',
		data:
		{
			"idCliente":	idCliente,
			tipoRegistro: 	$('#txtTipoRegistro').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarClientes').html(data);
			
			if(banco=='1')
			{
				configurarTabsCliente('cuentasBanco');
			}
		},
		error:function(datos)
		{
			$('#cargarClientes').html('Error al obtener el cliente');
		}
	});		
}

$(document).ready(function()
{
	$("#ventanaEditarClientes").dialog(
	{
		autoOpen:false,
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
				editarCliente();	  	  
			},
			/*'Recargar mapa': function() 
			{
				actualizarMapa();	  	  
			},*/
		},
		close: function() 
		{
			$("#cargarClientes").html('');
		}
	});
});

function editarCliente()
{
	var mensaje="";

	/*if(!camposVacios($('#empresa').val()))
	{
		mensaje+='El nombre de la empresa es incorrecto <br />';
	}
	*/
	
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
	
	/*if(!camposVacios($('#telefono').val()))
	{
		mensaje+='El teléfono es incorrecto <br />';
	}*/
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	copiarDireccionCliente();
	
	if(!confirm('¿Realmente desea editar el cliente?')) return

	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoEditarClientes').html('<img src="'+ img_loader +'"/> Se esta editando el cliente...');
		},
		type:"POST",
		url:base_url+"clientes/editarCliente",
		data: $('#frmEditarCliente').serialize()
		/*{
			"empresa":			$("#empresa").val(),
			"prospecto":		$("#selectTipoCliente").val(),
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
			"idCliente":		cliente,
			"idZona":			$('#zonita1').val(),
			"precio":			$('#txtPrecioClienteEditar').val(),
			"municipio":		$('#txtMunicipio').val(),
			"nombreVendedor":	$('#nombreVendedor1').val(),
			"limiteCredito":	$('#limiteCredito1').val(),
			"plazos":			$('#plazos1').val(),
			"grupo":			$('#txtGrupo').val(),
			"alias":			$('#txtAlias').val(),
			"competencia":			document.getElementById('chkCompetencia').checked==true?1:0,
			"serviciosProductos":	$('#txtServiciosProductos').val(),
			"idFuente":			$('#selectFuente').val(),
			"idUsuario":		$('#selectResponsableCliente').val(),
			"latitud":			$('#txtLatitud').val(),
			"longitud":			$('#txtLongitud').val(),
			
			"comentarios":		$('#txtComentariosCliente').val(),
			"razonSocial":		$('#txtRazonSocial').val(),
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			
			"idMetodo":			$('#selectMetodoPagoCliente').val(),
			"formaPago":		$('#txtFormaPagoCliente').val(),
			"saldoInicial":		$('#txtSaldoInicial').val(),
			
			"nombre":			$('#txtNombreAlumno').val(),
			"paterno":			$('#txtApellidoPaterno').val(),
			"materno":			$('#txtApellidoMaterno').val(),
			"promotor":			$('#txtPromotor').val(),
		}*/,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoEditarClientes').html('')
			
			switch(data)
			{
				case "0":
					notify('Error al editar el registro',500,5000,'error',30,5);
				break;
				
				case "1":
					//location.reload();
					$('#ventanaEditarClientes').dialog('close');
					obtenerClientes();
					
					if(obtenerNumeros($('#txtAtrasosDisponible').val())==1)
					{
						obtenerAtrasos();
					}
					
					notify('El cliente se ha editado correctamente',500,5000,'',30,5);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#cargandoEditarClientes').html('')
			notify('Error al editar el registro',500,5000,'error',30,5);
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

function esProveedor()
{
	if(document.getElementById('proveedorcito').checked==true)
	{
		$('#proveedoraso').val('si');
	}
	else
	{
		$('#proveedoraso').val('no');
	}
}

function busquedaStatus()
{
	window.location.href=base_url+'clientes/index/0/'+$('#selectStatusBusqueda').val();
}

function busquedaServicio()
{
	window.location.href=base_url+'clientes/index/0/0/'+$('#selectServicioBusqueda').val();
}

function busquedaFechaSeguimiento()
{
	window.location.href=base_url+'clientes/index/0/0/0/'+$('#FechaDia2').val();
}

function busquedaResponsable()
{
	window.location.href=base_url+'clientes/index/0/0/0/fecha/'+$('#selectResponsableBusqueda').val();
}

function busquedaTipo()
{
	window.location.href=base_url+'clientes/index/0/0/0/fecha/0/'+$('#selectBusquedaTipo').val();
}

function busquedaZona()
{
	window.location.href=base_url+'clientes/index/0/0/0/fecha/0/4/mes/'+$('#selectZonasBuscar').val();
}

function busquedaMesSeguimiento()
{
	window.location.href=base_url+'clientes/index/0/0/0/fecha/0/4/'+$('#txtFechaMes').val();
}


/*function mostrarDatos()
{
	if($('#TipoPago').val()=="1")
	{
		$('#mostrarCheques').fadeOut();
		$('#filaNombre').fadeOut();
		$('#mostrarTransferencia').fadeOut();
		
		$('#filaBanco').fadeOut();
		$('#filaCuenta').fadeOut();
	}
	
	if($('#TipoPago').val()=="2")
	{
		$('#mostrarCheques').fadeIn();
		$('#mostrarTransferencia').fadeOut();
		//$('#filaNombre').fadeIn();
		
		$('#filaBanco').fadeIn();
		$('#filaCuenta').fadeIn();
	}
	
	if($('#TipoPago').val()=="3")
	{
		$('#mostrarCheques').fadeOut();
		$('#mostrarTransferencia').fadeIn();
		//$('#filaNombre').fadeIn();
		
		$('#filaBanco').fadeIn();
		$('#filaCuenta').fadeIn();
	}
}*/

/*function buscarCuentas()
{
	$("#cargarCuenta").load(base_url+"ficha/obtenerCuentasDigitos/"+$('#listaBancos').val());
}*/




function imprimirFicha()
{
	mapa 				= document.getElementById('cargarFichaCliente');
	ventanaImprimir 	= window.open(' ', 'popimpr');
	
	ventanaImprimir.document.write( mapa.innerHTML );
	ventanaImprimir.document.close();
	ventanaImprimir.print( );
	ventanaImprimir.close();
}



function borrarCliente(idCliente)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{	
			$('#exportandoDatos').html('<img src="'+ img_loader +'"/> Borrando registro...');
		},
		type:"POST",
		url:base_url+"clientes/borrarCliente",
		data:
		{
			"idCliente":		idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#exportandoDatos").html('');
			
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro ',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerClientes();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar el registro',500,5000,'error',5,5);
			$("#exportandoDatos").html('');
		}
	});		
}

function copiarDireccionCliente()
{
	if(!camposVacios($('#direccion').val()) && !camposVacios($('#numero').val()) && !camposVacios($('#colonia').val()) && !camposVacios($('#codigoPostal').val())
	&& !camposVacios($('#localidad').val()) && !camposVacios($('#txtMunicipio').val()) && !camposVacios($('#estado').val()) && !camposVacios($('#txtPais').val()))
	{
		$('#direccion').val($('#txtCalleEnvio').val());
		$('#numero').val($('#txtNumeroEnvio').val());
		$('#colonia').val($('#txtColoniaEnvio').val());
		$('#codigoPostal').val($('#txtCodigoPostalEnvio').val());
		$('#localidad').val($('#txtLocalidadEnvio').val());
		$('#txtMunicipio').val($('#txtMunicipioEnvio').val());
		$('#estado').val($('#txtEstadoEnvio').val());
		$('#txtPais').val($('#txtPaisEnvio').val());
	}
}
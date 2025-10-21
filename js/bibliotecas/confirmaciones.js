
id			= 0;
Mensaje		= "";
modulo		= "";
idAdicional	= 0;
idAdicional2= 0;
Pagina		= 0;

$(document).ready(function()
{
	$('#txtCodigoConfirmacion').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			confirmarAccion();
		}
	});
	
	$("#ventanaConfirmacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600,complete: function() {$('#txtCodigoConfirmacion').focus()}  },
		height:200,
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
				confirmarAccion();
			},
		},
		close: function() 
		{
			$("#confirmando").html('');
			$("#txtCodigoConfirmacion").val('');
			
			$('#filaAccion').fadeOut();
			$('#lblAccionGlobal').html('Etiqueta');
			id	=0;
			
			if(!rutaEntrega)
			{
				$("#selectMostrador").val('0');
			}
			else
			{
				//rutaEntrega=false;
			}
			
			if(!opcionVendedor)
			{
				$("#txtIdUsuarioVendedor").val('0');

				$('#txtIdUsuarioVendedor').trigger('change');
			}
			else
			{
				//opcionVendedor=false;
			}
			
			if(!opcionCorte)
			{
				if(obtenerNumeros($('#txtIdRol').val())==1)
				{
					$("#selectCajeros").val('0');
				}
				
			}
			else
			{
				//opcionVendedor=false;
			}
		}
	});

	$("#ventanaSaldoPendiente").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600,complete: function() {}  },
		height:200,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');		
			},
		},
	});
});

function comprobarCodigoNuevo(mensaje,operacion)
{	
	//return true; //TEmporal
	
	codigo = $('#txtCodigoConfirmacion').val()
	
	if(codigo==null)return;
	
	if(codigo.length==0)
	{
		notify('El código es incorrecto',500,5000,'error',30,3);
		return false;
	}
	
	codigo	= hex_sha1(codigo);

	if(operacion=='borrado' )
	{
		if(codigo!=codigoBorrado && codigo!=codigoCancelar)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}

	if(operacion=='editar')
	{
		if(codigo!=codigoEditar && codigo!=codigoCancelar)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}
	
	if(operacion=='importar')
	{
		if(codigo!=codigoImportar && codigo!=codigoCancelar)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}
	
	if(operacion=='descuentos')
	{
		if(codigo!=$('#txtClaveDescuento').val())
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}
	
	if(operacion=='cancelacion')
	{
		if(codigo!=codigoCancelar)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}

	if(operacion=='inventario' )
	{
		if(codigo!=codigoInventario)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}
	
	if(operacion=='usuario')
	{
		respuesta=revisarCodigoUsuario($('#txtIdUsuarioVendedor').val(),codigo);
		
		setTimeout(function()
		{
			if(!respuesta)
			{
				notify('El código es incorrecto',500,5000,'error',30,3);
				return false;
			}
		},300);
		
	}
	
	if(mensaje=='moduloDistinto')return true; //Significa que se entrara a otro modulo donde no se requiere mensaje
	if(confirm(mensaje)) return true;
	
	return false;
}

function revisarCodigoUsuario(idUsuario,claveCancelacion)
{
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
		},
		type:"POST",
		url:base_url+'configuracion/revisarCodigoUsuario',
		data:
		{
			idUsuario: 			idUsuario,
			claveCancelacion: 	claveCancelacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);

			switch(data[0])
			{
				case "0":
						return false;
					break;
				
				case "1":
						return true;
					break;

			}
			
		},
		error:function(datos)
		{
			return false;
		}
	});		
}

function comprobarCodigo(mensaje)
{	
	codigo = prompt("Por favor ingrese el código de validación")
	
	if(codigo==null)return;
	
	if(codigo.length==0)
	{
		notify('El código es incorrecto',500,5000,'error',30,3);
		return false;
	}
	
	codigo=hex_sha1(codigo);

	if(codigo!=codigoBorrado)
	{
		notify('El código es incorrecto',500,5000,'error',30,3);
		return false;
	}
	
	if(mensaje=='moduloDistinto')return true; //Significa que se entrara a otro modulo donde no se requiere mensaje
	if(confirm(mensaje)) return true;
	
	return false;
}

function confirmarAccion()
{
	switch(modulo)
	{
		case "accesoBorrarCliente": accesoBorrarCliente(); break;
		case "accesoEditarCliente": accesoEditarCliente(); break;
		case "accesoEditarSeguimientoCliente": accesoEditarSeguimientoCliente(); break;
		case "accesoBorrarSeguimientoCliente": accesoBorrarSeguimientoCliente(); break;
		case "accesoBorrarFicheroCliente": accesoBorrarFicheroCliente(); break;
		case "accesoBorrarContactoCliente": accesoBorrarContactoCliente(); break;
		case "accesoEditarContactoCliente": accesoEditarContactoCliente(); break;
		case "accesoEditarCotizacion": accesoEditarCotizacion(); break;
		case "borrarCotizacionCliente": borrarCotizacionCliente(); break;
		case "accesoReutilizarVenta": accesoReutilizarVenta(); break;
		case "accesoConvertirVenta": accesoConvertirVenta(); break;
		case "accesoBorrarCotizacion": accesoBorrarCotizacion(); break;
		case "accesoCancelarCotizacion": accesoCancelarCotizacion(); break;
		case "accesoBorrarVenta": accesoBorrarVenta(); break;
		case "accesoCancelarVenta": accesoCancelarVenta(); break;
		case "borrarProveedor": borrarProveedor(); break;
		case "accesoEditarProveedor": accesoEditarProveedor(); break;
		case "accesoBorrarArchivoSeguimiento": accesoBorrarArchivoSeguimiento(); break;
		case "accesoBorrarArchivoSeguimientoProveedor": accesoBorrarArchivoSeguimientoProveedor(); break;
		case "accesoBorrarSeguimientoProveedor": accesoBorrarSeguimientoProveedor(); break;
		case "accesoEditarSeguimientoProveedor": accesoEditarSeguimientoProveedor(); break;
		case "borrarCompra": borrarCompra(); break;
		case "cancelarCompra": cancelarCompra(); break;
		case "accesoBorrarPagoCompraMaterial": accesoBorrarPagoCompraMaterial(); break;
		case "accesoBorrarComprobanteCompra": accesoBorrarComprobanteCompra(); break;
		case "acccesoEditarIngreso": acccesoEditarIngreso(); break;
		case "accesoBorrarIngreso": accesoBorrarIngreso(); break;
		case "accesoBorrarComprobanteIngreso": accesoBorrarComprobanteIngreso(); break;
		case "accesoEditarEgreso": accesoEditarEgreso(); break;
		case "accesoBorrarEgreso": accesoBorrarEgreso(); break;
		case "accesoBorrarComprobanteEgreso": accesoBorrarComprobanteEgreso(); break;
		case "accesoBorrarProducto": accesoBorrarProducto(); break;
		case "accesoEditarProducto": accesoEditarProducto(); break;
		case "accesoEditarCostoProveedor": accesoEditarCostoProveedor(); break;
		case "accesoBorrarCostoProveedor": accesoBorrarCostoProveedor(); break;
		case "accesoEditarServicio": accesoEditarServicio(); break;
		case "borrarServicioProducto": borrarServicioProducto(); break;
		case "accesoEditarMobiliario": accesoEditarMobiliario(); break;
		case "accesoBorrarMobiliario": accesoBorrarMobiliario(); break;
		case "accesoEditarMaterial": accesoEditarMaterial(); break;
		case "confirmarBorrarMaterial": confirmarBorrarMaterial(); break;
		case "accesoEditarProduccion": accesoEditarProduccion(); break;
		case "borrarProductoProduccion": borrarProductoProduccion(); break;
		case "accesoEditarProductoProduccion": accesoEditarProductoProduccion(); break;
		case "borrarMaterialProducto": borrarMaterialProducto(); break;
		case "accesoCancelarOrden": accesoCancelarOrden(); break;
		case "accesoBorrarCobro": accesoBorrarCobro(); break;
		case "accesoCancelarCfdi": accesoCancelarCfdi(); break;
		case "accesoEditarEmpleado": accesoEditarEmpleado(); break;
		case "accesoBorrarEmpleado": accesoBorrarEmpleado(); break;
		case "accesoEditarDepartamentoNomina": accesoEditarDepartamentoNomina(); break;
		case "accesoBorrarDepartamentoNomina": accesoBorrarDepartamentoNomina(); break;
		case "accesoEditarPuestoNomina": accesoEditarPuestoNomina(); break;
		case "accesoBorrarPuestoNomina": accesoBorrarPuestoNomina(); break;
		case "accesoEditarDeduccion": accesoEditarDeduccion(); break;
		case "accesoBorrarDeduccion": accesoBorrarDeduccion(); break;
		case "accesoEditarPercepcion": accesoEditarPercepcion(); break;
		case "accesoBorrarPercepcion": accesoBorrarPercepcion(); break;
		case "accesoEditarUsuario": accesoEditarUsuario(); break;
		case "confirmarBorrarUsuario": confirmarBorrarUsuario(); break;
		case "confirmarReactivarUsuario": confirmarReactivarUsuario(); break;
		case "accesoEditarRol": accesoEditarRol(); break;
		case "borrarRolUsuario": borrarRolUsuario(); break;
		case "accesoEditarBanco": accesoEditarBanco(); break;
		case "borrarBanco": borrarBanco(); break;
		case "accesoEditarCuenta": accesoEditarCuenta(); break;
		case "borrarCuenta": borrarCuenta(); break;
		case "accesoBorrarConversion": accesoBorrarConversion(); break;
		case "accesoEditarConversion": accesoEditarConversion(); break;
		case "accesoEditarUnidad": accesoEditarUnidad(); break;
		case "borrarUnidad": borrarUnidad(); break;
		case "accesoEditarZona": accesoEditarZona(); break;
		case "confirmarBorrarZona": confirmarBorrarZona(); break;
		case "accesoEditarEmisor": accesoEditarEmisor(); break;
		case "accesoBorrarEmisor": accesoBorrarEmisor(); break;
		case "accesoEditarProceso": accesoEditarProceso(); break;
		case "borrarProceso": borrarProceso(); break;
		case "accesoEditarDivisa": accesoEditarDivisa(); break;
		case "borrarDivisa": borrarDivisa(); break;
		case "accesoEditarLinea": accesoEditarLinea(); break;
		case "borrarLinea": borrarLinea(); break;
		case "accesoEditarServicioConfiguracion": accesoEditarServicioConfiguracion(); break;
		case "confirmarBorrarServicio": confirmarBorrarServicio(); break;
		case "accesoEditarForma": accesoEditarForma(); break;
		case "accesoBorrarForma": accesoBorrarForma(); break;
		case "accesoEditarTienda": accesoEditarTienda(); break;
		case "accesoBorrarTienda": accesoBorrarTienda(); break;
		case "borrarDepartamento": borrarDepartamento(); break;
		case "borrarProductoAdministracion": borrarProductoAdministracion(); break;
		case "borrarTipoGasto": borrarTipoGasto(); break;
		case "borrarNombre": borrarNombre(); break;
		case "accesoEditarDepartamentoConfiguracion": accesoEditarDepartamentoConfiguracion(); break;
		case "accesoEditarProductoConfiguracion": accesoEditarProductoConfiguracion(); break;
		case "accesoEditarTipoConfiguracion": accesoEditarTipoConfiguracion(); break;
		case "accesoEditarNombreConfiguracion": accesoEditarNombreConfiguracion(); break;
		case "accesoImportarClientes": accesoImportarClientes(); break;
		case "accesoImportarProspectos": accesoImportarProspectos(); break;
		case "accesoExportarClientes": accesoExportarClientes(); break;
		case "accesoImportarProveedores": accesoImportarProveedores(); break;
		case "accesoExportarProveedores": accesoExportarProveedores(); break;
		case "accesoImportarMateriales": accesoImportarMateriales(); break;
		case "accesoExportarMateriales": accesoExportarMateriales(); break;
		case "accesoImportarProductos": accesoImportarProductos(); break;
		case "accesoExportarProductos": accesoExportarProductos(); break;
		case "accesoImportarProduccion": accesoImportarProduccion(); break;
		case "accesoExportarProduccion": accesoExportarProduccion(); break;
		case "confirmarBorrarProcesoOrden": confirmarBorrarProcesoOrden(); break;
		case "accesoEditarProductoTerminado": accesoEditarProductoTerminado(); break;
		case "accesoBorrarProductoTerminado": accesoBorrarProductoTerminado(); break;
		case "accesoAgregarProveedorCompraProducto": accesoAgregarProveedorCompraProducto(); break;
		case "accesoEditarMotivo": accesoEditarMotivo(); break;
		case "confirmarBorrarMotivo": confirmarBorrarMotivo(); break;
		case "accesoAsignarDescuento": accesoAsignarDescuento(); break;
		case "accesoAgregarProveedorCompraMaterial": accesoAgregarProveedorCompraMaterial(); break;
		case "accesoAgregarProveedorCompraInventario": accesoAgregarProveedorCompraInventario(); break;
		case "accesoEditarStatus": accesoEditarStatus(); break;
		case "confirmarBorrarStatus": confirmarBorrarStatus(); break;
		case "accesoEditarServicioConsumo": accesoEditarServicioConsumo(); break;
		case "accesoBorrarServicioConsumo": accesoBorrarServicioConsumo(); break;
		case "accesoServicioProveedorConsumo": accesoServicioProveedorConsumo(); break; //PENDIENTE
		case "accesoEditarCostoProveedorServicio": accesoEditarCostoProveedorServicio(); break;
		case "accesoBorrarCostoProveedorServicio": accesoBorrarCostoProveedorServicio(); break;
		case "accesoAgregarProveedorCompraServicio": accesoAgregarProveedorCompraServicio(); break;
		case "accesoBorrarServicioRecibido": accesoBorrarServicioRecibido(); break;
		case "accesoBorrarProductoRecibido": accesoBorrarProductoRecibido(); break;
		case "accesoBorrarMaterialRecibido": accesoBorrarMaterialRecibido(); break;
		case "accesoBorrarInventarioRecibido": accesoBorrarInventarioRecibido(); break;
		case "borrarContactoProveedor": borrarContactoProveedor(); break;
		case "accesoAgregarProveedorServicio": accesoAgregarProveedorServicio(); break;
		case "accesoCancelarVentaServicios": accesoCancelarVentaServicios(); break;
		case "accesoBorrarFicheroProveedor": accesoBorrarFicheroProveedor(); break;
		case "accesoBorrarPedido": accesoBorrarPedido(); break;
		case "accesoEditarPedido": accesoEditarPedido(); break;
		case "accesoCancelarPedido": accesoCancelarPedido(); break;
		case "accesoEditarSalida": accesoEditarSalida(); break;
		case "accesoBorrarSalida": accesoBorrarSalida(); break;
		case "accesoEditarProducidoProducto": accesoEditarProducidoProducto(); break;
		case "accesoBorrarProducidoProducto": accesoBorrarProducidoProducto(); break;
		case "accesoEditarConteo": accesoEditarConteo(); break;
		case "accesoBorrarConteo": accesoBorrarConteo(); break;
		case "accesoEditarSubLinea": accesoEditarSubLinea(); break;
		case "accesoBorrarSubLinea": accesoBorrarSubLinea(); break;
		case "accesoBorrarTraspaso": accesoBorrarTraspaso(); break;
		case "accesoEditarEstatus": accesoEditarEstatus(); break;
		case "accesoBorrarEstatus": accesoBorrarEstatus(); break;
		case "accesoEditarProgramas": accesoEditarProgramas(); break;
		case "accesoBorrarProgramas": accesoBorrarProgramas(); break;
		case "accesoBorrarDetallesSeguimiento": accesoBorrarDetallesSeguimiento(); break;
		case "accesoEditarCampanas": accesoEditarCampanas(); break;
		case "accesoBorrarCampanas": accesoBorrarCampanas(); break;
		case "accesoBorrarDocumento": accesoBorrarDocumento(); break;
		case "accesoBorrarPromotores": accesoBorrarPromotores(); break;
		case "accesoEditarCausas": accesoEditarCausas(); break;
		case "accesoBorrarCausas": accesoBorrarCausas(); break;
		case "accesoImportarFacebook": accesoImportarFacebook(); break;		
		case "accesoEditarLista": accesoEditarLista(); break;
		case "accesoAutorizarLista": accesoAutorizarLista(); break;
		case "accesoBorrarLista": accesoBorrarLista(); break;
		case "accesoImportarChecador": accesoImportarChecador(); break;
		case "accesoImportarComparar": accesoImportarComparar(); break;
		case "accesoReactivarProspecto": accesoReactivarProspecto(); break;
		case "accesoReactivarProspectoSeguimiento": accesoReactivarProspectoSeguimiento(); break;
		
		case "accesoImportarProspectosAdmin": accesoImportarProspectosAdmin(); break;
		case "accesoBorrarPreinscrito": accesoBorrarPreinscrito(); break;
		case "accesoEditarEstatusSeguimiento": accesoEditarEstatusSeguimiento(); break;
		case "accesoEditarComisionProspecto": accesoEditarComisionProspecto(); break;
		case "accesoBorrarPlantilla": accesoBorrarPlantilla(); break;
		case "accesoEditarPeriodos": accesoEditarPeriodos(); break;
		case "accesoBorrarPeriodos": accesoBorrarPeriodos(); break;
		case "accesoPrecio1": accesoPrecio1(); break;
		case "accesoFacturarVenta": accesoFacturarVenta(); break;
		case "accesoRutaEntrega": accesoRutaEntrega(); break;
		case "accesoOpcionVendedor": accesoOpcionVendedor(); break;
		case "accesoConvertirPrefactura": accesoConvertirPrefactura(); break;	
		case "accesoOpcionFactura": accesoOpcionFactura(); break;
		case "accesoEditarFechaCorte": accesoEditarFechaCorte(); break;
		case "accesoBorrarInventarioSucursal": accesoBorrarInventarioSucursal(); break;
			
		case "accesoBorrarInventarioProveedor": accesoBorrarInventarioProveedor(); break;
			
		case "accesoEditarVehiculo": accesoEditarVehiculo(); break;
		case "accesoBorrarVehiculo": accesoBorrarVehiculo(); break;
			
		case "accesoOpcionCorte": accesoOpcionCorte(); break;
	}
}

opcionCorte=false;
function accesoOpcionCorte(ID)
{
	opcionCorte=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoOpcionCorte";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		codigo = $('#txtCodigoConfirmacion').val()
	
		if(codigo==null)return;

		if(codigo.length==0)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}

		codigo	= hex_sha1(codigo);

		ejecutar=$.ajax(
		{
			async:false,
			beforeSend:function(objeto)
			{
			},
			type:"POST",
			url:base_url+'configuracion/revisarCodigoUsuario',
			data:
			{
				idUsuario: 			$('#txtIdUsuarioRegistro').val(),
				claveCancelacion: 	codigo
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				data=eval(data);

				switch(data[0])
				{
					case "0":
							notify('El código es incorrecto',500,5000,'error',30,3);
						break;

					case "1":
							opcionCorte=true;
							formularioCorte()
							$("#ventanaConfirmacion").dialog('close');
						break;

				}

			},
			error:function(datos)
			{
				return false;
			}
		});		
	}
}

function accesoBorrarVehiculo(idPuesto)
{
	if(id==0)
	{
		id			= idPuesto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarVehiculo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarVehiculo(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarVehiculo(idPuesto)
{
	if(id==0)
	{
		id			= idPuesto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarVehiculo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerVehiculo(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarInventarioProveedor(idRegistro)
{
	if(id==0)
	{
		id				= idRegistro
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarInventarioProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarInventarioProveedor(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarInventarioSucursal(idRecibido)
{
	if(id==0)
	{
		id				= idRecibido
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarInventarioSucursal";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'inventario'))return;
		borrarInventarioSucursal(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarFechaCorte(ID)
{
	opcionVendedor=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarFechaCorte";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		$('#spnFechaCorte,#btnFechaCorte').fadeOut();
		$('#txtFechaCorte').fadeIn();
		
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoConvertirPrefactura(ID)
{
	opcionVendedor=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoConvertirPrefactura";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		convertirPreFactura(id)
		
		$("#ventanaConfirmacion").dialog('close');
	}
}


opcionVendedor=false;
function accesoOpcionVendedor(ID)
{
	opcionVendedor=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoOpcionVendedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		codigo = $('#txtCodigoConfirmacion').val()
	
		if(codigo==null)return;

		if(codigo.length==0)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}

		codigo	= hex_sha1(codigo);

		ejecutar=$.ajax(
		{
			async:false,
			beforeSend:function(objeto)
			{
			},
			type:"POST",
			url:base_url+'configuracion/revisarCodigoUsuario',
			data:
			{
				idUsuario: 			$('#txtIdUsuarioVendedor').val(),
				claveCancelacion: 	codigo
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				data=eval(data);

				switch(data[0])
				{
					case "0":
							notify('El código es incorrecto',500,5000,'error',30,3);
						break;

					case "1":
							opcionVendedor=true;
							$("#ventanaConfirmacion").dialog('close');
						break;

				}

			},
			error:function(datos)
			{
				return false;
			}
		});		

		/*if(!comprobarCodigoNuevo(Mensaje,'usuario'))return;
		opcionVendedor=true;
		$("#ventanaConfirmacion").dialog('close');*/
	}
}

rutaEntrega=false;
function accesoRutaEntrega(ID)
{
	rutaEntrega=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoRutaEntrega";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		rutaEntrega=true;
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoFacturarVenta(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoFacturarVenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerDatosFactura(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarPeriodos(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPeriodos";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPeriodos(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarPeriodos(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarPeriodos";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerPeriodosEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarPlantilla(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPlantilla";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPlantilla(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAgregarProveedorServicio(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAgregarProveedorServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		formularioProveedores(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarComisionProspecto(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarComisionProspecto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		formularioEditarComision(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarEstatusSeguimiento(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarEstatusSeguimiento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerEstatusSeguimientoEditar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarPreinscrito(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPreinscrito";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPreinscrito(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarProspectosAdmin()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarProspectosAdmin";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarProspectosAdmin();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoReactivarProspectoSeguimiento(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoReactivarProspectoSeguimiento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		reactivarProspectoSeguimiento(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoReactivarProspecto(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoReactivarProspecto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		reactivarProspecto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarComparar()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarComparar";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarComparar();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarChecador()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarChecador";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarChecador();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAutorizarLista(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAutorizarLista";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		autorizarLista(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarLista(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarLista";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerLista(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarLista(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarLista";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarLista(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarFacebook()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarFacebook";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarFacebook();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarProspectos()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarProspectos";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarProspectos();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCausas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarCausas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarCausas(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarCausas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarCausas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerCausasEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarPromotores(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPromotores";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPromotores(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCampanas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarCampanas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarCampanas(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarCampanas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarCampanas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerCampanasEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarDetallesSeguimiento(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarDetallesSeguimiento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarDetalleSeguimiento(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarProgramas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarProgramas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarProgramas(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarProgramas(ID)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProgramas";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerProgramasEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarEstatus(idEstatus)
{
	if(id==0)
	{
		id			= idEstatus
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarEstatus";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarEstatus(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarEstatus(idEstatus)
{
	if(id==0)
	{
		id			= idEstatus
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarEstatus";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerEstatusEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarTraspaso(idTraspaso)
{
	if(id==0)
	{
		id			= idTraspaso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarTraspaso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarTraspaso(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarSubLinea(idSubLinea)
{
	if(id==0)
	{
		id			= idSubLinea
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarSubLinea";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarSubLinea(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarSubLinea(idSubLinea)
{
	if(id==0)
	{
		id			= idSubLinea
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarSubLinea";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerSubLinea(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarConteo(idConteo)
{
	if(id==0)
	{
		id			= idConteo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarConteo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarConteo(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarConteo(idConteo)
{
	if(id==0)
	{
		id			= idConteo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarConteo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerConteo(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarProducidoProducto(idProducido)
{
	if(id==0)
	{
		id			= idProducido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarProducidoProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarProducidoProducto(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarProducidoProducto(idProducido)
{
	if(id==0)
	{
		id			= idProducido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProducidoProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		editarProducidoProducto(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarSalida(idSalida)
{
	if(id==0)
	{
		id			= idSalida
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarSalida";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarSalidaControl(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarSalida(idSalida)
{
	if(id==0)
	{
		id			= idSalida
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarSalida";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerSalidaControl(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoCancelarPedido(idPedido)
{
	if(id==0)
	{
		id			= idPedido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoCancelarPedido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		cancelarPedido(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarPedido(idPedido)
{
	if(id==0)
	{
		id			= idPedido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarPedido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerPedido(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarPedido(idPedido)
{
	if(id==0)
	{
		id			= idPedido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPedido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPedido(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarFicheroProveedor(idFichero)
{
	if(id==0)
	{
		id			= idFichero
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarFicheroProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarFichero(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoCancelarVentaServicios(idCotizacionPadre,idCotizacion,idProducto)
{
	if(id==0)
	{
		id			= idCotizacionPadre
		idAdicional	= idCotizacion
		idAdicional2= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoCancelarVentaServicios";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		cancelarVentaServicios(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarContactoProveedor(idContacto,mensaje,idProveedor)
{
	if(id==0)
	{
		id				= idContacto
		idAdicional		= idProveedor
		Mensaje			= 'moduloDistinto';
		modulo			= "borrarContactoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		window.location.href=base_url+'proveedores/borrarContacto/'+id+'/'+idAdicional;
		$("#ventanaConfirmacion").dialog('close');
	}	
}

function accesoBorrarMaterialRecibido(idRecibido)
{
	if(id==0)
	{
		id				= idRecibido
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarMaterialRecibido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarMaterialRecibido(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarProductoRecibido(idRecibido)
{
	if(id==0)
	{
		id				= idRecibido
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarProductoRecibido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarProductoRecibido(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarInventarioRecibido(idRecibido)
{
	if(id==0)
	{
		id				= idRecibido
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarInventarioRecibido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarInventarioRecibido(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarServicioRecibido(idRecibido)
{
	if(id==0)
	{
		id				= idRecibido
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarServicioRecibido";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarServicioRecibido(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAgregarProveedorCompraServicio(idMaterial)
{
	if(id==0)
	{
		id			= idMaterial
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAgregarProveedorCompraServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		formularioAgregarProveedorCompra(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCostoProveedorServicio(idServicio,idProveedor,i)
{
	if(id==0)
	{
		id				= idServicio
		idAdicional		= idProveedor
		idAdicional2	= i
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarCostoProveedorServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarProveedorServicio(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarCostoProveedorServicio(idServicio,idProveedor,i)
{
	if(id==0)
	{
		id				= idServicio
		idAdicional		= idProveedor
		idAdicional2	= i
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoEditarCostoProveedorServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		editarCostoProveedorServicio(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarServicioConsumo(idServicio)
{
	if(id==0)
	{
		id			= idServicio
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarServicioConsumo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerServicio(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarServicioConsumo(idServicio)
{
	if(id==0)
	{
		id			= idServicio
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarServicioConsumo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarServicio(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarStatus(idStatus)
{
	if(id==0)
	{
		id			= idStatus
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarStatus";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerStatusEditar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function confirmarBorrarStatus(idStatus)
{
	if(id==0)
	{
		id			= idStatus
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarStatus";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarStatus(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAgregarProveedorCompraInventario(idInventario)
{
	if(id==0)
	{
		id			= idInventario
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAgregarProveedorCompraInventario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		formularioAgregarProveedorCompra(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAgregarProveedorCompraMaterial(idMaterial)
{
	if(id==0)
	{
		id			= idMaterial
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAgregarProveedorCompraMaterial";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProveedoresCompraAsociar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAsignarDescuento(idProducto)
{
	//alert(id);
	if(id==0)
	{
		id			= idProducto+1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAsignarDescuento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'descuentos'))return;
		
		formularioDescuentoProducto(id-1);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarMotivo(idMotivo)
{
	if(id==0)
	{
		id			= idMotivo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarMotivo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerMotivo(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function confirmarBorrarMotivo(idMotivo)
{
	if(id==0)
	{
		id			= idMotivo
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarMotivo";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarMotivo(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoAgregarProveedorCompraProducto(idProducto)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoAgregarProveedorCompraProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProveedoresCompra(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarProductoTerminado(idDetalle)
{
	if(id==0)
	{
		id			= idDetalle
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarProductoTerminado";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarProductoTerminado(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarProductoTerminado(idDetalle)
{
	if(id==0)
	{
		id			= idDetalle
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProductoTerminado";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProductoTerminado(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function confirmarBorrarProcesoOrden(idRelacion)
{
	if(id==0)
	{
		id			= idRelacion
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarProcesoOrden";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarProcesoOrden(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoExportarProduccion()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoExportarProduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		exportarProduccion();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarProduccion()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarProduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarProduccion();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoExportarProductos()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoExportarProductos";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		exportarProductos();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarProductos()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarProductos";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarProductos();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoExportarMateriales()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoExportarMateriales";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		exportarMateriales();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarMateriales()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarMateriales";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarMateriales();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoExportarProveedores()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoExportarProveedores";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		exportarProveedores();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarProveedores()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarProveedores";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarProveedores();
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoExportarClientes()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoExportarClientes";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		exportarClientes();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoImportarClientes()
{
	if(id==0)
	{
		id			= 1
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoImportarClientes";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'importar'))return;
		
		formularioImportarClientes();
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarDepartamentoConfiguracion(idDepartamento)
{
	if(id==0)
	{
		id			= idDepartamento
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarDepartamentoConfiguracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerDepartamento(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarProductoConfiguracion(idProducto)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProductoConfiguracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProducto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarTipoConfiguracion(idGasto)
{
	if(id==0)
	{
		id			= idGasto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarTipoConfiguracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerGasto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarNombreConfiguracion(idNombre)
{
	if(id==0)
	{
		id			= idNombre
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarNombreConfiguracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerNombre(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarDepartamento(idDepartamento,mensaje)
{
	if(id==0)
	{
		id			= idDepartamento
		Mensaje		= mensaje;
		modulo		= "borrarDepartamento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarDepartamento/'+id;
	}
}

function borrarProductoAdministracion(idProducto,mensaje)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= mensaje;
		modulo		= "borrarProductoAdministracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarProducto/'+id;
	}
}

function borrarTipoGasto(idGasto,mensaje)
{
	if(id==0)
	{
		id			= idGasto
		Mensaje		= mensaje;
		modulo		= "borrarTipoGasto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarGasto/'+id;
	}
}

function borrarNombre(idNombre,mensaje)
{
	if(id==0)
	{
		id			= idNombre
		Mensaje		= mensaje;
		modulo		= "borrarNombre";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarNombre/'+id;
	}
}

function accesoEditarTienda(idTienda)
{
	if(id==0)
	{
		id			= idTienda
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarTienda";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerTienda(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}
	
function accesoBorrarTienda(idTienda)
{
	if(id==0)
	{
		id			= idTienda
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarTienda";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarTienda(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarForma(idForma)
{
	if(id==0)
	{
		id			= idForma
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarForma";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerForma(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}
	
function accesoBorrarForma(idForma,mensaje)
{
	if(id==0)
	{
		id			= idForma
		Mensaje		= mensaje;
		modulo		= "accesoBorrarForma";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarForma/'+id;
	}
}

function accesoEditarServicioConfiguracion(idServicio)
{
	if(id==0)
	{
		id			= idServicio
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarServicioConfiguracion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerServicio(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}
	
function confirmarBorrarServicio(idServicio)
{
	if(id==0)
	{
		id			= idServicio
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarServicio(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarLinea(idLinea)
{
	if(id==0)
	{
		id			= idLinea
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarLinea";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerLinea(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function borrarLinea(idLinea,mensaje)
{
	if(id==0)
	{
		id			= idLinea
		Mensaje		= mensaje;
		modulo		= "borrarLinea";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarLinea/'+id;
	}
}

function accesoEditarDivisa(idDivisa)
{
	if(id==0)
	{
		id			= idDivisa
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarDivisa";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerDivisa(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function borrarDivisa(idDivisa,mensaje)
{
	if(id==0)
	{
		id			= idDivisa
		Mensaje		= mensaje;
		modulo		= "borrarDivisa";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarDivisa/'+id;
	}
}

function accesoEditarProceso(idProceso)
{
	if(id==0)
	{
		id			= idProceso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProceso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProceso(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarProceso(idProceso,mensaje)
{
	if(id==0)
	{
		id			= idProceso
		Mensaje		= mensaje;
		modulo		= "borrarProceso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarProceso/'+id;
	}
}

function accesoBorrarEmisor(idEmisor)
{
	if(id==0)
	{
		id			= idEmisor
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarEmisor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarEmisor(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarEmisor(idEmisor)
{
	if(id==0)
	{
		id			= idEmisor
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarEmisor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerEmisor(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarZona(idZona)
{
	if(id==0)
	{
		id			= idZona
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarZona";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerZona(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function confirmarBorrarZona(idZona,mensaje)
{
	if(id==0)
	{
		id			= idZona
		Mensaje		= mensaje;
		modulo		= "confirmarBorrarZona";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarZona(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarConversion(idConversion)
{
	if(id==0)
	{
		id			= idConversion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarConversion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarConversion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarConversion(idConversion)
{
	if(id==0)
	{
		id			= idConversion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarConversion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerConversion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarUnidad(idUnidad)
{
	if(id==0)
	{
		id			= idUnidad
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarUnidad";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerUnidad(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarUnidad(idUnidad,mensaje)
{
	if(id==0)
	{
		id			= idUnidad
		Mensaje		= mensaje;
		modulo		= "borrarUnidad";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarUnidad/'+id;
	}
}

function accesoEditarCuenta(idCuenta)
{
	if(id==0)
	{
		id			= idCuenta
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarCuenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerCuenta(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function borrarCuenta(idCuenta,mensaje)
{
	if(id==0)
	{
		id			= idCuenta
		Mensaje		= mensaje;
		modulo		= "borrarCuenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'bancos/borrarCuenta/'+id;
	}
}

function accesoEditarBanco(idBanco)
{
	if(id==0)
	{
		id			= idBanco
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarBanco";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerBanco(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarBanco(idBanco,mensaje)
{
	if(id==0)
	{
		id			= idBanco
		Mensaje		= mensaje;
		modulo		= "borrarBanco";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'bancos/borrarBanco/'+id;
	}
}

function accesoEditarRol(idRol)
{
	if(id==0)
	{
		id			= idRol
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarRol";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerRol(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarRolUsuario(idRol,mensaje)
{
	if(id==0)
	{
		id			= idRol
		Mensaje		= mensaje;
		modulo		= "borrarRolUsuario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'configuracion/borrarRol/'+id;
	}
}

function confirmarReactivarUsuario(idUsuario,mensaje)
{
	if(id==0)
	{
		id			= idUsuario
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarReactivarUsuario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		reactivarUsuario(id);
		$("#ventanaConfirmacion").dialog('close');
		//window.location.href=base_url+'configuracion/borrarUsuario/'+id;
	}
}

function confirmarBorrarUsuario(idUsuario,mensaje)
{
	if(id==0)
	{
		id			= idUsuario
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarUsuario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarUsuario(id);
		$("#ventanaConfirmacion").dialog('close');
		//window.location.href=base_url+'configuracion/borrarUsuario/'+id;
	}
}

function accesoEditarUsuario(idUsuario)
{
	if(id==0)
	{
		id			= idUsuario
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarUsuario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerUsuario(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function desactivarBotonSistema(boton)
{
	$('#'+boton).addClass('escalaGrises');
	$('#a-'+boton).addClass('escalaGrises');
	$('#label-'+boton).addClass('escalaGrises');
	
	$('#'+boton+'> img').addClass('escalaGrises');
	
	$('#'+boton).prop("onclick", null);
	$('#'+boton).attr("onclick", null);
	
	$('#'+boton).prop("href", null);
	$('#'+boton).attr("href", null);
}

//NÓMINA
function accesoBorrarPercepcion(idPercepcion)
{
	if(id==0)
	{
		id			= idPercepcion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPercepcion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarPercepcion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarPercepcion(idPercepcion)
{
	if(id==0)
	{
		id			= idPercepcion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarPercepcion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerPercepcion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarDeduccion(idDeduccion)
{
	if(id==0)
	{
		id			= idDeduccion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarDeduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarDeduccion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarDeduccion(idDeduccion)
{
	if(id==0)
	{
		id			= idDeduccion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarDeduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerDeduccion(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarPuestoNomina(idPuesto)
{
	if(id==0)
	{
		id			= idPuesto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPuestoNomina";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarPuesto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarPuestoNomina(idPuesto)
{
	if(id==0)
	{
		id			= idPuesto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarPuestoNomina";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerPuesto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarDepartamentoNomina(idDepartamento)
{
	if(id==0)
	{
		id			= idDepartamento
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarDepartamentoNomina";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarDepartamento(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarDepartamentoNomina(idDepartamento)
{
	if(id==0)
	{
		id			= idDepartamento
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarDepartamentoNomina";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerDepartamento(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarEmpleado(idEmpleado)
{
	if(id==0)
	{
		id			= idEmpleado
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarEmpleado";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarEmpleado(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarEmpleado(idEmpleado)
{
	if(id==0)
	{
		id			= idEmpleado
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarEmpleado";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerEmpleado(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

//

function accesoCancelarCfdi(idFactura)
{
	if(id==0)
	{
		id			= idFactura
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoCancelarCfdi";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//if(!comprobarCodigoNuevo(Mensaje,'cancelacion'))return;
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		obtenerFacturaCancelar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCobro(idIngreso)
{
	if(id==0)
	{
		id			= idIngreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarCobro";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		borrarCobro(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoCancelarOrden(idOrden)
{
	if(id==0)
	{
		id			= idOrden
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoCancelarOrden";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		obtenerDetallesOrden(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarMaterialProducto(idMaterial,idProducto,mensaje,pagina)
{
	if(id==0)
	{
		id			= idMaterial
		idAdicional	= idProducto
		Mensaje		= mensaje;
		Pagina		= pagina;
		modulo		= "borrarMaterialProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'produccion/borrarMaterial/'+id+'/'+idAdicional+'/'+Pagina;
	}
}

function accesoEditarProductoProduccion(idProducto,idMaterial)
{
	if(id==0)
	{
		id			= idProducto
		idAdicional	= idMaterial
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProductoProduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerMaterialEditar(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarProductoProduccion(idProducto,mensaje,pagina)
{
	if(id==0)
	{
		id			= idProducto
		idAdicional	= pagina
		Mensaje		= mensaje;
		modulo		= "borrarProductoProduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'produccion/borrarProductoCategoria/'+id+'/'+idAdicional;
	}
}

function accesoEditarProduccion(idProducto)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProduccion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerProducto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function confirmarBorrarMaterial(idMaterial,idProveedor,mensaje)
{
	if(id==0)
	{
		id			= idMaterial
		idAdicional	= idProveedor
		Mensaje		= 'moduloDistinto';
		modulo		= "confirmarBorrarMaterial";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarMaterial(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
		//window.location.href=base_url+'materiales/borrarMaterial/'+id+'/'+idAdicional;
	}
}

function accesoEditarMaterial(idMaterial,idProveedor)
{
	if(id==0)
	{
		id			= idMaterial
		idAdicional	= idProveedor
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarMaterial";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		
		obtenerMaterialEditar(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarMobiliario(idInventario)
{
	if(id==0)
	{
		id			= idInventario
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarMobiliario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarMobiliario(id);
		$("#ventanaConfirmacion").dialog('close');
		//window.location.href=base_url+'inventarioProductos/borrarInventario/'+id;
	}
}

function accesoEditarMobiliario(idInventario)
{
	if(id==0)
	{
		id			= idInventario
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarMobiliario";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerInventario(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarServicioProducto(idServicio,mensaje)
{
	if(id==0)
	{
		id			= idServicio
		Mensaje		= mensaje;
		modulo		= "borrarServicioProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		window.location.href=base_url+'inventarioProductos/borrarServicioProducto/'+id;
	}
}


function accesoEditarServicio(idProducto)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarServicio";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerServicio(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCostoProveedor(idProducto,idProveedor,i)
{
	if(id==0)
	{
		id				= idProducto
		idAdicional		= idProveedor
		idAdicional2	= i
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoBorrarCostoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarProveedorProducto(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarCostoProveedor(idProducto,idProveedor,i)
{
	if(id==0)
	{
		id				= idProducto
		idAdicional		= idProveedor
		idAdicional2	= i
		Mensaje			= 'moduloDistinto';
		modulo			= "accesoEditarCostoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		editarCostoProveedor(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarProducto(idProducto)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerDetallesProducto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarProducto(idProducto,mensaje)
{
	if(id==0)
	{
		id			= idProducto
		Mensaje		= mensaje;
		modulo		= "accesoBorrarProducto";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		//window.location.href=base_url+'inventarioProductos/borrarProducto/'+id;
		
		borrarProducto(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarComprobanteEgreso(idComprobante,idEgreso)
{
	if(id==0)
	{
		id			= idComprobante
		idAdicional	= idEgreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarComprobanteEgreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;	
		borrarComprobanteEgreso(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarEgreso(idEgreso)
{
	if(id==0)
	{
		id			= idEgreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarEgreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		borrarEgreso(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarEgreso(idEgreso)
{
	if(id==0)
	{
		id			= idEgreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarEgreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
	
		obtenerEgresoEditar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarComprobanteIngreso(idComprobante,idIngreso)
{
	if(id==0)
	{
		id			= idComprobante
		idAdicional	= idIngreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarComprobanteIngreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;	
		borrarComprobante(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarIngreso(idIngreso)
{
	if(id==0)
	{
		id			= idIngreso
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarIngreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;	
		borrarIngreso(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function acccesoEditarIngreso(idIngreso)
{
	if(id==0)
	{
		id			= idIngreso
		Mensaje		= 'moduloDistinto';
		modulo		= "acccesoEditarIngreso";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
	
		obtenerIngresoEditar(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarComprobanteCompra(idComprobante,idCompra,idRecibido)
{
	if(id==0)
	{
		id			= idComprobante
		idAdicional	= idCompra
		idAdicional2= idRecibido
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarComprobanteCompra";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarComprobanteCompra(id,idAdicional,idAdicional2);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarPagoCompraMaterial(idEgreso,idCompra)
{
	if(id==0)
	{
		id			= idEgreso
		idAdicional	= idCompra
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarPagoCompraMaterial";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarPago(id,idAdicional);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function cancelarCompra(idCompra,mensaje,seccion)
{
	if(id==0)
	{
		id			= idCompra
		idAdicional	= seccion
		Mensaje		= mensaje;
		modulo		= "cancelarCompra";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//if(!comprobarCodigoNuevo(Mensaje,'cancelacion'))return;
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'compras/cancelarCompra/'+id+'/'+idAdicional;
	}
}


function borrarCompra(idCompra,mensaje,seccion)
{
	if(id==0)
	{
		id			= idCompra
		idAdicional	= seccion
		Mensaje		= mensaje;
		modulo		= "borrarCompra";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'compras/borrarCompra/'+id+'/'+idAdicional;
	}
}

function accesoBorrarSeguimientoProveedor(idArchivo)
{
	if(id==0)
	{
		id			= idArchivo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarSeguimientoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		borrarSeguimiento(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarSeguimientoProveedor(idProveedor)
{
	if(id==0)
	{
		id			= idProveedor
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarSeguimientoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerSeguimientoEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoBorrarArchivoSeguimientoProveedor(idArchivo)
{
	if(id==0)
	{
		id			= idArchivo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarArchivoSeguimientoProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		borrarArchivoSeguimiento(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarArchivoSeguimiento(idArchivo)
{
	if(id==0)
	{
		id			= idArchivo
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarArchivoSeguimiento";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		borrarArchivoSeguimiento(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarProveedor(idProveedor)
{
	if(id==0)
	{
		id			= idProveedor
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerProveedor(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarProveedor(idProveedor,mensaje)
{
	if(id==0)
	{
		id			= idProveedor
		Mensaje		= mensaje;
		modulo		= "borrarProveedor";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'proveedores/borrarProveedor/'+id;
	}
}

function accesoCancelarVenta(idCotizacion,mensaje)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= mensaje;
		modulo		= "accesoCancelarVenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//if(!comprobarCodigoNuevo(Mensaje,'cancelacion'))return;
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		//window.location.href=base_url+'ficha/cancelarVenta/'+id;
		cancelarVenta(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarVenta(idCotizacion,mensaje)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= mensaje;
		modulo		= "accesoBorrarVenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//if(!comprobarCodigoNuevo(Mensaje,'cancelacion'))return;
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		//window.location.href=base_url+'ficha/borrarVenta/'+id;
		borrarVenta(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoCancelarCotizacion(idCotizacion)
{
	
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoCancelarCotizacion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//if(!comprobarCodigoNuevo(Mensaje,'cancelacion'))return;
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		cancelarCotizacion(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCotizacion(idCotizacion,mensaje,idCliente)
{
	
	if(id==0)
	{
		id			= idCotizacion
		idAdicional	= idCliente
		Mensaje		= mensaje;
		modulo		= "accesoBorrarCotizacion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'ficha/borrarCotizacion/'+id+'/'+idAdicional;
	}
}

function accesoConvertirVenta(idCotizacion)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoConvertirVenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerDetallesCotizacion(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoReutilizarVenta(idCotizacion)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoReutilizarVenta";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerVentaEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function borrarCotizacionCliente(idCotizacion,mensaje)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= mensaje;
		modulo		= "borrarCotizacionCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		confirmarBorrarCotizacion(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarCotizacion(idCotizacion)
{
	if(id==0)
	{
		id			= idCotizacion
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarCotizacion";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerCotizacion(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarContactoCliente(idContacto,mensaje,idCliente)
{
	if(id==0)
	{
		id				= idContacto
		idAdicional		= idCliente
		Mensaje			= mensaje;
		modulo			= "accesoBorrarContactoCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		//alert('Moli')
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		//window.location.href=base_url+'ficha/borrarContacto/'+id+'/'+idAdicional;
		borrarContactoCliente(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}


function accesoEditarContactoCliente(idContacto)
{
	if(id==0)
	{
		id			= idContacto
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarContactoCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerContacto(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarFicheroCliente(idFichero)
{
	if(id==0)
	{
		id			= idFichero
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarFicheroCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarFichero(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarSeguimientoCliente(idSeguimiento)
{
	if(id==0)
	{
		id			= idSeguimiento
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarSeguimientoCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		borrarSeguimientoCrm(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarSeguimientoCliente(idSeguimiento)
{
	if(id==0)
	{
		id			= idSeguimiento
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarSeguimientoCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerSeguimientoEditar(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoEditarCliente(idCliente)
{
	if(id==0)
	{
		id			= idCliente
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoEditarCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'editar'))return;
		obtenerCliente(id)
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoBorrarCliente(idCliente,mensaje)
{
	if(id==0)
	{
		id			= idCliente
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoBorrarCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		//window.location.href=base_url+'clientes/borrarCliente/'+id;
		borrarCliente(id);
		$("#ventanaConfirmacion").dialog('close');
	}
}

function accesoPrecio1(ID,mensaj)
{
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoPrecio1";
		
		$('#lblAccionGlobal').html('Aplicar a todos los productos');
		$('#filaAccion').fadeIn();
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
		
		if(document.getElementById('chkAplicarAccion').checked)
		{
			asignarPrecios1();
			PRECIO1=true;
			
			obtenerProductosVenta();
		}
		else
		{
			asignarPrecio1()
		}
				
		$("#ventanaConfirmacion").dialog('close');
	}
}

function recargarPagina()
{
	location.reload();
}

opcionFactura	= false;
seccion			= 'ventas';
function accesoOpcionFactura(Seccion,ID)
{
	opcionFactura=false;
	
	if(id==0)
	{
		id			= ID
		Mensaje		= 'moduloDistinto';
		modulo		= "accesoOpcionFactura";
		seccion		= Seccion;
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		codigo = $('#txtCodigoConfirmacion').val()
	
		if(codigo==null)return;

		if(codigo.length==0)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}

		codigo	= hex_sha1(codigo);

		ejecutar=$.ajax(
		{
			async:false,
			beforeSend:function(objeto)
			{
			},
			type:"POST",
			url:base_url+'configuracion/revisarCodigoUsuario',
			data:
			{
				idUsuario: 			$('#txtIdUsuarioSistema').val(),
				claveCancelacion: 	codigo
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				data=eval(data);

				switch(data[0])
				{
					case "0":
							notify('El código es incorrecto',500,5000,'error',30,3);
						break;

					case "1":
							opcionFactura=true;
						
							revisarAccesoFacturacion(seccion,id);
						
							$("#ventanaConfirmacion").dialog('close');
						break;

				}

			},
			error:function(datos)
			{
				return false;
			}
		});		
	}
}

function revisarAccesoFacturacion(Modulo,ID)
{
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
		},
		type:"POST",
		url:base_url+'configuracion/revisarAccesoFacturacion',
		data:
		{
			modulo:modulo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			
			switch(Modulo)
			{
				case 'ventas':
					obtenerDatosFactura(ID);
				break;

				case 'global':
					formularioFacturaGlobal();
				break;
			}

			/*switch(data[0])
			{
				case "0":
					switch(Modulo)
					{
						case 'ventas':
							obtenerDatosFactura(ID);
						break;

						case 'global':
							formularioFacturaGlobal();
						break;
					}

				break;
				
				case "1":
						notify('Existe un usuario realizando una factura favor de intentarlo en 3 minutos más',500,5000,'error',30,3);
					break;

			}*/
			
		},
		error:function(datos)
		{
			return false;
		}
	});		
}

function actualizarAccesoFacturacion(accesoFacturacion)
{
	ejecutar=$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'configuracion/actualizarAccesoFacturacion',
		data:
		{
			accesoFacturacion:accesoFacturacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			
			
		},
		error:function(datos)
		{
			return '';
		}
	});		
}


id			=0;
Mensaje		="";
modulo		="";
idAdicional	=0;
idAdicional2=0;
Pagina		=0;

$(document).ready(function()
{
	$("#ventanaConfirmacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
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
			id	=0;
		}
	});
});

function comprobarCodigoNuevo(mensaje,operacion)
{	
	//codigo = prompt("Por favor ingrese el código de validación")
	codigo = $('#txtCodigoConfirmacion').val()
	
	if(codigo==null)return;
	
	if(codigo.length==0)
	{
		notify('El código es incorrecto',500,5000,'error',30,3);
		return false;
	}
	
	codigo	= hex_sha1(codigo);

	if(operacion=='borrado')
	{
		if(codigo!=codigoBorrado)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}

	if(operacion=='editar')
	{
		if(codigo!=codigoEditar)
		{
			notify('El código es incorrecto',500,5000,'error',30,3);
			return false;
		}
	}
	
	if(mensaje=='moduloDistinto')return true; //Significa que se entrara a otro modulo donde no se requiere mensaje
	if(confirm(mensaje)) return true;
	
	return false;
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
		case "borrarCliente": borrarCliente(); break;
		case "accesoEditarCliente": accesoEditarCliente(); break;
		case "accesoEditarSeguimientoCliente": accesoEditarSeguimientoCliente(); break;
		case "accesoBorrarSeguimientoCliente": accesoBorrarSeguimientoCliente(); break;
		case "accesoBorrarFicheroCliente": accesoBorrarFicheroCliente(); break;
		
		case "accesoBorrarContactoCliente": accesoBorrarContactoCliente(); break;
		case "accesoEditarContactoCliente": accesoEditarContactoCliente(); break;
	}
}

/*function borrarContactoCliente(idContacto,mensaje,idCliente)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'ficha/borrarContacto/'+idContacto+'/'+idCliente;
}*/

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
		window.location.href=base_url+'ficha/borrarContacto/'+id+'/'+idAdicional;
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

function borrarCliente(idCliente,mensaje)
{
	if(id==0)
	{
		id			= idCliente
		Mensaje		= mensaje;
		modulo		= "borrarCliente";
		
		$("#ventanaConfirmacion").dialog('open');
		return;
	}
	
	if(id>0)
	{
		if(!comprobarCodigoNuevo(Mensaje,'borrado'))return;
	
		window.location.href=base_url+'clientes/borrarCliente/'+id;
	}
}



function borrarProveedor(idProveedor,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'proveedores/borrarProveedor/'+idProveedor;
}

function borrarContactoProveedor(idContacto,mensaje,idProveedor)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'proveedores/borrarContacto/'+idContacto+'/'+idProveedor;
}

function borrarCompra(idCompra,mensaje,seccion)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'compras/borrarCompra/'+idCompra+'/'+seccion;
}

function borrarMaterial(idMaterial,idProveedor,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'materiales/borrarMaterial/'+idMaterial+'/'+idProveedor;
}

function borrarProducto(idProducto,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'inventarioProductos/borrarProducto/'+idProducto;
}

function borrarServicioProducto(idProducto,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'inventarioProductos/borrarServicioProducto/'+idProducto;
}

function borrarProductoProduccion(idProducto,mensaje,pagina)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'produccion/borrarProductoCategoria/'+idProducto+'/'+pagina;
}

function borrarMaterialProducto(idMaterial,idProducto,mensaje,pagina)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'produccion/borrarMaterial/'+idMaterial+'/'+idProducto+'/'+pagina;
}

function borrarOrden(idOrden,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'ordenes/borrarOrden/'+idOrden;
}

function borrarCotizacion(idCotizacion,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'ficha/borrarCotizacion/'+idCotizacion;
}

function borrarCotizacionCliente(idCotizacion,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	confirmarBorrarCotizacion(idCotizacion)
}

function borrarVenta(idCotizacion,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'ficha/borrarVenta/'+idCotizacion;
}

function cancelarVenta(idCotizacion,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'ficha/cancelarVenta/'+idCotizacion;
}

function borrarUsuario(idUsuario,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarUsuario/'+idUsuario;
}

function accesoEditarUsuario(idUsuario)
{
	mensaje='moduloDistinto';
	if(!comprobarCodigo(mensaje))return;

	obtenerUsuario(idUsuario);
}

function accesoProductos()
{
	mensaje='moduloDistinto';
	if(!comprobarCodigo(mensaje))return;

	formularioProductos();
}

function accesoProductosEgresos()
{
	mensaje='moduloDistinto';
	if(!comprobarCodigo(mensaje))return;

	obtenerListaConceptos();
}


//PARA LOS MODULOS DE ADMINISTRTACIÓN

function borrarPersonal(idPersonal,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'administracion/borrarPersonal/'+idPersonal;
}

function accesoBorrarIngreso(idIngreso)
{
	mensaje='moduloDistinto';
	if(!comprobarCodigo(mensaje))return;

	borrarIngreso(idIngreso);
}

function accesoBorrarEgreso(idEgreso)
{
	mensaje='moduloDistinto';
	if(!comprobarCodigo(mensaje))return;

	borrarEgreso(idEgreso);
}

//CONFIGURACION

function borrarRolUsuario(idRol,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarRol/'+idRol;
}

function borrarBanco(idBanco,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'bancos/borrarBanco/'+idBanco;
}

function borrarCuenta(idCuenta,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'bancos/borrarCuenta/'+idCuenta;
}

function borrarUnidad(idUnidad,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarUnidad/'+idUnidad;
}

function borrarZona(idZona,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarZona/'+idZona;
}

function borrarDivisa(idDivisa,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarDivisa/'+idDivisa;
}

function borrarLinea(idLinea,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarLinea/'+idLinea;
}

function borrarProceso(idProceso,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarProceso/'+idProceso;
}

function borrarDepartamento(idDepartamento,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarDepartamento/'+idDepartamento;
}

function borrarProductoAdministracion(idProducto,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarProducto/'+idProducto;
}

function borrarTipoGasto(idGasto,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarGasto/'+idGasto;
}

function borrarNombre(idNombre,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarNombre/'+idNombre;
}

function borrarServicio(idServicio,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarServicio/'+idServicio;
}

function borrarForma(idForma,mensaje)
{
	if(!comprobarCodigo(mensaje))return;

	window.location.href=base_url+'configuracion/borrarForma/'+idForma;
}
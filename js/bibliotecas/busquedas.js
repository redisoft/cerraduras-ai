modulo='';
function buscarDato(descripcion,seccion) 
{
	url='';
	
	modulo=seccion;
	
	if(seccion=='clientes')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='ventas')
	{
		url=base_url+'clientes/autoCompletadoClientes';
		$("#listaInformacion").css("margin-left","20.5%");
	}
	
	if(seccion=='reporteVentas')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='productos')
	{
		url=base_url+'inventarioProductos/autoCompletadoProductos';
	}
	
	if(seccion=='servicios')
	{
		url=base_url+'inventarioProductos/autoCompletadoServicios';
	}
	
	if(seccion=='bodegas')
	{
		url=base_url+'inventario/autoCompletadoBodegas';
	}
	
	if(seccion=='proveedores')
	{
		url=base_url+'proveedores/autoCompletadoProveedores';
	}
	
	if(seccion=='materiales')
	{
		url=base_url+'materiales/autoCompletadoMateriales';
	}
	
	if(seccion=='compras')
	{
		url=base_url+'proveedores/autoCompletadoProveedores';
	}
	
	if(seccion=='facturas')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='produccion')
	{
		url=base_url+'produccion/autoCompletadoProduccion';
	}
	
	if(seccion=='cotizaciones')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='usuarios')
	{
		url=base_url+'configuracion/autoCompletadoUsuarios';
	}
	
	if(seccion=='fichaCotizacion')
	{
		url=base_url+'clientes/autoCompletadoCotizacion';
	}
	
	if(seccion=='fichaVentas')
	{
		url=base_url+'clientes/autoCompletadoVentas';
	}
	
	if(seccion=='clientesVentas')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='productosGraficas')
	{
		url=base_url+'inventarioProductos/autoCompletadoProductos';
	}
	
	if(seccion=='usuariosVentas')
	{
		url=base_url+'configuracion/autoCompletadoUsuarios';
	}
	
	if(seccion=='identificador')
	{
		url=base_url+'configuracion/autoCompletadoIdentificador';
	}
	
	if(seccion=='productosVentas')
	{
		url=base_url+'inventarioProductos/autoCompletadoProductos';
		
		$("#listaInformacion").css("margin-left","67%");
	}
	
	if(seccion=='productoTerminado')
	{
		url=base_url+'inventarioProductos/autoCompletadoProductosAlmacen';
		
		//$("#listaInformacion").css("margin-left","70%");
	}
	
	if(seccion=='materialesProduccion')
	{
		url=base_url+'materiales/autoCompletadoMateriales';
	}
	
	if(seccion=='facturasClientes')
	{
		url=base_url+'clientes/autoCompletadoClientes';
	}
	
	if(seccion=='ordenProduccion')
	{
		url=base_url+'ordenes/autoCompletadoProductosProduccion';
	}
	
	if(seccion=='identificadorCobranza')
	{
		url=base_url+'configuracion/autoCompletadoIdentificador';
		$("#listaInformacion").css("margin-left","63%");
	}
	
	if(seccion=='cobranza')
	{
		url=base_url+'clientes/autoCompletadoClientes';
		$("#listaInformacion").css("margin-left","41%");
	}
	
	if(descripcion.length == 0) 
	{
		if(seccion=='bodegas' || seccion=='usuarios' || seccion=='identificador' || seccion=='materialesProduccion')
		{
			$('#listaInformacionBodega').hide();
		}
		else
		{
			$('#listaInformacion').hide();
		}
	} 
	else 
	{
		$.post(url, {descripcion: ""+descripcion+"", idCliente: ""+$('#id_cli').val()+"" }, function(data)
		{
			if(data.length >0) 
			{
				if(seccion=='bodegas' || seccion=='usuarios' || seccion=='identificador' || seccion=='materialesProduccion')
				{
					$('#listaInformacionBodega').show();
					$('#autoListaInformacionBodega').html(data);
				}
				else
				{
					$('#listaInformacion').show();
					$('#autoListaInformacion').html(data);
				}
			}
		});
	}
}

function datoEncontrado(id,descripcion,idUnidad,unidad) 
{
	setTimeout("$('#listaInformacion').hide();", 200);
	setTimeout("$('#listaInformacionBodega').hide();", 200);
	
	//alert(id)
	
	if(isNaN(id))
	{
		if(modulo!='fichaVentas' && modulo!='fichaCotizacion' )
		{
			$('#id_cli').val('');
		}
		
		return;
	}
	
	if(modulo=='clientes')
	{
		direccion=base_url+"clientes/prebusqueda/"+id;
	}
	
	if(modulo=='productos')
	{
		direccion=base_url+"inventarioProductos/prebusquedaProducto/"+id;
	}
	
	if(modulo=='servicios')
	{
		direccion=base_url+"inventarioProductos/prebusquedaServicios/"+id;
	}
	
	if(modulo=='bodegas')
	{
		direccion=base_url+"inventario/prebusqueda/"+id;
	}
	
	if(modulo=='reporteVentas')
	{
		direccion=base_url+"reportes/busquedaClienteVentas/"+id;
	}
	
	if(modulo=='proveedores')
	{
		direccion=base_url+"proveedores/prebusqueda/"+id;
	}
	
	if(modulo=='materiales')
	{
		direccion=base_url+"materiales/prebusqueda/"+id;
	}
	
	if(modulo=='ventas')
	{
		direccion=base_url+"reportes/busquedaClienteVentas/"+id;
	}
	
	if(modulo=='compras')
	{
		direccion=base_url+"reportes/busquedaProveedor/"+id;
	}
	
	if(modulo=='facturas')
	{
		direccion=base_url+"reportes/busquedaCliente/"+id;
	}
	
	if(modulo=='cobranza')
	{
		direccion=base_url+"reportes/busquedaClienteVentasCobranza/"+id;
	}
	
	if(modulo=='produccion')
	{
		direccion=base_url+"produccion/busquedaProduccion/"+id;
	}
	
	if(modulo=='fichaCotizacion')
	{
		direccion=base_url+"clientes/prebusquedaCotizacion/"+id;
	}
	
	if(modulo=='fichaVentas')
	{
		direccion=base_url+"clientes/prebusquedaVentas/"+id;
	}
	
	if(modulo=='usuariosVentas')
	{
		direccion=base_url+"reportes/busquedaUsuarioVentas/"+id;
	}
	
	if(modulo=='identificador')
	{
		direccion=base_url+"reportes/busquedaIdentificadorVentas/"+id;
	}
	
	if(modulo=='identificadorCobranza')
	{
		direccion=base_url+"reportes/busquedaIdentificadorCobranza/"+id;
	}
	
	if(modulo=='productosVentas')
	{
		direccion=base_url+"reportes/busquedaProductosVentas/"+id;
	} 
	
	if(modulo=='productoTerminado')
	{
		direccion=base_url+"produccion/busquedaProductoTerminado/"+id;
	} 
	
	if(modulo=='facturasClientes')
	{
		direccion=base_url+"facturacion/facturasCliente/"+id;
	}
	
	if(modulo=='productosGraficas')
	{
		if(verificarProductoGrafica(id)==false)
		{
			$('#txtBusquedas').val('');
			notify('El producto ya se encuentra en la lista',500,5000,'error',30,3);
			return;	
		}
		
		data='<tr>';
		data+='<td>';
		data+=filaUnidades;
		data+='<input type="hidden" id="txtIdProducto'+filaUnidades+'" value="'+id+'" />';
		data+='</td>';
		data+='<td>';
		data+=descripcion;
		data+='</td>';
		data+='</tr>';
		
		$('#tablaGraficaUnidades').append(data);
		$('#txtBusquedas').val('');
		filaUnidades++;
		return;
	}
	
	if(modulo=='clientesVentas')
	{
		$('#txtBusquedas').val(descripcion);
		$('#selectClientes').val(id);
		
		return;
	}
	
	if(modulo=='cotizaciones')
	{
		$('#txtBusquedas').val(descripcion);
		$('#id_cli').val(id);
		
		return;
	}
	
	if(modulo=='usuarios')
	{
		$('#txtUsuarios').val(descripcion);
		$('#nombreVendedor').val(id);
		
		return;
	}
	
	if(modulo=='materialesProduccion')
	{
		$('#txtMateriaPrima').val(descripcion);
		$('#selectMateriales').val(id);
		$('#txtUnidad').val(unidad);
		$('#txtIdUnidad').val(idUnidad);
		obtenerConversionesProduccion(idUnidad);
		
		return;
	}
	
	if(modulo=='ordenProduccion')
	{
		$('#txtBusquedaNombre').val(descripcion);
		$('#txtIdProducto').val(id);
		
		return;
	}
	
	window.location.href=direccion;
}

//Busqueda de archivos

function comprobarCertificado()
{
	cadena=	$('#fileCertificado').val();
	b=0;
	extension="";
	for(i=0;i<cadena.length;i++)
	{
		if(b==1)
		{
			extension+=cadena[i];
		}

		if(cadena[i]==".")
		{
			b=1;
		}
	}
	
	if(extension!='cer')
	{
		alert('El archivo de certificado es incorrecto');
		$('#fileCertificado').val('');
	}
}

function comprobarLlave()
{
	cadena=	$('#fileLlave').val();
	b=0;
	extension="";
	for(i=0;i<cadena.length;i++)
	{
		if(b==1)
		{
			extension+=cadena[i];
		}

		if(cadena[i]==".")
		{
			b=1;
		}
	}
	
	if(extension!='key')
	{
		alert('El archivo de llave privada es incorrecto');
		$('#fileLlave').val('');
	}
}


function obtenerConversionesProduccion(idUnidad)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarConversionesProduccion').html('<img src="'+ img_loader +'"/> Obteniendo las conversiones...');},
		type:"POST",
		url:base_url+'configuracion/obtenerConversionesProduccion',
		data:
		{
			"idUnidad":idUnidad
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarConversionesProduccion").html(data);
		},
		error:function(datos)
		{
			$("#cargarConversionesProduccion").html('Error al obtener las conversiones');
		}
	});//Ajax		
}
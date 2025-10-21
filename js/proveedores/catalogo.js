$(document).ready(function()
{
	$("#ventanaProveedores").dialog(
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
			/*'Recargar mapa': function() 
			{
				actualizarMapa();	  	  
			},*/
			'Guardar': function() 
			{
				registrarProveedor()		  	  
			},
			
		},
		close: function() 
		{
			$("#formularioProveedores").html('');
		}
	});
});

function formularioProveedores()
{
	$('#ventanaProveedores').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioProveedores').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de proveedores...');
		},
		type:"POST",
		url:base_url+'proveedores/formularioProveedores',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioProveedores').html(data)
		},
		error:function(datos)
		{
			$('#formularioProveedores').html('');
			notify('Error al obtener el formulario de proveedores',500,5000,'error',0,0);
		}
	});		
}

function actualizarMapa()
{
	if($('#txtLongitud').val()=="" || $('#txtLongitud').val()=="")
	{
		notify('La latitud y longitud son requeridas',500,5000,'error',5,5);
		return;
	}
	
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
			/*calle:			$('#domicilio').val(),
			numero:			$('#txtNumero').val(),
			colonia:		$('#txtColonia').val(),
			localidad:		$('#txtLocalidad').val(),
			municipio:		$('#txtMunicipio').val(),
			estado:			$('#estado').val(),
			pais:			$('#pais').val(),
			codigoPostal:	$('#txtCodigoPostal').val(),*/
			
			latitud:		$('#txtLatitud').val(),
			longitud:		$('#txtLongitud').val(),
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
	
	/*if($('#domicilio').val()=="")
	{
		mensaje+='El domicilio es incorrecto <br />';
	}*/
	
	if($('#txtTelefono').val()=="")
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
	
	$('#cargandoProveedores').fadeIn()
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoProveedores').html('<img src="'+ img_loader +'"/> Se esta registrando el proveedor, por favor espere...');
		},
		type:"POST",
		url:base_url+"proveedores/registrarProveedor",
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
			"email":			$('#email').val(),
			"pagina":			$('#pagina').val(),
			"banco":			$('#txtBanco').val(),
			"sucursal":			$('#txtSucursal').val(),
			"cuenta":			$('#txtCuenta').val(),
			"clabe":			$('#txtClabe').val(),
			"alias":			$('#txtAlias').val(),
			"nombreContacto":	$('#txtNombreContacto').val(),
			"telefonoContacto":	$('#txtTelefonoContacto').val(),
			"emailContacto":	$('#txtEmailContacto').val(),
			"departamento":		$('#txtDepartamento').val(),
			"extension":		$('#txtExtension').val(),
			"vende":			$("#txtVende").val(),
			latitud:			$('#txtLatitud').val(),
			longitud:			$('#txtLongitud').val(),
			diasCredito:		$('#txtDiasCredito').val(),
			
			"telefono":			$('#txtTelefono').val(),
			"fax":				$('#txtFax').val(),
			"lada":				$('#txtLada').val(),
			"ladaFax":			$('#txtLadaFax').val(),
			"idCuentaCatalogo":		$('#txtIdCuentaCatalogo').val(),
			"saldoInicial":		$('#txtSaldoInicial').val(),

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoProveedores').html('')
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
				
					if($('#txtPaginaActivada').val()!="proveedores")
					{
						notify('El proveedor se ha registrado correctamente',500,5000,'',30,5);
						
						
						if($('#txtPaginaActivada').val()=="servicios")
						{
							$('#txtIdProveedorServicio').val(data[2]);
							$('#txtBuscarProveedorServicio').val($('#empresa').val());
						}
						
						if(obtenerNumeros($('#txtAgregarProveedorInsumo').val())==1)
						{
							$('#proveedoresMateriales').val(data[2]);
							$('#txtBuscarProveedor').val(data[3]);
						}
						
						$('#ventanaProveedores').dialog('close');
						
						//location.reload();

						return;
					}
					
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			$('#cargandoProveedores').html('')
			notify('Error al registrar al proveedor',500,5000,'error',30,5);
		}
	});		
}
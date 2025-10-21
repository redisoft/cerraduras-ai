//DETALLES DE FACTURACIÒN
function formularioEmisores()
{
	$("#ventanaRegistrarEmisor").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioEmisores').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para registrar al emisor'+esperar);},
		type:"POST",
		url:base_url+'configuracion/formularioEmisores',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEmisores").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar al emisor',500,5000,'error',30,3);
			$("#formularioEmisores").html('');
		}
	}); 
}

$(document).ready(function()
{
	$("#ventanaRegistrarEmisor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:900,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');
			},
			'Registrar emisor': function() 
			{
				agregarEmisor();		  	  
			},
		},
		close: function() 
		{
			$('#formularioEmisores').html('');
		}
	});
	
	$("#ventanaEditarEmisor").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
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
				editarEmisor();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerEmisor').html('');
		}
	});
});

function agregarEmisor()
{
	mensaje="";
	
	if($('#txtEmpresa').val()=="")
	{
		mensaje+="La empresa es requerida<br />";
	}
	
	if($('#txtRfc').val()=="")
	{
		mensaje+="El RFC es requerido<br />";
	}
	
	if($('#txtCalle').val()=="")
	{
		mensaje+="La calle es requerida<br />";
	}
	
	/*if($('#txtNumeroExterior').val()=="")
	{
		mensaje+="El número exterior es requerido<br />";
	}
	
	if($('#txtColonia').val()=="")
	{
		mensaje+="La colonia es requerido<br />";
	}
	
	if($('#txtLocalidad').val()=="")
	{
		mensaje+="La localidad es requerida<br />";
	}*/
	
	if($('#txtMunicipio').val()=="")
	{
		mensaje+="El municipio es requerido<br />";
	}
	
	if($('#txtEstado').val()=="")
	{
		mensaje+="El estado es requerido<br />";
	}
	
	if($('#txtPais').val()=="")
	{
		mensaje+="El país es requerido<br />";
	}
	
	if($('#txtCodigoPostal').val()=="")
	{
		mensaje+="El código postal es requerido<br />";
	}
	
	if($('#txtFolioInicial').val()=="")
	{
		mensaje+="El folio inicial es requerido<br />";
	}
	
	if($('#txtFolioFinal').val()=="")
	{
		mensaje+="El folio final es requerido<br />";
	}
	
	if($('#fileCertificado').val()=="")
	{
		mensaje+="El certificado es requerido<br />";
	}
	
	if($('#numeroCertificado').val()=="")
	{
		mensaje+="El número de certificado es requerido<br />";
	}
	
	if($('#fileLlave').val()=="")
	{
		mensaje+="La llave es requerida<br />";
	}
	
	if($('#passwordLlave').val()=="")
	{
		mensaje+="El password de la llave es requerido<br />";
	}
	
	if($('#txtRegimenFiscal').val()=="")
	{
		mensaje+="El Regimen fiscal es requerido<br />";
	}


	if(mensaje.length>0)
	{
		notify(mensaje,500,7000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea registrar al emisor?')==false)
	{
		return;
	}
	
	$('#registrandoEmisor').html('<img src="'+base_url+'img/ajax-loader.gif"/>Registrando al emisor'+esperar);
	
	document.forms['frmEmisor'].submit();
}

function obtenerEmisor(idEmisor)
{
	$("#ventanaEditarEmisor").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerEmisor').html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo los datos para editar al emisor'+esperar);
		},
		type:"POST",
		url:base_url+'configuracion/obtenerEmisor',
		data:
		{
			idEmisor:idEmisor
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerEmisor").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar al emisor',500,5000,'error',30,3);
			$("#obtenerEmisor").html('');
		}
	}); 
}

function editarEmisor()
{
	mensaje="";
	
	if($('#txtEmpresa').val()=="")
	{
		mensaje+="La empresa es requerida<br />";
	}
	
	if($('#txtRfc').val()=="")
	{
		mensaje+="El RFC es requerido<br />";
	}
	
	if($('#txtCalle').val()=="")
	{
		mensaje+="La calle es requerida<br />";
	}
	
	/*if($('#txtNumeroExterior').val()=="")
	{
		mensaje+="El número exterior es requerido<br />";
	}
	
	if($('#txtColonia').val()=="")
	{
		mensaje+="La colonia es requerido<br />";
	}
	
	if($('#txtLocalidad').val()=="")
	{
		mensaje+="La localidad es requerida<br />";
	}*/
	
	if($('#txtMunicipio').val()=="")
	{
		mensaje+="El municipio es requerido<br />";
	}
	
	if($('#txtEstado').val()=="")
	{
		mensaje+="El estado es requerido<br />";
	}
	
	if($('#txtPais').val()=="")
	{
		mensaje+="El país es requerido<br />";
	}
	
	if($('#txtCodigoPostal').val()=="")
	{
		mensaje+="El código postal es requerido<br />";
	}
	
	if($('#txtFolioInicial').val()=="")
	{
		mensaje+="El folio inicial es requerido<br />";
	}
	
	if($('#txtFolioFinal').val()=="")
	{
		mensaje+="El folio final es requerido<br />";
	}
	
	
	/*if($('#fileCertificado').val()=="")
	{
		mensaje+="El certificado es requerido<br />";
	}*/
	
	if($('#numeroCertificado').val()=="")
	{
		mensaje+="El número de certificado es requerido<br />";
	}
	
	/*if($('#fileLlave').val()=="")
	{
		mensaje+="La llave es requerida<br />";
	}*/
	
	if($('#passwordLlave').val()=="")
	{
		mensaje+="El password de la llave es requerido<br />";
	}
	
	if($('#txtRegimenFiscal').val()=="")
	{
		mensaje+="El Regimen fiscal es requerido<br />";
	}


	if(mensaje.length>0)
	{
		notify(mensaje,500,7000,'error',30,3);
		return;
	}
	
	if(confirm('¿Realmente desea editar el registro del emisor?')==false)
	{
		return;
	}
	
	$('#editandoEmisor').html('<img src="'+base_url+'img/ajax-loader.gif"/>Se esta editando al emisor'+esperar);
	
	document.forms['frmEmisor'].submit();
}

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
		
		return false;
	}
	
	return true;
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

function borrarEmisor(idEmisor)
{
	if(!confirm('¿Realmente desea borrar el registro del emisor'))return;
		
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<img src="'+base_url+'img/ajax-loader.gif"/> Se esta borrando el emisor'+esperar);
		},
		type:"POST",
		url:base_url+"configuracion/borrarEmisor",
		data:
		{
			"idEmisor":		idEmisor,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
					notify('Error al borrar el registro del emisor, esta asociado a facturas',500,5000,'error',30,3);
					$('#procesandoInformacion').html('');
				break;
				
				case "1":
					$('#procesandoInformacion').html('');
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al borrar al emisor',500,5000,'error',30,3);
			$('#procesandoInformacion').html('');
		}
	});				  	  
}

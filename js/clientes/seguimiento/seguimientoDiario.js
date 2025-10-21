
idSeguimientoNuevo=0;

function obtenerSeguimientoDiario(idSeguimiento,idCliente)
{
	$('.sinSombra').removeClass('fuenteNaranja');
	$('#filaSeguimiento'+idSeguimiento).addClass('fuenteNaranja');
	
	idSeguimientoNuevo	= idSeguimiento;
	
	if(idSeguimiento==0)
	{
		$('#filaSeguimiento'+idCliente).addClass('fuenteNaranja');
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoDiario').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimientoDiario',
		data:
		{
			"idSeguimiento":	idSeguimiento,
			"idCliente":		idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoDiario').html(data)
			
			$('#ventanaAlertasPasado').dialog('close');
			
			
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoDiario').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function registrarPreinscrito()
{
	var formData = new FormData($('#frmSeguimientoDiario')[0]);
	
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:base_url+'crm/registrarPreinscrito',
		cache: false,
		contentType: false,
		processData: false, 
		data: formData,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Se registro exitósamente',500,5000,'',30,5);
					
					if($('#txtFechaCierreEditar').val()!=$('#txtFechaActualSeguimiento').val())
					{
						$('#filaSeguimiento'+$('#txtIdSeguimiento').val()).remove();
						
						if(idSeguimientoNuevo==0)
						{
							$('#filaSeguimiento'+$('#txtClienteId').val()).remove();
						}
						
						numero	= obtenerNumeros($('#txtNumeroTotalSeguimientos').val());
						numero--;
						$('#txtNumeroTotalSeguimientos').val(numero)
						$('#lblNumeroTotalSeguimientos').html(numero)

					}
					
					$('#lblHoraSeguimiento'+$('#txtIdSeguimiento').val()).html($("#txtHoraCierre").val()+'-'+$("#txtHoraCierre").val());
					
					if($("#selecEstatusProspecto").val()!="0")
					{
						window.setTimeout(function() 
						{
							location.reload(true);
						}, 2000);  
						
					}
					
					$('#obtenerSeguimientoDiario').html('');
					$('.sinSombra').removeClass('fuenteNaranja');

					comprobarSeguimientoAlertasPasado();
				break;
			}//switch
			
			idSeguimientoNuevo=0;
			
		},
		error:function(datos)
		{
			notify('Error en el registro',500,5000,'error',0,0);
			$('#editandoCrm').html('');
		}
	});					  	  
}

function registrarDetalleSeguimientoFecha()
{
	var mensaje	= "";
	metodos		= new Array();
	url			= base_url+"clientes/registrarDetalleSeguimientoFecha";
	i			= 0;
	ban			= false;
	
	Causas		= new String($('#selectCausas').val());
	causas 		= Causas.split('|');
	
	Nocuali		= new String($('#selectNocuali').val());
	nocuali		= Nocuali.split('|');
	
	Detalle		= new String($('#selectDetallesCausaBaja').val());
	detalle		= Detalle.split('|');
	
	if(!document.getElementById('chkPreinscrito').checked)
	{
		if($('#txtProspectoActivo').val()=='2' || $('#txtProspectoActivo').val()=='0')
		{
			if(!revisarMetodos(1))
			{
				//SI TIENE AL MENOS 8 SEGUIMIENTOS
				if(obtenerNumeros($('#txtNumeroDetalles').val())<8)
				{
					notify('Seleccione al menos un método',500,5000,'error',30,5);
					return;
				}
			}
			
			if(revisarMetodos(1))
			{
				if(revisarRadiosMetodos())
				{
					if($('#selectCualificado').val()=="2")
					{
						/*notify('Seleccione una opción para culificado',500,5000,'error',30,5);
						return;*/
						
						if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()) )
						{
							notify('Los comentarios y fecha del próximo contacto son requeridos',500,5000,'error',30,5);
							return;
						}	
					}
					
					if($('#selectCualificado').val()=="1")
					{
						if($('#selectInteresado').val()=="2")
						{
							notify('Seleccione una opción para interesado',500,5000,'error',30,5);
							return;
						}
						
						if($('#selectCualificado').val()=="1")
						{
							if($('#selectInteresado').val()=="1")
							{
								if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()))
								{
									notify('Los comentarios y fecha del próximo contacto son requeridos',500,5000,'error',30,5);
									return;
								}	
							}
						}
					}
				}
				else
				{
					//SI TIENE AL MENOS 8 SEGUIMIENTOS
					//if(obtenerNumeros($('#txtNumeroDetalles').val())<8)
					{
						if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()))
						{
							notify('Los comentarios y fecha del próximo contacto son requeridos',500,5000,'error',30,5);
							return;
						}	
					}
				}
			}
		}
		
		
		if($('#txtProspectoActivo').val()=='1')
		{
			if($('#selectProspectos').val()!='5')
			{
				if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()))
				{
					notify('Los comentarios y fecha del próximo contacto son requeridos',500,5000,'error',30,5);
					return;
				}	
			}
		}
		
		if(!confirm('¿Realmente desea continuar el registro?')) return;
		
	}

	if(document.getElementById('chkPreinscrito').checked)
	{
		if(!confirm('¿Realmente desea inscribir al prospecto?')) return;
	}
	
	if(document.getElementById('chkPreinscrito').checked)
	{
		url			= base_url+"crm/registrarPreinscrito";
		
		if(obtenerNumeros($("#txtVenta").val()) ==0 )
		{
			notify('Por favor escriba el monto de la venta',500,5000,'error',30,5);
			return;
		}
		
		if($("#selectProgramas").val() =="0" )
		{
			notify('Seleccione el programa',500,5000,'error',30,5);
			return;
		}
		
		
		
		leyenda	='¿Esta seguro que?';
		leyenda	+='\n\n Inscripción: $'+redondear($("#txtInscripcion").val());
		leyenda	+='\n Colegiatura: $'+redondear($("#txtColegiatura").val());
		leyenda	+='\n Reinscripción: $'+redondear($("#txtReinscripcion").val());
		leyenda	+='\n Titulación: $'+redondear($("#txtTitulacion").val());
		
		if(!confirm(leyenda)) return;
		
		registrarPreinscrito();
		
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:url,
		data: $('#frmSeguimientoDiario').serialize(),
		/*{
			metodos:	metodos,
			"observaciones":			$("#txtComentarios").val(),
			"fechaSeguimiento":			$('#txtFechaCierreEditar').val(),
			"idSeguimiento":			$('#txtIdSeguimiento').val(),
			//"idEstatus":				$("#selectEstatus").val(),
			"horaInicial":				$("#txtHoraCierre").val(),
			"horaFinal":				$("#txtHoraCierreFin").val(),
			"alerta":					document.getElementById('chkAlertaSeguimiento').checked?'1':'0',
			
			"idPrograma":				$("#selectProgramas").val(),
			"venta":					$("#txtVenta").val(),
			"idCliente":				$("#txtClienteId").val(),
			"idPromotor":				$("#txtIdPromotor").val(),
			
			"idZona":					$("#selecEstatusProspecto").val(),

			"preinscrito":				document.getElementById('chkPreinscrito').checked?'1':'0',
			
			"inscripcion":				$("#txtInscripcion").val(),
			"colegiatura":				$("#txtColegiatura").val(),
			"reinscripcion":			$("#txtReinscripcion").val(),
			"titulacion":				$("#txtTitulacion").val(),
			"cantidadInscripcion":		$("#txtCantidadInscripcion").val(),
			"cantidadColegiatura":		$("#txtCantidadColegiatura").val(),
			"cantidadReinscripcion":	$("#txtCantidadReinscripcion").val(),
			
			"idEmbudo":					$("input[name='rdEmbudo']:checked").val(),
			"idDetalleEmbudo":			$("#selectDetallesEmbudo").val(),
			
			"idCausa":					causas[0],
			"idNocuali":				nocuali[0],
			"idDetalle":				detalle[0],
			
			"texto":					$("#txtTextoDetalle").val(),
			"textoBaja":				$("#txtTextoDetalleBaja").val(),
			
		},*/
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Se registro exitósamente',500,5000,'',30,5);
					
					/*if($('#txtFechaCierreEditar').val()!=$('#txtFechaActualSeguimiento').val())
					{
						$('#filaSeguimiento'+$('#txtIdSeguimiento').val()).remove();
						
						numero	= obtenerNumeros($('#txtNumeroTotalSeguimientos').val());
						numero--;
						$('#txtNumeroTotalSeguimientos').val(numero)
						$('#lblNumeroTotalSeguimientos').html(numero)

					}*/
					
					$('#lblHoraSeguimiento'+$('#txtIdSeguimiento').val()).html($("#txtHoraCierre").val()+'-'+$("#txtHoraCierre").val());
					
					/*if($("#selecEstatusProspecto").val()!="0")
					{
						window.setTimeout(function() 
						{
							location.reload(true);
						}, 2000);  
					}*/
					
					if($("#selectCualificado").val()=="0" || $("#selectInteresado").val()=="0" || $('#txtFechaCierreEditar').val()!=$('#txtFechaActualSeguimiento').val())
					{
						$('#filaSeguimiento'+$('#txtIdSeguimiento').val()).remove();
						
						if(idSeguimientoNuevo==0)
						{
							$('#filaSeguimiento'+$('#txtClienteId').val()).remove();
						}
						
						
						numero	= obtenerNumeros($('#txtNumeroTotalSeguimientos').val());
						numero--;
						
						if(numero<=0) numero=0;
						
						$('#txtNumeroTotalSeguimientos').val(numero)
						$('#lblNumeroTotalSeguimientos').html(numero)
					}
					
					$('#obtenerSeguimientoDiario').html('');
					$('.sinSombra').removeClass('fuenteNaranja');
					
					
					comprobarSeguimientoAlertasPasado();
					
					
					
					//obtenerSeguimientoDiario($('#txtIdSeguimiento').val());
				break;
				
			}//switch
			
			idSeguimientoNuevo=0;
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}


function registrarDetalleSeguimientoFechaPasado()
{
	var mensaje	= "";
	metodos		= new Array();
	url			= base_url+"clientes/registrarDetalleSeguimientoFecha";
	i			= 0;
	ban			= false;
	
	Causas		= new String($('#selectCausas').val());
	causas 		= Causas.split('|');
	
	Nocuali		= new String($('#selectNocuali').val());
	nocuali		= Nocuali.split('|');
	
	Detalle		= new String($('#selectDetallesCausaBaja').val());
	detalle		= Detalle.split('|');
	
	if($('#selecEstatusProspecto').val()=="0" && !document.getElementById('chkPreinscrito').checked)
	{
		for(m=0;m<obtenerNumeros($("#txtNumeroMetodos").val());m++)
		{
			if(document.getElementById('chkMetodo'+m).checked)
			{
				metodos[i]	= $('#chkMetodo'+m).val();
				i++;
				
				ban=true;
			}
		}
		
		if(!ban)
		{
			notify('Seleccione al menos un método',500,5000,'error',30,5);
			return;
		}
		
		
		if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()) || !camposVacios($("#txtHoraCierre").val()) || !camposVacios($("#txtHoraCierreFin").val()) )
		{
			notify('Todos los datos son requeridos',500,5000,'error',30,5);
			return;
		}
	}
	
	if($('#selecEstatusProspecto').val()=="2" && !document.getElementById('chkPreinscrito').checked)
	{
		//if($('#selectCausas').val()=="0" )
		if(causas[0]==0 )
		{
			notify('Seleccione la causa',500,5000,'error',30,5);
			return;
		}
		
		if(!confirm('¿Realmente desea continuar el registro?')) return;
	}
	
	
	if($('#selecEstatusProspecto').val()=="8" && !document.getElementById('chkPreinscrito').checked)
	{
		//if($('#selectNocuali').val()=="0" )
		if(nocuali[0]==0 )
		{
			notify('Seleccione la causa de no cualilificado',500,5000,'error',30,5);
			return;
		}
		
		if(!confirm('¿Realmente desea dar de continuar el registro?')) return;
	}
	
	if($('#selecEstatusProspecto').val()=="1" && !document.getElementById('chkPreinscrito').checked)
	{
		if(!confirm('¿Realmente desea inscribir al prospecto?')) return;
	}
	
	
	if(document.getElementById('chkPreinscrito').checked)
	{
		url			= base_url+"crm/registrarPreinscrito";
		
		if(obtenerNumeros($("#txtVenta").val()) ==0 )
		{
			notify('Por favor escriba el monto de la venta',500,5000,'error',30,5);
			return;
		}
		
		if($("#selectProgramas").val() =="0" )
		{
			notify('Seleccione el programa',500,5000,'error',30,5);
			return;
		}
		
		
		
		leyenda	='¿Esta seguro que?';
		leyenda	+='\n\n Inscripción: $'+redondear($("#txtInscripcion").val());
		leyenda	+='\n Colegiatura: $'+redondear($("#txtColegiatura").val());
		leyenda	+='\n Reinscripción: $'+redondear($("#txtReinscripcion").val());
		leyenda	+='\n Titulación: $'+redondear($("#txtTitulacion").val());
		
		if(!confirm(leyenda)) return;
		
		registrarPreinscrito();
		
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:url,
		data:
		{
			metodos:	metodos,
			"observaciones":	$("#txtComentarios").val(),
			"fechaSeguimiento":	$('#txtFechaCierreEditar').val(),
			"idSeguimiento":	$('#txtIdSeguimiento').val(),
			//"idEstatus":		$("#selectEstatus").val(),
			"horaInicial":		$("#txtHoraCierre").val(),
			"horaFinal":		$("#txtHoraCierreFin").val(),
			"alerta":			document.getElementById('chkAlertaSeguimiento').checked?'1':'0',
			
			"idPrograma":		$("#selectProgramas").val(),
			"venta":			$("#txtVenta").val(),
			"idCliente":		$("#txtClienteId").val(),
			"idPromotor":		$("#txtIdPromotor").val(),
			
			"idZona":			$("#selecEstatusProspecto").val(),
			
			/*"idCausa":			$("#selectCausas").val(),
			"idNocuali":		$("#selectNocuali").val(),*/
			
			"preinscrito":		document.getElementById('chkPreinscrito').checked?'1':'0',
			
			"inscripcion":				$("#txtInscripcion").val(),
			"colegiatura":				$("#txtColegiatura").val(),
			"reinscripcion":			$("#txtReinscripcion").val(),
			"titulacion":				$("#txtTitulacion").val(),
			"cantidadInscripcion":		$("#txtCantidadInscripcion").val(),
			"cantidadColegiatura":		$("#txtCantidadColegiatura").val(),
			"cantidadReinscripcion":	$("#txtCantidadReinscripcion").val(),
			
			"idEmbudo":					$("input[name='rdEmbudo']:checked").val(),
			"idDetalleEmbudo":			$("#selectDetallesEmbudo").val(),
			
			"idCausa":					causas[0],
			"idNocuali":				nocuali[0],
			"idDetalle":				detalle[0],
			
			"texto":					$("#txtTextoDetalle").val(),
			"textoBaja":				$("#txtTextoDetalleBaja").val(),
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('Se registro exitósamente',500,5000,'',30,5);
					
					if($('#txtFechaCierreEditar').val()!=$('#txtFechaActualSeguimiento').val())
					{
						$('#filaSeguimiento'+$('#txtIdSeguimiento').val()).remove();
						
						numero	= obtenerNumeros($('#txtNumeroTotalSeguimientos').val());
						numero--;
						$('#txtNumeroTotalSeguimientos').val(numero)
						$('#lblNumeroTotalSeguimientos').html(numero)
						
						
						
					}
					
					$('#lblHoraSeguimiento'+$('#txtIdSeguimiento').val()).html($("#txtHoraCierre").val()+'-'+$("#txtHoraCierre").val());
					
					if($("#selecEstatusProspecto").val()!="0")
					{
						window.setTimeout(function() 
						{
							location.reload(true);
						}, 2000);  
						
					}
					
					$('#obtenerSeguimientoDiario').html('');
					$('.sinSombra').removeClass('fuenteNaranja');
					
					
					comprobarSeguimientoAlertasPasado();
					
					
					
					//obtenerSeguimientoDiario($('#txtIdSeguimiento').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function editarDatosGenerales()
{
	var mensaje="";

	if(!confirm('¿Realmente desea editar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Se esta editando el registro...');
		},
		type:"POST",
		url:base_url+"clientes/editarDatosGenerales",
		data:
		{
			"nombre":			$("#txtNombreCliente").val(),
			"paterno":			$('#txtPaterno').val(),
			"materno":			$("#txtMaterno").val(),
			"fechaNacimiento":	$("#txtFechaNacimiento").val(),
			"idCliente":		$("#txtClienteId").val(),
			"idCampana":		$("#selectCampanas").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',0,0);
				break;
				
				case "1":
					notify('El registro fue editado correctamente',500,5000,'',0,0);
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error al editar el seguimiento',500,5000,'error',0,0);
		}
	});					  	  
}


function definirTabSeguimiento(tab)
{
	$('.tabsSeguimiento').removeClass('activado');
	$('.tabsTabla').fadeOut(1);
	$('#'+tab).addClass('activado');
	$('#tabla-'+tab).fadeIn(1);
}


//ENVIAR IMPORTE
$(document).ready(function()
{
	$("#ventanaEnviarImporte").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:230,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Enviar': function() 
			{
				
			}
		},
		close: function()
		{
			$("#formularioEnviarImporte").html('');
		}
	});
});

function formularioEnviarImporte(idCliente)
{
	$('#ventanaEnviarImporte').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioEnviarImporte').html('<img src="'+ img_loader +'"/>Preparando el formulario...');
		},
		type:"POST",
		url:base_url+'crm/formularioEnviarImporte',
		data:
		{
			"idCliente":	idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEnviarImporte').html(data)
		},
		error:function(datos)
		{
			$('#formularioEnviarImporte').html('');
			notify('Error al obtener el formulario',500,5000,'error',0,0);
		}
	});		
}

function registrarClienteIexe()
{
	var mensaje="";
	
	if(!camposVacios($("#txtNombreClienteRegistro").val()) || !camposVacios($("#txtPaternoRegistro").val()))
	{
		notify('Todos los datos son requeridos',500,5000,'error',30,5);
		return;
	}

	if(!confirm('¿Realmente desea continuar el registro?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:base_url+"crm/registrarClienteIexe",
		data:
		{
			"nombre":		$("#txtNombreClienteRegistro").val(),
			"apaterno":		$('#txtPaternoRegistro').val(),
			"amaterno":		$('#txtMaternoRegistro').val(),
			"telefono":		$("#txtTelefonoRegistro").val(),
			"email":		$("#txtEmailRegistro").val(),
			
			"fnacimiento":	$("#txtFechaNacimientoRegistro").val(),
			"promotor":		$("#txtPromotorSeguimiento").val(),
			
			"idCliente":	$("#txtClienteId").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCrm').html('');
			
			switch(data)
			{
				case "0":
					notify('¡Sin cambios en el registro!',500,5000,'error',30,5);
				break;
				
				case "1":
					notify('El registro fue exitoso',500,5000,'',30,5);
					
					obtenerSeguimientoDiario($('#txtIdSeguimiento').val());
				break;
				
			}//switch
		},
		error:function(datos)
		{
			$('#editandoCrm').html('')
			notify('Error en el registro',500,5000,'error',0,0);
		}
	});					  	  
}

function obtenerSeguimientosDiarios()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientosDiarios').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimientosDiarios',
		data:
		{
			"idPromotor":	$('#selectPromotorSeguimientos').val(),
			"fecha":		$('#txtFechaSeguimiento').val(),
			"idCampana":	$('#selectCampanasSeguimientoDiario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientosDiarios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientosDiarios').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function obtenerSeguimientosDiariosPromotor()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientosDiarios').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimientosDiarios',
		data:
		{
			"idPromotor":	$('#selectPromotorSeguimientos').val(),
			"fecha":		$('#txtFechaSeguimientoPromotor').val(),
			"idCampana":	$('#selectCampanasSeguimientoDiario').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientosDiarios').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientosDiarios').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}



function calcularTotalesAcademicosProspecto()
{
	totalAcademico=0;
	
	inscripcion		= obtenerNumeros($('#txtInscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadInscripcion').val())	
	importe			= cantidad*inscripcion
	totalAcademico +=importe;

	
	colegiatura		= obtenerNumeros($('#txtColegiatura').val())
	cantidad		= obtenerNumeros($('#txtCantidadColegiatura').val())	
	importe			= cantidad*colegiatura
	totalAcademico +=importe;
	
	reinscripcion	= obtenerNumeros($('#txtReinscripcion').val())
	cantidad		= obtenerNumeros($('#txtCantidadReinscripcion').val())	
	importe			= cantidad*reinscripcion
	totalAcademico 	+=importe;

	$('#txtVenta').val((redondear(totalAcademico)))
	
	registrarTotalesAcademicosProspecto()
	
}

function registrarTotalesAcademicosProspecto()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/registrarTotalesAcademicosProspecto',
		data:
		{
			"idCliente":		$('#txtClienteId').val(),
			"idAcademico":		$('#txtIdAcademico').val(),
			"inscripcion":		$('#txtInscripcion').val(),
			"colegiatura":		$('#txtColegiatura').val(),
			"reinscripcion":	$('#txtReinscripcion').val(),
			"titulacion":		$('#txtTitulacion').val(),
			
			"cantidadInscripcion":		$('#txtCantidadInscripcion').val(),
			"cantidadColegiatura":		$('#txtCantidadColegiatura').val(),
			"cantidadReinscripcion":	$('#txtCantidadReinscripcion').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#obtenerSeguimientosDiarios').html(data)
		},
		error:function(datos)
		{
			//$('#obtenerSeguimientosDiarios').html('');
			//notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function mostrarCausas()
{
	$('#filaDetallesCausas').fadeOut();
	$('#txtTextoDetalle').fadeOut();
	$('#txtTextoDetalle').val('');
	
	$('#txtTextoDetalleBaja').fadeOut();
	$('#txtTextoDetalleBaja').val('');
	
	$('#lblEtiquetaBaja').html('Embudo:');
	
	switch($('#selecEstatusProspecto').val())
	{
		case "0":
			$('#filaCausas').fadeOut();
			$('#filaNocuali').fadeOut();
			
			$('#selectCausas').val('0|0');
			$('#selectNocuali').val('0|0');
			
			
			
		break;
		
		case "2":
			$('#filaCausas').fadeIn();
			$('#filaNocuali').fadeOut();
			
			$('#selectCausas').val('0|0');
			$('#selectNocuali').val('0|0');
		break;
		
		case "8":
			$('#filaCausas').fadeOut();
			$('#filaNocuali').fadeIn();
			
			$('#selectCausas').val('0|0');
			$('#selectNocuali').val('0|0');
		break;
	}
}

function obtenerDetallesCausaBaja()
{
	Detalles	= new String($('#selectCausas').val());
	detalles 	= Detalles.split('|');
	$('#lblEtiquetaBaja').html('Embudo:');

	if(detalles[1]==0) 
	{
		$('#obtenerDetallesCausas').html('<select id="selectDetallesCausaBaja" name="selectDetallesCausaBaja" class="cajas" style="width:200px;"><option value="0">Seleccione</option></select>')
		$('#filaDetallesCausas').fadeOut();
		return;	
	}
	else
	{
		$('#obtenerDetallesCausas').html('<select id="selectDetallesCausaBaja" name="selectDetallesCausaBaja" class="cajas" style="width:200px;"><option value="0">Seleccione</option></select>')
		$('#filaDetallesCausas').fadeIn();
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/obtenerDetallesCausaBaja',
		data:
		{
			"idCausa":		detalles[0],
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesCausas').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}

function obtenerDetallesCausaNocuali()
{
	Detalles	= new String($('#selectNocuali').val());
	detalles 	= Detalles.split('|');
	$('#lblEtiquetaBaja').html('No interesado:');

	if(detalles[1]==0) 
	{
		$('#obtenerDetallesCausas').html('<select id="selectDetallesCausaBaja" name="selectDetallesCausaBaja" class="cajas" style="width:200px;"><option value="0">Seleccione</option></select>')
		$('#filaDetallesCausas').fadeOut();
		return;	
	}
	else
	{
		$('#obtenerDetallesCausas').html('<select id="selectDetallesCausaBaja" name="selectDetallesCausaBaja" class="cajas" style="width:200px;"><option value="0">Seleccione</option></select>')
		$('#filaDetallesCausas').fadeIn();
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/obtenerDetallesCausaNocuali',
		data:
		{
			"idCausa":		detalles[0],
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDetallesCausas').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}

function sugerirTextoCausa()
{
	Detalles	= new String($('#selectDetallesCausaBaja').val());
	detalles 	= Detalles.split('|');

	if(detalles[1]==0) 
	{
		$('#txtTextoDetalle').fadeOut();
		$('#txtTextoDetalle').val('');
	}
	else
	{
		$('#txtTextoDetalle').fadeIn();
		$('#txtTextoDetalle').val('');
	}
}

function sugerirTextoCausaBaja()
{
	Detalles	= new String($('#selectCausas').val());
	detalles 	= Detalles.split('|');

	if(detalles[1]==0) 
	{
		$('#txtTextoDetalleBaja').fadeOut();
		$('#txtTextoDetalleBaja').val('');
	}
	else
	{
		$('#txtTextoDetalleBaja').fadeIn();
		$('#txtTextoDetalleBaja').val('');
	}
}

function invocarWhatsapp()
{
	telefono	= $('#txtTelefonoContactoSeguimiento').val();
	
	if(telefono.length>4)
	{
		window.open('https://api.whatsapp.com/send?phone=52'+telefono);
	}
	else
	{
		notify('El teléfono es incorrecto',500,5000,'error',30,5);
	}
	
}
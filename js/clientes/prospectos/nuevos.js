function obtenerNuevo(idCliente)
{
	$('.sinSombra').removeClass('fuenteNaranja');
	$('#filaNuevo'+idCliente).addClass('fuenteNaranja');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoDiario').html('<img src="'+ img_loader +'"/>Obteniendo seguimiento');
		},
		type:"POST",
		url:base_url+'crm/obtenerNuevo',
		data:
		{
			"idCliente":	idCliente,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoDiario').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoDiario').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}

function registrarSeguimientoNuevos()
{
	var mensaje="";
	
	if(!camposVacios($("#txtComentarios").val()) || !camposVacios($("#txtFechaCierreEditar").val()) || !camposVacios($("#txtHoraCierre").val()) || !camposVacios($("#txtHoraCierreFin").val()) )
	{
		notify('Todos los datos son requeridos',500,5000,'error',30,5);
		return;
	}
	
	if($('#selecEstatusProspecto').val()=="0")
	{
		
	}
	
	//if(!confirm('¿Realmente desea inscribir al prospecto?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#editandoCrm').html('<img src="'+ img_loader +'"/>Procesando registro...');
		},
		type:"POST",
		url:base_url+"crm/registrarSeguimientoNuevos",
		data:
		{
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
			"idContacto":		$("#txtContactoId").val(),
			"idPromotor":		$("#txtIdPromotor").val(),
			
			"idZona":			$("#selecEstatusProspecto").val(),
			"idCausa":			$("#selectCausas").val(),
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
					$('#filaNuevo'+$("#txtClienteId").val()).remove()
					
					notify('Se registro exitósamente',500,5000,'',30,5);
					
					numero	= obtenerNumeros($('#txtNumeroTotalSeguimientos').val());
					numero--;
					$('#txtNumeroTotalSeguimientos').val(numero)
					$('#lblNumeroTotalSeguimientos').html(numero)

					$('#obtenerSeguimientoDiario').html('');
					$('.sinSombra').removeClass('fuenteNaranja');
					
					
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


function obtenerNuevos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerNuevos').html('<img src="'+ img_loader +'"/>Obteniendo registros');
		},
		type:"POST",
		url:base_url+'crm/obtenerNuevos',
		data:
		{
			"idPromotor":	$('#selectPromotorSeguimientos').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerNuevos').html(data)
		},
		error:function(datos)
		{
			$('#obtenerNuevos').html('');
			notify('Error al obtener el registro',500,5000,'error',0,0);
		}
	});		
}



$(document).ready(function()
{
	//$('.ajax-pagPolizas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagPolizas > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerPolizas";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				'inicio': 	$('#txtFechaInicial').val(),
				'fin': 		$('#txtFechaFinal').val(),
				'tipo':		$('#selectPolizasBusqueda').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<label><img src="'+ base_url +'img/loader.gif"/> Obteniendo detalles de pólizas'+leyendas+'</label>');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
			},
			error:function(datos){$(element).html('Error '+ datos).show('slow');}
		});
	});
});
	
function obtenerPolizas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de las pólizas'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerPolizas',
		data:
		{
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
			'tipo':		$('#selectPolizasBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPolizas').html(data)
		},
		error:function(datos)
		{
			$('#obtenerPolizas').html('');
			notify("Error al obtener las pólizas",500,4000,"error"); 
		}
	});	
}

function formularioPolizas()
{
	$('#ventanaFormularioPolizas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioPolizas',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioPolizas').html(data)
			$('#selectPolizas').focus();
		},
		error:function(datos)
		{
			$('#formularioPolizas').html('');
			notify("Error al preparar el formulario para las pólizas",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioPolizas").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:220,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			
			'Registrar': function() 
			{
				registrarPoliza();
			}
		},
		close: function() 
		{
			$('#formularioPolizas').html('');
		}
	});
	
	$("#ventanaEditarPoliza").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:410,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			
			'Editar': function() 
			{
				editarPoliza();
			}
		},
		close: function() 
		{
			$('#obtenerPoliza').html('');
		}
	});
});

function obtenerPoliza(idPoliza)
{
	$('#ventanaEditarPoliza').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoEditar').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerPoliza',
		data:
		{
			idPoliza:idPoliza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPoliza').html(data)
		},
		error:function(datos)
		{
			$('#obtenerPoliza').html('');
			notify("Error al preparar el formulario para la póliza",500,4000,"error"); 
		}
	});	
}

function editarPoliza()
{
	alerta="";
	
	if($('#selectTipoSolicitud').val()=='AF' || $('#selectTipoSolicitud').val()=='FC')
	{
		if(!camposVacios($('#txtNumeroOrden').val()))
		{
			alerta+='El número de orden es requerido<br />';
		}
	}
	
	if($('#selectTipoSolicitud').val()=='DE' || $('#selectTipoSolicitud').val()=='CO')
	{
		if(!camposVacios($('#txtNumeroTramite').val()))
		{
			alerta+='El número de tramite es requerido<br />';
		}
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/editarPoliza',
		data:
		$('#frmPolizas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro de la póliza no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerPolizas();
				$('#ventanaEditarPoliza').dialog('close');
				notify('La póliza se ha editado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al editar el la póliza',500,4000,'error',30,5);
		}
	});	
}

function registrarPoliza()
{
	alerta="";

	/*if(!camposVacios($('#txtRfc').val()))
	{
		alerta+='El rfc es requerido<br />';
	}
	
	if($('#selectTipoSolicitud').val()=='AF' || $('#selectTipoSolicitud').val()=='FC')
	{
		if(!camposVacios($('#txtNumeroOrden').val()))
		{
			alerta+='El número de orden es requerido<br />';
		}
	}
	
	if($('#selectTipoSolicitud').val()=='DE' || $('#selectTipoSolicitud').val()=='CO')
	{
		if(!camposVacios($('#txtNumeroTramite').val()))
		{
			alerta+='El número de tramite es requerido<br />';
		}
	}*/
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarPoliza',
		data:
		$('#frmPolizas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al registrar la póliza, ya existe un registro con la misma fecha',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerPolizas();
				$('#ventanaFormularioPolizas').dialog('close');
				notify('La póliza se ha registrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar la póliza',500,4000,'error',30,5);
		}
	});	
}

function borrarPoliza(idPoliza)
{
	if(!confirm('¿Realmente desea borrar el registro de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la póliza '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarPoliza',
		data:
		{
			idPoliza:idPoliza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la póliza',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaPoliza'+idPoliza).remove();
				notify('La póliza se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify("Error al borrar la póliza",500,4000,"error"); 
		}
	});	
}

//PARA SUBIR LAS PÓLIZAS CON EXCEL
/*$(document).ready(function()
{
	var button = $('#subirExcel'), interval;
	
	new AjaxUpload('#subirExcel', 
	{
        action: base_url+"excel/subirExcelPoliza",
		onSubmit : function(file , ext)
		{
			if (! (ext && /^(xls|)$/.test(ext)))
			{
				notify('Solo se permiten archivos de excel (xls)',500,5000,'error',20,5);
				return false;
			} 
			else 
			{
				$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Procesando el archivo para las pólizas</label>');
				this.disable();
			}
		},
		onComplete: function(file, response)
		{
			$('#procesandoInformacion').html('');
			
			switch(response)
			{
				case "1":
					obtenerPolizas();
					notify('Las pólizas se hah cargado correctamente',500,5000,'',20,5);
				break;
				
				case "0":
				notify('Error al registrar las pólizas, por favor verifique su archivo de excel',500,5000,'error',20,5);
				this.enable();
				return false;
				break;
			}
			
		}	
	});
});*/

function zipearPolizas()
{
	if(!confirm('¿Realmente desea zipear las pólizas?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obteniendoReporte').html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo el reporte'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'reportes/zipearPolizas',
		data:
		{
			'inicio': 			$('#txtFechaInicial').val(),
			'fin': 				$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obteniendoReporte').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify("Error al obtener el reporte",500,4000,"error"); 
				break;
				
				case "1":
					window.location.href	= base_url+'reportes/descargaZipContabilidad/'+data[1]+'/polizas'
				break;
			}
		},
		error:function(datos)
		{
			$('#obteniendoReporte').html('');
			notify("Error al obtener el reporte",500,4000,"error"); 
		}
	});	
}

function excelPolizas(idPoliza)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obteniendoReporte').html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo el reporte'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'reportes/excelPolizas',
		data:
		{
			'idPoliza': 		idPoliza,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obteniendoReporte').html('');
			window.location.href	= base_url+'reportes/descargarExcelPolizas/'+data
		},
		error:function(datos)
		{
			$('#obteniendoReporte').html('');
			notify("Error al obtener el reporte",500,4000,"error"); 
		}
	});	
}


//OBTENER POLIZAS CONCEPTOS
function obtenerPolizaConcepto(idConcepto)
{
	if(idConcepto>0)
	{
		$('#txtIdConceptoActivo').val(idConcepto);
		
	}
	$('.tabChico').removeClass('activado');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerPolizaConcepto',
		data:
		{
			idConcepto:	$('#txtIdConceptoActivo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPolizas').html(data);
			$('#poliza'+$('#txtIdConceptoActivo').val()).addClass('activado');
			par	= obtenerNumero($('#txtNumeroPartidas').val());
			sumarPartidas()
		},
		error:function(datos)
		{
			$('#obtenerPolizas').html('');
			notify("Error al obtener la póliza",500,4000,"error"); 
		}
	});	
}

function verPolizaConcepto(idConcepto)
{
	if(idConcepto>0)
	{
		$('#txtIdConceptoActivo').val(idConcepto);
		
	}
	$('.tabChico').removeClass('activado');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/verPolizaConcepto',
		data:
		{
			idConcepto:	$('#txtIdConceptoActivo').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerPolizas').html(data);
			$('#poliza'+$('#txtIdConceptoActivo').val()).addClass('activado');
			par	= obtenerNumero($('#txtNumeroPartidas').val());
			sumarPartidas()
		},
		error:function(datos)
		{
			$('#obtenerPolizas').html('');
			notify("Error al obtener la póliza",500,4000,"error"); 
		}
	});	
}


par=0; //PARTIDA

function enumerarPartidas()
{
	p=1;
	for(i=0;i<=par;i++)
	{
		if(comprobarNumeros($('#txtPartida'+i).val()))
		{
			$('#numeroPartida'+i).html(p)
			p++;
		}
	}
}

function quitarPartida(p)
{
	$('#filaPartida'+p).remove();
	
	enumerarPartidas()
	sumarPartidas()
}

function cargarPartida()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando partida...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarPartida',
		data:
		{
			par:	par
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPolizas').html('');
			$('#tablaPartidas').append(data);
			par++;
			
			$('#txtNumeroPartidas').val(par)
			enumerarPartidas();
			
			$("#tablaPartidas tr:even").addClass("sombreado");
			$("#tablaPartidas tr:odd").addClass("sinSombra");  
		},
		error:function(datos)
		{
			$('#procesandoPolizas').html('');
			notify("Error al cargar la partida",500,4000,"error"); 
		}
	});	
}

function sumarPartidas()
{
	p		= 1;
	cargo	= 0;
	abono	= 0;
	
	for(i=0;i<=par;i++)
	{
		if(comprobarNumeros($('#txtPartida'+i).val()))
		{
			cargo	+=obtenerNumero($('#txtCargo'+i).val());
			abono	+=obtenerNumero($('#txtAbono'+i).val());
		}
	}
	
	diferencia	= cargo-abono;
	
	$('#lblCargo').html('$'+redondear(cargo))
	$('#lblAbono').html('$'+redondear(abono))
	$('#lblDiferencia').html('$'+redondear(diferencia))
	if(diferencia!=0)
	{
		$('#lblDiferencia').addClass('fuenteRoja');	
	}
	else
	{
		$('#lblDiferencia').removeClass('fuenteRoja');	
	}
}

function guardarConcepto()
{
	if(!confirm('¿Realmente desea guardar los registros?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/>Guardando información</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/guardarConcepto',
		data: $('#frmGuardarConcepto').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify("Error al guardar la información",500,4000,"error",30,5); 
				break;
				
				case "1":
					notify("El registro se ha guardado correctamente",500,4000,"",30,5); 
					obtenerPolizaConcepto();
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify("Error al guardar la información",500,4000,"error",30,5); 
		}
	});	
}

function borrarPolizaConcepto(idConcepto)
{
	if(!confirm('¿Realmente desea borrar el registro de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la póliza</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarPolizaConcepto',
		data:
		{
			idConcepto:idConcepto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPolizas').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al borrar la póliza',500,4000,'error',30,5);
				break;
				
				case "1":
					obtenerPolizas();
					notify('La póliza se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoPolizas').html('');
			notify("Error al borrar la póliza",500,4000,"error"); 
		}
	});	
}


function cancelarPolizaConcepto(idConcepto)
{
	if(!confirm('¿Realmente desea cancelar el registro de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoPolizas').html('<label><img src="'+base_url+'img/loader.gif"/> Cancelando la póliza</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cancelarPolizaConcepto',
		data:
		{
			idConcepto:idConcepto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoPolizas').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al cancelar la póliza',500,4000,'error',30,5);
				break;
				
				case "1":
					obtenerPolizas();
					notify('La póliza se ha cancelado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoPolizas').html('');
			notify("Error al cancelar la póliza",500,4000,"error"); 
		}
	});	
}

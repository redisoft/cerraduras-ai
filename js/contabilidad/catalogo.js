//OBTENER LAS CUENTAS DEL CATÁLOGO
$(document).ready(function()
{
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerCuentasCatalogo();
		}, 700);
	});
	
	$(document).on("click", ".ajax-pagCatalogo > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerCatalogo";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtCriterio').val(),
				tipo:		$('#txtTipoCuentaActiva').val(),
				
				'inicio': 	$('#txtFechaInicial').val(),
				'fin': 		$('#txtFechaFinal').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<label><img src="'+ base_url +'img/loader.gif"/> Obteniendo detalles del catálogo'+leyendas+'</label>');},
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

function sugerirCuentaNivel()
{
	cuentas		= $('#selectCuenta').val();
	cuenta  	= cuentas.split('|');
	
	$('#txtIdCuenta').val(cuenta[0]);
	$('#txtCodigoAgrupador').val(cuenta[1]);
	$('#txtIdSubCuenta').val(cuenta[3]);
	
	if($('#txtIdCuentaCatalogo').val()=="0")
	{
		$('#txtNivel').val(cuenta[2]);
	}

	if(cuenta[1]!='0')
	{
		cuentas		= cuenta[1]
		referencia	= cuentas.replace('.','-');
		$('#txtNumeroCuenta').val(referencia+'-');
	}
	else
	{
		$('#txtNumeroCuenta').val('');
	}
	
	
}

function definirTipoCuentaCatalogo(tipo,numero)
{
	$('.menuCatalogo').removeClass('activado');
	$('#catalogo'+numero).addClass('activado');
	
	$('#txtTipoCuentaActiva').val(tipo)
	$('#txtNumeroCuentaActiva').val(numero)
	obtenerCuentasCatalogo()
}

function obtenerCuentasCatalogo()
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
			$('#obtenerCuentasCatalogo').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuentasCatalogo',
		data:
		{
			criterio:	$('#txtCriterio').val(),
			tipo:		$('#txtTipoCuentaActiva').val(),
			
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentasCatalogo').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuentasCatalogo').html('');
			notify("Error al obtener los detalles de las cuentas",500,4000,"error"); 
		}
	});	
}

function registrarCuenta()
{
	alerta="";
	cuentas		= $('#selectCuenta').val();
	cuenta  	= cuentas.split('-');

	if(cuenta[0]=="0")
	{
		alerta+='Seleccione la cuenta del SAT<br />';
	}
	
	if(!camposVacios($('#txtNumeroCuenta').val()))
	{
		alerta+='La referencia contable es requerida<br />';
	}
	
	if(!camposVacios($('#txtDescripcion').val()))
	{
		alerta+='La descripción es requerida<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la cuenta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarCuenta',
		data:
		$('#frmCuentas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify('Error al registrar la cuenta',500,4000,'error',30,5);
				break;
				
				case "1":
					obtenerCuentasCatalogo();
					$('#ventanaAgregarCuenta').dialog('close');
					notify('La cuenta se ha registrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar la cuenta',500,4000,'error',30,5);
		}
	});	
}

function obtenerCatalogo()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogo').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles del catálogo'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCatalogo',
		data:
		{
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogo').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogo').html('');
			notify("Error al obtener el catálogo",500,4000,"error"); 
		}
	});	
}

function formularioCatalogo()
{
	$('#ventanaFormularioCatalogo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCatalogo').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para el catálogo...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioCatalogo',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCatalogo').html(data)
		},
		error:function(datos)
		{
			$('#formularioCatalogo').html('');
			notify("Error al preparar el formulario para el catálogo",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioCatalogo").dialog(
	{
		autoOpen:false,        
		show: { effect: "scale", duration: 600 },                      
		height:250,
		width:500,
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
				registrarCatalogo();
			}
		},
		close: function() 
		{
			$('#formularioCatalogo').html('');
		}
	});
	
	$("#ventanaEditarCatalogo").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		height:250,
		width:500,
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
				editarCatalogo();
			}
		},
		close: function() 
		{
			$('#obtenerCatalogoEditar').html('');
		}
	});
});

function obtenerCatalogoEditar(idCatalogo)
{
	$('#ventanaEditarCatalogo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoEditar').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para el catálogo...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCatalogoEditar',
		data:
		{
			idCatalogo:idCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCatalogoEditar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCatalogoEditar').html('');
			notify("Error al preparar el formulario para el catálogo",500,4000,"error"); 
		}
	});	
}

function editarCatalogo()
{
	if(!confirm('¿Realmente desea editar el registro del catálogo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando el catálogo...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/editarCatalogo',
		data:
		$('#frmCatalogo').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro del catálogo no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerCatalogo();
				$('#ventanaEditarCatalogo').dialog('close');
				notify('El catálogo se ha editado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al editar el catalogo',500,4000,'error',30,5);
		}
	});	
}

function registrarCatalogo()
{
	alerta="";

	if($('#txtRfc').val()=="")
	{
		alerta+='El rfc es requerido<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando el catálogo...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarCatalogo',
		data:
		$('#frmCatalogo').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al registrar el catalogo, ya existe uno con la fecha seleccionada',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerCatalogo();
				$('#ventanaFormularioCatalogo').dialog('close');
				notify('El catálogo se ha registrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar el catalogo',500,4000,'error',30,5);
		}
	});	
}

function borrarCatalogo(idCatalogo)
{
	if(!confirm('¿Realmente desea borrar el registro del catálogo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando el catálogo '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarCatalogo',
		data:
		{
			idCatalogo:idCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar el catálogo',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaCatalogo'+idCatalogo).remove();
				notify('El catálogo se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify("Error al borrar el catálogo",500,4000,"error"); 
		}
	});	
}

//AGREGAR CUENTAS AL CATÁLOGO
$(document).ready(function()
{
	$("#ventanaCuentasCatalogo").dialog(
	{
		autoOpen:false,    
		show: { effect: "scale", duration: 600 },                          
		height:650,
		width:1104,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			
			'Agregar cuenta': function() 
			{
				formularioAgregarCuenta();
			}
		},
		close: function() 
		{
			$('#cuentasCatalogo').html('');
		}
	});
	
	$("#ventanaAgregarCuenta").dialog(
	{
		autoOpen:false,     
		show: { effect: "scale", duration: 600 },                         
		height:370,
		width:800,
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
				registrarCuenta();
			}
		},
		close: function() 
		{
			$('#formularioAgregarCuenta').html('');
		}
	});
	
	$("#ventanaEditarCuenta").dialog(
	{
		autoOpen:false,            
		show: { effect: "scale", duration: 600 },                  
		height:370,
		width:800,
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
				editarCuenta();
			}
		},
		close: function() 
		{
			$('#obtenerCuenta').html('');
		}
	});
	
	//$('.ajax-pagCuentas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagCuentas > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerCuentasCatalogo";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				criterio:	$('#txtCriterio').val(),
				tipo:		$('#txtTipoCuentaActiva').val(),
				
				'inicio': 	$('#txtFechaInicial').val(),
				'fin': 		$('#txtFechaFinal').val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<label><img src="'+ base_url +'img/loader.gif"/> Obteniendo detalles de cuentas'+leyendas+'</label>');},
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

function definirCodigoAgrupador()
{
	try
	{
		if($('#selectCuenta').val()=="0")
		{
			$('#txtCodigoAgrupador').val(0);
			return;	
		}
		
		cuentas	= $('#selectCuenta').val();
		cuenta  = cuentas.split('-');
		
		$('#txtCodigoAgrupador').val(cuenta[1]);
		$('#txtIdCuenta').val(cuenta[0]);
		
		if($('#selectSubCuenta').val()!="0")
		{
			cuentas	= $('#selectSubCuenta').val();
			cuenta  = cuentas.split('-');
			
			$('#txtCodigoAgrupador').val(cuenta[1]);
			$('#txtIdSubCuenta').val(cuenta[0]);
		}
		else
		{
			$('#txtIdSubCuenta').val(0);
		}
	}
	catch(e)
	{
		$('#txtCodigoAgrupador').val(0);
		$('#txtIdCuenta').val(0);
		$('#txtIdSubCuenta').val(0);
	}
}

function obtenerSubCuentas()
{
	cuentas	= $('#selectCuenta').val();
	cuenta  = cuentas.split('-');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSubCuentas').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo subcuentas'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerSubCuentas',
		data:
		{
			idCuenta: cuenta[0],
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSubCuentas').html(data);
			definirCodigoAgrupador()
		},
		error:function(datos)
		{
			$('#obtenerSubCuentas').html('');
			notify("Error al obtener las subcuentas",500,4000,"error"); 
		}
	});	
}

function cuentasCatalogo(idCatalogo)
{
	$('#ventanaCuentasCatalogo').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cuentasCatalogo').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cuentasCatalogo',
		data:
		{
			idCatalogo:idCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cuentasCatalogo').html(data);
			
			obtenerCuentasCatalogo()
		},
		error:function(datos)
		{
			$('#cuentasCatalogo').html('');
			notify("Error al obtener los detalles de las cuentas",500,4000,"error"); 
		}
	});	
}


function formularioAgregarCuenta(idCuentaCatalogo)
{
	$('#ventanaAgregarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioAgregarCuenta').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para las cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioAgregarCuenta',
		data:
		{
			cuenta:	$('#txtTipoCuentaActiva').val(),
			idCuentaCatalogo:idCuentaCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarCuenta').html(data)
			$('#txtBuscarCodigoAgrupador').focus()
		},
		error:function(datos)
		{
			$('#formularioAgregarCuenta').html('');
			notify("Error al preparar el formulario para las cuentas",500,4000,"error"); 
		}
	});	
}


function obtenerCuenta(idCuentaCatalogo)
{
	$('#ventanaEditarCuenta').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuenta').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuenta...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuenta',
		data:
		{
			idCuentaCatalogo:idCuentaCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuenta').html(data)
		},
		error:function(datos)
		{
			$('#obtenerCuenta').html('');
			notify("Error al obtener los detalles de la cuenta",500,4000,"error"); 
		}
	});	
}

function editarCuenta()
{
	alerta="";

	if(!camposVacios($('#txtNumeroCuenta').val()))
	{
		alerta+='El número de cuenta es requerido<br />';
	}
	
	if(!camposVacios($('#txtDescripcion').val()))
	{
		alerta+='La descripción es requerida<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la cuenta?'))return
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/editarCuenta',
		data:
		$('#frmCuentas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro de la cuenta no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerCuentasCatalogo($('#txtIdCatalogo').val());
				$('#ventanaEditarCuenta').dialog('close');
				notify('La cuenta se ha editado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al editar la cuenta',500,4000,'error',30,5);
		}
	});	
}

function borrarCuenta(idCuentaCatalogo)
{
	//if(!confirm('Si borra la cuenta se borraran los registros de balanza y pólizas asociados ¿Realmente desea continuar?')) return;
	if(!confirm('¿Realmente desea borrar la cuenta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoCuentas').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la cuenta '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarCuenta',
		data:
		{
			idCuentaCatalogo:idCuentaCatalogo
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCuentas').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta',500,4000,'error',30,5);
				break;
				
				case "1":
					//$('#filaCuenta'+idCuentaCatalogo).remove();
					obtenerCuentasCatalogo();
					notify('La cuenta se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoCuentas').html('');
			notify("Error al borrar la cuenta",500,4000,"error"); 
		}
	});	
}

//PARA SUBIR EL CATÁLOGO DE CUENTAS CON EXCEL
/*$(document).ready(function()
{
	var button = $('#subirExcel'), interval;
	
	new AjaxUpload('#subirExcel', 
	{
        action: base_url+"excel/subirExcelCatalogo",
		onSubmit : function(file , ext)
		{
			if (! (ext && /^(xls|)$/.test(ext)))
			{
				notify('Solo se permiten archivos de excel (xls)',500,5000,'error',20,5);
				return false;
			} 
			else 
			{
				$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Procesando el archivo para el catálogo</label>');
				this.disable();
			}
		},
		onComplete: function(file, response)
		{
			$('#procesandoInformacion').html('');
			
			switch(response)
			{
				case "1":
					obtenerCatalogo();
					notify('El catálogo se ha cargado correctamente',500,5000,'',20,5);
				break;
				
				case "0":
				notify('Error al registrar el catálogo, por favor verifique su archivo de excel',500,5000,'error',20,5);
				this.enable();
				return false;
				break;
			}
			
		}	
	});
});*/

function zipearCatalogo()
{
	if(!confirm('¿Realmente desea zipear el catálogo?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obteniendoReporte').html('<label><img src="'+base_url+'img/loader.gif"/>Obteniendo el reporte'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'reportes/zipearCatalogo',
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
					window.location.href	= base_url+'reportes/descargaZipContabilidad/'+data[1]+'/catalogo'
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


//EXPORTAR EL CATÁLOGO A XML
$(document).ready(function()
{
	$("#btnExportarXml").click(function(e)
	{
	   $('#ventanaExportarCatalogoXml').dialog('open');
	});

	$("#ventanaExportarCatalogoXml").dialog(
	{
		autoOpen:false,    
		show: { effect: "scale", duration: 600 },                          
		height:200,
		width:400,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Exportar': function() 
			{
				xmlCatalogo();
			}
		},
		close: function() 
		{
			
		}
	});
});

function xmlCatalogo()
{
	if(!confirm('¿Realmente desea exportar el catálogo a xml?')) return;
	
	window.location.href	= base_url+'contabilidad/xmlCatalogo/0/'+$('#txtFechaExportarCatalogo').val()
	
	//window.open(base_url+'contabilidad/xmlCatalogo/0/'+$('#txtFechaExportarCatalogo').val());
	
	/*$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#exportandoCatalogoXml').html('<label><img src="'+base_url+'img/loader.gif"/>Exportando el catálogo'+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'reportes/xmlCatalogo',
		data:
		{
			'fecha': 			$('#txtFechaExportarCatalogo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#exportandoCatalogoXml').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify("Error al exportar el catálogo",500,4000,"error",30,5); 
				break;
				
				case "1":
					//window.location.href	= base_url+'reportes/descargaZipContabilidad/'+data[1]+'/catalogo'
				break;
			}
		},
		error:function(datos)
		{
			$('#exportandoCatalogoXml').html('');
			notify("Error al exportar el catálogo",500,4000,"error",30,5); 
		}
	});	*/
}
//==============================================================================================//
//=====================================     ENVÍOS    ==========================================//
//==============================================================================================//
$(document).ready(function ()
{
	obtenerReporte()
	
	$('#txtCriterioBusqueda,#txtCriterioFolio').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporte();
		}
	});
	
	$(document).on("click", ".ajax-pagEnvios > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerReporte";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio: 		$('#FechaDia').val(),
				fin: 			$('#FechaDia2').val(),
				criterio: 		$('#txtCriterioBusqueda').val(),
				idRuta: 		$('#selectRutas').val(),
				cobrados: 		$('#selectCobrados').val(),
				"fecha":		$('#selectFechas').val(),
				"idPersonal":	$('#selectChofer').val(),
				"folioTicket":	$('#txtCriterioFolio').val()
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo registros...</label>');
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

function obtenerReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerReporte').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'reportes/obtenerEnvios',
		data:
		{
			inicio: 		$('#FechaDia').val(),
			fin: 			$('#FechaDia2').val(),
			criterio: 		$('#txtCriterioBusqueda').val(),
			idRuta: 		$('#selectRutas').val(),
			cobrados: 		$('#selectCobrados').val(),
			"fecha":		$('#selectFechas').val(),
			"idPersonal":	$('#selectChofer').val(),
			"folioTicket":	$('#txtCriterioFolio').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporte').html(data);
		},
		error:function(datos)
		{
			$("#obtenerReporte").html('');
			notify('Error al obtener los registros',500,5000,'error',2,5);
		}
	});	
}


function excelReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelEnvios',
		data:
		{
			inicio: 		$('#FechaDia').val(),
			fin: 			$('#FechaDia2').val(),
			criterio: 		$('#txtCriterioBusqueda').val(),
			idRuta: 		$('#selectRutas').val(),
			cobrados: 		$('#selectCobrados').val(),
			"fecha":		$('#selectFechas').val(),
			"idPersonal":	$('#selectChofer').val(),
			"folioTicket":	$('#txtCriterioFolio').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/reporteEnvios'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});	
}

function pdfReporte()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteEnvios',
		data:
		{
			inicio: 		$('#FechaDia').val(),
			fin: 			$('#FechaDia2').val(),
			criterio: 		$('#txtCriterioBusqueda').val(),
			idRuta: 		$('#selectRutas').val(),
			cobrados: 		$('#selectCobrados').val(),
			"fecha":		$('#selectFechas').val(),
			"idPersonal":	$('#selectChofer').val(),
			"folioTicket":	$('#txtCriterioFolio').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/reporteEnvios'
			//$("#cargarProductos").html(data);
		},
		error:function(datos)
		{
			$("#generandoReporte").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function ticketReporte()
{
	numeroRegistros = obtenerNumeros($('#txtNumeroRegistros').val());

	if (numeroRegistros == 0)
	{
		notify('Sin registros para el ticket', 500, 2000, 'error', 30, 5);
		return;
	}

	registros = false;

	for (i = 0; i < numeroRegistros; i++)
	{
		if ($("#chkVenta" + i).prop("checked"))
		{
			registros = true;
		}
	}

	if (!registros)
	{
		notify('Seleccione al menos un registro', 500, 2000, 'error', 30, 5);
		return;
	}

	$('#ventanaEnvios').dialog('open')
}

$(document).ready(function ()
{
	$("#ventanaEnvios").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:650,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarTicket();
				/*if ($('#selectPersonal').val() == 0 && $('#selectVehiculo').val() == "0")
				{
					notify('Seleccione el chofer y el vehículo', 500, 2000, 'error', 30, 5);
					return;
				}

				var theForm = document.forms['frmEnvios'];
				
				$('#idPersonalRegistro,#idVehiculoRegistro').remove()
				
				addHidden(theForm, 'idPersonalRegistro', $('#selectPersonal').val());
				addHidden(theForm, 'idVehiculoRegistro', $('#selectVehiculo').val());
				
				theForm.submit();

				setTimeout(function ()
				{
					obtenerReporte()
					$('#ventanaEnvios').dialog('close')
				}, 500);*/
				
			},
			
		},
		close: function() 
		{

		}
	});
	
});

function registrarTicket()
{
	if ($('#selectPersonal').val() == 0 && $('#selectVehiculo').val() == "0")
	{
		notify('Seleccione el chofer y el vehículo', 500, 2000, 'error', 30, 5);
		return;
	}

	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoReporte').html('<img src="'+ img_loader +'"/>Registrando');},
		type:"POST",
		url:base_url+'reportes/ticketEnvios',
		data:$("#frmEnvios").serialize()+"&idPersonalRegistro="+$('#selectPersonal').val()+"&idVehiculoRegistro="+$('#selectVehiculo').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
            data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro ha sido exitoso',500,5000,'',30,5);

					window.open(base_url+'reportes/pdfReporteEntregas/'+data[1])
					obtenerReporte()
					$('#ventanaEnvios').dialog('close')
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#generandoReporte').html('');
		}
	}); 	  
}



function addHidden(theForm, key, value) {
    // Create a hidden input element, and append it to the form:
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = key; // 'the key/name of the attribute/field that is sent to the server
    input.value = value;
    theForm.appendChild(input);
}

/*function ticketReporte()
{
	$.ajax(
	{
		async:false,
		beforeSend:function(objeto)
		{
		},
		type:"POST",
		url:base_url+"reportes/ticketEnvios",
		data:
		{
			inicio: 	$('#FechaDia').val(),
			fin: 		$('#FechaDia2').val(),
			criterio: 	$('#txtCriterioBusqueda').val(),
			idRuta: 	$('#selectRutas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#procesandoRecepciones').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al imprimir el ticket',500,5000,'error',30,5);
				break;
				
				default:
					$('#ticketReporte').html(data);
				
					etiquetas 			= document.getElementById('ticketReporte');
					ventanaImprimir 	= window.open(' ', 'popimpr');

					ventanaImprimir.document.write( etiquetas.innerHTML );
					ventanaImprimir.document.close();
					ventanaImprimir.print( );
					ventanaImprimir.close();
					$('#ticketReporte').html('');
					
					
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al imprimir el ticket ',500,5000,'error',30,10);
		}
	});	
}*/

$(document).ready(function()
{    
	$("#ventanaEntregas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1200,
		modal:true,
		resizable:false,
		buttons: 
		[
		 	{
                text: "Cancelar",
                click: function() 
				{
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "Registrar",
                click: $.noop,
                type: "submit",
				form: "frmEntregas",
				
            },
        ],
		close: function() 
		{
			$("#formularioEntregas").html('');
		}
	});
});
function formularioEntregas()
{
	$("#ventanaEntregas").dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioEntregas').html('<img src="'+ img_loader +'"/> Preparando el formulario');},
		type:"POST",
		url:base_url+'reportes/formularioEntregas',
		data:
		{
			inicio: 		$('#FechaDia').val(),
			fin: 			$('#FechaDia2').val(),
			criterio: 		$('#txtCriterioBusqueda').val(),
			idRuta: 		$('#selectRutas').val(),
			cobrados: 		$('#selectCobrados').val(),
			"fecha":		$('#selectFechas').val(),
			"idPersonal":	$('#selectChofer').val(),
			"folioTicket":	$('#txtCriterioFolio').val(),
			"folioIndividual": "0"
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEntregas').html(data);			
		},
		error:function(datos)
		{
			$("#formularioEntregas").html('');
			notify('Error al preparar el formulario',500,5000,'error',2,5);
		}
	});	
}


function formularioEntregasFolio(folio,idPersonal)
{
	$("#ventanaEntregas").dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioEntregas').html('<img src="'+ img_loader +'"/> Preparando el formulario');},
		type:"POST",
		url:base_url+'reportes/formularioEntregas',
		data:
		{
			inicio: 			'',
			fin: 				'',
			criterio: 			'',
			idRuta: 			0,
			cobrados: 			0,
			"fecha":			0,
			"idPersonal":		idPersonal,
			"folioTicket":		folio,
			"folioIndividual": "1"
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioEntregas').html(data);			
		},
		error:function(datos)
		{
			$("#formularioEntregas").html('');
			notify('Error al preparar el formulario',500,5000,'error',2,5);
		}
	});	
}

function registrarEntregas()
{
	if (obtenerNumeros($('#txtNumeroCotizaciones').val()) == 0)
	{
		notify('Sin registros', 500, 5000, 'error', 30, 5);
		return;
	}

	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoEntregas').html('<img src="'+ img_loader +'"/>Registrando');},
		type:"POST",
		url:base_url+'ventas/registrarEntregas',
		data:$("#frmEntregas").serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoEntregas').html('');
			
            data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					$("#ventanaEntregas").dialog('close');
					obtenerReporte();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#registrandoEntregas').html('');
		}
	}); 	  
}

function revisarCantidadEnvio(i)
{
	disponible	= obtenerNumeros($("#txtEntrega" + i).val());
	cantidad	= obtenerNumeros($("#txtCantidad" + i).val());

	diferencia	= disponible - cantidad;
	diferencia	= diferencia < 0 ? 0 : diferencia

	$("#lblEntregado" + i).html(redondear(diferencia))
}

function borrarFolioEntregas(folio)
{
	if(!confirm('¿Realmente desea borrar el registro?')) return;
	
	$.ajax(
		{
			async: true,
			beforeSend: function (objeto) { $('#generandoReporte').html('<img src="' + img_loader + '"/>Borrando'); },
			type: "POST",
			url: base_url + 'ventas/borrarFolioEntregas',
			data: { folio: folio }
,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoReporte').html('');
			
            data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);

					obtenerReporte()
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#generandoReporte').html('');
		}
	}); 	  
}

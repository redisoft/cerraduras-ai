//==============================================================================================//
//=====================================     ENVÍOS    ==========================================//
//==============================================================================================//
$(document).ready(function ()
{
	$("#txtFechaInicio,#txtFechaFin").datepicker();

	$('#txtCriterioReporte').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerReporteEntregas();
		}
	});

	$("#ventanaReporteEntregas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1100,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				formularioRegistroEnvio()
			},		
			'Aceptar': function() 
			{
				$('#ventanaReporteEntregas').dialog('close')
			},			
		},
		close: function() 
		{

		}
	});
	
	$(document).on("click", ".ajax-pagReporteEntregas > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerReporteEntregas";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				inicio: 		$('#txtFechaInicio').val(),
				fin: 			$('#txtFechaFin').val(),
				criterio: 		$('#txtCriterioReporte').val(),
				idVehiculo: 	$('#selectVehiculoReporte').val(),
				"idPersonal":	$('#selectPersonalReporte').val()
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

	$("#ventanaRegistroEnvios").dialog(
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
				form: "frmRegistro",
				
            },
        ],
		close: function() 
		{
			$("#formularioRegistroEnvio").html('');
		}
	});
});

function obtenerReporteEntregas()
{
	$('#ventanaReporteEntregas').dialog('open')

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerReporteEntregas').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'reportes/obtenerReporteEntregas',
		data:
		{
			inicio: 		$('#txtFechaInicio').val(),
			fin: 			$('#txtFechaFin').val(),
			criterio: 		$('#txtCriterioReporte').val(),
			idVehiculo: 	$('#selectVehiculoReporte').val(),
			"idPersonal":	$('#selectPersonalReporte').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerReporteEntregas').html(data);

			$("#tablaEntregas tr:even").addClass("sombreado");
			$("#tablaEntregas tr:odd").addClass("sinSombra");  
		},
		error:function(datos)
		{
			$("#obtenerReporteEntregas").html('');
			notify('Error al obtener los registros',500,5000,'error',2,5);
		}
	});	
}

function formularioRegistroEnvio()
{
	$('#ventanaRegistroEnvios').dialog('open')

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioRegistroEnvio').html('<img src="'+ img_loader +'"/> Obteniendo registros...');},
		type:"POST",
		url:base_url+'ventas/formularioRegistroEnvio',
		data:
		{
			inicio: 		$('#txtFechaInicio').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioRegistroEnvio').html(data);
		},
		error:function(datos)
		{
			$("#formularioRegistroEnvio").html('');
			notify('Error al obtener los registros',500,5000,'error',2,5);
		}
	});	
}


function obtenerVentaEnvio()
{
	if(ejecutar && ejecutar.readyState != 4)
	{
		notify('Se esta buscando el registro',500,5000,'error',30,5);
		return;
	}

	ejecutar=$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#buscandoTicket').html('<img src="'+ img_loader +'"/>Buscando el registro...');
		},
		type:"POST",
		url:base_url+'ventas/obtenerVentaEnvio',
		data:
		{
			idCotizacion: 			$('#txtBuscarNota').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			var datos	= $.parseJSON(data);
			
			$("#buscandoTicket").html('');
			
			switch(datos.idCotizacion)
			{
				case 0:
					notify('El folio no existe o ya sido entregado',500,5000,'error',30,5);
					break;
				
				default:
					
					cargarVentaEnvio(datos)
				
					break;
			}
		},
		error:function(datos)
		{
			notify('Error al buscar el registro',500,5000,'error',30,5);
			$("#buscandoTicket").html('');
		}
	});
}

function cargarVentaEnvio(venta)
{
	if (!revisarVentaEnvio(venta.idCotizacion, "carga"))
	{
		notify('Ya se ha agregado la nota', 500, 5000, 'error', 30, 5);

		setTimeout(function ()
		{
			$("#txtBuscarNota").val("").focus();
		}, 300);

		return;
	}

	f	= obtenerNumeros($('#txtNumeroEnvios').val());

	data = `<tr id="filaVentaEnvio${f}">
		<td class="text-center"><img src="${base_url}img/borrar.png" width="22" onclick="borrarVentaEnvio(${f})"/></td>
		<td class="text-center">${venta.folio}</td>
		<td>${venta.cliente}</td>
		<td>${venta.ruta}</td>
		<input type="hidden" id="txtIdCotizacion${f}" name="txtIdCotizacion${f}" value="${venta.idCotizacion}" />
	</tr>`;

	$("#tablaVentasEnvios").append(data);

	f++;

	$('#txtNumeroEnvios').val(f);

	$("#tablaVentasEnvios tr:even").addClass("sombreado");
	$("#tablaVentasEnvios tr:odd").addClass("sinSombra");  

	setTimeout(function ()
	{
		$("#txtBuscarNota").val("").focus();
	}, 300);
}

function revisarVentaEnvio(idCotizacion,tipo)
{
	f = obtenerNumeros($('#txtNumeroEnvios').val());

	if (tipo == "carga")
	{
		for (i = 0; i < f; i++)
		{
			if (obtenerNumeros($('#txtIdCotizacion' + i).val()) == idCotizacion) return false;
		}

		return true;
	}

	if (tipo == "registro")
	{
		for (i = 0; i < f; i++)
		{
			if (obtenerNumeros($('#txtIdCotizacion' + i).val())>0) return true;
		}

		return false;
	}
}


function borrarVentaEnvio(i)
{
	$("#filaVentaEnvio" + i).remove();
}

function registrarRegistroEnvios()
{
	if (!revisarVentaEnvio(0, "registro"))
	{
		notify('Agregue al menos una nota', 500, 5000, 'error', 30, 5);
		return;
	}

	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#generandoEnvios').html('<img src="'+ img_loader +'"/>Registrando');},
		type:"POST",
		url:base_url+'ventas/registrarRegistroEnvios',
		data:$("#frmRegistro").serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#generandoEnvios').html('');
			
            data	= eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					$("#ventanaRegistroEnvios").dialog('close');
					obtenerReporte();
					obtenerReporteEntregas()
				break;
			}
		},
		error:function(datos)
		{
			notify('Error en el registro',500,4000,"error"); 
			$('#generandoEnvios').html('');
		}
	}); 	  
}

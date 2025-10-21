
$(document).ready(function()
{
	$(document).on("click", ".ajax-pagBalanza > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerBalanza";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				'inicio': 	$('#txtFechaInicial').val(),
				'fin': 		$('#txtFechaFinal').val(),
				'criterio': $('#txtFechaFinal').val(),
				'filtro': 	$("input[name=rdMostrarCuentas]:checked").val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<label><img src="'+ base_url +'img/loader.gif"/> Obteniendo detalles de balanza'+leyendas+'</label>');},
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
	
function obtenerBalanza()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerBalanza').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles del balanza'+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerBalanza',
		data:
		{
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
			'criterio': $('#txtFechaFinal').val(),
			'filtro': 	$("input[name=rdMostrarCuentas]:checked").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerBalanza').html(data)
		},
		error:function(datos)
		{
			$('#obtenerBalanza').html('');
			notify("Error al obtener la balanza",500,4000,"error"); 
		}
	});	
}

function formularioBalanza()
{
	$('#ventanaFormularioBalanza').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioBalanza').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para la balanza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioBalanza',
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioBalanza').html(data)
		},
		error:function(datos)
		{
			$('#formularioBalanza').html('');
			notify("Error al preparar el formulario para la balanza",500,4000,"error"); 
		}
	});	
}

$(document).ready(function()
{
	$("#ventanaFormularioBalanza").dialog(
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
				registrarBalanza();
			}
		},
		close: function() 
		{
			$('#formularioBalanza').html('');
		}
	});
	
	$("#ventanaEditarBalanza").dialog(
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
				editarBalanza();
			}
		},
		close: function() 
		{
			$('#obtenerBalanzaEditar').html('');
		}
	});
});

function obtenerBalanzaEditar(idBalanza)
{
	$('#ventanaEditarBalanza').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCatalogoEditar').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para la balanza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerBalanzaEditar',
		data:
		{
			idBalanza:idBalanza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerBalanzaEditar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerBalanzaEditar').html('');
			notify("Error al preparar el formulario para la balanza",500,4000,"error"); 
		}
	});	
}

function editarBalanza()
{
	if(!confirm('¿Realmente desea editar el registro de la balanza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando la balanza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/editarBalanza',
		data:
		$('#frmBalanza').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El registro de la balanza no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerBalanza();
				$('#ventanaEditarBalanza').dialog('close');
				notify('La balanza se ha editado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al editar el la balanza',500,4000,'error',30,5);
		}
	});	
}

function registrarBalanza()
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
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la balanza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarBalanza',
		data:
		$('#frmBalanza').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al registrar la balanza, ya existe el registro del mes seleccionado',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerBalanza();
				$('#ventanaFormularioBalanza').dialog('close');
				notify('La balanza se ha registrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar la balanza',500,4000,'error',30,5);
		}
	});	
}

function borrarBalanza(idBalanza)
{
	if(!confirm('¿Realmente desea borrar el registro de la balanza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la balanza '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarBalanza',
		data:
		{
			idBalanza:idBalanza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la balanza',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaBalanza'+idBalanza).remove();
				notify('La balanza se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoInformacion').html('');
			notify("Error al borrar la balanza",500,4000,"error"); 
		}
	});	
}

//AGREGAR CUENTAS A LA BALANZA

fila	= 1; //Para configurar el número de cuentas de la balanza

$(document).ready(function()
{
	$("#ventanaCuentasBalanza").dialog(
	{
		autoOpen:false,  
		show: { effect: "scale", duration: 600 },                            
		height:650,
		width:1120,
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
				cargarCuentaBalanza();
			},
			
			'Guardar': function() 
			{
				guardarBalanzaComprobacion();
			}
		},
		close: function() 
		{
			$('#obtenerCuentasBalanza').html('');
		}
	});
	
	$("#ventanaAgregarCuentaBalanza").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },                              
		height:330,
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
			$('#formularioCatalogo').html('');
		}
	});
	
	$("#ventanaCuentasBalanzaIva").dialog(
	{
		autoOpen:false,    
		show: { effect: "scale", duration: 600 },                          
		height:650,
		width:1120,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cancelar': function() 
			{
				$(this).dialog('close');
			},
			
			'Excel': function() 
			{
				cargarCuentaBalanza();
			},
		},
		close: function() 
		{
			$('#obtenerCuentasBalanzaIva').html('');
		}
	});
});

function obtenerCuentasBalanzaIva(idBalanza)
{
	$('#ventanaCuentasBalanzaIva').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentasBalanzaIva').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuentasBalanzaIva',
		data:
		{
			idBalanza:idBalanza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentasBalanzaIva').html(data);
		},
		error:function(datos)
		{
			$('#obtenerCuentasBalanzaIva').html('');
			notify("Error al obtener los detalles de las cuentas",500,4000,"error"); 
		}
	});	
}

function obtenerCuentasBalanza(idBalanza)
{
	$('#ventanaCuentasBalanza').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerCuentasBalanza').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de cuentas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerCuentasBalanza',
		data:
		{
			idBalanza:idBalanza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerCuentasBalanza').html(data);
			fila	= parseInt($('#txtNumeroCuentas').val());
			fila++;
		},
		error:function(datos)
		{
			$('#obtenerCuentasBalanza').html('');
			notify("Error al obtener los detalles de las cuentas",500,4000,"error"); 
		}
	});	
}

function formularioAgregarCuentaBalanza()
{
	$('#ventanaAgregarCuentaBalanza').dialog('open');
	
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
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioAgregarCuenta').html(data)
		},
		error:function(datos)
		{
			$('#formularioAgregarCuenta').html('');
			notify("Error al preparar el formulario para las cuentas",500,4000,"error"); 
		}
	});	
}

function calcularSaldoFinal(i)
{
	saldoInicial	= obtenerNumero(parseFloat($('#txtSaldoInicial'+i).val()));
	debe			= obtenerNumero(parseFloat($('#txtDebe'+i).val()));
	haber			= obtenerNumero(parseFloat($('#txtHaber'+i).val()));
	
	$('#txtSaldoInicial'+i).val(redondear(saldoInicial));
	$('#txtDebe'+i).val(redondear(debe));
	$('#txtHaber'+i).val(redondear(haber));
	
	saldoFinal	= saldoInicial+debe-haber;
	$('#txtSaldoFinal'+i).val(redondear(saldoFinal));
}

function borrarCuentaNueva(i)
{
	$('#filaCuenta'+i).remove();
}

function cargarCuentaBalanza()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Cargando la cuenta...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/cargarCuentaBalanza',
		data:
		{
			i:		fila,
			fecha:	$('#txtFechaBalanza').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			fila++;
			$('#tablaCuentasBalanza').append(data);
			$('#txtNumeroCuentas').val(fila);
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify("Error al preparar el formulario para las cuentas",500,4000,"error"); 
		}
	});	

	/*cuenta='<tr id="filaCuenta'+fila+'">';
	cuenta+='<td class="numeral">'+fila+'</td>';
	cuenta+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentas" id="txtCuenta'+fila+'" name="txtCuenta'+fila+'" placeholder="Número de cuenta" /></td>';
	cuenta+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtSaldoInicial'+fila+'" name="txtSaldoInicial'+fila+'" value="0.00" onchange="calcularSaldoFinal('+fila+')" maxlength="15" /></td>';
	cuenta+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtDebe'+fila+'" name="txtDebe'+fila+'"  value="0.00" onchange="calcularSaldoFinal('+fila+')" maxlength="15"/></td>';
	cuenta+='<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtHaber'+fila+'" name="txtHaber'+fila+'"  value="0.00" onchange="calcularSaldoFinal('+fila+')" maxlength="15"/></td>'; 
	cuenta+='<td align="center"><input type="text" class="textosBalanzaCantidades" id="txtSaldoFinal'+fila+'" name="txtSaldoFinal'+fila+'"  value="0.00" readonly="readonly" maxlength="15" /></td>'; 
	cuenta+='<td class="vinculos"><img src="'+base_url+'img/borrar.png" title="Borrar cuenta" onclick="borrarCuentaNueva('+fila+')" />';
	cuenta+='<input type="hidden" id="txtIdDetalle'+fila+'" name="txtIdDetalle'+fila+'" value="0" /></td>';
	cuenta+='</tr>';*/
}

function guardarBalanzaComprobacion()
{
	alerta	= "";
	cuenta	= false;
	
	for(i=1;i<=fila;i++)
	{
		if(!isNaN($('#txtIdDetalle'+i).val()))
		{
			cuenta=true;
			
			if($('#selectCuentas'+i).val()=="0")
			{
				alerta+='Seleccione el número de cuenta para todos los registros<br />';
				break;
			}
		}
	}
	
	if(!cuenta)
	{
		alerta+='Agregar por lo menos una cuenta<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea guardar los registros?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando la balanza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/guardarBalanzaComprobacion',
		data:
		$('#frmBalanza').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al guardar los registros de la balanza',500,4000,'error',30,5);
				break;
				
				case "1":
				notify('Los registros se han guardado correctamente',500,4000,'',30,5);
				obtenerCuentasBalanza($('#txtIdBalanza').val());
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar la balanza',500,4000,'error',30,5);
		}
	});	
}

function borrarCuentaBalanza(idDetalle)
{
	if(!confirm('¿Realmente desea borrar el registro de la cuenta?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando la cuenta '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarCuentaBalanza',
		data:
		{
			idDetalle:idDetalle
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar la cuenta',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaCuenta'+idDetalle).remove();
				notify('La cuenta se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify("Error al borrar la cuenta",500,4000,"error"); 
		}
	});	
}

//REPORTES
function reporteBalanza()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Se esta generando el reporte ...');},
		type:"POST",
		url:base_url+'reportes/reporteBalanza',
		data:
		{
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
			'criterio': $('#txtFechaFinal').val(),
			'filtro': 	$("input[name=rdMostrarCuentas]:checked").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			window.location.href=base_url+'reportes/descargarPdfReportes/'+data+'/Balanza'
		},
		error:function(datos)
		{
			$("#procesandoInformacion").html('');
			notify('Error al generar el reporte',500,5000,'error',2,5);
		}
	});		
}

function excelBalanza()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoInformacion').html('<img src="'+ img_loader +'"/> Se esta generando el reporte en excel...');},
		type:"POST",
		url:base_url+'reportes/excelBalanza',
		data:
		{
			'inicio': 	$('#txtFechaInicial').val(),
			'fin': 		$('#txtFechaFinal').val(),
			'criterio': $('#txtFechaFinal').val(),
			'filtro': 	$("input[name=rdMostrarCuentas]:checked").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoInformacion').html('');
			
			window.location.href=base_url+'reportes/descargarExcelReportes/'+data+'/Balanza'
		},
		error:function(datos)
		{
			$("#procesandoInformacion").html('');
			notify('Error al generar el reporte en excel',500,5000,'error',2,5);
		}
	});
}


//PARA SUBIR LA BALANZA DE COMPROBACIÓN CON EXCEL
/*$(document).ready(function()
{
	var button = $('#subirExcel'), interval;
	
	new AjaxUpload('#subirExcel', 
	{
        action: base_url+"excel/subirExcelBalanza",
		onSubmit : function(file , ext)
		{
			if (! (ext && /^(xls|)$/.test(ext)))
			{
				notify('Solo se permiten archivos de excel (xls)',500,5000,'error',20,5);
				return false;
			} 
			else 
			{
				$('#procesandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Procesando el archivo para la balanza</label>');
				this.disable();
			}
		},
		onComplete: function(file, response)
		{
			$('#procesandoInformacion').html('');
			
			switch(response)
			{
				case "1":
					obtenerBalanza();
					notify('La balanza se ha cargado correctamente',500,5000,'',20,5);
				break;
				
				case "0":
				notify('Error al registrar la balanza, por favor verifique su archivo de excel',500,5000,'error',20,5);
				this.enable();
				return false;
				break;
			}
			
		}	
	});
});*/

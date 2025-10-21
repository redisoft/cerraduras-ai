//GENERACIÓN DE RECIBOS DE NÓMINA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

e=0; //Indice para empleados
p=0; //Indice para percepciones
d=0; //Indice para deducciones
totalPercepciones	=0;
totalDeducciones	=0;

$(document).ready(function()
{
	$("#ventanaFormularioNomina").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1020,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Previa': function() 
			{
				previaRecibosNomina();
			},
			'Registrar': function() 
			{
				procesarRecibosNomina();
			},
		},
		close: function() 
		{
			$('#formularioNomina').html('');
		}
	});
	
	$("#ventanaEmpleados").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:980,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#listaEmpleados').html('');
		}
	});
	
	$("#ventanaPercepciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:980,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#listaPercepciones').html('');
		}
	});
	
	$("#ventanaDeducciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:980,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$('#listaDeducciones').html('');
		}
	});
});


function formularioNomina()
{
	$("#ventanaFormularioNomina").dialog('open');
	
	e=0; //Indice para empleados
	p=0; //Indice para percepciones
	d=0; //Indice para deducciones
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioNomina').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo el formulario para nómina'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/formularioNomina',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioNomina").html(data);
			window.setTimeout("obtenerFolio()",1000);
		},
		error:function(datos)
		{
			notify('Error al obtener el formulario para nómina'+conexion,500,5000,'error',30,3);
			$("#formularioNomina").html('');
		}
	});		
}

function listaEmpleados()
{
	$("#ventanaEmpleados").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaEmpleados').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de empleados'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/listaEmpleados',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaEmpleados").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de empleados'+conexion,500,5000,'error',30,3);
			$("#listaEmpleados").html('');
		}
	});		
}

function comprobarEmpleado(n)
{
	for(i=0;i<e;i++)
	{
		if(!isNaN($('#txtIdEmpleado'+i).val()))
		{
			if($('#txtIdEmpleado'+i).val()==$('#idEmpleado'+n).val()) return false;
		}
	}
	
	return true;
}

function quitarEmpleado(i)
{
	$('#filaEmpleado'+i).remove()
}

function agregarEmpleadoRecibo(i)
{
	if(!comprobarEmpleado(i))
	{
		notify('Ya ha agregado al empleado',500,5000,'error',30,3);
		return;
	}
	
	data='';
	data+='<tr id="filaEmpleado'+e+'">';
	data+='<td class="formularios"><img src="'+base_url+'img/borrar.png" onclick="quitarEmpleado('+e+')" title="Quitar empleado" /></td>';
	data+='<td>'+$('#nombreEmpleado'+i).val()+'</td>';
	data+='<td align="center">'+$('#rfcEmpleado'+i).val()+'</td>';
	data+='<td>'+$('#puestoEmpleado'+i).val()+'</td>';
	data+='<td>'+$('#departamentoEmpleado'+i).val()+'</td>';
	data+='<td id="filaStatus'+e+'" align="center">Pendiente</td>';
	data+='<input type="hidden" name="txtIdEmpleado'+e+'" id="txtIdEmpleado'+e+'" value="'+$('#idEmpleado'+i).val()+'" />';
	data+='<input type="hidden" name="txtNombreEmpleado'+e+'" id="txtNombreEmpleado'+e+'" value="'+$('#nombreEmpleado'+i).val()+'" />';
	data+='</tr>';
	
	$('#tablaEmpleadosNomina').append(data);
	
	$("#tablaEmpleadosNomina tr:even").addClass("sombreado");
	$("#tablaEmpleadosNomina tr:odd").addClass("sinSombra"); 
	
	e++;
}

function listaPercepciones()
{
	$("#ventanaPercepciones").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaPercepciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de percepciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/listaPercepciones',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaPercepciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de percepciones'+conexion,500,5000,'error',30,3);
			$("#listaPercepciones").html('');
		}
	});		
}

function comprobarPercepcion(n)
{
	for(i=0;i<p;i++)
	{
		if(!isNaN($('#txtIdPercepcion'+i).val()))
		{
			if($('#txtIdPercepcion'+i).val()==$('#idPercepcion'+n).val()) return false;
		}
	}
	
	return true;
}

function quitarPercepcion(i)
{
	$('#filaPercepcion'+i).remove()
	sumarPercepciones();
	
	$("#tablaPercepcionesNomina tr:even").addClass("sombreado");
	$("#tablaPercepcionesNomina tr:odd").addClass("sinSombra"); 
}

function agregarPercepcionRecibo(i)
{
	if(!comprobarPercepcion(i))
	{
		notify('Ya ha agregado la percepción',500,5000,'error',30,3);
		return;
	}
	
	data='';
	data+='<tr id="filaPercepcion'+p+'">';
	data+='<td class="formularios"><img src="'+base_url+'img/borrar.png" onclick="quitarPercepcion('+p+')" title="Quitar percepción" /></td>';
	data+='<td>'+$('#clavePercepcion'+i).val()+'</td>';
	data+='<td align="center">'+$('#conceptoPercepcion'+i).val()+'</td>';
	data+='<td>'+$('#nombrePercepcion'+i).val()+'</td>';
	data+='<td><input type="text" style="width:100px" onchange="sumarPercepciones()" class="cajas" name="txtImporteGravadoPercepcion'+p+'" id="txtImporteGravadoPercepcion'+p+'" value="'+$('#importeGravado'+i).val()+'" /></td>';
	data+='<td><input type="text" style="width:100px" onchange="sumarPercepciones()" class="cajas" name="txtImporteExentoPercepcion'+p+'" id="txtImporteExentoPercepcion'+p+'" value="'+$('#importeExento'+i).val()+'" /></td>';
	
	data+='<input type="hidden" name="txtIdPercepcion'+p+'" id="txtIdPercepcion'+p+'" value="'+$('#idPercepcion'+i).val()+'" />';
	data+='<input type="hidden" name="txtConceptoPercepcion'+p+'" id="txtConceptoPercepcion'+p+'" value="'+$('#conceptoPercepcion'+i).val()+'" />';
	data+='<input type="hidden" name="txtClavePercepcion'+p+'" id="txtClavePercepcion'+p+'" value="'+$('#clavePercepcion'+i).val()+'" />';
	data+='<input type="hidden" name="txtTipoPercepcion'+p+'" id="txtTipoPercepcion'+p+'" value="'+$('#tipoPercepcion'+i).val()+'" />';
	data+='<input type="hidden" name="txtNombrePercepcion'+p+'" id="txtNombrePercepcion'+p+'" value="'+$('#nombrePercepcion'+i).val()+'" />';
	
	data+='</tr>';
	
	$('#tablaPercepcionesNomina').append(data);
	
	$("#tablaPercepcionesNomina tr:even").addClass("sombreado");
	$("#tablaPercepcionesNomina tr:odd").addClass("sinSombra"); 
	
	p++;
	
	$("#txtNumeroPercepciones").val(p); 
	sumarPercepciones();
}

function sumarPercepciones()
{
	totalPercepciones	=0;
	totalGravado		=0;
	totalExento			=0;
	
	for(i=0;i<p;i++)
	{
		if(!isNaN($('#txtIdPercepcion'+i).val()))
		{
			importeGravado	=parseFloat($('#txtImporteGravadoPercepcion'+i).val());
			importeExento	=parseFloat($('#txtImporteExentoPercepcion'+i).val());
			
			if(!comprobarNumeros(importeGravado))
			{
				importeGravado=0;
				$('#txtImporteGravadoPercepcion'+i).val(0)
			}
			
			if(!comprobarNumeros(importeExento))
			{
				importeExento=0;
				$('#txtImporteExentoPercepcion'+i).val(0)
			}
			
			totalGravado		+=importeGravado;
			totalExento			+=importeExento;
			totalPercepciones	+=importeGravado+importeExento;
		}
	}
	
	$('#txtTotalGravadoPercepciones').val(redondear(totalGravado));
	$('#txtTotalExentoPercepciones').val(redondear(totalExento));
	$('#txtPercepciones').val(redondear(totalPercepciones));
	calcularTotalNomina();
}

function listaDeducciones()
{
	$("#ventanaDeducciones").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#listaDeducciones').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo detalles de deducciones'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/listaDeducciones',
		data:
		{
			//idUsuario:idUsuario
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#listaDeducciones").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de deducciones'+conexion,500,5000,'error',30,3);
			$("#listaDeducciones").html('');
		}
	});		
}

function comprobarDeduccion(n)
{
	for(i=0;i<d;i++)
	{
		if(!isNaN($('#txtIdDeduccion'+i).val()))
		{
			if($('#txtIdDeduccion'+i).val()==$('#idDeduccion'+n).val()) return false;
		}
	}
	
	return true;
}

function quitarDeduccion(i)
{
	$('#filaDeduccion'+i).remove()
	sumarDeducciones()
	
	$("#tablaDeduccionesNomina tr:even").addClass("sombreado");
	$("#tablaDeduccionesNomina tr:odd").addClass("sinSombra"); 
}

function agregarDeduccionRecibo(i)
{
	if(!comprobarDeduccion(i))
	{
		notify('Ya ha agregado la deducción',500,5000,'error',30,3);
		return;
	}
	
	data='';
	data+='<tr id="filaDeduccion'+d+'">';
	data+='<td class="formularios"><img src="'+base_url+'img/borrar.png" onclick="quitarDeduccion('+d+')" title="Quitar deducción" /></td>';
	data+='<td>'+$('#claveDeduccion'+i).val()+'</td>';
	data+='<td align="center">'+$('#conceptoDeduccion'+i).val()+'</td>';
	data+='<td>'+$('#nombreDeduccion'+i).val()+'</td>';
	data+='<td><input type="text" style="width:100px" onchange="sumarDeducciones()" class="cajas" name="txtImporteGravadoDeduccion'+d+'" id="txtImporteGravadoDeduccion'+d+'" value="'+$('#importeGravado'+i).val()+'" /></td>';
	data+='<td><input type="text" style="width:100px" onchange="sumarDeducciones()" class="cajas" name="txtImporteExentoDeduccion'+d+'" id="txtImporteExentoDeduccion'+d+'" value="'+$('#importeExento'+i).val()+'" /></td>';
	
	data+='<input type="hidden" name="txtIdDeduccion'+d+'" id="txtIdDeduccion'+d+'" value="'+$('#idDeduccion'+i).val()+'" />';
	data+='<input type="hidden" name="txtConceptoDeduccion'+d+'" id="txtConceptoDeduccion'+d+'" value="'+$('#conceptoDeduccion'+i).val()+'" />';
	data+='<input type="hidden" name="txtClaveDeduccion'+d+'" id="txtClaveDeduccion'+d+'" value="'+$('#claveDeduccion'+i).val()+'" />';
	data+='<input type="hidden" name="txtTipoDeduccion'+d+'" id="txtTipoDeduccion'+d+'" value="'+$('#tipoDeduccion'+i).val()+'" />';
	data+='<input type="hidden" name="txtNombreDeduccion'+d+'" id="txtNombreDeduccion'+d+'" value="'+$('#nombreDeduccion'+i).val()+'" />';
	
	data+='</tr>';
	
	$('#tablaDeduccionesNomina').append(data);
	
	$("#tablaDeduccionesNomina tr:even").addClass("sombreado");
	$("#tablaDeduccionesNomina tr:odd").addClass("sinSombra"); 
	
	d++;
	
	$("#txtNumeroDeducciones").val(d); 
	sumarDeducciones();
}

function sumarDeducciones()
{
	totalDeducciones	=0;
	totalGravado		=0;
	totalExento			=0;
	totalIsr			=0;
	
	for(i=0;i<d;i++)
	{
		if(!isNaN($('#txtIdDeduccion'+i).val()))
		{
			importeGravado	=parseFloat($('#txtImporteGravadoDeduccion'+i).val());
			importeExento	=parseFloat($('#txtImporteExentoDeduccion'+i).val());
			
			if(!comprobarNumeros(importeGravado))
			{
				importeGravado=0;
				$('#txtImporteGravadoDeduccion'+i).val(0)
			}
			
			if(!comprobarNumeros(importeExento))
			{
				importeExento=0;
				$('#txtImporteExentoDeduccion'+i).val(0)
			}
			
			if($('#txtTipoDeduccion'+i).val()=="002")
			{
				totalIsr		+=importeGravado;
			}
			
			totalGravado		+=importeGravado;
			totalExento			+=importeExento;
			totalDeducciones	+=importeGravado+importeExento;
		}
	}
	
	$('#txtTotalGravadoDeducciones').val(redondear(totalGravado));
	$('#txtTotalExentoDeducciones').val(redondear(totalExento));
	$('#txtDeducciones').val(redondear(totalDeducciones));
	$('#txtTotalIsr').val(redondear(totalIsr));
	
	calcularTotalNomina()
}

function calcularTotalNomina()
{
	deducciones		=parseFloat($('#txtDeducciones').val());
	percepciones	=parseFloat($('#txtPercepciones').val());
	total			=percepciones-deducciones;
	total			=total<0?0:total;
	
	$('#txtTotales').val(redondear(total))
}

function obtenerDiasTrabajados()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoRecibo').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo los días trabajados'+esperar);
		},
		type:"POST",
		url:base_url+'nomina/obtenerDiasTrabajados',
		data:
		{
			inicio:	$('#txtFechaInicialPago').val(),
			fin:	$('#txtFechaFinalPago').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoRecibo").html('');
			$("#txtDiasTrabajados").val(data);
		},
		error:function(datos)
		{
			notify('Error al obtener la lista de deducciones'+conexion,500,5000,'error',30,3);
			$("#registrandoRecibo").html('');
		}
	});		
}

function previaRecibosNomina()
{
	mensaje		=	"";
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if($('#txtDiasTrabajados').val()=="0")
	{
		mensaje+='Los dias trabajados son incorrectos <br />';
	}
	
	if($('#txtConcepto').val()=="")
	{
		mensaje+='El concepto es incorrecto <br />';
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+='La forma de pago es incorrecta <br />';
	}
	
	if($('#txtMetodoPago').val()=="")
	{
		mensaje+='El método de pago es incorrecto <br />';
	}
	
	if(parseFloat($('#txtTotales').val())<=0)
	{
		mensaje+='Configure correctamente las percepciones y deducciones <br />';
	}
	
	b=0; //Checar si al menos hay un empleado
	idEmpleado=0;
	for(i=0;i<e;i++)
	{
		if(!isNaN($('#txtIdEmpleado'+i).val()))
		{
			idEmpleado	=$('#txtIdEmpleado'+i).val();
			b=1;
		}
	}
	
	if(b==0)
	{
		mensaje+='Seleccione al menos un empleado <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea ver la previa del recibo?')) return;
	

	previaRecibo(idEmpleado); //Registrar todos los recibos para los empleados
	
}

function previaRecibo(idEmpleado)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoRecibo').html('<img src="'+base_url+'img/ajax-loader.gif"/>El sistema esta creando la previa del recibo');
		},
		type:"POST",
		url:base_url+'pdf/previaReciboNomina/'+idEmpleado,
		data:$('#frmNomina').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoRecibo').html('');
			notify('La previa se ha creado correctamente',500,5000,'',30,3);
			
			window.location.href=base_url+'reportes/descargarPdfPrevia/previaRecibo/previaRecibo'
		},
		error:function(datos)
		{
			notify('Error al crear la previa',500,5000,'error',30,3);
			$("#registrandoRecibo").html('');
		}
	});		
}

function procesarRecibosNomina()
{
	mensaje		=	"";
	
	if($('#selectEmisores').val()=="0")
	{
		mensaje+="Seleccione el emisor <br />";
	}
	
	if($('#txtDiasTrabajados').val()=="0")
	{
		mensaje+='Los dias trabajados son incorrectos <br />';
	}
	
	if($('#txtConcepto').val()=="")
	{
		mensaje+='El concepto es incorrecto <br />';
	}
	
	if($('#txtFormaPago').val()=="")
	{
		mensaje+='La forma de pago es incorrecta <br />';
	}
	
	if($('#txtMetodoPago').val()=="")
	{
		mensaje+='El método de pago es incorrecto <br />';
	}
	
	if(parseFloat($('#txtTotales').val())<=0)
	{
		mensaje+='Configure correctamente las percepciones y deducciones <br />';
	}
	
	b=0; //Checar si al menos hay un empleado
	for(i=0;i<e;i++)
	{
		if(!isNaN($('#txtIdEmpleado'+i).val())) b=1;
	}
	
	if(b==0)
	{
		mensaje+='Seleccione al menos un empleado <br />';
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar los recibos de nómina para los empleados seleccionados?')) return;
	
	//$('#registrandoRecibo').html('<img src="'+base_url+'img/loader.gif"/>El sistema esta registrando los recibos de nómina'+esperar);
	
	/*for(i=0;i<e;i++)
	{*/
		registrarRecibo(0); //Registrar todos los recibos para los empleados
	//}
}

function registrarRecibo(i)
{
	if(!isNaN($('#txtIdEmpleado'+i).val()))
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#registrandoRecibo').html('<img src="'+base_url+'img/ajax-loader.gif"/>El sistema esta registrando el recibo de nómina del empleado '+ $('#txtNombreEmpleado'+i).val() + esperar);
			},
			type:"POST",
			url:base_url+'nomina/registrarRecibo/'+$('#txtIdEmpleado'+i).val(),
			data:$('#frmNomina').serialize(),
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#registrandoRecibo').html('');
				
				data	=eval(data);
				
				switch(data[0])
				{
					case "0":
						notify(data[1],500,5000,'error',30,3);
						$('#filaStatus'+i).html(data[1]);
						
						return;
					break;
		
					case "1":
						notify(data[1],500,5000,'',30,3);
						$('#filaStatus'+i).html('Timbrado');
						$('#txtIdEmpleado'+i).val('timbrado');
					break;
				}
				
				i++;
				
				if(i==e)
				{
					$('#registrandoRecibo').html('');
					notify('Los recibos de nómina se han registrado correctamente',500,5000,'',30,3);
					obtenerRecibos();
					$("#ventanaFormularioNomina").dialog('close');
					return;
				}
				
				if(i<e)
				{
					window.setTimeout("registrarRecibo("+i+")",3000);
				}
			},
			error:function(datos)
			{
				notify('Error al registrar el recibo de nómina',500,5000,'error',30,3);
				$("#registrandoRecibo").html('');
			}
		});		
	}
	else
	{
		i++;
		
		if(i==e)
		{
			$('#registrandoRecibo').html('');
			notify('Los recibos de nómina se han registrado correctamente',500,5000,'',30,3);
			obtenerRecibos();
			$("#ventanaFormularioNomina").dialog('close');
			return;
		}
		
		registrarRecibo(i)
	}
}

//OBTENER LOS RECIBOS DE NÓMINA
$(document).ready(function ()
{
    //$('.ajax-pagRecibos > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagRecibos > li a", function(eve)
	{
		eve.preventDefault();
		var element 		= "#obtenerRecibos";
		var link		 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				fecha:		$('#txtMes').val(),
				criterio:	$('#txtBuscarRecibo').val(),
			},
			dataType:"html",
			beforeSend:function()
			{
				$(element).html('<label><img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo los recibos, por favor tenga paciencia...</label>');
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

function obtenerRecibos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerRecibos').html('<img src="'+ img_loader +'"/>Obteniendo los recibos, por favor tenga paciencia...');
		},
		type:"POST",
		url:base_url+'nomina/obtenerRecibos',
		data:
		{
			fecha:		$('#txtMes').val(),
			criterio:	$('#txtBuscarRecibo').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerRecibos').html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los recibos',500,5000,'error',2,5);
			$("#obtenerRecibos").html('');
		}
	});
}

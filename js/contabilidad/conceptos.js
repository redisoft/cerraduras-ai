//VENTANA CONCEPTOS POLIZA

p	= 0; //El indice para la póliza
g	= 0; //El indice para los grupos
c	= 0; //El indice para los cheques

$(document).ready(function()
{
	$("#ventanaConceptosPoliza").dialog(
	{
		autoOpen:false, 
		show: { effect: "scale", duration: 600 },                             
		height:650,
		width:1200,
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
				formularioConceptos();
			},
		},
		close: function() 
		{
			$('#conceptosPoliza').html('');
		}
	});
	
	$("#ventanaFormularioConceptos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		width:1199,                              
		height:645,
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
				registrarConcepto();
			},
		},
		close: function() 
		{
			$('#formularioConceptos').html('');
		}
	});
	
	$("#ventanaEditarConcepto").dialog(
	{
		autoOpen:false,   
		show: { effect: "scale", duration: 600 },                           
		width:1199,                              
		height:645,
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
				editarConcepto();
			},
		},
		close: function() 
		{
			$('#obtenerConcepto').html('');
		}
	});
	
	//$('.ajax-pagConceptosPolizas > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagConceptosPolizas > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#obtenerConceptosPoliza";
		var link 	= $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				idPoliza:	$('#txtIdPoliza').val(),
				tipo:		$('#selectTipoPoliza').val()
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

function conceptosPoliza(idPoliza)
{
	$('#ventanaConceptosPoliza').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#conceptosPoliza').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de pólizas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/conceptosPoliza',
		data:
		{
			idPoliza:idPoliza
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#conceptosPoliza').html(data);
			obtenerConceptosPoliza();
		},
		error:function(datos)
		{
			$('#conceptosPoliza').html('');
			notify("Error al obtener los detalles de las pólizas",500,4000,"error"); 
		}
	});	
}

function obtenerConceptosPoliza()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConceptosPoliza').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo detalles de pólizas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerConceptosPoliza',
		data:
		{
			idPoliza:	$('#txtIdPoliza').val(),
			tipo:		$('#selectTipoPoliza').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConceptosPoliza').html(data)
		},
		error:function(datos)
		{
			$('#obtenerConceptosPoliza').html('');
			notify("Error al obtener los detalles de las pólizas",500,4000,"error"); 
		}
	});	
}

function formularioConceptos()
{
	$('#ventanaFormularioConceptos').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioConceptos').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para conceptos de pólizas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/formularioConceptos',
		data:
		{
			fecha:$('#txtFechaPolizaTransaccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioConceptos').html(data);
			p	= 0; //El indice para la póliza
			g	= 0; //El indice para los grupos
			c	= 0; //El indice para los cheques 
		},
		error:function(datos)
		{
			$('#formularioConceptos').html('');
			notify("Error al obtener el formulario",500,4000,"error"); 
		}
	});	
}

function opcionesTipoPoliza(sugerir)
{
	switch($('#selectTipo').val())
	{
		case '1':
		$('#tipoIngreso').fadeIn();
		$('#tipoEgreso').fadeOut();
		break;
		
		case '2':
		$('#tipoIngreso').fadeOut();
		$('#tipoEgreso').fadeIn();
		break;
		
		default:
		$('#tipoIngreso').fadeOut();
		$('#tipoEgreso').fadeOut();
		break;
	}
	
	if(sugerir==1)
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Obteniendo tipo de póliza...</label>');
			},
			type:"POST",
			url:base_url+'contabilidad/obtenerTipoPoliza',
			data:
			{
				tipoPoliza:$('#selectTipo').val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				data	= eval(data);
				$('#registrandoInformacion').html('');
				$('#lblPoliza').html(data[0]);
				$('#txtNumero').val(data[1]);
				
			},
			error:function(datos)
			{
				$('#registrandoInformacion').html('');
				notify('Error al obtener el tipo de póliza',500,4000,'error',30,5);
			}
		});	
	}
}

function registrarConcepto()
{
	alerta="";

	if(!camposVacios($('#txtNumero').val()))

	{
		alerta+='El número es incorrecto<br />';
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		alerta+='El concepto es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea registrar el concepto de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta registrando el concepto de la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/registrarConcepto',
		data:
		$('#frmConceptos').serialize()
		+'&idPoliza='+$('#txtIdPoliza').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al registrar el concepto de la póliza',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerConceptosPoliza();
				$('#ventanaFormularioConceptos').dialog('close');
				notify('El concepto de la póliza se ha registrado correctamente',500,4000,'',30,5);
				obtenerPolizas();
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al registrar el concepto de la póliza',500,4000,'error',30,5);
		}
	});	
}

function obtenerConcepto(idConcepto)
{
	$('#ventanaEditarConcepto').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerConcepto').html('<label><img src="'+base_url+'img/loader.gif"/> Preparando el formulario para conceptos de pólizas...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/obtenerConcepto',
		data:
		{
			idConcepto:	idConcepto,
			fecha:		$('#txtFechaPolizaTransaccion').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerConcepto').html(data);
			p	= parseInt($('#txtNumeroTransacciones').val()); //Numero de transacciones
			g	= parseInt($('#txtNumeroGrupos').val()); //Número de grupos

		},
		error:function(datos)
		{
			$('#obtenerConcepto').html('');
			notify("Error al obtener el concepto",500,4000,"error"); 
		}
	});	
}

function editarConcepto()
{
	alerta="";

	if(!camposVacios($('#txtNumero').val()))
	{
		alerta+='El número es incorrecto<br />';
	}
	
	if(!camposVacios($('#txtConcepto').val()))
	{
		alerta+='El concepto es incorrecto<br />';
	}
	
	if(alerta.length>0)
	{
		notify(alerta,500,5000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea editar el concepto?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoInformacion').html('<label><img src="'+base_url+'img/loader.gif"/> Se esta editando el concepto de la póliza...</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/editarConcepto',
		data:
		$('#frmConceptos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoInformacion').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('El concepto de la póliza no tuvo cambios',500,4000,'error',30,5);
				break;
				
				case "1":
				obtenerConceptosPoliza();
				$('#ventanaEditarConcepto').dialog('close');
				notify('El concepto de la póliza se ha editado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoInformacion').html('');
			notify('Error al editar el concepto de la póliza',500,4000,'error',30,5);
		}
	});	
}

function borrarConcepto(idConcepto)
{
	if(!confirm('¿Realmente desea borrar el concepto de la póliza?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#procesandoConceptos').html('<label><img src="'+base_url+'img/loader.gif"/> Borrando el concepto de la póliza '+leyendas+'</label>');
		},
		type:"POST",
		url:base_url+'contabilidad/borrarConcepto',
		data:
		{
			idConcepto:idConcepto
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoConceptos').html('');
			
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
				notify('Error al borrar el concepto de la póliza',500,4000,'error',30,5);
				break;
				
				case "1":
				$('#filaConcepto'+idConcepto).remove();
				notify('El concepto de la póliza se ha borrado correctamente',500,4000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			$('#procesandoConceptos').html('');
			notify("Error al borrar el concepto de la póliza",500,4000,"error"); 
		}
	});	
}

function borrarTransaccionCargada(i)
{
	//if(!confirm('¿Realmente desea quitar el registro?'))return;
	
	$('#filaTransaccion'+i).remove();
	$('#filaIva'+i).remove();
	$('#filaCheque'+i).remove();
	$('#filaTransferencia'+i).remove();
	$('#filaVenta'+i).remove();
	$('#filaSuma'+i).remove();
	
	$('#filaRetencionIva'+i).remove();
	$('#filaDescuento'+i).remove();
	$('#filaMetodoPago'+i).remove();
}

function borrarTransaccionGrupo(i)
{
	//if(!confirm('¿Realmente desea quitar el registro?'))return;
	$('.grupoTransaccion'+i).remove();
	
	/*$('#filaTransaccion'+i).remove();
	$('#filaIva'+i).remove();
	$('#filaCheque'+i).remove();
	$('#filaTransferencia'+i).remove();
	$('#filaVenta'+i).remove();
	$('#filaSuma'+i).remove();*/
}


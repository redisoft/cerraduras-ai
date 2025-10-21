//==============================================================================================//
//========================================ORDENES DE PRODU.=====================================//
//==============================================================================================//

function busquedaOrdenesFecha()
{
	location.href=base_url+"ordenes/index/0/"+$('#FechaDia').val();
}

$(document).ready(function()
{
	$("#txtBuscarOrden").autocomplete(
	{
		source:base_url+'configuracion/obtenerOrdenesProduccion',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'ordenes/index/'+ui.item.idOrden;
		}
	});

	$("#ventanaOrdenProduccion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:970,
		modal:true,
		resizable:false,
		buttons: 
		{
			
			Cancelar: function() 
			{
				$(this).dialog('close');
			},
			'Imprimir': function() 
			{
				imprimirOrden()				  	  
			},
			'Aceptar': function() 
			{
				registrarOrdenProduccion()				  	  
				//comprobarMaterialesOrden()
			},
		},
		close: function() 
		{
			$('#cargarFormularioOrdenProduccion').html('');	
		}
	});
});

function obtenerOrdenes()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerOrdenes').html('<img src="'+ img_loader +'"/> Obteniendo los detalles de la ordenes de producción...');
		},
		type:"POST",
		url:base_url+"ordenes/obtenerOrdenes",
		data:
		{
			//"idOrden":idOrden,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerOrdenes").html(data);
		},
		error:function(datos)
		{
			$("#obtenerOrdenes").html('');	
		}
	});	
}

function comprobarCantidadesMateriales()
{
	for(i=1;i<=parseInt($('#txtNumeroMateriales').val());i++)
	{
		cantidad	= parseFloat($('#txtCantidadRequerida'+i).val());
		ordenes		= parseInt($('#txtNumeroOrdenes'+i).val());
		sumaOrdenes = 0;
		
		for(c=1;c<=ordenes;c++)
		{
			if(document.getElementById('chkCompra'+i+'_'+c).checked)
			{
				sumaOrdenes+=parseFloat($('#txtCantidadOrden'+i+'_'+c).val());
			}
		}
		
		if(cantidad>sumaOrdenes) return false;
	}
	
	return true;
}

function imprimirOrden()
{
	mensaje	= "";
	procesos	= new Array();
	p			= 0;

	indice		= parseInt($("#txtIndiceProcesos").val());
	
	for(i=1;i<indice;i++)
	{
		if(document.getElementById('chkProceso'+i).checked==true)
		{
			procesos[p]=$('#chkProceso'+i).val();
			p++;
		}
	}
	
	if(p==0)
	{
		//mensaje+="Debe seleccionar al menos un proceso <br />";
	}
	
	if($("#txtIdProducto").val()=="0")
	{
		mensaje+="Por favor seleccione un producto <br />";
	}
	
	if($("#txtCantidadProduccion").val()=="" || parseInt($("#txtCantidadProduccion").val())<1 || isNaN($("#txtCantidadProduccion").val()))
	{
		mensaje+="La cantidad a producir es incorrecta <br />";
	}
	
	if(!comprobarCantidadesMateriales())
	{
		//mensaje+="Las cantidades de materia prima son incorrectas ";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea imprimir la orden?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoOrdenProduccion').html('<img src="'+ img_loader +'"/> Imprimiendo la orden...');},
		type:"POST",
		url:base_url+'ordenes/imprimirOrden',
		data:$('#frmOrdenes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoOrdenProduccion').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);							
					
				break;
				case "1":
					notify('La orden se ha imprimir correctamente',500,5000,'',30,5);	
					window.location.href=base_url+'reportes/descargarPdfReportes/Ordenes/Ordenes'
				break; 
			
			}//switch
		},
		error:function(datos)
		{
			notify('Error al imprimir la orden de producción',500,5000,'error',0,0);							
			$('#agregandoOrdenProduccion').html('');
		}
	});
}


function registrarOrdenProduccion()
{
	mensaje	= "";
	procesos	= new Array();
	p			= 0;

	indice		= parseInt($("#txtIndiceProcesos").val());
	
	for(i=1;i<indice;i++)
	{
		if(document.getElementById('chkProceso'+i).checked==true)
		{
			procesos[p]=$('#chkProceso'+i).val();
			p++;
		}
	}
	
	if(p==0)
	{
		//mensaje+="Debe seleccionar al menos un proceso <br />";
	}
	
	if($("#txtIdProducto").val()=="0")
	{
		mensaje+="Por favor seleccione un producto <br />";
	}
	
	if($("#txtCantidadProduccion").val()=="" || parseInt($("#txtCantidadProduccion").val())<1 || isNaN($("#txtCantidadProduccion").val()))
	{
		mensaje+="La cantidad a producir es incorrecta <br />";
	}
	
	if(!comprobarCantidadesMateriales())
	{
		//mensaje+="Las cantidades de materia prima son incorrectas ";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea registrar la orden?')) return;
	
	/*{
		"idProducto":	$("#txtIdProducto").val(),
		"cantidad":		$("#txtCantidadProduccion").val(),
		"idCotizacion":	0,
		"procesos"		:procesos
	}*/
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoOrdenProduccion').html('<img src="'+ img_loader +'"/> Registrando la orden de producción, por favor espere...');},
		type:"POST",
		url:base_url+'ordenes/agregarOrden',
		data:$('#frmOrdenes').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#agregandoOrdenProduccion').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);							
					
				break;
				case "1":
					//location.reload();
					notify('La orden se ha registrado correctamente',500,5000,'',30,5);	
					obtenerOrdenes();
					$('#ventanaOrdenProduccion').dialog('close');
				break; 
				
				case "2":
					notify('No existe suficiente material para registrar la orden de producción',500,5000,'error',0,5);							
				break;
				
				case "3":
					notify('El producto que desea mandar a producir no tiene definidos sus materiales',500,5000,'error',30,5);							
				break;
			}//switch
		},
		error:function(datos)
		{
			notify('Error al registrar la orden de producción',500,5000,'error',0,0);							
			$('#agregandoOrdenProduccion').html('');
		}
	});
}



function obtenerFormularioProduccion()
{
	$('#ventanaOrdenProduccion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarFormularioOrdenProduccion').html('<img src="'+ img_loader +'"/> Espere por favor...');
		},
		type:"POST",
		url:base_url+'ordenes/formularioProduccion',
		data:
		{
			//"idVenta":idVenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarFormularioOrdenProduccion').html(data);
			$('#txtBuscarProducto').focus();
		},
		error:function(datos)
		{
			$('#cargarFormularioOrdenProduccion').html('Error al obtener el formulario para realizar la orden de producción');
		}
	});
}

//======================POR PROCESOS======================//

$(document).ready(function()
{
	$("#ventanaProcesosProduccion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:800,
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
				registrarProcesoProducido()
			},
		},
		close: function() 
		{
			$('#cargarProcesosProduccion').html('');	
		}
	});
});

function registrarProcesoProducido()
{
	try
	{
		var mensaje="";
	
		var cantidad=$("#txtCantidadProducido").val();

		if($("#txtFechaProducido").val()=="")
		{
			mensaje+="La fecha es incorrecta <br />";
		}
		
		if($("#txtSuperviso").val()=="")
		{
			mensaje+="Error en la persona que superviso <br />";										
		}
		
		if (!cantidad.match(RegExPatternX) || parseFloat($('#txtCantidadProducido').val())<1 || isNaN($('#txtCantidadProducido').val())) 
		{
			mensaje+="La cantidad es incorrecta ";										
		}
		
		/*if((parseFloat($('#txtCantidadProducido').val())+parseFloat($('#txtTotalProducido').val()))>parseFloat($('#txtTotalOrden').val()))  
		{
			mensaje+="La cantidad es incorrecta ";										
		}*/
		
		if(mensaje.length>0)
		{
			notify(mensaje,500,4000,"error",30,5);
			return;
		}
		
		if(!confirm('¿Realmente desea realizar el registro?'))return;
		

		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#cargandoProcesosProduccion').html('<img src="'+ img_loader +'"/>Registrando la producción del proceso, por favor espere...');},
			type:"POST",
			url:base_url+"ordenes/procesosProducido",
			data:
			{
				"idOrden":			$("#txtIdOrden").val(),
				"idRelacion":		$("#txtIdRelacion").val(),
				"idRelacionPasada":	$("#txtIdRelacionPasada").val(),
				"prioridad":		$("#txtPrioridad").val(),
				"fecha":			$("#txtFechaProducido").val(),
				"idProducto":		$("#txtIdProducto").val(),
				"superviso":		$("#txtSuperviso").val(),
				"cantidad":			$("#txtCantidadProducido").val()
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#cargandoProcesosProduccion').html('');
				data=eval(data);
				switch(data[0])
				{
					case "0":
						notify(data[1],500,4000,"error",30,5);
					
					break;
					case "1":
						obtenerDetallesProceso($("#txtIdOrden").val(),$("#txtIdRelacion").val(),$("#txtPrioridad").val(),$("#txtIdRelacionPasada").val());
						obtenerOrdenes();
						notify('El registro ha sido correcto',500,4000,"",30,5);
					break;
					
				}
			},
			error:function(datos)
			{
				$('#cargandoProcesosProduccion').html('');
				notify('Error en el proceso de producción',500,4000,"error");
			}
		});					  	  
	}
	catch(datos)
	{
		$(this).dialog('close');
	}
}

function obtenerDetallesProceso(idOrden,idRelacion,prioridad,procesoAnterior)
{
	$('#ventanaProcesosProduccion').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarProcesosProduccion').html('<img src="'+ img_loader +'"/>Obteniendo los detalles del proceso de producción...');
		},
		type:"POST",
		url:base_url+'ordenes/obtenerDetallesProceso',
		data:
		{
			"idOrden":		idOrden,
			"idRelacion":		idRelacion,
			"prioridad":		prioridad,
			"procesoAnterior":	procesoAnterior,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarProcesosProduccion').html(data);
			$('#txtCantidadProducido').focus();
		},
		error:function(datos)
		{
			$('#cargarProcesosProduccion').html('Obteniendo los detalles del proceso de producción');
		}
	});
}


function obtenerProductosOrden()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarProductosOrden').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+"ordenes/obtenerProductosOrden",
		data:
		{
			"idCliente":$("#id_cli").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarProductosOrden").html(data);
		},
		error:function(datos)
		{
			$("#cargarProductosOrden").html('Error');	
		}
	});//Ajax	
}
 
 function agregarOrdenProduccionas(n)
 {
	 var URL=base_url+"ordenes/agregarOrden";
	 
	 if(confirm('¿Realmente desea realizar la orden de produccion?')==false)
	 {
		 //$('#chkProduccion'+n).fadeIn();
		 document.getElementById('chkProduccion'+n).checked=false;
		 return;
	 }
	 
	 $('#generandoOrdenProduccion').fadeIn();
	 $.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoOrdenProduccion').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"autor":'Admin',
			"idProducto":$("#idProducto"+n).val(),
			"cantidad":$("#cantidad"+n).val(),
			"idProductoCotizado":$("#idProductoCotizado"+n).val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#ErrorOrdenProduccion").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
				$('#generandoOrdenProduccion').fadeOut();
				document.getElementById('chkProduccion'+n).checked=false;
				break;
				case "1":
				img='<img src="http://'+base_url+'img/success.png" width="16"/>';
				$('#filaOrden'+n).html(img);
				$('#generandoOrdenProduccion').fadeOut();
				
				break; 
				
				case "2":
				$("#ErrorOrdenProduccion").fadeIn();
				$("#ErrorOrdenProduccion").html("<p>No existe suficiente material para realizar la orden " +
												  "de produccion.</p>");
				$('#generandoOrdenProduccion').fadeOut();
				document.getElementById('chkProduccion'+n).checked=false;
				break;
				
				case "3":
				$("#ErrorOrdenProduccion").fadeIn();
				$("#ErrorOrdenProduccion").html("<p>No existe el producto en el catálogo</p>");
				//document.getElementById('id_CargandoOrdenProduccion').style.display="none";
				$('#generandoOrdenProduccion').fadeOut();
				document.getElementById('chkProduccion'+n).checked=false;
				break;
			}//switch
		},
		error:function(datos)
		{
			$("#cargarProductosOrden").html('Error');	
		}
	});//Ajax	
 }

$(document).ready(function()
{
	$("#ventanaProductoProducido").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:650,
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
				registraProducidoFinal()
			},
		},
		close: function() 
		{
			$('#cargarOrdenProduccion').html('');
		}
	});
});

function registraProducidoFinal()
{
	try
	{
		var mensaje		= "";
		var cantidad	= $("#txtCantidadProducido").val();

		if($("#txtFechaProducido").val()=="")
		{
			mensaje+="La fecha es incorrecta <br />";
		}
		
		if($("#txtSuperviso").val()=="")
		{
			mensaje+="Error en la persona que superviso <br />";										
		}
		
		if (!cantidad.match(RegExPatternX) || parseFloat($('#txtCantidadProducido').val())<1 || isNaN($('#txtCantidadProducido').val())) 
		{
			mensaje+="La cantidad es incorrecta ";										
		}
		
		/*if((parseFloat($('#txtCantidadProducido').val())+parseFloat($('#txtTotalProducido').val()))>parseFloat($('#txtTotalOrden').val()))  
		{
			mensaje+="La cantidad es incorrecta ";										
		}*/
		
		if(mensaje.length>0)
		{
			notify(mensaje,500,4000,"error",30,5); 
			return;
		}
		
		if(!confirm('¿Realmente desea realizar el registro?'))return;
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#cargandoProducido').html('<img src="'+ img_loader +'"/>Registrando el producto terminado, por favor espere...');},
			type:"POST",
			url:base_url+"ordenes/ordenProducido",
			data:
			{
				"idOrden":			$("#txtIdOrden").val(),
				"idRelacion":		$("#txtIdRelacion").val(),
				"fecha":			$("#txtFechaProducido").val(),
				"fechaCaducidad":	$("#txtFechaCaducidad").val(),
				"idProducto":		$("#txtIdProducto").val(),
				"superviso":		$("#txtSuperviso").val(),
				"materiaPrima":		$("#txtMateriaPrima").val(),
				"cantidad":			cantidad
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#cargandoProducido').html('');
				data=eval(data);
				switch(data[0])
				{
					case "0":
						notify(data[1],500,4000,"error",30,5); 
					break;

					case "1":
						obtenerProducido($("#txtIdOrden").val(),$("#txtIdRelacion").val());
						obtenerOrdenes();
						notify('El registro ha sido correcto',500,4000,"",30,5); 
					break;
					
					/*case "2":
						notify('No puede producir mas producto del registrado, esta superando la cantidad del proceso',500,4000,"error",30,5); 
					break;
					
					case "3":
						notify('El proceso anterior no cuenta con unidades para registrar el producto terminado',500,4000,"error",30,5); 
					break;*/
				}
			},
			error:function(datos)
			{
				$('#cargandoProducido').html('');
				notify('Error al producir el producto ',500,4000,"error",30,5); 
			}
		});					  	  
	}
	catch(datos)
	{
		$(this).dialog('close');
	}
}

function obtenerProducido(idOrden,idRelacion)
{
	$('#ventanaProductoProducido').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarOrdenProduccion').html('<img src="'+ img_loader +'"/> Obteniendo los detalles de la orden de producción...');
		},
		type:"POST",
		url:base_url+"ordenes/buscarDetalles/",
		data:
		{
			"idOrden":idOrden,
			"idRelacion":idRelacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarOrdenProduccion").html(data);
			$('#txtCantidadProducido').focus();
		},
		error:function(datos)
		{
			$("#cargarOrdenProduccion").html('Error al obtener los detalles');	
		}
	});	
}

function obtenerMaterialesProducto()
{
	cantidad	= obtenerNumero($('#txtCantidadProduccion').val());
	cantidad	= cantidad==0?1:cantidad;
	
	if($('#txtIdProducto').val()=="0")
	{
		//notify('Seleccione el producto',500,4000,"error",30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerMaterialesProducto').html('<img src="'+ img_loader +'"/> Obteniendo los detalles materiales...');
		},
		type:"POST",
		url:base_url+"ordenes/obtenerMaterialesProducto",
		data:
		{
			"idProducto": 		$('#txtIdProducto').val(),
			"cantidadOrden": 	$('#txtCantidadProduccion').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMaterialesProducto").html(data);
		},
		error:function(datos)
		{
			$("#obtenerMaterialesProducto").html('');	
		}
	});
}



function comprobarMaterialesOrden()
{
	mensaje	= "";
	procesos	= new Array();

	if($("#txtIdProducto").val()=="0")
	{
		mensaje+="Por favor seleccione un producto <br />";
	}
	
	if($("#txtCantidadProduccion").val()=="" || parseInt($("#txtCantidadProduccion").val())<1 || isNaN($("#txtCantidadProduccion").val()))
	{
		mensaje+="La cantidad a producir es incorrecta";
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#agregandoOrdenProduccion').html('<img src="'+ img_loader +'"/> El sistema esta comprobando los materiales del producto...');},
		type:"POST",
		url:base_url+'ordenes/comprobarMaterialesOrden',
		data:
		{
			"idProducto":	$("#txtIdProducto").val(),
			"cantidad":		$("#txtCantidadProduccion").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//data	= eval(data);
			
			if(!confirm(data)) return;
		},
		error:function(datos)
		{
			notify('Error al registrar la orden de producción',500,5000,'error',0,0);							
			$('#agregandoOrdenProduccion').html('');
		}
	});
}

//ORDEN DE PRODUCCIÓN

$(document).ready(function()
{
	$("#ventanaCancelarOrden").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:600,
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
				cancelarOrden()
			},
		},
		close: function() 
		{
			$('#obtenerDetallesOrden').html('');	
		}
	});
});

function obtenerDetallesOrden(idOrden)
{
	$("#ventanaCancelarOrden").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDetallesOrden').html('<img src="'+ img_loader +'"/> Obteniendo los detalles de la orden de producción...');
		},
		type:"POST",
		url:base_url+"ordenes/obtenerDetallesOrden",
		data:
		{
			"idOrden":idOrden,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDetallesOrden").html(data);
		},
		error:function(datos)
		{
			$("#obtenerDetallesOrden").html('');	
		}
	});	
}

function cancelarOrden()
{
	if(!camposVacios($("#txtMotivosCancelacion").val()))
	{
		notify('Escriba los motivos de cancelación',500,4000,"error",30,5); 
		return;
	}
	
	if(!confirm('¿Realmente desea cancelar la orden?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cancelandoOrden').html('<img src="'+ img_loader +'"/>Cancelando la orden...');},
		type:"POST",
		url:base_url+"ordenes/cancelarOrden",
		data:
		{
			"idOrden":	$("#txtIdOrden").val(),
			motivos:	$("#txtMotivosCancelacion").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cancelandoOrden').html('');
			
			switch(data)
			{
				case "0":
					notify('Error al cancelar la orden ',500,4000,"error",30,5); 
				break;

				case "1":
					location.reload();
				break;
			}
		},
		error:function(datos)
		{
			$('#cancelandoOrden').html('');
			notify('Error al cancelar la orden',500,4000,"error",30,5); 
		}
	});					  	  
}

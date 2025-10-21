$(document).ready(function()
{
	$("#ventanaEntregarProductos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:320,
		width:850,
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
				entregarProductosVenta();
			},
		},
		close: function() 
		{
			$('#entregaProductos').html('');
		}
	});
});

function entregarProductosVenta()
{
	var mensaje="";
	
	if($("#FechaDia").val()=="")
	{
		mensaje+="La fecha es incorrecta <br />";
	}
	
	if($("#txtEntrego").val()=="")
	{
		mensaje+="Por favor especifique quien entrego el producto <br />";										
	}
	
	if (!comprobarNumeros($("#txtCantidadEntregar").val()) || parseFloat($("#txtCantidadEntregar").val())==0) 
	{
		mensaje+="La cantidad es incorrecta";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm("¿Realmente desea realizar la entrega del producto?")) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#entregandoProductos').html('<img src="'+ img_loader +'"/> Realizando el envio, por favor espere...');},
		type:"POST",
		url:base_url+"ventas/entregarProductos",
		data:
		{
			"idProductoCaja":	productoCaja,
			"idProducto":		productoEntregar,
			"entrego": 			$("#txtEntrego").val(),
			"fecha":			$("#FechaDia").val(),
			"cantidad":			$("#txtCantidadEntregar").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#entregandoProductos').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					//location.reload();
					if ($("#txtModuloEnvios").val() == "ventas") {
						obtenerVentas();
					}
					else {
						obtenerReporte();
					}
					notify(data[1],500,5000,'',30,5);
					$('#ventanaEntregarProductos').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			$('#entregandoProductos').html('');
			notify('Error en la entrega de los productos',500,5000,'error',0,0);
		}
	});
}

productoEntregar=0;
productoCaja=0;

function obtenerProductosEntregados(idProducto,idProductoCaja)
{
	$('#ventanaEntregarProductos').dialog('open');
	
	productoEntregar=idProducto;
	productoCaja	=idProductoCaja;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#entregaProductos').html('<img src="'+ img_loader +'"/> Obteniendo productos entregados...');},
		type:"POST",
		url:base_url+"ventas/buscarEntregas/"+idProducto,
		data:
		{
			
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#entregaProductos").html(data);
			$("#txtEntrego").val('');
			$("#txtCantidadEntregar").val('');
		},
		error:function(datos)
		{
			$("#ErrorEntrega").fadeIn();
			$("#ErrorEntrega").html(datos);	
		}
	});//Ajax			
}

function enviarTodosProductos(idCotizacion)
{
	if(!confirm("¿Realmente desea realizar la entrega de todos los productos?")) return;

	//DIBUJAR EL LINK NUEVAMENTE EN CASO DE ERROR
	linkEnvio='<img onclick="enviarTodosProductos('+idCotizacion+')" src="'+base_url+'img/truck.png" title="Entregar todos" ';
	linkEnvio+='width="25" height="25" style="cursor:pointer;" />';
	linkEnvio+='<br />Enviar todos';
			
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#enviandoTodos'+idCotizacion).html('<img src="'+ img_loader +'"/> Se estan registrando las entregas de los productos, por favor espere...');},
		type:"POST",
		url:base_url+"ventas/enviarTodosProductos",
		data:
		{
			idCotizacion: idCotizacion
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
					
					$('#enviandoTodos'+idCotizacion).html(linkEnvio);
				break;
				
				case "1":
					//location.reload();
					notify(data[1],500,5000,'',30,5);

					if ($("#txtModuloEnvios").val() == "ventas") {
						obtenerVentas();
					}
					else {
						obtenerReporte();
					}
				break;
				
				/*case "2":
					notify('No existen suficientes productos para ser entregados, revise por favor el inventario',500,5000,'error',30,5);
					$('#enviandoTodos'+idCotizacion).html(linkEnvio);
				break;*/
			}
		},
		error:function(datos)
		{
			notify('Error en la entrega de los productos',500,5000,'error',30,5);
			$('#enviandoTodos'+idCotizacion).html(linkEnvio);
		}
	});//Ajax			
}




function entregarProductos(idProducto,cantidad,unidades,i,a,idProductoCaja)
{
	//$("#entregaProductos").load(base_url+"ventas/buscarEntregas/"+idProducto);
	
	$(document).ready(function()
	{
		//$("#"+id).click(function(e){
		$('#dialog-Entrega').dialog('open');
		//});
		
		$("#dialog-Entrega").dialog(
		{
		autoOpen:false,
		height:400,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				var   mensaje="";
	
				var TC=$("#txtCantidadEntregar").val();
				var URL=base_url+"ventas/entregarProductos";
				
				if($("#txtFechaEntrega").val()=="")
				{
					mensaje+="Error en la fecha <br />";
				}
				
				if($("#txtEntrego").val()=="")
				{
					mensaje+="Por favor especifique quien entrego el producto <br />";										
				}
				
				if (!TC.match(RegExPatternX)) 
				{
					mensaje+="La cantidad es incorrecta <br />";										
				}
				
				insertar=(parseInt(TC))+(parseInt(unidades));
				cantidad=parseInt(cantidad);
				insertar=parseInt(insertar);
	
				if(parseInt($("#txtCantidadEntregar").val())>parseInt(unidades))
				{
					mensaje+="Error en la cantidad <br />";
					return;
				}
				
				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',0,0);
					return;
				}

				$('#id_CargandoEntrega').fadeIn();
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#id_CargandoEntrega').html('<img src="'+ img_loader +'"/> Realizando la entrega...');},
					type:"POST",
					url:URL,
					data:
					{
						"idProducto":idProducto,
						"idProductoCaja":idProductoCaja,
						"entrego":$("#txtEntrego").val(),
						"fecha":$("#txtFechaEntrega").val(),
						"cantidad":TC,
						"total":cantidad
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$('#id_CargandoEntrega').fadeOut();
							notify('Error al enviar los productos, por favor verifique las existencias y la conexión a internet',500,5000,'error',0,0);
							break;
							case "1":
							$('#id_CargandoEntrega').fadeOut();
							document.getElementById('entrega'+i+'_'+a).src=base_url+'img/truck.png';
							obtenerProductosEntregados(idProducto);
							break;
							case "2":
							notify('No existen suficientes productos para ser entregados',500,5000,'error',0,0);
							break;
							case "3":
							$('#id_CargandoEntrega').fadeOut();
							notify('Imposible entregar mas productos de los vendidos',500,5000,'error',0,0);
							break;
						}
					},
					error:function(datos)
					{
						$("#ErrorEntrega").fadeIn();
						$("#ErrorEntrega").html(datos);	
					}
				});//Ajax						  	  
			},
			Cancelar: function() 
			{
				$("#ErrorEntrega").fadeOut(); 
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#ErrorEntrega").fadeOut();
		}
		});
		//*********************** Terminar ***********************
	});
}

function entregasTotales(id)
{
	$("#productosEntregados").load(base_url+"ventas/buscarEntregas/"+id);
	//idCliente=id;
	
	$('#dialog-Entregados').dialog('open');
	
	$("#dialog-Entregados").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:500,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$("#ErrorEntregados").fadeOut(); 
				$(this).dialog('close');				 
			}
			
		},
		close: function()
		{
			$("#ErrorEntregados").fadeOut();
		}
	});
}

$(document).ready(function()
{
	$("#ventanaEditarEntrega").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:220,
		width:750,
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
				editarEntrega();
			},
		},
		close: function() 
		{
			$('#formularioEditarEntrega').html('');
		}
	});
});

function formularioEditarEntrega(idEntrega)
{
	$('#ventanaEditarEntrega').dialog('open');

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioEditarEntrega').html('<img src="'+ img_loader +'"/> Obteniendo productos entregados...');},
		type:"POST",
		url:base_url+"ventas/formularioEditarEntrega",
		data:
		{
			idEntrega:idEntrega
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioEditarEntrega").html(data);
			$("#txtCantidadEditarEntregar").focus();
		},
		error:function(datos)
		{
			$("#formularioEditarEntrega").fadeIn();
		}
	});//Ajax			
}

function editarEntrega()
{
	var mensaje="";

	if (obtenerNumeros($("#txtCantidadEditarEntregar").val()) > obtenerNumeros($("#txtCantidadPendiente").val())) 
	{
		mensaje+="La cantidad es incorrecta o esta superando el inventario";										
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',0,0);
		return;
	}
	
	if(!confirm("¿Realmente desea editar la entrega del producto?")) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoEntrega').html('<img src="'+ img_loader +'"/> Realizando el envio, por favor espere...');},
		type:"POST",
		url:base_url+"ventas/editarEntrega",
		data:$('#frmEditarEntrega').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoEntrega').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				
				case "1":
					//location.reload();
					$('#ventanaEditarEntrega').dialog('close');
					$('#ventanaEntregarProductos').dialog('close');
					
					notify(data[1], 500, 5000, '', 30, 5);

					if ($("#txtModuloEnvios").val() == "ventas")
					{
						obtenerVentas();
					}
					else
					{
						obtenerReporte();
					}
					
				break;
			}
		},
		error:function(datos)
		{
			$('#editandoEntrega').html('');
			notify('Error en la entrega de los productos',500,5000,'error',0,0);
		}
	});
}

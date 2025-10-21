
 
 var RegExPatternX = new RegExp("[0123456789. -]");
idProducto=0;

function inventario(id)
{
	idProducto=id;
}


$(document).ready(function(){

for(i=1;i<20;i++)
{
	$("#agregar"+i).click(function(e){
	   $('#dialog-Agregar').dialog('open');
	});
}


$("#dialog-Agregar").dialog({
     autoOpen:false,
       height:450,
        width:600,
        modal:true,
    resizable:false,
      buttons: {
	 	'Aceptar': function() {
			
		       
			   
			   var Mensage="";
			   var CANT=$("#canti").val();
			   
			   var URL=base_url+"inventarioProductos/agregarProducto";
			   //*** validar campos *****	 					 
				if($("#idProducto").val()==""){
				 Mensage+="<p>Error en el producto</p>";
			    }
				
				var pro=parseInt($("#inputString").val());
			   
			   if (!CANT.match(RegExPatternX)) {
			      Mensage+="<p>Error en la cantidad.</p>";										
			   }//Telefono
			   
			   
              if(Mensage.length==0){
			  // Guardar los datos con Ajax
			  $('#id_CargandoAgregar').fadeIn();
            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#id_CargandoAgregar').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
          //data:{"idProducto":$("#T11").val(),"idMaterial":$("#T22").val(),"costo":costo,"cantidad":T44},
		  data:
		  {
			  "idProducto":idProducto,
			  "cantidad":CANT,
			  "idProductoProduccion":$("#idProducto").val()
		  },
           datatype:"html",
            success:function(data, textStatus){
								       					   					   
                           switch(data){
                                   case "0":
                                            $("#ErrorAgregar").fadeIn();
                                            $("#ErrorAgregar").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
                                             break;
                                   case "1":
                                            window.location.href=base_url+"inventarioProductos/index/"+$('#pagina').val();
                                            break;
											
								   case "2":
                                            $("#ErrorAgregar").fadeIn();
                                            $("#ErrorAgregar").html("<p>El producto seleccionado no tiene sus conceptos definidos.</p>");
											//alert('No existen suficientes productos para realizar el registro');
											 $('#id_CargandoAgregar').fadeOut();
											// window.location.href=base_url+"inventarioProductos";
											break;
											
									case "3":
                                            $("#ErrorAgregar").fadeIn();
                                            $("#ErrorAgregar").html("<p>Ya ha sido agregado el producto.</p>");
											//alert('No existen suficientes productos para realizar el registro');
											 $('#id_CargandoAgregar').fadeOut();
											// window.location.href=base_url+"inventarioProductos";
											break;

                           }//switch
 	               },
	         error:function(datos){
                    $("#ErrorAgregar").fadeIn();
		    $("#ErrorAgregar").html(datos);	
                  }
           });//Ajax						  	  
				  				  				  
			  }//
			 else{
                  $("#ErrorAgregar").fadeIn();
		          $("#ErrorAgregar").html(Mensage);
			 }				 				 
		   //*** validar campos *****	       
		},
        Cancel: function() {
			    $("#ErrorAgregar").fadeOut(); 
                            $(this).dialog('close');				 
			  }
		},
	  close: function() {
    			   $("#ErrorAgregar").fadeOut();
			}
      });
  //*********************** Terminar ***********************
 });

	function busqueda()
	{
		div = document.getElementById('bus_id');
				filtro=div.value;
		
		if(filtro=='')
		{
			//alert('Escriba el nombre del producto a buscar');
			showDialog('ERROR','Escriba el nombre del producto a buscar','error',2);

			return;
		}
				
		document.buscarInventario.submit();
	}
	
	
	
	
	
	caja=0;
	idProductoProduccion=0;
	
	function edicionDetalle(idCaja,idProducto,nombre)
	{
		$('#cargarEditarDetalle').load(base_url+"inventarioProductos/editarDetalleCaja/"+idCaja+"/"+idProducto);
		$('#dialog-editarProductoDetalle').dialog('open');
		
		//nombre="Jajaja";
		window.setTimeout("$('#nombreDetalle').html('"+nombre+"')",500);
		caja=idCaja;
		idProductoProduccion=idProducto;
		//document.getElementById('dialog-editarProductoDetalle').setAttribute('title',"Jaja");
	}
	
	$(document).ready(function()
	{
		for(i=1;i<20;i++)
		{
			$("#editarDetalle"+i).click(function(e){
			   $('#dialog-editarProductoDetalle').dialog('open');
			});
		}
		
		$("#dialog-editarProductoDetalle").dialog(
		{
			autoOpen:false,
			height:210,
			width:600,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Aceptar': function() 
				{
					
					
					var Mensage="";
					var ganancia=$("#cantidadProducto").val();
					
					var URL=base_url+"inventarioProductos/editarDetalleConfirmar";

				     ganancia=parseFloat(ganancia);
					
					if (isNaN(ganancia))
					{
						Mensage+="<p>La cantidad es invalida</p>";
					}
					
					if(Mensage.length==0)
					{
						$('#id_CargandoEditarDetalleCaja').fadeIn();
						
						$.ajax(
						{
							async:true,
							beforeSend:function(objeto){$('#id_CargandoEditarDetalleCaja').html('<img src="'+ img_loader +'"/> Espere...');},
							type:"POST",
							url:URL,
							data:
							{
								"id":caja,
								'idProductoProduccion':idProductoProduccion,
								"cantidad":$("#cantidadProducto").val()
							},
							datatype:"html",
							success:function(data, textStatus)
							{
								switch(data)
								{
									case "0":
									$("#ErrorEditarDetalleCaja").fadeIn();
									$("#ErrorEditarDetalleCaja").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
									break;
									
									case "1":
									window.location.href=base_url+"inventarioProductos/index/"+$('#pagina').val();
									break;
								}//switch
							},
							error:function(datos)
							{
								$("#ErrorEditarDetalleCaja").fadeIn();
								$("#ErrorEditarDetalleCaja").html(datos);	
							}
						});//Ajax						  	  
						
					}//
					else
					{
						$("#ErrorEditarDetalleCaja").fadeIn();
						$("#ErrorEditarDetalleCaja").html(Mensage);
					}				 				 
					//*** validar campos *****	       
				},
				Cancel: function() 
				{
					$("#ErrorEditarDetalleCaja").fadeOut(); 
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#ErrorEditarDetalleCaja").fadeOut();
			}
		});
	})
	
	proveedor=0;

	function confirmarProveedor()
	{
		//if(confirm('Cambiar de proveedor implica borrar la lista actual, ¿desea continuar?')==true)
		//{
			/*html='';
			html+='<tr>'
			html+='<th>#</th>'
			html+='<th>Nombre</th>'
			html+='<th>Precio unitario</th>'
			html+='<th>Cantidad</th>'
			html+='<th>Total</th>'
			html+='</tr>'
			
			$('#armarKit').html("");
			$('#armarKit').append(html);*/
			
			calcularTotales();
			listaProductosServicios();
		//}
	}
	
	
	
	function imprimirTicket()
	{
		var ficha = document.getElementById('ticket');
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
	}
	
	function limpiarVentana()
	{
		//listaProductosServicios();
		$('#cambioVenta').val('0');
		$('#pagoVenta').val('0');
		$('#ivaVenta').val('16');
		$('#kitTotal').val('0');
		$('#totalVenta').val('0');
		$('#nombreKit').val('');
		
		data='<th>#</th><th>Nombre</th><th>Precio unitario</th><th>Cantidad</th><th>Total</th>';
		
		$('#armarKit').html(data);
		
		
		data='<select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:80%;" >'+
         	 '<option value="1">Efectivo</option></select>';
			 
		$('#cargarCuenta').html(data);
		
		$('#cargarBancos').load(base_url+'clientes/obtenerBancos');
		
		data='<select id="TipoPago" name="TipoPago" class="cajas" style="width:80%;" onchange="mostrarDatos()">'+
            '<option value="1" >Efectivo</option>'+
            '<option value="2" >Cheque</option>'+
            '<option value="3" >Transferencia</option>'+
            '<option value="4" >Terminal bancaria</option>'+
            ' </select>  ';
		
		$('#cargarTipoPago').html(data);
		$('#mostrarTransferencia').fadeOut();
		$('#mostrarCheques').fadeOut();
		$('#numeroCheque').val('');
		$('#numeroTransferencia').val('');
	}

	function cargarTicket(idVenta)
	{
		URL=base_url+"inventario/imprimirTicket/"+idVenta;
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#productosKit').html('<img src="'+ img_loader +'"/> Se esta cargando el ticket para imprimirlo...');
			},
			type:"POST",
			url:URL,
			data:
			{
				"idVenta":idVenta,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#ticket').html(data);
				//$('#id_CargandoRecibido').fadeOut();
				listaProductosServicios();
				imprimirTicket();
				
			},
			error:function(datos)
			{
				alert('Error al procesar el ticket de venta');
			}
		});//Ajax	
	}

	fila	=0; //Es el numero de fila del producto donde ira el kit

	
	
	/*function calcularTotales() //Calular el total del kit de productos
	{
		totalKit=0;
		
		for(i=0;i<fila;i++)
		{
			precio=parseFloat($('#totalProducto'+i).val());
			
			if(!isNaN(precio))
			{
				totalKit+=precio;
			}
		}
		
		$('#kitTotal').val(totalKit);
		
		if($('#paginaActiva').val()=="1")
		{
			iva=parseFloat($('#ivaVenta').val())/100;
			totalVenta=totalKit+(iva*totalKit)
			$('#totalVenta').val(totalVenta)
			
			//calcularCambio();
		}
	}*/
	
	function calcularCambio()
	{
		total=parseFloat($('#totalVenta').val());
		pago=parseFloat($('#pagoVenta').val());
		
		cambio=pago-total;
		
		$('#cambioVenta').val(cambio)
		
	}
	
	$(function()
	{
		$("#mostrar1").click(function(event) 
		{
			event.preventDefault();
			$("#productosKit").slideToggle();
		});
		
		$("#caja a1").click(function(event) 
		{
			event.preventDefault();
			$("#productosKit").slideUp();
		});
	});
	
	//Recibir compras
	
	function obtenerProductosCompradosSS(idCompra)
	{
		var URL=base_url+"compras/obtenerProductosComprados";
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#id_CargandoRecibido').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:URL,
			data:
			{
				"idCompras":idCompra,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#carga').html("");
				$('#carga').append(data);
				$('#id_CargandoRecibido').fadeOut();
				
			},
			error:function(datos)
			{
				$("#Error-Recibido").fadeIn();
				$("#Error-Recibido").html(datos);	
			}
		});//Ajax	
	}
	
	
	
	function confirmarRecibirCompra(idDetalle,idCompra,cantidad,totalRecibido)
	{
		recibir=parseFloat($('#txtRecibir'+idDetalle).val());
		totalRecibido=parseFloat(totalRecibido);
		cantidad=parseFloat(cantidad);
		
		if(isNaN($('#txtRecibir'+idDetalle).val())) 
		{
			alert('La cantidad a recibir no es correcta');
			document.getElementById('chkCompras'+idDetalle).checked=false;
			return;
		}
		
		if($('#txtRecibir'+idDetalle).val()=="") 
		{
			alert('La cantidad a recibir no es correcta');
			document.getElementById('chkCompras'+idDetalle).checked=false;
			return;
		}
		
		if(parseFloat(recibir)<1) 
		{
			alert('La cantidad a recibir debe ser mayor a cero');
			document.getElementById('chkCompras'+idDetalle).checked=false;
			return;
		}
		
		if((recibir+totalRecibido)>cantidad) 
		{
			alert('No puede recibir mas del total de unidades');
			document.getElementById('chkCompras'+idDetalle).checked=false;
			return;
		}
		
		if(confirm('Confirmar que se ha recibido el producto')==false) 
		{
			document.getElementById('chkCompras'+idDetalle).checked=false;
			return;
		}

		$('#id_CargandoRecibido').fadeIn();
		
		URL=base_url+"compras/confirmarRecibirCompra";
		
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#id_CargandoRecibido').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:URL,
			data:
			{
				"idDetalle":idDetalle,
				"idProducto":$('#txtProducto'+idDetalle).val(),
				"cantidad":$('#txtRecibir'+idDetalle).val(),
				"totalRecibido":totalRecibido,
				"totalUnidades":cantidad
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				//$('#carga').html("");
				//$('#carga').append(data);
				$('#id_CargandoRecibido').fadeOut();
				obtenerProductosComprados(idCompra);
			},
			error:function(datos)
			{
				$("#Error-Recibido").fadeIn();
				$("#Error-Recibido").html(datos);	
			}
		});//Ajax		
	}
	
	function obtenerDescuentos(idProveedor)
	{
		URL=base_url+"compras/obtenerDescuentos";
		
		$('#cargandoDescuentos').fadeIn();
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargandoDescuentos').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:URL,
			data:
			{
				"idProveedor":idProveedor,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#cargarDescuentos').html("");
				$('#cargarDescuentos').append(data);
				$('#cargandoDescuentos').fadeOut();
			},
			error:function(datos)
			{
				$("#ErrorDescuentos").fadeIn();
				$("#ErrorDescuentos").html(datos);	
			}
		});//Ajax	
	}
	
	function agregarDescuento(idProveedor,idCompra)
	{
		$('#dialogoDescuentos').dialog('open');

		obtenerDescuentos(idProveedor);
		
		URL=base_url+"compras/confirmarDescuento";
		
		$("#dialogoDescuentos").dialog(
		{
			autoOpen:false,
			height:200,
			width:400,
			modal:true,
			resizable:false,
			buttons:
			{
				'Guardar': function() 
				{
					if(confirm('¿Realmente desea aplicar el descuento adicinal?')==false)
					{
						return;
					}

					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto){$('#productosKit').html('<img src="'+ img_loader +'"/> Espere...');},
						type:"POST",
						url:URL,
						data:
						{
							"descuento":$("#descuentosProveedor").val(),
							"idProveedor":idProveedor,
							"idCompra":idCompra
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								alert('Error al realizar el registro')
								break;
								case "1":
								window.location.href=base_url+"compras/administracion";
								break;
							}//switch
						},
						error:function(datos)
						{
							alert('Error al agregar el descuento adicional')
						}
					});//Ajax						  	  
				},
				Cancel: function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				//$("#ErrorContactoAdd").fadeOut();
			}
		})
	}
	
function obtenerPorcentaje()
{
 	URL=base_url+"inventario/obtenerPorcentaje";
 	
	if($('#proveedor').val()=='0')
	{
		$('#descuentos').html('');
		$('#precioOculto').fadeOut();
		$('#costoOculto').fadeOut();
		
		return;
	}
	
	 $.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#descuentos').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"idProveedor":$('#proveedor').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#descuentos').html(data);
			$('#precioOculto').fadeIn();
			$('#costoOculto').fadeIn();
			
			calcularCosto()
		},
		error:function(datos)
		{
			$("#descuentos").html("Error");	
		}
	});//Ajax	
}
 
 function calcularCosto()
 {
	 porcentaje=parseFloat($('#precio').val())*parseFloat($('#porcentajeDescuento').val());
	 
	 precio=parseFloat($('#precio').val())-porcentaje;
	 
	 if(isNaN($('#precio').val()))
	 {
		 alert('El precio es incorrecto');
		 
		 $('#precio').val(0);
		 precio=0;
	 }
	 
	 $('#costo').val(precio);
 }
 
 function prueba()
 {
	 $('#fresita').load(base_url+'inventario/imprimirCodigoBarras');
 }
 
 function obtenerCodigoBarras(idProducto)
 {
	 URL=base_url+"inventario/obtenerCodigoBarras";
	 
	 $('#cargandocodigoBarras').fadeIn();
	 
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandocodigoBarras').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"idProducto":idProducto,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandocodigoBarras').fadeOut();
			$('#cargarcodigoBarras').html(data);
			
		},
		error:function(datos)
		{
			$("#cargarcodigoBarras").html("Error");	
		}
	});//Ajax	
 }
	
	function confirmarImprimirCodigo()
	{
		var ficha = document.getElementById('cargameCodigo');
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
	}
	
 function numeroImpresiones()
 {
	 URL=base_url+"inventario/imprimirCodigoBarras";
	 
	 $('#cargandocodigoBarras').fadeIn();
	 
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandocodigoBarras').html('<img src="'+ img_loader +'"/> Cargando codigo para impresion...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"codigo":$('#codigoImprimir').val(),
			"numero":$('#numeroCodigos').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//confirmarImprimirCodigo();
			$('#cargandocodigoBarras').fadeOut();
			$('#cargameCodigo').html(data);
			 confirmarImprimirCodigo();
			$(this).dialog('close');
			
		},
		error:function(datos)
		{
			$("#cargarcodigoBarras").html("Error");	
		}
	});//Ajax	
 }
 
 function imprimirCodigo(idProducto)
 {
	 $('#codigoBarras').dialog('open');

	 	obtenerCodigoBarras(idProducto)
		
		$("#codigoBarras").dialog(
		{
			autoOpen:false,
			height:300,
			width:500,
			modal:true,
			resizable:false,
			buttons:
			{
				'Imprimir': function() 
				{
					if(confirm('¿Realmente desea imprimir el codigo de barras?')==false)
					{
						return;
					}
					else
					{
						numeroImpresiones();
					}
				},
				'Cancelar': function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				//$("#ErrorContactoAdd").fadeOut();
			}
		})
 }
 
 //==========================================================================================================//
 
function editarPrecioMaterial(i,idMaterial,idProveedor)
{
	if(isNaN($('#precio'+i).val()) || parseFloat($('#precio'+i).val())<0 || $('#precio'+i).val()=="")
	{
		notify('El precio es incorrecto',500,5000,'error',30,5);
		$('#precio'+i).focus();
		return;
	}

	//if(!confirm('¿Realmente desea actualizar el precio?')) return;

	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#productosKit').html('<img src="'+ img_loader +'"/> Se esta cargando el ticket para imprimirlo...');
		},
		type:"POST",
		url:base_url+"compras/precioMaterial",
		data:
		{
			"idMaterial":	idMaterial,
			"idProveedor":	idProveedor,
			"costo":		$('#precio'+i).val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			//$('#ticket').html(data);
			//notify('El precio se ha actualizado correctamente',500,5000,'',30,5);
		},
		error:function(datos)
		{
			//notify('Error al actualizar el precio',500,5000,'error',30,5);
		}
	});//Ajax	
}

//===================================================================================================================================//
//==============================================================TIENDAS==============================================================//
//===================================================================================================================================//

/*Envios de productos a tiendas*/
function obtenerProductosEnvio()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarEnvios').html('<img src="'+ img_loader +'"/> Se esta cargando los productos para el envio...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/obtenerProductosEnvio',
		data:
		{
			"nombre":$('#txtBusquedaProductoEnviar').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarEnvios').html(data);
		},
		error:function(datos)
		{
			alert('Error al actualizar el precio del material');
		}
	});
}

function enviarProductosTienda(i)
{
	var mensaje="";
	
	if(parseFloat($('#txtCantidadEnviar'+i).val())<1 || isNaN($('#txtCantidadEnviar'+i).val()) || parseFloat($('#txtCantidadEnviar'+i).val()) > parseFloat($('#txtCantidadTotal'+i).val()) )
	{
		mensaje+="La cantidad a enviar es incorrecta <br />";
	}
	
	if($("#selectTiendas").val()=="0")
	{
		mensaje+="Por favor seleccione una tienda <br />";										
	}
			
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,3);
		document.getElementById('chkEnviar'+i).checked=false;
		return;
	}
	
	if(confirm('¿Realmente desea hacer el envio del producto?')==false)
	{
		document.getElementById('chkEnviar'+i).checked=false;
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#enviandoProducto').html('<img src="'+ img_loader +'"/> Se estan enviando los productos a la tienda...');
		},
		type:"POST",
		url:base_url+'inventarioProductos/enviarProductosTienda',
		data:
		{
			"idProducto":$('#txtIdProducto'+i).val(),
			"cantidad":$('#txtCantidadEnviar'+i).val(),
			"idTienda":$('#selectTiendas').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al enviar el producto a la tienda',500,5000,'error',30,3);
				$('#enviandoProducto').html('');
				document.getElementById('chkEnviar'+i).checked=false;
				break;
				
				case "1":
				notify('El producto se ha enviado correctamente',500,5000,'successs',30,3);
				$('#enviandoProducto').html('');
				obtenerProductosEnvio();
				break;
				
				case "2":
				notify('No existe suficientes productos para ser enviados',500,5000,'error',30,3);
				$('#enviandoProducto').html('');
				document.getElementById('chkEnviar'+i).checked=false;
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al enviar el producto a la tienda',500,5000,'error',30,3);
			document.getElementById('chkEnviar'+i).checked=false;
		}
	});
}

$(document).ready(function()
{
	$("#envios").click(function(e)
	{
		obtenerProductosEnvio();
		$('#ventanaEnviosProductos').dialog('open');
	});
	
	$("#ventanaEnviosProductos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:900,
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
			$("#ErrorRecibir").fadeOut();
		}
	});
});




	
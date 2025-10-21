//base_url="sanvalentin.redisoftsystem.com/";
var RegExPatternX = new RegExp("[0123456789 -]");
$(document).ready(function()
{
	//$('.ajax-pagsC > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pagsC > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#cargarProductosOrden";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idCliente":$("#clientes").val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
			success:function(html,textStatus)
			{
				setTimeout(function()
				{
					$(element).html(html);},300);
				},
				error:function(datos){$(element).html('Error '+ datos).show('slow');
			}
		});
	});//.ajax
})
	
$(document).ready(function()
{
	//$('.ajax-pags > li a').live('click',function(eve)
	$(document).on("click", ".ajax-pags > li a", function(eve)
	{
		eve.preventDefault();
		var element = "#cargarProductosOrden";
		var link = $(this).attr('href');
		
		$.ajax(
		{
			url:link,
			type:"POST",
			data:
			{
				"idCliente":$("#clientes").val(),
			},
			dataType:"html",
			beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
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

//*********************COMPRAS**********************

$(document).ready(function(){


$("#addCompra").click(function(e){
   $('#dialog-Compra').dialog('open');
});


$("#dialog-Compra").dialog({
     autoOpen:false,
       height:400,
        width:600,
        modal:true,
    resizable:false,
      buttons: {
	 	'Aceptar': function() {
			
		       
			   
			   var Mensage="";
			   var CANTI=$("#CANTI").val();
			   var TOT=$("#TOT").val();
			   
			   var URL=base_url+"compras/agregarCompra";
			   //*** validar campos *****	 					 
			   
			   if($("#IDCOM").val()==""){
				 Mensage+="<p>Error en el id</p>";
			    }
				
				if($("#selecprod").val()==""){
				 Mensage+="<p>Error en el producto</p>";
			    }
			
				var pro=parseInt($("#selecprod").val());
			   
			   if (!CANTI.match(RegExPatternX)) {
			      Mensage+="<p>Error en la cantidad.</p>";										
			   }//CANTIDAD
			   
			     if (!TOT.match(RegExPatternX)) {
			      Mensage+="<p>Error en el total.</p>";										
			   }//CANTIDAD
			   
			   //var costo=T44*T33;
			   
              if(Mensage.length==0){
			  // Guardar los datos con Ajax
			  
            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#id_CargandoCompra').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
          //data:{"idProducto":$("#T11").val(),"idMaterial":$("#T22").val(),"costo":costo,"cantidad":T44},
		  data:{"clave":$("#IDCOM").val(),"mate":pro,"canti":CANTI, "total":TOT},
           datatype:"html",
            success:function(data, textStatus){
								       					   					   
                           switch(data){
                                   case "0":
                                            $("#ErrorCompra").fadeIn();
                                            $("#ErrorCompra").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
                                             break;
                                   case "1":
                                            window.location.href=base_url+"compras";
                                            break;

                           }//switch
 	               },
	         error:function(datos){
                    $("#ErrorCompra").fadeIn();
		    $("#ErrorCompra").html(datos);	
                  }
           });//Ajax						  	  
				  				  				  
			  }//
			 else{
                          $("#ErrorCompra").fadeIn();
		          $("#ErrorCompra").html(Mensage);
			 }				 				 
		   //*** validar campos *****	       
		},
        Cancelar: function() {
			    $("#ErrorCompra").fadeOut(); 
                            $(this).dialog('close');				 
			  }
		},
	  close: function() {
    			   $("#ErrorCompra").fadeOut();
			}
      });
  //*********************** Terminar ***********************
 });
 
 
 //*********************EDITAR MATERIALES**********************
 productos="";
 materiales="";
 
function cargando(idProducto,idMaterial)
{
	$("#carga").load(base_url+"produccion/editarMaterial/"+idProducto+"/"+idMaterial);
	produc=idProducto;
	materiales=idMaterial;
}

/* --------------------------------COSTO ESTANDAR DE PRODUCCION ----------------------------------- */
$(document).ready(function()
{
	$("#costo-Estandar").click(function(e)
	{
		$('#dialog-Estandar').dialog('open');
		$('#estandares').load(base_url+"produccion/obtenerEstandar/");
	});
	
	$("#dialog-Estandar").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cerrar': function() 
			{
			$(this).dialog('close');	
			},
		},
		close: function() 
		{
			$("#ErrorEstandar").fadeOut();
		}
	});
});
 
 /*--------------------------------CANCELACION DE FACTURAS---------------------------------------*/
 
	 function cancelarFactura(folio,id)
	 {
		//var base_url="localhost/sanvalentin/";
		//$("#cancelar").load(base_url+"factura_ventas/buscarEntregas/"+id);
		$("#idFolio").val(folio)
		$(document).ready(function(){

		//$("#"+id).click(function(e){
		   $('#dialog-Cancelar-Factura').dialog('open');
		//});
		
		$("#dialog-Cancelar-Factura").dialog({
			 autoOpen:false,
			   height:250,
				width:500,
				modal:true,
			resizable:false,
			  buttons: {
				'Aceptar': function() {
					
					   
					   
					   var Mensage="";
					  // var T33=$("#T33").val();
					  
					   var URL=base_url+"factura_ventas/cancelarFactura";
					   
					 
						 
					   if($("#motivos").val()==""){
						  Mensage+="<p>Por favor ponga los motivos de cancelacion.</p>";										
					   }
					   
					   
					  if(Mensage.length==0){
					  // Guardar los datos con Ajax
					  
					$.ajax({
					  async:true,
				 beforeSend:function(objeto){$('#id_CargandoCancelacion').html('<img src="'+ img_loader +'"/> Espere...');},
					   type:"POST",
					url:URL,
				  //data:{"idProducto":$("#T11").val(),"idMaterial":$("#T22").val(),"costo":costo,"cantidad":T44},
				  data:{"idFactura":id,"motivos":$("#motivos").val()},
				   datatype:"html",
					success:function(data, textStatus){
																					   
								   switch(data){
										   case "0":
													$("#ErrorCancelacion").fadeIn();
													$("#ErrorCancelacion").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
													 break;
										   case "1":
													window.location.href=base_url+"factura_ventas/productos_cliente/"+$("#idCliente").val()+"/"+$("#idCotizacion").val();
													break;
													
		
								   }//switch
						   },
					 error:function(datos){
							$("#Error acion").fadeIn();
					$("#ErrorCancelacion").html(datos);	
						  }
				   });//Ajax						  	  
														  
					  }//
					 else{
								  $("#ErrorCancelacion").fadeIn();
						  $("#ErrorCancelacion").html(Mensage);
					 }				 				 
				   //*** validar campos *****	       
				},
				Cancelar: function() {
						$("#ErrorCancelacion").fadeOut(); 
									$(this).dialog('close');				 
					  }
				},
			  close: function() {
						   $("#ErrorCancelacion").fadeOut();
					}
			  });
		  //*********************** Terminar ***********************
			 });
	}
	
	/*------------------------------------------PRODUCTOS QUE SON SIMILARES----------------------------------------------------*/
	


//-------------------------PAGOS AL PROVEEDOR--------------------------------//
$(document).ready(function()
{
	$("#agregarFamilia").click(function(e)
	{
		$('#dialogo-familias').dialog('open');
	});
	
	$("#dialogo-familias").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:350,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Guardar': function() 
			{
				
				var Mensage="";
				
				var URL=base_url+"produccion/agregarCategoria";
				//*** validar campos *****	 					 
				if($("#familia").val()=="")
				{
					Mensage+="<p>Error en el nombre de la familia</p>"
				}
				
				if($("#utilidad").val()=="")
				{
					Mensage+="<p>El porcentaje de utilidad es incorrecto</p>"
				}
				
				if(isNaN($("#utilidad").val()))
				{
					Mensage+="<p>El porcentaje de utilidad es incorrecto</p>"
				}
				
				if($("#utilidadB").val()=="")
				{
					Mensage+="<p>El porcentaje de utilidad B es incorrecto</p>"
				}
				
				if(isNaN($("#utilidadB").val()))
				{
					Mensage+="<p>El porcentaje de utilidad B es incorrecto</p>"
				}
				
				if($("#utilidadC").val()=="")
				{
					Mensage+="<p>El porcentaje de utilidad C es incorrecto</p>"
				}
				
				if(isNaN($("#utilidadC").val()))
				{
					Mensage+="<p>El porcentaje de utilidad C es incorrecto</p>"
				}
				
				if($("#piezitas").val()=="")
				{
					Mensage+="<p>Numero de piezas invalido</p>"
				}
				
				if(isNaN($("#piezitas").val()))
				{
					Mensage+="<p>Numero de piezas invalido</p>"
				}
				
				if(parseFloat($("#piezitas").val())<1)
				{
					Mensage+="<p>El Numero de piezas debe ser mayor a cero</p>"
				}
				
				if(Mensage.length==0)
				{
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto){$('#cargandoFamilias').html('<img src="'+ img_loader +'"/> Espere...');},
						type:"POST",
						url:URL,
						data:{"nombre":$("#familia").val(),"utilidad":$("#utilidad").val(),
						"utilidadB":$("#utilidadB").val(),"piezas":$("#piezitas").val()},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								$("#ErrorFamilias").fadeIn();
								$("#ErrorFamilias").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
								break;
								case "1":
								window.location.href=base_url+"produccion/index/0";
								break;
							}//switch
						},
						error:function(datos)
						{
							$("#ErrorFamilias").fadeIn();
							$("#ErrorFamilias").html(datos);	
						}
					});//Ajax						  	  
				}//
				else
				{
					$("#ErrorFamilias").fadeIn();
					$("#ErrorFamilias").html(Mensage);
				}				 				 
				//*** validar campos *****	       
			},
			Cancelar: function() 
			{
				$("#ErrorFamilias").fadeOut(); 
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#ErrorFamilias").fadeOut();
		}
	});
	//*********************** Terminar ***********************
});


function editarFamilia(idFamilia)
{
	$('#cargaFamilia').load(base_url+'produccion/obtenerFamilia/'+idFamilia);
	
	$(document).ready(function()
	{
		$('#dialogo-EditarFamilia').dialog('open');
		
		$("#dialogo-EditarFamilia").dialog(
		{
			autoOpen:false,
			height:320,
			width:600,
			modal:true,
			resizable:false,
			buttons: {
			'Aceptar': function() {
			
			
			
			var Mensage="";
			
			var URL=base_url+"produccion/editarFamilia";
			//*** validar campos *****	 					 
			if($("#familia1").val()=="")
			{
			Mensage+="<p>Error en el nombre de la familia</p>"
			}
			
			if($("#utilizame").val()=="")
			{
			Mensage+="<p>El porcentaje de utilidad es incorrecto</p>"
			}
			
			if(isNaN($("#utilizame").val()))
			{
			Mensage+="<p>El porcentaje de utilidad es incorrecto</p>"
			}
			
			if($("#utilizameB").val()=="")
			{
				Mensage+="<p>El porcentaje de utilidad B es incorrecto</p>"
			}
			
			if(isNaN($("#utilizameB").val()))
			{
				Mensage+="<p>La utilidad B es incorrecta</p>"
			}
			
			if($("#utilizameC").val()=="")
			{
				Mensage+="<p>La utilidad C es incorrecta</p>"
			}
			
			if(isNaN($("#utilizameC").val()))
			{
				Mensage+="<p>La utilidad C es incorrecta</p>"
			}
			
			if($("#piezitas1").val()=="")
			{
				Mensage+="<p>Numero de piezas invalido</p>"
			}
			
			if(isNaN($("#piezitas1").val()))
			{
				Mensage+="<p>Numero de piezas invalido</p>"
			}

			if(Mensage.length==0){
			// Guardar los datos con Ajax
			
			$.ajax({
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargandoEditarFamilia').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:URL,
			data:
			{
				"nombre":$("#familia1").val(),
				"utilidad":$("#utilizame").val(),
				"utilidadB":$("#utilizameB").val(),
				"utilidadC":$("#utilizameC").val(),
				"piezas":$("#piezitas1").val(),
				"idFamilia":idFamilia
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				switch(data)
				{
					case "0":
					$("#errorEditarFamilia").fadeIn();
					$("#errorEditarFamilia").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
					break;
					
					case "1":
					window.location.href=base_url+"produccion/index/"+$("#pagina").val();
					break;
					
				}//switch
			},
			error:function(datos)
			{
				$("#errorEditarFamilia").fadeIn();
				$("#errorEditarFamilia").html(datos);	
			}
			});//Ajax						  	  
			
			}//
			else
			{
				$("#errorEditarFamilia").fadeIn();
				$("#errorEditarFamilia").html(Mensage);
			}				 				 
			//*** validar campos *****	       
			},
			Cancelar: function() 
			{
				$("#errorEditarFamilia").fadeOut(); 
				$(this).dialog('close');				 
			}
			},
			close: function() 
			{
				$("#errorEditarFamilia").fadeOut();
			}
		});
	});
}

/*Cancelación de facturas*/

function obtenerFolio(idFactura)
{
	var URL=base_url+"facturacion/motivosCancelacionFactura";
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoCancelacion').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:URL,
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarFolio").html(data);
			$('#cargandoCancelacion').html("");
		},
		error:function(datos)
		{
			$("#ErrorFamilias").fadeIn();
			$("#ErrorFamilias").html(datos);	
		}
	});//Ajax	
}

$(document).ready(function()
{
	for(i=0;i<30;i++)
	{	
		$("#cancelarFactura"+i).click(function(e)
		{
			$('#ventanaCancelarFactura').dialog('open');
		});
	}
	
	$("#ventanaCancelarFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				var URL=base_url+"facturacion/cancelarCFDI";
				
				if($("#motivosCancelacion").val()=="")
				{
					notify('Por favor describa cuales son los motivos de cancelación',500,5000,'error',30,3);
					return;
				}
				
				if(confirm('¿Realmente desea cancelar el CFDI?')==false)
				{
					return;
				}
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto)
					{
						$('#cargandoCancelacion').html('<img src="'+ img_loader +'"/> Se esta cancelando el CFDI, por favor espere...');
					},
					type:"POST",
					url:URL,
					data:
					{
						"idFactura":$("#txtIdFactura").val(),
						"motivos":	$("#motivosCancelacion").val(),
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							$('#cargandoCancelacion').html("");
							notify('Error al cancelar el CFDI, por favor verifique que el CFDI no haya sido previamente cancelado',500,5000,'error',30,3);
							break;
							case "1":
							window.location.href=base_url+"facturacion/facturasCliente/"+$('#clienteFactura').val();
							break;
						}
					},
					error:function(datos)
					{
						$('#cargandoCancelacion').html("");
						notify('Error al cancelar el CFDI, por favor verifique que el CFDI no haya sido previamente cancelado',500,5000,'error',30,3);
					}
				});//Ajax						  	  
			},
			Cancelar: function() 
			{
				$('#cargandoCancelacion').html("");
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			$("#ErrorCancelacion").fadeOut();
		}
	});
});

function listaRemisiones(idFactura)
{
	//base_url="localhost:81/sanvalentin/";

	var URL=base_url+"ventas/remisionesFactura";
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoRemisiones').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"idFactura":idFactura
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarRemisiones").html(data);
			$('#cargandoRemisiones').html("");
		},
		error:function(datos)
		{
			$("#ErrorRemisiones").fadeIn();
			$("#ErrorRemisiones").html(datos);	
		}
	});//Ajax	
}

function obtenerRemisionesFactura(idFactura)
{
	listaRemisiones(idFactura);
	
	$('#remisionesFactura').dialog('open');
	
	$("#remisionesFactura").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:250,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$("#ErrorRemisiones").fadeOut(); 
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
			$("#ErrorRemisiones").fadeOut();
		}
	});
}

//===================================================================================================================//
//===============================================   NÓTA DE CREDITO  ================================================//
//===================================================================================================================//

function obtenerDetallesNota(idCotizacion)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarNotaCredito').html('<img src="'+ img_loader +'"/>Obteniendo los datos necesarios para realizar la Nota de Crédito...');
		},
		type:"POST",
		url:base_url+'facturacion/obtenerDetallesNota',
		data:
		{
			"idCotizacion":idCotizacion,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarNotaCredito').html(data);
		},
		error:function(datos)
		{
			$('#cargarNotaCredito').html('Error al obtener los detalles para realizar la Nota de Crédito');
		}
	});		
}

$(document).ready(function()
{
	$("#notaCredito").click(function(e)
	{
		$('#ventanaNotaCredito').dialog('open');
	});
	
	$("#ventanaNotaCredito").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:450,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Guardar': function() 
			{
				var mensaje="";
				var URL=base_url+"facturacion/crearCFDI";
				
				if($('#txtFormaPago1').val()=="")
				{
					mensaje+="Especifique la forma de pago <br />";
				}
				
				if($('#txtMetodoPago1').val()=="")
				{
					mensaje+="Especifique el metodo de pago <br />";
				}
				
				if($('#txtCondiciones1').val()=="")
				{
					mensaje+="Especifique las condiciones de pago <br />";
				}
				
				if(mensaje.length>0)
				{
					notify(mensaje,500,5000,'error',0,5);
					return;
				}
					
				if(confirm('¿Realmente desea realizar la Nóta de Crédito?')==false)
				{
					return;
				}
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto)
					{
						$('#cargandoNotaCredito').html('<img src="'+ img_loader +'"/> Se esta generando la Nota de Crédito, por favor espere...');
					},
					type:"POST",
					url:URL,
					data:
					{
						"idCotizacion":$('#txtIdCotizacion').val(),
						"documento":'NOTA DE CRÉDITO',
						"tipoComprobante":'egreso',
						"formaPago":$('#txtFormaPago1').val(),
						"metodoPago":$('#txtMetodoPago1').val(),
						"condiciones":$('#txtCondiciones1').val(),
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						switch(data)
						{
							case "0":
							notify('Error al generar la factura',500,5000,'error',30,5);
							$('#cargandoNotaCredito').html('')
							break;
							
							case "1":
							alert('Factura creada correctamente');
							window.location.href=base_url+"clientes/ventas/";
							break;
							
							case "2":
							notify('El cliente seleccionado no tiene los datos fiscales necesarios para crear la factura',500,5000,'error',30,5);
							$('#cargandoNotaCredito').html('')
							return;
							break;
							
							case "3":
							//alert('El cliente seleccionado no tiene los datos necesarios para crear la factura');
							notify('Error al conectarse al servidor de timbrado, verifique por favor su usuario y contraseña',500,5000,'error',30,5);
							$('#cargandoNotaCredito').html('')
							break;
							
							case "4":
								notify('Los folios se han terminado, por favor compre mas folios',500,5000,'error',30,5);
								$('#facturando').fadeOut()
								break;
								
							default:
							//alert('Factura creada correctamente');
							window.location.href=base_url+"clientes/ventas/";
							break;
						}//switch
						
						window.location.href=base_url+"clientes/ventas/";
					},
					error:function(datos)
					{
						//window.location.href=base_url+"clientes/ventas/";
						//$("#ErrorContactoAdd").fadeIn();
						//$("#ErrorContactoAdd").html(datos);	
					}
				});					  	  
			},
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function() 
		{
			
		}
	});
});
	
/********************************FACTURACION**********************************************/

	ban=0;
	
	function calcularTotal()
	{
		indice=$('#indice').val();
		ban=0;
		total=0;
		subtotal=0;
		
		var ventas=new Array();

		for(i=1;i<indice;i++)
		{
			try
			{
				if(document.getElementById('activar'+i).checked)
				{
					total+=parseFloat($('#total'+i).val());
					subtotal+=parseFloat($('#subtotal'+i).val());
					
					ventas[i-1]=$('#activar'+i).val();
	
					ban=1;
				}
			}
			catch(error)
			{
				total+=0;
				subtotal+=0;
				
				ban=1;
			}
		}
	
		iva=subtotal*0.16;
		
		total=Math.round(total*100)/100;
		subtotal=Math.round(subtotal*100)/100;
		iva=Math.round(iva*100)/100;
		total=subtotal+iva;
		
		$('#totalFactura').html(number_format(total,2,"",""))

		$('#total').html('$ '+number_format(total,2,'',''))
		$('#subtotal').html('$ '+number_format(subtotal,2,'',''))
		$('#iva').html('$ '+number_format(iva,2,'',''))
		
		$('#totalFactura').val(total)
		$('#subtotalFactura').val(subtotal)
		$('#ivaFactura').val(iva)
	}
	
	
	
	cotizacionFactura=0;
	
	function datosFactura(cliente,idCotizacion,subTotal,iva,descuento,total,totalDescuento,totalIVA)
	{
		cotizacionFactura=idCotizacion;
		
		$("#cliente").html(cliente);
		$("#descuento").html('$'+totalDescuento);
		$("#iva").html('$'+totalIVA);
		$("#total").html('$'+total);
		$("#subtotal").html('$'+subTotal);
		$("#ivaPorcentaje").html('IVA '+iva +'%');
		$("#descuentoPorcentaje").html('Descuento '+descuento+'%');
	}

	$(document).ready(function()
	{
		$("#gastosAdministrativos").click(function(e)
		{
   			$('#ventanaGastos').dialog('open');
		});
		
		$("#ventanaGastos").dialog(
		{
			autoOpen:false,
			height:300,
			width:600,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Guardar': function() 
				{
					var mensaje="";
					var URL=base_url+"produccion/agregarGasto";
					
					if($('#concepto').val()=="")
					{
						mensaje+='El concepto no es valido <br />';
					}
					
					if($('#costo').val()=="" || isNaN($('#costo').val()) || parseFloat($('#costo').val())<0 || $('#costo').val()=="0")
					{
						mensaje+='El costo no es valido <br />';
					}
					
					if($('#selectCuentaContable').val()=="0")
					{
						mensaje+='Seleccione la cuenta contable <br />';
					}
					
					if($('#TipoPago').val()=="2")
					{
						if($('#numeroCheque').val()=="")
						{
							mensaje+='Numero de cheque invalido <br />';
						}
					}
				
					if($('#TipoPago').val()=="3")
					{
						if($('#numeroTransferencia').val()=="")
						{
							mensaje+='Numero de transferencia es invalido <br />';
						}
					}
				
					if($('#cuentasBanco').val()=="0")
					{
						mensaje+='Seleccione un banco y una cuenta <br />';
					}
							
					if(mensaje.length>0)
					{
						notify(mensaje,500,5000,'error',30,5);
						return;
					}
					
					if(confirm('¿Realmente desea registrar el gasto?')==false)
					{
						return;
					}
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#cargandoGastos').html('<img src="'+ img_loader +'"/>Se esta registrando el gasto administrativo, por favor espere...');
						},
						type:"POST",
						url:URL,
						data:
						{
							"nombre":$("#concepto").val(),
							"costo":$('#costo').val(),
							"idCuentaContable":$('#selectCuentaContable').val(),
							"cuentasBanco":$('#cuentasBanco').val(),
							"numeroCheque":$('#numeroCheque').val(),
							"numeroTransferencia":$('#numeroTransferencia').val(),
							"formaPago":$('#TipoPago').val(),
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								notify('Error al registrar el gasto administrativo',500,5000,'error',30,5);
								$('#cargandoGastos').html('');
								break;
								
								case "1":
								window.location.href=base_url+
								"produccion/gastos/"+$('#paginaActiva').val()+'/'+$('#anio').val();
								break;
								
							}//switch
						},
						error:function(datos)
						{
							notify('Error al registrar el gasto administrativo',500,5000,'error',30,5);
							$('#cargandoGastos').html('');	
						}
					});					  	  
				},
				Cancelar: function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#cargandoGastos").html('');
			}
		});
	});
	
	$(document).ready(function()
	{
		$("#agregarLicencia").click(function(e)
		{
   			$('#ventanaLicencias').dialog('open');
		});
		
		$("#ventanaLicencias").dialog(
		{
			autoOpen:false,
			height:250,
			width:600,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Guardar': function() 
				{
					
					var Mensage="";
					
					
					var URL=base_url+"principal/agregarLicencia";
					
					if($('#empresa').val()=="")
					{
						alert('El nombre de la empresa es incorrecto');
						return;
					}
					
					if($('#FechaDia').val()=="")
					{
						alert('La fecha de inicio es invalida');
						return;
					}
					
					if($('#FechaDia2').val()=="")
					{
						alert('La fecha final es invalida');
						return;
					}
					
					if($('#FechaDia2').val()<$('#FechaDia').val())
					{
						alert('Existe un error en las fechas');
						return;
					}
					
					if(confirm('¿Realmente desea registrar la licencia?')==false)
					{
						return;
					}
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#cargandoLicencias').html('<img src="'+ img_loader +'"/> Espere...');
						},
						type:"POST",
						url:URL,
						data:
						{
							"empresa":$("#empresa").val(),
							"fechaInicio":$('#FechaDia').val(),
							"fechaFin":$('#FechaDia2').val()
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								alert('Error al registrar el gasto');
								$('#cargandoLicencias').fadeOut()
								break;
								
								case "1":
								window.location.href=base_url+"principal/admin/";
								break;
								
							}//switch
						},
						error:function(datos)
						{
							//$("#ErrorContactoAdd").fadeIn();
							//$("#ErrorContactoAdd").html(datos);	
						}
					});					  	  
				},
				'Cancelar': function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#cargandoLicencias").html('Error');
			}
		});
	});
	
	
	
	
	
	function obtenerProveedores()
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#listaProveedores').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:base_url+'proveedores/obtenerProveedores',
			data:
			{
				//"idCliente":idCliente
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#listaProveedores').html(data);
				
				setTimeout('listaProductosServicios()',1000);
			},
			error:function(datos)
			{
				$('#listaProveedores').html('Error al obtener la lista de proveedores');
			}
		});		
	}
	
 	
	function comprobarArchivo()
	{
		cadena=	$('#userfile').val();
		b=0;
		extension="";
		for(i=0;i<cadena.length;i++)
		{
			if(b==1)
			{
				extension+=cadena[i];
			}
	
			if(cadena[i]==".")
			{
				b=1;
			}
		}
		
		if(extension!='png' && extension!='jpg' && extension!='gif' && extension!='bmp')
		{
			alert('Solo se permiten archivos de imagen');
			$('#userfile').val('');
		}
	}
	
	function comprobarArchivoEditar()
	{
		cadena=	$('#userfile1').val();
		b=0;
		extension="";
		for(i=0;i<cadena.length;i++)
		{
			if(b==1)
			{
				extension+=cadena[i];
			}
	
			if(cadena[i]==".")
			{
				b=1;
			}
		}
		
		if(extension!='png' && extension!='PNG' && extension!='jpg' && extension!='gif' && extension!='bmp')
		{
			alert('Solo se permiten archivos de imagen');
			$('#userfile1').val('');
		}
	}
	
	
	
	
	
	//==================================================================================================//
	//===================================       SEGUIMIENTOS        ====================================//
	//==================================================================================================//
	
	$(document).ready(function()
	{
		$("#agregarSeguimiento").click(function(e)
		{
			$('#ventanaSeguimiento').dialog('open');
		});
		
		$("#ventanaSeguimiento").dialog(
		{
			autoOpen:false,
			height:300,
			width:700,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Guardar': function() 
				{
					
					var mensaje="";
					
					
					var URL=base_url+"clientes/agregarSeguimiento";
					
					if($('#txtComentarios').val()=="")
					{
						mensaje+='Los comentarios son requeridos \n';
						
					}
					
					if($('#FechaDia').val()=="")
					{
						mensaje+='Debe seleccionar una fecha \n';
						
					}
					
					if(mensaje.length>0)
					{
						alert(mensaje);
						return;
					}
					
					if(confirm('¿Realmente desea agregar el seguimiento?')==false)
					{
						return;
					}
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#cargandoSeguimiento').html('<img src="'+ img_loader +'"/> \
							Se esta registrando un seguimiento...');
						},
						type:"POST",
						url:URL,
						data:
						{
							"comentarios":	$("#txtComentarios").val(),
							"fecha":		$('#FechaDia').val(),
							"idCliente":	$('#id_cli').val(),
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								$('#cargandoSeguimiento').fadeOut()
								$('#errorSeguimiento').html('Error  al agregar el seguimiento')
								break;
								
								case "1":
								window.location.href=base_url+"clientes/seguimiento/"+$('#id_cli').val();
								break;
								
							}//switch
						},
						error:function(datos)
						{
							$('#cargandoSeguimiento').fadeOut()
							$('#errorSeguimiento').html('Error al agregar el seguimiento')
						}
					});					  	  
				},
				Cancelar: function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#errorSeguimiento").html('');
			}
		});
	});
	
	//**********************CONFIRMAR EL SEGUIMIENTO***********************//
	iSeguimiento=0;
	
	function confirmarSeguimiento(i)
	{
		iSeguimiento=i;		
	}
	
	$(document).ready(function()
	{
		for(i=1;i<25;i++)
		{
			$("#chkSeguimiento"+i).click(function(e)
			{
				$('#ventanaConfirmar').dialog('open');
			});
		}
		
		$("#ventanaConfirmar").dialog(
		{
			autoOpen:false,
			height:200,
			width:600,
			modal:true,
			resizable:false,
			
			buttons: 
			{
				'Aceptar': function() 
				{
					if(confirm('¿Realmente desea confirmar el seguimiento?')==false)
					{
						//document.getElementById('chkSeguimiento'+iSeguimiento).checked=false;
						return;
					}
					
					$('#cargandoConfirmacion').fadeIn();
					
					$.ajax(
					{
						async:true,
						beforeSend:function(objeto)
						{
							$('#cargandoConfirmacion').html('<img src="'+ img_loader +'"/> \
							Confirmando...');
						},
						type:"POST",
						url:base_url+'clientes/confirmarSeguimiento',
						data:
						{
							"idSeguimiento":	$("#idSeguimiento"+iSeguimiento).val(),
							"observaciones":	$("#txtObservaciones").val(),
						},
						datatype:"html",
						success:function(data, textStatus)
						{
							switch(data)
							{
								case "0":
								$('#cargandoConfirmacion').fadeOut();
								$('#errorConfirmacion').html('Error al confirmar el seguimiendo')
								break;
								
								case "1":
								window.location.href=base_url+"clientes/seguimiento/"+$('#id_cli').val();
								break;
								
							}//switch
						},
						error:function(datos)
						{
							$("#errorConfirmacion").html('');
							$('#errorConfirmacion').html('Error al confirmar el seguimiendo')
						}
					});					 
				}
			},
			close: function()
			{
				document.getElementById('chkSeguimiento'+iSeguimiento).checked=false;
				$("#errorConfirmacion").html('');
			}
		});
	})
	
	//**********************DETALLES DEL SEGUIMIENTO***********************//
	
	function detallesSeguimiento(idSeguimiento)
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargarSeguimiento').html('<img src="'+ img_loader +'"/> \
				Cargando los detalles del seguimiento...');
			},
			type:"POST",
			url:base_url+'clientes/obtenerSeguimiento/',
			data:
			{
				"idSeguimiento":idSeguimiento,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#cargarSeguimiento').html(data)
			},
			error:function(datos)
			{
				$('#cargarSeguimiento').html('Error al obtener los detalles del seguimiento')
			}
		});		
	}
	
	$(document).ready(function()
	{
		for(i=1;i<100;i++)
		{
			$("#lblSeguimiento"+i).click(function(e)
			{
				$('#ventanaInformacionSeguimiento').dialog('open');
			});
		}
		
		$("#ventanaInformacionSeguimiento").dialog(
		{
			autoOpen:false,
			height:300,
			width:800,
			modal:true,
			resizable:false,
			
			buttons: 
			{
				'Aceptar': function() 
				{
					$(this).dialog('close');				 
				}
			},
			close: function()
			{
				$("#cargarSeguimiento").html('');
			}
		});
	})
	


	
//========================================================================================================================//
	
idCompraEnviar=0;

function compraEnviar(idCompra)
{
	idCompraEnviar=idCompra;
}

function obtenerCompraEntrega(idBodega,idCompras)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargandoCompras').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+'bodegas/obtenerCompraEntrega',
		data:
		{
			"idBodega":idBodega,
			"idCompras":idCompras
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargandoCompras").html(data);
		},
		error:function(datos)
		{
			$("#cargandoCompras").html("<p>Error al obtener los productos.</p>");
		}
	});//Ajax	
}

function obtenerRecibidos(idDetalle,idProducto,cantidad)
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarRecibidos').html('<img src="'+ img_loader +'"/> Espere...');},
		type:"POST",
		url:base_url+'bodegas/obtenerRecibidos',
		data:
		{
			"idDetalle":idDetalle,
			"idProducto":idProducto,
			"cantidad":cantidad,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarRecibidos").html(data);
		},
		error:function(datos)
		{
			$("#cargarRecibidos").html("<p>Error al obtener las cantidades recibidas.</p>");
		}
	});
}

$(document).ready(function()
{
	for(i=1;i<100;i++)
	{
		$("#recibir"+i).click(function(e)
		{
			$('#ventanaRecibir').dialog('open');
		});
	}
	
	$("#ventanaRecibir").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:800,
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
	
	$("#ventanaRecibiendo").dialog(
	{
		autoOpen:false,
		height:300,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				mensaje="";
				
				if($('#txtCantidad').val()=="")
				{
					mensaje+="La cantidad es incorrecta \n";
				}
				
				if(isNaN($('#txtCantidad').val())) 
				{
					mensaje+="La cantidad es incorrecta \n";
				}
				
				if(parseInt($('#txtCantidad').val())==0) 
				{
					mensaje+="La cantidad es incorrecta \n";
				}
				
				
				if(parseInt($('#cantidadTotal').val())<(parseInt($('#txtTotal').val())+parseInt($('#txtCantidad').val())))  
				{
					mensaje+="La cantidad a recibir no debe ser mayor a la solicitada \n";
				}
				
				if($('#txtAutorizo').val()=="")
				{
					mensaje+="Por favor escriba quien autorizo \n";
				}
				
				if(mensaje.length>0)
				{
					alert(mensaje)
					return;
				}
				
				//return;
				
				$.ajax(
				{
					async:true,
					beforeSend:function(objeto){$('#cargandoRecibidos').html('<img src="'+ img_loader +'"/> Espere...');},
					type:"POST",
					url:base_url+'bodegas/recibirMercancia',
					data:
					{
						"idDetalle":$('#txtIdDetalle').val(),
						"autorizo":$('#txtAutorizo').val(),
						"cantidad":$('#txtCantidad').val(),
						"idProducto":$('#txtIdProducto').val(),
						"idBodega":$('#txtIdBodega').val(),
					},
					datatype:"html",
					success:function(data, textStatus)
					{
						$('#cargandoRecibidos').html('');
						obtenerRecibidos($('#txtIdDetalle').val());
					},
					error:function(datos)
					{
						$("#cargandoRecibidos").html("<p>Error al obtener las cantidades recibidas.</p>");
					}
				});
			},
		},
		close: function() 
		{
			$("#ErrorRecibiendo").fadeOut();
		}
	});
});


	
	//==============================================================================================//
	//==========================================DEVOLUCIONES =======================================//
	//==============================================================================================//
	
	function obtenerDevolucionesVenta(idCotizacion)
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#cargarDevolucionesProductos').html('<img src="'+ img_loader +'"/> Obteniendo las devoluciones, por favor espere ...');},
			type:"POST",
			url:base_url+'facturacion/obtenerDevolucionesVenta',
			data:
			{
				"idCotizacion":idCotizacion,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#cargarDevolucionesProductos").html(data);
			},
			error:function(datos)
			{
				$("#cargarDevolucionesProductos").html('Error al obtener el formulario de los clientes');
			}
		});				  	  
	}
	
	function devolverProductoVenta(i,idProducto)
	{
		inventario="1";
		
		if($("#txtCantidadDevolver"+i).val()=="" || isNaN($("#txtCantidadDevolver"+i).val()) || parseFloat($("#txtCantidadDevolver"+i).val())<1 || parseFloat($("#txtCantidadDevolver"+i).val()) > parseFloat($("#txtCantidadTotal"+i).val()))
		{
			notify('La cantidad a devolver es incorrecta',500,5000,'error',0,0);
			document.getElementById('chkDevoluciones'+i).checked=false;
			return;
		}
		
		if(confirm('¿Realmente desea realizar la devolución del producto')==false)
		{
			document.getElementById('chkDevoluciones'+i).checked=false;
			return;
		}
		
		if(confirm('¿Desea que las unidades devueltas entren al inventario?')==false)
		{
			inventario="0";
		}

		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#cargandoDevolucionesProductos').html('<img src="'+ img_loader +'"/>Se esta registrando la devolución, por favor espere ...');},
			type:"POST",
			url:base_url+'facturacion/devolverProductosVenta',
			data:
			{
				"idProducto":idProducto,
				"cantidad":$("#txtCantidadDevolver"+i).val(),
				"inventario":inventario,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				switch(data)
				{
					case "0":
					notify('Error al realizar la devolución',500,5000,'error',5,5);
					document.getElementById('chkDevoluciones'+i).checked=false;
					break;
					case "1":
					notify('La devolución se registro correctamente',500,5000,'',5,5);
					obtenerDevolucionesVenta($("#txtIdCotizacion").val());
					break;
				}
				$("#cargandoDevolucionesProductos").html('');
			},
			error:function(datos)
			{
				notify('Error al realizar la devolución',500,5000,'error',5,5);
				$("#cargandoDevolucionesProductos").html('');
				document.getElementById('chkDevoluciones'+i).checked=false;
			}
		});				  	  
	}
	
	$(document).ready(function()
	{
		for(i=1;i<30;i++)
		{
			$("#devolucionesVentas"+i).click(function(e)
			{
				$('#ventanaDevolucionesVentas').dialog('open');
			});
		}
		
		$("#ventanaDevolucionesVentas").dialog(
		{
			autoOpen:false,
			height:450,
			width:800,
			modal:true,
			resizable:false,
			buttons: 
			{
				'Cerrar': function() 
				{
					$(this).dialog('close');	
				},
			},
			close: function() 
			{
				$("#cargandoEditarProveedores").html('');
			}
		});
	});
	
	//==============================================================================================//
	//==========================================REPOSICIONES =======================================//
	//==============================================================================================//
	
	function reposicionesVenta(idProducto)
	{
		if(confirm('¿Realmente desea realizar la reposición del producto')==false)
		{
			return;
		}
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#cargandoDevolucionesProductos').html('<img src="'+ img_loader +'"/>Se esta realizando la reposición, por favor espere');},
			type:"POST",
			url:base_url+'facturacion/reposicionesVenta',
			data:
			{
				"idProducto":idProducto,
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$('#cargandoDevolucionesProductos').html('');
				notify('La reposición se registro correctamente',500,5000,'',5,5);
				obtenerDevolucionesVenta($("#txtIdCotizacion").val());
			},
			error:function(datos)
			{
				$("#cargandoDevolucionesProductos").html('Error al obtener el formulario de los clientes');
			}
		});				  	  
	}
	
	

//=======================================================================================================//
	//==============================AGREGAR UN NUEVO PROVEEDOR MATERIAS======================================//
	//=======================================================================================================//
	
	
	
	//OBTENER LOS PRODUCTOS QUE SE VAN A ENTREGAR
	function obtenerProductosAlmacen(idCotizacion,idProducto)
	{
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto){$('#entregaProductos').html('<img src="'+ img_loader +'"/> Obteniendo productos para entregas...');},
			type:"POST",
			url:base_url+"ventas/obtenerProductosAlmacen/",
			data:
			{
				idCotizacion: idCotizacion,
				idProducto: idProducto
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
	
	material=0;
	
//===========================================================================================================
//PRODUCTOS Y SERVICIOS

	todas=0;
	errorOrden=0;
	
	function producirOrdenes()
	{
		if(confirm('El sistema registrara todas las ordenes de producción, por favor espere y espere que el proceso termine, si existe algun error se lo notificara')==false)
		{
			return;
		}
		
		todas=1;
		tiempo=1500;
		
		$('#producirOrdenes').fadeOut();
		$('#contenedorProcesos').fadeOut();
		
		numeroOrdenes=parseInt($('#txtNumeroOrdenes').val());
		
		for(i=1;i<numeroOrdenes;i++)
		{
			if($('#txtProducido'+i).val()=="0")
			{
				$('#filaOrden'+i).html('En espera..');
			}
		}
		
		for(i=1;i<numeroOrdenes;i++)
		{
			if($('#txtProducido'+i).val()=="0")
			{
				//$('#filaOrden'+i).html('<img src="'+ img_loader +'"/> Registrando orden...');
				
				window.setTimeout('agregarOrdenProduccion('+i+')',tiempo);
				tiempo+=1500;
			}
		}
		
		/*if(errorOrden==0)
		{
			notify('Todas las ordenes de producción se han registrado correctamente',500,4000,"");
		}
		
		if(errorOrden==1)
		{
			notify('El registro de las ordenes de producción termino con errores',500,4000,"error");
		}*/
	}

function obtenerFormularioProcesos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargarProcesosProduccion').html('<img src="'+ img_loader +'"/> Obteniendo la lista de procesos, por favor espere...');
		},
		type:"POST",
		url:base_url+'clientes/procesosProduccion',
		data:
		{
			//"idVenta":idVenta,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargarProcesosProduccion').html(data);
		},
		error:function(datos)
		{
			$('#cargarProcesosProduccion').html('Error al obtener el formulario para realizar la orden de producción');
		}
	});
}


$(document).ready(function()
{
	/*$("#btnProcesos").click(function(e)
	{
		$('#ventanaProcesosCotizacion').dialog('open');
	});*/
		
	$("#ventanaProcesosCotizacion").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:300,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Cerrar': function() 
			{
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
			//$("#cargandoEditarProveedores").html('');
		}
	});
});
	
 function agregarOrdenProduccion(n)
 {
	 var URL=base_url+"ordenes/agregarOrden";
	 
	 $('#generandoOrdenProduccion').fadeIn();
	 
	 p=0;
	procesos= new Array();
	
	indice=parseInt($("#txtIndiceProcesos").val());
	
	for(i=1;i<indice;i++)
	{
		if(document.getElementById('chkProceso'+i).checked==true)
		{
			procesos[p]=$('#chkProceso'+i).val();
			p++;
		}
	}
	
	 $.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoOrdenProduccion').html('<img src="'+ img_loader +'"/> Espere por favor...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"idCotizacion":$("#txtIdCotizacion").val(), //Es el id del producto (Tabla productos)
			"idProducto":$("#idProducto"+n).val(), //Es el id del producto (Tabla productos)
			"cantidad":$("#cantidad"+n).val(),
			"idProductoCotizado":$("#idProductoCotizado"+n).val(), //Es el id del producto cotizacdo(Tabla cotiza_productos)
			"procesos":procesos
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar la orden de producción, por favor verifique su conexión a internet',500,4000,"error");				
				$('#generandoOrdenProduccion').fadeOut();
				break;
				
				case "1":
				notify('Registro correcto de la orden de producción del producto: '+$('#txtNombreProductoOrden'+n).val(),500,4000,"");
				img='<img src="'+base_url+'img/success.png" width="16"/>';
				$('#filaOrden'+n).html(img);
				$('#txtProducido'+n).val("1");
				$('#generandoOrdenProduccion').fadeOut();
				break; 
				
				case "2":
				notify('No existe suficiente material para realizar la orden de producción del producto: ' +$('#txtNombreProductoOrden'+n).val(),500,4000,"error");
				$('#generandoOrdenProduccion').fadeOut();
				$('#filaOrden'+n).html('Sin material');
				break;
				
				case "3":
				notify('No existe el producto en el catálogo para producirlo, por favor verifique que no se haya borrado anteriormente',500,4000,"error"); //Sistema de notificaciones
				$('#generandoOrdenProduccion').fadeOut();
				break;
			}//switch
		},
		error:function(datos)
		{
			notify('Error al registrar la orden de producción, por favor verifique su conexión a internet',500,4000,"error");
			return;
		}
	});	
 }
 
function agregarOrdenProduccionIndividual(n)
{
	var URL=base_url+"ordenes/agregarOrden";
	
	p=0;
	procesos= new Array();
	
	indice=parseInt($("#txtIndiceProcesos").val());
	
	for(i=1;i<indice;i++)
	{
		if(document.getElementById('chkProceso'+i).checked==true)
		{
			procesos[p]=$('#chkProceso'+i).val();
			p++;
		}
	}
				
	if(confirm('¿Realmente desea registrar la orden de produccion?')==false)
	{
		document.getElementById('chkProduccion'+n).checked=false;
		return;
	}
	
	$('#generandoOrdenProduccion').fadeIn();
	 
	 $.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#generandoOrdenProduccion').html('<img src="'+ img_loader +'"/> Espere por favor...');
		},
		type:"POST",
		url:URL,
		data:
		{
			"idCotizacion":$("#txtIdCotizacion").val(), //Es el id del producto (Tabla productos)
			"idProducto":$("#idProducto"+n).val(), //Es el id del producto (Tabla productos)
			"cantidad":$("#cantidad"+n).val(),
			"idProductoCotizado":$("#idProductoCotizado"+n).val(), //Es el id del producto cotizacdo(Tabla cotiza_productos)
			"procesos":procesos
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('Error al registrar la orden de producción, por favor verifique su conexión a internet',500,4000,"error");				
				document.getElementById('chkProduccion'+n).checked=false;
				$('#generandoOrdenProduccion').fadeOut();
				break;
				
				case "1":
				notify('Registro correcto de la orden de producción del producto: '+$('#txtNombreProductoOrden'+n).val(),500,4000,"");
				img='<img src="'+base_url+'img/success.png" width="16"/>';
				$('#filaOrden'+n).html(img);
				$('#txtProducido'+n).val("1");
				$('#generandoOrdenProduccion').fadeOut();
				break; 
				
				case "2":
				notify('No existe suficiente material para realizar la orden de producción del producto: ' +$('#txtNombreProductoOrden'+n).val(),500,4000,"error");
				document.getElementById('chkProduccion'+n).checked=false;
				$('#generandoOrdenProduccion').fadeOut();
				break;
				
				case "3":
				notify('No existe el producto en el catálogo para producirlo, por favor verifique que no se haya borrado anteriormente',500,4000,"error"); //Sistema de notificaciones
				document.getElementById('chkProduccion'+n).checked=false;
				$('#generandoOrdenProduccion').fadeOut();
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la orden de producción, por favor verifique su conexión a internet',500,4000,"error");
			return;
		}
	});	
 }
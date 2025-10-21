
$('.ajax-pag > li a').live('click',function(eve){
 eve.preventDefault();
 var element = "#InformacionProveedores";
 var link = $(this).attr('href');

 $.ajax({
            url:link,
           type:"POST",
           data:{"T1":"pag","serie":$("#id_serie").val(),"Idc":$("#id_cli").val(),"TBConCliente":$("#TBuscaContactoCliente").val()},
       dataType:"html",
     beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
        success:function(html,textStatus){
            setTimeout(function(){
            $(element).html(html);},300);
         },
      error:function(datos){$(element).html('Error '+ datos).show('slow');}
    });

 });//.ajax


	function PagosGlobales()
	{
		$("#InformacionProveedores").load(base_url+"compras/obtenerComprasProveedor/");
		
		$('#dialog-pagosGlobales').dialog('open');
		
		$("#dialog-pagosGlobales").dialog(
		{
			autoOpen:false,
			height:600,
			width:1000,
			modal:true,
			resizable:false,
			
			buttons: 
			{
				'Aceptar': function() 
				{
					
					
					var Mensage="";
					
					var pagos = new Array();
					var compras = new Array();
					
					var URL=base_url+"compras/pagosGlobales";
					
					indice = $('#indice').val();
					
					if(indice==1)
					{
						alert('Sin registro de compras');
						return;
					}
					
					if($('#TipoPago').val()=="2")
					{
						if($('#numeroCheque').val()=="")
						{
							alert('Numero de cheque invalido');
							return;
						}
					}
				
					if($('#TipoPago').val()=="3")
					{
						if($('#numeroTransferencia').val()=="")
						{
							alert('Numero de transferencia es invalido');
							return;
						}
					}
			
					if($('#cuentasBanco').val()=="0")
					{
						alert('Seleccione un banco y una cuenta');
						return;
					}
					
					for(i=0;i<indice-1;i++)
					{
						if (Solo_Numerico($('#pagar_'+(i+1)).val())=="")
						{
							alert('Error en el monto a pagar');
							//$('#pagar_'+(i+1)).style.focus();
							return;
						}
						
						if (Solo_Numerico($('#pagar_'+(i+1)).val())=="")
						{
							alert('Error en el monto a pagar');
							return;
						}
						
						saldo=parseFloat($('#saldo'+(i+1)).val());
						pagar=parseFloat($('#pagar_'+(i+1)).val());
						
						if(pagar>saldo)
						{
							alert('El pago es mayor a la deuda');
							return;
						}
						
						pagos[i]	=$('#pagar_'+(i+1)).val();
						compras[i]	=$('#idCompra'+(i+1)).val();
					}
					
					if(Mensage.length==0)
					{
						$.ajax(
						{
							async:true,
							beforeSend:function(objeto){$('#id_CargandoPagosProveedor').html('<img src="'+ img_loader +'"/> Espere...');},
							type:"POST",
							url:URL,
							data:
							{
								"pagos":pagos,"compras":compras,
								"cuentasBanco":$('#cuentasBanco').val(),
								"numeroCheque":$('#numeroCheque').val(),
								"numeroTransferencia":$('#numeroTransferencia').val(),
								"formaPago":$('#TipoPago').val(),"banco":$('#listaBancos').val(),
								"indice":indice
							},
							datatype:"html",
							success:function(data, textStatus)
							{
								switch(data)
								{
									case "0":
									$("#Error-pagosGlobales").fadeIn();
									$("#Error-pagosGlobales").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
									break;
									
									case "1":
									window.location.href=base_url+"compras"
									break;
								}//switch
							},
							error:function(datos)
							{
								$("#Error-pagosGlobales").fadeIn();
								$("#Error-pagosGlobales").html(datos);	
							}
						});//Ajax						  	  
					}//
					else
					{
						$("#Error-pagosGlobales").fadeIn();
						$("#Error-pagosGlobales").html(Mensage);
					}				 				 
				},
				Cancel: function() 
				{
					$("#Error-pagosGlobales").fadeOut(); 
					$(this).dialog('close');				 
				}
			},
			close: function() 
			{
				$("#Error-pagosGlobales").fadeOut();
			}
		});
	}
	
function Solo_Numerico(variable){
	//patron = /^\d+(\.\d{1,2})?$/;
	patron = /^\d+\.?\d*$/;
	
	Numer=variable;
	
    //if (isNaN(Numer)){
	if(!patron.test(Numer)){
    	return "";
    }
        return Numer;
}//function



function redondeo2decimales(numero)
{
    var original	=parseFloat(numero);
   	var result		=Math.round(original*100)/100 ;
	
	return result;
}



$(document).ready(function () 
{
	$("#actualizarPagos").click(
	
	function(e)
	{
		if($('#TipoPago').val()=="2")
		{
			if($('#numeroCheque').val()=="")
			{
				alert('Numero de cheque invalido');
				return;
			}
		}
	
		if($('#TipoPago').val()=="3")
		{
			if($('#numeroTransferencia').val()=="")
			{
				alert('Numero de transferencia es invalido');
				return;
			}
		}

		if($('#cuentasBanco').val()=="0")
		{
			alert('Seleccione un banco y una cuenta');
			return;
		}
		
		if (Solo_Numerico($('#montoPagar').val())=="")
		{
			alert('Error en el monto a pagar');
			return;
		}
		
		var pagar= parseFloat($('#montoPagar').val());
		var deuda=parseFloat($('#T3').val());
		
		if(pagar>deuda)
		{
			alert('El monto a pagar es mayor a la deuda');
			return;
		}
		
		if (Solo_Numerico($('#montoPagar').val())==0)
		{
			alert('El monto a pagar debe ser mayor a 0');
			return;
		}
		
		$.ajax(
		{
			async   : true,
			type    : "POST",
			url     : base_url+"ficha/realizarPago/",
			data	: {"idVenta":$('#idVenta').val(),"montoPagar":$('#montoPagar').val(),
			"cuentasBanco":$('#cuentasBanco').val(),
			"numeroCheque":$('#numeroCheque').val(),
			"numeroTransferencia":$('#numeroTransferencia').val(),
			"formaPago":$('#TipoPago').val(),"banco":$('#listaBancos').val() },
			datatype: "html",
			success	: function(data, textStatus)
			{
				switch(data)
				{
					case "0":
					alert('Error en el pago');
					return;
					break;
							
					case "1":
							//$('#HistoricoPagos').load(base_url+"ficha/mostrarPagos/"+$('#idVenta').val());
					alert('Pago realizado con exito');
					window.location.href=base_url+"ficha/pagos/"+$("#idCotizacion").val();
					break;
				}
			},
			error: function(datos)
			{
				// $(obj).html('Error '+ datos).show('slow'); 
			}
		});//Ajax
	});//click
});	

$(document).ready(function () 
{
	$("#pagosProveedor").click(
	
	function(e)
	{
		if($('#TipoPago').val()=="2")
		{
			if($('#numeroCheque').val()=="")
			{
				alert('Numero de cheque invalido');
				return;
			}
		}
	
		if($('#TipoPago').val()=="3")
		{
			if($('#numeroTransferencia').val()=="")
			{
				alert('Numero de transferencia es invalido');
				return;
			}
		}

		if($('#cuentasBanco').val()=="0")
		{
			alert('Seleccione un banco y una cuenta');
			return;
		}
		
		if (Solo_Numerico($('#montoPagar').val())=="")
		{
			alert('Error en el monto a pagar');
			return;
		}
		
		var pagar= parseFloat($('#montoPagar').val());
		var deuda=parseFloat($('#T3').val());
		//alert(pagar)
		
		if(pagar>deuda)
		{
			alert('El monto a pagar es mayor a la deuda');
			return;
		}
		
		if (Solo_Numerico($('#montoPagar').val())==0)
		{
			alert('El monto a pagar debe ser mayor a 0');
			return;
		}
		
		$.ajax(
		{
			async   : true,
			type    : "POST",
			url     : base_url+"compras/realizarPago/",
			data	: {"idCompras":$('#idCompras').val(),"montoPagar":$('#montoPagar').val(),
			"cuentasBanco":$('#cuentasBanco').val(),
			"numeroCheque":$('#numeroCheque').val(),
			"numeroTransferencia":$('#numeroTransferencia').val(),
			"formaPago":$('#TipoPago').val(),"banco":$('#listaBancos').val() },
			datatype: "html",
			success	: function(data, textStatus)
			{
				switch(data)
				{
					case "0":
					alert('Error en el pago');
					return;
					break;
							
					case "1":
							//$('#HistoricoPagos').load(base_url+"ficha/mostrarPagos/"+$('#idVenta').val());
					alert('Pago realizado con exito');
					window.location.href=base_url+"compras/pagos/"+$("#idCompras").val();
					break;
				}
			},
			error: function(datos)
			{
				// $(obj).html('Error '+ datos).show('slow'); 
			}
		});//Ajax
	});//click
});	

	
$(document).ready(function () {
	
	$("#SaveGuardarPagos").click(
	   function(e){
		  e.preventDefault();
		  
		  var T1=$("#T1").val();
		  var T2=$("#T2").val();		  
		  var T3=$("#T3").val();		  		  
		  var T4=$("#T4").val();		  		
		  var T5=$("#T5").val();
		  
		  var TIdc=$("#TIdc").val();

//idTpos,idTpoEfectivo,idTpoTarjeta,idTpoBanco,idTpoPlazo,idTpoCheque		  
		  var T6=$("#idTpos").val();
		  var T7=$("#idTpoEfectivo").val();
          var T8=$("#idTpoTarjeta").val();	 
          var T9=$("#idTpoCheque").val();		  		  
		  		  		 
		  var mensage="";
		  

            if(!isNaN(T1)){
				T1=parseFloat(T1);
			}else{
				mensage+="- Error en el Pago Total\n";
			}
			
		   if(!isNaN(T2)){
				T2=parseFloat(T2);
			}else{
				mensage+="- Error en el Monto pagado\n";
			}
			
            if(!isNaN(T3)){
				T3=parseFloat(T3);
			}else{
				mensage+="- Error en el Saldo deudor\n";
			}
			
		   if((!isNaN(T4))&&(T4!="")){
				T4=parseFloat(T4);
			}else{
				mensage+="- Error en el Monto a pagar\n";
			}			
		  
		 
	if(T4 > T3){
		mensage+='- Tu monto ' + T4 + ' es mayor al saldo deudor '+T3 + " Verifica !!!!\n";
		$("#T4").val('');		
	}
	
	if(T6==""){
	  mensage+="- Debe de seleccionar una forma de pago.\n";	 
	}
	
	if(mensage!=""){
	 alert("-----------------------------------------------------------------\n"+mensage+"\n-----------------------------------------------------------------");
     return;	 
	}

	
	var r=confirm("Va a realizar un pago por $ "+ T4);
	if (r!=true)
	  return;
	  
	$.ajax({
		async   : true,
		type    : "POST",
		url     : base_url+"ficha/pagosave/",
		data	: {"T5":T5,"T4":T4, "T1":T1, "T2":T2,"T3":T3,"T7":T7,"T8":T8,"T9":T9 },
		datatype: "html",
		success	: function(data, textStatus){
						val = new Array();
						val = data.split("@");
						if(val[0] == 1){
							//Message_rem('Pago actualizada correctamente.');
							alert('Pago actualizado correctamente.');
							

							$("#T2").val(val[1]);
							$("#T3").val(val[2]);
                            $("#T4").val("0");
                                                          
							$("#T2V").val(number_format(val[1],2,".",","));
							$("#T3V").val(number_format(val[2],2,".",","));
							
    $.ajax({
            async:true,
       beforeSend:function(objeto){$('#RespPagosHistorico').html('<img src="'+ img_loader +'"/> Espere...');},
	         type:"POST",
	          url:base_url+"ficha/pagoshistorico/",
	         data:{"T5":T5,"TIdc":TIdc},
         datatype:"html",
	      success:function(data, textStatus){
                    $('#RespPagosHistorico').html(data);	            
		       },
                error:function(datos){                    
                    }//Error
	        });//Ajax							
	       }else{alert('Ha sucedido un error inesperado.'+ data );}
					  },
		error: function(datos){
			// $(obj).html('Error '+ datos).show('slow'); 
			}
	});//Ajax
	 	
   });//click
	
});	

//linea de pagos

$(document).ready(function(){

 $('#FormasDePagos')
    .button()
   .click(function() {
      $('#dialog-Pagos').dialog('open');
   });


$("#dialog-Pagos").dialog({
     autoOpen:false,
       height:200,
	    width:243,
	modal:true,
    resizable:false,
      buttons: {
	 	  'Terminar': function() {

                   var Tpo=$("#idTpos").val();
                   var band=false;
//<!-- idTpos,idTpoEfectivo,idTpoTarjeta,idTpoBanco,idTpoPlazo,idTpoCheque -->
                    switch(Tpo){

                     case "0":band=false;
                              break;
                     case "1":
                              $("#FormasPagos").html("Pago: Efectivo");
			      $("#idTpoEfectivo").val("Efectivo");							  		 
                              $("#idTpoTarjeta").val("");	 
                              $("#idTpoCheque").val("");
                              band=true;
                              break;
                     case "2":	
                                switch($("#TipoTarjeta").val()){					 
                                   case "1":  
				            $("#FormasPagos").html("Pago en tarjeta:"+$("#idTpoTarjeta").val()+", Banco: "+$("#TipoBanco").val()+", Plazo: "+$("#TipoPlazo").val());
							              $("#idTpoTarjeta").val($("#idTpoTarjeta").val()+"^"+$("#TipoBanco").val()+"^"+$("#TipoPlazo").val());
										  break;
										  
                                  case "2":  
								          $("#FormasPagos").html("Pago en tarjeta:"+$("#idTpoTarjeta").val() +", Plazo: "+$("#TipoPlazo").val());
							              $("#idTpoTarjeta").val($("#idTpoTarjeta").val()+"^"+$("#TipoPlazo").val());
										  break;
                                 }//switch										  
							   band=true; 
							   
							  $("#idTpoEfectivo").val("");							  		                               
                              $("#idTpoCheque").val("");
							   
                              break;
					case "3":
                              //if(!isNaN(T1))
							  if(!isNaN($("#idNumCantidad").val())){
							  document.getElementById('ErrorTipo').style.display = "none";
							  $("#FormasPagos").html("Pago en cheque");
							  $("#idTpoCheque").val($("#idNumCantidad").val()+"^"+$("#TipoBanco").val());
							  band=true; 
							  }else{
							  document.getElementById('ErrorTipo').style.display = "block";
							  $("#ErrorTipo").html("Error en el n&uacute;mero de cheque.");
							  band=false
							  }
							  
							  $("#idTpoEfectivo").val("");							  		 
                              $("#idTpoTarjeta").val("");	                               
							  
							  break;					

                     }//switch

                   if(band==true){
                        $(this).dialog('close');
                    }//IF
                  else{
                      document.getElementById('ErrorTipo').style.display = "block";
                  }


		},
        Cancel: function() {
                         $(this).dialog('close');
                         Tpo="";
                    }//Functions
		}, //Botones
	  close: function() {
				//allFields.val('').removeClass('ui-state-error');
			}
      });


//Seleccion de Pago


//document
});


function LlamarFormaPago(){
var valor=$("#TipoPago").val();

  switch (valor){

     case "0": $("#idTpos").val(valor);//Seleccionar
               document.getElementById('idTarjeta').style.display = "none";
               document.getElementById('idBanco').style.display = "none";
			   document.getElementById('idPlazo').style.display = "none";	 
               document.getElementById('idCheque').style.display = "none";			   
               break;
     case "1": $("#idTpos").val(valor);//Efectivo
               document.getElementById('idTarjeta').style.display = "none";
               document.getElementById('idBanco').style.display = "none";
			   document.getElementById('idPlazo').style.display = "none";	 
               document.getElementById('idCheque').style.display = "none";			   
               break;
     case "2":  //Tarjeta
               document.getElementById('idTarjeta').style.display = "block";
               document.getElementById('idBanco').style.display = "none";
			   document.getElementById('idPlazo').style.display = "none";
               document.getElementById('idCheque').style.display = "none";
               $("#idTpos").val(valor);			   
              break;
			  
      case "3":  //Cheque idCheque               
			   $("#idTpos").val(valor);
			   document.getElementById('idTarjeta').style.display = "none";
               document.getElementById('idBanco').style.display = "block";
			   document.getElementById('idPlazo').style.display = "none";
               document.getElementById('idCheque').style.display = "block";			   
              break;			  

    }//Fin del switch
}

//**********  Tarjeta 
function LlamarFormaPagoTar(){
  var valor=$("#TipoTarjeta").val();

   switch(valor){
    
	case "1":             
			  document.getElementById('idBanco').style.display = "block";
			  document.getElementById('idPlazo').style.display = "block";
			  document.getElementById('idCheque').style.display = "none";
			  $("#idTpoTarjeta").val("Visa o Master Card");
			  break;
    case "2": 
	          document.getElementById('idBanco').style.display = "none";
			  document.getElementById('idPlazo').style.display = "block";
			  document.getElementById('idCheque').style.display = "none";
			  $("#idTpoTarjeta").val("American Express");
              break;	
   
   
   }//switch

   
}//Fin de llamarformapagoTar

$(document).ready(function(){


$("#llamarBanco").click(function(e){
   $('#dialogo-bancos').dialog('open');
});


$("#dialogo-bancos").dialog({
     autoOpen:false,
       height:230,
        width:500,
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
    	 //$("#ErrorContactoAdd").fadeOut();
	  }
      });
  //*********************** Terminar ***********************
 });
 
 function descuentoAdicional(idCotizacion)
 {
	if(parseFloat($('#txtDescuentoAdicional').val())<0 || isNaN($('#txtDescuentoAdicional').val()) || $('#txtDescuentoAdicional').val()=="")
	{
		notify('El descuento adicional es incorrecto',500,5000,'error',0,0);
		return;
	}
	 
	 if(confirm('¿Realmente desea agregar el descuento adicional?')==false)
	 {
		 return;
	 }
	 
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#cargandoPagosClientes').html('<img src="'+ img_loader +'"/> Se esta agregando el descuento adicional...');
		},
		type:"POST",
		url:base_url+'ficha/descuentoAdicional',
		data:
		{
			"idCotizacion":			idCotizacion,
			"descuentoAdicional":	$('#txtDescuentoAdicional').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#cargandoPagosClientes').html('')
			notify('El descuento adicional se ha agregado correctamente',500,5000,'',0,0);
			obtenerPagosClientes(idCotizacion);
		},
		error:function(datos)
		{
			$('#cargandoPagosClientes').html('Error al agregar el descuento adiocional')
		}
	});
}
 




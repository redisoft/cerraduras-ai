
//base_url="sanvalentin.redisoftsystem.com/";

$(document).ready(function()
{

$("#Id_Compras").click(function(e){

 var URL=base_url+"clientes/ListOrdenCompra";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#RESPUESTA').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	              url:URL,
               data:{"T1":"all","TB":$("#id_cotiza_bus").val(),"idc":$("#id_cli").val()},
           datatype:"html",
            success:function(data, textStatus){

		        $("#registroError").fadeOut();
                        $('#RESPUESTA').html(data);

 	               },
	         error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);
                  }
           });//Ajax

});

$("#Id_Cotizaciones").click(function(e){
  
var URL=base_url+"clientes/ListCotizaciones";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#RESPUESTA').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	              url:URL,
               data:{"T1":"all","TB":$("#id_cotiza_bus").val(),"idc":$("#id_cli").val()},
           datatype:"html",
            success:function(data, textStatus){

			            $("#registroError").fadeOut(); 
                        $('#RESPUESTA').html(data);					   
                          
 	               },
	         error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);	
                  }
           });//Ajax
});


$("#Id_Pedidos").click(function(e){
  
var URL=base_url+"clientes/Listpedidos";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#RESPUESTA').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	              url:URL,
               data:{"T1":"all","TB":$("#id_cotiza_bus").val(),"idc":$("#id_cli").val()},
           datatype:"html",
            success:function(data, textStatus){

			            $("#registroError").fadeOut(); 
                        $('#RESPUESTA').html(data);					   
                          
 	               },
	         error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);	
                  }
           });//Ajax
});



$("#Id_Buscar_Coti").click(function(e){
  
var URL=base_url+"clientes/ListCotizaciones";

       $.ajax({
              async:true,
     beforeSend:function(objeto){$('#RESPUESTA').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	              url:URL,
               data:{"T1":"all","Search":"Search","TB":$("#id_cotiza_bus").val(),"idc":$("#id_cli").val()},
           datatype:"html",
            success:function(data, textStatus){

			$("#registroError").fadeOut(); 
                        $('#RESPUESTA').html(data);					   
                          
 	               },
	         error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);	
                  }
           });//Ajax

});


$('.ajax-pag > li a').live('click',function(eve){
 eve.preventDefault();
 var element = "#RESPUESTA";
 var link = $(this).attr('href');

 $.ajax({
            url:link,
           type:"POST",
           data:{"T1":"pag","TB":$("#id_cotiza_bus").val(),"idc":$("#id_cli").val()},		   
       dataType:"html",
     beforeSend:function(){$(element).html('<img src="'+ img_loader +'"/> Espere...');},
        success:function(html,textStatus){
                         setTimeout(function(){
                            $(element).html(html);},300);
         },
      error:function(datos){$(element).html('Error '+ datos).show('slow');}
    });

 });//.ajax 
//*************** Document ********************************
});

function ConvertirOrdenCompra(Idct, folio)
{
	var URLNo		=base_url+"ficha/NOdernTrabajo";			
	var Mensaje		="";
	venta			="VEN-"+folio
	var nombre 		= prompt("Introduzca el número de orden de venta: ",venta);
	
	if((nombre=="")||(nombre==null))
	{
		Mensaje+=" Por favor debe introducir el número de orden de venta.\n"
	}
	
	if(Mensaje!="")
	{	
		notify(Mensaje,500,5000,'error',30,5);
		return;
	}
	
	var r		=confirm("Está seguro del número de orden: "+ nombre);
	if (r!=true) return;
	
	var Data	=XML(URLNo,"No="+nombre+"&Idct="+Idct);		
	
	switch(Data)
	{
		case "0":
			$("#registroError").fadeIn();
			$('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
			$("#registroError").html("Error, al momento de convertir la cotización en orden de venta.");
		break;
		
		case "1":
			window.location.href=base_url+'clientes/ventas';
		break;
	}
}

function DeleteCotiza(Idct,N)
{
	var Nombre	=$("#id_name_"+N).val();
	var URL		=base_url+"ficha/DeleteCotizacion/";
	
	var r=confirm("Desea eliminar la cotización: "+Nombre);
	if (r!=true)
	return;
	
	var Data	=XML(URL,"Idct="+Idct+"&name="+Nombre);
	
	switch(Data)
	{
		case "0":
		$("#ErrorRespuesta").addClass("Error_validar");
		$("#ErrorRespuesta").html("Error al procesar la eliminación de la cotizacion: "+Nombre);
		$('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
		break;
		case "1":
		$("#RESPUESTACEPTADA").html("Se elimino correctamente la cotización: "+Nombre);
		$("#ErrorRespuesta").removeClass("ExitoRespuesta");
		$('#RESPUESTACEPTADA').addClass("ExitoRespuesta");
		
		$("#Id_Cotizaciones").trigger('click');
		
		setTimeout(function()
		{
			$('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
			$('#RESPUESTACEPTADA').html("");
		},2000);
		break;
	}//switch
}//DeletCotiza

//************************* Para elimnar Orden de compra  ***********************
function DeleteOrdenCompra(Idct,N){

var Nombre=$("#id_name_"+N).val();
var URL=base_url+"ficha/DeleteCotizacionOrden/";

var r=confirm("Desea eliminar la orden de venta: "+Nombre);
	if (r!=true)
	  return;

  var Data=XML(URL,"Idct="+Idct+"&name="+Nombre);

  switch(Data){

  case "0":
            $("#ErrorRespuesta").addClass("Error_validar");
            $("#ErrorRespuesta").html("Error al procesar la eliminación de la orden de venta: "+Nombre);
            $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
            break;
  case "1":
            $("#RESPUESTACEPTADA").html("Se elimino correctamente la orden de venta: "+Nombre);
            $("#ErrorRespuesta").removeClass("ExitoRespuesta");
            $('#RESPUESTACEPTADA').addClass("ExitoRespuesta");

            $("#Id_Compras").trigger('click');

                   setTimeout(function(){
                               $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
                               $('#RESPUESTACEPTADA').html("");
                             },2000);


             break;

  }//switch			
}//DeleteOrdenCompra

//**************************** Orden de Pedido ****************************************************

function IntroducirNoPedido(Idct){
	
	var URLNo=base_url+"ficha/NOdernPedido";			
  var Mensaje="";
		
  var nombre = prompt("Introduzca el número de confirmación: ");
		var r=confirm("Está seguro del número de confirmación: "+ nombre);
  	       if (r!=true)
	        return;
			
  	if((nombre=="")||(nombre==null)){
			Mensaje+=" Por favor debe introducir el número de confirmación correctamente.\n"
		}
	if(Mensaje!=""){	
	 alert("-----------------------------------------------------------------\n"+Mensaje+"\n-----------------------------------------------------------------");
     return;
	}
	var Data=XML(URLNo,"No="+nombre+"&Idct="+Idct);		
		
     switch(Data){

        case "0":
                    $("#registroError").fadeIn();
                    $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
		            $("#registroError").html("Error, al momento de introducir el número de confirmación.");
                  break;
        case "1":
                  $("#registroError").fadeOut();
                  $('#RESPUESTACEPTADA').addClass("ExitoRespuesta");
                  $('#RESPUESTACEPTADA').html("Se registro correctamente el No. de confirmación.");

                  $("#Id_Pedidos").trigger('click');

                   setTimeout(function(){
                               $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
                               $('#RESPUESTACEPTADA').html("");                              
                             },2000);                  
                break;

     }//Switch		
	
}//IntroducirNoPedido


function ConvertirOrdenPedido(Idct){
	
	var URLNo=base_url+"ficha/NOdernPedido";			
  var Mensaje="";
   var nombre="";
  
  
  var Data=XML(URLNo,"No="+nombre+"&Idct="+Idct);		
		
     switch(Data){

        case "0":
                    $("#registroError").fadeIn();
                    $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
		            $("#registroError").html("Error, al momento al convertir el orden de pedido.");
                  break;
        case "1":
                  $("#registroError").fadeOut();
                  $('#RESPUESTACEPTADA').addClass("ExitoRespuesta");
                  $('#RESPUESTACEPTADA').html("Se convirtio la venta a pedido.");

                  $("#Id_Pedidos").trigger('click');

                   setTimeout(function(){
                               $('#RESPUESTACEPTADA').removeClass("ExitoRespuesta");
                               $('#RESPUESTACEPTADA').html("");
                               $("#Id_Pedidos").trigger('click');
                             },2000);                  
                break;

     }//Switch		
	
}//ConvertirOrdenPedido


	//==================================================================================================//
	//=====================================DETALLE DE PRODUCCION========================================//
	//==================================================================================================//

	cotizacion=0;
	
	function obtenerDetalleProduccion(idCotizacion)
	{
		cotizacion=idCotizacion;
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargarAlerta').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:base_url+'clientes/detalleProductosRemision',
			data:
			{
				"idCotizacion":idCotizacion
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#cargarAlerta").html(data);
			},
			error:function(datos)
			{
				$("#cargarAlerta").html('Error en datos');	
			}
		});//Ajax	
	}
	
	$(document).ready(function()
	{
		for(i=1;i<100;i++)
		{
			$("#produceme"+i).click(function(e)
			{
				$('#alertaProduccion').dialog('open');
			});
		}
		
		$("#alertaProduccion").dialog(
		{
			autoOpen:false,
			height:450,
			width:940,
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
				///$("#ErrorRemision").fadeOut();
			}
		});
		
		$("#ventanaMateriales").dialog(
		{
			autoOpen:false,
			height:350,
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
				///$("#ErrorRemision").fadeOut();
			}
		});
		
	});
 
 
 	function obtenerDetalleMateriales(idProducto,cantidad)
	{
		//cotizacion=idCotizacion;
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargarCompra').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:base_url+'clientes/obtenerDetalleMateriales',
			data:
			{
				"idProducto":idProducto,
				"cantidad":cantidad
			},
			datatype:"html",
			success:function(data, textStatus)
			{
				$("#cargarCompra").html(data);
			},
			error:function(datos)
			{
				$("#cargarCompra").html('Error en datos');	
			}
		});//Ajax	
	}
	
	function realizarCompra(i)
	{
		//cotizacion=idCotizacion;
		
		if(confirm('¿Realmente desea hacer la compra?')==false)
		{
			document.getElementById('chkCompra'+i).checked=false;
			return;
		}
		
		$.ajax(
		{
			async:true,
			beforeSend:function(objeto)
			{
				$('#cargarCompra').html('<img src="'+ img_loader +'"/> Espere...');
			},
			type:"POST",
			url:base_url+'compras/agregarCompra',
			
			data:
			{
				"mate":$('#idMaterial'+i).val(),
				"canti":$('#cantidadecitas'+i).val(), 
				"total":$('#total'+i).val()
			},
			
			/*data:
			{
				"idProducto":idProducto,
				"cantidad":cantidad
			},*/
			datatype:"html",
			success:function(data, textStatus)
			{
				//$("#cargarCompra").html(data);
				alert('Se ha generado la compra del material');
			},
			error:function(datos)
			{
				$("#cargarCompra").html('Error en datos');	
			}
		});//Ajax	
	}

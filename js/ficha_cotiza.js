function BusquedaProductos(){
$("#id_Lista_productos").trigger('click');
}//
//Busqueda de Contactos del cliente
function BusquedaClientesContacto(){
$("#idListaContactosClientes").trigger('click');
}

//Borrar Contactos del cliente en la Lista

function DeleteContacto(K,Capa){
 if(confirm("Esta seguro de quitar el contacto.?")){
   $("#"+Capa+K).remove();
   return true;
  }
else
return false;    
}//DeleteContacto

$(document).ready(function(){

$("#id_TablaTipoCambio").fadeOut();

 $("#formcotizar").validate({
			 rules:{
			 	T2:{required:true},			 	
			  FechaDia:{required:true},            
			CantLetras:{required:true},
                       id_vals_idp:{required:true}
			 },
         messages: {
                    T2: "Por favor escriba el nombre de la cotización",                                   
              FechaDia: "Por favor seleccione la fecha de pedido",                            
            CantLetras: "Por favor escriba la cantidad en letra.",
           id_vals_idp: "Debe de seleccionar productos para la cotización." 							   						
                   }
        });

//************* Para Lista de Productos ****************************************

$('#Btn_VerListaProductos').button().click(function() {
   
    $('#dialog-ListaProductos').dialog('open');
    $("#id_Lista_productos").trigger('click');

});

//Boton para abrir la ventana de la lista de contactos
$("#IdAddConctosCliente").button().click(function(){
    $("#dialog-ListaContactos").dialog("open");
    $("#idListaContactosClientes").trigger('click');
});

$("#id_RecardarContactoCliente").click(function(){
  $("#idListaContactosClientes").trigger('click');
});

$("#id_RecargarProducto").click(function(){
  $("#id_Lista_productos").trigger('click');
});

//Click para en listar los contactos de cliente

$("#idListaContactosClientes").click(function(e){

var URL=base_url+"clientes/listacontactosclientes";

$('#id_CargandoListaContactosClientes').fadeIn();

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#id_CargandoListaContactosClientes').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"T1":"all","Idc":$("#id_cli").val(),"TBConCliente":$("#TBuscaContactoCliente").val()},
           datatype:"html",
            success:function(data, textStatus){
                 $('#id_CargandoListaContactosClientes').fadeOut();
		       $("#ErrorListaContactosClientes").fadeOut();
                        $('#RESPUESTACONTACTOSCLIENTES').html(data);
 	               },
	         error:function(datos){
                    $("#ErrorListaContactosClientes").fadeIn();
		    $("#ErrorListaContactosClientes").html(datos);
                  }
           });//Ajax
});



$("#id_Lista_productos").click(function(e){

var URL=base_url+"ficha/listaproductos";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#RESPUESTAPRODUCTOS').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"T1":"all","serie":$("#id_serie").val(),"TBuscaProducto":$("#TBuscaProducto").val()},
           datatype:"html",
            success:function(data, textStatus){

		       $("#ErrorListaProductos").fadeOut();
                        $('#RESPUESTAPRODUCTOS').html(data);					   
                          
 	               },
	         error:function(datos){
                    $("#ErrorListaProductos").fadeIn();
		    $("#ErrorListaProductos").html(datos);
                  }
           });//Ajax
});

//**************** Ventanas *********

$("#dialog-ListaContactos").dialog({
     autoOpen:false,
       height:400,
        width:800,
        modal:true,
    resizable:false,
      buttons: {
   	       'Añadir': function() {

                     var HTML="";
                        var K=0;
                    var onclick="";
                  var ImgDelete="";
             var ImgStyleDelete="";
                         var Kn=0;
             
                   $("#ErrorListaContactosClientes").fadeOut(); //Para muestra del Error
                var bValid=false;
              var checkPC = $('.CheckboxPC:checked').map(function(i,n) {
                              return $(n).val();
                             }).get(); //get converts it to an array

             if(checkPC.length == 0) {
                  $("#ErrorListaContactosClientes").fadeIn();
                  $("#ErrorListaContactosClientes").html("Debe de seleccionar un contacto.");                  
               }//If
             else{
                 $("#ErrorListaContactosClientes").fadeOut();
                 
                if($("#NContactos").val()==""){
                   Kn=1;
                }else{
                   Kn=parseInt($("#NContactos").val())+1;
                }

               while(K<checkPC.length){
                          onclick="DeleteContacto('"+Kn+"','idAddCampoContacto');";
                   ImgStyleDelete='style="display:inline-table; cursor:pointer; margin-top:-6px;"';
                        ImgDelete='<img '+ImgStyleDelete+' width="10" height="10" src="'+URLBASE+'img/borrar.png" title="Quitar contacto" align="middle" alt="Quitar contacto" border="0" onclick="'+onclick+'"  />';
                            HTML+='<td id="idAddCampoContacto'+Kn+'" align="center" style="text-align:center;"><ul><li>'+ImgDelete+'&nbsp;&nbsp;'+$("#id_nombreContacto"+checkPC[K]).val()+'|';
                            HTML+='<input type="hidden" id="NCTOS_'+Kn+'" name="NCTOS_'+Kn+'" value="'+checkPC[K]+'"/></li></ul></td>';
                          K++;
                       Kn++;
                 }//While
               $("#AddContactosClientes").append(HTML);
                         $("#NContactos").val(Kn);                      
              $('#dialog-ListaContactos').dialog('close');
             }//Fin del Else
  
              //*********************************************
		},
        Cancel: function() {
			     $(this).dialog('close');
  	          }//cancel
		}, //Buttons
	 close: function() {
	
		}//Cancel
  });



//************************************

$("#dialog-ListaProductos").dialog({
     autoOpen:false,
       height:400,
        width:1000,
        modal:true,
    resizable:false,
      buttons: {
   	       'Añadir': function() {

                //Para Añadir los productos a la Lista....
                //........................................
               $("#ErrorListaProductos").fadeOut();
                var bValid=false;                                
               var check = $('.CheckboxP:checked').map(function(i,n) {
                          return $(n).val();
                        }).get(); //get converts it to an array
               
		if(check.length == 0) {
                      bValid=false;
                   }//If
                 else{

                 var K=0;
                 var Refe=new Array(),Cant=new Array();
                 var No= parseInt($("#T").val());
             var Indice=No+1;
               var Cont= parseInt($("#Contador_init").val());
              var Total=0;
                var Idc=$("#id_cli").val();
				
		var Moneda=$("#id_StpoMda").val();
				
				

		  while(K<check.length){
		      Cant[K]=check[K]+"^"+$("#id_cantidad_"+check[K]).val();
                      Refe[K]=check[K]+"^"+$("#id_referencia_"+check[K]).val();
                      K++;
		    }//while
                 //************ Para mostrarlos en la lista*********************
				              
                $.ajax({
                        async:true,
                   beforeSend:function(objeto){$('#id_CargandoListaProductos').html('<img src="'+ img_loader +'"/> Espere...');},
                         type:"POST",
                          url:base_url+"ficha/MuetraLaListaProductosAdd",
                         data:{"idp[]":check,"No":Indice,"idc":Idc,"id_s":$("#id_serie").val(),"Cant[]":Cant,"Refe[]":Refe,"Moneda":Moneda},
                     datatype:"html",
                      success:function(data, textStatus){

                             switch(data){
                               case "0":$("#ErrorListaProductos").fadeIn();
                                          $("#ErrorListaProductos").html("Error al procesar los datos.");
                                          break;
                                   default:$("#ErrorListaProductos").fadeOut();
                                           $("#id_TablaTipoCambio").fadeIn();
					   $("#FormularioCotizacion").append(data);
                                           obtener_importesN();										   
                                           $('#id_CargandoListaProductos').html("");
                                         break;

                              }//switch
                             },
                        error:function(datos){
                               $("#ErrorListaProductos").fadeIn();
                               $("#ErrorListaProductos").html("Error al procesar los datos.");
                             }//Error
                         });//Ajax
              
                //******************************************************************************
                     bValid=true;
                                          
                      TotalNo=parseInt(No)+parseInt(K);//+1;//Para No
                    TotalCont=parseInt(Cont)+parseInt(K);//+1;//Para Cont

                            $("#T").val(TotalNo);
                $("#Contador_init").val(TotalCont);

                 }//Else de si hay datos en el Array
		      
	          if (bValid) {
		   $(this).dialog('close');
		  }else{
                    $("#ErrorListaProductos").fadeIn();
                    $("#ErrorListaProductos").html("Debe de seleccionar un producto de la lista.");
                  }//else

              //*********************************************
		},
        Cancel: function() {
			     $(this).dialog('close');
		        }//cancel
		}, //Buttons
	  close: function() {
				//allFields.val('').removeClass('ui-state-error');
		}//Cancel
      });

// Termina la funcion para productos
// Paginado


$('.ajax-pag > li a').live('click',function(eve){
 eve.preventDefault();
 var element = "#RESPUESTAPRODUCTOS";
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

 //Termina paginado

//******** Termina document
});//Document


//*****************************************************************
//********* Buscar Referencia deacuerdo a la busqueda *************

function BuscarReferencia(){

var URL=base_url+"ficha/BuscarReferencia/";
var Pal=$("#TBuscarReferencia").val();

Pal=jQuery.trim(Pal);

var Idc=$("#id_cli").val();

var No= parseInt($("#T").val());

var Moneda=$("#id_StpoMda").val();

$.ajax({
              async:true,
         beforeSend:function(objeto){$('#CargandoBuscar').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"T1":Pal,"Idc":Idc,"Moneda":Moneda},
           datatype:"html",
            success:function(data, textStatus){

               switch(data){
                case "0":
                          $('#CargandoBuscar').fadeIn();
                          $('#CargandoBuscar').html("<i>No se encontro producto relacionada con la busqueda.</i>");
                          break;
                 default:
                         $("#TBuscarReferencia").val("");
                         $("#TBuscarReferencia").focus();
						 
		         $("#id_TablaTipoCambio").fadeIn();//Muestra el Tipo de Cambio
						  						  						 
                         AddFilaProducto();
						                                 
			var cad=data.toString().split('^');
								
			 var No= parseInt($("#T").val());

 //********   0              1         2              3        4          5      6     7         8          9
//********  referencia | cantidad | descripcion  | Precio | Tipo |   Importe | idp | normal | Moneda | Precio convertido
//id_descr_   , id_precio_  ,id_tipo_   id_precio_t_

                        $("#id_refe_"+No).val(cad['0']);
                        $("#id_refelabel_"+No).html(cad['0']);
                        
		        $("#id_canti_"+No).val(cad['1']);
                        
           		$("#id_descr_"+No).val(cad['2']);
                        
						
                        $("#id_mone_"+No).html(Moneda);
                        $("#id_mone_n_"+No).val(Moneda);

                        $("#id_precio_"+No).html("$ "+number_format(cad['9'],2,"",""));
                        $("#id_precio_conver_"+No).val(cad['9']);

                                              
                        $("#id_moneNormal_"+No).val(cad['8']);
                        $("#id_precioNormal_"+No).val(cad['3']);
          		                        
                          
                        switch(cad['4']){
                          case "A":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "B":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "C":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "E":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "E1":$("#id_tpo_"+No+"option[value="+cad['4']+"]").attr("selected",true);break;
                          case "pb":$("#id_tpo_"+No+"option[value="+cad['4']+"]").attr("selected",true);break;
                        }//switch
                      
                        
                        $("#id_precio_t_impor_"+No).html("$ "+number_format(cad['5'],"2","",""));
			$("#id_precio_t_"+No).val(cad['5']);
                        
			$("#FechaAumentada"+No).datepicker({changeMonth:true});
                       
                        $("#id_p_"+No).val(cad['6']);
						
						
                        
                        $("#id_moneda_t_"+No).val(Moneda);
                        $("#id_Moneda_"+No).html(Moneda);
						
			$("#id_precio_base_"+No).val(cad['10']);
			$("#id_moneda_base_"+No).val(cad['11']);

                        $('#CargandoBuscar').fadeOut();

                        obtener_importesN();//Obtiene el total del Importe = SubTotal
						                       
                      break;
                    }//Fin del switch                    

 	          },
	      error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);	
                 }//error
           });//Ajax
  
}//Buscar Referencia

//Agrega otra Fila para realizar operaciones de cotizaciones ....
function AddFilaProducto(){

  var No= parseInt($("#T").val());
var Cont= parseInt($("#Contador_init").val());

  No=No+1;
Cont=Cont+1;

var OnclickQuitar="RemoveFilaProducto('"+No+"','rows_');";
var Imagen=base_url+"img/unchecked.gif";

var OnclickTpo="CambiarTipoPrecio('"+No+"');";
var SelectTipo='<select id="id_tpo_'+No+'" name="id_tpo_'+No+'" onchange="'+OnclickTpo+'" >';
    SelectTipo+='<option value="A">A</option>';
    SelectTipo+='<option value="B">B</option>';
    SelectTipo+='<option value="C">C</option>';
    SelectTipo+='<option value="E">E</option>';
    SelectTipo+='<option value="E1">E1</option>';
    SelectTipo+='<option value="pb">P. Base</option>';
    SelectTipo+='</select>';



var SelectSemana=XML(base_url+"ficha/SelectFecha/","No="+No);
 

var HTML='<tr id="rows_'+No+'">';
   HTML+='<td>'+No+'</td>';
   HTML+='<td align="center" valign="middle"><img src="'+Imagen+'" onclick="'+OnclickQuitar+'" width="12"';
   HTML+='alt="Quitar de la lista" title="Quitar de la lista" style="cursor:pointer;" /> </td>';

	  HTML+='<td align="left" valign="middle">';
	  HTML+='<label id="id_refelabel_'+No+'"></label><input type="hidden" name="id_refe_'+No+'"  id="id_refe_'+No+'" alt="'+No+'" /></td>';

	  HTML+='<td align="left" valign="middle">';
	  HTML+='<input type="text" name="id_canti_'+No+'" id="id_canti_'+No+'" onmouseout="calcular_importe_rowCant(this);" '; 
      HTML+='class="cajas" style="width:90%;" alt="'+No+'" onblur=""calcular_importe_rowCant(this);" /></td>';

	  HTML+='<td align="center" valign="middle">';
	  HTML+='<textarea name="id_descr_'+No+'" id="id_descr_'+No+'" class="TextArea" cols="16" rows="1"></textarea></td>';

          HTML+='<td align="left" valign="middle" >';
          HTML+=SelectSemana;
          HTML+='</td>';

	  HTML+='<td align="left" valign="middle">'+SelectTipo;
	  HTML+='</td>';
	  HTML+='<td align="right" valign="middle"><label id="id_mone_'+No+'"></label><label id="id_precio_'+No+'"></label>';
	  
    HTML+='<input type="hidden" name="id_moneNormal_'+No+'"  id="id_moneNormal_'+No+'"  />';
    HTML+='<input type="hidden" name="id_precioNormal_'+No+'" id="id_precioNormal_'+No+'" />';
		
      HTML+='<input type="hidden" name="id_precio_base_'+No+'" id="id_precio_base_'+No+'" />';
      HTML+='<input type="hidden" name="id_moneda_base_'+No+'" id="id_moneda_base_'+No+'" />';
	  
      HTML+='<input type="hidden" name="id_mone_n_'+No+'" id="id_mone_n_'+No+'" />';
      HTML+='<input type="hidden" name="id_precio_conver_'+No+'" id="id_precio_conver_'+No+'" />';
	    
      HTML+='</td>';
          
	  HTML+='<td align="right" valign="middle"><label id="id_Moneda_'+No+'"></label><label id="id_precio_t_impor_'+No+'"></label>';
      HTML+='<input type="hidden" name="id_moneda_t_'+No+'"id="id_moneda_t_'+No+'" alt="'+No+'" />';
	  HTML+='<input type="hidden" name="id_precio_t_'+No+'"id="id_precio_t_'+No+'" alt="'+No+'" />';
	  HTML+='<input type="hidden" name="id_p_'+No+'"id="id_p_'+No+'" alt="'+No+'" /></td>';
	  HTML+='</tr>';
  
$("#FormularioCotizacion").append(HTML);	

$("#T").val(No);
$("#Contador_init").val(Cont);
	
}//AddFilaProducto

// Lista de metodos para realizar el calculo 
//******************************************
// Para cambiar el tipo de Cambio ya sean Dolares / Moneda Nacional MX
function CambiarMonedaTipo(){
var	Moneda=$("#id_StpoMda").val();
var contador_init=parseInt($("#Contador_init").val());
var URL=base_url+"ficha/CambiarMoneda/";

	var total=0; 
	
	for(var i=0; i<= contador_init; i++){
		  	   
   	// var Precio = parseFloat($("#id_precio_base_"+i).val());//Precio
       //  var MonedaBase = $("#id_moneda_base_"+i).val();//MonedaNormal

       var Precio = parseFloat($("#id_precioNormal_"+i).val());//Precio
   var MonedaBase = $("#id_moneNormal_"+i).val();//MonedaNormal
    
   	   var Cantidad = parseFloat($("#id_canti_"+i).val());//Cantidad
	    	   	   
	   var PrecioCambiado=XML(URL,"MonedaConvertir="+Moneda+"&MonedaNormal="+MonedaBase+"&Precio="+Precio);

          // alert("MonedaConvertir:"+Moneda+"MonedaBase:"+MonedaBase+"_PrecioaConverir:"+Precio+"_Res:"+PrecioCambiado);

            
            //$("#id_moneNormal_"+i).val(Moneda);
            //$("#id_precioNormal_"+i).val(PrecioCambiado);
		  		  		  									  
	    $("#id_mone_"+i).html(Moneda);//label
            $("#id_mone_n_"+i).val(Moneda);
        
	$("#id_precio_"+i).html("$ "+number_format(parseFloat(PrecioCambiado*Cantidad),2,"",""));
 $("#id_precio_conver_"+i).val(PrecioCambiado);

  $("#id_precio_t_impor_"+i).html("$ "+number_format(parseFloat(PrecioCambiado*Cantidad),"2","",""));		
        $("#id_precio_t_"+i).val(parseFloat(PrecioCambiado*Cantidad));

		$("#id_moneda_t_"+i).val(Moneda);
		$("#id_Moneda_"+i).html(Moneda);
					  	   	   	   	   	   
	}//for
  obtener_importesN();//Obtiene el total del Importe = SubTotal
}//Cambia Moneda

function CambiarTipoPrecio(No)
{
var	Moneda=$("#id_StpoMda").val();

var URL=base_url+"ficha/CambiarPreciosByTipo/";
var refe=$("#id_refe_"+No).val();
var Datos="";

  if(refe!=""){

 Data=XML(URL,"refe="+refe+"&Tpo="+$("#id_tpo_"+No).val()+"&cant="+$("#id_canti_"+No).val()+"&Moneda="+Moneda);

   //Reingresa los datos con el valor cambiado

 switch(Data){
                case "0":
                          $('#CargandoBuscar').html("<i>No se encontro producto relacionada con la busqueda.</i>");
                          break;
                 default:

                         var cad=Data.toString().split('^');

			 //var No= parseInt($("#T").val());
//********   0              1         2              3        4          5      6     7         8          9
//********  referencia | cantidad | descripcion  | Precio | Tipo |   Importe | idp | normal | Moneda | Precio convertido
//id_descr_   , id_precio_  ,id_tipo_   id_precio_t_

                             $("#id_refe_"+No).val(cad['0']);
                        $("#id_refelabel_"+No).html(cad['0']);

                        $("#id_canti_"+No).val(cad['1']);
                        $("#id_descr_"+No).val(cad['2']);

                        $("#id_mone_"+No).html(Moneda);
                        $("#id_mone_n_"+No).val(Moneda);

                        $("#id_precio_"+No).html("$ "+number_format(cad['9'],2,"",""));
                        $("#id_precio_conver_"+No).val(cad['9']);
                        
                        $("#id_moneNormal_"+No).val(cad['8']);
                        $("#id_precioNormal_"+No).val(cad['3']);

                        switch(cad['4']){
                          case "A":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "B":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "C":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "E":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "E1":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "pb":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                        }//switch


                        $("#id_precio_t_impor_"+No).html("$ "+number_format(cad['5'],"2","",""));
			$("#id_precio_t_"+No).val(cad['5']);


                        $("#id_p_"+No).val(cad['6']);

                        $("#id_moneda_t_"+No).val(Moneda);
                        $("#id_Moneda_"+No).html(Moneda);
						
                        $("#id_precio_base_"+No).val(cad['10']);
                        $("#id_moneda_base_"+No).val(cad['11']);

                        $('#CargandoBuscar').fadeOut();

                        obtener_importesN();//Obtiene el total del Importe = SubTotal

                      break;
                    }//Fin del switch                  


    
  // Termina de reingresar los datos

   }//Fin del refe
  else{
    return;
  }//else
 
}//CambiarTipoPrecio


/*
 * Obtiene el importe al hacer change en el input Cantidad
 */



function calcular_importe_rowCant(obj)
{
	var cont = obj.alt;
	var unitario = parseFloat($("#id_precio_conver_"+cont).val());
	var cant = parseInt($("#"+obj.id).val());

	if(isNaN(unitario)){
		unitario = 0;
	}
	
	if(isNaN(cant)){
		//alert("Ingresa valor");
		//$("#id_precio_"+cont).focus();
		//return;
	}

	var importe = unitario * cant;

	$("#id_precio_t_impor_"+cont).html("$ "+number_format(importe,"2","",""));
        $("#id_precio_t_"+cont).val(importe);

   obtener_importesN();//Obtiene el total del Importe = SubTotal
	
}//function

//Este Metodo obtiene el importe total = SubTotal


function obtener_importes(){

 var contador_init=parseInt($("#Contador_init").val());

	var total=0;
	for(var i=0; i< contador_init; i++){

	var importe = parseFloat($('#id_precio_t_'+i).val());
	  if(isNaN(importe)){
	 	importe = 0;
	   }
	  total = total + importe;
	}//for

    $("#TSubTotal").val(total);
   $("#TLSubTotal").html(number_format(total,"2","",""));	

}//function

function calcular_importe(obj){

	var cont = obj.alt;
	var unitario = parseFloat($("#"+obj.id).val());
	var cant = parseInt($("#id_canti_"+cont).val());
	if(isNaN(cant)){
		alert("Ingresa cantidad");
		$("#id_canti_"+cont).focus();
		return;
	}
	var importe = unitario * cant;

	$("#id_precio_t_impor_"+cont).html("$ "+number_format(importe,"2","",""));
        $("#id_precio_t_"+cont).val(importe);

	obtener_importes();
	
	//calcular_total(0);//le paso 0 porq sino duplica el importe
}//function



// Para Cambiar IVA

function CambiarIVA(){
calcular_total(0);    
}

function Descuentos(){
  calcular_total(0);
}

//Para mostrar la fecha y aumentada ***********************

function AddFechaSemanas(date,N){
var URL=base_url+"ficha/retornaFechaSumada/"
var PARAMETROS="date="+date+"&dias="+$("#id_semana_"+N).val()

$("#FechaAumentada"+N).val(XML(URL,PARAMETROS));
$("#FechaLabSem"+N).html(XML(URL,PARAMETROS));

$("#FechaLabSem"+N).attr("disabled",true); 

}//AddFechasSemanas

//Para Calcular Semanas del Años
//Para Calcular Semanas del Años
function SemanaAnno(No){

var URL=base_url+"ficha/semanasyear/"
var IdSe=$("#id_semana_"+No).val();//No
var Year=$("#id_year_"+No).val();

$("#FechaAumentada"+No).val(XML(URL,"Num="+IdSe+"&Year="+Year));
   $("#FechaLabSem"+No).html(XML(URL,"Num="+IdSe+"&Year="+Year));

}//SemanaAnno


//**********************************************************************************************************


//====================================================================================================//
//====================================AGREGAR PRODUCTOS A LA COTIZACIÓN===============================//
//====================================================================================================//





function lookup(inputString) 
{
	if(inputString.length == 0) 
	{
		// Hide the suggestion box.
		$('#suggestions').hide();
	} 
	else 
	{
		$.post(base_url+"inventarioProductos/obtenerRemisionProductos", {queryString: ""+inputString+""}, 
		function(data)
		{
			if(data.length >0) 
			{
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			}
		});
	}
} // lookup

function fill(descripcion,idProducto,precioA,precioB,precioC,precioD,precioE,servicio,idPeriodo,factor,valor) 
{
	precio=0;
	precioActual=0;
	
	if(isNaN(precioA))
	{
		return;
	}
	
	i=$('#contador').val();
	fechaServicio='';
	
	if(servicio=="1")
	{
		fechaServicio=', Fecha inicio <input type="text" id="txtFechaInicio'+i+'" name="txtFechaInicio'+i+'" class="cajas" style="width:125px" /> ';
	}
	
	fechaServicio+='<input type="hidden" id="txtIdPeriodo'+i+'" name="txtIdPeriodo'+i+'" value="'+idPeriodo+'"/>';
	fechaServicio+='<input type="hidden" id="txtFactor'+i+'" name="txtFactor'+i+'" value="'+factor+'"/>';
	fechaServicio+='<input type="hidden" id="txtValor'+i+'" name="txtValor'+i+'" value="'+valor+'"/>';
	
	precioCliente=$('#precioCliente').val();
	
	a='';
	b='';
	c='';
	d='';
	e='';
	
	if(precioCliente=="1") {a='selected="selected"'; precioActual=precioA};
	if(precioCliente=="2") {b='selected="selected"'; precioActual=precioB};
	if(precioCliente=="3") {c='selected="selected"'; precioActual=precioC};
	if(precioCliente=="4") {d='selected="selected"'; precioActual=precioD};
	if(precioCliente=="5") {e='selected="selected"'; precioActual=precioE};
	
	preciosA=Math.round(precioA*100)/100;
	preciosB=Math.round(precioB*100)/100;
	preciosC=Math.round(precioC*100)/100;
	preciosD=Math.round(precioD*100)/100;
	preciosE=Math.round(precioE*100)/100;
	
	precios='<select style="width:100px" class="cajas" id="id_tpo_'+i+'" name="id_tpo_'+i+'" \
			  onchange="cambiarPrecioTipo('+i+')" >';
	precios+='<option value="'+precioA+'" '+a+'>'+preciosA+'</option>';
	precios+='<option value="'+precioB+'" '+b+'>'+preciosB+'</option>';
	precios+='<option value="'+precioC+'" '+c+'>'+preciosC+'</option>';
	precios+='<option value="'+precioD+'" '+d+'>'+preciosD+'</option>';
	precios+='<option value="'+precioE+'" '+e+'>'+preciosE+'</option>';
	precios+='</select>';
	
	HTML='<tr id="rows_'+i+'">';
	HTML+='<td align="center" valign="middle"><img src="http://'+base_url+'img/quitar.png"'+
			'onclick="RemoveFilaProducto('+i+',\'rows_\')" ';
	HTML+=' width="16" title="Quitar de la lista" style="cursor:pointer;" /> </td>';
	HTML+='<td align="center" valign="middle">';
    HTML+='<input type="text" name="id_canti_'+i+'" alt="'+i+'" id="id_canti_'+i+'" value="1"  ';
    HTML+='class="cajas" style="width:90%;"  alt="'+i+'"> </td>';
	HTML+='<td align="center" valign="middle">';
    
	//HTML+='<textarea name="id_descr_'+i+'" alt="'+i+'" id="id_descr_'+i+'" \
	//		class="TextArea" cols="16" rows="1">'+descripcion+'</textarea>';
    HTML+=descripcion;
	HTML+=fechaServicio;
	HTML+='</td>';
	
	precioActual=Math.round(precioActual*100)/100;
	
	HTML+='<td align="center" valign="middle">'+precios;

	HTML+='<input type="hidden" name="txtServicio'+i+'"  id="txtServicio'+i+'"   value="'+servicio+'"  />';

	HTML+='<input type="hidden" name="id_precioNormal_'+i+'" id="id_precioNormal_'+i+'"  value="'+precioActual+'"  />';
	HTML+='<input type="hidden" name="id_precio_conver_'+i+'" id="id_precio_conver_'+i+'" value="'+precioActual+'"  />';
	HTML+='</td>';
	HTML+='<td align="right" valign="middle">';
    HTML+='<label id="id_precio_t_impor_'+i+'">'+precioActual+'</label>';
    HTML+='<input type="hidden" name="id_precio_t_'+i+'"id="id_precio_t_'+i+'" alt="'+i+'" value="'+precioActual+'"/>';
    HTML+='<input type="hidden" name="id_p_'+i+'"id="id_p_'+i+'" alt="'+i+'" value="'+idProducto+'" /></td>';
    HTML+='</tr>';
	
	$('#inputString').val('');
	//$('#idProducto').val(idProducto);
	setTimeout("$('#suggestions').hide();", 200);
	
	$('#id_canti_'+i).focus();
	
	HTML+='<script>\
		$("#txtFechaInicio'+i+'").datetimepicker({ changeMonth: true });\
	</script>';
	
	i++;
	
	$('#contador').val(i);

	$('#FormularioCotizacion').append(HTML);
	
}

//====================================================================================================//
//====================================CHECAR PRODUCTOS ANTES DE COTIZAR===============================//
//====================================================================================================//



function checarProductos()
{
	indice=$('#contador').val();
	
	if($('#FechaDia').val()=="")
	{
		alert('Debe seleccionar una fecha para la entrega');
		return;
	}
	
	if(indice=='1')
	{
		alert('Aun no se han agregado productos a la cotizacion');
		return;
	}
	
	if($('#id_cli').val()=='')
	{
		alert('Debe seleccionar un cliente');
		return;
	}
	
	if(isNaN($('#id_cli').val()))
	{
		alert('El numero de dias de credito no es correcto');
		return;
	}
	
	v=0;
	
	productos=new Array();
	cantidades=new Array();
	mostrar='';
	
	for(i=1;i<indice;i++)
	{
		idProducto=$('#id_p_'+i).val();
		cantidad=$('#id_canti_'+i).val();
		//descripcion=$('#id_descr_'+i).val();
		
		if(!isNaN(idProducto))
		{
			productos[v]=idProducto;
			cantidades[v]=cantidad;
			
			v++;
		}
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			//$('#clientesZona').html('<img src="'+ img_loader +'"/> Espere...');
		},
		type:"POST",
		url:base_url+'ficha/obtenerDetalleExistencia',
		data:
		{
			"productos":productos,
			"cantidades":cantidades
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			if(data=="0")
			{
				document.forms['formcotizar'].submit();
			}
			else
			{
				
				notificaciones='';
				
				p=1;
		
				for(i=0;i<data.length;i++)
				{
					if(i==0)
					{
						notificaciones+=p+".- ";
						p++;
					}
					if(data[i]=='&')
					{
						if(i+1==data.length)
						{
							notificaciones+='\n';
						}
						else
						{
							notificaciones+='\n'+p+".- ";
						}
						p++;
					}
					else
					{
						notificaciones+=data[i];
						
					}
				}
				
				if(confirm('Productos con existencia menor. ¿Desea continuar?: \n\n'+notificaciones)==false)
				{
					return;
				}
				else
				{
					document.forms['formcotizar'].submit();
				}
				
			}
		},
		error:function(datos)
		{
			$("#clientesZona").html('Error');	
		}
	});//Ajax	
}

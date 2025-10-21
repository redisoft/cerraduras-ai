//base_url="sanvalentin.redisoftsystem.com/";
//base_url="localhost/sanvalentin/";

$(document).ready(function(){

 $("#formcotizar").validate({
			 rules:{
			 	T2:{required:true},			 	
			  FechaDia:{required:true},            
			        //T6:{required:true},
                       id_vals_idp:{required:true}                                           
			 },
         messages: {
                    T2: "Por favor escriba el nombre de la cotización",                                   
              FechaDia: "Por favor seleccione la fecha de pedido",                            
                    //T6: "Por favor escriba las observaciones de la cotización.",
           id_vals_idp: "Debe de seleccionar productos para la cotización." 							   						
                   }
        });

//************* Para Lista de Productos ****************************************

$('#Btn_VerListaProductos').button().click(function() {
   
    $('#dialog-ListaProductos').dialog('open');
    $("#id_Lista_productos").trigger('click');

});


$("#id_Lista_productos").click(function(e){

var URL=base_url+"ficha/listaproductos";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#RESPUESTAPRODUCTOS').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	              url:URL,
               data:{"T1":"all","serie":$("#id_serie").val()},
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
                         data:{"idp[]":check,"No":Indice,"idc":Idc,"id_s":$("#id_serie").val(),"Cant[]":Cant,"Refe[]":Refe},
                     datatype:"html",
                      success:function(data, textStatus){

                             switch(data){
                               case "0":  $("#ErrorListaProductos").fadeIn();
                                          $("#ErrorListaProductos").html("Error al procesar los datos.");
                                          break;
                                   default:$("#ErrorListaProductos").fadeOut();
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
                                          
                      TotalNo=parseInt(No)+parseInt(K)+1;//Para No
                    TotalCont=parseInt(Cont)+parseInt(K)+1;//Para Cont

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
           data:{"T1":"pag","serie":$("#id_serie").val()},
       dataType:"html",
     beforeSend:function(){ $(element).html('<img src="'+ img_loader +'"/> Espere...'); },
        success:function(html,textStatus){
            setTimeout(function(){
            $(element).html(html);},300);
         },
      error:function(datos){$(element).html('Error '+ datos).show('slow'); }
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

$.ajax({
              async:true,
         beforeSend:function(objeto){$('#CargandoBuscar').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"T1":Pal,"Idc":Idc},
           datatype:"html",
            success:function(data, textStatus){
                              
               switch(data){
                case "0":
                          $('#CargandoBuscar').html("<i>No se encontro producto relacionada con la busqueda.</i>");
                          break;
                 default:
                         $("#TBuscarReferencia").val("");
                         $("#TBuscarReferencia").focus();

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
                        
          		$("#id_precio_"+No).html("$ "+number_format(cad['3'],2,"",""));
                        
                        $("#id_mone_"+No).html(cad['8']);
                        $("#id_mone_n_"+No).val(cad['8']);
                        
                        $("#id_precio_conver_"+No).val(cad['9']);

                          
                        switch(cad['4']){
                          case "A":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "B":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "C":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                        }//switch
                      
                        
                        $("#id_precio_t_impor_"+No).html("$ "+number_format(cad['5'],"2","",""));
			$("#id_precio_t_"+No).val(cad['5']);
                        
			$("#FechaAumentada"+No).datepicker({changeMonth:true});
                       
                        $("#id_p_"+No).val(cad['6']);
                        
                        $("#id_moneda_t_"+No).val("EUR");
                        $("#id_Moneda_"+No).html("EUR");

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
    SelectTipo+='</select>';

var OnClickSemana="AddFechaSemanas('"+getFechActual('n')+"','"+No+"')";

var SelectSemana='<input type="text"  id="id_semana_'+No+'" name="id_semana_'+No+'" value="0" onmouseout="'+OnClickSemana+'" class="cajas" style="float:left;width:20%;" />';
   SelectSemana+='<input type="text" name="FechaAumentada'+No+'" id="FechaAumentada'+No+'" class="cajas" style="width:60%;" value="'+getFechActual('m')+'" />';
   SelectSemana+='<label id="FechaLabSem'+No+'" class="cajas" style="display:none; float:left;width:49%;">'+getFechActual('m')+'</label>';

var OnClickMnda="CambiarMonedaTipo('"+No+"');";   
var SelectMda='<select id="id_StpoMda_'+No+'" name="id_StpoMda_'+No+'" onchange="'+OnClickMnda+'" >';
    SelectMda+='<option value="EUR">EUR</option>';
    SelectMda+='<option value="USD">USD</option>';
    SelectMda+='<option value="MX">MX</option>';
    SelectMda+='</select>'; 

var HTML='<tr id="rows_'+No+'">';
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

	  HTML+='<td align="center" valign="middle">'+SelectTipo;
	  HTML+='</td>';
	  HTML+='<td align="left" valign="middle"><label>'+SelectMda+'</label><label id="id_mone_'+No+'"></label><label id="id_precio_'+No+'"></label>';
      HTML+='<input type="hidden" name="id_mone_n_'+No+'" id="id_mone_n_'+No+'" />';
      HTML+='<input type="hidden" name="id_precio_conver_'+No+'" id="id_precio_conver_'+No+'" />';
      HTML+='</td>';
          
	  HTML+='<td align="right" valign="middle"><label id="id_Moneda_'+No+'"></label> <label id="id_precio_t_impor_'+No+'"></label>';
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

// Para cambiar el tipo de precio del producto
function CambiarMonedaTipo(No){
	
	
}//Cambia Moneda

function CambiarTipoPrecio(No){

 var URL=base_url+"ficha/CambiarPreciosByTipo/";
var refe=$("#id_refe_"+No).val();
var Datos="";
  if(refe!=""){

   Data=XML(URL,"refe="+refe+"&Tpo="+$("#id_tpo_"+No).val()+"&cant="+$("#id_canti_"+No).val());

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

			$("#id_precio_"+No).html("$ "+number_format(cad['3'],2,"",""));

                        $("#id_mone_"+No).html(cad['8']);
                        $("#id_mone_n_"+No).val(cad['8']);

                        $("#id_precio_conver_"+No).val(cad['9']);


                        switch(cad['4']){
                          case "A":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "B":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                          case "C":$("#id_tpo_"+No+" option[value="+cad['4']+"]").attr("selected",true);break;
                        }//switch


                        $("#id_precio_t_impor_"+No).html("$ "+number_format(cad['5'],"2","",""));
			$("#id_precio_t_"+No).val(cad['5']);


                        $("#id_p_"+No).val(cad['6']);

                        $("#id_moneda_t_"+No).val("EUR");
                        $("#id_Moneda_"+No).html("EUR");

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

function calcular_importe_rowDesc(obj){
	
	    var cont = obj.alt;
	var unitario = parseFloat($("#id_precio_conver_"+cont).val());
	    var cant = parseInt($("#id_canti_"+cont).val());
	
	var Desc = parseInt($("#"+obj.id).val());
    	   
	if(isNaN(unitario)){
		unitario = 0;
	}
	
	if(isNaN(cant)){
		alert("Ingresa valor");
		$("#id_canti_"+cont).focus();
		return;
	}

	if(isNaN(Desc)){
		alert("Ingresa valor");
		$("#"+obj.id).focus();
		return;
	}

   var Descuentos=parseFloat(Desc/100);

	 var importe = unitario * cant;
	
  var SubImporte=importe*Descuentos;
        
         importe=importe-SubImporte;
	
	$("#id_precio_t_impor_"+cont).html("$ "+number_format(importe,"2","",""));
          $("#id_precio_t_"+cont).val(importe);

   obtener_importesN();//Obtiene el total del Importe = SubTotal
   	
}//calcular_importe_rowDesc


function calcular_importe_rowCant(obj){

	var cont = obj.alt;
	var unitario = parseFloat($("#id_precio_conver_"+cont).val());
	var cant = parseInt($("#"+obj.id).val());

	if(isNaN(unitario)){
		unitario = 0;
	}
	
	if(isNaN(cant)){
		alert("Ingresa valor");
		$("#id_precio_"+cont).focus();
		return;
	}

	var importe = unitario * cant;

	$("#id_precio_t_impor_"+cont).html("$ "+number_format(importe,"2","",""));
        $("#id_precio_t_"+cont).val(importe);

   obtener_importesN();//Obtiene el total del Importe = SubTotal
	
}//function

//Este Metodo obtiene el importe total = SubTotal
function obtener_importesN(){

 var contador_init=parseInt($("#Contador_init").val());
var subTotal_real =parseFloat($("#TSubTotal").val());//El subtotal

	var total=0;

	for(var i=0; i<= contador_init; i++){
  	   var importe = parseFloat($("#id_precio_t_"+i).val());
	    if(isNaN(importe)){
		 importe = 0;
	    }
	 total = total + importe;
	}//for

   $("#TSubTotal").val(total);
   $("#TLSubTotal").html(number_format(total,"2","",""));

  calcular_total(0);

}//function



function RemoveFilaProducto(No,Row){

 var _No= parseInt($("#T").val());

  $("#"+Row+No).remove();
  
 _No=_No-1;
 $("#T").val(_No);

  obtener_importesN();

}//RemoveFilaProducto


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

function calcular_total(importe_parm){

	var _iva = $("#TIVA").val();
	    _iva = parseFloat(_iva);

	var _desc = $("#TDesc").val();
	    _desc = parseFloat(_desc);
            
	var importe_base = parseFloat($("#TSubTotal").val());
	var subtotal = importe_base + parseFloat(importe_parm);
      
       $("#TLSubTotal").html(number_format(subtotal,"2","",""));
        $("#TSubTotal").val(subtotal);

        var iva = (subtotal * _iva);      
       	    iva = redondeo2decimales(iva);
        	
      var total = subtotal + iva;

	total = redondeo2decimales(total);

       var todo = total*_desc;
           todo = redondeo2decimales(todo);
           todo = total -todo;

           todo = redondeo2decimales(todo);

	$("#TLTotal").html(number_format(todo,"2","",""));
        $("#TTotal").val(todo);

		
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



//base_url="sanvalentin.redisoftsystem.com/";

var Num="";

$(document).ready(function(){

$("#dialog-Mensajeria").dialog({
    autoOpen:false,
      height:300,
       width:480,
       modal:true,
   resizable:false,
       close: function() {
		   
		//allFields.val('').removeClass('ui-state-error');
	     }//close
      });


});


//******************************************************************************
//********** Mensajeria de Paqueteria ******************************************
         // Proveedor , producto , Id cotiza
function random(desde,hasta){
    posibles = hasta - desde
    aleatorio = Math.random() * posibles
    aleatorio = Math.floor(aleatorio)
    return parseInt(desde) + aleatorio
}
r=random(1,9999999999);
function PaqueteriaEnvio(idct,idcl){
//**********************************

var r=random(11, 999999999);
var url=base_url+"mensajeria/ExisteMesnajeria/"+r;

var Cantidad=$("#id_cantidad").val();
var CantidadTotal=0;
var Tope=0;

var No=$("#Num").val();

 if((No==1)){
      CantidadTotal=$("#idCanti_"+No).val();
 }//If del No==1
else
    if(No>1){

     var Suma=0;
     var Total=0;
     var J=1;

        while(J<=No){

           if($("#idPrv_"+J).val()==idpv){
               Suma=Suma+parseFloat($("#idCanti_"+J).val());
               Tope=J;
            }//If
         else{
             Total=Total+parseFloat($("#idCanti_"+J).val());
          }
          J++;

        }//Fin del while

          Total=Suma+Total;

       if((Suma<=Cantidad)&&(Total<=Cantidad)){
           CantidadTotal=Suma;
        }//If Suma
   }//No>1
// Regresa los datos
 $.ajaxSetup({ cache: false }); 
  $.ajax({
       type:"post",
        url:url,
      async:true,
 /*beforeSend:function(objeto){$('#id_CargandoMj').html('<img src="'+ img_loader +'"/> Espere...');},*/
   datatype:"html",
       data:{"idct":idct,"idcl":idcl},
    success:function(data, textStatus){

      if(data==0){
			
         document.getElementById('id_VerFormularioMensajeria').style.display = "block";
         document.getElementById('id_VerFormularioSemaforo').style.display = "none";
         $('#dialog-Mensajeria').dialog('open');

           $("#id_nomj").val("");
        $("#id_nombrep").val("");
          $("#FechaDia").val("");
        $("#id_nombrev").val("");


      $('#dialog-Mensajeria').dialog({
             buttons: {
                 "Guardar":function(){

              if(ValidarDatosGuardarMensaje($("#id_nomj").val(),$("#id_nombrep").val(),$("#id_nombrev").val())){
                                                     									 
	      if(guardarDatosPaqueteria(idct,idcl,$("#id_nomj").val(),$("#id_nombrep").val(),$("#FechaDia").val(),$("#HoraArriba").val(),$("#id_nombrev").val())){
	            $("#ErrorDocumentoMj").html("");
                    CapaSemaforo(idct,idcl,Tope)
		    $(this).dialog('close');
		  }else {//IF
		        $("#ErrorDocumentoMj").html("Se encontro un error.");
	             }//Else guardarDatosPaqueteria
	        }//ValidarDatosGuardarMensaje
	          else{
                       $("#ErrorDocumentoMj").html("Falta escribir un dato en el formulario.");
		   }//Else ValidarDatos			  
                 //////////////////////////
 var img_loaderxx=base_url+"img/ajax-loader.gif";
				window.location.replace("/ventas");
				 },//buttons Guardar
                      Cancel: function() {
			                 $(this).dialog('close');
                                         $("#ErrorDocumentoMj").html("");
			       }//cancel
		       }//Buttons
              });//dialog-Mensajeria

       }//data==0
       else
           if(data==1){

                document.getElementById('id_VerFormularioMensajeria').style.display = "none";
                document.getElementById('id_VerFormularioSemaforo').style.display = "block";
                  $('#dialog-Mensajeria').dialog('open');
                  $('#dialog-Mensajeria').dialog({
                                 buttons: {
	                 
                                       Cancel: function() {
			                        $(this).dialog('close');
                                                $("#ErrorDocumentoMj").html("");
			                 }//cancel
		                  }//Buttons
                            });
$.ajaxSetup ({ 
    // Disable caching of AJAX responses */ 
    cache: false 
});
               $.ajax({
                      type:"post",
                       url:base_url+"mensajeria/semaforo/"+r,
                     async:true,
                  datatype:"html",
                      data:{"idct":idct,"idcl":idcl},
                   success:function(data, textStatus){
                          $("#id_VerFormularioSemaforo").html(data);
                          $("#FechaFinalizada").datepicker({ changeMonth: true });
	                  },
                    error:function(datos){
                           // $('#resultados_busqueda').html('Error '+ datos).show('slow');
                      }//Error
                  });//Ajax

               }//if
	    },
      error:function(datos){
               // $('#resultados_busqueda').html('Error '+ datos).show('slow');
             }//Error
  });//Ajax

//**********************************
}//Paquete de envio







function vista(idcl){
//**********************************

var r=random(11, 999999999);
var url=base_url+"mensajeria/ExisteMesnajeria/";

var Cantidad=$("#id_cantidad").val();
var CantidadTotal=0;
var Tope=0;

var No=$("#Num").val();

 if((No==1)){
      CantidadTotal=$("#idCanti_"+No).val();
 }//If del No==1
else
    if(No>1){

     var Suma=0;
     var Total=0;
     var J=1;

        while(J<=No){

           if($("#idPrv_"+J).val()==idpv){
               Suma=Suma+parseFloat($("#idCanti_"+J).val());
               Tope=J;
            }//If
         else{
             Total=Total+parseFloat($("#idCanti_"+J).val());
          }
          J++;

        }//Fin del while

          Total=Suma+Total;

       if((Suma<=Cantidad)&&(Total<=Cantidad)){
           CantidadTotal=Suma;
        }//If Suma
   }//No>1
// Regresa los datos
 $.ajaxSetup({ cache: false }); 
  $.ajax({
       type:"post",
        url:url,
      async:true,
 /*beforeSend:function(objeto){$('#id_CargandoMj').html('<img src="'+ img_loader +'"/> Espere...');},*/
   datatype:"html",
       data:{"idct":idct,"idcl":idcl},
    success:function(data, textStatus){

      if(data==0){
			
         document.getElementById('id_VerFormularioMensajeria').style.display = "block";
         document.getElementById('id_VerFormularioSemaforo').style.display = "none";
         $('#dialog-Mensajeria').dialog('open');

           $("#id_nomj").val("");
        $("#id_nombrep").val("");
          $("#FechaDia").val("");
        $("#id_nombrev").val("");


      $('#dialog-Mensajeria').dialog({
             buttons: {
                 "Guardar":function(){

              if(ValidarDatosGuardarMensaje($("#id_nomj").val(),$("#id_nombrep").val(),$("#id_nombrev").val())){
                                                     									 
	      if(guardarDatosPaqueteria(idct,idcl,$("#id_nomj").val(),$("#id_nombrep").val(),$("#FechaDia").val(),$("#HoraArriba").val(),$("#id_nombrev").val())){
	            $("#ErrorDocumentoMj").html("");
                    CapaSemaforo(idct,idcl,Tope)
		    $(this).dialog('close');
		  }else {//IF
		        $("#ErrorDocumentoMj").html("Se encontro un error.");
	             }//Else guardarDatosPaqueteria
	        }//ValidarDatosGuardarMensaje
	          else{
                       $("#ErrorDocumentoMj").html("Falta escribir un dato en el formulario.");
		   }//Else ValidarDatos			  
                 //////////////////////////
 var img_loaderxx=base_url+"img/ajax-loader.gif";

     $("#container").html('<img src="'+ img_loaderxx +'"/> Espere...');
$("#container").load(base_url+"ventas/cotizacion_cliente/"+idcl);	 
				 },//buttons Guardar
                      Cancel: function() {
			                 $(this).dialog('close');
                                         $("#ErrorDocumentoMj").html("");
			       }//cancel
		       }//Buttons
              });//dialog-Mensajeria

       }//data==0
       else
           if(data==1){

                document.getElementById('id_VerFormularioMensajeria').style.display = "none";
                document.getElementById('id_VerFormularioSemaforo').style.display = "block";
                  $('#dialog-Mensajeria').dialog('open');
                  $('#dialog-Mensajeria').dialog({
                                 buttons: {
	                 
                                       Cancel: function() {
			                        $(this).dialog('close');
                                                $("#ErrorDocumentoMj").html("");
			                 }//cancel
		                  }//Buttons
                            });

               $.ajax({
                      type:"post",
                       url:base_url+"mensajeria/semaforo/",
                     async:true,
                  datatype:"html",
                      data:{"idct":idct,"idcl":idcl,"r":r},
                   success:function(data, textStatus){
                          $("#id_VerFormularioSemaforo").html(data);
                          $("#FechaFinalizada").datepicker({ changeMonth: true });
	                  },
                    error:function(datos){
                           // $('#resultados_busqueda').html('Error '+ datos).show('slow');
                      }//Error
                  });//Ajax

               }//if
	    },
      error:function(datos){
               // $('#resultados_busqueda').html('Error '+ datos).show('slow');
             }//Error
  });//Ajax

//**********************************
}//Paquete de envio



//*************************************************

function OcultarActualizarEnvio(){

$("#ActualizarInventario").fadeOut();
$("#OcultarTerminar").fadeOut();
$("#VerTerminar").fadeIn();

}

function VerActualizarEnvio(){

$("#ActualizarInventario").fadeIn();
$("#OcultarTerminar").fadeIn();
$("#VerTerminar").fadeOut();

}//Terminar...

function ActualizarInventario(Idct,Idcl){

var T1=$("#id_nombreRec").val();
var T2=$("#FechaFinalizada").val();
var mensaje="";

 if(T1==""){
  mensaje+=" Error, debe escribir el nombre de recibido."
 }

 if(T2==""){
   mensaje+=" Error, debe escribir la fecha."
 }

if(mensaje.length>0){
 $("#ErrorActualizar").html('<div class="Error_validar" style="width:90%; margin-left:4%;">'+mensaje+'</div>');
}
else{


 $.ajax({
                      type:"post",
                       url:base_url+"mensajeria/actualizarInventario/",
                     async:true,
                  datatype:"html",
                        data:{"Idct":Idct,"Idcl":Idcl,"Nombre":T1,"FechaReal":T2},
                   success:function(data, textStatus){
                          
                              switch(data){
                               
                               case "0": $("#ErrorActualizar").fadeIn();
                                         $("#ErrorActualizar").html('<div class="Error_validar" style="width:90%; margin-left:4%;">Error al guardar.</div>');
                                         break;
                               case "1":  
                                         $("#ActualizarInventario").html("Datos guardados correctamente.");
                                         $("#ErrorActualizar").fadeOut(); 
          
								 break;
                              
                               }
                              

	                  },
                    error:function(datos){
                           // $('#resultados_busqueda').html('Error '+ datos).show('slow');
                      }//Error
                  });//Ajax
 var img_loaderx=base_url+"img/ajax-loader.gif";

     $("#container").html('<img src="'+ img_loaderx +'"/> Espere...');
              window.location.href=base_url+"ventas";

}//Else de comparacion de valores y vericarlos...else

}


//**************************************************

function guardarDatosPaqueteria(idct,idcl,id_nomj,id_nombrep,FechaDia,HoraArriba,id_nombrev){
var PARAMETROS="idct="+idct+"&idcl="+idcl+"&id_nomj="+id_nomj+"&id_nombrep="+id_nombrep+"&FechaDia="+FechaDia+"&HoraArriba="+HoraArriba+"&id_nombrev="+id_nombrev;
return RegresaValor(XML(base_url+"mensajeria/guardardatos/",PARAMETROS));  
}

function RegresaValor(Data){
var RegresaValorDatos="";

   switch (Data){
  	case "0": RegresaValorDatos=false;  break;
	case "1": RegresaValorDatos=true; break;
   }//Switch
   
   return RegresaValorDatos;
}

function ValidarDatosGuardarMensaje(T1,T2,T3){
//$("#id_nomj").val(),$("#id_nombrep").val(),$("#id_nombrev").val()
var Band=true;

   if(T1==""){
     Band=false;
   }

   if(T2==""){
    Band=false;
   }

   if(T3==""){
    Band=false;
  }

  return Band;

}


// CapaSemaforo_

function CapaSemaforo(Id_cotiza,IdPrv,IdPro,Tope){

$.ajax({
      type:"post",
       url:base_url+"mensajeria/SemaforoPaqueteriaCapa/",
beforeSend:function(objeto){$('#CapaSemaforo'+Tope).html('<img src="'+ img_loader +'"/> Espere...');},
     async:true,
  datatype:"html",
      data:{"idct":Id_cotiza,"idpv":IdPrv,"idp":IdPro},
   success:function(data, textStatus){
          $("#CapaSemaforo_"+Tope).html(data);
          },
    error:function(datos){
          $("#CapaSemaforo_"+Tope).html('Error '+ datos);
      }//Error
  });//Ajax


}//CapaSemaforo

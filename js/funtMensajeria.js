//base_url="sanvalentin.redisoftsystem.com/";

$(document).ready(function(){

$("#dialog-Pedidos").dialog({
    autoOpen:false,
      height:550,
       width:600,
       modal:true,
   resizable:false,
       close: function() {		
	     }//close
      });
 });

 //*****************************************************************************
 //****************** Busca primero que proveedores son ***********************

function VerPaqueteriaProveedor(No){
var Idpv=parseInt($("#id_proveedor_"+No).val());
 var Idp=parseInt($("#idp_"+No).val());
var Idct=parseInt($("#idct").val());

var Canti=parseInt($("#id_cantidad_"+No).val());

  if(Idpv==0){
      $("#id_semaforo_"+No).html("pendiente");
      $("#id_acciones_"+No).html("pendiente");      
  }else
      if(Idpv>=1){
         PaqueteriaEnvio(Idct,Idp,Idpv,No,Canti);
      }
}//Termina funcion



//********** Mensajeria de Paqueteria ******************************************

function PaqueteriaEnvio(Idct,Idp,Idpv,No,Canti){
//**********************************
var url=base_url+"mensajeriaproveedor/ExisteMesnajeria/";
var PARAMETROS="idct="+Idct+"&idpv="+Idpv+"&idp="+Idp;
var Existe=XML(url,PARAMETROS);
var OnClickVentana="";
var LinkVentana="";

switch(Existe){

 case "0"://Para mostrar el Semaforo/Link del Paquete si no existe
          //id_semaforo_,id_acciones_

           OnClickVentana="AbrirVentana('"+Idct+"','"+Idp+"','"+Idpv+"','"+No+"','0','"+Canti+"')";
              LinkVentana='<img src="http://'+base_url+'img/mensajeria.gif" id="idme_'+No+'" title="Mensajería"';
             LinkVentana+='alt="Mensajería" width="17"  height="17" style="cursor:pointer" onclick="'+OnClickVentana+'"/>';

          $("#id_acciones_"+No).html(LinkVentana);

          break;

 case "1": //Para mostrar el Semaforo/Link del Paquete si existe
           OnClickVentana="AbrirVentana('"+Idct+"','"+Idp+"','"+Idpv+"','"+No+"','1','"+Canti+"')";
              LinkVentana='<img src="http://'+base_url+'img/mensajeria.gif" id="idme_'+No+'" title="Mensajería"';
             LinkVentana+='alt="Mensajería" width="17"  height="17" style="cursor:pointer" onclick="'+OnClickVentana+'"/>';

          $("#id_acciones_"+No).html(LinkVentana);
         
          CapaSemaforo(Idct,Idpv,Idp,No);

          break;


}//Fin del Switch si Existen Datos de mensajeria
//**********************************
}//Paquete de envio
//*************************************************


function AbrirVentana(Idct,Idp,Idpv,No,Tpo,Canti){

var HTML="";
var URLA=base_url+"mensajeriaproveedor/FormularioHTML/";
var URLB=base_url+"mensajeriaproveedor/ExisteMesnajeria/";
var PARAMETROS="idct="+Idct+"&idpv="+Idpv+"&idp="+Idp+"&No="+No+"&Canti="+Canti;


    switch (Tpo){

      case "0"://Para mostrar el formulario con los datos ya introducidos
          
               $('#CargandoPedidos').fadeOut();
                  $("#PedidosError").fadeOut();

         document.getElementById('id_VerFormularioMensajeria').style.display = "block";
         document.getElementById('id_VerFormularioSemaforo').style.display = "none";


         HTML=XML(URLA,PARAMETROS);
         $("#id_VerFormularioMensajeria").html("");
         $("#id_VerFormularioMensajeria").append(HTML);

           $("#FechaDia").datepicker({changeMonth: true});
          $("#FechaDia2").datepicker({changeMonth: true});

          $('#dialog-Pedidos').dialog('open');


       $('#dialog-Pedidos').dialog({
          buttons: {
        "Guardar":function(){
       //***********************************************************************

     if(ValidarDatosGuardarMensaje($("#id_nomj_1").val(),$("#id_nombrep").val(),$("#id_nombrev").val(),$("#id_piezasT").val(),$("#id_piezas").val())){
	 	 
if(guardarDatosPaqueteria(Idct,Idpv,Idp,$("#id_nomj_1").val(),$("#id_nombrep").val(),$("#FechaDia").val(),$("#HoraArriba").val(),$("#id_nombrev").val(),Canti,$("#id_piezas").val())){

	                $("#PedidosError").fadeOut();
                    $("#PedidosError").html("");

                    CapaSemaforo(Idct,Idpv,Idp,No);

                    $(this).dialog('close');
		  }else {//IF
		        $("#PedidosError").fadeIn();
                $("#PedidosError").html("Se encontro un error.");
	             }//Else guardarDatosPaqueteria
	   }//ValidarDatosGuardarMensaje
	   else{
                 $("#PedidosError").fadeIn();
                 $("#PedidosError").html("Falta escribir un dato en el formulario/recuerda que las piezas no debe de revasar de lo solicitado.");
	     }//Else ValidarDatos
       

                   
      //**************** Termina el Boton guardar ******************************
                  },//buttons Guardar
                              Cancel: function() {
                                       $("#PedidosError").html("");
                                       $("#PedidosError").fadeOut();
                                       $(this).dialog('close');
                                    }//cancel
                               }//Buttons
                  });//dialog-Mensajeria

              //Termina el primero 0
                break;
      case "1"://Ver datos que ya estan hechos.....
              //****************************************************************

              $("#PedidosError").fadeOut();

            document.getElementById('id_VerFormularioMensajeria').style.display = "none";
            document.getElementById('id_VerFormularioSemaforo').style.display = "block";

              $('#dialog-Pedidos').dialog('open');

              $('#dialog-Pedidos').dialog({
                           buttons: {

                           Cancel: function() {
                                    $("#PedidosError").html("");
                                    $("#PedidosError").fadeOut();
                                    $(this).dialog('close');
                             }//cancel
		           }//Buttons
                      });

               $.ajax({
                      type:"post",
                       url:base_url+"mensajeriaproveedor/semaforo/",
                     async:true,
                  datatype:"html",
                      data:{"idct":Idct,"idpv":Idpv,"idp":Idp,"Total":Canti},
                   success:function(data, textStatus){
                          $("#id_VerFormularioSemaforo").html(data);
                          $("#FechaFinalizada").datepicker({ changeMonth: true });
	                  },
                       error:function(datos){
                           // $('#resultados_busqueda').html('Error '+ datos).show('slow');
                      }//Error
                  });//Ajax
             
          
              //****************** Termina el Break *****************************
                break;
    }//Switch

}//AbrirVentana


//******************************************************************************

function guardarDatosPaqueteria(Idct,Idpv,Idp,Noc,Nombrep,FechaDia,HoraArriba,id_nombrev,Canti,Piezas){

var PARAMETROS="idct="+Idct+"&idpv="+Idpv+"&idp="+Idp+"&id_nomj="+Noc+"&id_nombrep="+Nombrep+"&FechaDia="+FechaDia+"&HoraArriba="+HoraArriba+"&id_nombrev="+id_nombrev+"&Canti="+Canti+"&Piezas="+Piezas;

return RegresaValor(XML(base_url+"mensajeriaproveedor/guardardatos/",PARAMETROS));

}

function RegresaValor(Data){
var RegresaValorDatos="";

   switch (Data){
  	case "0": RegresaValorDatos=false;  break;
	case "1": RegresaValorDatos=true; break;
   }//Switch

   return RegresaValorDatos;
}

function ValidarDatosGuardarMensaje(T1,T2,T3,T4A,T4B){
var Num=parseInt(T4A);
var Ped=parseInt(T4B);

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
  
  if((Ped==0)||(Ped<0)||(Ped>Num)){
     Band=false;
  }
  
  return Band;
  
}//ValidarDatosGuardarMensaje


//************** Capas Semaforo ************

function CapaSemaforo(Idct,Idpv,Idp,No){

$.ajax({
      type:"post",
       url:base_url+"mensajeriaproveedor/SemaforoPaqueteriaCapa/",
beforeSend:function(objeto){$('#id_semaforo_'+No).html('<img src="'+ img_loader +'"/> Espere...');},
     async:true,
  datatype:"html",
      data:{"idct":Idct,"idpv":Idpv,"idp":Idp},
   success:function(data, textStatus){
          $("#id_semaforo_"+No).html(data);
          },
    error:function(datos){
          $("#id_semaforo_"+No).html('Error '+ datos);
      }//Error
  });//Ajax


}//CapaSemaforo


//Para Finalizar Envio


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

function ActualizarInventario(Idp,Idpv,Cant,Idm,Idc){

var T1=$("#id_nombreRec").val();
var T2=$("#FechaFinalizada").val();
var mensaje="";

var NoSerie=new Array();

//Tomar los valores del Numero de serie

var No=parseInt($("#Cantidad").val());
 var J=0;
var  K=0;
var  NoSerie=new Array();
var Cadena="";

while(J<=No){
Cadena="#id_noserie_"+J;

   if($(Cadena).val()!=""){
      NoSerie[K]=$(Cadena).val();
	    K++;
    }//IF
   else{
       mensaje+="Error, debe de introducir el No.serie.";
     }
  J++;
}//While

//**************************************
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
          url:base_url+"mensajeriaproveedor/actualizarInventarioProveedores/",
        async:true,
     datatype:"html",
         data:{"Cantidad":No,"Idp":Idp,"Idpv":Idpv,"Nombre":T1,"FechaReal":T2,"Idm":Idm,"Idc":Idc,"NoSerie[]":NoSerie},
      success:function(data, textStatus){

               switch(data){

               case "0": $("#ErrorActualizar").fadeIn();
                         $("#ErrorActualizar").html('<div class="Error_validar" style="width:90%; margin-left:4%;">Error al guardar.</div>');
                        break;
               case "1":
                        $("#ActualizarInventario").html("Datos guardados correctamente.");
                        $("#ErrorActualizar").fadeOut();
                        break;

               }//switch
       },
       error:function(datos){
                    // $('#resultados_busqueda').html('Error '+ datos).show('slow');
             }//Error
      });//Ajax

  }//Else de comparacion de valores y vericarlos...else

}//Actualizar Inventario


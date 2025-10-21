 $(document).ready(function(){

    $("#id_buscar_link").click(function(e){
        
        var   mensage="";
        var pal_empre=$("#emp_id").val();


     //if((ID == null) || (ID.length == 0) || (pal_empre == null) || (pal_empre.length == 0))    {
     //   mensage+="<p>Debe de ingresar el ID o el nombre de la empresa a buscar.</p>";
     // }
     // terminar de verificar

    if(mensage.length>0){
      $("#registroError").fadeIn();
      $("#registroError").html(mensage);
      }//
     else{
      $("#registroError").fadeOut();

      //Manda a buscar el cliente

      $.ajax({
                 type:"POST",
                  url:base_url+"clientes/buscar",
                async:true,
           beforeSend:function(objeto){$('#CargandoID').html('<img src="'+ img_loader +'"/> Espere...');},
             datatype:"html",
                 data:{"T2":pal_empre},
              success:function(data, textStatus){

                       switch(data){
                           case "0":
                                    $("#registroError").fadeIn();
                                    $('#registroError').html('No se encontro ningun regristo.');
                                    break;
                           default:
                                    $("#registroError").fadeOut();
                                    $('#RESPUESTACLIENTE').html(data);
                                   break;
                       }//switch

                     },
              complete: function(objeto, exito){
                       $('#CargandoID').fadeOut();
                     },
                error:function(datos){
                      $("#registroError").fadeIn();
                      $('#registroError').html('Error '+ datos);
                     }//Error

         });//Ajax

      

      // Termina para buscar
     }//Else


   });//Click

//*********************** Terminar ***********************
 });





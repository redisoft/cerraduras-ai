
function ListaProductos(Serie,No,Capa){
var HTML="";
var OnClickCerrar="Cerrar('"+Capa+"','"+No+"');";
var NumSe=Serie;

$("#registroError").fadeOut();   
     $('#'+Capa+No).fadeOut();

HTML+='<div style="background-color:#f6f6f6;height:15px;vertical-align:middle;';
HTML+='text-align:left;padding-left:2%;float:left;width:95%;margin-top:0.2em;margin-bottom:1%;padding-right:2%;border-bottom:1px solid #e9e9e9;">';
HTML+='<label>Lista de productos</label>  |  <label style="cursor:pointer;text-decoration:underline;font-family:Trebuchet MS;';
HTML+='font-size:9px;color:#A90303;" onclick="'+OnClickCerrar+'">Cerrar</label> </div>';
$("#"+Capa+No).fadeIn();

var URL=base_url+"ficha/ListaProductosProveedores";

            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#'+Capa+No).html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"Serie":NumSe},
           datatype:"html",
            success:function(data, textStatus){

                         switch(data){			
                            case "0": 
                                      $("#registroError").html("Error, intentelo de nuevo o recarge la página.");
                                      $("#registroError").fadeIn(); 
                                      $('#'+Capa+No).html("");
                                     break;
                              default:                              
                                      $('#'+Capa+No).html(HTML+data);					   
                                      $("#registroError").fadeOut();                                                 
                                     break;
                           }
 	             },
	         error:function(datos){
                    $("#registroError").fadeIn();
		    $("#registroError").html(datos);	
                  }
           });//Ajax

//$("#"+Capa+No).html(HTML);

}

function Cerrar(Capa,No){
$("#"+Capa+No).fadeOut();
} 

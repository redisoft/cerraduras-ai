
//base_url="sanvalentin.redisoftsystem.com/";
//base_url="localhost/sanvalentin/";


$(document).ready(function(){

//********* A�adir Documentos ***********

$("#Add_documento").click(function(e){
//$("#registroError").fadeIn();
//$("#registroError").fadeOut();

$("#AddRESPUESTA").fadeIn();

});//


$("#formdocumento").validate({
			 rules:{
			 	T1:{required:true},
			 	//T3:{required:true},
   ArchivoExcel:{required:true}//,accept:"doc|docx|xls|xlsx|pdf"}
			 },
                         messages: {
                                    T1: "Por favor escriba el nombre.",
                                  //  T3: "Por favor escriba la descripción.",
                          ArchivoExcel: "Por favor seleccione un archivo."
                                   }


		 });



new AjaxUpload("#SaveDocumento", {

	       action: base_url+"ficha/uploadArchivo",
           beforeSend:function(){
			  $("#SaveDocumento").html("Espere un momento...");
			},

             onSubmit:function(file , ext){

              if (!(ext && /^(doc|docx|xls|xlsx|pdf)$/.test(ext))){
				     alert("Error: no se permiten ese tipo de archivo.");
				// cancela upload
				return false;
			} else {
				$("#SaveDocumento").text("Subiendo al servidor el archivo.");
				this.disable();
			}
			},
			onComplete: function(file, response){
				$("#SaveDocumento").text("Se agrego exitosamente el archivo");
				// enable upload button
				this.enable();
				// Agrega archivo a la lista
				//$("#lista").appendTo(".files").text(file);
				$("#preview1").html(response);
			}
			});




});

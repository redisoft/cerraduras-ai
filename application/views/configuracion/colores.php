<script language="javascript" type="text/javascript">
$(document).ready(function(){


$("#agregarColor").click(function(e){
   $('#dialog-Colores').dialog('open');
});


$("#dialog-Colores").dialog({
     autoOpen:false,
       height:200,
        width:300,
        modal:true,
    resizable:false,
      buttons: {
	 	'Aceptar': function() {
			
		       
			   
			   var Mensage="";
			  // var T33=$("#T33").val();
			   var URL="http://"+base_url+"configuracion/agregarColor";
			   
			   if($("#col").val()==""){
			      Mensage+="<p>Error en la descripcion del color.</p>";										
			   }
              if(Mensage.length==0){
			  // Guardar los datos con Ajax
			  
            $.ajax({
              async:true,
         beforeSend:function(objeto){$('#id_CargandoColor').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
          //data:{"idProducto":$("#T11").val(),"idMaterial":$("#T22").val(),"costo":costo,"cantidad":T44},
		  data:{"desc":$("#col").val()},
           datatype:"html",
            success:function(data, textStatus){
								       					   					   
                           switch(data){
                                   case "0":
                                            $("#Error-Colores").fadeIn();
                                            $("#Error-Colores").html("<p>Error al momento de guardar los datos intentelo de nuevo.</p>");
                                             break;
                                   case "1":
                                            window.location.href="http://"+base_url+"configuracion/colores";
                                            break;
											
									
                           }//switch
 	               },
	         error:function(datos){
                    $("#Error-Colores").fadeIn();
		    $("#Error-Colores").html(datos);	
                  }
           });//Ajax						  	  
				  				  				  
			  }//
			 else{
                  $("#Error-Colores").fadeIn();
		          $("#Error-Colores").html(Mensage);
			 }				 				 
		   //*** validar campos *****	       
		},
        Cancel: function() {
			    $("#Error-Colores").fadeOut(); 
                            $(this).dialog('close');				 
			  }
		},
	  close: function() {
    			   $("#Error-Colores").fadeOut();
			}
      });
  //*********************** Terminar ***********************
 });
</script>

<div class="derecha">

<div class="barra"><?php print('Colores'); ?></div>

<div class="submenu">

<div class="toolbar" id="toolbar">
  <table class="toolbar admintable" width="55%">
    <tr>
      <td width="27%" align="center" valign="middle" class="button"><a  href="<?php print(base_url()."configuracion/"); ?>" class="toolbar" id=""> <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.gif" alt="a" width="24" height="23" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
      <td width="23%" align="center" valign="middle" ><a href="<?php print(base_url()."configuracion/listauser"); ?>" class="toolbar" id=""> <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/user_48.png" alt="a"  width="24" height="24" title="Lista de usuarios" /></span> Lista de usuarios </a> </td>
      <td width="23%" align="center" valign="middle" ><a href="<?php print(base_url()."configuracion/adduser"); ?>" class="toolbar" id=""> <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/user_add_32.png" alt="a"  width="24" height="24" title="Añadir nuevo usuario" /></span> A&ntilde;adir nuevo usuario </a> </td>
      
            <td width="23%" align="center" valign="middle" ><a href="<?php print(base_url()."configuracion/roles"); ?>" class="toolbar" id=""> <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png" alt="a"  width="24" height="24" title="Roles" /></span> Roles </a> </td>
            
      <td width="17%" align="center" valign="middle" ><a href="<?php print(base_url()."bancos"); ?>" class="toolbar" id=""> <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/money_bills.png" alt="a"  width="24" height="24" title="Banco" /></span> Banco </a> </td>
	  <td width="17%" align="center" valign="middle" ><a href="<?php print(base_url()."bancos/cuentas"); ?>" class="toolbar" id=""> <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png" alt="a"  width="24" height="24" title="Banco" /></span> Cuentas </a> </td>
	  
	   
	    <td width="17%" align="center" valign="middle" ><a href="<?php print(base_url()."configuracion/unidades"); ?>" class="toolbar" id=""> <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png" alt="a"  width="24" height="24" title="Unidades" /></span> Unidades </a> </td>
		
<td width="17%" align="center" valign="middle" ><a href="<?php print(base_url()."configuracion/zonas"); ?>" class="toolbar" id=""> <span class="icon-option" title="Zonas"> <img src="<?php print(base_url()); ?>img/zona.png" alt="a"  width="24" height="24" title="Zonas" /></span> Zonas </a> </td>

    </tr>
  </table>
  </div>

 
</div>

<div class="listproyectos" >

<div style="padding:10px" ><?php print('<img src="'.base_url().'img/add.png" width="24px;" height="24px;" class="agregarColor" id="agregarColor" style="cursor:pointer; padding-left:45px" title="Añadir color">'); ?>  <br />
<label style="padding-left:20px">Añadir color </label>
</div>
  <?php

if(!empty ($colores)){

?>
<table width="99%;" class="admintable" style="float:left; padding-left:20px">
    <thead>
     <tr >
        <th width="10%" align="center" valign="middle">#</th>
        <th width="50%" align="center">Descripcion</th>
	    <th width="10%" align="center">Acciones</th>

     </tr>
    </thead>
   <tbody>

<?php
  $No=1;
foreach ($colores as $color){


?>
<tr>
<td align="center"> <?php print($color['idColor']); ?> </td>
<td align="center" valign="middle"><?php print($color['descripcion']); ?></td>
<td align="center" valign="middle">
<!--a href="<?php echo base_url()?>configuracion/editauser/<?php echo $color['idColor']; ?>"><img src="<?php echo base_url()?>img/edit.png" width="18" height="18" border="0"  title="Editar"></a-->

<a href="<?php echo base_url()?>configuracion/borrarColor/<?php echo $color['idColor']  ?>"><img src="<?php echo base_url()?>img/bin_empty.png" width="18" height="18" border="0" title="Eliminar" onClick="return confirm('Esta seguro de borrar este color')" ></a></td>
</tr>

<?php

$No++;

}//Foreach


//echo $pa;



?>
   </tbody>
 </table>

<?php



// ***** Productos
}else{
    print('<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
            No se encontraron registros.
           </div>');
}//Productos
?>

	<div style="visibility:hidden">
	
		<div id="dialog-Colores" title="Colores:">
		<div style="width:99%;" id="id_CargandoColor"></div>
		
		<div id="Error-Colores" class="ui-state-error" ></div>
		
		<table class="admintable" width="99%;">
		
		<tr>
		  <td class="key">Descripcion:</td>
		  <td>
		 <input name="col" id="col" type="text" class="cajasSelect" value="	" />
		 
		  </td>
		</tr>	
		
		</table>
		</div>
	</div>


</div>
<!-- Termina derecha-->
</div>

<script>
function ingresar()
{
	document.getElementById('acceso').submit();
}
</script>

<div class="arriba">
    <div class="titulitoEncabezado" align="center">
    	<img style="margin-left:650px" src="<?php echo base_url()?>img/redisoft.png" />
    </div>
</div>

<div class="top">
</div>

<form id="acceso" name="acceso" action="<?php echo base_url()?>login/acceso" method="post">

<div id="div_login" align="right">
	<div class="header"  style="position:absolute; margin-left:864px; margin-top:9px; z-index:200">Acceso</div>

	<div style="font-size:26px; width:400px; float:left; 
    color:#FFF; text-align:left; padding-top:130px; padding-left:150px; font-weight:normal">
    Si buscas <strong>resultados distintos,</strong> <br />
    usa <strong>Redisoft Systems</strong>
    </div>
    
    <div style="color:#FFF; font-size:18px; z-index:30; position:absolute; margin-left:870px; margin-top:60px">
    <table border="0" cellspacing="0" cellpadding="0" class="tablaFormulario">
		<tr>
		<td align="center">&nbsp;
        
		</td>
		</tr>
		<tr align="center" class="textosRosa">
			<td >
            Nombre
            </td>
		</tr>
		<tr style="height:40px">
			<td align="center">
                <input type="text"  class="textosFormulario" id="username" name="username" />
 			</td>
		</tr>
		<tr align="center" class="textosRosa">
			<td>
            Contrase&#241;a:
            </td>
		</tr>
		<tr style="height:40px">
			<td align="center">
              <input type="password" class="textosFormulario"  id="password" name="password" />
			</td>
		</tr>
		
		<tr>
        
        <tr>
        	<td style="border: solid 1px #666"></td>
        </tr>
        
		<td align="center">
		  <div style="text-align:left; padding-left:7.0em; font-size:12px; color:#B10808; ">
		     <?php 
			  # print($this->validation->error_string);			   			  			   
			 ?>
		  </div>
		  <div style="text-align:center; font-size:12px; color:#B10808;">
			<?php
            if(isset($ErrorDatos))
            {
            	#echo($ErrorDatos);
            }
            ?>
		  </div>
		  
		</td>		
		</tr>		
		<tr>
			<td align="center" style="height:55px">
                <!--img src="<?php echo base_url()?>img/aceptars.png" style="cursor:pointer" onclick="ingresar()" /-->
                <input type="submit" class="aceptar" style="color:#000" value="ACEPTAR">
			</td>
		</tr>
        
        <tr>
			<td align="center" style="border-top: solid 1px #666; border-bottom: solid 1px #666; height:50px; font-size:15px">
            		¿Olvidaste tu contraseña?
			</td>
		</tr>
        
        <tr>
        	<td>
            	<?php
                if(file_exists('img/logos/'.$estilo->id.'_'.$estilo->logotipo) and strlen($estilo->logotipo)>4)
				{
					echo '<img src="'.base_url().'img/logos/'.$estilo->id.'_'.$estilo->logotipo.'" style="margin-top:0px; max-width:187px; max-height:80px"  />';
				}
				?>
            	
            </td>
        </tr>
				
	</table>
    </div>
    
    <div class="formularioLogin" style="float:right; z-index:-10">
    <!--div class="header">ACCESO</div-->
    
    </div>
    <!--img src="<?php echo base_url()?>img/logos/logoBon.png" style="width:120px; height:130px" /-->
</div>
</form>
<script src="<?php echo base_url()?>js/serviciosConsumo/servicios.js"></script>


<script>
$(document).ready(function()
{
	obtenerServicios();
});
</script>

<div class="derecha">
<div class="submenu">

<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%">
 	<tr>
    	<td class="seccion" colspan="2">
    		Servicios
   	    </td>
    </tr>
    <tr>
    	<td width="20%" align="left" valign="middle" style="border:none" >
			<?php 
            echo '
            <a onclick="formularioServicios()" id="btnServicios">
                <img src="'.base_url().'img/servicios.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar servicio">  
                <br />
                Nuevo servicio 
            </a>'; 
            ?>
        </td>
        
    	<?php

		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnServicios\');
			</script>';
		}
		
        ?>
        <td width="80%" align="left" valign="middle" style=" padding-right:120px">
        	<input type="text"  name="txtBusquedasServicios" id="txtBusquedasServicios" class="busquedas" placeholder="Buscar servicio" style="width:400px; "/>
        </td>
    </tr>
 </table>
 </div>
</div>
<div class="listproyectos">

<div id="procesandoServicios"></div>
<div id="obtenerServicios"></div>


<div id="ventanaAsociarProveedor" title="Agregar proveedor">
    <div id="agregandoProveedor"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioAgregarProveedor"></div>
</div>

<div id="ventanaEditarServicio" title="Editar servicio">
    <div id="editandoServicio"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerServicio"></div>
</div>

<div id="ventanaRegistrarServicio" title="Agregar servicio">
    <div id="registrandoServicio"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioServicios"></div>
</div>



</div>
</div>

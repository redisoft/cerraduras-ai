<script type="text/javascript" src="<?php echo base_url()?>js/inventarios/inventarios.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/inventarios/usos.js"></script>
<script>
$(document).ready(function()
{
	obtenerInventarios();
});
</script>

<div class="derecha">
<div class="submenu">

<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%">
 	<tr>
    	<td class="seccion" colspan="2">
    	Mobiliario/equipo
   	    </td>
    </tr>
    <tr>
    	<td width="20%" align="left" valign="middle" style="border:none" >
			<?php 
            echo '
            <a onclick="formularioInventarios()" id="btnMobiliario">
                <img src="'.base_url().'img/inventario.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Añadir nuevo producto">  
                <br />
                Nuevo Mobiliario/equipo  
            </a>'; 
            ?>
        </td>
        
    	<?php
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnMobiliario\');
			</script>';
		}
        ?>
        <td width="80%" align="left" valign="middle" style=" padding-right:120px">
        	<input type="text"  name="txtBusquedasInventarios" id="txtBusquedasInventarios" class="busquedas" placeholder="Buscar mobiliario/equipo" style="width:400px; "/>
        </td>
    </tr>
 </table>
 </div>
</div>
<div class="listproyectos">

<div id="procesandoInventarios"></div>
<div id="obtenerInventarios"></div>

<div id="ventanaUsos" title="Usos del Mobiliario/equipo">
<div style="width:99%;" id="registrandoUsos"></div>
<div id="errorUsosInventario" class="ui-state-error" ></div>
<div id="obtenerUsosInventario"></div>
</div>

<div id="ventanaAsociarProveedor" title="Agregar proveedor al Mobiliario/equipo">
<div id="agregandoProveedor"></div>
<div id="errorAgregarProveedor" class="ui-state-error" ></div>
<div id="formularioAgregarProveedor"></div>
</div>

<div id="ventanaEditarInventario" title="Editar Mobiliario/equipo">
<div id="editandoInventario"></div>
<div id="errorEditarInventario" class="ui-state-error" ></div>
<div id="obtenerInventario"></div>
</div>

<div id="ventanaAgregarInventario" title="Agregar Mobiliario/equipo">
    <div id="registrandoInventario"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioInventarios"></div>
</div>

</div>
</div>

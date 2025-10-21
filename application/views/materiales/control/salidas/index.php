<script src="<?php echo base_url()?>js/materiales/control/salidas.js"></script>
<script src="<?php echo base_url()?>js/materiales/control/devueltos.js"></script>

<script>
$(document).ready(function()
{
	//obtenerSalidasControl();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%" >
    <tr>
    	<?php
		
		echo '
		<td align="center" valign="middle">
			<a onclick="formularioSalidaControl()" id="btnControl">
				<img src="'.base_url().'img/materiales.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Registrar avío">
				<br />
				Nuevo registro
			</a>
		</td>';
	
	
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnControl\');
			</script>';
		}
        ?>
			<td width="80%" align="left" valign="middle" >
                <input type="text"  	name="txtBuscarControl" id="txtBuscarControl" class="busquedas" placeholder="Buscar por folio, usuario, tienda" style="width:500px; "/>
                
                Filtro de 
                <input type="text"  	name="txtInicioControl" id="txtInicioControl" class="busquedas" value="<?php echo date('Y-m-d')?>" style="width:90px; " onchange="obtenerSalidasControl()"/>
                a 
                <input type="text"  	name="txtFinControl" 	id="txtFinControl" 	class="busquedas" 	value="<?php echo date('Y-m-d')?>" style="width:90px;" onchange="obtenerSalidasControl()"/>
        	</td>
        
		</tr>
 	</table>
 </div>
</div>
<div class="listproyectos">
	<div id="procesandoControl"></div>

	<div id="obtenerSalidasControl"></div>

    <div id="ventanaSalidasControl" title="Salidas control">
        <div id="registrandoSalida"></div>
        <div id="formularioSalidaControl"></div>
    </div>
    
    <div id="ventanaEditarSalidaControl" title="Editar salida">
        <div id="editandoSalida"></div>
        <div id="obtenerSalidaControl"> </div>
    </div>
    
    <div id="ventanaDevueltosControl" title="Devueltos">
        <div id="registrandoDevueltos"></div>
        <div id="obtenerDevueltosControl"> </div>
    </div>

</div>
</div>


<script src="<?php echo base_url()?>js/pedidos/conteos.js"></script>

<script>
$(document).ready(function()
{
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
		<td align="center" valign="middle" width="25%">
			<a onclick="formularioConteos()" id="btnControl">
				<img src="'.base_url().'img/pan.png" width="40px;" height="30px;"  style="cursor:pointer;" title="Registrar conteo">
				<br />
				Nuevo registro
			</a>
			
			<!--<a onclick="obtenerReportePanaderos()">
				<img src="'.base_url().'img/reportes.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Reporte panaderos">
				<br />
				Reporte panaderos
			</a>-->
		</td>';
	
	
		/*if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnControl\');
			</script>';
		}*/
        ?>
			<td width="80%" align="left" valign="middle" >
                <input type="text"  	name="txtBuscarConteo" id="txtBuscarConteo" class="busquedas" placeholder="Buscar por folio, usuario, tienda" style="width:500px; "/>
                
                Filtro de 
                <input type="text"  	name="txtInicioConteo" id="txtInicioConteo" class="busquedas" value="<?php echo $fechas[0]?>" style="width:90px; " onchange="obtenerConteos()"/>
                a 
                <input type="text"  	name="txtFinConteo" 	id="txtFinConteo" 	class="busquedas" 	value="<?php echo $fechas[1]?>" style="width:90px;" onchange="obtenerConteos()"/>
                
                <input type="hidden"  	name="txtOrdenConteos" 	id="txtOrdenConteos"  value="desc"/>
                <input type="hidden"  	name="txtOrdenReporte" 	id="txtOrdenReporte"  value="desc"/>
        	</td>
        
		</tr>
 	</table>
 </div>
</div>
<div class="listproyectos">
	<div id="procesandoConteo"></div>

	<div id="obtenerConteos"></div>

    <div id="ventanaConteos" title="Conteo de pan">
        <div id="registrandoConteo"></div>
        <div id="formularioConteos"></div>
    </div>
    
    <div id="ventanaEditarConteo" title="Editar conteo">
        <div id="editandoConteo"></div>
        <div id="obtenerConteo"> </div>
    </div>
    
    <div id="ventanaDetallesConteo" title="Detalles conteo">
        <div id="detallesConteo"> </div>
    </div>
    
    

</div>
</div>


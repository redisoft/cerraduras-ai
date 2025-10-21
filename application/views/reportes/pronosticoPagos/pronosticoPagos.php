<script src="<?php echo base_url()?>js/reportes.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerPronostico();
})
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
<div class="seccionDiv">
Pronóstico de pagos
</div>
 <table class="toolbar" width="80%">
    <tr>
    	<td>
        <select class="cajas" id="selectProveedores" onchange="obtenerPronostico()" >
        	<option value="0">Todos los proveedores</option>
			<?php
                foreach($proveedores as $row)
                {
                    echo '<option value="'.$row->idProveedor.'">'.$row->empresa.'</option>"';
                }
            ?>
        </select>
        
        <input title="Fecha inicio" type="text" class="busquedas" placeholder="Seleccionar fecha" 
        	style="width:150px; cursor:pointer" id="FechaDia" onchange="obtenerPronostico()" value="<?php echo date('Y-m-01')?>" />
            
             <input title="Fecha fin" type="text" class="busquedas" placeholder="Seleccionar fecha" 
        	style="width:150px; cursor:pointer" id="FechaDia2" onchange="obtenerPronostico()" value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" />
        </td> 
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">

<div id="obtenerPronostico"></div>

<div id="ventanaAgenda" title="Agenda de pagos">
	<div id="registrandoAgenda"></div>
	<div id="obtenerAgenda"></div>
</div>

</div>
</div>

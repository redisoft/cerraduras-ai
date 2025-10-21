<script src="<?php echo base_url()?>js/reportes/auxiliarProveedores.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerAuxiliarProveedores();
})
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Auxiliar de proveedores
</div>-->
 <table class="toolbar" width="80%">
    <tr>
    	<td>
        <select class="cajas" id="selectProveedores" onchange="obtenerAuxiliarProveedores()" >
        	<option value="0">Todos los proveedores</option>
			<?php
                foreach($proveedores as $row)
                {
                    echo '<option value="'.$row->idProveedor.'">'.$row->empresa.'</option>"';
                }
            ?>
        </select>
        
        <input title="Fecha inicio" type="text" class="busquedas" placeholder="Seleccionar fecha" 
        	style="width:150px; cursor:pointer" id="FechaDia" onchange="obtenerAuxiliarProveedores()" value="<?php echo date('Y-m-01')?>" />
            
             <input title="Fecha fin" type="text" class="busquedas" placeholder="Seleccionar fecha" 
        	style="width:150px; cursor:pointer" id="FechaDia2" onchange="obtenerAuxiliarProveedores()" value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" />
        </td> 
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
<div id="generandoReporte"></div>
<div id="obtenerAuxiliarProveedores"></div>


</div>
</div>

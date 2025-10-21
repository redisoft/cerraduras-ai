<script type="text/javascript" src="<?php echo base_url()?>js/reportes/gastos.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>
<script>
$(document).ready(function()
{
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			$('#txtIdProveedor').val(ui.item.idProveedor);
			obtenerGastos();
		}
	});
	
	obtenerGastos();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de gastos
</div>-->
 <table class="toolbar" width="90%">
    <tr>
        <td width="90%">
            <input onchange="obtenerGastos()" readonly="readonly" value="<?php echo date('Y-m-01')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerGastos()" readonly="readonly" value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" type="text" title="Fin" id="FechaDia2" style="width:90px" class="busquedas" placeholder="Fecha fin" />
        
        
         <select  id="selectCuentas" name="selectCuentas" class="busquedas" style="width:auto;"  onchange="obtenerGastos()">
            <option value="0">Seleccione cuenta</option>
            <?php
			foreach($cuentas as $row)
			{
				echo '<option value="'.$row->idCuenta.'">'.$row->nombre.', '.$row->cuenta.'</option>';
			}
            ?>
         </select>
         
         <input type="text" class="busquedas" id="txtBuscarProveedor" placeholder="Seleccione proveedor"  />
         <input type="hidden" id="txtIdProveedor" value="0"  />
         
         <select  id="selectCriterio" name="selectCriterio" class="busquedas" style="width:125px"  onchange="obtenerGastos()">
            <option value="0">Con iva y sin iva</option>
            <option value="1">Con iva</option>
            <option value="2">Sin iva</option>
         </select>
         
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerGastos"></div>
</div>

<div id="ventanaGastosInformacion" title="Detalles de egreso">
<div id="obtenerGastoInformacion"></div>
</div>

</div>

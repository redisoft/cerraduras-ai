<script type="text/javascript" src="<?php echo base_url()?>js/reportes/pronosticoGastos.js"></script>
<script>
$(document).ready(function()
{
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			$('#txtIdProveedor').val(ui.item.idProveedor);
			obtenerPronosticoGastos();
		}
	});
	
	obtenerPronosticoGastos();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de Pronóstico de pagos
</div>-->
 <table class="toolbar" width="90%">
    <tr>
        <td width="90%">
            <input onchange="obtenerPronosticoGastos()" readonly="readonly" value="<?php echo date('Y-m-01')?>" type="text" title="Inicio" style="width:130px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerPronosticoGastos()" readonly="readonly" value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" type="text" title="Fin" id="FechaDia2" style="width:130px" class="busquedas" placeholder="Fecha fin" />
       
        
        
         <input type="text" class="busquedas" id="txtBuscarProveedor" placeholder="Seleccione proveedor" style="width:650px"  />
         <input type="hidden" id="txtIdProveedor" value="0"  />
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerPronosticoGastos"></div>
</div>
</div>

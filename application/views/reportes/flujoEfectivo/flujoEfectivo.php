<script src="<?php echo base_url()?>js/reportes/flujoEfectivo.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$("#txtMesFlujo").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
	
	obtenerFlujoEfectivo();
})
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Reporte de flujo de efectivo
</div>-->
 <table class="toolbar" width="30%">
	<tr>
    	<td class="key">
        Buscar por mes:
        </td>
    	<td>
        	<input value="<?php echo date('Y-m')?>" onchange="obtenerFlujoEfectivo()" 
            	style="width:100px" type="text" class="cajas" id="txtMesFlujo" />
        </td>
    </tr>
</table>
</div>
</div>

<div class="listproyectos">

<div id="obtenerFlujoEfectivo"></div>


<div id="ventanaEntradasFlujo" title="Detalles de entradas">
<div id="obtenerEntradasFlujo"></div>
</div>

<div id="ventanaSalidasFlujo" title="Detalles de salidas">
<div id="obtenerSalidasFlujo"></div>
</div>

<div id="ventanaDetallesCaja" title="Detalles de caja chica">
<div id="obtenerSalidasCajaChica"></div>
</div>

</div>
</div>

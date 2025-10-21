<script src="<?php echo base_url()?>js/reportes/flujoCaja.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$("#txtMesFlujo").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
	
	obtenerFlujoCaja();

})
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Reporte de flujo de caja chica
</div>-->
 <table class="toolbar" width="30%">
	<tr>
    	<td class="key">
        Buscar por mes:
        </td>
    	<td>
        	<input value="<?php echo date('Y-m')?>" onchange="obtenerFlujoCaja()" 
            	style="width:100px" type="text" class="cajas" id="txtMesFlujo" />
        </td>
    </tr>
</table>
</div>
</div>

<div class="listproyectos">

<div id="obtenerFlujoCaja"></div>


<div id="ventanaSalidasFlujoCaja" title="Detalles de salidas">
<div id="obtenerSalidasCajaDetalles"></div>
</div>

</div>
</div>

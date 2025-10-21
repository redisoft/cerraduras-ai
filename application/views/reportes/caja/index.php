<script type="text/javascript" src="<?php echo base_url()?>js/reportes/caja.js"></script>

<form id="frmCriterios">
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de Ingresos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
        <td width="90%">
        	
			<input onchange="obtenerReporte()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fecha" style="width:90px" id="FechaDia" name="txtFechaInicial" class="busquedas" />
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="generandoReporte"></div>
	<div id="obtenerReporte"></div>
</div>


</div>
</form>

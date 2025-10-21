<script type="text/javascript" src="<?php echo base_url()?>js/reportes/historialMovimientos.js"></script>
<script>
$(document).ready(function()
{
	//$('#btnBorrar').fadeOut();

	obtenerHistorialMovimientos();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">

 <table class="toolbar" width="100%" >
    <tr>
        <td width="100%">
            <input onchange="obtenerHistorialMovimientos()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerHistorialMovimientos()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fin" id="FechaDia2" style="width:90px" class="busquedas" placeholder="Fecha fin" />
            
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerHistorialMovimientos">
    	<input type="hidden" id="selectUsuario" name="selectUsuario" value="" />
        <input type="hidden" id="selectModulo" name="selectModulo" value="" />
    </div>
</div>
</div>

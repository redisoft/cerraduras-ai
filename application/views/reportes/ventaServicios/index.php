<script type="text/javascript" src="<?php echo base_url()?>js/reportes/ventaServicios.js"></script>
<script>
$(document).ready(function()
{
	obtenerVentaServicios();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">

 <table class="toolbar" width="100%" >
    <tr>
        <td width="100%">
            <input onchange="obtenerVentaServicios()" readonly="readonly" value="<?php echo date('Y-01-01')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerVentaServicios()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fin" id="FechaDia2" style="width:90px" class="busquedas" placeholder="Fecha fin" />
            
            <input type="text" class="cajas" style="width:300px" id="txtCriterio" name="txtCriterio" placeholder="Buscar por servicio, cliente"/>
            
            <input type="hidden" id="txtModuloActivo" name="txtModuloActivo" value="ventaServicios"/>
            
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="cancelandoVentaServicios"></div>
	<div id="obtenerVentaServicios"></div>
</div>
</div>

<div id="ventanaEditarVentasServicios" title="Editar venta">
	<div id="editandoVentaServicio"></div>
    <div id="obtenerVentaServicioEditar"></div>
</div>

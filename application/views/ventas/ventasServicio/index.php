<script src="<?php echo base_url()?>js/ventas/catalogo/ventasServicio.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerVentasServicio();
	$('#txtInicio,#txtFin').datepicker({changeMonth: true, changeYear: true});
});
	
</script>

<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
    <!--<div class="seccionDiv">
        Ventas por servicio
    </div>-->
     <table class="toolbar" width="100%">
        <tr>
            <td align="center">
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text"  name="txtInicio" id="txtInicio" class="busquedas" style="width:100px;" onchange="obtenerVentasServicio()" value="<?php echo date('Y-01-01')?>"/>
                <input type="text"  name="txtFin" id="txtFin" class="busquedas" style="width:100px;" onchange="obtenerVentasServicio()" value="<?php echo date('Y-12-31')?>"/>
                <input type="hidden"  name="txtOrdenVentas" id="txtOrdenVentas" value="desc"/>
            </td>
        </tr>
      </table>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>

	<div id="obtenerVentasServicio">
    	<input type="hidden"  name="selectClientesBusqueda" id="selectClientesBusqueda" value="0"/>
    	<input type="hidden"  name="selectVentasBusqueda" id="selectVentasBusqueda" value="0"/>
        <input type="hidden"  name="selectProductosBusqueda" id="selectProductosBusqueda" value="0"/>
    </div>
</div>


</div>

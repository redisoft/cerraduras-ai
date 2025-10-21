<script src="<?php echo base_url()?>js/bibliotecas/barcode.js"></script>
<script src="<?php echo base_url()?>js/informacion.js"></script>
<script src="<?php echo base_url()?>js/ventas/procesadas.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventas.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>
<script src="<?php echo base_url()?>js/bancos/bancos.js"></script>
<script src="<?php echo base_url()?>js/ventas/sucursales.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/descuentos.js"></script>

<!--<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesAdministracion.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/clientesCotizaciones.js"></script>
<script src="<?php echo base_url()?>js/cotizaciones/cotizacionesClientes.js"></script>-->


<script >
$(document).ready(function()
{
	obtenerProcesadas();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
    <!--<div class="seccionDiv">
    	Ventas procesadas
    </div>-->
 <table class="toolbar" width="100%">
    <tr>
        <td align="center">
        	<input type="text"  name="txtBuscarVenta" id="txtBuscarVenta" class="busquedas" placeholder="Buscar cotización"  style="width:500px;" />
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
    
    <ul class="menuTabs">
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones'">Cotizaciones</li>
        <li onclick="window.location.href='<?php echo base_url()?>cotizaciones/desasignadas'">No asignadas</li>
        <li class="activado" >Procesadas</li>
    </ul>
    
	<div id="obtenerProcesadas"></div>
</div>


<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>

<div id="ventanaEditarVenta" title="Reutilizar venta">
<input type="hidden" id="txtRecargar" value="0" />
<div id="editandoVenta"></div>
<div id="obtenerVentaEditar"></div>
</div>




<div id="ventanaDetallesProducto" title="Detalles del producto">
<div id="cargarDetallesProducto"></div>
</div>

<div id="ventanaStockSucursales" title="Stock sucursales">
	<div id="obtenerStockSucursales"></div>
</div>

<div id="ventanaCobrosVentaEditar" title="Cobrar venta">
<div id="registrandoCobroVenta"></div>
<div id="formularioCobros"></div>
</div>

<div id="ventanaAsignarDescuento" title="Asignar descuento">
	<table class="admintable" width="100%">
    	<tr>
        	<td class="key">Descuento:</td>
            <td><input type="text" class="cajas" id="txtAsignarDescuento" value="0" onkeypress="return soloDecimales(event)" maxlength="6" /></td>
        </tr>
    </table>
</div>

</div>

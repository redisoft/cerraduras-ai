<script src="<?php echo base_url()?>js/tablero.js"></script>  
<script src="<?php echo base_url()?>js/crm.js"></script>  

<script src="<?php echo base_url()?>js/facturacion/folios.js"></script>  
<script src="<?php echo base_url()?>js/facturacion/facturaManual.js"></script>  

<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/crm.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/bitacora.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarSeguimiento.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarCrm.js"></script>  
<script src="<?php echo base_url()?>js/crm/clientes/contactos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/crm.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/contactos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/editarSeguimiento.js"></script>
<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js"></script>

<script src="<?php echo base_url()?>js/configuracion/estatus/catalogo.js"></script>

<script src="<?php echo base_url()?>js/informacion.js"></script>

<script>
$(document).ready(function()
{
	obtenerTablero();
	<?php
	
	if($this->session->userdata('mensajeRedisoft')=='1')
	{
		if(strlen($mensaje)>0)
		{
			echo '$("#ventanaRedisoft").dialog("open");';
		}
	}
	
   $this->session->set_userdata('mensajeRedisoft','0');
	
	?>
});
</script>

<div class="derecha">
<div class="submenu" >
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
    <table class="toolbar" width="100%">
    	<tr>
            <td class="toolbar">
                <a onclick="formularioFacturaManual()">
                	<img src="<?php echo base_url()?>img/xml.png" width="28" /><br />
                    Factura
                </a>
                
            </td>
    	</tr>
    </table>
</div>

<input type="hidden" id="txtFechaActual" value="<?php echo date('Y-m-d')?>" />
<input type="hidden" id="txtPermisoEditar" value="<?php echo $permiso[2]->activo?>" />
<input type="hidden" id="txtPermisoBorrar" value="<?php echo $permiso[3]->activo?>" />


<div class="listproyectos" align="center">

<div id="obtenerTablero"></div>


<div id="ventanaInformacionSeguimiento" title="Detalles de seguimiento">
    <div class="ui-state-error" ></div>
    <div id="procesandoSeguimiento"></div>
    <div id="cargarSeguimiento"></div>
</div>


<div id="ventanaCotizacionesInformacion" title="Detalles de cotización">
<div id="obtenerCotizacionInformacion"></div>
</div>

<div id="ventanaVentasInformacion" title="Detalles de venta">
<div id="obtenerVentaInformacion"></div>
</div>


<div id="ventanitaVentas" title="Detalles de venta">
<div id="errorVentitas" class="ui-state-error" ></div>
<div id="facturando"></div>
<div  id="cargarVentita"></div>
</div>

<div id="ventanitaCompras" title="Detalles de compra">
<div id="errorComprita" class="ui-state-error" ></div>
<div  id="cargarComprita"></div>
</div>

<div id="ventanaCobrosClientesTablero" title="Cobros a clientes:">
<div id="errorCobrosCliente" class="ui-state-error" ></div>
<div id="cargarPagosClientesTablero"></div>
</div>

<div id="ventanaPagosProveedor" title="Pagos de a proveedores:">
<div id="errorPagosProveedor" class="ui-state-error" ></div>
<div  id="cargarPagosProveedor"></div>
</div>

<div id="ventanaFacturasTablero" title="CFDI">
<div id="errorFacturasTablero" class="ui-state-error" ></div>
<div  id="cargarDetallesFactura"></div>
</div>


<div id="ventanaRedisoft" title="Notificaciones">
	<div id="errorFormularioPw" class="ui-state-error" ></div>
	<div style="font-size:20px; text-align:center">
    	<?php echo nl2br($mensaje)?>
    </div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
	<div id="obtenerCatalogoServicios"></div>
</div>

<div id="ventanaCatalogoStatus" title="Catálogo de CRM">
	<div id="obtenerCatalogoStatus"></div>
</div>

<div id="ventanaFacturaManual" title="Factura">
	<div id="registrandoFacturaManual"></div>
	<div id="formularioFacturaManual"></div>
</div>

<?php 
	$this->load->view('clientes/seguimiento/crmTablero/modales');
	$this->load->view('proveedores/seguimiento/crmTablero/modales');
?>

<div id="ventanaCatalogoEstatus" title="Catálogo de Estatus">
	<div id="obtenerCatalogoEstatus"></div>
</div>

</div>
</div>
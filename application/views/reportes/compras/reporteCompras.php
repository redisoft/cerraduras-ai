<script src="<?php echo base_url()?>js/reportes/compras.js"></script>
<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>
<script src="<?php echo base_url()?>js/administracion/comprobantesEgresos.js"></script>

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>


<!--<script src="<?php echo base_url()?>js/comprar.js"></script>	-->
<script type="text/javascript">
$(document).ready(function()
{
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			$('#txtIdProveedor').val(ui.item.idProveedor);
			obtenerCompras();
		}
	});
	
	obtenerCompras();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de compras
</div>
-->
 <table class="toolbar" width="100%">
    <tr>
     <td>
        <input value="<?php echo date('Y-m-01')?>" onchange="obtenerCompras()" name="FechaDia" type="text" title="Inicio" style="width:120px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
        <input value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" onchange="obtenerCompras()" name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:120px" class="busquedas" placeholder="Fecha fin" />
     </td>
     <td width="60%" style="padding-right:130px">
    <input type="text"  name="txtBuscarProveedor" id="txtBuscarProveedor" class="busquedas" placeholder="Seleccione proveedor"  style="width:500px;"/>
    <input type="hidden" id="txtIdProveedor" value="0" />
    
    <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
    <input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
    <?php
	if($this->session->userdata('proveedorReporte')!="")
	{
		echo 
		'<br />
		<a href="'.base_url().'reportes/busquedaProveedor" class="toolbar" style="margin-left:240px">
			<img src="'.base_url().'img/quitar.png" width="22px;" height="22px;" title="Borrar busqueda" />
		</a>';
	}
        ?>        
     </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos">

<div id="generandoReporte"></div>
<div id="obtenerCompras"></div>

<div id="ventanitaCompras" title="Detalles de compra">
    <div id="errorComprita" class="ui-state-error" ></div>
    <div  id="cargarComprita"></div>
</div>

<div id="ventanaComprobantesEgresos" title="Comprobantes gastos">
    <div id="registrandoComprobanteEgreso"></div>
    <div id="obtenerComprobantesEgresos"></div>
</div>


<?php $this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>

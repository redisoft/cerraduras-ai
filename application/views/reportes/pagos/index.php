<script src="<?php echo base_url()?>js/reportes/pagos.js"></script>	
<!--<script src="<?php echo base_url()?>js/comprar.js"></script>-->	
<script src="<?php echo base_url()?>js/informacion.js"></script>
	
<script type="text/javascript">
$(document).ready(function()
{
	$('#txtIdProveedor').val(0);
	$('#txtBuscarProveedor').val('');
	
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			$('#txtIdProveedor').val(ui.item.idProveedor);
			obtenerPagos();
		}
	});
	
	obtenerPagos();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">
<!--<div class="seccionDiv">
Reporte de pagos
</div>-->

 <table class="toolbar" width="100%">
    <tr>
     <td>
        <input value="<?php echo date('Y-m-01')?>" onchange="obtenerPagos()" name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
        <input value="<?php echo date('Y-m-'.$this->reportes->obtenerUltimaDiaFecha(date('Y-m-d')))?>" onchange="obtenerPagos()" name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" />
     </td>
     <td width="60%" style="padding-right:130px">
    <input type="text"  name="txtBuscarProveedor" id="txtBuscarProveedor" class="busquedas" placeholder="Seleccione proveedor"  style="width:300px;"/>
    <input type="hidden" id="txtIdProveedor" value="0" />
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
<div id="obtenerPagos"></div>




<div id="ventanaComprasInformacion" title="Detalles de compra">
<div id="obtenerCompraInformacion"></div>
</div>

</div>
</div>

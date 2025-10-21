<script type="text/javascript" src="<?php echo base_url()?>js/reportes/mobiliario.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerInventarioMobiliario',
		
		select:function( event, ui)
		{
			$('#txtIdInventario').val(ui.item.idInventario	);
			obtenerMobiliario();
		}
	});
	
	
	$("#txtBuscarProveedor").autocomplete(
	{
		source:base_url+'configuracion/obtenerProveedores',
		
		select:function( event, ui)
		{
			$('#txtIdProveedor').val(ui.item.idProveedor);
			obtenerMobiliario();
		}
	});
	
	$('#txtIdInventario').val(0);
	$('#txtBuscarProducto').val('');
	
	$('#txtIdProveedor').val(0);
	$('#txtBuscarProveedor').val('');

	obtenerMobiliario();
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Inventario mobiliario / equipo
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
            <input type="text"  name="txtBuscarProducto" id="txtBuscarProveedor" class="busquedas" placeholder="Seleccionar proveedor"  style="width:500px;"/>
            <input type="hidden" id="txtIdProveedor" value="0" />
            
            <input type="text"  name="txtBuscarProducto" id="txtBuscarProducto" class="busquedas" placeholder="Seleccionar producto"  style="width:500px;"/>
            <input type="hidden" id="txtIdInventario" value="0" />
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerMobiliario"></div>
</div>


</div>

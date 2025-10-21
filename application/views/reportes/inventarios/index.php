<script type="text/javascript" src="<?php echo base_url()?>js/informacion.js?v=<?=rand()?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/reportes/inventarios.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	/*$("#txtBuscarProducto").autocomplete(
	{
		source:base_url+'configuracion/obtenerProductosInventario',
		
		select:function( event, ui)
		{
			$('#txtIdProducto').val(ui.item.idProducto);
			obtenerInventarios();
		}
	});
	*/

	
	$('#txtIdProducto').val(0);
	$('#txtBuscarProducto').val('');
//	
//	$('#txtIdFactura').val(0);
//	$('#txtBuscarFactura').val('');
	obtenerInventarios();
	
	$("#txtMes").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
<!--<div class="seccionDiv">
Inventario productos
</div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td>
      
            <input type="text"  name="txtBuscarProducto" id="txtBuscarProducto" class="busquedas" placeholder="Buscar por código, producto"  style="width:600px;"/>
            <input type="hidden" id="txtIdProducto" value="0" />
            
            <select class="cajas" id="selectTiendas" name="selectTiendas" style="width:300px; display:none" onchange="obtenerInventarios()">
            	<option value="0">Matriz</option>
                
                <?php
                foreach($tiendas as $row)
				{
					echo '<option value="'.$row->idTienda.'">'.$row->nombre.'</option>';
				}
				?>
            </select>
        </td>
	</tr>
  </table>
</div>
</div>

<div class="listproyectos">
	<div id="generandoReporte"></div>
	<div id="obtenerInventarios"></div>
</div>

<div id="ventanaInformacionCompras" title="Detalles de inventario">
    <div id="obtenerInformacionCompras"></div>
</div>



</div>

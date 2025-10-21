<?php
$pendiente	= $producto->cantidadTotal - $entregados + $producto->cantidad;
?>
<form id="frmEditarEntrega">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Producto:</td>
			<td><?=$producto->producto?></td>
		</tr>	
		<tr>
			<td class="key">Cantidad:</td>
			<td>
				<input type="text" class="cajas" name="txtCantidadEditarEntregar" id="txtCantidadEditarEntregar" style="width: 120px" value="<?=round($producto->cantidad,decimales)?>" />
				<input type="hidden" 	name="txtCantidadPendiente" id="txtCantidadPendiente" value="<?=round($pendiente,decimales)?>" />
				<input type="hidden" 	name="txtCantidadEntregada" id="txtCantidadEntregada" value="<?=round($producto->cantidad,decimales)?>" />
				
				<input type="hidden" 	name="txtIdEntrega" id="txtIdEntrega" value="<?=$producto->idEntrega?>" />
				<input type="hidden" 	name="txtIdProductoEntrega" id="txtIdProductoEntrega" value="<?=$producto->idProducto?>" />
				<input type="hidden" 	name="txtIdProductoCotizacion" id="txtIdProductoCotizacion" value="<?=$producto->idProductoCotizacion?>" />
			</td>
		</tr>	
	</table>
</form>
<?php
echo '
<input type="hidden" id="txtNumeroMateriales" name="txtNumeroMateriales" value="'.count($materiales).'" />

<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="7">Detalles de materiales</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Material</th>
		<th>Unidad</th>
		<th>Conversión</th>
		<th>Inventario</th>
		<th>Cantidad requerida</th>
		<th>Orden de compra</th>
	</tr>';

$i	= 1;
foreach($materiales as $row)
{
	$compras	= $this->ordenes->obtenerDisponiblesComprasMateriales($row->idMaterial);
	$cantidad	= $row->cantidad*$cantidadOrden;
	
	if($row->idConversion>0 and $row->valor>0)
	{
		$cantidad	= (1/$row->valor)* ($row->cantidad * $cantidadOrden);
	}
	
	echo '
	<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		<td align="right">'.$i.'</td>
		<td>'.$row->nombre.'</td>
		<td align="center">'.$row->unidad.'</td>
		<td align="center">'.$row->conversion.'</td>
		<td align="right">'.number_format($row->cantidad,decimales).'</td>
		<td align="right">'.number_format($row->cantidad*$cantidadOrden,decimales).'</td>
			
			<input type="hidden" id="txtIdMaterial'.$i.'" 			name="txtIdMaterial'.$i.'" 	value="'.$row->idMaterial.'" />
			<input type="hidden" id="txtCantidad'.$i.'" 			name="txtCantidad'.$i.'" 	value="'.$row->cantidad.'" />
			<input type="hidden" id="txtCantidadRequerida'.$i.'" 	name="txtCantidadRequerida'.$i.'" 	value="'.$cantidad.'" />
		<td>';
			$c=1;
			foreach($compras as $compra)	
			{
				echo $c==1?'
				<input type="checkbox" id="chkCompra'.$i.'_'.$c.'" name="chkCompra'.$i.'_'.$c.'" value="'.$compra->idCompras.'" /> <label>'.$compra->nombre.'</label>, Disponible: '.number_format($compra->disponible,4):'
				<br />
				<input type="checkbox" id="chkCompra'.$i.'_'.$c.'" name="chkCompra'.$i.'_'.$c.'" value="'.$compra->idCompras.'" /> <label>'.$compra->nombre.'</label>, Disponible: '.number_format($compra->disponible,4);
				
				echo '
				<input type="hidden" id="txtCantidadOrden'.$i.'_'.$c.'" name="txtCantidadOrden'.$i.'_'.$c.'" value="'.$compra->disponible.'" />
				<input type="hidden" id="txtIdEntrada'.$i.'_'.$c.'" name="txtIdEntrada'.$i.'_'.$c.'" value="'.$compra->idEntrada.'" />';
				
				$c++;
			}
		
		echo'
			
			<input type="hidden" id="txtNumeroOrdenes'.$i.'" name="txtNumeroOrdenes'.$i.'" value="'.count($compras).'" />
		
		</td>
	</tr>';
	
	$i++;
}

echo '</table>';


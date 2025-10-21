<?php
$i=1;
echo'

<input type="hidden" id="txtModuloCompras" value="'.$modulo.'" />
<input type="hidden" id="txtIdCompraModulo" value="'.$compra->idCompras.'" />

<table class="admintable" width="100%">
	<tr>
		<td class="key">Fecha entrega:</td>
		<td>'.obtenerFechaMesCorto($compra->fechaEntrega).'</td>
	</tr>
	<tr>
		<td class="key">Orden de compra:</td>
		<td>'.$compra->nombre.'</td>
	</tr>
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$compra->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Domicilio:</td>
		<td>'.$compra->domicilio.'</td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td>'.$compra->telefono.'</td>
	</tr>
	<tr>
		<td class="key">Subtotal:</td>
		<td>$'.number_format($compra->subTotal,2).'</td>
	</tr>
	<tr>
		<td class="key">Descuento global:</td>
		<td>$'.number_format($compra->descuento,2).'</td>
	</tr>
	<tr>
		<td class="key">IVA:</td>
		<td>$'.number_format($compra->iva,2).'</td>
	</tr>
	<tr>
		<td class="key">Total:</td>
		<td>$'.number_format($compra->total,2).'</td>
	</tr>
</table>';

if($productos!=null)
{
	echo'
	<table class="admintable" style="margin-top:3px; width:100%">
		<tr>
			<th>#</th>
			<th>Producto</th>
			<th>Cantidad</th>
			<th align="right">Precio</th>
			<th align="right">Descuento unitario</th>
			<th align="right">Total</th>
		</tr>';
	
	foreach($productos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
	
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td>'.$row->nombre.'</td>
			<td align="center">'.$row->cantidad.'</td>
			<td align="right">$'.number_format($row->precio,2).'</td>
			<td align="right">$'.number_format($row->descuento,2).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
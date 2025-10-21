<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Orden de venta</td>
		<td>'.$cotizacion->ordenCompra.' '.($cotizacion->idTienda>0?'('.$cotizacion->tienda.')':'').'</td>
	</tr>
	<tr>
		<td class="key">Empresa</td>
		<td>'.$cliente->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Teléfono</td>
		<td>'.$cliente->telefono.'</td>
	</tr>';

echo'</table>';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Código</th>
		<th>Descripción</th>
		<th>UM</th>
		<th>Cantidad</th>		
		<th>Precio</th>
		<th>Descuento</th>
		<th>Importe</th>
	</tr>';

$i=0;

foreach($productos as $row)
{
	$estilo		= $i%2>0?"class='sinSombra'":'class="sombreado"';
	$nombre		= strlen($row->nombre)>2?$row->nombre:$row->producto;
	
	$cantidad=$row->cantidad-$row->devueltos;
	
	echo'
	<tr '.$estilo.'>
		<td>'.$row->codigoInterno.'</td>
		<td>'.$nombre.'</td>
		<td>'.$row->unidad.'</td>
		<td align="center">'.number_format($row->cantidad,2).'</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right">$'.number_format($row->descuento,2).'</td>
		<td align="right">$'.number_format($row->importe,2).'</td>
	</tr>';
	
	$i++;
}

echo'
<tr>
	<td colspan="6" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($cotizacion->subTotal,2).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Descuento</td>
	<td align="right">$'.number_format($cotizacion->descuento,2).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Impuestos</td>
	<td align="right">$'.number_format($cotizacion->iva,2).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($cotizacion->total,2).'</td>
</tr>';

echo '</table>';

echo '<input type="hidden" id="txtIdFactura" value="'.$cotizacion->idFactura.'" />';
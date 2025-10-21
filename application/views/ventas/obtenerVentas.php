<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Orden de venta</td>
		<td>'.$cotizacion->ordenCompra.'</td>
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
		<th>Cantidad</th>
		<th>Descripción</th>
		<th>Precio</th>
		<th>Importe</th>
	</tr>';

$i=0;

foreach($productos as $row)
{
	$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';

	$nombre=strlen($row->nombre)>2?$row->nombre:$row->producto;
	
	echo'
	<tr '.$estilo.'>
		<td align="center">'.number_format($row->cantidad,2).'</td>
		<td>'.$nombre.'</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right">$'.number_format($row->importe,2).'</td>
	</tr>';
	
	$i++;
}

$descuento		=$cotizacion->subTotal*($cotizacion->descuento/100);
$suma			=$cotizacion->subTotal-$descuento;
$iva			=$suma*$cotizacion->iva;
$total			=$suma+$iva;

echo'
<tr>
	<td colspan="3" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($cotizacion->subTotal,2).'</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right" class="totales">Descuento '.number_format($cotizacion->descuento,2).'%</td>
	<td align="right">$'.number_format($descuento,2).'</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right" class="totales">IVA '.number_format($cotizacion->iva*100,2).'%</td>
	<td align="right">$'.number_format($iva,2).'</td>
</tr>
<tr>
	<td colspan="3" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($cotizacion->total,2).'</td>
</tr>';

echo '</table>';

echo '<input type="hidden" id="txtIdFactura" value="'.$cotizacion->idFactura.'" />';
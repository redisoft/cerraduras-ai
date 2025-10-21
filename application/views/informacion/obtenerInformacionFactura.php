<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cliente</td>
		<td>'.$factura->empresa.'</td>
	</tr>
	<tr>
		<td class="key">RFC</td>
		<td>'.$factura->rfc.'</td>
	</tr>
	<tr>
		<td class="key">Serie y folio</td>
		<td>'.$factura->serie.$factura->folio.'</td>
	</tr>';

echo'</table>';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Cantidad</th>
		<th>UM</th>
		<th>Descripción</th>
		<th>Precio</th>
		<th>Importe</th>
	</tr>';

$i=0;

foreach($productos as $row)
{
	$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';

	echo'
	<tr '.$estilo.'>
		<td align="center">'.number_format($row->cantidad,2).'</td>
		<td align="center">'.$row->unidad.'</td>
		<td width="50%">'.$row->nombre.'</td>
		<td align="right">$'.number_format($row->precio,2).'</td>
		<td align="right">$'.number_format($row->importe,2).'</td>
	</tr>';
	
	$i++;
}

echo'
<tr>
	<td colspan="4" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($factura->subTotal,2).'</td>
</tr>

<tr>
	<td colspan="4" style="text-align:right" class="totales">IVA '.number_format($factura->iva*100,2).'%</td>
	<td align="right">$'.number_format($factura->iva*$factura->subTotal,2).'</td>
</tr>
<tr>
	<td colspan="4" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($factura->total,2).'</td>
</tr>';

echo '</table>';

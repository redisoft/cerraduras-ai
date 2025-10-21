<?php
echo'
<input type="hidden" id="txtAcceso" value="'.$acceso.'" />
<input type="hidden" id="txtIdFacturaAcceso" value="'.$factura->idFactura.'" />

<table class="admintable" width="100%">
	<tr>
		<td class="key">Factura:</td>
		<td>'.$factura->serie.$factura->folio.'</td>
	</tr>
	<tr>
		<td class="key">Empresa:</td>
		<td>'.$cliente->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td>'.$cliente->telefono.'</td>
	</tr>';

echo'</table>';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Código</th>
		<th>Descripción</th>
		<th>Cantidad</th>
		<th>Unidad</th>
		<th>Precio</th>
		<th>Importe</th>
	</tr>';

$i=0;

foreach($productos as $row)
{
	$estilo	= $i%2>0?"class='sombreado'":'class="sinSombra"';

	echo'
	<tr '.$estilo.'>
		<td align="center">'.$row->codigoInterno.'</td>
		<td>'.$row->nombre.'</td>
		<td align="center">'.number_format($row->cantidad,decimales).'</td>
		<td>'.$row->unidad.'</td>
		<td align="right">$'.number_format($row->precio,decimales).'</td>
		<td align="right">$'.number_format($row->importe,decimales).'</td>
	</tr>';
	
	$i++;
}

$descuento=$factura->subTotal*($factura->descuento/100);
$suma=$factura->subTotal-$descuento;
$iva=$suma*$factura->iva;
$total=$suma+$iva;

echo'
<tr>
	<td colspan="5" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($factura->subTotal,decimales).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">Descuento '.number_format($factura->descuento,decimales).'%</td>
	<td align="right">$'.number_format($descuento,decimales).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">IVA '.number_format($factura->ivaPorcentaje,decimales).'%</td>
	<td align="right">$'.number_format($factura->iva,decimales).'</td>
</tr>
<tr>
	<td colspan="5" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($factura->total,decimales).'</td>
</tr>';

echo '</table>';
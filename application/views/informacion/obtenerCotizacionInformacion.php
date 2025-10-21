<?php
echo'
<input type="hidden" id="txtIdCotizacionInformacion" value="'.$cotizacion->idCotizacion.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Serie:</td>
		<td>'.$cotizacion->serie.'</td>
	</tr>
	<tr>
		<td class="key">Cliente:</td>
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
		<th>UM</th>
		<th>Cantidad</th>		
		<th>Precio</th>
		<th>Descuento</th>
		<th>Importe</th>
	</tr>';

$i=0;

foreach($productos as $row)
{
	$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';

	$nombre=strlen($row->nombre)>2?$row->nombre:$row->producto;
	
	echo'
	<tr '.$estilo.'>
		<td align="center">'.$row->codigoInterno.'</td>
		<td>'.$nombre.'</td>
		
		<td align="center">'.$row->unidad.'</td>
		<td align="center">'.number_format($row->cantidad,decimales).'</td>
		<td align="right">$'.number_format($row->precio,decimales).'</td>
		<td align="right">$'.number_format($row->descuento,decimales).'</td>
		<td align="right">$'.number_format($row->importe,decimales).'</td>
	</tr>';
	
	$i++;
}

echo'
<tr>
	<td colspan="6" style="text-align:right" class="totales">Subtotal</td>
	<td align="right">$'.number_format($cotizacion->subTotal,2).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Descuento '.number_format($cotizacion->descuentoPorcentaje,decimales).'%</td>
	<td align="right">$'.number_format($cotizacion->descuento,decimales).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Impuestos</td>
	<td align="right">$'.number_format($cotizacion->iva,decimales).'</td>
</tr>
<tr>
	<td colspan="6" style="text-align:right" class="totales">Total</td>
	<td align="right">$'.number_format($cotizacion->total,decimales).'</td>
</tr>';

echo '</table>';

echo '<input type="hidden" id="txtIdFactura" value="'.$cotizacion->idFactura.'" />';
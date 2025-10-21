<?php
echo'
<input type="hidden" id="txtAcceso" value="'.$acceso.'" />
<input type="hidden" id="txtIdFacturaAcceso" value="'.$factura->idFactura.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">CFDI:</td>
		<td>'.$factura->serie.$factura->folio.'</td>
	</tr>
	<tr>
		<td class="key">Empleado:</td>
		<td>'.$empleado->nombre.'</td>
	</tr>
	<tr>
		<td class="key">CURP:</td>
		<td>'.$empleado->curp.'</td>
	</tr>
	<tr>
		<td class="key">Fecha inicial pago:</td>
		<td>'.obtenerFechaMesCorto($empleado->fechaInicialPago).'</td>
	</tr>
	<tr>
		<td class="key">Fecha final pago:</td>
		<td>'.obtenerFechaMesCorto($empleado->fechaFinalPago).'</td>
	</tr>
	<tr>
		<td class="key">Total:</td>
		<td>$'.number_format($factura->total,2).'</td>
	</tr>';

echo'</table>';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th colspan="4">Percepciones</th>
	</tr>
	
	<tr>
		<th>Clave</th>
		<th>Concepto</th>
		<th>Importe gravado</th>
		<th>Importe exento</th>
	</tr>';

$i=0;

foreach($percepciones as $row)
{
	$estilo	= $i%2>0?"class='sombreado'":'class="sinSombra"';

	echo'
	<tr '.$estilo.'>
		<td align="center">'.$row->clave.'</td>
		<td>'.$row->concepto.'</td>
		<td align="right">$'.number_format($row->importeGravado,2).'</td>
		<td align="right">$'.number_format($row->importeExento,2).'</td>
	</tr>';
	
	$i++;
}

	echo '
	<tr>
		<th colspan="4">Deducciones</th>
	</tr>
	
	<tr>
		<th>Clave</th>
		<th>Concepto</th>
		<th>Importe gravado</th>
		<th>Importe exento</th>
	</tr>';
	
	$i=0;

foreach($deducciones as $row)
{
	$estilo	= $i%2>0?"class='sombreado'":'class="sinSombra"';

	echo'
	<tr '.$estilo.'>
		<td align="center">'.$row->clave.'</td>
		<td>'.$row->concepto.'</td>
		<td align="right">$'.number_format($row->importeGravado,2).'</td>
		<td align="right">$'.number_format($row->importeExento,2).'</td>
	</tr>';
	
	$i++;
}


echo '</table>';
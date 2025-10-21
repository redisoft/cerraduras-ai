<?php
#if($ingresos!=null)
{
	$totalMonto = 0;
	$totalAdeudo = 0;
	
	echo'
	<div class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th colspan="8" class="encabezadoPrincipal">
				Créditos y prestamos vigentes
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fuente</th>
			<th>Monto financiado</th>
			<th>Interés anual</th>
			<th>Adeudo actual</th>
			<th>Frecuencia de pago</th>
			<th>Siguiente fecha de pago</th>
			<th>Monto de siguiente pago</th>
		</tr>';
	
	$i=1;
	
	foreach($creditos as $row)
	{
		$totalMonto+=	$row->monto;
		$totalAdeudo+=	$row->adeudoActual;
		
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td>'.$row->fuente.'</td>
			<td align="right">$'.number_format($row->monto,decimales).'</td>
			<td align="center">$'.number_format($row->interesAnual,decimales).'%</td>
			<td align="right">$'.number_format($row->adeudoActual,decimales).'</td>
			<td>'.$row->frecuencia.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaPago).'</td>
			<td align="right">$'.number_format($row->pago,decimales).'</td>
		</tr>';

		$i++;
	}
	
	echo '
		<tr>
			<td class="totales" colspan="2">Total</td>
			<td class="totales" align="right">$'.number_format($totalMonto,decimales).'</td>
			<td></td>
			<td class="totales" align="right">$'.number_format($totalAdeudo,decimales).'</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table></div>';
}

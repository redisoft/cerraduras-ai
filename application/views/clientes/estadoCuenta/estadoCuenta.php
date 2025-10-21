<?php

$total	= ($academicos->inscripcion*$academicos->cantidadInscripcion) + ($academicos->colegiatura*$academicos->cantidadColegiatura) + ($academicos->reinscripcion*$academicos->cantidadReinscripcion) + ($academicos->titulacion*$academicos->cantidadTitulacion);

echo'
<input type="hidden" id="txtIdClienteEstado" value="'.$cliente->idCliente.'" />
<table class="admintable" width="100%;">
	<tr>
		<th colspan="2"> Datos del alumno</th>
	</tr>
	<tr>
		<td class="key">Alumno:</td>
		<td>'.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno.'</td>
	</tr>
	<tr>
		<td class="key">Programa:</td>
		<td>'.$academicos->programa.'</td>
	</tr>
	<tr>
		<td class="key">Matrícula:</td>
		<td>'.$academicos->matricula.'</td>
	</tr>
	<tr>
		<td class="key">Periodo:</td>
		<td>'.$academicos->periodo.'</td>
	</tr>
	<tr>
		<td class="key">Total:</td>
		<td>$'.number_format($total,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Otros:</td>
		<td>$'.number_format($otrosPagos,decimales).'</td>
	</tr>
	
	<tr>
		<td class="key">Subtotal:</td>
		<td>$'.number_format($otrosPagos+$total,decimales).'</td>
	</tr>
	
	
	<tr>
		<td class="key">Pagos:</td>
		<td>$'.number_format($totalPagos,decimales).'</td>
	</tr>
	<tr>
		<td class="key">Saldo:</td>
		<td>$'.number_format($total-$totalPagos+$otrosPagos,decimales).'</td>
	</tr>
	
</table>
	
<table class="admintable" width="100%;">
	<tr>
		<th colspan="5"> Pagos</th>
	</tr>
	<tr>
		<th align="right" width="4%">#</th>
		<th align="center" width="15%">Fecha</th>
		<th align="left">Concepto</th>
		<th align="left">Forma de pago</th>
		<th align="right" width="20%">Importe</th>
	</tr>';
	
	$i=1;
	foreach($pagos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
		 <td align="right">'.$i.'</td>
		 <td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
		 <td align="left">'.$row->producto.'</td>
		 <td align="left">'.$row->forma.'<br />'.$row->banco.'</td>
		 <td align="right">$'.number_format($row->pago,decimales).'</td>
		</tr>';
		
		$i++;
	}
	
echo'</table>';
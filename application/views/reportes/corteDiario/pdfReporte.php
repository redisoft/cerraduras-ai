<?php
$this->load->view('reportes/corteDiario/encabezado');

echo '
<table class="admintable" width="100%" >
	<tr>
		<th>Factura</th>
		<th></th>
		<th>Contado</th>
		<th>Crédito</th>
	</tr>

	<tr>
		<td align="center">Folio inicial: '.$facturas->folioMenor.'</td>
		<td align="center">Folio final: '.$facturas->folioMayor.'</td>
		<td align="right">$'.number_format($facturas->total,2).'</td>
		<td align="right">$'.number_format(0,2).'</td>
	</tr>';

$i		= 1;
$totalContado = 0;
$totalCredito = 0;
foreach($remisiones as $row)
{
	$contado	= $this->reportes->obtenerTotalCorteContado($fecha,$row->idEstacion);
	$credito	= $this->reportes->obtenerTotalCorteCredito($fecha,$row->idEstacion);
	
	echo '
	<tr>
		<th width="25%">Remisiones '.$row->estacion.'</th>
		<th width="25%"></th>
		<th width="25%">Contado</th>
		<th width="25%">Crédito</th>
	</tr>
	<tr>
		<td align="center">Folio inicial: '.$row->folioMenor.'</td>
		<td align="center">Folio final: '.$row->folioMayor.'</td>
		<td align="right">$'.number_format($contado,2).'</td>
		<td align="right">$'.number_format($credito,2).'</td>
	</tr>';
	
	$totalContado+=$contado;
	$totalCredito+=$credito;
	
	$i++;
}

foreach($prefacturas as $row)
{
	$contado	= $this->reportes->obtenerTotalPrefacturaContado($fecha,$row->idEstacion);
	$credito	= $this->reportes->obtenerTotalPrefacturaCredito($fecha,$row->idEstacion);
	
	echo '
	<tr>
		<th>Prefacturas '.$row->estacion.'</th>
		<th></th>
		<th>Contado</th>
		<th>Crédito</th>
	</tr>
	<tr>
		<td align="center">Folio inicial: '.$row->folioMenor.'</td>
		<td align="center">Folio final: '.$row->folioMayor.'</td>
		<td align="right">$'.number_format($contado,2).'</td>
		<td align="right">$'.number_format($credito,2).'</td>
	</tr>';
	
	$totalContado+=$contado;
	$totalCredito+=$credito;
	
	$i++;
}



echo '

	<tr>
		<td class="totales">TOTALES</td>
		<td></td>
		<td class="totales" align="right">'.number_format($totalContado,2).'</td>
		<td class="totales" align="right">'.number_format($totalCredito,2).'</td>
	</tr>
 </table>';
?>
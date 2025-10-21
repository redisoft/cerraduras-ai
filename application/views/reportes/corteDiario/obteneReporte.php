
<?php

echo '
<table class="admintable" width="100%" >
	<tr>
		<th class="encabezadoPrincipal" colspan="2"  style="text-align: right">
			Reporte de ventas del día '.obtenerFechaMesCorto($fecha).'
		</th>
		<th class="encabezadoPrincipal"  colspan="2" style="text-align: left">
			<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" title="PDF" onclick="pdfReporteCorte()" />
			<br>
			PDF
		</th>
	</tr>
	<tr>
		<th width="30%">Factura</th>
		<th width="20%"></th>
		<th width="20%">Contado</th>
		<th width="30%">Crédito</th>
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
		<th>Remisiones '.$row->estacion.'</th>
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
		<td class="totales" align="right">$'.number_format($totalContado,2).'</td>
		<td class="totales" align="right">$'.number_format($totalCredito,2).'</td>
	</tr>
 </table>';



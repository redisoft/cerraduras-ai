<?php
$this->load->view('reportes/panaderos/encabezado');

if($pedidos!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($pedidos as $row)
	{
		$reporte	= $this->pedidos->obtenerReportePedido($row->idPedido);
		$total		= $this->pedidos->obtenerTotalesPedido($row->idPedido);
		$impuestos	= $this->pedidos->obtenerImpuestosPedido($row->idPedido);
		
		$linea='';
		if($row->idLinea==2) $linea=frances;
		if($row->idLinea==3)  $linea=bizcocho;
		
		echo '
		<tr>
			<td width="3%" align="right">'.$i.'</td>
			<td width="6%" align="center">'.obtenerFechaMesCorto($row->fechaPedido).'</td>
			<td width="10%" align="left">'.$row->linea.'</td>
			<td width="10%" align="center">'.$linea.$row->folio.'</td>
			<td width="8%" align="right">$'.number_format($total+$impuestos,decimales).'</td>
			<td width="7%" align="right">$'.number_format($reporte!=null?$reporte->manoTotal:0,decimales).'</td>
			
			<td width="7%" align="right">$'.number_format($reporte!=null?$reporte->maestro:0,decimales).'</td>
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->maestro*$reporte->cuotaSindical/100:0,decimales).'</td>
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->maestro*$reporte->primaDominical/100:0,decimales).'</td>
			
			<td width="7%" align="right">$'.number_format($reporte!=null?$reporte->oficial:0,decimales).'</td>
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->oficial*$reporte->cuotaSindical/100:0,decimales).'</td>
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->oficial*$reporte->primaDominical/100:0,decimales).'</td>
			
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->cuotaTotal:0,decimales).'</td>
			<td width="6%" align="right">$'.number_format($reporte!=null?$reporte->primaTotal:0,decimales).'</td>
			<td width="7%" align="right">$'.number_format($reporte!=null?$reporte->manoTotal+$reporte->primaTotal-$reporte->cuotaTotal:0,decimales).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
?>
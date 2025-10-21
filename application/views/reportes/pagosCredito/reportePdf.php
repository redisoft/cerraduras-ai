<?php
if($ingresos!=null)
{
	$this->load->view('reportes/pagosCredito/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($ingresos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		$banco 		= explode('|',$row->banco);

		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="10%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="26%" align="center">'.$row->cliente.'</td>
			<td width="10%">'.$row->estacion.'-'.$row->folio.'</td>
			<td width="10%">'.$row->forma.'</td>
			<td width="10%" align="center">'.(strlen($row->banco)>3?$banco[1]:'').'</td>
			<td width="10%" align="center">'.(strlen($row->banco)>3?$banco[0]:'').'</td>
			<td width="7%" align="center">'.($factura!=null?$factura->cfdi:$row->factura).'</td>
			<td width="7%" align="right">$'.number_format($row->total,2).'</td>
			<td width="7%" align="right">$'.number_format($row->pago,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ingresos</div>';
}
?>
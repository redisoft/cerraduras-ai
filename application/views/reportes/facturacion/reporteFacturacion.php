<?php
$this->load->view('reportes/facturacion/encabezado');
if($facturas!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($facturas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=$row->cancelada==1?'<i> (Cancelada)</i>':'';
		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="9%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="10%" align="center">'.$row->documento.'</td>
			<td width="17%" align="center">'.$row->emisor.'</td>
			<td width="17%" align="left">'.$row->empresa.$cancelada.'</td>
			<td width="10%" align="center">'.($row->pendiente=='1'?'':$row->serie.$row->folio.$cancelada).'</td>
			
			<td width="8%" align="left">'.$row->estacion.'</td>
			
			<td width="10%" align="right">$'.number_format($row->subTotal,2).'</td>
			<td width="6%" align="right">$'.number_format($row->iva,2).'</td>
			<td width="10%" align="right">$'.number_format($row->total,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de facturas</div>';
}
?>
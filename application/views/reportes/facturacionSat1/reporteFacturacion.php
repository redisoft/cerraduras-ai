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
		$parciales	=0;
		
		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="8%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="11%" align="center">'.$row->documento.'</td>
			<td width="24%" align="left">'.$row->empresa.'</td>
			
			<td width="15%" align="left">'.$row->emisor.'</td>
			
			<td width="13%" align="center">'.$row->serie.$row->folio.$cancelada.'</td>';
			
			echo'
			<td align="right" width="8%">';
			
			if($row->cancelada==0)
			{
				#if($row->parcial==1)
				#{
					$parciales	=$this->reportes->sumarFacturasParciales($row->idCotizacion);
					
					echo '$'.number_format($parciales,2);
				#}
			}
			
			echo'</td>
			<td align="right" width="8%">';
			if($row->cancelada==0)
			{
				#if($row->parcial==1)
				#{
					$cotizacion=$this->reportes->obtenerCotizacionFactura($row->idCotizacion)-$parciales;
					
					echo  '$'. number_format($cotizacion<0?0:$cotizacion,2);
				#}
			}
			
			echo'</td>
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
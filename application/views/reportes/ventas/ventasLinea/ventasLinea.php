<?php
if($ventas!=null)
{
	$this->load->view('reportes/ventas/ventasLinea/encabezado');
	
	echo '<table class="admintable" width="100%">';
	
	$cantidad	= 0;
	$importe	= 0;
	$i			= 1;
	
	foreach($ventas as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$cantidad	+= $row->cantidad;
		$importe	+= $row->importe;
		
		echo '
		<tr '.$estilo.'>
			<td width="3%" align="right">'.$i.'</td>
			<td width="50%" align="left">'.$row->departamento.'</td>
			<td width="22%" align="right">'.number_format($row->cantidad,2).'</td>
			<td width="25%" align="right">$'.number_format($row->importe,2).'</td>
		</tr>'; 
	
		$i++;
	}
	
	echo '
		<tr>
			<td colspan="2" align="right" class="totales">Total</td>
			<td align="right" class="totales">'.number_format($cantidad,2).'</td>
			<td align="right" class="totales">$'.number_format($importe,2).'</td>
		</tr>
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ventas</div>';
}
?>
<?php
$this->load->view('reportes/mobiliario/encabezado');

if($inventarios!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$i=1;
	foreach($inventarios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td width="5%" align="right">'.$i.'</td>
			<td width="30%" align="left">'.$row->nombre.'</td>
			<td width="20%" align="left">'.$row->empresa.'</td>
			<td width="15%" align="center">'.number_format($row->cantidad,2).'</td>
			<td width="15%" align="right">$'.number_format($row->costo,2).'</td>
			<td width="15%" align="right">$'.number_format($row->costo*$row->cantidad,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de mobiliario/equipo</div>';
}
?>
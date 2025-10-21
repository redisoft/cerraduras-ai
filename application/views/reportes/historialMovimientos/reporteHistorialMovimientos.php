<?php
if($movimientos!=null)
{
	$this->load->view('reportes/historialMovimientos/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($movimientos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';

		echo '
		<tr '.$estilo.'>
			<td width="5%" align="right">'.$i.'</td>
			<td width="10%" align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td width="5%" align="center">'.obtenerHora($row->fecha).'</td>
			<td width="10%" align="center">'.$row->nombre.'('.$row->usuario.')</td>
			<td width="15%" align="center">'.$row->modulo.'</td>
			<td width="15%" align="left">'.$row->accion.'</td>
			<td width="40%" align="left">'.$row->descripcion.'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de movimientos</div>';
}
?>
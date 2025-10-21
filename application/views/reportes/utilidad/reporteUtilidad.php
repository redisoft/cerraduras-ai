<?php
$this->load->view('reportes/utilidad/encabezado');

echo'
<table class="admintable" width="100%">
	<tr>
		<th width="15%">Mes</th>
		<th width="40%">Emisor</th>
		<th width="15%">Ingreso</th>
		<th width="15%">Gasto</th>
		<th width="15%">Utilidad</th>
	</tr>';
	
	$i		= 1;
	$mes	= substr($fecha,5,2);
	$anio	= substr($fecha,0,4);
	
	foreach($emisores as $row)
	{
		$gastos		= $this->reportes->obtenerGastosProveedoresMes($mes,$anio,$row->idEmisor);
		$ingreso	= $this->reportes->obtenerGastosClientesMes($mes,$anio,$row->idEmisor);
		
		echo '
		<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
			<td align="center" width="15%">'.obtenerFechaMesAnio($fecha).'</td>
			<td align="left"   width="40%">'.$row->nombre.'</td>
			<td align="right" width="15%">$'.number_format($ingreso,2).'</td>
			<td align="right" width="15%">$'.number_format($gastos,2).'</td>
			<td align="right" width="15%">$'.number_format($ingreso-$gastos,2).'</td>
			
		</tr>';
		
		$i++;
	}

echo'
</table>';

?>
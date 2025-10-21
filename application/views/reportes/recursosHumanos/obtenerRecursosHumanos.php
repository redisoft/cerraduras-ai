<?php
$totalEmpleados	= $activos+$inactivos;

echo'
<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="2">
			Recursos humanos
		</th>
	</tr>
	<tr>
		<td class="key">Total empleados:</td>
		<td>'.($activos+$inactivos).'</td>
	</tr>
	<tr>
		<td class="key">Empleados activos:</td>
		<td>'.($activos).'</td>
	</tr>
	<tr>
		<td class="key">Empleados inactivos:</td>
		<td>'.($inactivos).'</td>
	</tr>
</table>

<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="3">
			Documentos
		</th>
	</tr>
	<tr>
		<th>Documentos</th>
		<th>Total</th>
		<th>Faltante</th>
	</tr>';

	$i		= 1;

	foreach($documentos as $row)
	{
		$total	= $this->administracion->obtenerNumeroDocumentos($row->idTipo);
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$row->nombre.'</td>
			<td align="center">'.round($total,decimales).'</td>
			<td align="center">'.round($totalEmpleados-$total,decimales).'</td>
		</tr>';

		$i++;
	}

echo'
</table>';

?>

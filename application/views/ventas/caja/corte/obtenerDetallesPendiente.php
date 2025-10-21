
<table class="admintable" width="100%" style="float: left">
	<tr>
		<td class="key">Fecha:</td>
		<td><?=obtenerFechaMesCorto($fecha)?></td>
	</tr>
</table>


<table class="admintable" width="100%" style="float: left">
	<tr>
		<th colspan="5">Detalles de ventas por cobrar</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Cliente</th>
		<th>Nota</th>
		<th>Fecha venta</th>
		<th>Importe</th>
	</tr>

	<?php
	$i=1;
	foreach($registros as $row)
	{
		echo '
		<tr '.($i%2>0?"class='sinSombra'":'class="sombreado"').'>
			<td>'.$i.'</td>
			<td>'.$row->cliente.'</td>
			<td align="center">'.$row->estacion.'-'.$row->folio.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
		</tr>';
		
		$i++;
	}
	?>

</table>


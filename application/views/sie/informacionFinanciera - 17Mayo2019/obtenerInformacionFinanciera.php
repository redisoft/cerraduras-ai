
    <?php

	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="2" class="resaltadoIexe">Recurso disponible</th>
			<th rowspan="4" class="">$'.number_format($efectivo+$disponible,decimales).'</th>
		</tr>
		
		<tr>
			<td>Cuentas</td>
			<td align="right">$'.number_format($disponible,decimales).'</td>
		</tr>
		
		<tr>
			<td>Efectivo</td>
			<td align="right">$'.number_format($efectivo,decimales).'</td>
		</tr>
		
		<tr>
			<td>Total</td>
			<td align="right">$'.number_format($efectivo+$disponible,decimales).'</td>
		</tr>';
		
		$totalIngresosNoDisponible		= 0;
		$totalEgresosNoDisponible		= 0;
		
		foreach($noDisponible as $row)
		{
			$egresos					= $this->sie->obtenerEgresosNoDisponiblesCuenta($fechaFinanciera,$row->idBanco);
			
			$totalEgresosNoDisponible	+=$egresos;
			$totalIngresosNoDisponible	+=$row->total;
		}
		
		echo'
		<tr>
			<th colspan="2" class="resaltadoIexe">Recurso no disponible</th>
			
			<th rowspan="'.(count($noDisponible)+2).'" class="">$'.number_format($totalIngresosNoDisponible-$totalEgresosNoDisponible,decimales).'</th>
		</tr>';

		foreach($noDisponible as $row)
		{
			$egresos	= $this->sie->obtenerEgresosNoDisponiblesCuenta($fechaFinanciera,$row->idBanco);
			
			echo '
			<tr>
				<td>'.$row->banco.'</td>
				<td align="right">$'.number_format($row->total-$egresos,decimales).'</td>
			</tr>';
		}
		
		echo '
		<tr>
			<td>Total</td>
			<td align="right">$'.number_format($totalIngresosNoDisponible-$totalEgresosNoDisponible,decimales).'</td>
		</tr>
		
		
		<tr>
			<td colspan="2" class="totales">Total general</td>
			<td class="totales" align="center">$'.number_format($efectivo+$disponible+$totalIngresosNoDisponible-$totalEgresosNoDisponible,decimales).'</td>
		</tr>';
		
	echo'
	</table>';
	?>




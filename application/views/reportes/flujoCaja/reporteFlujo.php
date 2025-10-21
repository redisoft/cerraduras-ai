<?php
$this->load->view('reportes/flujoCaja/encabezado');

echo'
		<table class="admintable" width="100%">';
		
		echo '
		<tr>
			<th colspan="3">
				Saldo inicial
				
			</th>
		</tr>';
		
		$sumaCajas	=0;
		
		foreach($cajas as $row)
		{
			$ingreso		=$this->reportes->obtenerIngresoCajaChica($row->idProducto,$mes,$anio);
			$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
			$saldoInicial	=$ingreso-$egreso;
			
			$sumaCajas	+=$saldoInicial;
			echo '
			<tr>
				<td width="50%">'.$row->cajaChica.'</td>
				<td width="20%" align="right">$'.number_format($saldoInicial,2).'</td>
				<td width="30%"></td>
			</tr>';
		}
		
		echo '
			<tr>
				<th colspan="3">
					Entradas en caja chica
				</th>
			</tr>';
		
		
		foreach($cajas as $row)
		{
			$entradas		=$this->administracion->obtenerEntradasCaja($row->idProducto,$mes,$anio);
			#$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
			#$saldoInicial	=$ingreso-$egreso;

			$sumaCajas	+=$entradas;
			
			echo '
			<tr>
				<td>'.$row->cajaChica.'</td>
				<td align="right">$'.number_format($entradas,2).'</td>
				<td></td>
			</tr>';
		}
		
		echo '
		<tr>
			<td class="totales" colspan="2">Suma de entradas en caja</td>
			<td class="totales" align="right">$'.number_format($sumaCajas,2).'</td>
		</tr>';
		
		
		$salidas	=$this->administracion->obtenerSalidasCaja($mes,$anio);
		echo '
		<tr>
			<th colspan="3">Salidas en caja chica</th>
		</tr>';
		
		$salida	=0;
		$i=1;
		foreach($salidas as $row)
		{
			$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
			
			$salida	+=$row->importe;
			echo '
			<tr '.$estilo.'>
				<td>'.$row->concepto.'</td>
				<td align="right">$'.number_format($row->importe,2).'</td>
				<td></td>
			</tr>';
			
			$i++;
		}
		
		echo '
		<tr>
			<td class="totales" colspan="2">Suma de salidas en caja</td>
			<td class="totales" align="right">$'.number_format($salida,2).'</td>
		</tr>
		
		<tr>
			<td class="totales" colspan="2">Entradas menos salidas</td>
			<td class="totales" align="right">$'.number_format($sumaCajas-$salida,2).'</td>
		</tr>';
		
		echo '
		<tr>
			<th colspan="3">Saldos en caja</th>
		</tr>';
		
		$saldos	=0;
		$saldo=0;
		#$mes=04;
		foreach($cajas as $row)
		{
			$entradas		=$this->administracion->obtenerEntradasCaja($row->idProducto,$mes,$anio);
			$salida	=$this->administracion->obtenerSalidaCaja($mes,$anio,$row->idProducto);
			#$saldo	=$row->pago-$saldo;
			
			$ingreso		=$this->reportes->obtenerIngresoCajaChica($row->idProducto,$mes,$anio);
			$egreso			=$this->reportes->obtenerEgresoCajaChica($row->idProducto,$mes,$anio);
			$saldo			=$ingreso-$egreso;

			$saldo	=$entradas+$saldo-$salida;
			#$sumaCajas	+=$saldoInicial;
			
			$saldos	+=$saldo;
			
			echo '
			<tr>
				<td>'.$row->cajaChica.'</td>
				<td align="right">$'.number_format($saldo,2).'</td>
				<td></td>
			</tr>';
		}
		
		echo '
		<tr>
			<td class="totales" colspan="2">Suma de saldos en caja</td>
			<td class="totales" align="right">$'.number_format($saldos,2).'</td>
		</tr>';
		
		echo '</table>';
?>
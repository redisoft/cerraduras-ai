<?php
$this->load->view('contabilidad/balanza/reportes/encabezado');

if($cuentas!=null)
{
	echo '<table class="admintable" width="100%">';
	
	$totalSaldos	= 0;
	$totalDebe		= 0;
	$totalHaber		= 0;
	
	$i=1;
	foreach($cuentas as $row)
	{
		$saldo	= $row->saldo;
		$debe	= $row->debe;
		$haber	= $row->haber;
	
		if($row->cuentasHijo>0)
		{
			$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo);
			$saldo	= $saldos[0];
			$debe	= $saldos[1];
			$haber	= $saldos[2];
		}
		
		$totalSaldos	+= $saldo;
		$totalDebe		+= $debe;
		$totalHaber		+= $haber;
		
		$mostrar		= false;
		if($filtro==0 and ($saldo>0 or $debe>0 or $haber>0))
		{
			$mostrar=true;
		}
		
		if($filtro==1 and $saldo==0 and $debe==0 and $haber==0)
		{
			$mostrar=true;
			
			$totalSaldos	= 0;
			$totalDebe		= 0;
			$totalHaber		= 0;
		}
		
		if($filtro==2)
		{
			$mostrar=true;
		}
		
		if($mostrar)
		{
			echo '
			<tr>
				<td width="20%" align="left">'.$row->numeroCuenta.'</td>
				<td width="20%" align="left">'.$row->descripcion.'</td>
				<td width="20%" align="right">$'.number_format($saldo,decimales).'</td>
				<td width="10%" align="right">$'.number_format($debe,decimales).'</td>
				<td width="10%" align="right">$'.number_format($haber,decimales).'</td>
				<td width="20%" align="right">$'.number_format($saldo+$debe-$haber,decimales).'</td>
			</tr>';
		}
		
		if($filtro!=3)
		{
			if($row->cuentasHijo>0)
			{
				$this->contabilidad->obtenerCuentasBalanzaVista($row->idCuentaCatalogo,2,$filtro);
			}
		}
		
		$i++;
	}
	
	echo '
		<tr>
			<td width="40%" class="totales" colspan="2" align="right">Totales</td>
			<td width="20%" class="totales" align="right">$'.number_format($totalSaldos,decimales).'</td>
			<td width="10%" class="totales" align="right">$'.number_format($totalDebe,decimales).'</td>
			<td width="10%" class="totales" align="right">$'.number_format($totalHaber,decimales).'</td>
			<td width="20%" class="totales" align="right">$'.number_format($totalSaldos+$totalDebe-$totalHaber,decimales).'</td>
		</tr>
		
		<tr>
			<td class="totales" colspan="2" align="right">Diferencias</td>
			<td class="totales" align="right"></td>
			<td class="totales" align="right">$'.number_format($totalDebe-$totalHaber,decimales).'</td>
			<td class="totales" align="right"></td>
			<td class="totales" align="right"></td>
		</tr>
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
?>
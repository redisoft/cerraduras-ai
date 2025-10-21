<?php
$numero		= $numero+17;

foreach($cuentas as $row)
{
	$i		= 1;
	$saldo	= $row->saldo;
	$debe	= $row->debe;
	$haber	= $row->haber;
	
	if($row->cuentasHijo>0)
	{
		$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo,0);
		$saldo	= $saldos[0];
		$debe	= $saldos[1];
		$haber	= $saldos[2];
	}
	
	$mostrar		= false;
	if($filtro==0 and ($saldo>0 or $debe>0 or $haber>0))
	{
		$mostrar=true;
	}
	
	if($filtro==1 and $saldo==0 and $debe==0 and $haber==0)
	{
		$mostrar=true;
	}
	
	if($mostrar)
	{
		echo'
		<tr id="filaCuenta'.$row->idCuentaCatalogo.'">
			<td align="left" style="border-bottom:none; border-top: none; padding-left: '.$numero.'px;">'.$row->numeroCuenta.'</td>
			<td align="left" style="border-bottom:none; border-top: none">'.$row->descripcion.'</td>
			<td align="right" style="border-bottom:none; border-top: none">$'.number_format($saldo,decimales).'</td>
			<td align="right" style="border-bottom:none; border-top: none">$'.number_format($debe,decimales).'</td>
			<td align="right" style="border-bottom:none; border-top: none">$'.number_format($haber,decimales).'</td>
			<td align="right" style="border-bottom:none; border-top: none">$'.number_format($saldo+$debe-$haber,decimales).'</td>
		</tr>';
	}

	$i++;
	
	if($row->cuentasHijo>0)
	{
		$this->contabilidad->obtenerCuentasBalanzaVista($row->idCuentaCatalogo,$numero,$filtro);
	}
}
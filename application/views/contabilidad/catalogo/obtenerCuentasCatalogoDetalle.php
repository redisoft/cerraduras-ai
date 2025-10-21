<?php
$numero=$numero+17;

foreach($cuentas as $row)
{
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
	
	$i=1;
	echo'
	<tr id="filaCuenta'.$row->idCuentaCatalogo.'">
		<!--<td class="numeral	"></td>-->
		<td align="right" colspan="2" style="border-bottom:none; border-top: none">'.obtenerFechaMesCorto($row->fecha).'</td>
		<td align="left" style="padding-left: '.$numero.'px; border-bottom:none; border-top: none" >'; 

			echo $row->cuenta.' ('.$row->codigoAgrupador.')';
		
		echo'
		</td>
		<td align="left" style="border-bottom:none; border-top: none">'.$row->numeroCuenta.'</td>
		<td align="left" style="border-bottom:none; border-top: none">'.$row->descripcion.'</td>
		<!--<td align="left">'.$row->subCuenta.'</td>-->
		<td align="center" style="border-bottom:none; border-top: none">'.$row->nivel.'</td>
		<td align="center" style="border-bottom:none; border-top: none">'.($row->naturaleza=='A'?'Acreedora':'Deudora').'</td>
		<td align="right" style="border-bottom:none; border-top: none">$'.number_format($saldo+$debe-$haber,decimales).'</td>
		<td class="vinculos" style="border-bottom:none; border-top: none">
			<img src="'.base_url().'img/editar.png" title="Editar cuenta" onclick="obtenerCuenta('.$row->idCuentaCatalogo.')" />
			
			&nbsp;
			<img src="'.base_url().'img/add.png" title="Agregar cuenta" onclick="formularioAgregarCuenta('.$row->idCuentaCatalogo.')" />
			
			&nbsp;&nbsp;
			<img src="'.base_url().'img/borrar.png" title="Borrar cuenta" onclick="borrarCuenta('.$row->idCuentaCatalogo.')" />
			
			<br />
			Editar
			Cuenta
			Borrar
		</td>
	</tr>';
	
	if($row->cuentasHijo>0)
	{
		$this->contabilidad->obtenerCuentasCatalogoDetalleVista($row->idCuentaCatalogo,$numero);
	}
	
	$i++;
}
	


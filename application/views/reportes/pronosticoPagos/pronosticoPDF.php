<?php

$this->load->view('reportes/pronosticoPagos/encabezado');

if($proveedores!=null)
{
	echo '
	<table class="admintable" width="100%">';
	$i=1;
	foreach($proveedores as $row)
	{
		echo'
		<tr>
			<td class="totales" align="left" colspan="8">'.$i.' '.$row->empresa.'</td>
		</tr>';
		
		$compras	=$this->reportes->obtenerComprasProveedor($fechaInicio,$fechaFin,$row->idProveedor);
		#$compras=$this->reportes->obtenerAuxiliar($fechaInicio,$fechaFin,$row->idProveedor);
		
		$total	=0;
		$total1	=0;
		$total8	=0;
		$total14=0;
		$total22=0;
		
		foreach($compras as $compra)
		{
			$pagado		=$this->reportes->sumarPagadoCompra($compra->idCompras);
			$saldo		=$compra->total-$pagado;
			$pronostico	=$this->compras->obtenerDiferenciaFecha($compra->fechaCompra);
			
			$pro1		=0;
			$pro8		=0;
			$pro14		=0;
			$pro22		=0;
			
			switch($pronostico)
			{
				case $pronostico>=1 and $pronostico<=7:
				$pro1	=$saldo;
				break;
				
				case $pronostico>=8 and $pronostico<=14:
				$pro8	=$saldo;
				break;
				
				case $pronostico>=15 and $pronostico<=21:
				$pro14	=$saldo;
				break;
				
				case $pronostico>21:
				$pro22	=$saldo;
				break;
			}
			
			$total 		+=$saldo;
			$total1		+=$pro1;
			$total8		+=$pro8;
			$total14	+=$pro14;
			$total22	+=$pro22;
		
			echo'
			<tr>
				<td width="35%" class="sinBordes"  align="right">';
					echo $compra->nombre.' | '.substr($compra->fechaCompra,0,10);
				echo'</td>

				<td width="13%" class="sinBordes" align="right">$'.number_format($saldo,2).'</td>
				<td width="13%" class="sinBordes" align="right">$'.number_format($pro1,2).'</td>
				<td width="13%" class="sinBordes" align="right">$'.number_format($pro8,2).'</td>
				<td width="13%" class="sinBordes" align="right">$'.number_format($pro14,2).'</td>
				<td width="13%" class="sinBordes" align="right">$'.number_format($pro22,2).'</td>
			</tr>';
		}
		
		echo '
		<tr>
			<td colspan=""></td>
			<td width="13%" align="right" class="totales">$'.number_format($total,2).'</td>
			<td width="13%" align="right" class="totales">$'.number_format($total1,2).'</td>
			<td width="13%" align="right" class="totales">$'.number_format($total8,2).'</td>
			<td width="13%" align="right" class="totales">$'.number_format($total14,2).'</td>
			<td width="13%" align="right" class="totales">$'.number_format($total22,2).'</td>
			
		</tr>';
	
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de pronostico</div>';
}
?>


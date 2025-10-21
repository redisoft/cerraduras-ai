<?php
$this->load->view('reportes/flujoEfectivo/encabezado');

echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="4">
		SALDOS INICIALES EN BANCOS
		</th>
	</tr>';

$saldoInicial	=0;
$i	=1;

foreach($cuentas as $row)
{
	$ingresos		=$this->bancos->obtenerIngresosCuentaInicial($row->idCuenta,$mes,$anio);
	$egresos		=$this->bancos->obtenerEgresosCuentaInicial($row->idCuenta,$mes,$anio);
	$saldo			=$ingresos-$egresos;
	$saldoInicial	+=$saldo;
	
	$estilo		=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
	echo '
	<tr '.$estilo.'>
		<td width="50%">'.$row->nombre.' CTA '.$row->cuenta.'</td>
		<td width="20%">SALDO INICIAL</td>
		<td width="15%" align="right">$'.number_format($saldo,2).'</td>
		<td width="15%"></td>
	</tr>';
	
	$i++;
}


echo '
	<tr>
		<td class="totales" align="left" colspan="3">SUMA TOTAL DE SALDOS INICIALES</td>
		<td class="totales" align="right" >$'.number_format($saldoInicial,2).'</td>
	</tr>';

#ENTRADAS EN PRODUCTOS
$entradas	=$this->administracion->obtenerEntradaProductos($mes,$anio);

echo '
	<tr>
		<th colspan="4">ENTRADA A BANCOS O CAJA</th>
	</tr>';

$totalEntradas	=0;
foreach($entradas as $row)
{
	$estilo		=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
	$totalEntradas	+=$row->pago;
	
	echo '
	<tr '.$estilo.'>
		<td>'.$row->producto.'</td>
		<td></td>
		<td align="right">$'.number_format($row->pago,2).'</td>
		<td></td>
	</tr>';
	
	$i++;
}

/*$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
$ingresos		=$this->bancos->obtenerTraspasosIngresos($mes,$anio);
$totalEntradas	+=$ingresos;
echo '
<tr '.$estilo.'>
	<td width="50%">TRASPASOS</td>
	<td width="20%"></td>
	<td width="15%" align="right">$'.number_format($ingresos,2).'</td>
	<td width="15%"></td>
</tr>';*/
	
echo '
	<tr>
		<td class="totales" align="left" colspan="3">SUMAS</td>
		<td  class="totales" align="right" >$'.number_format($totalEntradas,2).'</td>
	</tr>';

#SALIDAS EN PRODUCTOS
#$cajaChica		=$this->administracion->obtenerSalidasProductosCajaChica($mes,$anio);
$salidas		=$this->administracion->obtenerSalidasProductos($mes,$anio);
#$salidasCompras	=$this->administracion->obtenerSalidasCompras($mes,$anio);
echo '
	<tr>
		<th colspan="4">SALIDAS BANCOS / CAJA</th>
	</tr>';

$totalSalidas	=0;
		
/*echo '
<tr '.$estilo.' onclick="obtenerSalidasCajaChica()">
	<td>CAJA CHICA</td>
	<td></td>
	<td align="right">$'.number_format($cajaChica->pago,2).'</td>
	<td></td>
</tr>';
	
$totalSalidas	=$cajaChica->pago;*/

foreach($salidas as $row)
{
	$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
	$retenciones	=0;#$this->administracion->obtenerRetencionesProductos($row->idDepartamento,$mes,$anio);
	$totalSalidas	+=$row->pago-$retenciones;
	echo '
	<tr '.$estilo.'>
		<td>'.$row->departamento.'</td>
		<td></td>
		<td align="right">$'.number_format($row->pago-$retenciones,2).'</td>
		<td></td>
	</tr>';
	
	$i++;
}

/*foreach($salidasCompras as $row)
{
	$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
	$totalSalidas	+=$row->pago;
	echo '
	<tr '.$estilo.'>
		<td>'.$row->producto.'</td>
		<td></td>
		<td align="right">$'.number_format($row->pago,2).'</td>
		<td></td>
	</tr>';
	
	$i++;
}


$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
$egresos		=$this->bancos->obtenerTraspasosEgresos($mes,$anio);
$totalSalidas	+=$egresos;
echo '
<tr '.$estilo.'>
	<td width="50%">TRASPASOS</td>
	<td width="20%"></td>
	<td width="15%" align="right">$'.number_format($egresos,2).'</td>
	<td width="15%"></td>
</tr>';*/

	
echo '
<tr>
	<td class="totales" align="left" colspan="3">SUMAS</td>
	<td class="totales" align="right" >$'.number_format($totalSalidas,2).'</td>
</tr>
<tr>
	<td class="totales" align="left" colspan="3">SALDO INICIAL MAS ENTRADAS MENOS SALIDAS</td>
	<td class="totales" align="right" >$'.number_format($saldoInicial+$totalEntradas-$totalSalidas,2).'</td>
</tr>';

#SALDOS FINALES
echo '
	<tr>
		<th colspan="4">SALDOS FINALES EN BANCOS</th>
	</tr>';

$saldoFinal	=0;

foreach($cuentas as $row)
{
	$estilo		=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
	$ingresos		=$this->bancos->obtenerIngresosCuentaFinal($row->idCuenta,$mes,$anio);
	$egresos		=$this->bancos->obtenerEgresosCuentaFinal($row->idCuenta,$mes,$anio);
	$saldo			=$ingresos-$egresos;
	$saldoFinal		+=$saldo;
	
	echo '
	<tr '.$estilo.'>
		<td width="50%">'.$row->nombre.' CTA '.$row->cuenta.'</td>
		<td width="20%"></td>
		<td width="15%" align="right">$'.number_format($saldo,2).'</td>
		<td width="15%"></td>
	</tr>';
	
	$i++;
}

echo '
	<tr>
		<td class="totales" align="left" colspan="3">SUMA TOTAL DE SALDOS FINALES</td>
		<td class="totales" align="right" >$'.number_format($saldoFinal,2).'</td>
	</tr>
</table>';
?>
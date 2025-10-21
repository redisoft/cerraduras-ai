<?php
$orden			= $this->input->post('orden');
#$orden			= 'asc';
$fecha			= $this->input->post('fecha');
$anio			= substr($fecha,0,4);
$mes			= substr($fecha,5,2);

$idCuenta		= $this->input->post('idCuenta');
$fin			= $this->administracion->obtenerUltimoDia($fecha.'-01');
$saldo			= 0;
$r				= 2;

$ingreso		= $this->administracion->obtenerIngresosMes($idCuenta,$mes,$anio);
$inicial		= $this->administracion->obtenerSaldoInicialCuenta($idCuenta);
$egreso			= $this->administracion->obtenerEgresosMes($idCuenta,$mes,$anio);

$movimientos	= $this->administracion->obtenerMovimientosMes($idCuenta,$mes,$anio,$orden);

$ingreso		+=$inicial;

$saldoInicio	= ($ingreso-$egreso);
$positivo		= '';
$negativo		= '';

if($saldoInicio>=0)
{
	$positivo	='$'.number_format($saldoInicio,2);
	$saldo		+=$saldoInicio;
}
else
{
	$negativo	='$'.number_format($saldoInicio,2);
	$saldo		-=($saldoInicio)*(-1);
}
	
echo'
<script>
	$("#tablaReporte tr:even").addClass("sombreado");
	$("#tablaReporte tr:odd").addClass("sinSombra");
</script>
<div id="generandoReporte"></div>
<table class="admintable" width="100%" id="tablaReporte">
	<tr>
		<th>
			Fecha
			
			<img onclick="ordenReporteBancos('.($orden=='asc'?'\'desc\'':'\'asc\'').')" src="'.base_url().'img/'.($orden=='asc'?'ocultar':'mostrar').'.png" width="17"  style="display:none"/>
		</th>
		<th align="left">
			Concepto/Referencia
			<!--img src="'.base_url().'img/pdf.png" width="30" title="Generar PDF" 
				onclick="window.open(\''.base_url().'administracion/reporteBancosPdf/'.$mes.'/'.$anio.'/'.$idCuenta.'\')" /-->
		</th>
		<th align="left">Receptor</th>
		<th align="left">Forma pago</th>
		<th>Cargo</th>
		<th>Abono</th>
		<th>Saldo</th>
	</tr>';
	
	
	if($orden=='asc')
	{
		echo'
		<tr class="sombreado">
			<td align="center">'.obtenerFechaMesCorto($anio.'-'.$mes.'-01').'</td>
			<td align="left">Saldo inicial</td>
			<td align="left"></td>
			<td align="left"></td>
			<td align="right">'.$positivo.' </td>
			<td align="right">'.$negativo.'</td>
			<td align="right">$'.number_format($saldoInicio,2).' </td>
		</tr>';
	}
	


foreach($movimientos as $row)
{
	$estilo		= "";
	
	echo'
	<tr '.$estilo.'>
		<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
		<td align="left">'.$row->producto.'</td>
		<td align="left">'.$row->nombreReceptor.'</td>
		<td align="left">'.$row->formaPago.'</td>
		<td align="right">'.($row->movimiento=='ingreso'?'$'.number_format($row->pago,2):'').'</td>
		<td align="right">'.($row->movimiento=='egreso'?'$'.number_format($row->pago,2):'').'</td>
		<td align="right">$'.number_format($row->movimiento=='ingreso'?$saldo+=$row->pago:$saldo-=$row->pago,2).' </td>
	</tr>';
}

if($orden=='desc')
{
	echo'
	<tr class="sombreado">
		<td align="center">'.obtenerFechaMesCorto($anio.'-'.$mes.'-01').'</td>
		<td align="left">Saldo inicial</td>
		<td align="left"></td>
		<td align="left"></td>
		<td align="right">'.$positivo.' </td>
		<td align="right">'.$negativo.'</td>
		<td align="right">$'.number_format($saldo,2).' </td>
	</tr>';
}

for($i=1;$i<1;$i++)
{
	$estilo		= "";
	$ingresos	= $this->administracion->obtenerIngresosDia($idCuenta,$fecha.'-'.$i);
	$egresos	= $this->administracion->obtenerEgresosDia($idCuenta,$fecha.'-'.$i);
	
	foreach($ingresos as $row)
	{
		#$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
		echo'
		<tr '.$estilo.'>
			<td align="center">
				'.obtenerFechaMesCorto($row->fecha).'
				
				
			</td>
			<td align="left">'.$row->producto.'</td>
			<td align="left">'.$row->nombreReceptor.'</td>
			<td align="left">'.$row->formaPago.'</td>
			<td align="right">$'.number_format($row->pago,2).' </td>
			<td align="right"></td>
			<td align="right">$'.number_format($saldo+=$row->pago,2).' </td>
		</tr>';
		
		$r++;
	}
	
	foreach($egresos as $row)
	{
	
		echo'
		<tr '.$estilo.'>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="left">'.$row->producto.'</td>
			<td align="left">'.$row->nombreReceptor.'</td>
			<td align="left">'.$row->formaPago.'</td>
			<td align="right"></td>
			<td align="right">$'.number_format($row->pago,2).' </td>
			<td align="right">$'.number_format($saldo-=$row->pago,2).' </td>
		</tr>';
		
		$r++;
	}
}

echo '</table>

<script>
$("#lblSaldoFinal").html("Saldo final: $ '.number_format($saldo,decimales).'");
</script>';
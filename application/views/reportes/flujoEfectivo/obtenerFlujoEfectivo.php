<?php
$fecha	=$this->input->post('fecha');
$anio	=substr($fecha,0,4);
$mes	=substr($fecha,5,2);

#SALDOS INICIALES
$cuentas	=$this->bancos->obtenerCuentas();

/*<img src="'.base_url().'img/excel.png" width="22" title="Generar Excel Flujo" 
				onclick="generarExcelFlujo(\''.$mes.'\',\''.$anio.'\')" />*/
echo '
<div id="generandoReporte"></div>
<table class="admintable" width="100%">
	<tr>
		<th align="right" style="border-right:none">
			SALDOS INICIALES EN BANCOS
		</th>
		<th style="border-right:none; border-left:none">
		<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" title="Generar PDF Flujo" onclick="window.open(\''.base_url().'reportes/reporteFlujo/'.$mes.'/'.$anio.'\')" />
		&nbsp;&nbsp;
		<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" width="22" title="Generar Excel Flujo" onclick="excelFlujoEfectivo(\''.$mes.'\',\''.$anio.'\')" />
		<br />
		
		<a>PDF</a>
		<a>Excel</a>';
			
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdfReporte\');
					desactivarBotonSistema(\'btnExportarExcelReporte\');
				</script>';
			}
			
		echo'    
		</th>
		<th colspan="2" style="border-left:none"></th>
	</tr>';

$saldoInicial	=0;
$i				=1;

foreach($cuentas as $row)
{
	$ingresos		=$this->bancos->obtenerIngresosCuentaInicial($row->idCuenta,$mes,$anio);
	$egresos		=$this->bancos->obtenerEgresosCuentaInicial($row->idCuenta,$mes,$anio);
	
	#$ingresos		=$this->bancos->obtenerIngresosCuentaMes($row->idCuenta,$mes,$anio);
	#$egresos		=$this->bancos->obtenerEgresosCuentaMes($row->idCuenta,$mes,$anio);
	
	$saldo			=$ingresos-$egresos;
	$saldoInicial	+=$saldo;
	
	$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
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
	
	#onclick="obtenerEntradasFlujo('.$row->idProducto.')"
	echo '
	<tr '.$estilo.' >
		<td>'.$row->producto.'</td>
		<td></td>
		<td align="right">$'.number_format($row->pago,2).'</td>
		<td></td>
	</tr>';
	
	$i++;
}

/*$ingresos		=$this->bancos->obtenerTraspasosIngresos($mes,$anio); 
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
</tr>';*/

#$totalSalidas	=$cajaChica->pago;
	
foreach($salidas as $row)
{
	$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
	$retenciones	=0;#$this->administracion->obtenerRetencionesProductos($row->idDepartamento,$mes,$anio);
	#$retenciones=0;
	$totalSalidas	+=$row->pago-$retenciones;
	#$totalSalidas	+=$salidaDepa;
	
	#onclick="obtenerSalidasFlujo('.$row->idDepartamento.')"
	echo '
	<tr '.$estilo.' >
		<td>'.$row->departamento.'</td>
		<td></td>
		<td align="right">$'.number_format($row->pago-$retenciones,2).'</td>
		<td></td>
	</tr>';
	
	$i++;
}

/*$egresos		=$this->bancos->obtenerTraspasosEgresos($mes,$anio);
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
	$estilo			=$i%2>0?"class='sinSombra'":'class="sombreado"';
	
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